<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElQuizRegisterTable extends Migration
{
    public function up()
    {
        Schema::create('el_quiz_register', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('quiz_id')->index();
            $table->bigInteger('user_id')->index();
            $table->bigInteger('part_id')->index();
            $table->tinyInteger('type')->index()->comment('1 - Người thi trong / 2 - Người thi ngoài');
            $table->bigInteger('created_by')->nullable()->index();
            $table->bigInteger('updated_by')->nullable()->index();
            $table->integer('unit_by')->nullable()->index();
            $table->integer('locked')->default(0)->comment('1: Bị khoá do vi phạm');
            $table->integer('blocked_quiz')->default(0)->comment('1: Cấm thi');
            $table->string('blocked_quiz_note')->nullable()->comment('Lý do cấm thi');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('el_quiz_register');
    }
}
