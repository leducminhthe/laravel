<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBotConfigSuggestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bot_config_suggest', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->nullable()->comment('suggest');
            $table->string('parent_id')->nullable();
            $table->string('url')->nullable();
            $table->integer('level')->nullable();
            $table->string('answer',4000)->nullable();
            $table->tinyInteger('type')->default(0)->nullable()->comment('1 default');
            $table->bigInteger('created_by')->nullable()->index();
            $table->bigInteger('updated_by')->nullable()->index();
            $table->integer('unit_by')->nullable()->index();
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
        Schema::dropIfExists('bot_config_suggest');
    }
}
