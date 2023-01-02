<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOfflineSurveyUserAnswerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offline_survey_user_answer', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('survey_user_question_id')->index()->comment('id table offline_survey_user_question');
            $table->bigInteger('answer_id');
            $table->string('answer_code')->nullable();
            $table->longText('answer_name')->nullable();
            $table->longText('text_answer')->nullable();
            $table->string('check_answer_matrix')->nullable();
            $table->longText('answer_matrix')->nullable();
            $table->integer('is_check')->default(0)->comment('Check câu trả lời');
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
        Schema::dropIfExists('offline_survey_user_answer');
    }
}
