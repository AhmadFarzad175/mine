<?php

namespace App\Http\Controllers;

use App\Models\Expenses;
use App\Models\OwnerPickup;
use App\Models\Sale;
use App\Models\sales;
use Illuminate\Http\Request;

class ProfitLossReportController extends Controller
{
    public function __construct(){
        $this->middleware('can:viewProfitLossReport');
    }
    public function __invoke(Request $request)
    {
        $startDate = $request->input('start_Date');
        $endDate  = $request->input('end_Date');


        $totalSales = Sale::whereBetween('created_at',[$startDate,$endDate])->sum('total_amount');
        $totalPickups = OwnerPickup::whereBetween('created_at',[$startDate,$endDate])->sum('amount');
        $totalExpenses = Expenses::whereBetween('created_at',[$startDate,$endDate])->sum('amount');

        $netProfit = $totalSales - ($totalExpenses + $totalPickups);

        // return view('reports.profit_loss', compact('totalSales', 'totalExpenses', 'totalPickups', 'netProfit'));
        // return response()->json($productConsumption);
        return response()->json([
            'total_sales' => $totalSales,
            'total_expenses' => $totalExpenses,
            'total_pickups' => $totalPickups,
            'net_profit' => $netProfit,
        ]);



    }
}


// 2026-02-15

// class ProfitLossReportController extends Controller
// {
//     public function __invoke(Request $request)
//     {
//         $startDate = $request->input('start_Date');
//         $endDate  = $request->input('end_Date');

//         $item = []; // Initialize an empty array

//         $item['total_sales'] = sales::whereBetween('date', [$startDate, $endDate])->sum('total_amount');
//         $item['total_pickups'] = OwnerPickup::whereBetween('date', [$startDate, $endDate])->sum('amount');
//         $item['total_expenses'] = Expenses::whereBetween('date', [$startDate, $endDate])->sum('amount');

//         $item['net_profit'] = $item['total_sales'] - ($item['total_expenses'] + $item['total_pickups']);

//         return response()->json($item);
//     }
// }
