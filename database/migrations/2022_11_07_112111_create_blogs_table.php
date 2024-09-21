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
            $table->foreignId('category_id')->nullable()->unsigned();
            $table->string('title')->nullable();
            $table->string('slug')->nullable();
            $table->longText('description')->nullable();
            $table->longText('content')->nullable();
            $table->json('tags')->nullable();
            $table->string('image')->nullable();
            $table->boolean('is_like')->default(false);
            $table->integer('view_count')->default(0);
            $table->boolean('status')->default(0);
            $table->enum('type', ['post', 'blog'])->default('post');
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
