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
        Schema::create('assignee', function (Blueprint $table) {
            $table->id();
            $table->foreignId('AssignorID')->constrained(table:'businessuser',indexName:'fk_assignee_businessuser');
            $table->foreignId('AssigneeID')->constrained(table:'firmuser',indexName:'fk_assignee_firmuser');
            $table->dateTime('appointedDateValidFrom');
            $table->dateTime('appointedDateValidTo');
            $table->string('allowedAccessCode');
            $table->string('Status');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assignee');
    }
};
