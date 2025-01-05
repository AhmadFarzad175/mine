<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'amount', 'currency_id'
    ];

    public function currency(){
        return $this->belongsTo(Currency::class);
    }
    public function sale_payment(){
        return $this->hasMany(SalePayment::class);
    }

    public function expense_payment(){
        return $this->hasMany(ExpensePayments::class);
    }

    public function expense(){
        return $this->hasMany(Expenses::class);
    }

    public function billExpense(){
        return $this->hasMany(BillExpenses::class);
    }

    public function owner_pickup(){
        return $this->belongsTo(OwnerPickup::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
    public function owner(){
        return $this->hasMany(Owner::class);
    }


    public function scopeSearch($query, $search){
        if(!empty($search)){
            return $query->where('name', 'like', '%'. $search . '%');
        }
        return $query;
    }
}
