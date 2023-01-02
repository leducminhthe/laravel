<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateElInteractionHistoryNameTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_interaction_history_name', function (Blueprint $table) {
            $table->id();
            $table->string('code')->comment('Mã loại tương tác. Tự quy định');
            $table->string('name')->comment('Tên loại tương tác');
            $table->timestamps();
        });

        \DB::table('el_interaction_history_name')->insert([
            [
                'code' => 'news',
                'name' => trans('lamenu.news')
            ],
            [
                'code' => 'libraries',
                'name' => trans('lamenu.libraries')
            ],
            [
                'code' => 'forum',
                'name' => trans('lamenu.forum')
            ],
            [
                'code' => 'survey',
                'name' => trans('lamenu.survey')
            ],
            [
                'code' => 'training_video',
                'name' => trans('lamenu.training_video')
            ],
            [
                'code' => 'quiz',
                'name' => trans('lamenu.quiz')
            ],
            [
                'code' => 'rating_level',
                'name' => trans('lamenu.kirkpatrick_model')
            ],
            [
                'code' => 'help',
                'name' => trans('lamenu.support')
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
        Schema::dropIfExists('el_interaction_history_name');
    }
}
