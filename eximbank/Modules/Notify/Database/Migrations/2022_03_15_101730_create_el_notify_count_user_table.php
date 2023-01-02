<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElNotifyCountUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_notify_count_user', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id')->comment('ng nhận thông báo');
            $table->integer('num_notify')->default(0)->comment('số thông báo được nhận');
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
        Schema::dropIfExists('el_notify_count_user');
    }
}
