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
        Schema::create('mocktest_answer', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mocktest_id')->nullable()->constrained('mocktests')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('mocktest_user_id')->nullable()->constrained('mocktest_user')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->foreignId('question_id')->nullable()->constrained('questions');
            $table->string('user_answer')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_mocktest_answer');
    }
};
