<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ExpenseProducts;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ExpenseProductRequest;
use Illuminate\Auth\AuthenticationException;
use App\Http\Resources\ExpenseProductResource;

class ExpenseProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:viewExpenseProduct')->only(['index', 'show']);
        $this->middleware('can:createExpenseProduct')->only(['store']);
        $this->middleware('can:editExpenseProduct')->only(['update']);
        $this->middleware('can:deleteExpenseProduct')->only('destroy');
    }
    private function authorizeUser(ExpenseProducts $expenseProduct)
    {
        if ($expenseProduct->user_id !== auth()->id()) {
            throw new AuthenticationException('you must be logged in');
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
        $expenseProducts = ExpenseProducts::query()
            ->search($search);

        $expenseProducts = $perPage ? $expenseProducts->latest()->paginate($perPage) : $expenseProducts->latest()->get();

        return ExpenseProductResource::collection($expenseProducts);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ExpenseProductRequest $request)
    {
        $validated = $request->validated();
        $validated['user_id'] = Auth::id() ?? 1;
        $expenseProduct = ExpenseProducts::create($validated);
        return ExpenseProductResource::make($expenseProduct);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $expenseProduct = ExpenseProducts::find($id);

        // return $expenseProduct;
        return ExpenseProductResource::make($expenseProduct);
    }
    public function searchProduct(Request $request)
    {
        $name=$request->query('name');
        $expproduct = ExpenseProducts::where('name', 'LIKE', '%' .$name . '%')->get();
        return ExpenseProductResource::collection($expproduct);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ExpenseProductRequest $request, $id)
    {
        $expenseProduct = ExpenseProducts::find($id);
        $validated = $request->validated();
        $validated['user_id'] = Auth::id() ?? 2;
        $expenseProduct->update($validated);
        return ExpenseProductResource::make($expenseProduct);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $expenseProduct = ExpenseProducts::find($id);
        $expenseProduct->delete();
        return response()->noContent();
    }
}
