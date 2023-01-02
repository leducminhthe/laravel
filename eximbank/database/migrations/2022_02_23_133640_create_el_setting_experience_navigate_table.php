<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateElSettingExperienceNavigateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_setting_experience_navigate', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('start_date');
            $table->string('end_date');
            $table->integer('total_count');
            $table->integer('date_count');
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
        Schema::dropIfExists('el_setting_experience_navigate');
    }
}
