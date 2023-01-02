<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElOnlineSurveyCategoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_online_survey_category', function (Blueprint $table) {
            $table->integer('id');
            $table->bigInteger('course_id')->index()->comment('id table online_course');
            $table->bigInteger('course_activity_id')->index()->comment('id table online_course_activity');
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
        Schema::dropIfExists('el_online_survey_category');
    }
}
