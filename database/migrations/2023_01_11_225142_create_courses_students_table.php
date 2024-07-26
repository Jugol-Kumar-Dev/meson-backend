<?php

use App\Models\Course;
use App\Models\User;
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
        Schema::create('courses_students', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Course::class,'course_id')->constrained('courses')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignIdFor(User::class,'user_id')->constrained('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();
            $table->foreignId('assigned_by')->constrained('users');
            $table->tinyInteger('is_show')->default(false);
            $table->enum('status', ['success', 'warning', 'info', 'danger'])->default('warning');
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
        Schema::dropIfExists('courses_students');
    }
};
