<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExpenseProductReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:viewExpenseProductReport');
    }
    public function __invoke(Request $request)
    {
    $expenseProduct = DB::table('expenses')
    ->join('expense_products', 'expenses.expense_product_id', '=', 'expense_products.id')
    ->select(
        'expense_products.name as Product',
        DB::raw('SUM(expenses.amount) as consumed')
    )
    ->groupBy('expense_products.name')
    ->get();

return response()->json($expenseProduct);

}
}
