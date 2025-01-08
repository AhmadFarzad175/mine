<?php

use App\Models\Currency;
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
        Schema::create('money_accounts', function (Blueprint $table) {
            $table->softDeletes(); // Adds deleted_at for soft deletion
            $table->id(); // Auto-incrementing primary key
            $table->string('name');
            $table->decimal('initial_amount', 22, 2)->nullable(); // Default precision and scale
            $table->decimal('amount', 22, 2); // Default value for amount
            $table->foreignIdFor(Currency::class)->constrained(); // Foreign key with automatic constraint
            $table->timestamps(); // Adds created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('money_accounts');
    }
};
