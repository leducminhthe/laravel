<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOfflineTeamsReportTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offline_teams_report', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('course_id')->index();
             $table->integer('class_id')->index();
            $table->integer('schedule_id')->index();
            $table->string('teams_id',200)->index();
            $table->string('report_id',200)->index();
            $table->string('title',500)->nullable();
            $table->integer('duration')->nullable();
            $table->integer('total_participant')->nullable();
            $table->dateTime('meeting_start')->nullable();
            $table->dateTime('meeting_end')->nullable();
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
        Schema::dropIfExists('offline_teams_report');
    }
}
