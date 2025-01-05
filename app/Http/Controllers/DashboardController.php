<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Sales;
use App\Models\Expenses;
use App\Models\Customers;
use App\Models\BillExpenses;
use App\Models\Customer;
use App\Models\OwnerPickup;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:dashboard_view');
    }

    public function index(Request $request)
    {

        // Get dates for today, the month, and the year
        $today = Carbon::today();
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();
        $startOfYear = Carbon::now()->startOfYear();
        $endOfYear = Carbon::now()->endOfYear();

        // Fetch total sales and total paid for today, grouped by currency
        $sales = Sale::with('currency')
            ->selectRaw('SUM(total_amount) as total_sales, SUM(paid) as total_paid, currency_id')
            ->whereDate('date', $today)
            ->groupBy('currency_id')
            ->get();

        $todaySales = $sales->map(function ($sale) {
            return "{$sale->total_sales} {$sale->currency->code}";
        })->implode(' | ');

        // Fetch total expenses for today, grouped by currency
        $expenses = Expenses::with('currency')
            ->selectRaw('SUM(amount) as total_expenses, currency_id')
            ->whereDate('date', $today)
            ->groupBy('currency_id')
            ->get();

        // Fetch total billable expenses for today, grouped by currency
        $billExpenses = BillExpenses::with('currency')
            ->selectRaw('SUM(amount) as total_bill_expenses, SUM(paid) as total_paid, currency_id')
            ->whereDate('date', $today)
            ->groupBy('currency_id')
            ->get();

        // Calculate today's total expenses by currency
        $todayExpenses = $billExpenses->map(function ($billExpense) use ($expenses) {
            $expense = $expenses->firstWhere('currency_id', $billExpense->currency_id);
            $totalExpenses = $expense ? $expense->total_expenses : 0;
            $allExpenseAmount = $billExpense->total_bill_expenses + $totalExpenses;
            return "{$allExpenseAmount} {$billExpense->currency->code}";
        })->implode(' | ');

        // Calculate today's profit by currency
        $todayProfit = $sales->map(function ($sale) use ($expenses, $billExpenses) {
            $expense = $expenses->firstWhere('currency_id', $sale->currency_id);
            $billExpense = $billExpenses->firstWhere('currency_id', $sale->currency_id);
            $totalExpenses = ($expense ? $expense->total_expenses : 0) + ($billExpense ? $billExpense->total_bill_expenses : 0);
            $profitAmount = $sale->total_sales - $totalExpenses;
            return "{$profitAmount} {$sale->currency->code}";
        })->implode(' | ');

        // Fetch total pickups for today, grouped by currency
        $pickups = OwnerPickup::with('currency')
            ->selectRaw('SUM(amount) as total_pickups, currency_id')
            ->whereDate('date', $today)
            ->groupBy('currency_id')
            ->get();

        $todayPickup = $pickups->map(function ($pickup) {
            return "{$pickup->total_pickups} {$pickup->currency->code}";
        })->implode(' | ');


        // Fetch top 5 customers by sales amount
        $customers = Customer::with(['sales.currency'])
            ->get()
            ->sortByDesc(function ($customer) {
                return $customer->sales->sum('total_amount');
            })
            ->take(5);

        $data = [];
        foreach ($customers as $customer) {
            $totalSales = $customer->sales->count();
            $totalAmounts = $customer->sales->groupBy('currency_id')->map(function ($sales) {
                return [
                    'total' => $sales->sum('total_amount'),
                    'total_paid' => $sales->sum('paid'),
                    'currency' => $sales->first()->currency->code
                ];
            });

            $item = [
                'id' => $customer->id,
                'customer_name' => $customer->name,
                'phone' => $customer->phone,
                'address' => $customer->address,
                'total_sales' => $totalSales,
                'total_amount' => $totalAmounts->map(function ($total) {
                    return number_format($total['total'], 2) . ' ' . $total['currency'];
                })->implode(' | '),
                'total_paid' => $totalAmounts->map(function ($total) {
                    return number_format($total['total_paid'], 2) . ' ' . $total['currency'];
                })->implode(' | '),
                'total_due' => $totalAmounts->map(function ($total) {
                    return number_format($total['total'] - $total['total_paid'], 2) . ' ' . $total['currency'];
                })->implode(' | ')
            ];

            $data[] = $item;
        }

        $allMonths = array_fill(1, 12, 0); // Initialize array for all 12 months

// Fetch earnings
$earnings = Sale::select(
    DB::raw('MONTH(date) as month'),
    DB::raw('SUM(total_amount) as total_sales')
)
    ->whereBetween('date', [$startOfYear, $endOfYear])
    ->groupBy('month')
    ->pluck('total_sales', 'month')
    ->toArray();

// Fetch expenses (union of two tables)
$expenses = DB::table('expenses')
    ->select(
        DB::raw('MONTH(date) as month'),
        DB::raw('SUM(amount) as total_expenses')
    )
    ->whereBetween('date', [$startOfYear, $endOfYear])
    ->groupBy('month')
    ->unionAll(
        DB::table('bill_expenses')
            ->select(
                DB::raw('MONTH(date) as month'),
                DB::raw('SUM(amount) as total_expenses')
            )
            ->whereBetween('date', [$startOfYear, $endOfYear])
            ->groupBy('month')
    )
    ->get()
    ->groupBy('month')
    ->map(function ($data) {
        return $data->sum('total_expenses');
    })
    ->toArray();

// Calculate monthly profits (Earnings - Expenses)
$monthlyProfit = [];
foreach ($earnings as $month => $earning) {
    $expense = $expenses[$month] ?? 0; // Use null coalescing operator
    $monthlyProfit[$month] = $earning - $expense;
}

// Include months with expenses but no earnings
foreach ($expenses as $month => $expense) {
    if (!isset($monthlyProfit[$month])) {
        $monthlyProfit[$month] = 0 - $expense; // Handle no earnings for a month
    }
}

// Ensure all 12 months are included and sorted
$monthlyProfit = array_values(array_replace($allMonths, $monthlyProfit));
// ksort($monthlyProfit); // Sort by month keys (1-12)

// Merge earnings and expenses into final structure
$earningExpenses = [
    'earnings' => array_values(array_replace($allMonths, $earnings)),
    'expenses' => array_values(array_replace($allMonths, $expenses)),
];



        // Get today’s expenses
        $todayExpenses = $this->aggregateExpenses($today, $today);

        // Get this month’s expenses
        $monthExpenses = $this->aggregateExpenses($startOfMonth, $endOfMonth);

        // Get this year’s expenses
        $yearExpenses = $this->aggregateExpenses($startOfYear, $endOfYear);

        // Return the data as JSON
        return response()->json([
            'today_sales' => $todaySales,
            'todayExpenses' => $todayExpenses,
            'monthExpenses' => $monthExpenses,
            'yearExpenses' => $yearExpenses,
            'monthlyProfit' => $monthlyProfit,
            'today_profit' => $todayProfit,
            'today_pickup' => $todayPickup,
            'top_customers' => $data,
            'monthly_earning_expenses' => $earningExpenses

        ]);
    }




    private function aggregateExpenses($startDate, $endDate)
    {
        $expenseQuery = DB::table('expenses')
            ->selectRaw('expense_category_id, SUM(amount) as expAmount')
            ->whereNull('deleted_at')
            ->whereBetween('date', [$startDate, $endDate])
            ->groupBy('expense_category_id');

        $billQuery = DB::table('bill_expenses')
            ->selectRaw('expense_category_id, SUM(amount) as billAmount')
            ->whereNull('deleted_at')
            ->whereBetween('date', [$startDate, $endDate])
            ->groupBy('expense_category_id');

        $expenses = DB::table('expense_categories')
            ->selectRaw('
            expense_categories.id,
            expense_categories.name,
            COALESCE(expenses_sum.expAmount, 0) as expAmount,
            COALESCE(bill_expenses_sum.billAmount, 0) as billAmount,
            COALESCE(expenses_sum.expAmount, 0) + COALESCE(bill_expenses_sum.billAmount, 0) as total
        ')
            ->leftJoinSub($expenseQuery, 'expenses_sum', function ($join) {
                $join->on('expense_categories.id', '=', 'expenses_sum.expense_category_id');
            })
            ->leftJoinSub($billQuery, 'bill_expenses_sum', function ($join) {
                $join->on('expense_categories.id', '=', 'bill_expenses_sum.expense_category_id');
            })
            ->groupBy('expense_categories.id', 'expense_categories.name', 'expenses_sum.expAmount', 'bill_expenses_sum.billAmount')
            ->get();

        // Calculate total and percentage
        $total = $expenses->sum('total');
        $expenses = $expenses->map(function ($expense) use ($total) {
            $expense->percentage = ($total > 0) ? ($expense->total / $total) * 100 : 0;
            return $expense;
        });

        return [
            'expenses' => $expenses,
            'total' => $total,
        ];
    }
}
