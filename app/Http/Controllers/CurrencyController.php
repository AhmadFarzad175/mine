<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use Illuminate\Http\Request;
use App\Http\Requests\CurrencyRequest;
use App\Http\Resources\CurrencyResource;
use App\Models\User;

class CurrencyController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:viewCurrency')->only(['index', 'show']);
        $this->middleware('can:createCurrency')->only('store');
        $this->middleware('can:editCurrency')->only('update');
        $this->middleware('can:deleteCurrency')->only('destroy');
    }

    // private function authorizeUser(User $user)
    // {
    //     if ($user->user_id !== auth()->id()) {
    //         abort(403, 'Unauthorized action.');
    //     }
    // }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->input('perPage');
        $search = $request->input('search');
        $currency = Currency::query()->search($search);
        $currencies = $perPage?$currency->latest()->paginate($perPage):$currency->latest()->get();
        return CurrencyResource::collection($currencies);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CurrencyRequest $request)
    {
        $validated = $request->validated();
        $currency = Currency::create($validated);
        return new CurrencyResource($currency);
    }

    /**
     * Display the specified resource.
     */
    public function show(Currency $currency)
    {
        return new CurrencyResource($currency);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CurrencyRequest $request, Currency $currency)
    {
        $validated = $request->validated();
        $currency->update($validated);
        return new CurrencyResource($currency);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Currency $currency)
    {
        $currency->delete();
        return response()->noContent();
    }
}
