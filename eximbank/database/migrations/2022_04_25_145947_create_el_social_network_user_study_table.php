<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateElSocialNetworkUserStudyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_social_network_user_study', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('name');
            $table->string('description');
            $table->integer('status')->comment('0: chưa tốt nghiệp, 1: đã tốt nghiệp');
            $table->string('year_start');
            $table->string('year_end')->nullable();
            $table->integer('type');
            $table->integer('type_study')->comment('0: trung học, 1: cao đẳng/đại học');
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
        Schema::dropIfExists('el_social_network_user_study');
    }
}
