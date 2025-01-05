<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleDetails extends Model
{
    use HasFactory;

    protected $fillable = ['sale_id', 'sale_products_id', 'quantity', 'unit_price', 'total'];

    public function sales()
    {
        return $this->belongsTo(Sale::class);
    }
    // public function salesProduct()
    // {
    //     return $this->belongsTo(saleProducts::class);
    // }
    

    public function saleProducts()
    {
        return $this->belongsTo(saleProducts::class);
    }

   
}
