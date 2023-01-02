<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOfflineSurveyUserCategoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offline_survey_user_category', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('survey_user_id')->index()->comment('id table offline_survey_user');
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
        Schema::dropIfExists('offline_survey_user_category');
    }
}
