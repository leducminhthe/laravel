<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElOfflineScheduleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_offline_schedule', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('session')->nullable();
            $table->bigInteger('class_id')->index();
            $table->bigInteger('course_id')->index();
            $table->time('start_time');
            $table->time('end_time');
            $table->dateTime('lesson_date');
            $table->dateTime('end_date')->nullable()->comment('Ngày kết thúc. Dành cho type_study = 3');
            $table->bigInteger('teacher_main_id')->nullable()->comment('Giảng viên chính');
            $table->string('teach_id')->nullable()->comment('Trợ giảng');
            $table->decimal('cost_teacher_main', 15)->nullable()->comment('Chi phí giảng viên chính');
            $table->float('cost_teach_type')->nullable()->comment('Chi phí trợ giảng');
            $table->integer('total_lessons')->default(1);
            $table->integer('training_location_id')->nullable()->comment('Địa điểm đào tạo');
            $table->integer('cost_by')->default(1)->comment('Chi phí theo. 1 => Giờ. 2 => Số Sao');
            $table->integer('type_study')->default(1)->comment('Hình thức học. 1 => Tại lớp; 2 => MS Teams; 3 => Elearning');
            $table->float('condition_complete_teams')->nullable()->comment('Điều kiện hoàn thành');
            $table->float('practical_teaching')->nullable()->comment('Giờ dạy thực tế');
            $table->integer('created_by')->index()->default(2);
            $table->integer('updated_by')->index()->default(2);
            $table->integer('unit_by')->index();
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
        Schema::dropIfExists('el_offline_schedule');
    }
}
