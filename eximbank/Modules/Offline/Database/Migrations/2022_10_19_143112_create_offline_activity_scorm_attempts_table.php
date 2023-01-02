<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOfflineActivityScormAttemptsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offline_activity_scorm_attempts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('activity_id')->index();
            $table->bigInteger('user_id')->index();
            $table->bigInteger('user_type')->default(1);
            $table->integer('attempt')->index();
            $table->string('lesson_location', 100)->nullable();
            $table->text('suspend_data')->nullable();
            $table->timestamps();
            $table->unique(['activity_id', 'user_id', 'attempt'], 'activity_scorm_key_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('offline_activity_scorm_attempts');
    }
}
