<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOfflineCourseActivityTeamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offline_course_activity_teams', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('course_id')->index();
            $table->bigInteger('class_id')->nullable()->index()->comment('Lớp học');
            $table->bigInteger('schedule_id')->nullable()->index()->comment('Buổi học');
            $table->string('topic',256);
            $table->text('description')->nullable();
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->integer('duration')->nullable();
            $table->string('meeting_code',256)->nullable();
            $table->string('join_url',1000)->nullable();
            $table->string('join_web_url',1000)->nullable();
            $table->string('teams_id',1000)->nullable();
            $table->tinyInteger('report')->default(0)->index()->nullable();
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
        Schema::dropIfExists('offline_course_activity_teams');
    }
}
