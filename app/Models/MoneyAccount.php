<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MoneyAccount extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['name', 'amount', 'currency_id','initial_amount'];

    protected $casts = [
        'amount' => 'double',
        'currency_id' => 'integer',
    ];

    public function scopeSearch($query, $search)
    {
        if (!empty($search)) {
            return $query->where('name', 'like', '%' . $search . '%');
        }

        return $query;
    }
    

    public function currency()
    {
        return $this->belongsTo('App\Models\Currency');
    }

    public function assignedUsers()
    {
        return $this->belongsToMany('App\Models\User');
    }}
