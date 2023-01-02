<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateElLessonStarTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_lesson_star', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->comment('HV đánh giá');
            $table->integer('num_star')->nullable();
            $table->integer('course_id')->comment('Khoá học');
            $table->integer('course_type')->comment('1: Online, 2: Offline');
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
        Schema::dropIfExists('el_lesson_star');
    }
}
