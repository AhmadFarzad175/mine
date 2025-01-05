<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanPaymentReceived extends Model
{
    use HasFactory;

    protected $fillable = ['loan_people_id', 'currency_id', 'amount', 'payment_date', 'notes'];



    public function loanPeople()
    {
        return $this->belongsTo(LoanPeople::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }
}
