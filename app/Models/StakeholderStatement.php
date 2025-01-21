<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StakeholderStatement extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = ['id'];
    protected $dates = ['deleted_at'];

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function stakeholderAccount()
    {
        return $this->belongsTo(StakeholderAccount::class);
    }
    public function stakeholder()
    {
        return $this->belongsTo(Stakeholder::class);
    }
}
