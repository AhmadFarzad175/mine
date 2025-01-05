<?php

use App\Models\BillExpenses;
use App\Models\Currency;
use App\Models\ExpensePayments;
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
        Schema::create('expense_payments', function (Blueprint $table) {
            $table->id();
            // $table->foreignId('bill_expenses_id');
            $table->foreignIdFor(BillExpenses::class , 'bill_expenses_id');
            $table->date('date');
            $table->decimal('amount', 15, 2);
            $table->foreignIdFor(Currency::class,'currency_id');
            $table->string('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expense_payments');
    }
};
