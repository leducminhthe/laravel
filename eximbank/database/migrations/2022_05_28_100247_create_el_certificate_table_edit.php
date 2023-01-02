<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateElCertificateTableEdit extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		 Schema::table('el_certificate', function (Blueprint $table) {
            $table->enum('type', ['1', '2'])->nullable()->default('1');
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       Schema::table('el_certificate', function (Blueprint $table) {
            $table->dropColumn('type');
		});
    }
}
