<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElOnlineSurveyAnswerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_online_survey_answer', function (Blueprint $table) {
            $table->integer('id');
            $table->string('code')->nullable();
            $table->longText('name')->nullable();
            $table->bigInteger('course_id')->index()->comment('id table online_course');
            $table->bigInteger('course_activity_id')->index()->comment('id table online_course_activity');
            $table->bigInteger('question_id');
            $table->integer('is_text')->default(0)->comment('Nhập text');
            $table->integer('is_row')->default(1)->comment('Đáp án dòng');
            $table->string('icon')->nullable();
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
        Schema::dropIfExists('el_online_survey_answer');
    }
}
