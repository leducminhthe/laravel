<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateElRelatedSubjectTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_related_subject', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('subject_id');
            $table->integer('compel')->nullable();
            $table->integer('finish_5day')->nullable();
            $table->integer('finish_soon_end')->nullable();
            $table->integer('score_5')->nullable();
            $table->integer('score_8')->nullable();
            $table->integer('number_lesson')->nullable();
            $table->integer('new_subject')->nullable();
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
        Schema::dropIfExists('el_related_subject');
    }
}
