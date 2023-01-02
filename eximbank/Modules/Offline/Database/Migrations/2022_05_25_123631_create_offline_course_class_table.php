<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOfflineCourseClassTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offline_course_class', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('course_id')->index();
            $table->string('code');
            $table->string('name');
            $table->tinyInteger('default')->index()->default(0)->comment('1 lớp mặc định ko được phép xóa');
            $table->integer('students')->nullable()->comment('số lượng học viên');
            $table->dateTime('start_date')->nullable()->comment('Thời gian bắt đầu tổ chức');
            $table->dateTime('end_date')->nullable()->comment('Thời gian kết thúc');
            $table->integer('training_location_id')->nullable()->comment('Địa điểm đào tạo');
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
        Schema::dropIfExists('offline_course_class');
    }
}
