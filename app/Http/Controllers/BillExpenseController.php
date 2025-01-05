<?php

namespace App\Http\Controllers;

use App\Models\BillExpenses;
use Illuminate\Http\Request;
use App\Models\BillExpenseDetails;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\BillExpensesRequest;
use App\Http\Resources\BillExpenseResource;
use Illuminate\Auth\Access\AuthorizationException;
use App\Http\Resources\Sales_and_SalesDetailsResource;
use App\Http\Resources\Expense_and_ExpenseDetailsResource;
use App\Models\ExpensePayments;
use GuzzleHttp\Promise\Create;

class BillExpenseController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:viewBillexpense')->only(['index', 'show']);
        $this->middleware('can:createBillexpense')->only('store');
        $this->middleware('can:editBillexpense')->only('update');
        $this->middleware('can:deleteBillexpense')->only('destroy');
    }
    private function authorizeUser(BillExpenses $billExpenses)
    {
        if ($billExpenses->user_id !== auth()->id()) {
            throw new AuthorizationException('You are not authorized');
        }
    }
    public function index(Request $request)
    {
        $perPage = $request->input('perPage');
        $search = $request->input('search');

        // Eager load relationships and apply search
        $billExpenses = BillExpenses::query()->search($search);

        $billExpenses = $perPage ? $billExpenses->latest()->paginate($perPage) : $billExpenses->latest()->get();

        return BillExpenseResource::collection($billExpenses);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BillExpensesRequest $request)
    {
        $validated = $request->validated();
        $validated['user_id'] = Auth::id() ?? 3;
        $Due = $validated['amount'] - $validated['paid'];
        $validated['Due'] = $Due;
        // dd($Due);
        $billExpense = BillExpenses::create($validated);
        // $payment = Create($payment->validated());
        // if ($payment)
        if ($request->paid > 0) {
            ExpensePayments::create([
                'bill_expenses_id' => $billExpense->id,
                'date' => $request['date'],
                'amount' => $request['paid'],
                'currency_id' => $request['currency_id'],
            ]);
        }

        foreach ($request->input('Bill_expense_details') as $expenseDetail) {
            BillExpenseDetails::create([
                'bill_expenses_id' => $billExpense->id,
                'expense_product_id' => $expenseDetail['expense_product_id'],
                'currency_id' => $request['currency_id'],
                'quantity' => $expenseDetail['quantity'],
                'unit_price' => $expenseDetail['unit_price'],
                'total' => $expenseDetail['quantity'] * $expenseDetail['unit_price'],
            ]);
        }
        return BillExpenseResource::make($billExpense);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {

        $billExpenses = BillExpenses::find($id);
        return Expense_and_ExpenseDetailsResource::make($billExpenses);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BillExpensesRequest $request, $id)
    {
        // Validate the request
        $validated = $request->validated();

        // Find the existing BillExpense
        $billExpense = BillExpenses::findOrFail($id);

        // Calculate the new due amount
        $Due = $validated['amount'] - $validated['paid'];
        $validated['Due'] = $Due;

        // Update the BillExpense
        $billExpense->update($validated);

        // Update or create BillExpenseDetails
        foreach ($validated['Bill_expense_details'] as $detail) {
            BillExpenseDetails::updateOrCreate(
                [
                    'id' => $detail['id'] ?? null,  // If the ID exists, update; otherwise, create new
                    'bill_expenses_id' => $billExpense->id, // Link to the correct BillExpense
                ],
                [
                    'expense_product_id' => $detail['expense_product_id'],  // Link to the correct product
                    'quantity' => $detail['quantity'],               // Update the quantity
                    'currency_id' => $request['currency_id'],
                    'unit_price' => $detail['unit_price'],                  // Update the unit price
                    'total' => $detail['quantity'] * $detail['unit_price'], // Update the total price
                ]
            );
        }

        // Handle deleted BillExpenseDetails
        if (!empty($request->deletedIds)) {
            BillExpenseDetails::destroy($request->deletedIds);  // Delete the BillExpenseDetails by their IDs
        }

        // Return the updated BillExpense resource
        return Expense_and_ExpenseDetailsResource::make($billExpense);
    }




    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // dd($id);
        $billExpenses = BillExpenses::findOrFail($id);

        // $expenseAmount = $billExpenses->amount;

        // $account = $billExpenses->account;

        // if ($account) {
        //     $account->balance += $expenseAmount;
        //     $account->save();
        // }
        // Detach related records from the pivot table
        $billExpenses->expensepayment()->delete();
        $billExpenses->billExpenseDetails()->delete();
        // Delete the BillableExpense record
        $billExpenses->delete();

        return response()->json(['message' => 'BillableExpense deleted successfully']);
    }

    public function bulkDelete(Request $request)
{
    // Debug the incoming request
    // dd($request);

    // Get the array of BillExpense IDs from the request
    $billExpenseIds = $request->input('billExpenseIds');
    foreach ($billExpenseIds as $billExpenseId){
        BillExpenses::where('id', $billExpenseId)->delete();
        BillExpenseDetails::where('bill_expenses_id', $billExpenseId)->delete();

    }

    return response()->json(['message' => 'BillableExpense records deleted successfully']);
}



}

// Developed By: Fahim Rahimi

