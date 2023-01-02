<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateElTrainingTeacherStarTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_training_teacher_star', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->comment('HV đánh giá');
            $table->integer('teacher_id')->comment('GV được đánh giá');
            $table->float('num_star')->nullable();
            $table->integer('course_id')->comment('Khoá học');
            $table->integer('course_type')->comment('1: Online, 2: Offline');
            $table->integer('class_id')->nullable()->comment('Lớp học');
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
        Schema::dropIfExists('el_training_teacher_star');
    }
}
