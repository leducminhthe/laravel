<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOfflineCourseActivityQuizTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offline_course_activity_quiz', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('course_id')->index();
            $table->integer('class_id')->index();
            $table->integer('schedule_id')->index();
            $table->integer('quiz_id')->index();
            $table->text('description')->nullable();
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
        Schema::dropIfExists('offline_course_activity_quiz');
    }
}
