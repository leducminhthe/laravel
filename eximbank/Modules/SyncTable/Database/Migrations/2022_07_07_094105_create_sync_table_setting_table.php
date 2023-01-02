<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSyncTableSettingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sync_table_setting', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('from_table',100)->index()->comment('table nguồn');
            $table->string('to_table',100)->index()->comment('table đích');
            $table->string('from_column',200)->comment('cột table nguồn');
            $table->string('to_column',200)->comment('cột table đích');
            $table->string('relationship',200)->comment('cột quan hệ table đích');
//            $table->bigInteger('id_change')->comment('id table nguồn');
//            $table->integer('status')->index()->default(0)->comment('1: có thay đổi, 0: chưa thay đổi');
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
        Schema::dropIfExists('sync_table_setting');
    }
}
