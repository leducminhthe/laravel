<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElSalesKitTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_sales_kit', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('image')->nullable();
            $table->integer('views')->default(0);
            $table->integer('current_number')->default(0);
            $table->integer('like_libraries')->default(0);
            $table->integer('download')->default(0);
            $table->integer('status')->default(1);
            $table->longText('description')->nullable();
            $table->integer('category_id')->default(0);
            $table->text('category_parent')->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->integer('unit_by')->nullable();
            $table->string('attachment')->nullable();
            $table->string('phone_contact')->nullable();
            $table->string('name_author')->nullable();
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
        Schema::dropIfExists('el_sales_kit');
    }
}
