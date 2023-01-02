<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElUnitTable extends Migration
{
    public function up()
    {
        Schema::create('el_unit', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code', 150)->unique();
            $table->string('name');
            $table->integer('level')->index();
            $table->string('parent_code', 150)->index()->nullable();
            $table->tinyInteger('status')->index();
            $table->string('email')->nullable()->comment('email của đơn vị');
            $table->bigInteger('type')->index()->nullable()->comment('table el_unit_type');
            $table->string('note1')->nullable();
            $table->string('note2')->nullable();
            $table->integer('created_by')->nullable()->default(2)->index();
            $table->integer('updated_by')->nullable()->default(2)->index();
            $table->integer('area_id')->nullable()->index();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('el_unit');
    }
}
