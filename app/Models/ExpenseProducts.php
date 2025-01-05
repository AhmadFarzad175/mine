<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpenseProducts extends Model
{
    use HasFactory;
    protected $fillable = [
        'name', 'unit',
    ];


    public function expenses()
    {
        return $this->hasMany(Expenses::class);
    }
    public function billexpensedetails()
    {
        return $this->hasMany(BillExpenseDetails::class);
    }

    public function scopeSearch($query, $search)
    {
        if (!empty($search)) {
            return $query->where('name', 'like', '%' . $search . '%');
        }

        return $query;
    }
}
