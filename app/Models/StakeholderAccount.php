<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StakeholderAccount extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = ['id'];
    protected $dates = ['deleted_at'];
    public function stakeholder()
    {
        return $this->belongsTo(Stakeholder::class);
    }
    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }
    public function scopeSearch($query, $search)
    {
        if (!$search) {
            return $query;
        }
        $query->whereHas('stakeholder', function ($query) use ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        });
    }}
