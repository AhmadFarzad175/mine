<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Traits\ImageManipulation;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use Spatie\Permission\Models\Role;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\AuthenticationException;

class UsersController extends Controller
{
    use ImageManipulation;

    public function __construct()
    {
        $this->middleware('can:viewUser')->only(['index', 'show']);
        $this->middleware('can:createUser')->only(['store']);
        $this->middleware('can:editUser')->only(['update']);
        $this->middleware('can:deleteUser')->only('destroy');
    }
    private function authorizeUser(User $user)
    {
        if ($user->user_id !== auth()->id()) {
            throw new AuthenticationException('you must be logged in');
        }
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->input('perPage');
        $search = $request->input('search');

        $users = User::query()->search($search);

        $users = $perPage ? $users->latest()->paginate($perPage) : $users->latest()->get();
        return UserResource::collection($users);
    }


    public function store(UserRequest $request)
    {
        $validated = $request->validated();

        $role = Role::where("id", $request['role'])->first();
        // Handle image upload, if present
        $request->hasFile('image') ? $this->storeImage($request, $validated, "users", 'image') : null;


        $validated['password'] = Hash::make($validated['password']);

        // Create the user with validated data
        $user = User::create($validated);

        // Assign the role to the user
        $user->assignRole($role);

        return response()->json(['message' => 'User created successfully'], 200);
        // return new UserResource($user);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        return UserResource::make(User::find($id));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, $id)
    {



        $user = User::findOrFail($id);
        $validated = $request->validated();

        $this->updateImage($user, $request, $validated, 'users', 'image');
          // Update the user's role
          $validated['password'] = Hash::make($validated['password']);
    // Update the user's role if it is provided in the request
   if ($request->has('role')) {
    $user->roles()->sync([$request->role]); // Pass an array of role IDs.
}

        $user->update($validated);


        return response()->json(['success' => 'User updated successfully']);


        // Return the updated resource
    }
    // return new UserResource($user);

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $user = User::find($id);
        $this->deleteImage($user, 'users', 'image');
        $user->delete();
        return response()->noContent();
    }

    
    public function switchUser(Request $request, User $user)
    {
        // HERE WE SWITCH THE STATUS OF USER
        $user->update($request->validate(['status' => 'boolean']));
        return new UserResource($user);
    }

    public function updateUser(Request $request, User $user)
    {
        
        $validated = $request->validated();

        // update image.
        $this->updateImage($user, $request, $validated, 'users', "image"); // Update the user's role
      
        
        // Update other user attributes
        $user->update($validated);
        return new UserResource($user);
    }
}
