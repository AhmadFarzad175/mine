<?php

namespace App\Http\Controllers;

use App\Models\MoneyAccount;
use Illuminate\Http\Request;
use App\Models\MoneyAccountStatement;
use App\Http\Requests\MoneyAccountRequest;
use App\Http\Resources\MoneyAccountResource;
use App\Http\Resources\AccountStatementResource;

class MoneyAccountController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:viewMoneyAccount')->only(['index', 'show']);
        $this->middleware('can:createMoneyAccount')->only('store');
        $this->middleware('can:editMoneyAccount')->only('update');
        $this->middleware('can:deleteMoneyAccount')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->input('perPage', 10);
        $search = request()->input('search');
        $Account = MoneyAccount::with('currency')->search($search)->latest()->paginate($perPage);
        return MoneyAccountResource::collection($Account);

    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(MoneyAccountRequest $request)
    {
        $validatedData = $request->validated();
        $validatedData['initial_amount'] = $validatedData['amount'];
        $Account = MoneyAccount::create($validatedData);
        return MoneyAccountResource::make($Account);
    }

    /**
     * Display the specified resource.
     */
    public function show(MoneyAccount $moneyAccount)
    {
        return MoneyAccountResource::make($moneyAccount);
    }
    


    /**
     * Update the specified resource in storage.
     */
    public function update(MoneyAccountRequest $request, MoneyAccount $moneyAccount)
    {
        $validated = $request->validated();
        // Update the sale
        $moneyAccount->update($validated);
        return MoneyAccountResource::make($moneyAccount);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MoneyAccount $moneyAccount)
    {
        $moneyAccount->delete();
        return response()->noContent();
    }

    public function bulkDelete(Request $request){
        $ids = $request->input('ids');
        MoneyAccount::whereIn('id', $ids)->delete();
        return response()->noContent();
    }

    public function accountStatements($id){
        $statements = MoneyAccountStatement::with(['currency', 'moneyAccount'])->where('money_account_id', $id)->get();
        return AccountStatementResource::collection($statements);
    }
}
