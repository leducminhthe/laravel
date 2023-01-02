<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateElUserpointItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_userpoint_item', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('ikey')->nullable();
            $table->string('name');
            $table->integer('type')->nullable();
            $table->decimal('default_value', 5, 2)->nullable()->default(0.00);
            $table->timestamps();
        });

        DB::table('el_userpoint_item')
        ->insert([
            [
                'ikey' => 'online_complete',
                'name' => 'Hoàn thành khóa học',
                'type' => '2',
                'default_value' => '0',
            ],
            [
                'ikey' => 'online_comment',
                'name' => 'Nhận điểm khi bình luận khóa học',
                'type' => '2',
                'default_value' => '0',
            ],
            // [
            //     'ikey' => 'online_share',
            //     'name' => 'Nhận điểm khi Share khoá học',
            //     'type' => '2',
            //     'default_value' => '0',
            // ],
            [
                'ikey' => 'online_rating_star',
                'name' => 'Nhận điểm khi Đánh giá sao khoá học',
                'type' => '2',
                'default_value' => '0',
            ],
            [
                'ikey' => 'offline_complete',
                'name' => 'Hoàn thành khóa học',
                'type' => '3',
                'default_value' => '0',
            ],
            [
                'ikey' => 'offline_comment',
                'name' => 'Nhận điểm khi bình luận khóa học',
                'type' => '3',
                'default_value' => '0',
            ],
            [
                'ikey' => 'offline_share',
                'name' => 'Nhận điểm khi Share khoá học',
                'type' => '3',
                'default_value' => '0',
            ],
            [
                'ikey' => 'offline_rating_star',
                'name' => 'Nhận điểm khi Đánh giá sao khoá học',
                'type' => '3',
                'default_value' => '0',
            ],
            [
                'ikey' => 'quiz_complete',
                'name' => 'Hoàn thành kỳ thi',
                'type' => '4',
                'default_value' => '0',
            ],
            [
                'ikey' => 'user_rating_libraries',
                'name' => 'Nhận điểm khi đánh giá sao thư viện',
                'type' => '6',
                'default_value' => '0',
            ],
            [
                'ikey' => 'user_view_libraries',
                'name' => 'Nhận điểm khi xem thư viện',
                'type' => '6',
                'default_value' => '0',
            ],
            [
                'ikey' => 'user_download_libraries',
                'name' => 'Nhận điểm khi tải thư viện',
                'type' => '6',
                'default_value' => '0',
            ],
            [
                'ikey' => 'forum_create',
                'name' => 'Nhận điểm khi tạo bài viết diễn đàn và được duyệt',
                'type' => '7',
                'default_value' => '0',
            ],
            [
                'ikey' => 'user_comment_forum_thread',
                'name' => 'Nhận điểm khi bình luận bài viết diễn đàn',
                'type' => '7',
                'default_value' => '0',
            ],
            [
                'ikey' => 'daily_create',
                'name' => 'Nhận điểm khi tạo video và được duyệt',
                'type' => '8',
                'default_value' => '0',
            ],
            [
                'ikey' => 'user_like_daily_training',
                'name' => 'Học viên nhận điểm khi thích video',
                'type' => '8',
                'default_value' => '0',
            ],
            [
                'ikey' => 'user_view_new',
                'name' => 'Học viên nhận điểm khi xem tin tức',
                'type' => '9',
                'default_value' => '0',
            ],
            [
                'ikey' => 'user_like_new',
                'name' => 'Học viên nhận điểm khi thích tin tức',
                'type' => '9',
                'default_value' => '0',
            ],
            [
                'ikey' => 'suggest_create',
                'name' => 'Nhận điểm khi tạo góp ý',
                'type' => '10',
                'default_value' => '0',
            ],
            [
                'ikey' => 'student_complete_coaching',
                'name' => 'HV nhận điểm khi hoàn thành Coaching',
                'type' => '11',
                'default_value' => '0',
            ],
            [
                'ikey' => 'student_rating_teacher_coaching',
                'name' => 'HV nhận điểm khi đánh giá GV Coaching',
                'type' => '11',
                'default_value' => '0',
            ],
            [
                'ikey' => 'teacher_complete_coaching',
                'name' => 'GV nhận điểm khi hoàn thành Coaching',
                'type' => '11',
                'default_value' => '0',
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('el_userpoint_item');
    }
}
