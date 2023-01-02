<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElTargetManagerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_target_manager', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->comment('Tên nhóm đối tượng');
            $table->bigInteger('parent_id')->comment('id el_target_manager_parent');
            $table->longText('group_object')->nullable();
            $table->integer('num_hour_student')->default(0)->comment('Số giờ học của HV');
            $table->integer('num_course_student')->default(0)->comment('Số khoá học của HV');
            $table->integer('num_hour_teacher')->default(0)->comment('Số giờ giảng dạy của GV');
            $table->integer('num_course_teacher')->default(0)->comment('Số khoá giảng dạy của GV');
            $table->integer('type')->default(1)->comment('1: Nhóm chức danh; 2: Nhóm Cá nhân');
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->integer('unit_by')->nullable();
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
        Schema::dropIfExists('el_target_manager');
    }
}
