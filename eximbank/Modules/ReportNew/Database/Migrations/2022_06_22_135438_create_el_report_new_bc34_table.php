<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElReportNewBc34Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_report_new_bc34', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('category_id')->index()->comment('id table el_question_category');
            $table->integer('scoring_question_used')->default(0)->comment('Số câu hỏi tự tính điểm được dùng');
            $table->integer('question_graded_used')->default(0)->comment('Số câu hỏi chờ GV chấm điểm (essay, fill_in) được dùng');
            $table->integer('scoring_question_correct')->default(0)->comment('Số câu tl đúng câu hỏi tự tính điểm');
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
        Schema::dropIfExists('el_report_new_bc34');
    }
}
