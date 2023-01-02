<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElCoachingTeacherTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_coaching_teacher', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->comment('User GV');
            $table->string('image')->comment('Hình của GV');
            $table->text('technique')->comment('Chuyên môn');
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->integer('coaching_group_id')->comment('Nhóm coaching trong Danh mục');
            $table->integer('number_coaching')->default(0)->comment('SL kèm cặp');
            $table->integer('status')->default(2)->comment('Trạng thái đăng ký: 0 => Từ chối, 1 => Duyệt, 2 => Chờ duyệt');
            $table->tinyInteger('full_class')->default(0)->comment('Không cho ghi danh vào nếu full_class = 1');
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
        Schema::dropIfExists('el_coaching_teacher');
    }
}
