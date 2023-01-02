<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuizTimeFinishPointTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quiz_time_finish_point', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('quiz_id')->index();
            $table->integer('userpoint_setting_id')->index();
            $table->integer('rank');
            $table->integer('score');
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
        Schema::dropIfExists('quiz_time_finish_point');
    }
}
