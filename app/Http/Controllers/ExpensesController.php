<?php

namespace App\Http\Controllers;

use App\Models\Expenses;
use App\Models\SaleDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ExpensesRequest;
use App\Http\Resources\ExpenseResource;
use Illuminate\Auth\Access\AuthorizationException;

class ExpensesController extends Controller
{

    public function __construct()
    {
        $this->middleware('can:viewNoneBillExpense')->only(['index','show']);
        $this->middleware('can:createNoneBillExpense')->only(['store']);
        $this->middleware('can:editNoneBillExpense')->only(['update']);
        $this->middleware('can:deleteNoneBillExpense')->only('destroy');


    }
    private function authorizeUser(Expenses $expenses)
    {
        if ($expenses->user_id !== auth()->id()) {
            throw new AuthorizationException('You are not authorized');
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->input('perPage', 10);
        $search = $request->input('search');

        // Eager load relationships and apply search
        $expenses = Expenses::query()
            ->search($search);

        $expenses = $perPage ? $expenses->latest()->paginate($perPage) : $expenses->latest()->get();

        return ExpenseResource::collection($expenses);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(ExpensesRequest $request)
    {
        $validated = $request->validated();
        $validated['user_id'] = Auth::id() ?? 1;
        $expense = Expenses::create($validated);
        return ExpenseResource::make($expense);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $expense = Expenses::find($id);

        // return $expense;
        return ExpenseResource::make($expense);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ExpensesRequest $request, $id)
    {
        $expense = Expenses::find($id);
        $validated = $request->validated();
        $validated['user_id'] = Auth::id() ?? 2;
        $expense->update($validated);
        return ExpenseResource::make($expense);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // dd($id);
        $expense = Expenses::findOrFail($id);
        $expense->delete();
        return response()->noContent();
    }
}
//developed by: Fahim rahimi
