<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OwnerPickup extends Model
{
    use HasFactory;


    protected $fillable = [
        'owner_id', 'date', 'amount', 'description','currency_id','user_id', 
    ];

    public function owner()
    {
        return $this->belongsTo(Owner::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function scopeSearch($query, $search)
    {
        if (!empty($search)) {
            return $query->where('date', 'like', '%' . $search . '%');
        }

        return $query;
    }
}
