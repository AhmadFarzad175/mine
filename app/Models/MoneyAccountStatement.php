<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MoneyAccountStatement extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = ['id'];
    protected $dates = ['deleted_at'];

    public function moneyAccount()
    {
        return $this->belongsTo(MoneyAccount::class);
    }
    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }}
