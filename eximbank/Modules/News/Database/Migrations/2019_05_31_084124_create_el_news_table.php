<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElNewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_news', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->integer('type');
            $table->integer('hot')->default(0);
            $table->integer('hot_public')->default(0);
            $table->integer('hot_public_sort')->nullable();
            $table->text('content');
            $table->text('description');
            $table->integer('status');
            $table->integer('views')->default(0);
            $table->string('image')->nullable();
            $table->dateTime('date_setup_icon')->nullable();
            $table->integer('number_setup')->nullable();
            $table->bigInteger('category_id')->nullable();
            $table->bigInteger('category_parent_id')->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->integer('unit_by')->nullable();
            $table->integer('like_new')->default(0);
            $table->integer('user_view')->nullable()->comment('người xem cuối');
            $table->dateTime('view_time')->nullable()->comment('thời gian bấm xem');
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
        Schema::dropIfExists('el_news');
    }
}
