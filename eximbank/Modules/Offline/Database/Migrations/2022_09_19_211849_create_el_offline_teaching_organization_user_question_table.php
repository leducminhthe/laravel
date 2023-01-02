<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElOfflineTeachingOrganizationUserQuestionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_offline_teaching_organization_user_question', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('teaching_organization_category_id');
            $table->bigInteger('question_id');
            $table->string('question_code')->nullable();
            $table->longText('question_name');
            $table->longText('answer_essay')->nullable()->comment('câu tự luận');
            $table->string('type')->comment('multiple_choice, essay');
            $table->integer('multiple')->default(0)->comment('1: Chọn nhiều, 0: Chọn 1');
            $table->bigInteger('teacher_id')->nullable();
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
        Schema::dropIfExists('el_offline_teaching_organization_user_question');
    }
}
