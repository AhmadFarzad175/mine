<?php

namespace App\Http\Controllers;

use App\Models\Owner;
use App\Models\Stakeholder;
use Illuminate\Http\Request;
use App\Traits\ImageManipulation;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\OwnerRequest;
use App\Http\Resources\OwnerResource;
use App\Http\Resources\StakeholderResource;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Auth\AuthenticationException;

class OwnerController extends Controller
{
    use ImageManipulation;
    public function __construct()
    {
        $this->middleware('can:viewOwner')->only(['index', 'show']);
        $this->middleware('can:createOwner')->only(['store']);
        $this->middleware('can:editOwner')->only(['update']);
        $this->middleware('can:deleteOwner')->only('destroy');
    }
    private function authorizeUser(Owner $owner)
    {
        if ($owner->user_id !== auth()->id()) {
            throw new AuthenticationException('you must be logged in');
        }
    }

    // public function index(Request $request)
    // {
    //     $perPage = $request->input('perPage', 10);
    //     $search = request()->input('search');
    //     $stakeholder = Stakeholder::where('type', 'Owner')->search($search)->latest()->paginate($perPage);
        

    //     return StakeholderResource::collection($stakeholder);
    // }

    /**
     * Display a listing of the resource.
     */
//     public function index(Request $request)
//     {
//         $perPage = $request->input('perPage', 10);
//         $search = request()->input('search');
//         $owners = Owner::with(['ownerPickups.currency'])->search($search);
//         $owners = $perPage ? $owners->latest()->paginate($perPage) : $owners->latest()->get();


//         return OwnerResource::collection($owners);
//     }
//     // public function index(Request $request)
//     // {
//     //     $perPage = $request->input('perPage', 10);
//     //     $search = $request->input('search');

//     //     // Build query using joins to get the necessary data
//     //     $owners = DB::table('owners')
//     //         ->leftJoin('owner_pickups', 'owners.id', '=', 'owner_pickups.owner_id')
//     //         ->leftJoin('currencies', 'owner_pickups.currency_id', '=', 'currencies.id')
//     //         ->select(
//     //             'owners.id',
//     //             'owners.name',
//     //             'owners.phone',
//     //             'currencies.code AS currency_code',
//     //             DB::raw('SUM(owner_pickups.amount) AS total_amount') // Sum of pickups per currency
//     //         )
//     //         ->when($search, function ($query) use ($search) {
//     //             $query->where('owners.name', 'like', '%' . $search . '%')
//     //                   ->orWhere('owners.phone', 'like', '%' . $search . '%');
//     //         })
//     //         ->groupBy('owners.id', 'owners.name', 'owners.phone', 'currencies.id', 'currencies.code')
//     //         ->orderBy('owners.id')
//     //         ->paginate($perPage);

//     //     // Transform results to group withdrawals by currency
//     //     $groupedOwners = [];
//     //     foreach ($owners as $pickup) {
//     //         $ownerId = $pickup->id;
//     //         if (!isset($groupedOwners[$ownerId])) {
//     //             $groupedOwners[$ownerId] = [
//     //                 'id' => $pickup->id,
//     //                 'name' => $pickup->name,
//     //                 'phone' => $pickup->phone,
//     //                 'withdrawals_by_currency' => [],
//     //             ];
//     //         }
//     //         $groupedOwners[$ownerId]['withdrawals_by_currency'][] = [
//     //             'currency' => $pickup->currency_code,
//     //             'total_amount' => $pickup->total_amount,
//     //         ];
//     //     }

//     //     return response()->json(array_values($groupedOwners));
//     // }





//     public function store(OwnerRequest $request)
//     {
//         $validated = $request->validated();
//         $validated['user_id'] = 2;

//         // store the image if exists
//         $request->hasFile('image') ? $this->storeImage($request, $validated, "owners", 'image') : null;


//         $owner = Owner::create($validated);
//         return new OwnerResource($owner);
//     }

//     // public function show($id)
//     // {
//     //     $owner = Owner::findOrFail($id);
//     //     // dd($owner);
//     //     return OwnerResource::make($owner);
//     // }

//     public function show($id)
// {
//     // Fetch the owner's pickups grouped by currency
//    $ownerPickups = DB::table('owners')
//         ->leftJoin('owner_pickups', 'owners.id', '=', 'owner_pickups.owner_id')
//         ->leftJoin('currencies', 'owner_pickups.currency_id', '=', 'currencies.id')
//         ->select(
//             'owners.id',
//             'owners.name',
//             'owners.phone',
//             'currencies.code AS currency_code',
//             DB::raw('SUM(owner_pickups.amount) AS total_amount')
//         )
//         ->where('owners.id', $id)
//         ->groupBy('owners.id', 'owners.name', 'owners.phone', 'currencies.code',)
//         ->get();

//     // Check if the owner exists
//     if ($ownerPickups->isEmpty()) {
//         return response()->json(['message' => 'Owner not found'], 404);
//     }

//     // Group withdrawals by currency for this owner
//     $ownerData = [
//         'id' => $ownerPickups->first()->id,
//         'name' => $ownerPickups->first()->name,
//         'phone' => $ownerPickups->first()->phone,
//         'withdrawals_by_currency' => [],
//     ];

//     foreach ($ownerPickups as $pickup) {
//         $ownerData['withdrawals_by_currency'][] = [
//             'currency' => $pickup->currency_code,
//             'total_amount' => $pickup->total_amount,
//         ];
//     }

//     return response()->json($ownerData);
// }

//     public function update(OwnerRequest $request, $id)
//     {
//         // Find the owner
//         $owner = Owner::findOrFail($id);

//         // Validate the request data
//         $validated = $request->validated();

//         // Handle image update
//         $this->updateImage($owner, $request, $validated, 'owners', 'image');
//         // Update the owner with validated data
//         $owner->update($validated);


//         // Return the updated resource
//         return new OwnerResource($owner);
//     }


//     public function destroy($id)
//     {
//         $owner = Owner::findOrFail($id);
//         $this->deleteImage($owner, 'owners', 'image');
//         $owner->delete();
//         return response()->noContent();
//     }
}
