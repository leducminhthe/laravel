<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateElUsermedalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_usermedal', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code', 150);
            $table->string('name')->nullable();
            $table->string('photo')->nullable();
            $table->integer('parent_id')->nullable()->default(0);
            $table->text('content')->nullable();
            $table->text('rule')->nullable();
            $table->integer('rank')->nullable();
            $table->tinyInteger('status')->nullable();
            $table->integer('created_by')->nullable()->default(2);
            $table->integer('updated_by')->nullable()->default(2);
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
        Schema::dropIfExists('el_usermedal');
    }
}
