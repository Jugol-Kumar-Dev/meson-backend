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
        Schema::create('blogs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unsigned();
            $table->foreignId('category_id')->unsigned();
            $table->string('title');
            $table->string('slug');
            $table->longText('description');
            $table->longText('content');
            $table->json('tags');
            $table->string('image')->nullable();
            $table->boolean('is_like')->default(false);
            $table->integer('view_count')->default(0);
            $table->boolean('publication_status')->default(0);
            $table->boolean('is_featured')->default(0);
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
        Schema::dropIfExists('blogs');
    }
};
