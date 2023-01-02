<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElLibrariesFileZipTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_libraries_file_zip', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('libraries_id')->index();
            $table->string('origin_path', 150)->index();
            $table->string('unzip_path', 150)->nullable();
            $table->string('index_file', 100)->nullable();
            $table->text('error')->nullable();
            $table->tinyInteger('status')->index()->default(2);
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
        Schema::dropIfExists('el_libraries_file_zip');
    }
}
