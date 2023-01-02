<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateElUsermedalSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_usermedal_settings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('usermedal_id')->nullable();
            $table->integer('start_date');
            $table->integer('end_date')->nullable();
            $table->boolean('status')->nullable()->default(0);
            $table->bigInteger('created_by')->nullable();
            $table->bigInteger('updated_by')->nullable();
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
        Schema::dropIfExists('el_usermedal_settings');
    }
}
