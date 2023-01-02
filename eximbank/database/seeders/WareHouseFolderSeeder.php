<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Categories\Unit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class WareHouseFolderSeeder extends Seeder
{
    public function run()
    {
        DB::table('el_warehouse_folder')->truncate();
        DB::table('el_warehouse_folder')->insert(
            [
                [
                    'name' => 'Hình nền đăng nhập',
                    'parent_id' => null,
                    'type' => 'image',
                    'name_url' => 'login-image'
                ],
                [
                    'name' => 'Logo',
                    'parent_id' => null,
                    'type' => 'image',
                    'name_url' => 'logo'
                ],
                [
                    'name' => 'Logo bên ngoài',
                    'parent_id' => null,
                    'type' => 'image',
                    'name_url' => 'logo-outside'
                ],
                [
                    'name' => 'Favicon',
                    'parent_id' => null,
                    'type' => 'image',
                    'name_url' => 'favicon'
                ],
                [
                    'name' => 'App Mobile',
                    'parent_id' => null,
                    'type' => 'image',
                    'name_url' => 'app-mobile'
                ],
                [
                    'name' => 'Mẫu mail',
                    'parent_id' => null,
                    'type' => 'image',
                    'name_url' => 'mail-template'
                ],
                [
                    'name' => 'Diễn đàn',
                    'parent_id' => null,
                    'type' => 'image',
                    'name_url' => 'forums'
                ],
                [
                    'name' => 'Chữ ký mail',
                    'parent_id' => null,
                    'type' => 'image',
                    'name_url' => 'mail-signature'
                ],
                [
                    'name' => 'Liên hệ',
                    'parent_id' => null,
                    'type' => 'image',
                    'name_url' => 'contact'
                ],
                [
                    'name' => 'Banner',
                    'parent_id' => null,
                    'type' => 'image',
                    'name_url' => 'slider'
                ],
                [
                    'name' => 'Banner mobile',
                    'parent_id' => null,
                    'type' => 'image',
                    'name_url' => 'banner-login-mobile'
                ],
                [
                    'name' => 'Ảnh đại diện hoạt động',
                    'parent_id' => null,
                    'type' => 'image',
                    'name_url' => 'activity-lesson'
                ],
                [
                    'name' => 'Mẫu chứng chỉ',
                    'parent_id' => null,
                    'type' => 'image',
                    'name_url' => 'certificate'
                ],
                [
                    'name' => 'Mẫu KPI',
                    'parent_id' => null,
                    'type' => 'image',
                    'name_url' => 'kpi-template'
                ],
                [
                    'name' => 'Câu hỏi',
                    'parent_id' => null,
                    'type' => 'image',
                    'name_url' => 'question-lib'
                ],
                [
                    'name' => 'Hình đại diện kỳ thi',
                    'parent_id' => null,
                    'type' => 'image',
                    'name_url' => 'quiz'
                ],
                [
                    'name' => 'Tin tức',
                    'parent_id' => null,
                    'type' => 'image',
                    'name_url' => 'news'
                ],
                [
                    'name' => 'Sách giấy',
                    'parent_id' => null,
                    'type' => 'image',
                    'name_url' => 'book'
                ],
                [
                    'name' => 'Sách điện tử',
                    'parent_id' => null,
                    'type' => 'image',
                    'name_url' => 'ebook'
                ],
                [
                    'name' => 'Tài liệu',
                    'parent_id' => null,
                    'type' => 'image',
                    'name_url' => 'document'
                ],
                [
                    'name' => 'Sách nói',
                    'parent_id' => null,
                    'type' => 'image',
                    'name_url' => 'audiobook'
                ],
                [
                    'name' => 'Video',
                    'parent_id' => null,
                    'type' => 'image',
                    'name_url' => 'video'
                ],
                [
                    'name' => 'Khóa học Online',
                    'parent_id' => null,
                    'type' => 'image',
                    'name_url' => 'online'
                ],
                [
                    'name' => 'Khóa học Tập trung',
                    'parent_id' => null,
                    'type' => 'image',
                    'name_url' => 'offline'
                ],
                [
                    'name' => 'Quà tặng',
                    'parent_id' => null,
                    'type' => 'image',
                    'name_url' => 'promotion'
                ],
                [
                    'name' => 'Nhóm danh mục quà tặng (mobile)',
                    'parent_id' => null,
                    'type' => 'image',
                    'name_url' => 'promotion-group'
                ],
                [
                    'name' => 'Danh hiệu học tập',
                    'parent_id' => null,
                    'type' => 'image',
                    'name_url' => 'promotion-level'
                ],
                [
                    'name' => 'Kế hoạch đào tạo',
                    'parent_id' => null,
                    'type' => 'image',
                    'name_url' => 'course-educate-plan'
                ],
                [
                    'name' => 'Chương trình thi đua',
                    'parent_id' => null,
                    'type' => 'image',
                    'name_url' => 'usermedal'
                ],
                [
                    'name' => 'Chuyên đề (Danh mục)',
                    'parent_id' => null,
                    'type' => 'image',
                    'name_url' => 'subject'
                ],
                [
                    'name' => 'Chứng chỉ (giảng viên)',
                    'parent_id' => null,
                    'type' => 'image',
                    'name_url' => 'training-teacher'
                ],
                [
                    'name' => 'Xử lý tình huống',
                    'parent_id' => null,
                    'type' => 'image',
                    'name_url' => 'topic-situations'
                ],
                [
                    'name' => 'Profile nhân viên',
                    'parent_id' => null,
                    'type' => 'image',
                    'name_url' => 'user'
                ],
                [
                    'name' => 'Khảo sát',
                    'parent_id' => null,
                    'type' => 'image',
                    'name_url' => 'survey'
                ],
                [
                    'name' => 'FAQ',
                    'parent_id' => null,
                    'type' => 'image',
                    'name_url' => 'faq'
                ],
                [
                    'name' => 'Hướng dẫn',
                    'parent_id' => null,
                    'type' => 'image',
                    'name_url' => 'guide'
                ],
                [
                    'name' => 'Khóa Online (kế hoạch tháng)',
                    'parent_id' => null,
                    'type' => 'image',
                    'name_url' => '1'
                ],
                [
                    'name' => 'Khóa offline (kế hoạch tháng)',
                    'parent_id' => null,
                    'type' => 'image',
                    'name_url' => '2'
                ],
                [
                    'name' => 'Dành cho học viên',
                    'type' => 'image',
                    'name_url' => 'thread',
                    'parent_id' => 7,
                ],
                [
                    'name' => 'Khóa học online',
                    'parent_id' => null,
                    'type' => 'file',
                    'name_url' => 'online'
                ],
                [
                    'name' => 'Thư viện file',
                    'parent_id' => null,
                    'type' => 'file',
                    'name_url' => 'libraryFile'
                ],
                [
                    'name' => 'Scrom',
                    'parent_id' => null,
                    'type' => 'scorm',
                    'name_url' => 'online'
                ],
                [
                    'name' => 'Xapi',
                    'parent_id' => null,
                    'type' => 'xapi',
                    'name_url' => 'online'
                ],
                [
                    'name' => 'Khóa học tập trung',
                    'parent_id' => null,
                    'type' => 'file',
                    'name_url' => 'offline'
                ],
                [
                    'name' => 'Quản lý file',
                    'parent_id' => null,
                    'type' => 'file',
                    'name_url' => 'upload'
                ],
                [
                    'name' => 'Khóa online (kế hoạch tháng)',
                    'parent_id' => null,
                    'type' => 'file',
                    'name_url' => '1'
                ],
                [
                    'name' => 'Khóa tập trung (kế hoạch tháng)',
                    'parent_id' => null,
                    'type' => 'file',
                    'name_url' => '2'
                ],
                [
                    'name' => 'Sách diện tử',
                    'parent_id' => null,
                    'type' => 'file',
                    'name_url' => 'ebook'
                ],
                [
                    'name' => 'Tài liệu',
                    'parent_id' => null,
                    'type' => 'file',
                    'name_url' => 'document'
                ],
                [
                    'name' => 'Sách nói',
                    'parent_id' => null,
                    'type' => 'file',
                    'name_url' => 'audiobook'
                ],
                [
                    'name' => 'Video',
                    'parent_id' => null,
                    'type' => 'file',
                    'name_url' => 'video'
                ],
                [
                    'name' => 'Tin tức',
                    'parent_id' => null,
                    'type' => 'file',
                    'name_url' => 'news'
                ],
                [
                    'name' => 'Hướng dẫn',
                    'parent_id' => null,
                    'type' => 'file',
                    'name_url' => 'guide'
                ],
                [
                    'name' => 'coaching-teacher',
                    'parent_id' => null,
                    'type' => 'image',
                    'name_url' => 'coaching-teacher'
                ],
                [
                    'name' => 'Sách điện tử (scorm)',
                    'parent_id' => null,
                    'type' => 'scorm',
                    'name_url' => 'ebook'
                ],
                [
                    'name' => 'Tài liệu (scorm)',
                    'parent_id' => null,
                    'type' => 'scorm',
                    'name_url' => 'document'
                ],
                [
                    'name' => 'Huy hiệu thi đua',
                    'parent_id' => null,
                    'type' => 'image',
                    'name_url' => 'emulation-badge'
                ],
                [
                    'name' => 'Scrom',
                    'parent_id' => null,
                    'type' => 'scorm',
                    'name_url' => 'offline'
                ],
                [
                    'name' => 'Xapi',
                    'parent_id' => null,
                    'type' => 'xapi',
                    'name_url' => 'offline'
                ],
            ]
        );
    }
}
