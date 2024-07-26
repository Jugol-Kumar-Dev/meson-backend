<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mocktests', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('total_q')->nullable();
            $table->integer('duration')->nullable();
            $table->json('categories')->nullable();
            $table->json('questions')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->boolean('status')->default(0);
            $table->string('password')->nullable();
            $table->integer('pass_mark')->nullable();
            $table->integer('minus_mark')->nullable()->comment('in parsentage');
            $table->float('pass_parsentage')->nullable();
            $table->enum('exam_type', ['main', 'practice'])->default('practice');

            $table->timestamp('start_time')->nullable();
            $table->timestamp('end_time')->nullable();


            $table->integer('perPage')->nullable();
            $table->text('about')->nullable();
            $table->boolean('show_student')->default(false);
            $table->enum('question_type', ['rand','category','questions'])->default('rand');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mocktests');
    }
};
