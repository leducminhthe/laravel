<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElOfflineTeacherClassTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_offline_teacher_class', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('class_id')->index();
            $table->bigInteger('course_id')->index();
            $table->bigInteger('teacher_id')->index();
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
        Schema::dropIfExists('el_offline_teacher_class');
    }
}
