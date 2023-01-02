<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElSettingJoinOfflineCourseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_setting_join_offline_course', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('course_id')->index();
            $table->bigInteger('title_id')->nullable()->index()->comment('Chức danh');
            $table->bigInteger('title_rank_id')->nullable()->index()->comment('Cấp bậc (Nhóm) chức danh');
            $table->string('course_complete_id')->nullable()->comment('Khóa học cần hoàn thành');
            $table->integer('date_register')->nullable()->comment('Số ngày được phép ghi danh tính từ ngày bổ nhiệm chức danh');
            $table->integer('date_register_join_company')->nullable()->comment('Số ngày được phép ghi danh tính từ ngày vào làm');
            $table->tinyInteger('auto_register')->default(0)->comment('Tự động ghi danh HV');
            $table->bigInteger('created_by')->index()->default(2);
            $table->bigInteger('updated_by')->index()->default(2);
            $table->integer('unit_by')->index()->nullable();
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
        Schema::dropIfExists('el_setting_join_offline_course');
    }
}
