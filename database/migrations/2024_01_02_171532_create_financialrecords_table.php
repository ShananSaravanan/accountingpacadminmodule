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
        Schema::create('financialrecords', function (Blueprint $table) {
            $table->id();
            $table->foreignId('businessID')->constrained(table:'business',indexName:'fk_financialrecord_business');
            $table->float('amount');
            $table->string('recordCategory');
            $table->string('description'); 
            $table->dateTime('recordedtime');
            $table->softDeletes();   
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('financialrecords');
    }
};
