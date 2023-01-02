<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOfflineSurveyUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offline_survey_user', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('template_id');
            $table->bigInteger('user_id');
            $table->bigInteger('course_id')->index()->comment('id table offline_course');
            $table->bigInteger('course_activity_id')->index()->comment('id table offline_course_activity');
            $table->text('more_suggestions')->nullable()->comment('Đề xuất khác');
            $table->integer('send')->default(0);
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
        Schema::dropIfExists('offline_survey_user');
    }
}
