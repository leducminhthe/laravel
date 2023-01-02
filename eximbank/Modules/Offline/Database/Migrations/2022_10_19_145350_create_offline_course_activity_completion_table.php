<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOfflineCourseActivityCompletionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offline_course_activity_completion', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->index();
            $table->bigInteger('user_type')->default(1)->nullable();
            $table->bigInteger('activity_id')->index()->comment('offline_course_activity');
            $table->bigInteger('course_id')->index();
            $table->bigInteger('status')->default(0);
            $table->unique(['user_id', 'activity_id'], 'offline_course_activity_completion_unique');
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
        Schema::dropIfExists('offline_course_activity_completion');
    }
}
