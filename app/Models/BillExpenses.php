<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BillExpenses extends Model
{
    use HasFactory;
    use SoftDeletes;
protected $fillable = ['supplier_id', 'amount', 'date', 'ref', 'user_id',
 'paid','Due', 'expense_category_id', 'bill_number', 'total','description','currency_id',];

public function supplier(){
    return $this->belongsTo(Suppliers::class);
}
public function billExpenseDetails(){
    return $this->hasMany(BillExpenseDetails::class);
}
public function expensepayment()
{
    return $this->hasMany(ExpensePayments::class);
}
public function user(){
    return $this->belongsTo(User::class);
}

public function currency()
{
    return $this->belongsTo(Currency::class);
}
public function expenseCategory()
{
    return $this->belongsTo(ExpenseCategory::class);
}
public function account()
{
    return $this->belongsTo(Account::class);
}


public function scopeSearch($query, $search)
    {
        if (!empty($search)) {
            return $query->where('date', 'like', '%' . $search . '%')
            ->where('amount', 'like', '%' . $search . '%');

        }

        return $query;
    }

protected static function boot()
    {
        parent::boot();

        static::creating(function ($billexpense) {
            // Get the latest expense that is not soft deleted
            $latestbillExpense = self::withTrashed()->latest('id')->first();

            // Generate reference based on the latest expense id
            $newId = $latestbillExpense ? $latestbillExpense->id + 1 : 1;
            $billexpense->ref = 'EXP_' . $newId;
        });
    }
}
