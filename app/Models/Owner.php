<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Owner extends Model
{
    use HasFactory;
    protected $fillable = [
        'name' , 'phone','image', 'description',
    ];
    public function ownerPickups() {
        return $this->hasMany(OwnerPickup::class);
    }
    public function currency() // Add this relationship
    {
        return $this->belongsTo(Currency::class);
    }

    public function account() // Add this relationship
    {
        return $this->belongsTo(Account::class);
    }


    public function scopeSearch($query, $search)
    {
        if (!empty($search)) {
            return $query->where('name', 'like', '%' . $search . '%');
        }

        return $query;
    }

}
