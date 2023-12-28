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
        Schema::create('firmuser', function (Blueprint $table) {
            $table->id();
            $table->foreignId('firmID')->constrained(table:'firm',indexName:'fk_firmuser_firm');
            $table->foreignId('userID')->constrained(table:'users',indexName:'fk_firmuser_user');
            $table->string('MIA_NO');
            $table->string('PC_NO');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('firmuser');
    }
};
