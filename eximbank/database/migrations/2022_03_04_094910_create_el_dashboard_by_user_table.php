<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateElDashboardByUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_dashboard_by_user', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique()->comment('Phân loại thống kê');
            $table->string('name')->comment('Tên thống kê');
            $table->string('condition')->nullable()->comment('Điều kiền cho các thống kê');
            $table->integer('created_by')->nullable()->comment('Người thiết lập');
            $table->integer('updated_by')->nullable()->comment('Người sửa thiết lập');
            $table->integer('unit_by')->nullable()->comment('đơn vị ng thiết lập');
            $table->string('color')->nullable()->comment('màu nội dung');
            $table->integer('i_text')->default(0)->comment('in nghiêng nội dung');
            $table->integer('b_text')->default(0)->comment('in đậm nội dung');
            $table->string('images_web')->nullable()->comment('hình đính kèm trên web');
            $table->string('images_mobile')->nullable()->comment('hình đính kèm trên mobile');
            $table->integer('location')->default(1)->comment('Vị trí hình đính kèm vs nội dung. 1: tách riêng, 2: phía trên hình, 3: giữa hình, 4: phía dưới hình');
            $table->string('year')->nullable();
            $table->timestamps();
        });

        \DB::table('el_dashboard_by_user')->insert([
            //Khóa học Elearning: Hoàn thành sớm. => So theo tháng
            [
                'code' => 'online_complete',
                'name' => 'Khóa học Elearning: Hoàn thành sớm',
            ],
            //Bạn đã tham gia …..  khóa học trực tuyến trong năm
            [
                'code' => 'online_register',
                'name' => 'Bạn đã tham gia …..  khóa học trực tuyến trong năm',
            ],
            //Các hoạt động bạn đã tham gia
            [
                'code' => 'activity_joined',
                'name' => 'Các hoạt động bạn đã tham gia',
            ],
            //Bạn đã có ... bài viết
            [
                'code' => 'user_has_post',
                'name' => 'Bạn đã có ... bài viết',
            ],
            //Bài viết bạn đã có …. người like
            [
                'code' => 'user_post_with_like',
                'name' => 'Bài viết bạn đã có …. người like',
            ],
            //Bài post được like nhiều hơn
            [
                'code' => 'you_post_liked_more',
                'name' => 'Bài post được like nhiều hơn',
            ],
            //Bạn thuộc Top học viên năng động trong …. Thành viên trong phòng Ban của bạn
            [
                'code' => 'top_in_unit',
                'name' => 'Bạn thuộc Top học viên năng động trong …. Thành viên trong phòng Ban của bạn',
            ],
            //Nằm trong Top 15 học viên xuất sắc trong năm
            [
                'code' => 'top_user',
                'name' => 'Nằm trong Top học viên xuất sắc trong năm',
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
        Schema::dropIfExists('el_dashboard_by_user');
    }
}
