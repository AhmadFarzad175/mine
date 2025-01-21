<?php

namespace App\Http\Controllers;

use DateTime;
use App\Models\Currency;
use Illuminate\Http\Request;
use App\Models\StakeholderAccount;
use App\Models\StakeholderStatement;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\StakeholderResource;
use App\Http\Requests\StakeholderAccountRequest;
use App\Http\Resources\StakeholderAccountResource;

class StakeholderAccountController extends Controller
{
    public function index(Request $request)
{
    $perPage = $request->input('perPage', 10);
    $search = $request->input('search');
    $stakeholderId = $request->input('stakeholder_id'); // Retrieve stakeholder ID from the request

    // Build the query
    $query = StakeholderAccount::with(['currency', 'stakeholder'])
                ->search($search)
                ->latest();

    // Filter by stakeholder ID if provided
    if ($stakeholderId) {
        $query->where('stakeholder_id', $stakeholderId);
    }

    // Paginate the results
    $accounts = $query->paginate($perPage);

    // Return the paginated collection
    return StakeholderAccountResource::collection($accounts);
}



    /**
     * Store a newly created resource in storage.
     */
    public function store(StakeholderAccountRequest $request)
{
    $validatedData = $request->validated();
    $validatedData['initial_amount'] = $validatedData['amount'];
    $validatedData['date'] = date("Y-m-d");
    $currencyRate = Currency::find($validatedData['currency_id'])->rate;

    // Check if the stakeholder already has an account with the specified currency
    $existingAccount = StakeholderAccount::where('stakeholder_id', $validatedData['stakeholder_id'])
        ->where('currency_id', $validatedData['currency_id'])
        ->first();

    if ($existingAccount) {
        return response()->json([
            'message' => 'The stakeholder already has an account with this currency.',
        ], 422);
    }

    if ($validatedData['pay_or_receive'] == "receiveMoney") {
        $validatedData['amount'] = -$validatedData['amount'];
    }

    // Create the stakeholder account
    $account = StakeholderAccount::create($validatedData);

    // Create the stakeholder statement
    StakeholderStatement::create([
        "user_id" => Auth::user()->id,
        "currency_id" => $validatedData['currency_id'],
        "stakeholder_id" => $validatedData['stakeholder_id'],
        "stakeholder_account_id" => $account->id,
        "type" => 'Opening Balance',
        "date" => $validatedData['date'],
        "amount" => abs($validatedData['amount']),
        "balance" => $account->amount,
        "rate" => $currencyRate,
        "description" => "Account opening balance",
    ]);

    return StakeholderAccountResource::make($account);
}


    /**
     * Display the specified resource.
     */
    public function show(StakeholderAccountController $stakeholderAccount)
    {
        return StakeholderAccountResource::make($stakeholderAccount);
    }
    


    /**
     * Update the specified resource in storage.
     */
    public function update(StakeholderAccountRequest $request, StakeholderAccount $stakeholderAccount)
    {
        $validated = $request->validated();
        // Update the sale
        $stakeholderAccount->update($validated);
        return StakeholderAccountResource::make($stakeholderAccount);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StakeholderAccount $stakeholderAccount)
    {
        $stakeholderAccount->delete();
        return response()->noContent();
    }
}
