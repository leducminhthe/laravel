<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateElLanguagesTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_languages_type', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('icon')->nullable();
            $table->string('key');
            $table->string('name');
            $table->timestamps();
        });

        DB::table('el_languages_type')->insert([
            [
                'icon' => 'images/i_flag_vietnam.png',
                'key' => 'vi',
                'name' => 'Vietnamese'
            ],
            [
                'icon' => 'images/i_flag_england.png',
                'key' => 'en',
                'name' => 'English'
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('el_languages_type');
    }
}
