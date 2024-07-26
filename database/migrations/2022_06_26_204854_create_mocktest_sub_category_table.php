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
        Schema::create('mocktest_sub_category', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mocktest_id');
            $table->unsignedBigInteger('sub_category_id');
            $table->unsignedInteger('question')->default(0);
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
        Schema::dropIfExists('mocktest_sub_category');
    }
};
