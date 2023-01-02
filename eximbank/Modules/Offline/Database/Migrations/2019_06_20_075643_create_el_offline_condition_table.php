<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElOfflineConditionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_offline_condition', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('course_id')->unique();
            $table->integer('ratio')->nullable()->comment('Tỉ lệ tham gia');
            $table->double('minscore')->nullable()->comment('Điểm tối thiểu');
            $table->integer('survey')->nullable()->comment('Thực hiện bài đánh giá');
            $table->integer('certificate')->nullable()->comment('Chứng chỉ khóa học');
            $table->integer('online_activity')->nullable()->comment('Số hoạt động online cần hoàn thành');
            $table->integer('num_hour')->nullable()->comment('Số giờ học meeting');
            $table->string('activity', 255)->nullable()->comment('các hoạt động');
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
        Schema::dropIfExists('el_offline_condition');
    }
}
