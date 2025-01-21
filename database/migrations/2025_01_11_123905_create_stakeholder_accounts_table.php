<?php

use App\Models\Currency;
use App\Models\Stakeholder;
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
        Schema::create('stakeholder_accounts', function (Blueprint $table) {
            $table->softDeletes();
            $table->id();
            $table->string('name');
            $table->date('date');
            $table->decimal('amount', 20, 2); 
            $table->decimal('initial_amount', 10, 2); 
            $table->foreignIdFor(Currency::class);
            $table->foreignIdFor(Stakeholder::class);
            $table->string('pay_or_receive')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stakeholder_accounts');
    }
};
