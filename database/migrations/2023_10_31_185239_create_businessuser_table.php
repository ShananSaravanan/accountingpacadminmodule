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
        Schema::create('businessuser', function (Blueprint $table) {
            $table->id();
            $table->foreignId('businessID')->constrained(table:'business',indexName:'fk_businessuser_business');
            $table->foreignId('userID')->constrained(table:'users',indexName:'fk_businessuser_users');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('businessuser');
    }
};
