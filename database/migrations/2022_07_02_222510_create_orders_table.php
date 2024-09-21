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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->integer('course_id')->nullable();
            $table->integer('user_id');
            $table->integer('instructor_id')->nullable();
//            $table->text('transaction_id');
            $table->string('payment_method', 191);
            $table->decimal('total_amount', 8,2);
            $table->decimal('coupon_discount', 5,2)->nullable();
            $table->decimal('pay_amount', 8,2);
            $table->string('currency', 191);
            $table->integer('duration')->nullable();
            $table->string('duration_type')->nullable();
            $table->date('enroll_start')->nullable();
            $table->date('enroll_expire')->nullable();
            $table->string('status')->nullable();
            $table->boolean('is_show')->default(1);
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
        Schema::dropIfExists('orders');
    }
};
