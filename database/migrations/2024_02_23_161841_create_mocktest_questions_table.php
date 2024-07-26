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
        Schema::create('mocktest_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mocktest_id')->constrained('mocktests')->onUpdate('cascade');
            $table->foreignIdFor(\App\Models\Question::class, 'qustion_id')->constrained('questions')->cascadeOnUpdate();
            $table->foreignIdFor(\App\Models\User::class, 'user_id')->nullable()->constrained('users')->cascadeOnUpdate()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mocktest_questions');
    }
};
