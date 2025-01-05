<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleProducts extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_name',
        'unit',
    ];

    public function salesDetails()
    {
        return $this->hasMany(SaleDetails::class);
    }


    public function scopeSearch($query, $search)
    {
        if ($search) {
            return $query->where('product_name', 'like', '%' . $search . '%');
        }

        return $query;
    }
}
