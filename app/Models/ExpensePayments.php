<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpensePayments extends Model
{
    use HasFactory;

    protected $fillable = [
        'bill_expenses_id','date', 'amount', 'description','currency_id',
    ];

    public function billExpense(){
        return $this->belongsTo(BillExpenses::class,'bill_expenses_id');
    }
    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }
    


    public function scopeSearch($query, $search, $expenseId)
    {

        if (!$search && !$expenseId) {

            return $query;
        }

        if ($expenseId) {
            return $query->where('bill_expense_id', $expenseId);
        }

        return $query->where('amount', 'like', '%' . $search . '%');
    }
}
