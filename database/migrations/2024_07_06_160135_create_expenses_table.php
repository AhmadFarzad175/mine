<?php

use App\Models\User;
use App\Models\Currency;
use App\Models\Suppliers;
use App\Models\ExpenseCategory;
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
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            // $table->string('ref');
            $table->foreignIdFor(User::class);
            $table->decimal('amount', 15, 2);
            $table->foreignIdFor(ExpenseCategory::class);
            $table->foreignIdFor(Currency::class,'currency_id');
            $table->string('description')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

     /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
