<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateElSocialNetworkUserCommentNewTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_social_network_user_comment_new', function (Blueprint $table) {
            $table->id();
            $table->integer('social_network_new_id');
            $table->integer('user_id');
            $table->string('comment');
            $table->string('avatar');
            $table->string('user_name');
            $table->integer('total_reply')->nullable();
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
        Schema::dropIfExists('el_social_network_user_comment_new');
    }
}
