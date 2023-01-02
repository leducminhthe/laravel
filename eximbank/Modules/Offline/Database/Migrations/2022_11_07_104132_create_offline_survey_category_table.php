<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOfflineSurveyCategoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offline_survey_category', function (Blueprint $table) {
            $table->integer('id');
            $table->bigInteger('course_id')->index()->comment('id table offline_course');
            $table->bigInteger('course_activity_id')->index()->comment('id table offline_course_activity');
            $table->bigInteger('template_id');
            $table->longText('name');
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
        Schema::dropIfExists('offline_survey_category');
    }
}
