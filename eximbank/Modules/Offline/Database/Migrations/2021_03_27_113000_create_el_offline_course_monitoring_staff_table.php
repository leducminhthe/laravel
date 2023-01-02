<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElOfflineCourseMonitoringStaffTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_offline_course_monitoring_staff', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('class_id')->index();
            $table->bigInteger('course_id')->index();
            $table->bigInteger('user_id')->index();
            $table->string('fullname')->nullable();
            $table->string('note')->nullable();
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
        Schema::dropIfExists('el_offline_course_monitoring_staff');
    }
}
