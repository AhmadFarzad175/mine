<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExpenseCategoryReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:viewExpenseCategoryReport');
    }

    public function __invoke(Request $request)
    {
        // Query to get the total consumed amount for each product category
        $expenses = DB::table('expenses')
            ->join('expense_categories', 'expenses.expense_category_id', '=', 'expense_categories.id')
            ->select(
                'expense_categories.name as Category',
                DB::raw('SUM(expenses.amount) as consumed')
            )
            ->groupBy('expense_categories.name')
            ->get();

        // Return the result as a JSON response or pass it to a view
        return response()->json($expenses);
    }
}
