<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use App\Models\Stakeholder;
use App\Models\MoneyAccount;
use Illuminate\Http\Request;
use App\Models\StakeholderAccount;
use App\Models\StakeholderStatement;
use App\Services\CurrencyConversion;
use Illuminate\Support\Facades\Auth;
use App\Models\MoneyAccountStatement;
use App\Http\Requests\StakeholderStatementRequest;
use App\Http\Resources\StakeholderStatementResource;

class StakeholderStatementController extends Controller
{
    protected $currencyConversion;

    public function __construct(CurrencyConversion $currencyConversion)
    {
        $this->currencyConversion = $currencyConversion;
    }

    public function index(Request $request)
    {
        $perPage = $request->input('perPage', 10);
        $search = $request->input('search');
        $stakeholderId = $request->input('stakeholder_id'); // Retrieve stakeholder ID from the request

        // Build the query
        $query = StakeholderStatement::with(['currency', 'stakeholder', 'user', 'stakeholderAccount'])
            ->latest();

        // Filter by stakeholder ID if provided
        if ($stakeholderId) {
            $query->where('stakeholder_id', $stakeholderId);
        }

        // Paginate the results
        $accounts = $query->paginate($perPage);

        // Return the paginated collection
        return StakeholderStatementResource::collection($accounts);
    }

    public function store(StakeholderStatementRequest $request)
    {
        $validated = $request->validated();
        $newAmount = 0;

        try {
            $validated['user_id'] = Auth::id();

            $stakeName = Stakeholder::where('id', $validated['stakeholder_id'])->value('name');
            $currency = Currency::where('id', $validated['currency_id'])->first();
            $moneyAccount = MoneyAccount::where('id', $validated['money_account_id'])->first();

            if (isset($validated['stakeholder_account_id']) && $validated['stakeholder_account_id']) {
                $stakeholderAccount = StakeholderAccount::find($validated['stakeholder_account_id']);
            } else {
                $stakeholderAccount = StakeholderAccount::Create(
                    [
                        'stakeholder_id' => $validated['stakeholder_id'],
                        'currency_id' => $validated['currency_id'],
                        'name' => $stakeName . '_' . $currency->code,
                        'amount' => 0,
                        'initial_amount' => 0,
                        'date' => $validated['date'],
                    ]
                );
            }

            $newAmount = $this->currencyConversion->convertFromOneCurrencyToAnotherCurrency($validated['amount'], $moneyAccount->currency_id, $stakeholderAccount->currency_id);
            // dump($moneyAccount->amount, $stakeholderAccount->amount);
            if ($validated['pay_or_receive'] === 'PayMoney') {
                $stakeholderAccount->increment('amount', $newAmount);
                if ($moneyAccount) {
                    $moneyAccount->decrement('amount', $validated['amount']);
                }
            }
            // dd($moneyAccount->amount, $stakeholderAccount->amount);
            if ($validated['pay_or_receive'] === 'ReceiveMoney') {
                $stakeholderAccount->decrement('amount', $newAmount);
                if ($moneyAccount) {    
                    $moneyAccount->increment('amount', $validated['amount']);
                }
            }

            $moneyAccountStatement = MoneyAccountStatement::create([
                'currency_id' => $validated['currency_id'],
                'money_account_id' => $moneyAccount->id,
                'type' => $validated['type'],
                'date' => $validated['date'],
                'amount' => $validated['amount'],
                'balance' => $moneyAccount->amount,
                'description' => $validated['description'] ?? null,
                'pay_or_receive' => $validated['pay_or_receive'],
                'rate' => $currency->rate,
            ]);

            $validated['stakeholder_account_id'] = $stakeholderAccount->id;
            $validated['balance'] = $stakeholderAccount->amount;
            $validated['rate'] = $currency->rate;
            $validated['record_id'] = $moneyAccountStatement->id;
            StakeholderStatement::create($validated);

            return response()->json(['message' => 'Record created successfully.'], 201);
        } catch (\Exception $e) {
            // Log the exception for debugging purposes
            \Log::error('Error creating record: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'message' => 'An error occurred while processing the request.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(StakeholderStatementRequest $request, StakeholderStatement $stakeholderStatement)
    {
        $validated = $request->validated();

        try {
            $validated['user_id'] = Auth::id();

            // Revert changes made by the old data
            $stakeholderAccount = StakeholderAccount::findOrFail($stakeholderStatement->stakeholder_account_id);
            $moneyAccount = MoneyAccount::findOrFail($stakeholderStatement->money_account_id);
            // $currency = Currency::findOrFail($stakeholderStatement->currency_id);

            if ($stakeholderStatement->pay_or_receive === 'PayMoney') {
                $stakeholderAccount->decrement('amount', $stakeholderStatement->amount);
                $moneyAccount->increment('amount', $stakeholderStatement->amount);
            }
            if ($stakeholderStatement->pay_or_receive === 'ReceiveMoney') {
                $stakeholderAccount->increment('amount', $stakeholderStatement->amount);
                $moneyAccount->decrement('amount', $stakeholderStatement->amount);
            }

            // Apply new data
            $stakeName = Stakeholder::where('id', $validated['stakeholder_id'])->value('name');
            $currency = Currency::where('id', $validated['currency_id'])->first();
            $moneyAccount = MoneyAccount::where('id', $validated['money_account_id'])->first();
            $stakeholderAccount = StakeholderAccount::firstOrCreate(
                [
                    'stakeholder_id' => $validated['stakeholder_id'],
                    'currency_id' => $validated['currency_id'],
                ],
                [
                    'name' => $stakeName . '_' . $currency->code,
                    'amount' => 0,
                    'initial_amount' => 0,
                    'date' => $validated['date'],
                ]
            );

            if ($validated['pay_or_receive'] === 'PayMoney') {
                $stakeholderAccount->increment('amount', $validated['amount']);
                if ($moneyAccount) {
                    $moneyAccount->decrement('amount', $validated['amount']);
                }
            }
            if ($validated['pay_or_receive'] === 'ReceiveMoney') {
                $stakeholderAccount->decrement('amount', $validated['amount']);
                if ($moneyAccount) {
                    $moneyAccount->increment('amount', $validated['amount']);
                }
            }

            // Update money account statement
            $moneyAccountStatement = MoneyAccountStatement::where('id', $stakeholderStatement->record_id)->firstOrFail();
            $moneyAccountStatement->update([
                'currency_id' => $validated['currency_id'],
                'money_account_id' => $moneyAccount->id,
                'type' => $validated['type'],
                'date' => $validated['date'],
                'amount' => $validated['amount'],
                'description' => $validated['description'] ?? null,
                'pay_or_receive' => $validated['pay_or_receive'],
                'rate' => $currency->rate,
            ]);

            // Update stakeholder statement
            $validated['stakeholder_account_id'] = $stakeholderAccount->id;
            $validated['rate'] = $currency->rate;
            $stakeholderStatement->update($validated);

            return response()->json(['message' => 'Record updated successfully.'], 200);
        } catch (\Exception $e) {
            // Log the exception for debugging purposes
            \Log::error('Error updating record: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'message' => 'An error occurred while processing the update request.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
