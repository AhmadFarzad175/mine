<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerSalesReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:viewCustomerSalesReport');
    }
    public function __invoke(Request $request)
    {
    // Base query for sales summary
    $salesSummary = DB::table('sales')
        ->select(
            DB::raw('COUNT(id) as total_sales'), // Count the total number of sales
            DB::raw('SUM(total_amount) as total_amount'), // Sum of total amounts
            DB::raw('SUM(paid) as total_paid'), // Sum of paid amounts
            DB::raw('SUM(total_amount - paid) as total_due') // Sum of due amounts (total_amount - paid)
        )
        ->first(); // Get the first record as a single object

        $salesDetails = DB::table('sales')
        ->join('users', 'sales.user_id', '=', 'users.id')
        ->select(
            'sales.date',
            'sales.ref# as reference',
            'users.user_name as created_by',
            'sales.total_amount',
            'sales.paid',
            DB::raw('sales.total_amount - sales.paid as due')
        )
        ->get();

        $salesPayments = DB::table('sale_payments')
        ->select(
            'sale_payments.date',
            'sale_payments.amount',

        )->get();

    return response()->json([
        'sales_summary' => [
            'sales' => $salesSummary->total_sales,
            'total_amount' => number_format($salesSummary->total_amount, 2),
            'paid' => number_format($salesSummary->total_paid, 2),
            'due' => number_format($salesSummary->total_due, 2),
        ],
        'sales_details' => $salesDetails, //This part is not paginated
        'sale_payments' => $salesPayments,

    ]);
}

}





// return response()->json([
//     'data' => [
//         'sales_summary' => $sales->items(), //Get the items for the current page
//     ],
