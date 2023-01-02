<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOfflineCourseTimeUserLearnTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offline_course_time_user_learn', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('course_id');
            $table->integer('user_id');
            $table->integer('time')->default(0);
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
        Schema::dropIfExists('offline_course_time_user_learn');
    }
}