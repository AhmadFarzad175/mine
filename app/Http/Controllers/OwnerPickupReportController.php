<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OwnerPickupReportController extends Controller
{

    
    public function __construct()
    {
        $this->middleware('can:viewOwnerPickupReport');
    }
    public function __invoke(Request $request)
    {
        $ownerPickups = DB::table('owner_pickups')
            ->join('owners', 'owner_pickups.owner_id', '=', 'owners.id')
            ->select(
                'owners.name as owner_name',
                DB::raw('SUM(owner_pickups.amount) as total_pickup')
            )
            ->groupBy('owners.name')
            ->get();

        return response()->json($ownerPickups);
    }
}
