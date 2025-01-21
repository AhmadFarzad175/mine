<?php

use App\Models\User;
use App\Models\Currency;
use App\Models\MoneyAccount;
use App\Models\Stakeholder;
use App\Models\StakeholderAccount;
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
        Schema::create('stakeholder_statements', function (Blueprint $table) {
            $table->softDeletes();
            $table->id();
            $table->foreignIdFor(User::class);
            $table->foreignIdFor(Currency::class);
            $table->foreignIdFor(Stakeholder::class);
            $table->foreignIdFor(StakeholderAccount::class);
            $table->foreignIdFor(MoneyAccount::class)->nullable();
            $table->unsignedBigInteger('record_id')->nullable();
            $table->string('type');
            $table->date('date');
            $table->unsignedBigInteger('amount');
            $table->bigInteger('balance');
            $table->unsignedBigInteger('rate');
            $table->string('pay_or_receive')->nullable();
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
        Schema::dropIfExists('stakeholder_statements');
    }
};
