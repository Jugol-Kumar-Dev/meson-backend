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
        Schema::create('zooms', function (Blueprint $table) {
            $table->id();
            $table->string('photo')->nullable();
            $table->string('meeting_id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('owner_id');
            $table->string('meeting_title');
            $table->dateTime('start_time')->nullable();
            $table->text('zoom_url');
            $table->string('link_by')->nullable();
            $table->string('type')->nullable();
            $table->string('agenda')->nullable();
            $table->unsignedBigInteger('course_id')->nullable();
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
        Schema::dropIfExists('zooms');
    }
};
