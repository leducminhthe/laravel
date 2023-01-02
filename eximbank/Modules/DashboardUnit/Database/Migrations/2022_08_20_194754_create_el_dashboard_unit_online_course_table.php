<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElDashboardUnitOnlineCourseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_dashboard_unit_online_course', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('unit_id')->index();
            $table->string('unit_code')->nullable();
            $table->integer('course_id')->index();
            $table->integer('total')->default(0);
            $table->integer('unlearned')->default(0)->comment('SL Chưa học');
            $table->integer('studying')->default(0)->comment('SL Đang học');
            $table->integer('completed')->default(0)->comment('SL Hoàn thành');
            $table->integer('uncompleted')->default(0)->comment('SL Chưa hoàn thành');
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();
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
        Schema::dropIfExists('el_dashboard_unit_online_course');
    }
}
