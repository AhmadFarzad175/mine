<?php

use App\Models\Currency;
use App\Models\Owner;
use App\Models\User;
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
        Schema::create('owner_pickups', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->foreignIdFor(Owner::class);
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
        Schema::dropIfExists('owner_pickups');
    }
};
