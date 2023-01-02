<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElTrainingByTitleUploadImageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_training_by_title_upload_image', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('type');
            $table->string('image');
            $table->timestamps();
        });

        DB::table('el_training_by_title_upload_image')->insert(
            [
                [
                    'image' => asset('images/title_male.png'),
                    'type' => 1,
                ],
                [
                    'type' => 0,
                    'image' => asset('images/title_female.png')
                ],
            ]
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('el_training_by_title_upload_image');
    }
}
