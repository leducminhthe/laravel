<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateElObjectExperienceNavigateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_object_experience_navigate', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('experience_navigate_id');
            $table->integer('unit_id')->nullable();
            $table->integer('title_id')->nullable();
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
        Schema::dropIfExists('el_object_experience_navigate');
    }
}
