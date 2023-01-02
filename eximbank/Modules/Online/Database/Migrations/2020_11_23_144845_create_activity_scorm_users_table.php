<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActivityScormUsersTable extends Migration
{
    public function up()
    {
        Schema::create('el_activity_scorm_users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('activity_id')->index();
            $table->bigInteger('user_id')->index();
            $table->bigInteger('user_type')->default(1);
            $table->bigInteger('attempt_id')->index();
            $table->unique(['activity_id', 'user_id', 'attempt_id'], 'activity_scorm_user_key');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('el_activity_scorm_users');
    }
}
