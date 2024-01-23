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
            $table->foreignId('firmOwnerID')->constrained(table:'firmuser',indexName:'fk_firm_firmuser');
            $table->foreignId('firmTypeID')->constrained(table:'firmtype',indexName:'fk_firm_firmtype');
            $table->foreignId('addressID')->constrained(table:'address',indexName:'fk_firm_address');
            $table->string('AF_NO');
            $table->string('SSM_NO');
            $table->string('contactNo');
            $table->string('emailAddress');
            $table->string('status');
            $table->integer('userLimit');
            $table->string('logo');
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
