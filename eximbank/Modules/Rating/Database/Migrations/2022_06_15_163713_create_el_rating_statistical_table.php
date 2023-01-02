<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElRatingStatisticalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_rating_statistical', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('template_id')->comment('Mẫu đánh giá đào tạo. id table el_rating_template');
            $table->string('title_lesson')->comment('tiêu đề bài học');
            $table->string('title_organization')->comment('tiêu đề tổ chức');
            $table->string('title_teacher')->comment('tiêu đề giảng viên');
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
        Schema::dropIfExists('el_rating_statistical');
    }
}
