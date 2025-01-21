<?php
namespace App\Services;

use App\Models\Currency;

class CurrencyConversion
{
    public static function convertFromOneCurrencyToAnotherCurrency($amount, $fromCurrencyId, $toCurrencyId)
    {
        // Check if the amount is null or zero
        if ($amount === null || $amount === 0) {
            return 0;
        }

        if ($fromCurrencyId == $toCurrencyId) {
            return $amount;
        }
        // Fetch currency exchange rates from the database
        $fromRate = Currency::find($fromCurrencyId)->rate;

        $toRate = Currency::find($toCurrencyId)->rate;


        if ($fromRate !== null && $toRate !== null) {
            // Perform the conversion, considering USD as the base currency
            $convertedAmount = ($amount / $fromRate) * $toRate;

            // Round the result to 2 decimal places using ceil
            // $roundedAmount = $convertedAmount * 100 / 100;

            return $convertedAmount;
        } else {
            // Handle cases where exchange rates are not available
            return 0;
        }
    }
}
