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
        Schema::create('postcode', function (Blueprint $table) {
            $table->id();
            $table->string('postcode');
            $table->string('location');
            $table->foreignId('postOfficeID')->constrained(table:'postoffice',indexName:'fk_postcode_postoffice');
            $table->foreignId('stateCodeID')->constrained(table:'statecode',indexName:'fk_postcode_statecode');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('postcode');
    }
};
