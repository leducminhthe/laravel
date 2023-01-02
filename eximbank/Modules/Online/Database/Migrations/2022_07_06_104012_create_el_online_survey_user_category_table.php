<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElOnlineSurveyUserCategoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_online_survey_user_category', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('survey_user_id')->index()->comment('id table el_online_survey_user');
            $table->bigInteger('category_id');
            $table->longText('category_name');
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
        Schema::dropIfExists('el_online_survey_user_category');
    }
}
