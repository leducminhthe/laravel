<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateElUsermedalSettingsItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_usermedal_settings_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('setting_id')->nullable();
            $table->integer('usermedal_id');
            $table->integer('item_id');
            $table->integer('item_type')->nullable();
            $table->bigInteger('start_date')->nullable();
            $table->bigInteger('end_date')->nullable();
            $table->decimal('min_score', 5, 2)->nullable();
            $table->decimal('max_score', 5, 2)->nullable();
            $table->string('note')->nullable();
            $table->integer('ref')->nullable();
            $table->integer('created_by')->nullable()->default(2);
            $table->integer('updated_by')->nullable()->default(2);
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
        Schema::dropIfExists('el_usermedal_settings_items');
    }
}
