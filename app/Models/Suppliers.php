<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Suppliers extends Model
{
    use HasFactory;


    protected $fillable = [
        'name', 'phone', 'address'
    ];

    public function billExpenses()
    {
        return $this->hasMany(BillExpenses::class, 'supplier_id'); // Specify the correct foreign key here
    }

    public function scopeSearch($query, $search)
    {
        if (!empty($search)) {
            return $query->where('name', 'like', '%' . $search . '%');
        }

        return $query;
    }
}
