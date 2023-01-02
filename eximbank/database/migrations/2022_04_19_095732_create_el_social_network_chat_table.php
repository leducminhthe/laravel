<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateElSocialNetworkChatTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_social_network_chat', function (Blueprint $table) {
            $table->id();
            $table->integer('group_id');
            $table->text('chat');
            $table->integer('post_by_user_id');
            $table->integer('type')->default(0)->comment('0: text, 1: ảnh');
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
        Schema::dropIfExists('el_social_network_chat');
    }
}
