<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BillExpenseDetails extends Model
{
    use HasFactory;
    protected $fillable = [
        'bill_expenses_id','expense_product_id','quantity','unit_price','total','currency_id'
    ];
public function expenseProduct()
{
    return $this->belongsTo(ExpenseProducts::class);
}

public function billExpense(){
    return $this->belongsTo(BillExpenses::class , 'bill_expenses_id');
}

public function currency(){
    return  $this->belongsTo(Currency::class , 'currency_id');
}
}

