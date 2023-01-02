<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateElSettingColorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_setting_color', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('text')->nullable();
            $table->string('hover_text')->nullable();
            $table->string('active')->nullable();
            $table->string('background')->nullable();
            $table->string('hover_background')->nullable();
            $table->string('background_child')->nullable();
            $table->timestamps();
        });

        \DB::table('el_setting_color')->insert([
            [
                'name' => 'color_menu',
                'text' => '#000000',
                'hover_text' => '#FFFFFF',
                'active' => '#00000',
                'background' => '#1b4486',
                'hover_background' => '#1b4486',
                'background_child' => '#dee2e6',
            ],
            [
                'name' => 'color_button',
                'text' => '#000000',
                'hover_text' => '#FFFFFF',
                'active' => null,
                'background' => '#1b4486',
                'hover_background' => '#1b4486',
                'background_child' => null,
            ],
            [
                'name' => 'color_link',
                'text' => '#000000',
                'hover_text' => '#1b4486',
                'active' => null,
                'background' => null,
                'hover_background' => null,
                'background_child' => null,
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
        Schema::dropIfExists('el_setting_color');
    }
}
