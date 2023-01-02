<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateElTrainingTeacherHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_training_teacher_history', function (Blueprint $table) {
            $table->id();
            $table->integer('teacher_id')->comment('Giảng Viên');
            $table->integer('course_id')->comment('Khoá học');
            $table->integer('class_id')->comment('Lớp học');
            $table->integer('schedule_id')->commet('Buổi học');
            $table->integer('teacher_type')->default(1)->commet('Loại GV. 1 => Chính; 2 => Trợ giảng');
            $table->integer('num_schedule')->default(0)->comment('Số buổi');
            $table->integer('num_hour')->default(0)->comment('Số giờ học');
            $table->string('cost')->nullable();
            $table->string('month')->nullable();
            $table->string('year')->nullable();
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
        Schema::dropIfExists('el_training_teacher_history');
    }
}
