<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElRatingTemplateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_rating_template', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code');
            $table->longText('name');
            $table->longText('description')->nullable();
            $table->integer('created_by')->nullable()->index();
            $table->integer('updated_by')->nullable()->index();
            $table->integer('unit_by')->nullable()->index();
            $table->tinyInteger('teaching_organization')->default(0)->comment('1 là Mẫu đánh giá công tác tổ chức giảng dạy khoá học tập trung');
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
        Schema::dropIfExists('el_rating_template');
    }
}
