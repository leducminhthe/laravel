<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAreaViewTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('area_view', function (Blueprint $table) {
            $table->integer('id');
            $table->integer('area1_id')->nullable();
            $table->string('area1_code')->nullable();
            $table->string('area1_name')->nullable();
            $table->integer('area2_id')->nullable();
            $table->string('area2_code')->nullable();
            $table->string('area2_name')->nullable();
            $table->integer('area3_id')->nullable();
            $table->string('area3_code')->nullable();
            $table->string('area3_name')->nullable();
            $table->integer('area4_id')->nullable();
            $table->string('area4_code')->nullable();
            $table->string('area4_name')->nullable();
            $table->integer('area5_id')->nullable();
            $table->string('area5_code')->nullable();
            $table->string('area5_name')->nullable();
            $table->string('area_code')->nullable()->comment('mã đơn vị trực tiếp');
            $table->integer('area_level')->nullable()->comment('cấp độ khu vực');
            $table->integer('status');
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
        Schema::dropIfExists('area_view');
    }
}
