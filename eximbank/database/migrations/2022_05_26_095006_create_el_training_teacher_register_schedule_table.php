<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateElTrainingTeacherRegisterScheduleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_training_teacher_register_schedule', function (Blueprint $table) {
            $table->id();
            $table->integer('course_id');
            $table->integer('teacher_id');
            $table->integer('user_id');
            $table->integer('class_id')->nullable();
            $table->integer('status')->default(1);
            $table->integer('approve')->default(2);
            $table->text('note')->nullable();
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
        Schema::dropIfExists('el_training_teacher_register_schedule');
    }
}
