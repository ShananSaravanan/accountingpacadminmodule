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
        Schema::create('firm', function (Blueprint $table) {
            $table->id();
            $table->string('firmName');
            $table->foreignId('firmOwnerID')->constrained(table:'users',indexName:'fk_firm_user');
            $table->foreignId('firmTypeID')->constrained(table:'firmtype',indexName:'fk_firm_firmtype');
            $table->string('AF_NO');
            $table->string('SSM_NO');
            $table->string('contactNo');
            $table->string('emailAddress');
            $table->string('status');
            $table->integer('userLimit');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('firm');
    }
};
