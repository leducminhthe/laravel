<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElCertificateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_certificate', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code', 150)->unique();
            $table->string('name');
            $table->string('image');
            $table->string('user')->nullable()->comment('Tên người đại diện');
            $table->string('position')->nullable()->comment('chức vụ người đại diện');
            $table->string('signature')->nullable()->comment('chữ ký người đại diện');
            $table->string('logo')->nullable()->comment('logo');
            $table->string('location')->nullable()->comment('vị trí logo. left, center, right');
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->integer('unit_by')->nullable();
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
        Schema::dropIfExists('el_certificate');
    }
}
