<?php

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
        Schema::create('subscription', function (Blueprint $table) {
            $table->id();
            $table->foreignId('userID')->constrained(table:'users',indexName:'fk_subscription_user');
            $table->foreignId('packagepriceID')->constrained(table:'packageprice',indexName:'fk_subscription_packageprice');
            $table->string('addOnID');
            $table->dateTime('DateValidFrom');
            $table->dateTime('DateValidTo');
            $table->float('PaidAmount');
            $table->foreignId('TransactionID')->constrained(table:'transaction',indexName:'fk_subscription_transaction');
            $table->string('approvedBankName');
            $table->string('status');
            $table->dateTime('cancelledDate');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscription');
    }
};
