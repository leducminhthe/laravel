<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElOfflineNewTeacherTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_offline_new_teacher', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('course_id')->index();
            $table->integer('class_id')->index();
            $table->integer('schedule_id');
            $table->integer('new_teacher_id');
            $table->float('cost_new_teacher')->nullable();
            $table->float('practical_teaching_new_teacher')->nullable();
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
        Schema::dropIfExists('el_offline_new_teacher');
    }
}
