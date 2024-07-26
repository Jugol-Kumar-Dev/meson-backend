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
        Schema::create('given_mocktest_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mocktest_user_id')->constrained('mocktest_user')->onUpdate('cascade');
            $table->foreignId('mocktest_id')->constrained('mocktests')->onUpdate('cascade');
            $table->foreignIdFor(\App\Models\Question::class, 'qustion_id')->constrained('questions')->cascadeOnUpdate();
            $table->foreignIdFor(\App\Models\User::class, 'user_id')->nullable()->constrained('users')->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('given_ans')->nullable();
            $table->boolean('is_anserd')->default(true);
            $table->boolean('is_correct')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('given_mocktest_answers');
    }
};
