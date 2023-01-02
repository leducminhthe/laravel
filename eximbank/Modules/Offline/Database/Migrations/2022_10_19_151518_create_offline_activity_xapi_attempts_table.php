<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOfflineActivityXapiAttemptsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offline_activity_xapi_attempts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('activity_id')->index();
            $table->bigInteger('user_id')->index();
            $table->bigInteger('course_id')->index();
            $table->bigInteger('user_type')->default(1);
            $table->integer('attempt')->index();
            $table->string('uuid',100)->index();
            $table->timestamps();
            $table->unique(['activity_id', 'user_id', 'attempt'], 'activity_xapi_key_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('offline_activity_xapi_attempts');
    }
}
