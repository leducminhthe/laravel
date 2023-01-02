<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateElTotalTimeUserLearnYearTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_total_time_user_learn_year', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('total_time');
            $table->string('full_name');
            $table->integer('unit_id')->nullable();
            $table->string('unit_name')->nullable();
            $table->integer('title_id')->nullable();
            $table->string('title_name')->nullable();
            $table->integer('year');
            $table->integer('time_second');
            $table->integer('title_time_new');
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
        Schema::dropIfExists('el_total_time_user_learn_year');
    }
}
