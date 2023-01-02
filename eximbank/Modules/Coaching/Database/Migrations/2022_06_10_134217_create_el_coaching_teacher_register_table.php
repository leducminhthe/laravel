<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElCoachingTeacherRegisterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_coaching_teacher_register', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('coaching_teacher_id')->comment('GV coaching. id bảng coaching_teacher');
            $table->longText('content')->comment('Nội dung / Kỹ năng');
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->longText('training_objectives')->nullable()->comment('Mục tiêu đào tạo');
            $table->integer('score_training_objectives')->nullable()->comment('Điểm mục tiêu đào tạo');
            $table->string('students')->nullable()->comment('HV học chung');
            $table->longText('comment_status_student')->nullable()->comment('Nhận xét thực trạng của HV');
            $table->integer('score_comment_status_student')->nullable()->comment('Điểm nhận xét thực trạng HV');
            $table->longText('plan_content')->nullable()->comment('Nội dung Kế hoạch');
            $table->longText('plan_start')->nullable()->comment('Bắt đầu Kế hoạch');
            $table->longText('plan_perform')->nullable()->comment('Thực hiện Kế hoạch');
            $table->longText('plan_note')->nullable()->comment('Ghi chú Kế hoạch');
            $table->integer('coaching_mentor_method_id')->nullable()->comment('Phương pháp kèm cặp. id bảng coaching_mentor_method');
            $table->longText('teacher_comment')->nullable()->comment('Nhận xét của GV');
            $table->integer('score_teacher_comment')->nullable()->comment('Điểm Nhận xét của GV');
            $table->longText('note_teacher_comment')->nullable()->comment('Ghi chú Nhận xét GV');
            $table->integer('metor_again')->default(0)->comment('Kèm cặp lại. 1: Có, 0: Không');
            $table->longText('student_comment')->nullable()->comment('Nhận xét của HV');
            $table->integer('score_student_comment')->nullable()->comment('Điểm Nhận xét của HV');
            $table->longText('note_student_comment')->nullable()->comment('Ghi chú Nhận xét HV');
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
        Schema::dropIfExists('el_coaching_teacher_register');
    }
}
