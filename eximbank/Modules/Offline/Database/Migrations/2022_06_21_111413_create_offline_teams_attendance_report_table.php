<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOfflineTeamsAttendanceReportTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offline_teams_attendance_report', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('course_id')->index();
            $table->integer('class_id')->index();
            $table->integer('schedule_id')->index();
            $table->string('teams_id',200)->index();
            $table->string('report_id',200)->index();
            $table->integer('user_id')->index()->nullable();
            $table->string('user_teams_id',50)->index();
            $table->string('full_name')->nullable();
            $table->string('email')->index()->nullable();
            $table->dateTime('join_time')->index()->nullable();
            $table->dateTime('leave_time')->nullable();
            $table->integer('total_second')->nullable();
            $table->integer('duration')->nullable()->comment('thời gian giây');
            $table->string('role')->nullable()->comment('vai trò');
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
        Schema::dropIfExists('offline_teams_attendance_report');
    }
}
