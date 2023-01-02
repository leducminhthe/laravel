<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOfflineActivityScormAttemptDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offline_activity_scorm_attempt_data', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('attempt_id')->index();
            $table->string('var_name', 150)->index();
            $table->text('var_value');
            $table->unique(['attempt_id', 'var_name']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('offline_activity_scorm_attempt_data');
    }
}
