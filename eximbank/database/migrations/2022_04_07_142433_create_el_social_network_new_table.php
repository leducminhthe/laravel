<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateElSocialNetworkNewTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_social_network_new', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('avatar');
            $table->string('user_name');
            $table->string('title_new');
            $table->integer('status')->comment('1: công khai; 2: Bạn bè cụ thể; 3:chỉ mình tôi');
            $table->integer('type')->comment('0: chỉ có text; 1: bài viết có hình ảnh; 2: bài viết có video');
            $table->integer('total_like')->nullable()->default(0);
            $table->integer('total_share')->nullable()->default(0);
            $table->integer('total_comment')->nullable()->default(0);
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
        Schema::dropIfExists('el_social_network_new');
    }
}
