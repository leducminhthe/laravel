<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElCapabilitiesGroupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_capabilities_group', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->longText('basic_knowledge')->nullable();
            $table->longText('medium_knowledge')->nullable();
            $table->longText('advanced_knowledge')->nullable();
            $table->longText('profession_knowledge')->nullable();

            $table->longText('basic_skills')->nullable();
            $table->longText('medium_skills')->nullable();
            $table->longText('advanced_skills')->nullable();
            $table->longText('profession_skills')->nullable();

            $table->longText('basic_expression')->nullable();
            $table->longText('medium_expression')->nullable();
            $table->longText('advanced_expression')->nullable();
            $table->longText('profession_expression')->nullable();

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
        Schema::dropIfExists('el_capabilities_group');
    }
}
