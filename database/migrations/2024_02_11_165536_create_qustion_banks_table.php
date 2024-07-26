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
        Schema::create('question_banks', function (Blueprint $table) {
            $table->id();
            $table->string('question_type')->nullable();
            $table->foreignId('category_id')->default(1)->constrained('categories');
            $table->string('random_answers')->nullable();
            $table->text('correct_feedback')->nullable();
            $table->text('incorrect_feedback')->nullable();
            $table->integer('points')->nullable();
            $table->text('question')->nullable();
            $table->string('correct_answer')->nullable();
            $table->string('answer_a')->nullable();
            $table->string('answer_b')->nullable();
            $table->string('answer_c')->nullable();
            $table->string('answer_d')->nullable();
            $table->string('answer_e')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('qustion_banks');
    }
};
