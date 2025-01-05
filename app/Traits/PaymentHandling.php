<?php

namespace App\Traits;

use App\Models\Account;

trait PaymentHandling
{
    protected $account;

    public function __construct(Account $account)
    {
        $this->account = $account;
    }

    public function updateAccountDetails($record, $storedAmount)
    {
        // to handle the update cases
       $amountDeference = $record->amount - $storedAmount;

       $account = $record->account;
       $account->increment('amount', $amountDeference);
    }
}
