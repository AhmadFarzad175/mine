<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExpensePaymentsRequest;
use App\Http\Requests\ExpensesRequest;
use App\Models\ExpensePayments;
use App\Http\Requests\StoreExpensePaymentsRequest;
use App\Http\Requests\UpdateExpensePaymentsRequest;
use App\Http\Resources\ExpenseCategoryResource;
use App\Http\Resources\ExpensePaymentResource;
use App\Models\BillExpenses;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExpensePaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:viewExpensePayments')->only(['index', 'show']);
        $this->middleware('can:createExpensePayments')->only(['store']);
        $this->middleware('can:editExpensePayments')->only(['update']);
        $this->middleware('can:deleteExpensePayments')->only('destroy');
    }
    private function authorizeUser(ExpensePayments $expensePayments)
    {
        if ($expensePayments->user_id !== auth()->id()) {
            throw new AuthenticationException('you must be logged in');
        }
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->input('perPage');
        $search = request()->input('search');
        $billExpensePayment = $request->input('billExpensePayment');
       $ePayment = ExpensePayments::with(['billExpense'])->search($search,$billExpensePayment)->latest();
       $ePayment =  $perPage == -1 ? $ePayment->get() : $ePayment->paginate($perPage);
        return ExpensePaymentResource::collection($ePayment);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ExpensePaymentsRequest $request)
    {
        
        // Validate the incoming request data
        $validated = $request->validated();
        $validated['created_by'] = Auth::id() ?? 2; // Add created_by field
    
        // Create the ExpensePayment entry
        $expensePayment = ExpensePayments::create($validated);
    
        // Access the related BillExpense using the relationship
        $billExpense = $expensePayment->billExpense;
    
        // Increment the paid amount in the BillExpenses table
        $billExpense->increment('paid', $validated['amount']);
    
        // Return the newly created expense payment resource
        return ExpensePaymentResource::make($expensePayment);
    }
    

    /**
     * Display the specified resource.
     */
    public function show(ExpensePayments $expensePayments)
    {
        return ExpensePaymentResource::make($expensePayments);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ExpensePaymentsRequest $request, $id)
    {
        // Fetch the existing expense payment record
        $expensePayment = ExpensePayments::findOrFail($id);
        
        // Get the current payment amount before the update
        $oldAmount = $expensePayment->amount;
    
        // Validate the incoming request data
        $validated = $request->validated();
        $validated['created_by'] = Auth::id() ?? 2;
    
        // Update the expense payment record
        $expensePayment->update($validated);
    
        // Access the related BillExpense using the relationship
        $billExpense = $expensePayment->billExpense;
    
        // Calculate the difference between the old amount and the new amount
        $amountDifference = $validated['amount'] - $oldAmount;
    
        // Update the 'paid' field in the BillExpenses table by adjusting it with the amount difference
        $billExpense->increment('paid', $amountDifference);
    
        // Return the updated expense payment resource
        return ExpensePaymentResource::make($expensePayment);
    }
    
    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // Find the expense payment record by its ID
        $expensePayment = ExpensePayments::find($id);
    
        // Check if the record exists
        if (!$expensePayment) {
            return response()->json(['message' => 'Expense Payment not found.'], 404);
        }
    
        // Access the related BillExpense using the relationship
        $billExpense = $expensePayment->billExpense;
    
        // Decrement the 'paid' field by the amount of the expense payment
        $billExpense->decrement('paid', $expensePayment->amount);
    
        // Delete the expense payment record
        $expensePayment->delete();
    
        // Return a success response
        return response()->json(['message' => 'Expense Payment deleted successfully.'], 200);
    }
    
}
