<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateElTrainingTeacherCertificateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_training_teacher_certificate', function (Blueprint $table) {
            $table->id();
            $table->integer('training_teacher_id');
            $table->string('name_certificate')->comment('Tên chứng chỉ');
            $table->string('name_school')->comment('Trường cấp chứng chỉ');
            $table->string('rank')->nullable()->comment('Cấp bậc');
            $table->date('time_start')->nullable()->comment('Thời gian bắt đầu học');
            $table->date('date_license')->nullable()->comment('Ngày cấp chứng chỉ');
            $table->string('score')->nullable()->comment('Điểm');
            $table->string('result')->nullable()->comment('Kết quả');
            $table->string('note')->nullable()->comment('Ghi chú');
            $table->string('certificate')->comment('Hình chứng chỉ');
            $table->date('date_effective')->nullable()->comment('Ngày hiệu lực chứng chỉ');
            $table->integer('status')->default(1)->comment('Trạng thái chứng chỉ. 1: còn hiệu lực, 0: hết hiệu lực');
            $table->bigInteger('created_by')->nullable();
            $table->bigInteger('updated_by')->nullable();
            $table->integer('unit_by')->nullable();
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
        Schema::dropIfExists('el_training_teacher_certificate');
    }
}
