<?php

namespace App\Http\Controllers;

use App\Models\Owner;
use App\Models\OwnerPickup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\OwnerPickupRequest;
use App\Http\Resources\OwnerPickupResource;
use App\Models\Currency;
use Illuminate\Auth\AuthenticationException;

class OwnerPickupController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:viewOwnerPickup')->only(['index', 'show']);
        $this->middleware('can:createOwnerPickup')->only(['store']);
        $this->middleware('can:editOwnerPickup')->only(['update']);
        $this->middleware('can:deleteOwnerPickup')->only('destroy');
    }
    private function authorizeUser(OwnerPickup $ownerPickup)
    {
        if ($ownerPickup->user_id !== auth()->id()) {
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

    // Assuming you have a search method defined in the OwnerPickup model
    $ownerpickup = OwnerPickup::where('owner_id', $request->input('owner_id'));

    if ($search) {
        $ownerpickup = $ownerpickup->search($search);
    }

    $ownerpickup = $perPage ? $ownerpickup->latest()->paginate($perPage) : $ownerpickup->latest()->get();

    return OwnerPickupResource::collection($ownerpickup);
}



    /**
     * Store a newly created resource in storage.
     */
    public function store(OwnerPickupRequest $request)
    {
        $validated = $request->validated();
        // $validated['user_id'] = Auth::id() ?? 1;
        $ownerpickup = OwnerPickup::create($validated);
        return OwnerPickupResource::make($ownerpickup);

    }


    /**
     * Display the specified resource.
     */
    public function show(OwnerPickup $ownerPickup)
    {
        return OwnerPickupResource::make($ownerPickup);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(OwnerPickupRequest $request, $id)
    {
        $ownerPickup = OwnerPickup::find($id);
        $ownerPickup->update($request->validated());
        return response()->json(['success', 'Ownerpickup updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $ownerPickup = OwnerPickup::findOrFail($id);
        if (!$ownerPickup)
        {
            return response()->json(['error' => 'OwnerPickup not found'], 404);
        }
        $ownerPickup->delete();
        return response()->json(['success', 'OwnerPickup deleted successfully']);
    }
}
