<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElOfflineAttendanceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_offline_attendance', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('register_id')->index();
            $table->bigInteger('schedule_id')->index();
            $table->bigInteger('course_id')->index();
            $table->bigInteger('class_id')->index();
            $table->bigInteger('user_id')->index()->nullable();
            $table->integer('percent')->nullable()->comment('Phần trăm tham gia');
            $table->string('note')->nullable();
            $table->integer('status')->default(0);
            $table->integer('absent_id')->default(0)->nullable();
            $table->integer('absent_reason_id')->default(0)->nullable();
            $table->integer('discipline_id')->default(0)->nullable();
            $table->string('type')->nullable()->comment('Loại điểm danh: 1.HVQRC, 2.GVQRC, 3.Manual, 4.Edit manual');
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
        Schema::dropIfExists('el_offline_attendance');
    }
}
