<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElReportNewExportBc05Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_report_new_export_bc05', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->nullable();
            $table->bigInteger('user_type')->default(1);
            $table->string('user_code')->nullable();
            $table->string('fullname')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->bigInteger('course_id')->nullable();
            $table->string('course_code')->nullable();
            $table->string('course_name')->nullable();
            $table->integer('class_id')->nullable()->index();
            $table->string('class_code')->nullable();
            $table->string('class_name')->nullable();
            $table->tinyInteger('course_type')->nullable()->index();
            $table->bigInteger('subject_id')->nullable()->index();
            $table->string('subject_name')->nullable();
            $table->string('training_unit')->nullable();
            $table->integer('training_type_id')->nullable()->index();
            $table->string('training_type_name')->nullable();
            $table->integer('training_form_id')->nullable();
            $table->string('training_form_name')->nullable();
            $table->string('training_area_id')->nullable();
            $table->string('training_area_name')->nullable();
            $table->string('course_time')->nullable();
            $table->integer('attendance')->nullable();
            $table->dateTime('start_date')->nullable()->index();
            $table->dateTime('end_date')->nullable()->index();
            $table->string('score')->nullable();
            $table->tinyInteger('result')->nullable();
            $table->integer('area_id')->nullable()->index();
            $table->string('area_code')->nullable();
            $table->string('area_name')->nullable();
            $table->integer('unit_id_1')->nullable()->index()->comment('đơn vị trực tiếp của user');
            $table->string('unit_code_1')->nullable();
            $table->string('unit_name_1')->nullable();
            $table->integer('unit_id_2')->nullable();
            $table->string('unit_code_2')->nullable();
            $table->string('unit_name_2')->nullable();
            $table->integer('unit_id_3')->nullable();
            $table->string('unit_code_3')->nullable();
            $table->string('unit_name_3')->nullable();
            $table->string('position_name')->nullable();
            $table->integer('title_id')->nullable()->index();
            $table->string('title_code')->nullable();
            $table->string('title_name')->nullable();
            $table->integer('status_user')->nullable();
            $table->string('note')->nullable();
            $table->string('unit_type')->nullable()->comment('Loại đơn vị hội sở/kinh doanh');
            $table->integer('course_employee')->index()->nullable()->comment('Nhân viên tân tuyển/hiện hữu');
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
        Schema::dropIfExists('el_report_new_export_bc05');
    }
}
