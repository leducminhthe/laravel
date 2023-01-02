<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('room',20)->index();
            $table->text('message');
            $table->integer('from')->index();
            $table->integer('to')->index();
            $table->integer('suggest_id')->index()->nullable();
            $table->tinyInteger('seen')->nullable()->default(0)->comment('0 not seen, 1 seen');
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
        Schema::dropIfExists('messages');
    }
}
