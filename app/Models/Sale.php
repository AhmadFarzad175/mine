<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sale extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'date', 'ref#', 'user_id', 'customer_id', 'total_amount','currency_id',
        'paid','discount','payment_status'
    ];

    public function customer(){
        return $this->belongsTo(Customer::class);
    }

    public function salesDetails()
    {
        return $this->hasMany(SaleDetails::class);
    }

    public function salesPayments()
    {
        return $this->hasMany(SalePayment::class , 'sales_id');
    }

    public function user(){
        return $this->belongsTo(User::class);
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

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($sale) {
            // Get the latest $sale that is not soft deleted
            $latestSale = self::withTrashed()->latest('id')->first();

            // Generate reference based on the latest $sale id
            $newId = $latestSale ? $latestSale->id + 1 : 1;
            $sale->ref = 'Sal_' . $newId;
        });

    }


}
