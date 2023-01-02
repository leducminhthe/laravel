<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePermissionMasterDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('master_data', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('model',50)->index()->comment('tên model');
            $table->string('description',250)->index()->comment('Mô tả');
            $table->tinyInteger('type')->default(1)->index()->comment('1: all, 2 thấy theo công ty, 3 thấy theo phân quyền đơn vị');
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
        Schema::dropIfExists('master_data');
    }
}
