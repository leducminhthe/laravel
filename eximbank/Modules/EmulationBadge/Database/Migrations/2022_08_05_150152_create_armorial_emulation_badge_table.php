<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArmorialEmulationBadgeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('armorial_emulation_badge', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('emulation_badge_id')->index();
            $table->string('level');
            $table->string('image');
            $table->integer('type')->comment('1: Học nanh nhất; 2: Điểm cao nhất; 3: Hoàn thành sớm nhất');
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
        Schema::dropIfExists('armorial_emulation_badge');
    }
}
