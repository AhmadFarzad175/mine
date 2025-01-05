<?php

namespace App\Http\Controllers;

use App\Models\BillExpenseDetails;
use App\Models\BillExpenses;
use App\Models\Customer;
use App\Models\Customers;
use App\Models\ExpensePayments;
use App\Models\Expenses;
use App\Models\OwnerPickup;
use App\Models\Sale;
use App\Models\SalePayment;
use App\Models\Sales;
use App\Models\Suppliers;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{


    public function profitAndLoss(Request $request)
    {
        $from = $request->input('startDate');
        $to = $request->input('endDate');
    
        // Fetch total sales and total paid grouped by currency
        $sales = Sale::with('currency')
            ->selectRaw('SUM(total_amount) as total_sales, SUM(paid) as total_paid, currency_id')
            ->whereBetween('sales.date', [$from, $to])
            ->groupBy('currency_id')
            ->get();
    
        // Fetch total pickups grouped by currency
        $pickups = OwnerPickup::with('currency')
            ->selectRaw('SUM(amount) as total_pickups, currency_id')
            ->whereBetween('date', [$from, $to])
            ->groupBy('currency_id')
            ->get();
    
        // Fetch total expenses grouped by currency
        $expenses = Expenses::with('currency')
            ->selectRaw('SUM(amount) as total_expenses, currency_id')
            ->whereBetween('date', [$from, $to])
            ->groupBy('currency_id')
            ->get();
    
        // Fetch total billable expenses grouped by currency
        $billExpenses = BillExpenses::with('currency')
            ->selectRaw('SUM(amount) as total_bill_expenses, SUM(paid) as total_paid, currency_id')
            ->whereBetween('date', [$from, $to])
            ->groupBy('currency_id')
            ->get();
    
        // Fetch total sale payments grouped by currency
        $salePayments = SalePayment::with('currency')
            ->selectRaw('SUM(amount) as total_sale_payments, currency_id')
            ->whereBetween('date', [$from, $to])
            ->groupBy('currency_id')
            ->get();
    
        // Fetch total expense payments grouped by currency
        $expensePayments = ExpensePayments::with('currency')
            ->selectRaw('SUM(amount) as total_expense_payments, currency_id')
            ->whereBetween('date', [$from, $to])
            ->groupBy('currency_id')
            ->get();
    
        // Calculate total sales due (total_amount - paid)
        $totalSalesDue = $sales->map(function ($sale) {
            $due = $sale->total_sales - $sale->total_paid;
            return "{$due} {$sale->currency->code}";
        })->implode(' | ');
    
        // Calculate total billable expenses due (tamount - paid)
        $totalBillExpensesDue = $billExpenses->map(function ($billExpense) {
            $due = $billExpense->total_bill_expenses - $billExpense->total_paid;
            return "{$due} {$billExpense->currency->code}";
        })->implode(' | ');
    
        // Prepare the results
        $totalSales = $sales->map(function ($sale) {
            return "{$sale->total_sales} {$sale->currency->code}";
        })->implode(' | ');
    
        $totalPickups = $pickups->map(function ($pickup) {
            return "{$pickup->total_pickups} {$pickup->currency->code}";
        })->implode(' | ');
    
        $totalExpenses = $expenses->map(function ($expense) {
            return "{$expense->total_expenses} {$expense->currency->code}";
        })->implode(' | ');
    
        $totalBillExpenses = $billExpenses->map(function ($billExpense) {
            return "{$billExpense->total_bill_expenses} {$billExpense->currency->code}";
        })->implode(' | ');
    
        $totalSalePayments = $salePayments->map(function ($payment) {
            return "{$payment->total_sale_payments} {$payment->currency->code}";
        })->implode(' | ');
    
        $totalExpensePayments = $expensePayments->map(function ($payment) {
            return "{$payment->total_expense_payments} {$payment->currency->code}";
        })->implode(' | ');
    
        // 5. Total Loan Payments Sent
        $loanPaymentsSent = DB::table('loan_payment_sents')
            ->join('currencies', 'loan_payment_sents.currency_id', '=', 'currencies.id')
            ->selectRaw('SUM(loan_payment_sents.amount) as total_loan_sent, currencies.code as currency_code')
            ->whereBetween('loan_payment_sents.payment_date', [$from, $to])
            ->groupBy('loan_payment_sents.currency_id', 'currencies.code')
            ->get();
    
        $totalLoanPaymentsSent = $loanPaymentsSent->map(function ($paymentSent) {
            return "{$paymentSent->total_loan_sent} {$paymentSent->currency_code}";
        })->implode(' | ');
    
        // 6. Total Loan Payments Received
        $loanPaymentsReceived = DB::table('loan_payment_receiveds')
            ->join('currencies', 'loan_payment_receiveds.currency_id', '=', 'currencies.id')
            ->selectRaw('SUM(loan_payment_receiveds.amount) as total_loan_received, currencies.code as currency_code')
            ->whereBetween('loan_payment_receiveds.payment_date', [$from, $to])
            ->groupBy('loan_payment_receiveds.currency_id', 'currencies.code')
            ->get();
    
        $totalLoanPaymentsReceived = $loanPaymentsReceived->map(function ($paymentReceived) {
            return "{$paymentReceived->total_loan_received} {$paymentReceived->currency_code}";
        })->implode(' | ');
    
        // Calculate allExpense (totalBillExpenses + totalExpenses) grouped by currency
        $allExpense = $billExpenses->map(function ($billExpense) use ($expenses) {
            $expense = $expenses->firstWhere('currency_id', $billExpense->currency_id);
            $totalExpenses = $expense ? $expense->total_expenses : 0;
            $allExpenseAmount = $billExpense->total_bill_expenses + $totalExpenses;
            return "{$allExpenseAmount} {$billExpense->currency->code}";
        })->implode(' | ');
    
        // Return the results including allExpense
        return response()->json([
            'totalSales' => $totalSales,
            'totalPickups' => $totalPickups,
            'noneBillExpenses' => $totalExpenses,
            'totalBillExpenses' => $totalBillExpenses,
            'totalSalePayments' => $totalSalePayments,
            'totalExpensePayments' => $totalExpensePayments,
            'TotalLoanPaymentsSent' => $totalLoanPaymentsSent,
            'TotalLoanPaymentsReceived' => $totalLoanPaymentsReceived,
            'totalSalesDue' => $totalSalesDue,
            'totalBillExpensesDue' => $totalBillExpensesDue,
            'allExpense' => $allExpense  // Include allExpense in the response
        ]);
    }
    
    




    public function customerReports(Request $request)
    {
        // Validate incoming request parameters
        $perPage = $request->input('limit', 15); // Default to 15 if not provided
        $search = $request->input('search', '');

        // Fetch customers with their sales using pagination
        $customers = Customer::with(['sales.currency']) // Eager load sales and associated currency
            ->when($search, function ($query) use ($search) {
                return $query->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('phone', 'LIKE', "%{$search}%");
            })
            ->paginate($perPage); // Use paginate instead of limit and offset

        $data = [];

        foreach ($customers as $customer) {
            $totalSales = $customer->sales->count();

            // Calculate total amount and total paid grouped by currency
            $totalAmounts = $customer->sales->groupBy('currency_id')->map(function ($sales) {
                return [
                    'total' => $sales->sum('total_amount'),
                    'total_paid' => $sales->sum('paid'),
                    'currency' => $sales->first()->currency->code // Get currency code from the first sale
                ];
            });

            // Prepare item data
            $item = [
                'id'=> $customer->id,
                'customer_name' => $customer->name,
                'phone' => $customer->phone,
                'address'=>$customer->address,
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

        return response()->json([
            'data' => $data,
            'current_page' => $customers->currentPage(),
            'last_page' => $customers->lastPage(),
            'total' => $customers->total(), // Total number of customers for pagination
            'per_page' => $customers->perPage(), // Items per page
        ]);
    }


    public function customerDetails($id)
    {
        $customerId= $id;
        // Validate incoming request parameter
        $customer = Customer::with(['sales.currency', 'sales.salesPayments.currency']) // Eager load sales and salesPayments with currency
            ->findOrFail($customerId); // Fetch the customer by ID or fail

            // Prepare sales data
            $salesData = $customer->sales->map(function ($sale) {
                return [
                    'date' => $sale->date,
                    'reference' => $sale->ref,
                    // 'sale_id' => $sale->id,
                    'totalAmount' => number_format($sale->total_amount, 2) . ' ' . $sale->currency->code,
                    'totalPaid' => number_format($sale->total_paid, 2) . ' ' . $sale->currency->code,
                    'totalDue' => number_format($sale->total_amount - $sale->total_paid, 2) . ' ' . $sale->currency->code, // Corrected
                    // 'salesPayments' => $sale->salesPayments->map(function ($payment) use ($sale) { // Added $sale to use in salesPayments
                    //     return [
                    //         'payment_id' => $payment->id,
                    //         'amount' => number_format($payment->amount, 2),
                    //         'sale_ref' => $sale->ref, // Accessing sale ref in payment
                    //         'currency' => $payment->currency->code,
                    //         'date' => $payment->date,
                    //         'description' => $payment->description,
                    //     ];
                    // })
                ];
            });
        
                    // Prepare all salesPayments separately
            $allSalesPayments = $customer->sales->flatMap(function ($sale) {
                return $sale->salesPayments->map(function ($payment) use ($sale) {
                    return [
                        'payment_id' => $payment->id,
                        'sale_ref' => $sale->ref, // Accessing sale ref in payment
                        'sale_id' => $payment->sale_id,
                        'amount' => number_format($payment->amount, 2). ' ' . $payment->currency->code,
                        'date' => $payment->date,
                        'description' => $payment->description,
                    ];
                });
            });

        // Calculate total amounts and dues
        $totalAmounts = $customer->sales->groupBy('currency_id')->map(function ($sales) {
            return [
                'total' => $sales->sum('total_amount'),
                'total_paid' => $sales->sum('salesPayments.amount'), // Adjust this if 'amount' isn't the correct field
                'currency' => $sales->first()->currency->code // Get currency code from the first sale
            ];
        });

        // Prepare total amounts formatted
        $totalAmountFormatted = $totalAmounts->map(function ($total) {
            return number_format($total['total'], 2) . ' ' . $total['currency'];
        })->implode(' | ');

        $totalPaidFormatted = $totalAmounts->map(function ($total) {
            return number_format($total['total_paid'], 2) . ' ' . $total['currency'];
        })->implode(' | ');

        $totalDueFormatted = $totalAmounts->map(function ($total) {
            $dueAmount = $total['total'] - $total['total_paid'];
            return number_format($dueAmount, 2) . ' ' . $total['currency'];
        })->implode(' | ');

        // Prepare customer details
        $customerDetails = [
            'customer_id' => $customer->id,
            'customer_name' => $customer->name,
            'phone' => $customer->phone,
            'sales' => $salesData,
            'total_amount' => $totalAmountFormatted,
            'total_paid' => $totalPaidFormatted,
            'total_due' => $totalDueFormatted,
            'sales_payments' => $allSalesPayments // Add all salesPayments here
        ];

        return response()->json($customerDetails);
    }


    public function SupplierReport(Request $request)
    {
        // Validate incoming request parameters
        $perPage = $request->input('limit', 15); // Default to 15 if not provided
        $search = $request->input('search', '');

        // Fetch suppliers with their bill expenses using pagination
        $suppliers = Suppliers::with(['billExpenses.currency']) // Eager load bill expenses and associated currency
            ->when($search, function ($query) use ($search) {
                return $query->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('phone', 'LIKE', "%{$search}%");
            })
            ->paginate($perPage); // Use paginate instead of limit and offset

        $data = [];

        foreach ($suppliers as $supplier) {
            $totalExpenses = $supplier->billExpenses->count();

            // Calculate total amount and total paid grouped by currency
            $totalAmounts = $supplier->billExpenses->groupBy('currency_id')->map(function ($expenses) {
                return [
                    'total' => $expenses->sum('amount'),
                    'total_paid' => $expenses->sum('paid'),
                    'currency' => $expenses->first()->currency->code // Get currency code from the first expense
                ];
            });

            // Prepare item data
            $item = [
                'id'=>$supplier->id,
                'supplier_name' => $supplier->name,
                'total_amount' => $totalAmounts->map(function ($total) {
                    return number_format($total['total'], 2) . ' ' . $total['currency'];
                })->implode(' | '),
                'total_paid' => $totalAmounts->map(function ($total) {
                    return number_format($total['total_paid'], 2) . ' ' . $total['currency'];
                })->implode(' | '),
                'total_due' => $totalAmounts->map(function ($total) {
                    return number_format($total['total'] - $total['total_paid'], 2) . ' ' . $total['currency'];
                })->implode(' | '),
                'phone' => $supplier->phone,
                'total_expenses' => $totalExpenses,
                'address' => $supplier->address,
            ];

            $data[] = $item;
        }

        return response()->json([
            'data' => $data,
            'current_page' => $suppliers->currentPage(),
            'last_page' => $suppliers->lastPage(),
            'total' => $suppliers->total(), // Total number of suppliers for pagination
            'per_page' => $suppliers->perPage(), // Items per page
        ]);
    }


    public function SupplierDetails($id)
    {
        // Fetch the supplier by ID along with their bill expenses, currency, and expense payments
        $supplier = Suppliers::with(['billExpenses.currency', 'billExpenses.expensepayment.currency'])
            ->where('id', $id)
            ->firstOrFail(); // Throw a 404 if not found
    
        // Prepare total calculations for bill expenses grouped by currency
        $totalAmounts = $supplier->billExpenses->groupBy('currency_id')->map(function ($expenses) {
            return [
                'total' => $expenses->sum('amount'),
                'total_paid' => $expenses->sum('paid'),
                'currency' => $expenses->first()->currency->code // Get currency code from the first expense
            ];
        });
    
        // Prepare detailed bill expenses and their payments
        $billExpensesData = $supplier->billExpenses->map(function ($expense) {
            return [
                'id' => $expense->id,
                'date' => $expense->date,
                'ref' => $expense->ref,
                'bill_number' => $expense->bill_number,
                'amount' => number_format($expense->amount, 2) . ' ' . $expense->currency->code,
                'paid' => number_format($expense->paid, 2) . ' ' . $expense->currency->code,
                'due' => number_format($expense->amount - $expense->paid, 2) . ' ' . $expense->currency->code,
                'description' => $expense->description,
                'currency' => $expense->currency->code,
               
            ];
        });
    
        // Prepare aggregated expense payments across all bill expenses
        $allexpensepayment = $supplier->billExpenses->flatMap(function ($expense) {
            return $expense->expensepayment->map(function ($payment) use ($expense) {
                return [
                    'payment_id' => $payment->id,
                    'expense_ref' => $expense->ref,
                    'amount' => number_format($payment->amount, 2) . ' ' . $payment->currency->code,
                    'date' => $payment->date,
                    'description' => $payment->description,
                ];
            });
        });
    
        // Format total amount, paid, and due fields by currency
        $totalAmountFormatted = $totalAmounts->map(function ($total) {
            return number_format($total['total'], 2) . ' ' . $total['currency'];
        })->implode(' | ');
    
        $totalPaidFormatted = $totalAmounts->map(function ($total) {
            return number_format($total['total_paid'], 2) . ' ' . $total['currency'];
        })->implode(' | ');
    
        $totalDueFormatted = $totalAmounts->map(function ($total) {
            $dueAmount = $total['total'] - $total['total_paid'];
            return number_format($dueAmount, 2) . ' ' . $total['currency'];
        })->implode(' | ');
    
        // Prepare supplier details
        $supplierDetails = [
            'supplier_name' => $supplier->name,
            'phone' => $supplier->phone,
            'address' => $supplier->address,
            'total_expenses' => $supplier->billExpenses->count(),
            'total_amount' => $totalAmountFormatted,
            'total_paid' => $totalPaidFormatted,
            'total_due' => $totalDueFormatted,
            'bill_expenses' => $billExpensesData,
            'expense_payments' => $allexpensepayment, // All expense payments in one section
        ];
    
        return response()->json($supplierDetails);
    }
    


    public function categoryExpenseReport(Request $request)
    {


        $fromDate = $request->input('startDate');
        $toDate = $request->input('endDate');


        $search = $request->input('categoryExpenseReportSearch');

       
        // Fetch and group expenses within the date range
        $expenses = Expenses::with('expenseCategory', 'currency')
            ->selectRaw('expense_category_id, currency_id, SUM(amount) as total_amount')
            ->when($search, function ($query, $search) {
                return $query->whereHas('expenseCategory', function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%");
                });
            })
            ->whereBetween('date', [$fromDate, $toDate])
            ->groupBy('expense_category_id', 'currency_id')
            ->get();

        // Prepare the final output
        $results = [];
        foreach ($expenses as $expense) {
            // Use category name as the key to accumulate amounts
            $categoryName = $expense->expenseCategory->name;
            $currencyCode = $expense->currency->code;
            $totalAmount = $expense->total_amount;

            // Initialize or append amounts to the category
            if (!isset($results[$categoryName])) {
                $results[$categoryName] = [];
            }

            $results[$categoryName][] = "{$totalAmount} {$currencyCode}";
        }

        // Format the final output to combine amounts
        $formattedResults = [];
        foreach ($results as $categoryName => $amounts) {
            $formattedResults[] = [
                'category_name' => $categoryName,
                'amount' => implode(' | ', $amounts), // Combine amounts with currency codes
            ];
        }

        return $formattedResults;
    }


    public function ownersReport(Request $request)
    {
        $fromDate = $request->input('startDate');
        $toDate = $request->input('endDate');



        // Fetch and group owner pickups within the date range
        $ownerPickups = OwnerPickup::with('owner', 'currency')
            ->selectRaw('owner_id, currency_id, SUM(amount) as total_amount')
            ->whereBetween('date', [$fromDate, $toDate])
            ->groupBy('owner_id', 'currency_id')
            ->get();

        // Prepare the final output
        $results = [];
        foreach ($ownerPickups as $pickup) {
            // Use owner name as the key to accumulate amounts
            $ownerName = $pickup->owner->name;
            $currencyCode = $pickup->currency->code;
            $totalAmount = $pickup->total_amount;

            // Initialize or append amounts to the owner
            if (!isset($results[$ownerName])) {
                $results[$ownerName] = [];
            }

            $results[$ownerName][] = "{$totalAmount} {$currencyCode}";
        }

        // Format the final output to combine amounts
        $formattedResults = [];
        foreach ($results as $ownerName => $amounts) {
            $formattedResults[] = [
                'owner_name' => $ownerName,
                'amount' => implode(' | ', $amounts), // Combine amounts with currency codes
            ];
        }

        return $formattedResults;
    }

    public function productExpenseReport(Request $request)
    {
        $fromDate = $request->input('startDate');
        $toDate = $request->input('endDate');


        $search = $request->input('ProductReportSearch');


        // Fetch and group bill expense details within the date range
        $billExpenseDetails = BillExpenseDetails::with('expenseProduct', 'currency')
            ->selectRaw('expense_product_id, currency_id, SUM(total) as total_amount')
            ->when($search, function ($query, $search) {
                return $query->whereHas('expenseProduct', function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%");
                });
            })
            ->whereHas('billExpense', function ($query) use ($fromDate, $toDate) {
                $query->whereBetween('date', [$fromDate, $toDate]); // Assuming BillExpenses has a date column
            })
            ->groupBy('expense_product_id', 'currency_id')
            ->get();

        // Prepare the final output
        $results = [];
        foreach ($billExpenseDetails as $detail) {
            // Use product name as the key to accumulate amounts
            $productName = $detail->expenseProduct->name;
            $currencyCode = $detail->currency->code;
            $totalAmount = $detail->total_amount;

            // Initialize or append amounts to the product
            if (!isset($results[$productName])) {
                $results[$productName] = [];
            }

            $results[$productName][] = "{$totalAmount} {$currencyCode}";
        }

        // Format the final output to combine amounts
        $formattedResults = [];
        foreach ($results as $productName => $amounts) {
            $formattedResults[] = [
                'product_name' => $productName,
                'amount' => implode(' | ', $amounts), // Combine amounts with currency codes
            ];
        }

        return $formattedResults;
    }
}
