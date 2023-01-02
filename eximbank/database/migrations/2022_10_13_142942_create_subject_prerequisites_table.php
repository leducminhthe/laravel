<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubjectPrerequisitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subject_prerequisites', function (Blueprint $table) {
            $table->id();
            $table->integer('subject_id');
            $table->integer('subject_prerequisite')->nullable();
            $table->integer('date_finish_prerequisite')->nullable();
            $table->integer('finish_and_score')->nullable();
            $table->integer('score_prerequisite')->nullable();
            $table->integer('select_subject_prerequisite')->nullable();
            $table->integer('status_title')->nullable();
            $table->integer('title_id')->nullable();
            $table->integer('select_title')->nullable();
            $table->integer('status_date_title_appointment')->nullable();
            $table->integer('date_title_appointment')->nullable();
            $table->integer('select_date_title_appointment')->nullable();
            $table->integer('status_join_company')->nullable();
            $table->integer('join_company')->nullable();
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
        Schema::dropIfExists('subject_prerequisites');
    }
}
