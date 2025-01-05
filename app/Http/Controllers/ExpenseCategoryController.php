<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExpenseCategoryRequest;
use App\Models\ExpenseCategory;
use App\Http\Resources\ExpenseCategoryResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExpenseCategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:viewExpenseCategory')->only(['index','show']);
        $this->middleware('can:createExpenseCategory')->only(['store']);
        $this->middleware('can:editExpenseCategory')->only(['update']);
        $this->middleware('can:deleteExpenseCategory')->only(['destroy']);
    }
    private function authorizeUser(ExpenseCategory  $expenseCategory)
    {
        if ($expenseCategory->user->id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->input('perPage');
        $search = $request->input('search');
        $expenseCategory = ExpenseCategory::query()->search($search);
        $expenseCategory = $perPage ? $expenseCategory->latest()->paginate($perPage) : $expenseCategory->latest()->get();
        return ExpenseCategoryResource::collection($expenseCategory);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ExpenseCategoryRequest $request)
    {
        $validatedData=$request->validated();
        $validatedData['user_id']=Auth::id();
        $expenseCategory = ExpenseCategory::create($validatedData);
        return ExpenseCategoryResource::make($expenseCategory);
    }

    /**
     * Display the specified resource.
     */
    public function show(ExpenseCategory $expenseCategory)
    {
    return ExpenseCategoryResource::make($expenseCategory);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ExpenseCategoryRequest $request, $id)
    {
        $expenseCategory = ExpenseCategory::find($id);
        $expenseCategory->update($request->validated());
        return ExpenseCategoryResource::make($expenseCategory);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $expenseCategory = ExpenseCategory::find($id);
        $expenseCategory->delete();
        return response()->noContent();

    }
}

