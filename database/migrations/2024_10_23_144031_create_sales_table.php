<?php

use App\Models\Currency;
use App\Models\Customer;
use App\Models\User;
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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('ref');
            $table->foreignIdFor(User::class);
            $table->foreignIdFor(Customer::class,'customer_id');
            $table->foreignIdFor(Currency::class,'currency_id');
            $table->decimal('total_amount', 15, 2);
            $table->decimal('paid', 15, 2)->default(0);
            $table->decimal('discount', 15, 2)->default(0);
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
        Schema::dropIfExists('sales');
    }
};
