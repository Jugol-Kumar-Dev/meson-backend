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
        Schema::create('chats', function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->foreignIdFor(\App\Models\User::class, 'form_id')->comment('Own Id / sender id');
            $table->foreignIdFor(\App\Models\User::class, 'to_id')->comment('Other User id / where send id');
            $table->text('message')->nullable();
            $table->string('attachment')->nullable();
            $table->enum('delete_type', ['unsend', 'for_me', 'show'])->default('show');
            $table->tinyInteger('is_seen')->default(0);
            $table->softDeletes();
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
        Schema::dropIfExists('chats');
    }
};
