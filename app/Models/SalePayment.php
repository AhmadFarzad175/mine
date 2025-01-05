<?php

namespace App\Models;

// app/Models/salesPayment.php

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalePayment extends Model
{
    use HasFactory;

    protected $fillable = ['sales_id', 'date', 'amount', 'description', 'account','currency_id'];

    public function sales()
    {
        return $this->belongsTo(Sale::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }


   


    public function scopeSearch($query, $search, $saleId)
    {

        if (!$search && !$saleId) {

            return $query;
        }

        if ($saleId) {
            return $query->where('sale_id', $saleId);
        }

        return $query->where('amount', 'like', '%' . $search . '%');
    }
}

