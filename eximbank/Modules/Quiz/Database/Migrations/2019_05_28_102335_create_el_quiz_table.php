<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElQuizTable extends Migration
{
    public function up()
    {
        Schema::create('el_quiz', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code', 150)->index();
            $table->string('name');
            $table->bigInteger('unit_id')->index()->nullable()->comment('Đơn vị tạo kỳ thi');
            $table->bigInteger('type_id')->index()->nullable()->comment('Danh mục Loại kỳ thi');
            $table->integer('limit_time')->default(60)->comment('Thời gian làm bài: phút');
            $table->integer('view_result')->default(0)->comment('1: được xem kết quả, 0: không được xem kết quả');
            $table->tinyInteger('shuffle_answers')->default(0)->comment('Xáo trộn đáp án');
            $table->tinyInteger('shuffle_question')->default(0)->comment('Xáo trộn câu hỏi');
            $table->tinyInteger('paper_exam')->default(0)->comment('Thi giấy');
            $table->integer('questions_perpage')->default(10)->comment('Số câu hỏi 1 trang');
            $table->double('pass_score')->default(5)->comment('Điểm chuẩn');
            $table->double('max_score')->default(10)->comment('Điểm tối đa');
            $table->string('description')->nullable();
            $table->integer('max_attempts')->default(1)->comment('Số lần làm bài');
            $table->integer('grade_methor')->default(1)->comment('Cách tính điểm');
            $table->integer('is_open')->index()->default(0);
            $table->integer('status')->index()->default(2)->comment('1: Duyệt, 2: Chưa duyệt, 0:Từ chối');
            $table->bigInteger('course_id')->index()->nullable()->comment('ID khóa học');
            $table->integer('course_type')->index()->nullable()->comment('Loại khóa học');
            $table->integer('quiz_type')->index()->nullable()->comment('Loại kỳ thi, 1:online, 2:tập trung, 3: độc lập');
            $table->string('img')->nullable();
            $table->tinyInteger('webcam_require')->default(0);
            $table->tinyInteger('question_require')->default(0);
            $table->tinyInteger('times_shooting_webcam')->default(0);
            $table->tinyInteger('times_shooting_question')->default(0);
            $table->bigInteger('created_by')->nullable()->index();
            $table->bigInteger('updated_by')->nullable()->index();
            $table->integer('unit_by')->nullable()->index();
            $table->integer('quiz_template_id')->nullable()->index();
            $table->bigInteger('approved_by')->nullable()->index();
            $table->dateTime('time_approved')->nullable();
            $table->string('quiz_location')->nullable();
            $table->integer('show_name')->default(0);
            $table->string('approved_step')->nullable();
            $table->tinyInteger('flag')->index()->default(0)->comment('1 chạy cron tạo bộ đề');
            $table->dateTime('start_quiz')->nullable()->index()->comment('Thời gian bắt đầu ca thi 1');
            $table->dateTime('end_quiz')->nullable()->comment('Thời gian kết thúc ca thi cuối');
            $table->integer('status_grading')->default(2)->comment('Trạng thái chấm điểm, 1:Đã chấm, 2:Chưa chấm, 3: Dang dỡ');
            $table->tinyInteger('teacher_grade')->index()->nullable()->default(0)->comment('chờ chấm điểm');
            $table->tinyInteger('full_screen')->index()->nullable()->default(0)->comment('phóng to màn hình khi thi');
            $table->integer('new_tab')->nullable()->default(0)->comment('SL cho phép mở tab mới khi thi');
            $table->integer('quiz_not_register')->nullable()->default(0)->comment('Kỳ thi không cần ghi danh');
            $table->string('unit_create_quiz')->nullable()->comment('Đơn vị tạo đề thi');
            $table->string('quiz_type_by_offline')->nullable()->comment('Loại kỳ thi khoá học tập trung. entrance_quiz_id: Kỳ thi đầu vào; quiz_id: kỳ thi cuối khoá');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('el_quiz');
    }
}
