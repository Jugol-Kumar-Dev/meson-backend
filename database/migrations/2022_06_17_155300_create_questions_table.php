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
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->longText('title');
            $table->unsignedBigInteger('category_id');
            $table->longText('a');
            $table->longText('b');
            $table->longText('c');
            $table->longText('d');
            $table->longText('e')->nullable();
            $table->integer('mark')->nullable();
            $table->enum('answer', ['a', 'b', 'c', 'd', 'e']);
            $table->longText('feedback')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('questions');
    }
};
