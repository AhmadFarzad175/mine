<?php

use App\Models\Currency;
use App\Models\MoneyAccount;
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
        Schema::create('money_account_statements', function (Blueprint $table) {
            $table->softDeletes();
            $table->id();
            $table->unsignedBigInteger('record_id')->nullable();
            $table->foreignIdFor(Currency::class);
            $table->foreignIdFor(MoneyAccount::class);
            $table->string('type');
            $table->date('date');
            $table->date('hijri_date')->nullable();
            $table->unsignedBigInteger('amount');
            $table->unsignedBigInteger('rate');
            $table->string('debit_or_credit')->nullable();
            $table->string('ref')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('money_account_statements');
    }
};
