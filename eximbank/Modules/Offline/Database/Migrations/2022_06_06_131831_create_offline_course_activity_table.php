<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOfflineCourseActivityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offline_course_activity', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->integer('course_id')->index();
            $table->bigInteger('class_id')->index();
            $table->bigInteger('schedule_id')->index();
            $table->integer('activity_id')->index();
            $table->integer('subject_id')->index();
            $table->integer('num_order');
            $table->integer('lesson_id')->nullable();
            $table->tinyInteger('status')->index()->default('0')->comment('0: ẩn, 1: hiện');
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
        Schema::dropIfExists('offline_course_activity');
    }
}
