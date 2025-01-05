<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanPeople extends Model
{
    use HasFactory;

    // Fillable attributes
    protected $fillable = [
        'name',
        'phone',
        'email',
    ];

    // Relationship with LoanPaymentSent
    public function paymentsSent()
    {
        return $this->hasMany(LoanPaymentSent::class);
    }
    
    // Relationship with LoanPaymentReceived
    public function paymentsReceived()
    {
        return $this->hasMany(LoanPaymentReceived::class);
    }
}

