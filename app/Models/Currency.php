<?php

namespace App\Models;

use App\Models\Account;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Currency extends Model
{
    use HasFactory;
    protected $fillable = [
        'name', 'symbol', 'code', 'rate'
    ];

    public function account(){
        return $this->hasMany(Account::class);
    }

    public function sale(){
        return $this->hasMany(Sale::class);
    }

    public function billExpense(){
        return $this->hasMany(BillExpenses::class);
    }
    public function owner(){
        return $this->hasMany(Owner::class);
    }
    public function user(){
        return $this->hasMany(User::class);
    }

    public function salepayments()
    {
        return $this->hasMany(SalePayment::class);
    }


    public function scopeSearch($query, $search){
        if(!$search){
            return $query;
        }
        $query->where('name', 'like', '%' . $search . '%');
    }
}
