<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElRatingLevelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_rating_levels', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->integer('status')->default(1)->comment('1: Bật, 0: Tắt');
            $table->integer('created_by')->default(2);
            $table->integer('updated_by')->default(2);
            $table->integer('unit_by')->nullable();
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
        Schema::dropIfExists('el_rating_levels');
    }
}
