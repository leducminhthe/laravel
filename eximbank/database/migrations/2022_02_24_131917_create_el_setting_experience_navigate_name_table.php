<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateElSettingExperienceNavigateNameTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_setting_experience_navigate_name', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->nullable();
            $table->string('url')->nullable();
            $table->string('image')->nullable();
            $table->integer('status')->default(1);
            $table->integer('type')->default(1);
            $table->timestamps();
        });

        DB::table('el_setting_experience_navigate_name')->insert(
            [
                [
                    'name' => json_encode(['vi' => 'Phát triển theo lộ trình']),
                ],
                [
                    'name' => json_encode(['vi' => 'Phát triển kỹ năng chuyên môn']),
                ],
                [
                    'name' => json_encode(['vi' => 'Khóa học của tôi']),
                ],
                [
                    'name' => json_encode(['vi' => 'Tham gia hoạt động diễn đàn']),
                ],
                [
                    'name' => json_encode(['vi' => 'Học theo sở thích']),
                ],
                [
                    'name' => json_encode(['vi' => 'Xem bài viết, kiến thức mới']),
                ],
                [
                    'name' => json_encode(['vi' => 'Xem điểm tích lũy học tập']),
                ],
                [
                    'name' => json_encode(['vi' => 'Xem thư viện']),
                ],
                [
                    'name' => json_encode(['vi' => 'Xem học liệu video']),
                ],
            ]
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('el_setting_experience_navigate_name');
    }
}
