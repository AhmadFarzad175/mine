<?php

use App\Models\User;
use App\Models\Currency;
use App\Models\Suppliers;
use App\Models\ExpenseCategory;
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
        Schema::create('bill_expenses', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('ref');
            $table->foreignIdFor(User::class);
            $table->foreignIdFor(Suppliers::class,'supplier_id');
            $table->string('bill_number');
            $table->decimal('amount', 15, 2);
            $table->foreignIdFor(Currency::class,'currency_id');
            $table->decimal('paid', 15, 2);
            $table->string('description')->nullable();
            $table->decimal('Due', 15, 2);
            $table->foreignIdFor(ExpenseCategory::class);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bill_expenses');
    }
};
