<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElRegisterTrainingPlanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_register_training_plan', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('course_type')->default(1)->comment('1: online, 2:offline');
            $table->bigInteger('training_program_id')->index()->unsigned();
            $table->bigInteger('level_subject_id')->unsigned()->nullable();
            $table->bigInteger('subject_id')->index();
            $table->string('name');
            $table->dateTime('start_date');
            $table->dateTime('end_date')->nullable();
            $table->string('course_time')->nullable()->comment('Thời lượng');
            $table->text('target')->nullable()->comment('Mục tiêu');
            $table->longText('content')->nullable()->comment('Nội dung');
            $table->bigInteger('training_form_id')->index()->nullable()->comment('loại hình đào tạo');
            $table->string('training_area_id')->nullable()->comment('Khu vực đào tạo');
            $table->string('teacher_id')->nullable()->comment('Các GV');
            $table->integer('course_employee')->default(0)->nullable();
            $table->integer('max_student')->default(0)->nullable();
            $table->bigInteger('created_by')->index()->default(2);
            $table->bigInteger('updated_by')->index()->default(2);
            $table->integer('unit_by')->nullable();
            $table->tinyInteger('send')->default(0);
            $table->tinyInteger('status')->default(2);
            $table->string('note_status')->nullable();
            $table->integer('course_belong_to')->nullable()->comment('Khoá học thuộc. 1: Đào tạo nội bộ; 2: Đào tạo chéo');
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
        Schema::dropIfExists('el_register_training_plan');
    }
}
