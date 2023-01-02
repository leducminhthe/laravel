<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElOnlineSurveyQuestionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_online_survey_question', function (Blueprint $table) {
            $table->integer('id');
            $table->string('code')->nullable();
            $table->longText('name');
            $table->bigInteger('course_id')->index()->comment('id table online_course');
            $table->bigInteger('course_activity_id')->index()->comment('id table online_course_activity');
            $table->bigInteger('category_id');
            $table->string('type')->nullable()->comment('multiple_choice, essay');
            $table->integer('multiple')->default(0)->comment('1: Chọn nhiều, 0: Chọn 1');
            $table->integer('obligatory')->default(0)->comment('1: Bắt buộc, 0: Không');
            $table->tinyInteger('num_order')->nullable();
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
        Schema::dropIfExists('el_online_survey_question');
    }
}
