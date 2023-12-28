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
        Schema::create('packageprice', function (Blueprint $table) {
            $table->id();
            $table->foreignId('PackageID')->constrained(table:'package',indexName:'fk_packageprice_package');
            $table->integer('duration');
            $table->float('baseprice');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('packageprice');
    }
};
