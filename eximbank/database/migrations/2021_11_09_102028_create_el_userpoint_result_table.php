<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateElUserpointResultTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_userpoint_result', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('setting_id');
            $table->integer('user_id');
            $table->string('content');
            $table->integer('point');
            $table->integer('ref')->nullable();
            $table->integer('item_id')->nullable();
            $table->integer('type')->nullable();
            $table->integer('type_promotion')->comment('0: Learn to earn, 1: Click to earn');
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
        Schema::dropIfExists('el_userpoint_result');
    }
}
