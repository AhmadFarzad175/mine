<?php

use App\Models\Currency;
use App\Models\Expenses;
use App\Models\BillExpenses;
use App\Models\ExpenseProducts;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bill_expense_details', function (Blueprint $table) {
                $table->id();
                $table->foreignIdFor(BillExpenses::class);
                $table->foreignIdFor(ExpenseProducts::class,'expense_product_id');
                $table->integer('quantity');
                $table->decimal('unit_price', 15, 2);
                $table->foreignIdFor(Currency::class,'currency_id');
                $table->decimal('total', 15, 2);
                $table->timestamps();
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bill_expense_details');
    }
};
