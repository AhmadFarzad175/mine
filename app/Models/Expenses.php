<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Expenses extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = [
        'date', 'ref#', 'user_id', 'amount', 'expense_category_id','currency_id','description',
    ];



    public function user(){
        return $this->belongsTo(User::class);
    }

    public function expenseCategory(){
        return $this->belongsTo(ExpenseCategory::class);
    }

    public function currency()
{
    return $this->belongsTo(Currency::class);
}



    public function scopeSearch($query, $search)
    {
        if (!empty($search)) {
            return $query->where('date', 'like', '%' . $search . '%');
        }

        return $query;
    }

    // protected static function boot()
    // {
    //     parent::boot();

    //     static::creating(function ($expense) {
    //         // Get the latest expense that is not soft deleted
    //         $latestExpense = self::withTrashed()->latest('id')->first();

    //         // Generate reference based on the latest expense id
    //         $newId = $latestExpense ? $latestExpense->id + 1 : 1;
    //         $expense->ref = 'EXP_' . $newId;
    //     });

    // }
}
