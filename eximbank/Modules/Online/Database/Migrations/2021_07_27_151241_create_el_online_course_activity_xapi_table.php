<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElOnlineCourseActivityXapiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_online_course_activity_xapi', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('course_id')->index();
            $table->bigInteger('xapi_id')->index();
            $table->string('path', 150)->index();
            $table->integer('max_attempt')->default(0)->comment('0: Không giới hạn');
            $table->integer('what_grade')->default(1)->comment('1: Lần cao nhất, 2: trung bình, 3: lần đầu, 4: lần cuối');
            $table->integer('max_score')->default(100)->comment('điểm tối đa');
            $table->tinyInteger('score_required')->default(0)->comment('nhận điểm để hoàn thành');
            $table->tinyInteger('status_passed')->default(0)->comment('trạng thái đạt');
            $table->tinyInteger('status_completed')->default(0)->comment('trạng thái hoàn thành');
            $table->tinyInteger('new_attempt_required')->default(0)->comment('1: khi có kết quả, 2: luôn luôn');
            $table->integer('min_score_required')->default(0)->comment('điểm tối thiểu để hoàn thành');
            $table->text('description')->nullable();
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
        Schema::dropIfExists('el_online_course_activity_xapi');
    }
}
