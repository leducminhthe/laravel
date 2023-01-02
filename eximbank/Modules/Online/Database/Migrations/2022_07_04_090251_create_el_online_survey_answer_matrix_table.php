<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElOnlineSurveyAnswerMatrixTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_online_survey_answer_matrix', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('course_id')->index()->comment('id table online_course');
            $table->bigInteger('course_activity_id')->index()->comment('id table online_course_activity');
            $table->bigInteger('question_id');
            $table->string('code')->nullable();
            $table->integer('answer_row_id')->comment('id answer dòng');
            $table->integer('answer_col_id')->comment('id answer cột');
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
        Schema::dropIfExists('el_online_survey_answer_matrix');
    }
}
