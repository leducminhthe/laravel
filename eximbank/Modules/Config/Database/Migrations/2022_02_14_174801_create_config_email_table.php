<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConfigEmailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('config_email', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('driver')->nullable();
            $table->string('host')->nullable();
            $table->integer('port')->nullable();
            $table->string('user')->nullable();
            $table->string('password')->nullable();
            $table->string('encryption')->nullable();
            $table->string('from_name')->nullable();
            $table->string('address')->nullable();
            $table->integer('company')->nullable();
            $table->integer('send_noty')->nullable();
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
        Schema::dropIfExists('config_email');
    }
}
