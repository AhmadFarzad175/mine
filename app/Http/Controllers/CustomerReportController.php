<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:viewCustomerReport');
    }
    public function __invoke(Request $request)
    {
        $customers = DB::table('sales')
            ->join('customers', 'sales.customer_id', '=', 'customers.id')
            ->select(
                'customers.id',   // Add customers.id to select to properly group by it
                'customers.name',
                'customers.phone',
                'customers.address',
                DB::raw('SUM(sales.total_amount) as total'),
                DB::raw('SUM(sales.total_amount - sales.paid) as due')
            )
            ->groupBy('customers.id', 'customers.name', 'customers.phone', 'customers.address')
            ->get();

        return response()->json($customers);
    }
}


