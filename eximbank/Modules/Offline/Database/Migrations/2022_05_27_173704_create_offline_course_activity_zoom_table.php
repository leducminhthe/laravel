<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOfflineCourseActivityZoomTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offline_course_activity_zoom', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('course_id')->index();
            $table->bigInteger('class_id')->nullable()->index()->comment('Lớp học');
            $table->bigInteger('schedule_id')->nullable()->index()->comment('Buổi học');
            $table->string('topic',256);
            $table->text('description')->nullable();
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->integer('duration')->nullable();
            $table->tinyInteger('type')->nullable();
            $table->string('status')->nullable();
            $table->string('join_url')->nullable();
            $table->text('start_url')->nullable();
            $table->string('password')->nullable();
            $table->bigInteger('zoom_id')->nullable();
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
        Schema::dropIfExists('offline_course_activity_zoom');
    }
}
