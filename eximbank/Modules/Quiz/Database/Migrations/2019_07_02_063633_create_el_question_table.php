<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElQuestionTable extends Migration
{
    public function up()
    {
            Schema::create('el_question', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->text('code')->unique()->nullable();
                $table->text('name');
                $table->string('type', 100)->index()->comment('essay: Tự luận/ multiple-choise: Trắc nghiệm/ matching: Nối câu');
                $table->text('note')->nullable()->comment('Ghi chú câu hỏi');
                $table->longText('feedback')->nullable()->comment('Phản hồi chung cho câu tự luận');
                $table->bigInteger('category_id')->index()->nullable();
                $table->integer('multiple')->index()->default(0)->comment('chọn nhiều');
                $table->integer('multiple_full_score')->index()->default(0)->comment('Trọn điểm chọn nhiều');
                $table->integer('status')->index()->default(2)->comment('1: Duyệt, 2: Chưa duyệt, 0:Từ chối');
                $table->bigInteger('created_by')->nullable()->index();
                $table->bigInteger('updated_by')->nullable()->index();
                $table->integer('unit_by')->nullable()->index();
                $table->tinyInteger('shuffle_answers')->default(0)->comment('Xáo trộn đáp án');
                $table->bigInteger('approved_by')->nullable()->index();
                $table->dateTime('time_approved')->nullable();
                $table->integer('answer_horizontal')->default(0);
                $table->string('image_drag_drop')->nullable();
                $table->string('difficulty')->default('D')->nullable()->comment('Mức độ: D => Dễ; K => Khó; TB => Trung bình');
                $table->timestamps();
            });
    }

    public function down()
    {
        Schema::dropIfExists('el_question');
    }
}
