<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateElUsermedalResultTableAlter extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('el_usermedal_result', function (Blueprint $table) {
			$table->integer('settings_items_id')->after('id');
			$table->integer('settings_items_id_got')->after('settings_items_id');
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('el_usermedal_result', function (Blueprint $table) {
			$table->dropColumn('settings_items_id');
			$table->dropColumn('settings_items_id_got');
		});
    }
}
