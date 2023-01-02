<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElMoreLibrariesVideoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_more_libraries_video', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('libraries_id')->nullable();
            $table->string('attachment')->nullable();
            $table->string('name_video')->nullable();
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
        Schema::dropIfExists('el_more_libraries_video');
    }
}
