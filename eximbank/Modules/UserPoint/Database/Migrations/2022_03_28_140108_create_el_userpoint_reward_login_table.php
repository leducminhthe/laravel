<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElUserpointRewardLoginTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_userpoint_reward_login', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('start_date');
            $table->string('end_date');
            $table->integer('number_login');
            $table->integer('reward_point');
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
        Schema::dropIfExists('el_userpoint_reward_login');
    }
}
