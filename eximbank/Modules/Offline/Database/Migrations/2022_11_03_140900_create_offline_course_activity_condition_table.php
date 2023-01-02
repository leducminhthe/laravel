<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOfflineCourseActivityConditionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offline_course_activity_condition', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('course_id')->index();
            $table->bigInteger('class_id')->index();
            $table->bigInteger('schedule_id')->index();
            $table->integer('course_activity_id')->index();
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
        Schema::dropIfExists('offline_course_activity_condition');
    }
}
