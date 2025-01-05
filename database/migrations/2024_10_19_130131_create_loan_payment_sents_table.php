<?php

use App\Models\Currency;
use App\Models\LoanPeople;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('loan_payment_sents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('loan_people_id')->constrained('loan_people')->onDelete('cascade'); // Foreign key constraint with loan_people
            $table->foreignId('currency_id')->constrained('currencies')->onDelete('cascade'); 
            $table->decimal('amount', 15, 2);
            $table->date('payment_date');
            $table->string('notes');
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loan_payment_sents');
    }
};
