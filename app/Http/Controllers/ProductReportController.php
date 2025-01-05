<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:viewProductReport');
    }
    public function __invoke(Request $request)
    {
        // Query to get the total consumed amount for each product category
        $productConsumption = DB::table('expenses')
            ->join('expense_products', 'expenses.expense_product', '=', 'expense_products.id')
            ->select(
                'expense_products.name as Product',
                DB::raw('SUM(expenses.amount) as consumed')
            )
            ->groupBy('expense_products.name')
            ->get();

        // Return the result as a JSON response or pass it to a view
        return response()->json($productConsumption);
    }
}
