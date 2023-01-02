<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateElSocialNetworkNotyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_social_network_noty', function (Blueprint $table) {
            $table->id();
            $table->integer('user_1')->comment('Người gửi');
            $table->integer('user_2')->comment('Người nhận');
            $table->text('noty');
            $table->integer('type')->comment('0: Kết bạn');
            $table->integer('status');
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
        Schema::dropIfExists('el_social_network_noty');
    }
}
