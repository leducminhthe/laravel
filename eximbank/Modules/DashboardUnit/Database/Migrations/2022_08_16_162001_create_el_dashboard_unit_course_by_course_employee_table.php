<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElDashboardUnitCourseByCourseEmployeeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_dashboard_unit_course_by_course_employee', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('unit_id')->index();
            $table->string('unit_code')->nullable();
            $table->integer('course_employee')->nullable();
            $table->integer('t1')->nullable();
            $table->integer('t2')->nullable();
            $table->integer('t3')->nullable();
            $table->integer('t4')->nullable();
            $table->integer('t5')->nullable();
            $table->integer('t6')->nullable();
            $table->integer('t7')->nullable();
            $table->integer('t8')->nullable();
            $table->integer('t9')->nullable();
            $table->integer('t10')->nullable();
            $table->integer('t11')->nullable();
            $table->integer('t12')->nullable();
            $table->integer('total')->nullable();
            $table->integer('year')->nullable();
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
        Schema::dropIfExists('el_dashboard_unit_course_by_course_employee');
    }
}