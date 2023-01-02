<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ElLanguagesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('el_languages')->delete();
        
        \DB::table('el_languages')->insert(array (
            0 => 
            array (
                'id' => 1,
                'groups_id' => 1,
                'pkey' => 'summary',
                'content' => 'Thống kê',
                'content_en' => 'Summary',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'groups_id' => 1,
                'pkey' => 'management',
                'content' => 'Quản lý',
                'content_en' => 'Management',
                'created_at' => '2021-11-11 17:40:50',
                'updated_at' => '2021-11-11 17:40:50',
            ),
            2 => 
            array (
                'id' => 3,
                'groups_id' => 1,
                'pkey' => 'training',
                'content' => 'Đào tạo',
                'content_en' => 'Training',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'groups_id' => 1,
                'pkey' => 'quiz_manager',
                'content' => 'Khảo thí',
                'content_en' => 'Quiz manager',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            4 => 
            array (
                'id' => 5,
                'groups_id' => 1,
                'pkey' => 'library',
                'content' => 'Thư viện',
                'content_en' => 'Library',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            5 => 
            array (
                'id' => 6,
                'groups_id' => 1,
                'pkey' => 'news',
                'content' => 'Tin tức',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            6 => 
            array (
                'id' => 7,
                'groups_id' => 1,
                'pkey' => 'study_promotion_program',
                'content' => 'Quà tặng',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            7 => 
            array (
                'id' => 8,
                'groups_id' => 1,
                'pkey' => 'training_video',
                'content' => 'Học liệu đào tạo video',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            8 => 
            array (
                'id' => 9,
                'groups_id' => 1,
                'pkey' => 'permission',
                'content' => 'Phân quyền',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            9 => 
            array (
                'id' => 10,
                'groups_id' => 1,
                'pkey' => 'setting',
                'content' => 'Cài đặt',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            10 => 
            array (
                'id' => 11,
                'groups_id' => 1,
                'pkey' => 'unit',
                'content' => 'Đơn vị',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            11 => 
            array (
                'id' => 12,
                'groups_id' => 1,
                'pkey' => 'category',
                'content' => 'Danh mục',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            12 => 
            array (
                'id' => 13,
                'groups_id' => 1,
                'pkey' => 'user',
                'content' => 'Nhân viên',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            13 => 
            array (
                'id' => 14,
                'groups_id' => 1,
                'pkey' => 'career_roadmap',
                'content' => 'Lộ trình nghề nghiệp',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            14 => 
            array (
                'id' => 15,
                'groups_id' => 1,
                'pkey' => 'survey',
                'content' => 'Khảo sát',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            15 => 
            array (
                'id' => 16,
                'groups_id' => 1,
                'pkey' => 'situations_proccessing',
                'content' => 'Xử lý tình huống',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            16 => 
            array (
                'id' => 17,
                'groups_id' => 1,
                'pkey' => 'forum',
                'content' => 'Diễn đàn',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            17 => 
            array (
                'id' => 18,
                'groups_id' => 1,
                'pkey' => 'suggestion',
                'content' => 'Góp ý',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            18 => 
            array (
                'id' => 19,
                'groups_id' => 1,
                'pkey' => 'note',
                'content' => 'Ghi chú',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            19 => 
            array (
                'id' => 20,
                'groups_id' => 1,
                'pkey' => 'history',
                'content' => 'Lịch sử',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            20 => 
            array (
                'id' => 21,
                'groups_id' => 1,
                'pkey' => 'faq',
                'content' => 'Câu hỏi thường gặp',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            21 => 
            array (
                'id' => 22,
                'groups_id' => 1,
                'pkey' => 'guide',
                'content' => 'Hướng dẫn',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            22 => 
            array (
                'id' => 23,
                'groups_id' => 1,
                'pkey' => 'plan_suggest',
                'content' => 'Đề xuất kế hoạch',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            23 => 
            array (
                'id' => 24,
                'groups_id' => 1,
                'pkey' => 'schedule_task',
                'content' => 'Lịch tác vụ',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            24 => 
            array (
                'id' => 25,
                'groups_id' => 1,
                'pkey' => 'table_manager',
                'content' => 'Quản lý table',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            25 => 
            array (
                'id' => 26,
                'groups_id' => 1,
                'pkey' => 'training_organizations',
                'content' => 'Tổ chức đào tạo',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            26 => 
            array (
                'id' => 27,
                'groups_id' => 1,
                'pkey' => 'learning_manager',
                'content' => 'Quản lý đào tạo',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            27 => 
            array (
                'id' => 28,
                'groups_id' => 1,
                'pkey' => 'subject_registered',
                'content' => 'Chuyên đề đã đăng ký',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            28 => 
            array (
                'id' => 29,
                'groups_id' => 1,
                'pkey' => 'indemnify',
                'content' => 'Quản lý bồi hoàn',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            29 => 
            array (
                'id' => 30,
                'groups_id' => 1,
                'pkey' => 'certificate',
                'content' => 'Mẫu chứng chỉ',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            30 => 
            array (
                'id' => 31,
                'groups_id' => 1,
                'pkey' => 'evaluate_training_effectiveness',
                'content' => 'Đánh giá hiệu quả đào tạo',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            31 => 
            array (
                'id' => 32,
                'groups_id' => 1,
                'pkey' => 'new_report',
                'content' => 'Báo cáo mới',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            32 => 
            array (
                'id' => 33,
                'groups_id' => 1,
                'pkey' => 'questionlib',
                'content' => 'Ngân hàng câu hỏi',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            33 => 
            array (
                'id' => 34,
                'groups_id' => 1,
                'pkey' => 'quiz_structure',
                'content' => 'Cơ cấu đề thi',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            34 => 
            array (
                'id' => 35,
                'groups_id' => 1,
                'pkey' => 'quiz_list',
                'content' => 'Kỳ thi',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            35 => 
            array (
                'id' => 36,
                'groups_id' => 1,
                'pkey' => 'data_old_quiz',
                'content' => 'Dữ liệu cũ',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            36 => 
            array (
                'id' => 37,
                'groups_id' => 1,
                'pkey' => 'grading',
                'content' => 'Chấm điểm',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            37 => 
            array (
                'id' => 38,
                'groups_id' => 1,
                'pkey' => 'statistic',
                'content' => 'Thống kê',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            38 => 
            array (
                'id' => 39,
                'groups_id' => 1,
                'pkey' => 'setting_alert',
                'content' => 'Thiết lập cảnh báo',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            39 => 
            array (
                'id' => 40,
                'groups_id' => 1,
                'pkey' => 'information_edit',
                'content' => 'Điều chỉnh thông tin',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            40 => 
            array (
                'id' => 41,
                'groups_id' => 1,
                'pkey' => 'user_secondary',
                'content' => 'Thí sinh bên ngoài',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            41 => 
            array (
                'id' => 42,
                'groups_id' => 1,
                'pkey' => 'book_register',
                'content' => 'Quản lý mượn sách',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            42 => 
            array (
                'id' => 43,
                'groups_id' => 1,
                'pkey' => 'book',
                'content' => 'Sách giấy',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            43 => 
            array (
                'id' => 44,
                'groups_id' => 1,
                'pkey' => 'ebook',
                'content' => 'Sách điện tử',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            44 => 
            array (
                'id' => 45,
                'groups_id' => 1,
                'pkey' => 'document',
                'content' => 'Tài liệu',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            45 => 
            array (
                'id' => 46,
                'groups_id' => 1,
                'pkey' => 'audio',
                'content' => 'Sách nói',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            46 => 
            array (
                'id' => 47,
                'groups_id' => 1,
                'pkey' => 'video',
                'content' => 'Video',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            47 => 
            array (
                'id' => 48,
                'groups_id' => 1,
                'pkey' => 'news_list',
                'content' => 'Tin tức',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            48 => 
            array (
                'id' => 49,
                'groups_id' => 1,
                'pkey' => 'cate_news_general',
                'content' => 'Danh mục tin tức chung',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            49 => 
            array (
                'id' => 50,
                'groups_id' => 1,
                'pkey' => 'news_list_outside',
                'content' => 'Tin tức chung',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            50 => 
            array (
                'id' => 51,
                'groups_id' => 1,
                'pkey' => 'news_adv_banner',
                'content' => 'Ảnh quảng cáo tin tức',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            51 => 
            array (
                'id' => 52,
                'groups_id' => 1,
                'pkey' => 'promotion_category_group',
                'content' => 'Nhóm danh mục quà tặng',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            52 => 
            array (
                'id' => 53,
                'groups_id' => 1,
                'pkey' => 'promotions',
                'content' => 'Quà tặng',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            53 => 
            array (
                'id' => 54,
                'groups_id' => 1,
                'pkey' => 'purchase_history',
                'content' => 'Lịch sử quà tặng',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            54 => 
            array (
                'id' => 55,
                'groups_id' => 1,
                'pkey' => 'donate_points',
                'content' => 'Tặng điểm',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            55 => 
            array (
                'id' => 56,
                'groups_id' => 1,
                'pkey' => 'user_level_setting',
                'content' => 'Huy hiệu',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            56 => 
            array (
                'id' => 57,
                'groups_id' => 1,
                'pkey' => 'emulation_program',
                'content' => 'Chương trình thi đua',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            57 => 
            array (
                'id' => 58,
                'groups_id' => 1,
                'pkey' => 'video_category',
                'content' => 'Danh mục video',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            58 => 
            array (
                'id' => 59,
                'groups_id' => 1,
                'pkey' => 'setting_views',
                'content' => 'Thiết lập lượt xem',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            59 => 
            array (
                'id' => 60,
                'groups_id' => 1,
                'pkey' => 'setting_like',
                'content' => 'Thiết lập lượt thích',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            60 => 
            array (
                'id' => 61,
                'groups_id' => 1,
                'pkey' => 'setting_comment',
                'content' => 'Thiết lập bình luận',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            61 => 
            array (
                'id' => 62,
                'groups_id' => 1,
                'pkey' => 'permission_group',
                'content' => 'Nhóm quyền',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            62 => 
            array (
                'id' => 63,
                'groups_id' => 1,
                'pkey' => 'role',
                'content' => 'Vai trò',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            63 => 
            array (
                'id' => 64,
                'groups_id' => 1,
                'pkey' => 'permission_approved',
                'content' => 'Phân quyền phê duyệt',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            64 => 
            array (
                'id' => 65,
                'groups_id' => 1,
                'pkey' => 'unit_manager_setup',
                'content' => 'Thiết lập TĐV',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            65 => 
            array (
                'id' => 66,
                'groups_id' => 1,
                'pkey' => 'approve_register',
                'content' => 'Duyệt ghi danh',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            66 => 
            array (
                'id' => 67,
                'groups_id' => 1,
                'pkey' => 'approve_student_cost',
                'content' => 'Duyệt chi phí học viên',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            67 => 
            array (
                'id' => 68,
                'groups_id' => 1,
                'pkey' => 'plan_app',
                'content' => 'Đánh giá hiệu quả đào tạo',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            68 => 
            array (
                'id' => 69,
                'groups_id' => 1,
                'pkey' => 'training_seft_plan',
                'content' => 'Kế hoạch tự đào tạo',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            69 => 
            array (
                'id' => 70,
                'groups_id' => 1,
                'pkey' => 'quiz_plan_suggest',
                'content' => 'Kế hoạch khảo thí',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            70 => 
            array (
                'id' => 71,
                'groups_id' => 1,
                'pkey' => 'authorized_unit_manager',
                'content' => 'Trưởng đơn vị ủy quyền',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            71 => 
            array (
                'id' => 72,
                'groups_id' => 1,
                'pkey' => 'user_info',
                'content' => 'Thông tin học viên',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            72 => 
            array (
                'id' => 73,
                'groups_id' => 1,
                'pkey' => 'logout',
                'content' => 'Đăng xuất',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            73 => 
            array (
                'id' => 74,
                'groups_id' => 1,
                'pkey' => 'dashboard',
                'content' => 'Dashboard',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            74 => 
            array (
                'id' => 75,
                'groups_id' => 1,
                'pkey' => 'training_calendar',
                'content' => 'Lịch đào tạo',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            75 => 
            array (
                'id' => 76,
                'groups_id' => 1,
                'pkey' => 'course',
                'content' => 'Khóa học',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            76 => 
            array (
                'id' => 77,
                'groups_id' => 1,
                'pkey' => 'quiz',
                'content' => 'Khảo thí',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            77 => 
            array (
                'id' => 78,
                'groups_id' => 1,
                'pkey' => 'admin_panel',
                'content' => 'Trang quản trị',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            78 => 
            array (
                'id' => 79,
                'groups_id' => 1,
                'pkey' => 'home_page',
                'content' => 'Trang chủ',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            79 => 
            array (
                'id' => 80,
                'groups_id' => 1,
                'pkey' => 'training_calendar',
                'content' => 'Lịch đào tạo',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            80 => 
            array (
                'id' => 81,
                'groups_id' => 1,
                'pkey' => 'libraries',
                'content' => 'Thư viện',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            81 => 
            array (
                'id' => 82,
                'groups_id' => 1,
                'pkey' => 'attendance',
                'content' => 'Điểm danh',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            82 => 
            array (
                'id' => 83,
                'groups_id' => 1,
                'pkey' => 'post',
                'content' => 'Bài viết',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            83 => 
            array (
                'id' => 84,
                'groups_id' => 1,
                'pkey' => 'pdf',
                'content' => 'PDF',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            84 => 
            array (
                'id' => 85,
                'groups_id' => 1,
                'pkey' => 'social_network',
                'content' => 'Mạng xã hội',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            85 => 
            array (
                'id' => 86,
                'groups_id' => 2,
                'pkey' => 'search',
                'content' => 'Tìm kiếm',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            86 => 
            array (
                'id' => 87,
                'groups_id' => 2,
                'pkey' => 'export_full',
                'content' => 'Xuất toàn bộ',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            87 => 
            array (
                'id' => 88,
                'groups_id' => 2,
                'pkey' => 'update_import_by_excel',
                'content' => 'Cập nhật import theo excel',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            88 => 
            array (
                'id' => 89,
                'groups_id' => 2,
                'pkey' => 'folder_tree',
                'content' => 'Cây thư mục',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            89 => 
            array (
                'id' => 90,
                'groups_id' => 2,
                'pkey' => 'import_template',
                'content' => 'Mẫu import',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            90 => 
            array (
                'id' => 91,
                'groups_id' => 2,
                'pkey' => 'import',
                'content' => 'Import',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            91 => 
            array (
                'id' => 92,
                'groups_id' => 2,
                'pkey' => 'export',
                'content' => 'Export',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            92 => 
            array (
                'id' => 93,
                'groups_id' => 2,
                'pkey' => 'enable',
                'content' => 'Bật',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            93 => 
            array (
                'id' => 94,
                'groups_id' => 2,
                'pkey' => 'disable',
                'content' => 'Tắt',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            94 => 
            array (
                'id' => 95,
                'groups_id' => 2,
                'pkey' => 'add_new',
                'content' => 'Thêm mới',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            95 => 
            array (
                'id' => 96,
                'groups_id' => 2,
                'pkey' => 'delete',
                'content' => 'Xóa',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            96 => 
            array (
                'id' => 97,
                'groups_id' => 2,
                'pkey' => 'close',
                'content' => 'Đóng',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            97 => 
            array (
                'id' => 98,
                'groups_id' => 2,
                'pkey' => 'cancel',
                'content' => 'Hủy',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            98 => 
            array (
                'id' => 99,
                'groups_id' => 2,
                'pkey' => 'save',
                'content' => 'Lưu',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            99 => 
            array (
                'id' => 100,
                'groups_id' => 2,
                'pkey' => 'summary_dashboard',
                'content' => 'Tổng quan',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            100 => 
            array (
                'id' => 101,
                'groups_id' => 2,
                'pkey' => 'detail',
                'content' => 'Chi tiết',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            101 => 
            array (
                'id' => 102,
                'groups_id' => 2,
                'pkey' => 'add_merge_subject',
                'content' => 'Thêm chuyên đề cần gộp',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            102 => 
            array (
                'id' => 103,
                'groups_id' => 2,
                'pkey' => 'view_logs',
                'content' => 'Xem logs',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            103 => 
            array (
                'id' => 104,
                'groups_id' => 2,
                'pkey' => 'query',
                'content' => 'Truy vấn',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            104 => 
            array (
                'id' => 105,
                'groups_id' => 2,
                'pkey' => 'add_lesson',
                'content' => 'Thêm bài học',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            105 => 
            array (
                'id' => 106,
                'groups_id' => 2,
                'pkey' => 'add_activities',
                'content' => 'Thêm hoạt động',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            106 => 
            array (
                'id' => 107,
                'groups_id' => 2,
                'pkey' => 'show',
                'content' => 'Hiện',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            107 => 
            array (
                'id' => 108,
                'groups_id' => 2,
                'pkey' => 'hide',
                'content' => 'Ẩn',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            108 => 
            array (
                'id' => 109,
                'groups_id' => 2,
                'pkey' => 'add_object',
                'content' => 'Thêm đối tượng',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            109 => 
            array (
                'id' => 110,
                'groups_id' => 2,
                'pkey' => 'send_mail_approve',
                'content' => 'Gửi mail duyệt',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            110 => 
            array (
                'id' => 111,
                'groups_id' => 2,
                'pkey' => 'send_mail_change',
                'content' => 'Gửi mail thay đổi',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            111 => 
            array (
                'id' => 112,
                'groups_id' => 2,
                'pkey' => 'approve',
                'content' => 'Duyệt',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            112 => 
            array (
                'id' => 113,
                'groups_id' => 2,
                'pkey' => 'deny',
                'content' => 'Từ chối',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            113 => 
            array (
                'id' => 114,
                'groups_id' => 2,
                'pkey' => 'copy',
                'content' => 'Sao chép',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            114 => 
            array (
                'id' => 115,
                'groups_id' => 2,
                'pkey' => 'view_report',
                'content' => 'Xem báo cáo',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            115 => 
            array (
                'id' => 116,
                'groups_id' => 2,
                'pkey' => 'export_excel',
                'content' => 'Xuất excel',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            116 => 
            array (
                'id' => 117,
                'groups_id' => 2,
                'pkey' => 'get_books',
                'content' => 'Lấy sách',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            117 => 
            array (
                'id' => 118,
                'groups_id' => 2,
                'pkey' => 'book_back',
                'content' => 'Trả sách',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            118 => 
            array (
                'id' => 119,
                'groups_id' => 2,
                'pkey' => 'see_result',
                'content' => 'Xem kết quả',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            119 => 
            array (
                'id' => 120,
                'groups_id' => 2,
                'pkey' => 'off_result',
                'content' => 'Không xem kết quả',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            120 => 
            array (
                'id' => 121,
                'groups_id' => 2,
                'pkey' => 'send_mail_invite',
                'content' => 'Gửi mail nhắc tham dự',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            121 => 
            array (
                'id' => 122,
                'groups_id' => 2,
                'pkey' => 'review_quiz',
                'content' => 'Xem trước kỳ thi',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            122 => 
            array (
                'id' => 123,
                'groups_id' => 2,
                'pkey' => 'add_questionlib',
                'content' => 'Thêm ngân hàng câu hỏi',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            123 => 
            array (
                'id' => 124,
                'groups_id' => 2,
                'pkey' => 'add_random_question',
                'content' => 'Thêm câu hỏi ngẫu nhiên',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            124 => 
            array (
                'id' => 125,
                'groups_id' => 1,
                'pkey' => 'user_take_leave',
                'content' => 'Nhân viên nghỉ phép',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            125 => 
            array (
                'id' => 126,
                'groups_id' => 1,
                'pkey' => 'user_contact',
                'content' => 'Người dùng liên hệ',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            126 => 
            array (
                'id' => 127,
                'groups_id' => 2,
                'pkey' => 'import_roadmap',
                'content' => 'Import lộ trình nghề nghiệp',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            127 => 
            array (
                'id' => 128,
                'groups_id' => 2,
                'pkey' => 'add_roadmap',
                'content' => 'Thêm lộ trình',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            128 => 
            array (
                'id' => 129,
                'groups_id' => 2,
                'pkey' => 'survey_form',
                'content' => 'Mẫu khảo sát',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            129 => 
            array (
                'id' => 130,
                'groups_id' => 2,
                'pkey' => 'filter_word',
                'content' => 'Lọc từ',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            130 => 
            array (
                'id' => 131,
                'groups_id' => 2,
                'pkey' => 'view_login_history',
                'content' => 'Xem lịch sử truy cập',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            131 => 
            array (
                'id' => 132,
                'groups_id' => 2,
                'pkey' => 'save_send',
                'content' => 'Lưu và gửi',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            132 => 
            array (
                'id' => 133,
                'groups_id' => 2,
                'pkey' => 'back',
                'content' => 'Trở về',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            133 => 
            array (
                'id' => 134,
                'groups_id' => 2,
                'pkey' => 'lock',
                'content' => 'Khóa',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            134 => 
            array (
                'id' => 135,
                'groups_id' => 2,
                'pkey' => 'open',
                'content' => 'Mở',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            135 => 
            array (
                'id' => 136,
                'groups_id' => 2,
                'pkey' => 'register',
                'content' => 'Ghi danh',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            136 => 
            array (
                'id' => 137,
                'groups_id' => 2,
                'pkey' => 'add_student_quiz',
                'content' => 'Thêm học viên vào kỳ thi',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            137 => 
            array (
                'id' => 138,
                'groups_id' => 2,
                'pkey' => 'invite_register',
                'content' => 'Mời ghi danh',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            138 => 
            array (
                'id' => 139,
                'groups_id' => 2,
                'pkey' => 'send_mail_registed',
                'content' => 'Gửi mail báo đã ghi danh',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            139 => 
            array (
                'id' => 140,
                'groups_id' => 2,
                'pkey' => 'send',
                'content' => 'Gửi',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            140 => 
            array (
                'id' => 141,
                'groups_id' => 2,
                'pkey' => 'sent',
                'content' => 'Đã gửi',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            141 => 
            array (
                'id' => 142,
                'groups_id' => 2,
                'pkey' => 'add_new_online',
                'content' => 'Thêm mới trực tuyến',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            142 => 
            array (
                'id' => 143,
                'groups_id' => 2,
                'pkey' => 'add_new_offline',
                'content' => 'Thêm mới tập trung',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            143 => 
            array (
                'id' => 144,
                'groups_id' => 2,
                'pkey' => 'convert',
                'content' => 'Chuyển',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            144 => 
            array (
                'id' => 145,
                'groups_id' => 2,
                'pkey' => 'report',
                'content' => 'Báo cáo',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            145 => 
            array (
                'id' => 146,
                'groups_id' => 2,
                'pkey' => 'history_export',
                'content' => 'Lịch sử export',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            146 => 
            array (
                'id' => 147,
                'groups_id' => 2,
                'pkey' => 'next',
                'content' => 'Tiếp theo',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            147 => 
            array (
                'id' => 148,
                'groups_id' => 2,
                'pkey' => 'extension',
                'content' => 'Gia hạn',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            148 => 
            array (
                'id' => 149,
                'groups_id' => 2,
                'pkey' => 'add_audio_book',
                'content' => 'Thêm sách nói',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            149 => 
            array (
                'id' => 150,
                'groups_id' => 2,
                'pkey' => 'add_video',
                'content' => 'Thêm video',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            150 => 
            array (
                'id' => 151,
                'groups_id' => 2,
                'pkey' => 'preview_new',
                'content' => 'Xem trước nội dung',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            151 => 
            array (
                'id' => 152,
                'groups_id' => 2,
                'pkey' => 'add_new_armorial',
                'content' => 'Thêm mới huy hiệu',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            152 => 
            array (
                'id' => 153,
                'groups_id' => 2,
                'pkey' => 'update',
                'content' => 'Cập nhật',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            153 => 
            array (
                'id' => 154,
                'groups_id' => 2,
                'pkey' => 'send_mail_test',
                'content' => 'Kiểm tra gửi mail',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            154 => 
            array (
                'id' => 155,
                'groups_id' => 2,
                'pkey' => 'send_notify',
                'content' => 'Gửi thông báo',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            155 => 
            array (
                'id' => 156,
                'groups_id' => 2,
                'pkey' => 'test',
                'content' => 'Kiểm tra',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            156 => 
            array (
                'id' => 157,
                'groups_id' => 2,
                'pkey' => 'create_lang',
                'content' => 'Tạo ngôn ngữ',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            157 => 
            array (
                'id' => 158,
                'groups_id' => 2,
                'pkey' => 'synchronized',
                'content' => 'Đồng bộ',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            158 => 
            array (
                'id' => 159,
                'groups_id' => 2,
                'pkey' => 'add_new_quiz',
                'content' => 'Thêm mới kỳ thi',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            159 => 
            array (
                'id' => 160,
                'groups_id' => 3,
                'pkey' => 'onl_course',
                'content' => 'Khóa học Online',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            160 => 
            array (
                'id' => 161,
                'groups_id' => 3,
                'pkey' => 'off_course',
                'content' => 'Khóa học Tập trung',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            161 => 
            array (
                'id' => 162,
                'groups_id' => 3,
                'pkey' => 'quiz',
                'content' => 'Kỳ thi',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            162 => 
            array (
                'id' => 163,
                'groups_id' => 3,
                'pkey' => 'your_accumulated_points',
                'content' => 'Điểm tích lũy của bạn',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            163 => 
            array (
                'id' => 164,
                'groups_id' => 3,
                'pkey' => 'header_user_complete_course',
                'content' => 'Bạn đã hoàn thành :count_complete_course_by_user/:count_register_course_by_user khóa học',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            164 => 
            array (
                'id' => 165,
                'groups_id' => 3,
                'pkey' => 'course_new',
                'content' => 'Khóa học mới',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            165 => 
            array (
                'id' => 166,
                'groups_id' => 3,
                'pkey' => 'time',
                'content' => 'Thời gian',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            166 => 
            array (
                'id' => 167,
                'groups_id' => 3,
                'pkey' => 'header_user_complete_subject',
                'content' => 'Bạn đã hoàn thành :count_complete_subject_by_user/:count_register_subject_by_user chuyên đề thuộc tháp đào tạo :level_subject_name',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            167 => 
            array (
                'id' => 168,
                'groups_id' => 3,
                'pkey' => 'subject_by_training_roadmap',
                'content' => 'Chuyên đề thuộc tháp đào tạo',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            168 => 
            array (
                'id' => 169,
                'groups_id' => 3,
                'pkey' => 'subject',
                'content' => 'Chuyên đề',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            169 => 
            array (
                'id' => 170,
                'groups_id' => 3,
                'pkey' => 'status',
                'content' => 'Trạng thái',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            170 => 
            array (
                'id' => 171,
                'groups_id' => 3,
                'pkey' => 'not_learned',
                'content' => 'Chưa học',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            171 => 
            array (
                'id' => 172,
                'groups_id' => 3,
                'pkey' => 'uncomplete',
                'content' => 'Chưa hoàn thành',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            172 => 
            array (
                'id' => 173,
                'groups_id' => 3,
                'pkey' => 'completed',
                'content' => 'Đã hoàn thành',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            173 => 
            array (
                'id' => 174,
                'groups_id' => 3,
                'pkey' => 'online_in_year',
                'content' => 'Khóa học Online trong năm',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            174 => 
            array (
                'id' => 175,
                'groups_id' => 3,
                'pkey' => 'offline_in_year',
                'content' => 'Khóa học Tập trung trong năm',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            175 => 
            array (
                'id' => 176,
                'groups_id' => 3,
                'pkey' => 'course_in_year',
                'content' => 'Khóa học đã ghi danh trong năm',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            176 => 
            array (
                'id' => 177,
                'groups_id' => 3,
                'pkey' => 'jan',
                'content' => 'Tháng 1',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            177 => 
            array (
                'id' => 178,
                'groups_id' => 3,
                'pkey' => 'feb',
                'content' => 'Tháng 2',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            178 => 
            array (
                'id' => 179,
                'groups_id' => 3,
                'pkey' => 'mar',
                'content' => 'Tháng 3',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            179 => 
            array (
                'id' => 180,
                'groups_id' => 3,
                'pkey' => 'apr',
                'content' => 'Tháng 4',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            180 => 
            array (
                'id' => 181,
                'groups_id' => 3,
                'pkey' => 'may',
                'content' => 'Tháng 5',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            181 => 
            array (
                'id' => 182,
                'groups_id' => 3,
                'pkey' => 'jun',
                'content' => 'Tháng 6',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            182 => 
            array (
                'id' => 183,
                'groups_id' => 3,
                'pkey' => 'jul',
                'content' => 'Tháng 7',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            183 => 
            array (
                'id' => 184,
                'groups_id' => 3,
                'pkey' => 'aug',
                'content' => 'Tháng 8',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            184 => 
            array (
                'id' => 185,
                'groups_id' => 3,
                'pkey' => 'sep',
                'content' => 'Tháng 9',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            185 => 
            array (
                'id' => 186,
                'groups_id' => 3,
                'pkey' => 'oct',
                'content' => 'Tháng 10',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            186 => 
            array (
                'id' => 187,
                'groups_id' => 3,
                'pkey' => 'nov',
                'content' => 'Tháng 11',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            187 => 
            array (
                'id' => 188,
                'groups_id' => 3,
                'pkey' => 'dec',
                'content' => 'Tháng 12',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            188 => 
            array (
                'id' => 189,
                'groups_id' => 3,
                'pkey' => 'analytic',
            'content' => 'Thống kê (Hoàn thành/Ghi danh) trong năm',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            189 => 
            array (
                'id' => 190,
                'groups_id' => 3,
                'pkey' => 'register',
                'content' => 'Ghi danh',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            190 => 
            array (
                'id' => 191,
                'groups_id' => 3,
                'pkey' => 'complete',
                'content' => 'Hoàn thành',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            191 => 
            array (
                'id' => 192,
                'groups_id' => 3,
                'pkey' => 'view_more',
                'content' => 'Xem thêm',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            192 => 
            array (
                'id' => 193,
                'groups_id' => 3,
                'pkey' => 'register_deadline',
                'content' => 'Hạn đăng ký',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            193 => 
            array (
                'id' => 194,
                'groups_id' => 3,
                'pkey' => 'notify',
                'content' => 'Thông báo',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            194 => 
            array (
                'id' => 195,
                'groups_id' => 3,
                'pkey' => 'no_notification',
                'content' => 'Chưa có thông báo',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            195 => 
            array (
                'id' => 196,
                'groups_id' => 3,
                'pkey' => 'course_roadmap',
                'content' => 'Khóa học theo lộ trình',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            196 => 
            array (
                'id' => 197,
                'groups_id' => 3,
                'pkey' => 'request',
                'content' => 'Yêu cầu',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            197 => 
            array (
                'id' => 198,
                'groups_id' => 3,
                'pkey' => 'course',
                'content' => 'Khóa học',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            198 => 
            array (
                'id' => 199,
                'groups_id' => 3,
                'pkey' => 'you',
                'content' => 'Bạn',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            199 => 
            array (
                'id' => 200,
                'groups_id' => 3,
                'pkey' => 'view',
                'content' => '',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            200 => 
            array (
                'id' => 201,
                'groups_id' => 3,
                'pkey' => 'view',
                'content' => 'Xem',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            201 => 
            array (
                'id' => 202,
                'groups_id' => 3,
                'pkey' => 'user',
                'content' => 'Nhân viên',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            202 => 
            array (
                'id' => 203,
                'groups_id' => 3,
                'pkey' => 'number_monthly_hits',
                'content' => 'Lượng truy cập hàng tháng',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            203 => 
            array (
                'id' => 204,
                'groups_id' => 3,
                'pkey' => 'browser_device',
                'content' => 'Thiết bị trình duyệt',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            204 => 
            array (
                'id' => 205,
                'groups_id' => 3,
                'pkey' => 'desktop',
                'content' => 'Máy tính',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            205 => 
            array (
                'id' => 206,
                'groups_id' => 3,
                'pkey' => 'mobile',
                'content' => 'Điện thoại',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            206 => 
            array (
                'id' => 207,
                'groups_id' => 3,
                'pkey' => 'tablet',
                'content' => 'Máy tính bảng',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            207 => 
            array (
                'id' => 208,
                'groups_id' => 3,
                'pkey' => 'course_statistics',
                'content' => 'Thống kê khóa học',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            208 => 
            array (
                'id' => 209,
                'groups_id' => 3,
                'pkey' => 'course_held',
                'content' => 'Khóa học đã tổ chức',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            209 => 
            array (
                'id' => 210,
                'groups_id' => 3,
                'pkey' => 'course_not_held',
                'content' => 'Khóa học chưa tổ chức',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            210 => 
            array (
                'id' => 211,
                'groups_id' => 3,
                'pkey' => 'course_canceled',
                'content' => 'Khóa học bị hủy',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            211 => 
            array (
                'id' => 212,
                'groups_id' => 3,
                'pkey' => 'course_pending_approval',
                'content' => 'Khóa học chờ duyệt',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            212 => 
            array (
                'id' => 213,
                'groups_id' => 3,
                'pkey' => 'latest_online_course',
                'content' => 'Khóa học online mới nhất',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            213 => 
            array (
                'id' => 214,
                'groups_id' => 3,
                'pkey' => 'latest_offline_course',
                'content' => 'Khóa học tập trung mới nhất',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            214 => 
            array (
                'id' => 215,
                'groups_id' => 3,
                'pkey' => 'situation_organizing_exam',
                'content' => 'Tình hình tổ chức kỳ thi',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            215 => 
            array (
                'id' => 216,
                'groups_id' => 3,
                'pkey' => 'latest_exam',
                'content' => 'Kỳ thi mới nhất',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            216 => 
            array (
                'id' => 217,
                'groups_id' => 3,
                'pkey' => 'students_complete_course',
                'content' => 'Học viên hoàn thành khóa học',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            217 => 
            array (
                'id' => 218,
                'groups_id' => 3,
                'pkey' => 'completion_rate',
                'content' => 'Tỷ lệ hoàn thành',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            218 => 
            array (
                'id' => 219,
                'groups_id' => 3,
                'pkey' => 'incomplete',
                'content' => 'Chưa hoàn thành',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            219 => 
            array (
                'id' => 220,
                'groups_id' => 3,
                'pkey' => 'online_course_view_summary',
                'content' => 'Thống kê truy cập khóa học Online',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            220 => 
            array (
                'id' => 221,
                'groups_id' => 3,
                'pkey' => 'photo_video_new_view_summary',
                'content' => 'Thống kê truy cập tin tức, video, hình ảnh',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            221 => 
            array (
                'id' => 222,
                'groups_id' => 3,
                'pkey' => 'quatity',
                'content' => 'Số lượng',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            222 => 
            array (
                'id' => 223,
                'groups_id' => 3,
                'pkey' => 'video',
                'content' => 'Video',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            223 => 
            array (
                'id' => 224,
                'groups_id' => 3,
                'pkey' => 'images',
                'content' => 'Hình ảnh',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            224 => 
            array (
                'id' => 225,
                'groups_id' => 3,
                'pkey' => 'post',
                'content' => 'Bài viết',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            225 => 
            array (
                'id' => 226,
                'groups_id' => 3,
                'pkey' => 'forum_access_summary',
                'content' => 'Thống kê truy cập diễn đàn',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            226 => 
            array (
                'id' => 227,
                'groups_id' => 3,
                'pkey' => 'subject',
                'content' => 'Chủ đề',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            227 => 
            array (
                'id' => 228,
                'groups_id' => 3,
                'pkey' => 'comment',
                'content' => 'Bình luận',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            228 => 
            array (
                'id' => 229,
                'groups_id' => 3,
                'pkey' => 'ebook_audio_video_summary',
                'content' => 'Thống kê truy cập tài liệu, ebook, sách giấy, audio, video',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            229 => 
            array (
                'id' => 230,
                'groups_id' => 3,
                'pkey' => 'book',
                'content' => 'Sách',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            230 => 
            array (
                'id' => 231,
                'groups_id' => 3,
                'pkey' => 'document',
                'content' => 'Tài liệu',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            231 => 
            array (
                'id' => 232,
                'groups_id' => 3,
                'pkey' => 'audio',
                'content' => 'Sách nói',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            232 => 
            array (
                'id' => 233,
                'groups_id' => 3,
                'pkey' => 'ebook',
                'content' => 'Sách điện tử',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            233 => 
            array (
                'id' => 234,
                'groups_id' => 28,
                'pkey' => 'unit_level',
                'content' => 'ĐV cấp :i',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            234 => 
            array (
                'id' => 235,
                'groups_id' => 28,
                'pkey' => 'unit_type',
                'content' => 'Loại đơn vị',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            235 => 
            array (
                'id' => 236,
                'groups_id' => 28,
                'pkey' => 'start_date',
                'content' => 'Ngày bắt đầu',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            236 => 
            array (
                'id' => 237,
                'groups_id' => 28,
                'pkey' => 'end_date',
                'content' => 'Ngày kết thúc',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            237 => 
            array (
                'id' => 238,
                'groups_id' => 3,
                'pkey' => 'elearning',
                'content' => 'E-Learning',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            238 => 
            array (
                'id' => 239,
                'groups_id' => 3,
                'pkey' => 'offline',
                'content' => 'Tập trung',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            239 => 
            array (
                'id' => 240,
                'groups_id' => 3,
                'pkey' => 'user_by_online',
                'content' => 'CBNV Online',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            240 => 
            array (
                'id' => 241,
                'groups_id' => 3,
                'pkey' => 'user_by_offline',
                'content' => 'CBNV Tập trung',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            241 => 
            array (
                'id' => 242,
                'groups_id' => 3,
                'pkey' => 'part',
                'content' => 'Ca thi',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            242 => 
            array (
                'id' => 243,
                'groups_id' => 3,
                'pkey' => 'user_by_quiz',
                'content' => 'Lượt CBNV thi',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            243 => 
            array (
                'id' => 244,
                'groups_id' => 3,
                'pkey' => 'chart_course_by_training_type',
                'content' => 'Thống kê số lớp theo loại hình đào tạo',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            244 => 
            array (
                'id' => 245,
                'groups_id' => 3,
                'pkey' => 'chart_user_by_training_type',
                'content' => 'Thống kê lượt CBNV theo loại hình đào tạo',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            245 => 
            array (
                'id' => 246,
                'groups_id' => 3,
                'pkey' => 'chart_course_by_course_employee',
                'content' => 'Thống kê số lớp Tân tuyển & Hiện hữu',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            246 => 
            array (
                'id' => 247,
                'groups_id' => 3,
                'pkey' => 'chart_user_by_course_employee',
                'content' => 'Thống kê lượt CBNV Tân tuyển & Hiện hữu',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            247 => 
            array (
                'id' => 248,
                'groups_id' => 3,
                'pkey' => 'chart_part_by_quiz_type',
                'content' => 'Thống kê số ca thi theo loại kỳ thi',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            248 => 
            array (
                'id' => 249,
                'groups_id' => 3,
                'pkey' => 'chart_user_by_quiz_type',
                'content' => 'Thống kê lượt CBNV thi theo loại kỳ thi',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            249 => 
            array (
                'id' => 250,
                'groups_id' => 3,
                'pkey' => 'month',
                'content' => 'Tháng',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            250 => 
            array (
                'id' => 251,
                'groups_id' => 4,
                'pkey' => 'generals_setting',
                'content' => 'Cài đặt chung',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            251 => 
            array (
                'id' => 252,
                'groups_id' => 4,
                'pkey' => 'email_configuration',
                'content' => 'Cấu hình mail',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            252 => 
            array (
                'id' => 253,
                'groups_id' => 4,
                'pkey' => 'login_wallpaper',
                'content' => 'Hình nền đăng nhập',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            253 => 
            array (
                'id' => 254,
                'groups_id' => 4,
                'pkey' => 'logo',
                'content' => 'Logo',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            254 => 
            array (
                'id' => 255,
                'groups_id' => 4,
                'pkey' => 'extenal_logo',
                'content' => 'Logo bên ngoài',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            255 => 
            array (
                'id' => 256,
                'groups_id' => 4,
                'pkey' => 'favicon',
                'content' => 'Favicon',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            256 => 
            array (
                'id' => 257,
                'groups_id' => 4,
                'pkey' => 'app_mobile',
                'content' => 'App Mobile',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            257 => 
            array (
                'id' => 258,
                'groups_id' => 4,
                'pkey' => 'notify',
                'content' => 'Thông báo',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            258 => 
            array (
                'id' => 259,
                'groups_id' => 4,
                'pkey' => 'notification_template',
                'content' => 'Mẫu thông báo',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            259 => 
            array (
                'id' => 260,
                'groups_id' => 4,
                'pkey' => 'mailtemplate',
                'content' => 'Mẫu mail',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            260 => 
            array (
                'id' => 261,
                'groups_id' => 4,
                'pkey' => 'email_signature',
                'content' => 'Chữ ký mail',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            261 => 
            array (
                'id' => 262,
                'groups_id' => 4,
                'pkey' => 'mailhistory',
                'content' => 'Lịch sử mail',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            262 => 
            array (
                'id' => 263,
                'groups_id' => 4,
                'pkey' => 'contact',
                'content' => 'Liên hệ',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            263 => 
            array (
                'id' => 264,
                'groups_id' => 4,
                'pkey' => 'training_position',
                'content' => 'Địa điểm đào tạo',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            264 => 
            array (
                'id' => 265,
                'groups_id' => 4,
                'pkey' => 'banner',
                'content' => 'Banner',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            265 => 
            array (
                'id' => 266,
                'groups_id' => 4,
                'pkey' => 'extenal_banner',
                'content' => 'Banner bên ngoài',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            266 => 
            array (
                'id' => 267,
                'groups_id' => 4,
                'pkey' => 'company_info',
                'content' => 'Thông tin công ty',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            267 => 
            array (
                'id' => 268,
                'groups_id' => 4,
                'pkey' => 'banner_login_mobile',
                'content' => 'Banner đăng nhập điện thoại',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            268 => 
            array (
                'id' => 269,
                'groups_id' => 2,
                'pkey' => 'button_setting_color',
                'content' => 'Cài đặt màu',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => '2022-01-12 10:35:19',
            ),
            269 => 
            array (
                'id' => 270,
                'groups_id' => 4,
                'pkey' => 'languages',
                'content' => 'Ngôn ngữ',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            270 => 
            array (
                'id' => 271,
                'groups_id' => 4,
                'pkey' => 'setting_time',
                'content' => 'Cài đặt thời gian',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            271 => 
            array (
                'id' => 272,
                'groups_id' => 4,
                'pkey' => 'general_ldap',
                'content' => 'Thiết lập LDAP',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            272 => 
            array (
                'id' => 273,
                'groups_id' => 4,
                'pkey' => 'ldap_host',
                'content' => 'Ldap Host',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            273 => 
            array (
                'id' => 274,
                'groups_id' => 4,
                'pkey' => 'ldap_host_note',
                'content' => 'Máy chủ LDAP. VD \'ldap://ldap.myorg.com/\' hoặc \'ldaps://ldap.myorg.com/\'',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            274 => 
            array (
                'id' => 275,
                'groups_id' => 4,
                'pkey' => 'version',
                'content' => 'Phiên bản',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            275 => 
            array (
                'id' => 276,
                'groups_id' => 4,
                'pkey' => 'version_your_LDAP',
                'content' => 'Phiên bản của giao thức LDAP máy chủ của bạn đang được sử dụng.',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            276 => 
            array (
                'id' => 277,
                'groups_id' => 4,
                'pkey' => 'use_tls',
                'content' => 'Sử dụng TLS',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            277 => 
            array (
                'id' => 278,
                'groups_id' => 4,
                'pkey' => 'no',
                'content' => 'Không',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            278 => 
            array (
                'id' => 279,
                'groups_id' => 4,
                'pkey' => 'yes',
                'content' => 'Có',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            279 => 
            array (
                'id' => 280,
                'groups_id' => 4,
                'pkey' => 'port_tls',
            'content' => 'Sử dụng dịch vụ LDAP thông thường (cổng 389) với mã hóa TLS',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            280 => 
            array (
                'id' => 281,
                'groups_id' => 4,
                'pkey' => 'distinguished_name',
                'content' => 'Tên phân biệt',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            281 => 
            array (
                'id' => 282,
                'groups_id' => 4,
                'pkey' => 'binding_find',
                'content' => 'Nếu bạn muốn sử dụng ràng buộc người dùng để tìm kiếm các người dùng. VD cn=ldapuser,ou=public,o=org',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            282 => 
            array (
                'id' => 283,
                'groups_id' => 4,
                'pkey' => 'bind_password',
                'content' => 'Mật khẩu ràng buộc',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            283 => 
            array (
                'id' => 284,
                'groups_id' => 4,
                'pkey' => 'password_binding',
                'content' => 'Mật khẩu đối với ràng buộc người dùng',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            284 => 
            array (
                'id' => 285,
                'groups_id' => 4,
                'pkey' => 'contexts',
                'content' => 'Ngữ cảnh',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            285 => 
            array (
                'id' => 286,
                'groups_id' => 4,
                'pkey' => 'list_context',
                'content' => 'Danh sách các ngữ cảnh mà ở đó những người sử dụng được xác định. Ngăn cách các ngữ cảnh khác nhau bởi dấu \';\'. Ví dụ : \'ou=users,o=org; ou=others,o=org\'',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            286 => 
            array (
                'id' => 287,
                'groups_id' => 4,
                'pkey' => 'configuration_generals_email',
                'content' => 'Thiết lập cấu hình email',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            287 => 
            array (
                'id' => 288,
                'groups_id' => 4,
                'pkey' => 'email_driver',
                'content' => 'Email driver',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            288 => 
            array (
                'id' => 289,
                'groups_id' => 4,
                'pkey' => 'email_host',
                'content' => 'Email host',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            289 => 
            array (
                'id' => 290,
                'groups_id' => 4,
                'pkey' => 'email_port',
                'content' => 'Email port',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            290 => 
            array (
                'id' => 291,
                'groups_id' => 4,
                'pkey' => 'send_from',
                'content' => 'Gửi từ',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            291 => 
            array (
                'id' => 292,
                'groups_id' => 4,
                'pkey' => 'address_email_send',
                'content' => 'Địa chỉ email gửi',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            292 => 
            array (
                'id' => 293,
                'groups_id' => 4,
                'pkey' => 'user_login',
                'content' => 'User Đăng nhập',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            293 => 
            array (
                'id' => 294,
                'groups_id' => 4,
                'pkey' => 'email_password',
                'content' => 'Email password',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            294 => 
            array (
                'id' => 295,
                'groups_id' => 4,
                'pkey' => 'email_encryption',
                'content' => 'Email encryption',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            295 => 
            array (
                'id' => 296,
                'groups_id' => 4,
                'pkey' => 'test_configuration_email',
                'content' => 'Kiểm tra cấu hình email',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            296 => 
            array (
                'id' => 297,
                'groups_id' => 4,
                'pkey' => 'save_configuration',
                'content' => 'Vui lòng lưu cấu hình của bạn trước khi thực hiện kiểm tra gửi mail.',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            297 => 
            array (
                'id' => 298,
                'groups_id' => 4,
                'pkey' => 'receive_email',
                'content' => 'Email nhận',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            298 => 
            array (
                'id' => 299,
                'groups_id' => 4,
                'pkey' => 'picture',
                'content' => 'Hình ảnh',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            299 => 
            array (
                'id' => 300,
                'groups_id' => 4,
                'pkey' => 'creator',
                'content' => 'Người tạo',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            300 => 
            array (
                'id' => 301,
                'groups_id' => 4,
                'pkey' => 'editor',
                'content' => 'Người sửa',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            301 => 
            array (
                'id' => 302,
                'groups_id' => 4,
                'pkey' => 'status',
                'content' => 'Trạng thái',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            302 => 
            array (
                'id' => 303,
                'groups_id' => 4,
                'pkey' => 'size',
                'content' => 'Kích thước',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            303 => 
            array (
                'id' => 304,
                'groups_id' => 4,
                'pkey' => 'choose_picture',
                'content' => 'Chọn hình ảnh',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            304 => 
            array (
                'id' => 305,
                'groups_id' => 4,
                'pkey' => 'enable',
                'content' => 'Bật',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            305 => 
            array (
                'id' => 306,
                'groups_id' => 4,
                'pkey' => 'disable',
                'content' => 'Tắt',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            306 => 
            array (
                'id' => 307,
                'groups_id' => 4,
                'pkey' => 'add_new',
                'content' => 'Thêm mới',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            307 => 
            array (
                'id' => 308,
                'groups_id' => 4,
                'pkey' => 'edit',
                'content' => 'Chỉnh sửa',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            308 => 
            array (
                'id' => 309,
                'groups_id' => 4,
                'pkey' => 'object',
                'content' => 'Đối tượng',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            309 => 
            array (
                'id' => 310,
                'groups_id' => 4,
                'pkey' => 'picture',
                'content' => 'Hình ảnh',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            310 => 
            array (
                'id' => 311,
                'groups_id' => 4,
                'pkey' => 'link',
                'content' => 'Link',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            311 => 
            array (
                'id' => 312,
                'groups_id' => 4,
                'pkey' => 'enter_titles',
                'content' => 'Nhập tiêu đề',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            312 => 
            array (
                'id' => 313,
                'groups_id' => 4,
                'pkey' => 'titles',
                'content' => 'Tiêu đề',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            313 => 
            array (
                'id' => 314,
                'groups_id' => 4,
                'pkey' => 'create_time',
                'content' => 'Thời gian tạo',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            314 => 
            array (
                'id' => 315,
                'groups_id' => 4,
                'pkey' => 'created_by',
                'content' => 'Tạo bởi',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            315 => 
            array (
                'id' => 316,
                'groups_id' => 4,
                'pkey' => 'info',
                'content' => 'Thông tin',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            316 => 
            array (
                'id' => 317,
                'groups_id' => 4,
                'pkey' => 'notify_name',
                'content' => 'Tên thông báo',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            317 => 
            array (
                'id' => 318,
                'groups_id' => 4,
                'pkey' => 'content',
                'content' => 'Nội dung',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            318 => 
            array (
                'id' => 319,
                'groups_id' => 4,
                'pkey' => 'time_send',
                'content' => 'Thời gian gửi',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            319 => 
            array (
                'id' => 320,
                'groups_id' => 4,
                'pkey' => 'important',
                'content' => 'Quan trọng',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            320 => 
            array (
                'id' => 321,
                'groups_id' => 4,
                'pkey' => 'object_belong',
                'content' => 'Đối tượng thuộc',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            321 => 
            array (
                'id' => 322,
                'groups_id' => 4,
                'pkey' => 'unit',
                'content' => 'Đơn vị',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            322 => 
            array (
                'id' => 323,
                'groups_id' => 4,
                'pkey' => 'title',
                'content' => 'Chức danh',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            323 => 
            array (
                'id' => 324,
                'groups_id' => 4,
                'pkey' => 'user',
                'content' => 'Nhân viên',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            324 => 
            array (
                'id' => 325,
                'groups_id' => 4,
                'pkey' => 'employee_code',
                'content' => 'Mã nhân viên',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            325 => 
            array (
                'id' => 326,
                'groups_id' => 4,
                'pkey' => 'employee_name',
                'content' => 'Tên nhân viên',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            326 => 
            array (
                'id' => 327,
                'groups_id' => 4,
                'pkey' => 'work_unit',
                'content' => 'Đơn vị công tác',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            327 => 
            array (
                'id' => 328,
                'groups_id' => 4,
                'pkey' => 'date_send',
                'content' => 'Ngày gửi',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            328 => 
            array (
                'id' => 329,
                'groups_id' => 4,
                'pkey' => 'user_send',
                'content' => 'Người gửi',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            329 => 
            array (
                'id' => 330,
                'groups_id' => 4,
                'pkey' => 'import_user',
                'content' => 'Import nhân viên',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            330 => 
            array (
                'id' => 331,
                'groups_id' => 4,
                'pkey' => 'enter_name_title_code',
                'content' => 'Nhập mã / tên / tiêu đề',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            331 => 
            array (
                'id' => 332,
                'groups_id' => 4,
                'pkey' => 'name',
                'content' => 'Tên',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            332 => 
            array (
                'id' => 333,
                'groups_id' => 4,
                'pkey' => 'note',
                'content' => 'Ghi chú',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            333 => 
            array (
                'id' => 334,
                'groups_id' => 4,
                'pkey' => 'company',
                'content' => 'Công ty',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            334 => 
            array (
                'id' => 335,
                'groups_id' => 4,
                'pkey' => 'enter_code_name_mail',
                'content' => 'Nhập mã / tên mail',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            335 => 
            array (
                'id' => 336,
                'groups_id' => 4,
                'pkey' => 'code',
                'content' => 'Mã',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            336 => 
            array (
                'id' => 337,
                'groups_id' => 4,
                'pkey' => 'email_name',
                'content' => 'Tên mail',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            337 => 
            array (
                'id' => 338,
                'groups_id' => 4,
                'pkey' => 'list_mail_send',
                'content' => 'Danh sách gửi mail',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            338 => 
            array (
                'id' => 339,
                'groups_id' => 4,
                'pkey' => 'time_send_mail',
                'content' => 'Thời gian gửi mail',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            339 => 
            array (
                'id' => 341,
                'groups_id' => 4,
                'pkey' => 'description',
                'content' => 'Mô tả',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            340 => 
            array (
                'id' => 342,
                'groups_id' => 4,
                'pkey' => 'lat',
                'content' => 'Vĩ độ',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            341 => 
            array (
                'id' => 343,
                'groups_id' => 4,
                'pkey' => 'lng',
                'content' => 'Kinh độ',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            342 => 
            array (
                'id' => 344,
                'groups_id' => 4,
                'pkey' => 'get_lat_lng',
            'content' => '(Nhấp vào bản đồ để lấy Vĩ độ, Kinh độ)',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            343 => 
            array (
                'id' => 345,
                'groups_id' => 4,
                'pkey' => 'add_position',
                'content' => 'Thêm địa điểm',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            344 => 
            array (
                'id' => 346,
                'groups_id' => 4,
                'pkey' => 'list_position',
                'content' => 'Danh sách địa điểm',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            345 => 
            array (
                'id' => 347,
                'groups_id' => 4,
                'pkey' => 'show',
                'content' => 'Hiện',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            346 => 
            array (
                'id' => 348,
                'groups_id' => 4,
                'pkey' => 'order',
                'content' => 'Thứ tự',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            347 => 
            array (
                'id' => 349,
                'groups_id' => 4,
                'pkey' => 'location',
                'content' => 'Vị trí',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            348 => 
            array (
                'id' => 350,
                'groups_id' => 4,
                'pkey' => 'emulation_program',
                'content' => 'Chương trình thi đua',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            349 => 
            array (
                'id' => 351,
                'groups_id' => 4,
                'pkey' => 'url',
                'content' => 'Đường dẫn',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            350 => 
            array (
                'id' => 352,
                'groups_id' => 4,
                'pkey' => 'enter_url',
                'content' => 'Nhập đường dẫn',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            351 => 
            array (
                'id' => 353,
                'groups_id' => 4,
                'pkey' => 'color_btn_click',
                'content' => 'Màu nút nhấn',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            352 => 
            array (
                'id' => 354,
                'groups_id' => 4,
                'pkey' => 'choose_color',
                'content' => 'Chọn màu',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            353 => 
            array (
                'id' => 355,
                'groups_id' => 4,
                'pkey' => 'color_btn_hover',
                'content' => 'Màu rê chuột vào nút nhấn',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            354 => 
            array (
                'id' => 356,
                'groups_id' => 4,
                'pkey' => 'type_keyword',
                'content' => 'Nhập từ khóa',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            355 => 
            array (
                'id' => 357,
                'groups_id' => 4,
                'pkey' => 'keyword',
                'content' => 'Từ khóa',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            356 => 
            array (
                'id' => 358,
                'groups_id' => 4,
                'pkey' => 'group',
                'content' => 'Nhóm',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            357 => 
            array (
                'id' => 359,
                'groups_id' => 4,
                'pkey' => 'import',
                'content' => 'Import',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            358 => 
            array (
                'id' => 360,
                'groups_id' => 4,
                'pkey' => 'add_lang',
                'content' => 'Thêm ngôn ngữ',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            359 => 
            array (
                'id' => 361,
                'groups_id' => 4,
                'pkey' => 'icon',
                'content' => 'Icon',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            360 => 
            array (
                'id' => 362,
                'groups_id' => 4,
                'pkey' => 'key_placeholder',
                'content' => 'Tiếng anh, viết thường, không khoảng trắng. vd: vi',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            361 => 
            array (
                'id' => 363,
                'groups_id' => 4,
                'pkey' => 'lang_name',
                'content' => 'Tên ngôn ngữ',
                'content_en' => 'Languages name',
                'created_at' => NULL,
                'updated_at' => '2021-11-15 16:13:29',
            ),
            362 => 
            array (
                'id' => 364,
                'groups_id' => 4,
                'pkey' => 'time',
                'content' => 'Thời gian',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            363 => 
            array (
                'id' => 365,
                'groups_id' => 4,
                'pkey' => 'morning',
                'content' => 'Buổi sáng',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            364 => 
            array (
                'id' => 366,
                'groups_id' => 4,
                'pkey' => 'good_morning',
                'content' => 'Chào buổi sáng',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            365 => 
            array (
                'id' => 367,
                'groups_id' => 4,
                'pkey' => 'noon',
                'content' => 'Buổi trưa',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            366 => 
            array (
                'id' => 368,
                'groups_id' => 4,
                'pkey' => 'good_afternoon',
                'content' => 'Chào buổi trưa',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            367 => 
            array (
                'id' => 369,
                'groups_id' => 4,
                'pkey' => 'afternoon',
                'content' => 'Buổi chiều',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            368 => 
            array (
                'id' => 370,
                'groups_id' => 4,
                'pkey' => 'see_you_again',
                'content' => 'Rất vui được gặp lại',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            369 => 
            array (
                'id' => 371,
                'groups_id' => 1,
                'pkey' => 'log_view_course',
                'content' => 'Lịch sử truy cập khóa học',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            370 => 
            array (
                'id' => 372,
                'groups_id' => 1,
                'pkey' => 'modelhistory',
                'content' => 'Lịch sử cập nhật',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            371 => 
            array (
                'id' => 373,
                'groups_id' => 1,
                'pkey' => 'login_history',
                'content' => 'Lịch sử truy cập',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            372 => 
            array (
                'id' => 374,
                'groups_id' => 1,
                'pkey' => 'online_course',
                'content' => 'Khóa học Online',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            373 => 
            array (
                'id' => 375,
                'groups_id' => 1,
                'pkey' => 'offline_course',
                'content' => 'Khóa học tập trung',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            374 => 
            array (
                'id' => 376,
                'groups_id' => 1,
                'pkey' => 'training_plan',
                'content' => 'Kế hoạch đào tạo năm',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            375 => 
            array (
                'id' => 377,
                'groups_id' => 1,
                'pkey' => 'month_elearning_plan',
                'content' => 'Kế hoạch đào tạo tháng',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            376 => 
            array (
                'id' => 378,
                'groups_id' => 1,
                'pkey' => 'course_old',
                'content' => 'Khóa học cũ',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            377 => 
            array (
                'id' => 379,
                'groups_id' => 1,
                'pkey' => 'trainingroadmap',
                'content' => 'Tháp đào tạo',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            378 => 
            array (
                'id' => 380,
                'groups_id' => 1,
                'pkey' => 'learning_path',
                'content' => 'Lộ trình đào tạo',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            379 => 
            array (
                'id' => 381,
                'groups_id' => 1,
                'pkey' => 'learning_path_result',
                'content' => 'Kết quả lộ trình đào tạo',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            380 => 
            array (
                'id' => 382,
                'groups_id' => 1,
                'pkey' => 'merge_subject',
                'content' => 'Gộp chuyên đề',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            381 => 
            array (
                'id' => 383,
                'groups_id' => 1,
                'pkey' => 'split_subject',
                'content' => 'Tách chuyên đề',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            382 => 
            array (
                'id' => 384,
                'groups_id' => 1,
                'pkey' => 'subject_complete',
                'content' => 'Hoàn thành quá trình đào tạo',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            383 => 
            array (
                'id' => 385,
                'groups_id' => 1,
                'pkey' => 'move_training_process',
                'content' => 'Chuyển quá trình đào tạo',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            384 => 
            array (
                'id' => 386,
                'groups_id' => 1,
                'pkey' => 'rating_template',
                'content' => 'Mẫu đánh giá',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            385 => 
            array (
                'id' => 387,
                'groups_id' => 1,
                'pkey' => 'rating_organization',
                'content' => 'Tổ chức đánh giá',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            386 => 
            array (
                'id' => 388,
                'groups_id' => 1,
                'pkey' => 'internal_user_history',
                'content' => 'Lịch sử thí sinh nội bộ',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            387 => 
            array (
                'id' => 389,
                'groups_id' => 1,
                'pkey' => 'external_user_history',
                'content' => 'Lịch sử thí sinh bên ngoài',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            388 => 
            array (
                'id' => 390,
                'groups_id' => 1,
                'pkey' => 'news_general_adv_banner',
                'content' => 'Ảnh quảng cáo tin tức chung',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            389 => 
            array (
                'id' => 391,
                'groups_id' => 1,
                'pkey' => 'usermedal_setting',
                'content' => 'Chương trình thi đua',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            390 => 
            array (
                'id' => 392,
                'groups_id' => 5,
                'pkey' => 'organize',
                'content' => 'Tổ chức',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            391 => 
            array (
                'id' => 393,
                'groups_id' => 5,
                'pkey' => 'company_categories',
                'content' => 'Danh mục công ty',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            392 => 
            array (
                'id' => 394,
                'groups_id' => 5,
                'pkey' => 'unit_level',
                'content' => 'ĐV cấp :i',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            393 => 
            array (
                'id' => 395,
                'groups_id' => 5,
                'pkey' => 'geographical_location',
                'content' => 'Vị trí địa lý',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            394 => 
            array (
                'id' => 396,
                'groups_id' => 5,
                'pkey' => 'info',
                'content' => 'Thông tin',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            395 => 
            array (
                'id' => 397,
                'groups_id' => 5,
                'pkey' => 'unit_type',
                'content' => 'Loại đơn vị',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            396 => 
            array (
                'id' => 398,
                'groups_id' => 5,
                'pkey' => 'title_level',
                'content' => 'Cấp bậc chức danh',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            397 => 
            array (
                'id' => 399,
                'groups_id' => 5,
                'pkey' => 'title',
                'content' => 'Chức danh',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            398 => 
            array (
                'id' => 400,
                'groups_id' => 5,
                'pkey' => 'level',
                'content' => 'Trình độ',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            399 => 
            array (
                'id' => 401,
                'groups_id' => 5,
                'pkey' => 'position',
                'content' => 'Chức vụ',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            400 => 
            array (
                'id' => 402,
                'groups_id' => 5,
                'pkey' => 'training',
                'content' => 'Đào tạo',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            401 => 
            array (
                'id' => 403,
                'groups_id' => 5,
                'pkey' => 'training_program',
                'content' => 'Chủ đề',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            402 => 
            array (
                'id' => 404,
                'groups_id' => 5,
                'pkey' => 'type_subject',
                'content' => 'Mảng nghiệp vụ',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            403 => 
            array (
                'id' => 405,
                'groups_id' => 5,
                'pkey' => 'course',
                'content' => 'Khóa học',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            404 => 
            array (
                'id' => 406,
                'groups_id' => 5,
                'pkey' => 'training_type',
                'content' => 'Hình thức đào tạo',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            405 => 
            array (
                'id' => 407,
                'groups_id' => 5,
                'pkey' => 'training_form',
                'content' => 'Loại hình đào tạo',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            406 => 
            array (
                'id' => 408,
                'groups_id' => 5,
                'pkey' => 'training_object_group',
                'content' => 'Nhóm đối tượng đào tạo',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            407 => 
            array (
                'id' => 409,
                'groups_id' => 5,
                'pkey' => 'quiz_type',
                'content' => 'Loại kỳ thi',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            408 => 
            array (
                'id' => 410,
                'groups_id' => 5,
                'pkey' => 'discipline',
                'content' => 'Kỷ luật',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            409 => 
            array (
                'id' => 411,
                'groups_id' => 5,
                'pkey' => 'absent_type',
                'content' => 'Loại nghỉ',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            410 => 
            array (
                'id' => 412,
                'groups_id' => 5,
                'pkey' => 'violator_list',
                'content' => 'Danh sách vi phạm',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            411 => 
            array (
                'id' => 413,
                'groups_id' => 5,
                'pkey' => 'absent_reason',
                'content' => 'Lý do vắng mặt',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            412 => 
            array (
                'id' => 414,
                'groups_id' => 5,
                'pkey' => 'cost',
                'content' => 'Chi phí',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            413 => 
            array (
                'id' => 416,
                'groups_id' => 5,
                'pkey' => 'fee_type',
                'content' => 'Loại chi phí',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            414 => 
            array (
                'id' => 417,
                'groups_id' => 5,
                'pkey' => 'training_cost',
                'content' => 'Chi phí đào tạo',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            415 => 
            array (
                'id' => 418,
                'groups_id' => 5,
                'pkey' => 'student_cost',
                'content' => 'Chi phí học viên',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            416 => 
            array (
                'id' => 419,
                'groups_id' => 5,
                'pkey' => 'commit',
                'content' => 'Khung tài trợ chi phí và thời gian cam kết',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            417 => 
            array (
                'id' => 420,
                'groups_id' => 5,
                'pkey' => 'teacher',
                'content' => 'Giảng viên',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            418 => 
            array (
                'id' => 421,
                'groups_id' => 5,
                'pkey' => 'partner',
                'content' => 'Đối tác',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            419 => 
            array (
                'id' => 422,
                'groups_id' => 5,
                'pkey' => 'teacher_type',
                'content' => 'Loại giảng viên',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            420 => 
            array (
                'id' => 423,
                'groups_id' => 5,
                'pkey' => 'list_teacher',
                'content' => 'Danh sách giảng viên',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            421 => 
            array (
                'id' => 424,
                'groups_id' => 5,
                'pkey' => 'training_location',
                'content' => 'Địa điểm đào tạo',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            422 => 
            array (
                'id' => 425,
                'groups_id' => 5,
                'pkey' => 'province',
                'content' => 'Tỉnh thành',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            423 => 
            array (
                'id' => 426,
                'groups_id' => 5,
                'pkey' => 'district',
                'content' => 'Quận huyện',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            424 => 
            array (
                'id' => 427,
                'groups_id' => 5,
                'pkey' => 'reward_points',
                'content' => 'Điểm thưởng',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            425 => 
            array (
                'id' => 428,
                'groups_id' => 5,
                'pkey' => 'onl_course',
                'content' => 'Khóa học trực tuyến',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            426 => 
            array (
                'id' => 429,
                'groups_id' => 5,
                'pkey' => 'off_course',
                'content' => 'Khóa học tập trung',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            427 => 
            array (
                'id' => 430,
                'groups_id' => 5,
                'pkey' => 'quiz',
                'content' => 'Kỳ thi',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            428 => 
            array (
                'id' => 431,
                'groups_id' => 5,
                'pkey' => 'library',
                'content' => 'Thư viện',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            429 => 
            array (
                'id' => 432,
                'groups_id' => 5,
                'pkey' => 'forum',
                'content' => 'Diễn đàn',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            430 => 
            array (
                'id' => 433,
                'groups_id' => 5,
                'pkey' => 'competition_program',
                'content' => 'Chương trình thi đua',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            431 => 
            array (
                'id' => 434,
                'groups_id' => 5,
                'pkey' => 'armorial',
                'content' => 'Huy hiệu',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            432 => 
            array (
                'id' => 435,
                'groups_id' => 5,
                'pkey' => 'enter_code_name',
                'content' => 'Nhập mã / tên',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            433 => 
            array (
                'id' => 436,
                'groups_id' => 5,
                'pkey' => 'enter_unit_manager_code',
                'content' => 'Nhập mã TĐV',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            434 => 
            array (
                'id' => 437,
                'groups_id' => 5,
                'pkey' => 'choose_unit_type',
                'content' => 'Chọn loại đơn vị',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            435 => 
            array (
                'id' => 438,
                'groups_id' => 5,
                'pkey' => 'unit_code',
                'content' => 'Mã đơn vị',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            436 => 
            array (
                'id' => 439,
                'groups_id' => 5,
                'pkey' => 'unit',
                'content' => 'Đơn vị',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            437 => 
            array (
                'id' => 440,
                'groups_id' => 5,
                'pkey' => 'management_unit',
                'content' => 'Đơn vị quản lý',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            438 => 
            array (
                'id' => 442,
                'groups_id' => 5,
                'pkey' => 'manager',
                'content' => 'Người quản lý',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            439 => 
            array (
                'id' => 443,
                'groups_id' => 5,
                'pkey' => 'creator',
                'content' => 'Người tạo',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            440 => 
            array (
                'id' => 444,
                'groups_id' => 5,
                'pkey' => 'editor',
                'content' => 'Người sửa',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            441 => 
            array (
                'id' => 445,
                'groups_id' => 5,
                'pkey' => 'status',
                'content' => 'Trạng thái',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            442 => 
            array (
                'id' => 446,
                'groups_id' => 5,
                'pkey' => 'import_unit',
                'content' => 'Import đơn vị',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            443 => 
            array (
                'id' => 447,
                'groups_id' => 5,
                'pkey' => 'import_update_unit',
                'content' => 'Import cập nhật đơn vị',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            444 => 
            array (
                'id' => 449,
                'groups_id' => 5,
                'pkey' => 'management',
                'content' => 'Quản lý',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            445 => 
            array (
                'id' => 450,
                'groups_id' => 5,
                'pkey' => 'email',
                'content' => 'Email',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            446 => 
            array (
                'id' => 451,
                'groups_id' => 5,
                'pkey' => 'note_1',
                'content' => 'Ghi chú 1',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            447 => 
            array (
                'id' => 452,
                'groups_id' => 5,
                'pkey' => 'note_2',
                'content' => 'Ghi chú 2',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            448 => 
            array (
                'id' => 453,
                'groups_id' => 5,
                'pkey' => 'enable',
                'content' => 'Bật',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            449 => 
            array (
                'id' => 454,
                'groups_id' => 5,
                'pkey' => 'disable',
                'content' => 'Tắt',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            450 => 
            array (
                'id' => 455,
                'groups_id' => 5,
                'pkey' => 'folder_tree',
                'content' => 'Cây thư mục',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            451 => 
            array (
                'id' => 456,
                'groups_id' => 5,
                'pkey' => 'code',
                'content' => 'Mã',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            452 => 
            array (
                'id' => 457,
                'groups_id' => 5,
                'pkey' => 'name',
                'content' => 'Tên',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            453 => 
            array (
                'id' => 458,
                'groups_id' => 5,
                'pkey' => 'import_area',
                'content' => 'Import khu vực',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            454 => 
            array (
                'id' => 459,
                'groups_id' => 5,
                'pkey' => 'management_locations',
                'content' => 'Địa điểm quản lý',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            455 => 
            array (
                'id' => 460,
                'groups_id' => 5,
                'pkey' => 'department',
                'content' => 'Phòng ban',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            456 => 
            array (
                'id' => 461,
                'groups_id' => 5,
                'pkey' => 'enter_name',
                'content' => 'Nhập tên',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            457 => 
            array (
                'id' => 462,
                'groups_id' => 5,
                'pkey' => 'title_group',
                'content' => 'Nhóm chức danh',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            458 => 
            array (
                'id' => 463,
                'groups_id' => 5,
                'pkey' => 'store',
                'content' => 'Cửa hàng',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            459 => 
            array (
                'id' => 464,
                'groups_id' => 5,
                'pkey' => 'branch',
                'content' => 'Chi nhánh tỉnh',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            460 => 
            array (
                'id' => 465,
                'groups_id' => 5,
                'pkey' => 'office',
                'content' => 'Văn phòng',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            461 => 
            array (
                'id' => 466,
                'groups_id' => 5,
                'pkey' => 'subsidiaries_factories',
                'content' => 'Công ty con - nhà máy',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            462 => 
            array (
                'id' => 467,
                'groups_id' => 5,
                'pkey' => 'code_name_title',
                'content' => 'Nhập mã / tên chức danh',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            463 => 
            array (
                'id' => 468,
                'groups_id' => 5,
                'pkey' => 'title_code',
                'content' => 'Mã chức danh',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            464 => 
            array (
                'id' => 469,
                'groups_id' => 5,
                'pkey' => 'import_title',
                'content' => 'Import chức danh',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            465 => 
            array (
                'id' => 470,
                'groups_id' => 5,
                'pkey' => 'import_training_program',
                'content' => 'Import Chủ đề',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            466 => 
            array (
                'id' => 471,
                'groups_id' => 5,
                'pkey' => 'import_type_subject',
                'content' => 'Import mảng nghiệp vụ',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            467 => 
            array (
                'id' => 472,
                'groups_id' => 5,
                'pkey' => 'created_at',
                'content' => 'Ngày tạo',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            468 => 
            array (
                'id' => 473,
                'groups_id' => 5,
                'pkey' => 'person_create',
                'content' => 'Người khởi tạo',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            469 => 
            array (
                'id' => 474,
                'groups_id' => 5,
                'pkey' => 'training_create',
                'content' => 'Đơn vị khởi tạo',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            470 => 
            array (
                'id' => 475,
                'groups_id' => 5,
                'pkey' => 'brief',
                'content' => 'Tóm tắt',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            471 => 
            array (
                'id' => 476,
                'groups_id' => 5,
                'pkey' => 'description',
                'content' => 'Mô tả',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            472 => 
            array (
                'id' => 477,
                'groups_id' => 5,
                'pkey' => 'group',
                'content' => 'Nhóm',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            473 => 
            array (
                'id' => 478,
                'groups_id' => 5,
                'pkey' => 'commitment_frame',
                'content' => 'Khung cam kết',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            474 => 
            array (
                'id' => 479,
                'groups_id' => 5,
                'pkey' => 'choose_title',
                'content' => 'Chọn chức danh',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            475 => 
            array (
                'id' => 480,
                'groups_id' => 5,
                'pkey' => 'stt',
                'content' => 'STT',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            476 => 
            array (
                'id' => 481,
                'groups_id' => 5,
                'pkey' => 'to',
                'content' => 'Đến',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            477 => 
            array (
                'id' => 482,
                'groups_id' => 5,
                'pkey' => 'from',
                'content' => 'Từ',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            478 => 
            array (
                'id' => 483,
                'groups_id' => 5,
                'pkey' => 'time_day',
            'content' => 'Thời gian (ngày)',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            479 => 
            array (
                'id' => 484,
                'groups_id' => 5,
                'pkey' => 'edit',
                'content' => 'Chỉnh sửa',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            480 => 
            array (
                'id' => 485,
                'groups_id' => 5,
                'pkey' => 'min_cost',
                'content' => 'Số tiền tối thiểu',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            481 => 
            array (
                'id' => 486,
                'groups_id' => 5,
                'pkey' => 'max_cost',
                'content' => 'Số tiền tối đa',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            482 => 
            array (
                'id' => 487,
                'groups_id' => 5,
                'pkey' => 'update',
                'content' => 'Cập nhật',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            483 => 
            array (
                'id' => 488,
                'groups_id' => 5,
                'pkey' => 'enter_commit_day',
                'content' => 'Nhập số ngày cam kết',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            484 => 
            array (
                'id' => 489,
                'groups_id' => 5,
                'pkey' => 'action',
                'content' => 'Hành động',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            485 => 
            array (
                'id' => 490,
                'groups_id' => 5,
                'pkey' => 'delete',
                'content' => 'Xóa',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            486 => 
            array (
                'id' => 491,
                'groups_id' => 5,
                'pkey' => 'contact_person',
                'content' => 'Người liên hệ',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            487 => 
            array (
                'id' => 492,
                'groups_id' => 5,
                'pkey' => 'address',
                'content' => 'Địa chỉ',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            488 => 
            array (
                'id' => 493,
                'groups_id' => 5,
                'pkey' => 'phone',
                'content' => 'Số điện thoại',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            489 => 
            array (
                'id' => 494,
                'groups_id' => 5,
                'pkey' => 'form',
                'content' => 'Hình thức',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            490 => 
            array (
                'id' => 495,
                'groups_id' => 5,
                'pkey' => 'internal',
                'content' => 'Nội bộ',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            491 => 
            array (
                'id' => 496,
                'groups_id' => 5,
                'pkey' => 'outside',
                'content' => 'Bên ngoài',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            492 => 
            array (
                'id' => 497,
                'groups_id' => 5,
                'pkey' => 'choose_user',
                'content' => 'Chọn nhân viên',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            493 => 
            array (
                'id' => 498,
                'groups_id' => 5,
                'pkey' => 'account_number',
                'content' => 'Số tài khoản',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            494 => 
            array (
                'id' => 499,
                'groups_id' => 5,
                'pkey' => 'working',
                'content' => 'Đang làm việc',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            495 => 
            array (
                'id' => 500,
                'groups_id' => 5,
                'pkey' => 'lay_off',
                'content' => 'Nghỉ việc',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            496 => 
            array (
                'id' => 501,
                'groups_id' => 5,
                'pkey' => 'import_province_district',
                'content' => 'Import Tỉnh thành - Quận huyện',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            497 => 
            array (
                'id' => 502,
                'groups_id' => 5,
                'pkey' => 'point_online_course',
                'content' => 'Điểm thưởng khóa học trực tuyến',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            498 => 
            array (
                'id' => 503,
                'groups_id' => 5,
                'pkey' => 'point_offline_course',
                'content' => 'Điểm thưởng khóa học tập trung',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            499 => 
            array (
                'id' => 504,
                'groups_id' => 5,
                'pkey' => 'point_quiz',
                'content' => 'Điểm thưởng kỳ thi',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
        ));
        \DB::table('el_languages')->insert(array (
            0 => 
            array (
                'id' => 505,
                'groups_id' => 5,
                'pkey' => 'point_online_activitive_course',
                'content' => 'Điểm thưởng hoạt động khóa trực tuyến',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            1 => 
            array (
                'id' => 506,
                'groups_id' => 5,
                'pkey' => 'point_library',
                'content' => 'Điểm thưởng thư viện',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            2 => 
            array (
                'id' => 507,
                'groups_id' => 5,
                'pkey' => 'point_forum',
                'content' => 'Điểm thưởng diễn đàn',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            3 => 
            array (
                'id' => 508,
                'groups_id' => 5,
                'pkey' => 'key',
                'content' => 'Từ khóa',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            4 => 
            array (
                'id' => 509,
                'groups_id' => 5,
                'pkey' => 'point_default',
                'content' => 'Điểm mặc định',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            5 => 
            array (
                'id' => 510,
                'groups_id' => 5,
                'pkey' => 'value',
                'content' => 'Giá trị',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            6 => 
            array (
                'id' => 511,
                'groups_id' => 5,
                'pkey' => 'image',
                'content' => 'Hình ảnh',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            7 => 
            array (
                'id' => 512,
                'groups_id' => 5,
                'pkey' => 'add_new',
                'content' => 'Thêm mới',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            8 => 
            array (
                'id' => 513,
                'groups_id' => 5,
                'pkey' => 'choose_picture',
                'content' => 'Chọn hình ảnh',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            9 => 
            array (
                'id' => 514,
                'groups_id' => 5,
                'pkey' => 'add_child_badge',
                'content' => 'Thêm huy hiệu con',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            10 => 
            array (
                'id' => 515,
                'groups_id' => 5,
                'pkey' => 'rank',
                'content' => 'Hạng',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            11 => 
            array (
                'id' => 516,
                'groups_id' => 5,
                'pkey' => 'child_badge',
                'content' => 'Huy hiệu con',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            12 => 
            array (
                'id' => 517,
                'groups_id' => 6,
                'pkey' => 'area',
                'content' => 'Khu vực',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            13 => 
            array (
                'id' => 518,
                'groups_id' => 6,
                'pkey' => 'status',
                'content' => 'Trạng thái',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            14 => 
            array (
                'id' => 519,
                'groups_id' => 6,
                'pkey' => 'inactivity',
                'content' => 'Nghỉ việc',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            15 => 
            array (
                'id' => 520,
                'groups_id' => 6,
                'pkey' => 'doing',
                'content' => 'Đang làm',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            16 => 
            array (
                'id' => 521,
                'groups_id' => 6,
                'pkey' => 'enter_code_name_email_username',
                'content' => 'Nhập mã/ tên/ email/ tên đăng nhập',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            17 => 
            array (
                'id' => 522,
                'groups_id' => 6,
                'pkey' => 'avatar',
                'content' => 'Avatar',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            18 => 
            array (
                'id' => 523,
                'groups_id' => 6,
                'pkey' => 'employee_code',
                'content' => 'Mã nhân viên',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            19 => 
            array (
                'id' => 524,
                'groups_id' => 6,
                'pkey' => 'employee_name',
                'content' => 'Tên nhân viên',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            20 => 
            array (
                'id' => 525,
                'groups_id' => 6,
                'pkey' => 'employee_email',
                'content' => 'Email nhân viên',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            21 => 
            array (
                'id' => 526,
                'groups_id' => 6,
                'pkey' => 'title',
                'content' => 'Chức danh',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            22 => 
            array (
                'id' => 527,
                'groups_id' => 6,
                'pkey' => 'work_unit',
                'content' => 'Đơn vị công tác',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            23 => 
            array (
                'id' => 528,
                'groups_id' => 6,
                'pkey' => 'unit_manager',
                'content' => 'Đơn vị quản lý',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            24 => 
            array (
                'id' => 529,
                'groups_id' => 6,
                'pkey' => 'import',
                'content' => 'Import',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            25 => 
            array (
                'id' => 530,
                'groups_id' => 6,
                'pkey' => 'user',
                'content' => 'Nhân viên',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            26 => 
            array (
                'id' => 531,
                'groups_id' => 6,
                'pkey' => 'working_process',
                'content' => 'Quá trình công tác',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            27 => 
            array (
                'id' => 532,
                'groups_id' => 6,
                'pkey' => 'training_program_learned',
                'content' => 'Chủ đề đã học',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            28 => 
            array (
                'id' => 533,
                'groups_id' => 6,
                'pkey' => 'import_template',
                'content' => 'Mẫu import',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            29 => 
            array (
                'id' => 534,
                'groups_id' => 6,
                'pkey' => 'import_user',
                'content' => 'Import nhân viên',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            30 => 
            array (
                'id' => 535,
                'groups_id' => 6,
                'pkey' => 'import_working_process',
                'content' => 'Import quá trình công tác',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            31 => 
            array (
                'id' => 536,
                'groups_id' => 6,
                'pkey' => 'import_training_program_learned',
                'content' => 'Import Chủ đề đã học',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            32 => 
            array (
                'id' => 537,
                'groups_id' => 6,
                'pkey' => 'info',
                'content' => 'Thông tin',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            33 => 
            array (
                'id' => 538,
                'groups_id' => 6,
                'pkey' => 'add_new',
                'content' => 'Thêm mới',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            34 => 
            array (
                'id' => 539,
                'groups_id' => 6,
                'pkey' => 'user_name',
                'content' => 'Tên đăng nhập',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            35 => 
            array (
                'id' => 540,
                'groups_id' => 6,
                'pkey' => 'pass',
                'content' => 'Mật khẩu',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            36 => 
            array (
                'id' => 541,
                'groups_id' => 6,
                'pkey' => 'repassword',
                'content' => 'Nhập lại mật khẩu',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            37 => 
            array (
                'id' => 542,
                'groups_id' => 6,
                'pkey' => 'login_form',
                'content' => 'Hình thức đăng nhập',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            38 => 
            array (
                'id' => 543,
                'groups_id' => 6,
                'pkey' => 'they_staff',
                'content' => 'Họ',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            39 => 
            array (
                'id' => 544,
                'groups_id' => 6,
                'pkey' => 'email',
                'content' => 'Email',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            40 => 
            array (
                'id' => 545,
                'groups_id' => 6,
                'pkey' => 'position',
                'content' => 'Chức vụ',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            41 => 
            array (
                'id' => 546,
                'groups_id' => 6,
                'pkey' => 'address',
                'content' => 'Địa chỉ',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            42 => 
            array (
                'id' => 547,
                'groups_id' => 6,
                'pkey' => 'current_address',
                'content' => 'Nơi ở hiện tại',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            43 => 
            array (
                'id' => 548,
                'groups_id' => 6,
                'pkey' => 'name_contact_person',
                'content' => 'Họ tên người liên hệ',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            44 => 
            array (
                'id' => 549,
                'groups_id' => 6,
                'pkey' => 'relationship',
                'content' => 'Mối quan hệ',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            45 => 
            array (
                'id' => 550,
                'groups_id' => 6,
                'pkey' => 'phone_contact_person',
                'content' => 'Số diện thoại người liên hệ',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            46 => 
            array (
                'id' => 551,
                'groups_id' => 6,
                'pkey' => 'school',
                'content' => 'Trường',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            47 => 
            array (
                'id' => 552,
                'groups_id' => 6,
                'pkey' => 'majors',
                'content' => 'Chuyên ngành',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            48 => 
            array (
                'id' => 553,
                'groups_id' => 6,
                'pkey' => 'license',
                'content' => 'Chứng chỉ/Bằng lái',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            49 => 
            array (
                'id' => 554,
                'groups_id' => 6,
                'pkey' => 'suspension_date',
                'content' => 'Ngày tạm hoãn',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            50 => 
            array (
                'id' => 555,
                'groups_id' => 6,
                'pkey' => 'reason',
                'content' => 'Lý do',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            51 => 
            array (
                'id' => 556,
                'groups_id' => 6,
                'pkey' => 'commendation',
                'content' => 'Khen thưởng',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            52 => 
            array (
                'id' => 557,
                'groups_id' => 6,
                'pkey' => 'discipline',
                'content' => 'Kỷ luật',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            53 => 
            array (
                'id' => 558,
                'groups_id' => 6,
                'pkey' => 'special_skills',
                'content' => 'Năng khiếu đặc biệt',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            54 => 
            array (
                'id' => 559,
                'groups_id' => 6,
                'pkey' => 'note',
                'content' => 'Ghi chú',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            55 => 
            array (
                'id' => 560,
                'groups_id' => 6,
                'pkey' => 'rank',
                'content' => 'Cấp bậc',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            56 => 
            array (
                'id' => 561,
                'groups_id' => 6,
                'pkey' => 'gender',
                'content' => 'Giới tính',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            57 => 
            array (
                'id' => 562,
                'groups_id' => 6,
                'pkey' => 'male',
                'content' => 'Nam',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            58 => 
            array (
                'id' => 563,
                'groups_id' => 6,
                'pkey' => 'female',
                'content' => 'Nữ',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            59 => 
            array (
                'id' => 564,
                'groups_id' => 6,
                'pkey' => 'marital_status',
                'content' => 'Trình trạng hôn nhân',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            60 => 
            array (
                'id' => 565,
                'groups_id' => 6,
                'pkey' => 'married',
                'content' => 'Đã kết hôn',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            61 => 
            array (
                'id' => 566,
                'groups_id' => 6,
                'pkey' => 'single',
                'content' => 'Độc thân',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            62 => 
            array (
                'id' => 567,
                'groups_id' => 6,
                'pkey' => 'phone',
                'content' => 'Số điện thoại',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            63 => 
            array (
                'id' => 568,
                'groups_id' => 6,
                'pkey' => 'dob',
                'content' => 'Ngày sinh',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            64 => 
            array (
                'id' => 569,
                'groups_id' => 6,
                'pkey' => 'identity_card',
                'content' => 'CMND',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            65 => 
            array (
                'id' => 570,
                'groups_id' => 6,
                'pkey' => 'date_issue',
                'content' => 'Ngày cấp',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            66 => 
            array (
                'id' => 571,
                'groups_id' => 6,
                'pkey' => 'issued_by',
                'content' => 'Nơi cấp',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            67 => 
            array (
                'id' => 572,
                'groups_id' => 6,
                'pkey' => 'experience',
            'content' => 'Thâm niên trong nghề (Tháng)',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            68 => 
            array (
                'id' => 573,
                'groups_id' => 6,
                'pkey' => 'level',
                'content' => 'Trình độ',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            69 => 
            array (
                'id' => 574,
                'groups_id' => 6,
                'pkey' => 'contract_signing_date',
                'content' => 'Ngày ký hợp đồng lao động',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            70 => 
            array (
                'id' => 575,
                'groups_id' => 6,
                'pkey' => 'effective_date',
                'content' => 'Ngày hiệu lực',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            71 => 
            array (
                'id' => 576,
                'groups_id' => 6,
                'pkey' => 'expiration_date',
                'content' => 'Ngày hết hạn',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            72 => 
            array (
                'id' => 577,
                'groups_id' => 6,
                'pkey' => 'day_work',
                'content' => 'Ngày vào làm',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            73 => 
            array (
                'id' => 578,
                'groups_id' => 6,
                'pkey' => 'date_off',
                'content' => 'Ngày nghỉ',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            74 => 
            array (
                'id' => 579,
                'groups_id' => 6,
                'pkey' => 'date_title_appointment',
                'content' => 'Ngày bổ nhiệm chức danh',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            75 => 
            array (
                'id' => 580,
                'groups_id' => 6,
                'pkey' => 'end_date_title_appointment',
                'content' => 'Ngày kết thúc bổ nhiệm chức danh',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            76 => 
            array (
                'id' => 581,
                'groups_id' => 6,
                'pkey' => 'type_labor_contract',
                'content' => 'Loại hợp đồng lao động',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            77 => 
            array (
                'id' => 582,
                'groups_id' => 6,
                'pkey' => 'part_time',
                'content' => 'Thời vụ',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            78 => 
            array (
                'id' => 583,
                'groups_id' => 6,
                'pkey' => 'probationary',
                'content' => 'Thử việc',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            79 => 
            array (
                'id' => 584,
                'groups_id' => 6,
                'pkey' => 'has_term',
                'content' => 'Có thời hạn',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            80 => 
            array (
                'id' => 585,
                'groups_id' => 6,
                'pkey' => 'indefinite',
                'content' => 'Không thời hạn',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            81 => 
            array (
                'id' => 586,
                'groups_id' => 6,
                'pkey' => 'your_referral_code',
                'content' => 'Mã giới thiệu của bạn',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            82 => 
            array (
                'id' => 587,
                'groups_id' => 6,
                'pkey' => 'roadmap',
                'content' => 'Tháp đào tạo',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            83 => 
            array (
                'id' => 588,
                'groups_id' => 6,
                'pkey' => 'quiz_result',
                'content' => 'Kết quả thi',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            84 => 
            array (
                'id' => 589,
                'groups_id' => 6,
                'pkey' => 'career_roadmap',
                'content' => 'Lộ trình nghề nghiệp',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            85 => 
            array (
                'id' => 590,
                'groups_id' => 6,
                'pkey' => 'training_process',
                'content' => 'Quá trình đào tạo',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            86 => 
            array (
                'id' => 591,
                'groups_id' => 6,
                'pkey' => 'training_program_code',
                'content' => 'Mã Chủ đề',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            87 => 
            array (
                'id' => 592,
                'groups_id' => 6,
                'pkey' => 'training_program_name',
                'content' => 'Tên Chủ đề',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            88 => 
            array (
                'id' => 593,
                'groups_id' => 6,
                'pkey' => 'subject_code',
                'content' => 'Mã chuyên đề',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            89 => 
            array (
                'id' => 594,
                'groups_id' => 6,
                'pkey' => 'subject',
                'content' => 'Chuyên đề',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            90 => 
            array (
                'id' => 595,
                'groups_id' => 6,
                'pkey' => 'training_form',
                'content' => 'Hình thức đào tạo',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            91 => 
            array (
                'id' => 596,
                'groups_id' => 6,
                'pkey' => 'time_held',
                'content' => 'Thời gian tổ chức',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            92 => 
            array (
                'id' => 597,
                'groups_id' => 6,
                'pkey' => 'date_effect',
                'content' => 'Thới gian hiệu lực',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            93 => 
            array (
                'id' => 598,
                'groups_id' => 6,
                'pkey' => 'result',
                'content' => 'Kết quả',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            94 => 
            array (
                'id' => 599,
                'groups_id' => 6,
                'pkey' => 'certificates',
                'content' => 'Chứng chỉ',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            95 => 
            array (
                'id' => 600,
                'groups_id' => 6,
                'pkey' => 'from_date',
                'content' => 'Từ ngày',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            96 => 
            array (
                'id' => 601,
                'groups_id' => 6,
                'pkey' => 'to_date',
                'content' => 'Đến ngày',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            97 => 
            array (
                'id' => 602,
                'groups_id' => 6,
                'pkey' => 'score',
                'content' => 'Điểm',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            98 => 
            array (
                'id' => 603,
                'groups_id' => 6,
                'pkey' => 'passed',
                'content' => 'Đạt',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            99 => 
            array (
                'id' => 604,
                'groups_id' => 6,
                'pkey' => 'course_code',
                'content' => 'Mã khóa học',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            100 => 
            array (
                'id' => 605,
                'groups_id' => 6,
                'pkey' => 'course_name',
                'content' => 'Tên khóa học',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            101 => 
            array (
                'id' => 606,
                'groups_id' => 6,
                'pkey' => 'training_type',
                'content' => 'Loại hình đào tạo',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            102 => 
            array (
                'id' => 607,
                'groups_id' => 6,
                'pkey' => 'quiz_code',
                'content' => 'Mã khảo thí',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            103 => 
            array (
                'id' => 608,
                'groups_id' => 6,
                'pkey' => 'quiz',
                'content' => 'Khảo thí',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            104 => 
            array (
                'id' => 609,
                'groups_id' => 6,
                'pkey' => 'start_date',
                'content' => 'Ngày bắt đầu',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            105 => 
            array (
                'id' => 610,
                'groups_id' => 6,
                'pkey' => 'end_date',
                'content' => 'Ngày kết thúc',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            106 => 
            array (
                'id' => 611,
                'groups_id' => 6,
                'pkey' => 'exam_time_minutes',
            'content' => 'Thời gian thi (Phút)',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            107 => 
            array (
                'id' => 612,
                'groups_id' => 6,
                'pkey' => 'training_program',
                'content' => 'Chủ đề',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            108 => 
            array (
                'id' => 613,
                'groups_id' => 6,
                'pkey' => 'time',
                'content' => 'Thời gian',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            109 => 
            array (
                'id' => 614,
                'groups_id' => 6,
                'pkey' => 'unit',
                'content' => 'Đơn vị',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            110 => 
            array (
                'id' => 615,
                'groups_id' => 6,
                'pkey' => 'view',
                'content' => 'Xem',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            111 => 
            array (
                'id' => 616,
                'groups_id' => 6,
                'pkey' => 'info_unit_by_user',
                'content' => 'Thông tin đơn vị của :user',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            112 => 
            array (
                'id' => 617,
                'groups_id' => 6,
                'pkey' => 'enter_code_name_user',
                'content' => 'Nhập mã/ tên nhân viên',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            113 => 
            array (
                'id' => 618,
                'groups_id' => 28,
                'pkey' => 'note_update_lang',
                'content' => 'LƯU Ý: KHÔNG THAY ĐỔI HOẶC XÓA TỪ SAU DẤU HAI CHẤM. VD. :i, :user, :level, v.v....',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            114 => 
            array (
                'id' => 619,
                'groups_id' => 6,
                'pkey' => 'date',
                'content' => 'Ngày',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            115 => 
            array (
                'id' => 620,
                'groups_id' => 6,
                'pkey' => 'import_user_take_leave',
                'content' => 'Import danh sách nhân viên nghỉ phép',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            116 => 
            array (
                'id' => 621,
                'groups_id' => 6,
                'pkey' => 'other',
                'content' => 'Khác',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            117 => 
            array (
                'id' => 622,
                'groups_id' => 6,
                'pkey' => 'created_at',
                'content' => 'Ngày tạo',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            118 => 
            array (
                'id' => 623,
                'groups_id' => 6,
                'pkey' => 'heading',
                'content' => 'Tiêu đề',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            119 => 
            array (
                'id' => 624,
                'groups_id' => 6,
                'pkey' => 'content',
                'content' => 'Nội dung',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            120 => 
            array (
                'id' => 625,
                'groups_id' => 6,
                'pkey' => 'training_path',
                'content' => 'Lộ trình đào tạo',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            121 => 
            array (
                'id' => 626,
                'groups_id' => 6,
                'pkey' => 'subject_registered',
                'content' => 'Khóa học đã đăng ký',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            122 => 
            array (
                'id' => 627,
                'groups_id' => 6,
                'pkey' => 'student_cost',
                'content' => 'Chi phí học viên',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            123 => 
            array (
                'id' => 628,
                'groups_id' => 6,
                'pkey' => 'violate_rules',
                'content' => 'Vi phạm nội dung',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            124 => 
            array (
                'id' => 629,
                'groups_id' => 6,
                'pkey' => 'promotion',
                'content' => 'Quà tặng',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            125 => 
            array (
                'id' => 630,
                'groups_id' => 6,
                'pkey' => 'history_point',
                'content' => 'Lịch sử điểm',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            126 => 
            array (
                'id' => 631,
                'groups_id' => 6,
                'pkey' => 'scan_code_infomation',
                'content' => 'Quét mã để lấy thông tin',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            127 => 
            array (
                'id' => 632,
                'groups_id' => 6,
                'pkey' => 'login_code',
                'content' => 'Mã đăng nhập',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            128 => 
            array (
                'id' => 633,
                'groups_id' => 6,
                'pkey' => 'full_name',
                'content' => 'Họ và tên',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            129 => 
            array (
                'id' => 634,
                'groups_id' => 6,
                'pkey' => 'code',
                'content' => 'Mã',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            130 => 
            array (
                'id' => 635,
                'groups_id' => 6,
                'pkey' => 'change_avatar',
                'content' => 'Đổi ảnh đại diện',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            131 => 
            array (
                'id' => 636,
                'groups_id' => 6,
                'pkey' => 'size',
                'content' => 'Kích thước',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            132 => 
            array (
                'id' => 637,
                'groups_id' => 6,
                'pkey' => 'change_pass',
                'content' => 'Đổi mật khẩu',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            133 => 
            array (
                'id' => 638,
                'groups_id' => 6,
                'pkey' => 'old_password',
                'content' => 'Mật khẩu cũ',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            134 => 
            array (
                'id' => 639,
                'groups_id' => 6,
                'pkey' => 'new_password',
                'content' => 'Mật khẩu mới',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            135 => 
            array (
                'id' => 640,
                'groups_id' => 6,
                'pkey' => 'confirm_password',
                'content' => 'Xác nhận mật khẩu',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            136 => 
            array (
                'id' => 641,
                'groups_id' => 6,
                'pkey' => 'training_form_expected',
                'content' => 'Hình thức đào tạo dự kiến',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            137 => 
            array (
                'id' => 642,
                'groups_id' => 6,
                'pkey' => 'add_title',
                'content' => 'Thêm chức danh',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            138 => 
            array (
                'id' => 643,
                'groups_id' => 1,
                'pkey' => 'add_roadmap',
                'content' => 'Thêm lộ trình',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            139 => 
            array (
                'id' => 644,
                'groups_id' => 2,
                'pkey' => 'edit',
                'content' => 'Chỉnh sửa',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            140 => 
            array (
                'id' => 645,
                'groups_id' => 6,
                'pkey' => 'seniority',
            'content' => 'Thâm niên (năm)',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            141 => 
            array (
                'id' => 646,
                'groups_id' => 6,
                'pkey' => 'cancel',
                'content' => 'Hủy',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            142 => 
            array (
                'id' => 647,
                'groups_id' => 6,
                'pkey' => 'course',
                'content' => 'Khóa học',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            143 => 
            array (
                'id' => 648,
                'groups_id' => 6,
                'pkey' => 'total',
                'content' => 'Tổng',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            144 => 
            array (
                'id' => 649,
                'groups_id' => 6,
                'pkey' => 'course_time',
                'content' => 'Thời lượng khóa học',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            145 => 
            array (
                'id' => 650,
                'groups_id' => 6,
                'pkey' => 'total_course_time',
                'content' => 'Tổng thời lượng khóa học',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            146 => 
            array (
                'id' => 651,
                'groups_id' => 6,
                'pkey' => 'schedule_discipline',
                'content' => 'Buổi học vi phạm',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            147 => 
            array (
                'id' => 652,
                'groups_id' => 6,
                'pkey' => 'discipline',
                'content' => 'Vi phạm',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            148 => 
            array (
                'id' => 653,
                'groups_id' => 6,
                'pkey' => 'absent',
                'content' => 'Loại nghỉ',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            149 => 
            array (
                'id' => 654,
                'groups_id' => 6,
                'pkey' => 'absent_reason',
                'content' => 'Lý do vắng',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            150 => 
            array (
                'id' => 655,
                'groups_id' => 6,
                'pkey' => 'list',
                'content' => 'Danh sách',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            151 => 
            array (
                'id' => 656,
                'groups_id' => 6,
                'pkey' => 'history',
                'content' => 'Lịch sử',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            152 => 
            array (
                'id' => 657,
                'groups_id' => 6,
                'pkey' => 'type',
                'content' => 'Loại',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            153 => 
            array (
                'id' => 658,
                'groups_id' => 6,
                'pkey' => 'quantity',
                'content' => 'Số lượng',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            154 => 
            array (
                'id' => 659,
                'groups_id' => 6,
                'pkey' => 'period',
                'content' => 'Thời hạn',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            155 => 
            array (
                'id' => 660,
                'groups_id' => 7,
                'pkey' => 'enter_code_name_title',
                'content' => 'Nhập mã/ tên chức danh',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            156 => 
            array (
                'id' => 661,
                'groups_id' => 7,
                'pkey' => 'title_code',
                'content' => 'Mã chức danh',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            157 => 
            array (
                'id' => 662,
                'groups_id' => 7,
                'pkey' => 'title_name',
                'content' => 'Tên chức danh',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            158 => 
            array (
                'id' => 663,
                'groups_id' => 7,
                'pkey' => 'roadmap_primary',
                'content' => 'Lộ trình chính',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            159 => 
            array (
                'id' => 664,
                'groups_id' => 7,
                'pkey' => 'roadmap',
                'content' => 'Tháp đào tạo',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            160 => 
            array (
                'id' => 665,
                'groups_id' => 7,
                'pkey' => 'seniority',
            'content' => 'Thâm niên (năm)',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            161 => 
            array (
                'id' => 666,
                'groups_id' => 7,
                'pkey' => 'add_title',
                'content' => 'Thêm chức danh',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            162 => 
            array (
                'id' => 667,
                'groups_id' => 7,
                'pkey' => 'import_career_roadmap',
                'content' => 'Import lộ trình nghề nghiệp',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            163 => 
            array (
                'id' => 668,
                'groups_id' => 7,
                'pkey' => 'add_roadmap',
                'content' => 'Thêm lộ trình',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            164 => 
            array (
                'id' => 669,
                'groups_id' => 7,
                'pkey' => 'roadmap_name',
                'content' => 'Tên lộ trình',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            165 => 
            array (
                'id' => 670,
                'groups_id' => 7,
                'pkey' => 'title',
                'content' => 'Chức danh',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            166 => 
            array (
                'id' => 671,
                'groups_id' => 7,
                'pkey' => 'parent_title',
                'content' => 'Chức danh cha',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            167 => 
            array (
                'id' => 672,
                'groups_id' => 7,
                'pkey' => 'edit_title',
                'content' => 'Chỉnh sửa chức danh',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            168 => 
            array (
                'id' => 673,
                'groups_id' => 8,
                'pkey' => 'enter_name_survey',
                'content' => 'Nhập tên khảo sát',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            169 => 
            array (
                'id' => 674,
                'groups_id' => 8,
                'pkey' => 'open',
                'content' => 'Mở',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            170 => 
            array (
                'id' => 675,
                'groups_id' => 8,
                'pkey' => 'survey_name',
                'content' => 'Tên khảo sát',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            171 => 
            array (
                'id' => 676,
                'groups_id' => 8,
                'pkey' => 'time',
                'content' => 'Thời gian',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            172 => 
            array (
                'id' => 677,
                'groups_id' => 8,
                'pkey' => 'number_of_questions',
                'content' => 'Số lượng câu hỏi',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            173 => 
            array (
                'id' => 678,
                'groups_id' => 8,
                'pkey' => 'join',
                'content' => 'Tham gia',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            174 => 
            array (
                'id' => 679,
                'groups_id' => 8,
                'pkey' => 'object',
                'content' => 'Đối tượng',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            175 => 
            array (
                'id' => 680,
                'groups_id' => 8,
                'pkey' => 'report',
                'content' => 'Báo cáo',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            176 => 
            array (
                'id' => 681,
                'groups_id' => 8,
                'pkey' => 'review_template',
                'content' => 'Xem mẫu',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            177 => 
            array (
                'id' => 682,
                'groups_id' => 8,
                'pkey' => 'survey_template',
                'content' => 'Mẫu khảo sát',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            178 => 
            array (
                'id' => 683,
                'groups_id' => 8,
                'pkey' => 'enter_template_name',
                'content' => 'Nhập tên mẫu',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            179 => 
            array (
                'id' => 684,
                'groups_id' => 8,
                'pkey' => 'template_name',
                'content' => 'Tên mẫu',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            180 => 
            array (
                'id' => 685,
                'groups_id' => 8,
                'pkey' => 'created_by',
                'content' => 'Tạo bởi',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            181 => 
            array (
                'id' => 686,
                'groups_id' => 8,
                'pkey' => 'update_by',
                'content' => 'Cập nhật bởi',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            182 => 
            array (
                'id' => 687,
                'groups_id' => 8,
                'pkey' => 'add_new',
                'content' => 'Thêm mới',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            183 => 
            array (
                'id' => 688,
                'groups_id' => 8,
                'pkey' => 'info',
                'content' => 'Thông tin',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            184 => 
            array (
                'id' => 689,
                'groups_id' => 8,
                'pkey' => 'category',
                'content' => 'Đề mục',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            185 => 
            array (
                'id' => 690,
                'groups_id' => 8,
                'pkey' => 'add_question',
                'content' => 'Thêm câu hỏi',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            186 => 
            array (
                'id' => 691,
                'groups_id' => 8,
                'pkey' => 'question_code',
                'content' => 'Mã câu hỏi',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            187 => 
            array (
                'id' => 692,
                'groups_id' => 8,
                'pkey' => 'question',
                'content' => 'Câu hỏi',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            188 => 
            array (
                'id' => 693,
                'groups_id' => 8,
                'pkey' => 'question_type',
                'content' => 'Loại câu hỏi',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            189 => 
            array (
                'id' => 694,
                'groups_id' => 8,
                'pkey' => 'choice',
                'content' => 'Trắc nghiệm',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            190 => 
            array (
                'id' => 695,
                'groups_id' => 8,
                'pkey' => 'essay',
                'content' => 'Tự luận',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            191 => 
            array (
                'id' => 696,
                'groups_id' => 8,
                'pkey' => 'text',
                'content' => 'Nhập text',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            192 => 
            array (
                'id' => 697,
                'groups_id' => 8,
                'pkey' => 'matrix',
                'content' => 'Ma trận',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            193 => 
            array (
                'id' => 698,
                'groups_id' => 8,
                'pkey' => 'matrix_text',
            'content' => 'Ma trận (Nhập text)',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            194 => 
            array (
                'id' => 699,
                'groups_id' => 8,
                'pkey' => 'dropdown',
                'content' => 'Lựa chọn',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            195 => 
            array (
                'id' => 700,
                'groups_id' => 8,
                'pkey' => 'sort',
                'content' => 'Sắp xếp',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            196 => 
            array (
                'id' => 701,
                'groups_id' => 8,
                'pkey' => 'percent',
                'content' => 'Phần trăm',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            197 => 
            array (
                'id' => 702,
                'groups_id' => 8,
                'pkey' => 'number',
                'content' => 'Nhập số',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            198 => 
            array (
                'id' => 703,
                'groups_id' => 8,
                'pkey' => 'answer_code',
                'content' => 'Mã tùy chọn',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            199 => 
            array (
                'id' => 704,
                'groups_id' => 8,
                'pkey' => 'answer_name',
                'content' => 'Tùy chọn',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            200 => 
            array (
                'id' => 705,
                'groups_id' => 8,
                'pkey' => 'enter_text',
                'content' => 'Nhập text',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            201 => 
            array (
                'id' => 706,
                'groups_id' => 8,
                'pkey' => 'add_row',
                'content' => 'Thêm dòng',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            202 => 
            array (
                'id' => 707,
                'groups_id' => 8,
                'pkey' => 'add_col',
                'content' => 'Thêm cột',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            203 => 
            array (
                'id' => 708,
                'groups_id' => 8,
                'pkey' => 'add_answer',
                'content' => 'Thêm tùy chọn',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            204 => 
            array (
                'id' => 709,
                'groups_id' => 8,
                'pkey' => 'multi_choose',
                'content' => 'Chọn nhiều',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            205 => 
            array (
                'id' => 710,
                'groups_id' => 8,
                'pkey' => 'head_code',
                'content' => 'Mã tiêu đề',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            206 => 
            array (
                'id' => 711,
                'groups_id' => 8,
                'pkey' => 'heading',
                'content' => 'Tiêu đề',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            207 => 
            array (
                'id' => 712,
                'groups_id' => 8,
                'pkey' => 'content',
                'content' => 'Nội dung',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            208 => 
            array (
                'id' => 713,
                'groups_id' => 8,
                'pkey' => 'date_format',
                'content' => 'Ngày/Tháng/Năm',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            209 => 
            array (
                'id' => 714,
                'groups_id' => 8,
                'pkey' => 'picture',
                'content' => 'Hình ảnh',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            210 => 
            array (
                'id' => 715,
                'groups_id' => 8,
                'pkey' => 'choose_picture',
                'content' => 'Chọn hình ảnh',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            211 => 
            array (
                'id' => 716,
                'groups_id' => 8,
                'pkey' => 'start',
                'content' => 'Bắt đầu',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            212 => 
            array (
                'id' => 717,
                'groups_id' => 8,
                'pkey' => 'over',
                'content' => 'Kết thúc',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            213 => 
            array (
                'id' => 718,
                'groups_id' => 8,
                'pkey' => 'another_suggestion',
                'content' => 'Đề xuất khác',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            214 => 
            array (
                'id' => 719,
                'groups_id' => 8,
                'pkey' => 'enable',
                'content' => 'Bật',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            215 => 
            array (
                'id' => 720,
                'groups_id' => 8,
                'pkey' => 'disable',
                'content' => 'Tắt',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            216 => 
            array (
                'id' => 721,
                'groups_id' => 8,
                'pkey' => 'status',
                'content' => 'Trạng thái',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            217 => 
            array (
                'id' => 722,
                'groups_id' => 8,
                'pkey' => 'reward_points',
                'content' => 'Điểm thưởng',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            218 => 
            array (
                'id' => 723,
                'groups_id' => 8,
                'pkey' => 'object_belong',
                'content' => 'Đối tượng thuộc',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            219 => 
            array (
                'id' => 724,
                'groups_id' => 8,
                'pkey' => 'unit',
                'content' => 'Đơn vị',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            220 => 
            array (
                'id' => 725,
                'groups_id' => 8,
                'pkey' => 'title',
                'content' => 'Chức danh',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            221 => 
            array (
                'id' => 726,
                'groups_id' => 8,
                'pkey' => 'user',
                'content' => 'Nhân viên',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            222 => 
            array (
                'id' => 727,
                'groups_id' => 8,
                'pkey' => 'select_all',
                'content' => 'Chọn hết',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            223 => 
            array (
                'id' => 728,
                'groups_id' => 8,
                'pkey' => 'employee_code',
                'content' => 'Mã nhân viên
',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            224 => 
            array (
                'id' => 729,
                'groups_id' => 8,
                'pkey' => 'employee_name',
                'content' => 'Tên nhân viên',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            225 => 
            array (
                'id' => 730,
                'groups_id' => 8,
                'pkey' => 'employee_email',
                'content' => 'Email nhân viên',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            226 => 
            array (
                'id' => 731,
                'groups_id' => 8,
                'pkey' => 'work_unit',
                'content' => 'Đơn vị công tác',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            227 => 
            array (
                'id' => 732,
                'groups_id' => 8,
                'pkey' => 'unit_manager',
                'content' => 'Đơn vị quản lý',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            228 => 
            array (
                'id' => 733,
                'groups_id' => 8,
                'pkey' => 'import_user',
                'content' => 'Import người dùng',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            229 => 
            array (
                'id' => 734,
                'groups_id' => 8,
                'pkey' => 'scoring_method',
                'content' => 'Cách tính điểm',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            230 => 
            array (
                'id' => 735,
                'groups_id' => 8,
                'pkey' => 'survey_complete',
                'content' => 'Hoàn thành khảo sát',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            231 => 
            array (
                'id' => 736,
                'groups_id' => 8,
                'pkey' => 'bonus_points',
                'content' => 'Mức điểm thưởng',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            232 => 
            array (
                'id' => 737,
                'groups_id' => 8,
                'pkey' => 'area',
                'content' => 'Khu vực',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            233 => 
            array (
                'id' => 738,
                'groups_id' => 8,
                'pkey' => 'inactivity',
                'content' => 'Nghỉ việc',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            234 => 
            array (
                'id' => 739,
                'groups_id' => 8,
                'pkey' => 'doing',
                'content' => 'Đang làm',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            235 => 
            array (
                'id' => 740,
                'groups_id' => 8,
                'pkey' => 'probationary',
                'content' => 'Thử việc',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            236 => 
            array (
                'id' => 741,
                'groups_id' => 8,
                'pkey' => 'pause',
                'content' => 'Tạm hoãn',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            237 => 
            array (
                'id' => 742,
                'groups_id' => 8,
                'pkey' => 'enter_code_name_user',
                'content' => 'Nhập mã/ tên nhân viên',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            238 => 
            array (
                'id' => 743,
                'groups_id' => 8,
                'pkey' => 'fullname',
                'content' => 'Họ và tên',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            239 => 
            array (
                'id' => 744,
                'groups_id' => 8,
                'pkey' => 'info_survey',
                'content' => 'Thông tin khảo sát',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            240 => 
            array (
                'id' => 745,
                'groups_id' => 8,
                'pkey' => 'start_date',
                'content' => 'Ngày bắt đầu',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            241 => 
            array (
                'id' => 746,
                'groups_id' => 8,
                'pkey' => 'end_date',
                'content' => 'Ngày kết thúc',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            242 => 
            array (
                'id' => 747,
                'groups_id' => 8,
                'pkey' => 'info_surveyor',
                'content' => 'Thông tin người khảo sát',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            243 => 
            array (
                'id' => 748,
                'groups_id' => 8,
                'pkey' => 'answers',
                'content' => 'Câu trả lời',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            244 => 
            array (
                'id' => 749,
                'groups_id' => 8,
                'pkey' => 'completed',
                'content' => 'Đã hoàn thành',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            245 => 
            array (
                'id' => 750,
                'groups_id' => 8,
                'pkey' => 'take_survey',
                'content' => 'Làm khảo sát',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            246 => 
            array (
                'id' => 751,
                'groups_id' => 8,
                'pkey' => 'end_survey',
                'content' => 'Kết thúc khảo sát',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            247 => 
            array (
                'id' => 752,
                'groups_id' => 8,
                'pkey' => 'view_survey',
                'content' => 'Xem khảo sát',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            248 => 
            array (
                'id' => 753,
                'groups_id' => 8,
                'pkey' => 'edit_survey',
                'content' => 'Chỉnh sửa',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            249 => 
            array (
                'id' => 754,
                'groups_id' => 9,
                'pkey' => 'enter_code_name',
                'content' => 'Nhập mã/ tên',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            250 => 
            array (
                'id' => 755,
                'groups_id' => 9,
                'pkey' => 'status',
                'content' => 'Trạng thái',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            251 => 
            array (
                'id' => 756,
                'groups_id' => 9,
                'pkey' => 'image',
                'content' => 'Hình ảnh',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            252 => 
            array (
                'id' => 757,
                'groups_id' => 9,
                'pkey' => 'situation_topic_name',
                'content' => 'Tên chủ đề tình huống',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            253 => 
            array (
                'id' => 758,
                'groups_id' => 9,
                'pkey' => 'situation_topic_code',
                'content' => 'Mã chủ đề tình huống',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            254 => 
            array (
                'id' => 759,
                'groups_id' => 9,
                'pkey' => 'situations_discuss',
                'content' => 'Chuyên đề tình huống',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            255 => 
            array (
                'id' => 760,
                'groups_id' => 9,
                'pkey' => 'size',
                'content' => 'Kích thước',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            256 => 
            array (
                'id' => 761,
                'groups_id' => 9,
                'pkey' => 'choose_picture',
                'content' => 'Chọn hình ảnh',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            257 => 
            array (
                'id' => 762,
                'groups_id' => 9,
                'pkey' => 'enable',
                'content' => 'Bật',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            258 => 
            array (
                'id' => 763,
                'groups_id' => 9,
                'pkey' => 'disable',
                'content' => 'Tắt',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            259 => 
            array (
                'id' => 764,
                'groups_id' => 9,
                'pkey' => 'created_at',
                'content' => 'Ngày tạo',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            260 => 
            array (
                'id' => 765,
                'groups_id' => 9,
                'pkey' => 'situations_discuss_name',
                'content' => 'Tên chuyên đề tình huống',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            261 => 
            array (
                'id' => 766,
                'groups_id' => 9,
                'pkey' => 'situations_discuss_code',
                'content' => 'Mã chuyên đề tình huống',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            262 => 
            array (
                'id' => 767,
                'groups_id' => 9,
                'pkey' => 'comment',
                'content' => 'Bình luận',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            263 => 
            array (
                'id' => 768,
                'groups_id' => 9,
                'pkey' => 'description',
                'content' => 'Mô tả',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            264 => 
            array (
                'id' => 769,
                'groups_id' => 9,
                'pkey' => 'add_new',
                'content' => 'Thêm mới',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            265 => 
            array (
                'id' => 770,
                'groups_id' => 9,
                'pkey' => 'name_situations',
                'content' => 'Tên xử lý tình huống',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            266 => 
            array (
                'id' => 771,
                'groups_id' => 9,
                'pkey' => 'code_situations',
                'content' => 'Mã xử lý tình huống',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            267 => 
            array (
                'id' => 772,
                'groups_id' => 9,
                'pkey' => 'comment_situation',
                'content' => 'Bình luận tình huống',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            268 => 
            array (
                'id' => 773,
                'groups_id' => 9,
                'pkey' => 'title',
                'content' => 'Chức danh',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            269 => 
            array (
                'id' => 774,
                'groups_id' => 9,
                'pkey' => 'unit',
                'content' => 'Đơn vị',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            270 => 
            array (
                'id' => 775,
                'groups_id' => 9,
                'pkey' => 'area',
                'content' => 'Khu vực',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            271 => 
            array (
                'id' => 776,
                'groups_id' => 9,
                'pkey' => 'user_comment',
                'content' => 'Tên người bình luận',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            272 => 
            array (
                'id' => 777,
                'groups_id' => 9,
                'pkey' => 'work_unit',
                'content' => 'Đơn vị công tác',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            273 => 
            array (
                'id' => 778,
                'groups_id' => 9,
                'pkey' => 'unit_manager',
                'content' => 'Đơn vị quản lý',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            274 => 
            array (
                'id' => 779,
                'groups_id' => 9,
                'pkey' => 'enter_code_name_situations',
                'content' => 'Nhập mã/ tên tình huống',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            275 => 
            array (
                'id' => 780,
                'groups_id' => 9,
                'pkey' => 'start_date',
                'content' => 'Ngày bắt đầu',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            276 => 
            array (
                'id' => 781,
                'groups_id' => 9,
                'pkey' => 'end_date',
                'content' => 'Ngày kết thúc',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            277 => 
            array (
                'id' => 782,
                'groups_id' => 9,
                'pkey' => 'like',
                'content' => 'Lượt thích',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            278 => 
            array (
                'id' => 783,
                'groups_id' => 9,
                'pkey' => 'code',
                'content' => 'Mã',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            279 => 
            array (
                'id' => 784,
                'groups_id' => 9,
                'pkey' => 'send',
                'content' => 'Gửi',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            280 => 
            array (
                'id' => 785,
                'groups_id' => 9,
                'pkey' => 'delete',
                'content' => 'Xóa',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            281 => 
            array (
                'id' => 786,
                'groups_id' => 9,
                'pkey' => 'edit',
                'content' => 'Sửa',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            282 => 
            array (
                'id' => 787,
                'groups_id' => 10,
                'pkey' => 'enter_name_category',
                'content' => 'Nhập tên danh mục',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            283 => 
            array (
                'id' => 788,
                'groups_id' => 10,
                'pkey' => 'category_name',
                'content' => 'Tên danh mục',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            284 => 
            array (
                'id' => 789,
                'groups_id' => 10,
                'pkey' => 'permission',
                'content' => 'Phân quyền',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            285 => 
            array (
                'id' => 790,
                'groups_id' => 10,
                'pkey' => 'status',
                'content' => 'Trạng thái',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            286 => 
            array (
                'id' => 791,
                'groups_id' => 10,
                'pkey' => 'posts',
                'content' => 'Bài viết',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            287 => 
            array (
                'id' => 792,
                'groups_id' => 10,
                'pkey' => 'icon',
                'content' => 'Icon',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            288 => 
            array (
                'id' => 793,
                'groups_id' => 10,
                'pkey' => 'size',
                'content' => 'Kích thước',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            289 => 
            array (
                'id' => 794,
                'groups_id' => 10,
                'pkey' => 'choose_picture',
                'content' => 'Chọn hình ảnh',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            290 => 
            array (
                'id' => 795,
                'groups_id' => 10,
                'pkey' => 'enable',
                'content' => 'Bật',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            291 => 
            array (
                'id' => 796,
                'groups_id' => 10,
                'pkey' => 'disable',
                'content' => 'Tắt',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            292 => 
            array (
                'id' => 797,
                'groups_id' => 10,
                'pkey' => 'filter_word',
                'content' => 'Lọc từ',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            293 => 
            array (
                'id' => 798,
                'groups_id' => 10,
                'pkey' => 'word',
                'content' => 'Chữ',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            294 => 
            array (
                'id' => 799,
                'groups_id' => 10,
                'pkey' => 'add_new',
                'content' => 'Thêm mới',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            295 => 
            array (
                'id' => 800,
                'groups_id' => 10,
                'pkey' => 'edit',
                'content' => 'Chỉnh sửa',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            296 => 
            array (
                'id' => 801,
                'groups_id' => 10,
                'pkey' => 'permission',
                'content' => 'Phân quyền',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            297 => 
            array (
                'id' => 802,
                'groups_id' => 10,
                'pkey' => 'user_belong',
                'content' => 'Đối tượng thuộc',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            298 => 
            array (
                'id' => 803,
                'groups_id' => 10,
                'pkey' => 'unit',
                'content' => 'Đơn vị',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            299 => 
            array (
                'id' => 804,
                'groups_id' => 10,
                'pkey' => 'user',
                'content' => 'Nhân viên',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            300 => 
            array (
                'id' => 805,
                'groups_id' => 10,
                'pkey' => 'employee_code',
                'content' => 'Mã nhân viên',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            301 => 
            array (
                'id' => 806,
                'groups_id' => 10,
                'pkey' => 'employee_name',
                'content' => 'Tên nhân viên',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            302 => 
            array (
                'id' => 807,
                'groups_id' => 10,
                'pkey' => 'employee_email',
                'content' => 'Email nhân viên',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            303 => 
            array (
                'id' => 808,
                'groups_id' => 10,
                'pkey' => 'work_unit',
                'content' => 'Đơn vị công tác',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            304 => 
            array (
                'id' => 809,
                'groups_id' => 10,
                'pkey' => 'unit_manager',
                'content' => 'Đơn vị quản lý',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            305 => 
            array (
                'id' => 810,
                'groups_id' => 10,
                'pkey' => 'enter_name_forum',
                'content' => 'Nhập tên',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            306 => 
            array (
                'id' => 811,
                'groups_id' => 10,
                'pkey' => 'approve',
                'content' => 'Duyệt',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            307 => 
            array (
                'id' => 812,
                'groups_id' => 10,
                'pkey' => 'info',
                'content' => 'Thông tin',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            308 => 
            array (
                'id' => 813,
                'groups_id' => 10,
                'pkey' => 'enter_title_thread',
                'content' => 'Nhập tiêu đề bài viết',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            309 => 
            array (
                'id' => 814,
                'groups_id' => 10,
                'pkey' => 'hashtag',
                'content' => 'Hashtag',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            310 => 
            array (
                'id' => 815,
                'groups_id' => 10,
                'pkey' => 'enter_content_thread',
                'content' => 'Nhập nội dung bài viết',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            311 => 
            array (
                'id' => 817,
                'groups_id' => 10,
                'pkey' => 'content',
                'content' => 'Nội dung',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            312 => 
            array (
                'id' => 818,
                'groups_id' => 10,
                'pkey' => 'title_thread',
                'content' => 'Tiêu đề bài viết',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            313 => 
            array (
                'id' => 819,
                'groups_id' => 10,
                'pkey' => 'created_at',
                'content' => 'Ngày tạo',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            314 => 
            array (
                'id' => 820,
                'groups_id' => 10,
                'pkey' => 'enter_hashtag',
                'content' => 'Nhập hashtag',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            315 => 
            array (
                'id' => 822,
                'groups_id' => 10,
                'pkey' => 'delete',
                'content' => 'Xóa',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            316 => 
            array (
                'id' => 823,
                'groups_id' => 10,
                'pkey' => 'view',
                'content' => 'Lượt xem',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            317 => 
            array (
                'id' => 824,
                'groups_id' => 10,
                'pkey' => 'comment',
                'content' => 'Bình luận',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            318 => 
            array (
                'id' => 825,
                'groups_id' => 10,
                'pkey' => 'Views',
                'content' => 'Views',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            319 => 
            array (
                'id' => 826,
                'groups_id' => 10,
                'pkey' => 'Topics',
                'content' => 'Topics',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            320 => 
            array (
                'id' => 827,
                'groups_id' => 10,
                'pkey' => 'Comment',
                'content' => 'Comment',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            321 => 
            array (
                'id' => 828,
                'groups_id' => 10,
                'pkey' => 'view_all',
                'content' => 'Xem tất cả',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            322 => 
            array (
                'id' => 829,
                'groups_id' => 10,
                'pkey' => 'send_new_posts',
                'content' => 'Gửi bài mới',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            323 => 
            array (
                'id' => 830,
                'groups_id' => 10,
                'pkey' => 'send_comment',
                'content' => 'Gửi bình luận',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            324 => 
            array (
                'id' => 831,
                'groups_id' => 10,
                'pkey' => 'edit_post',
                'content' => 'Chỉnh bài viết',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            325 => 
            array (
                'id' => 832,
                'groups_id' => 11,
                'pkey' => 'area',
                'content' => 'Khu vực',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            326 => 
            array (
                'id' => 833,
                'groups_id' => 11,
                'pkey' => 'title',
                'content' => 'Chức danh',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            327 => 
            array (
                'id' => 834,
                'groups_id' => 11,
                'pkey' => 'status',
                'content' => 'Trạng thái',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            328 => 
            array (
                'id' => 835,
                'groups_id' => 11,
                'pkey' => 'inactivity',
                'content' => 'Nghỉ việc',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            329 => 
            array (
                'id' => 836,
                'groups_id' => 11,
                'pkey' => 'doing',
                'content' => 'Đang làm',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            330 => 
            array (
                'id' => 837,
                'groups_id' => 11,
                'pkey' => 'probationary',
                'content' => 'Thử việc',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            331 => 
            array (
                'id' => 838,
                'groups_id' => 11,
                'pkey' => 'pause',
                'content' => 'Tạm hoãn',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            332 => 
            array (
                'id' => 839,
                'groups_id' => 11,
                'pkey' => 'enter_suggest',
                'content' => 'Nhập góp ý',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            333 => 
            array (
                'id' => 840,
                'groups_id' => 11,
                'pkey' => 'start_date',
                'content' => 'Ngày bắt đầu',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            334 => 
            array (
                'id' => 841,
                'groups_id' => 11,
                'pkey' => 'end_date',
                'content' => 'Ngày kết thúc',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            335 => 
            array (
                'id' => 842,
                'groups_id' => 11,
                'pkey' => 'enter_code_name_user',
                'content' => 'Nhập mã/ tên nhân viên',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            336 => 
            array (
                'id' => 843,
                'groups_id' => 11,
                'pkey' => 'user',
                'content' => 'Nhân viên',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            337 => 
            array (
                'id' => 844,
                'groups_id' => 11,
                'pkey' => 'email',
                'content' => 'Email',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            338 => 
            array (
                'id' => 845,
                'groups_id' => 11,
                'pkey' => 'work_unit',
                'content' => 'Đơn vị công tác',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            339 => 
            array (
                'id' => 846,
                'groups_id' => 11,
                'pkey' => 'unit_manager',
                'content' => 'Đơn vị quản lý',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            340 => 
            array (
                'id' => 847,
                'groups_id' => 11,
                'pkey' => 'created_at',
                'content' => 'Ngày tạo',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            341 => 
            array (
                'id' => 848,
                'groups_id' => 11,
                'pkey' => 'info',
                'content' => 'Thông tin',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            342 => 
            array (
                'id' => 849,
                'groups_id' => 11,
                'pkey' => 'time',
                'content' => 'Thời gian',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            343 => 
            array (
                'id' => 850,
                'groups_id' => 11,
                'pkey' => 'comment_content',
                'content' => 'Nội dung bình luận',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            344 => 
            array (
                'id' => 851,
                'groups_id' => 11,
                'pkey' => 'comment',
                'content' => 'Bình luận',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            345 => 
            array (
                'id' => 852,
                'groups_id' => 11,
                'pkey' => 'suggestion',
                'content' => 'Góp ý',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            346 => 
            array (
                'id' => 853,
                'groups_id' => 11,
                'pkey' => 'date_created',
                'content' => 'Ngày tạo',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            347 => 
            array (
                'id' => 854,
                'groups_id' => 11,
                'pkey' => 'add_suggest',
                'content' => 'Thêm góp ý',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            348 => 
            array (
                'id' => 855,
                'groups_id' => 11,
                'pkey' => 'name_suggest',
                'content' => 'Tên góp ý',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            349 => 
            array (
                'id' => 856,
                'groups_id' => 11,
                'pkey' => 'content',
                'content' => 'Nội dung',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            350 => 
            array (
                'id' => 857,
                'groups_id' => 12,
                'pkey' => 'unit',
                'content' => 'Đơn vị',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            351 => 
            array (
                'id' => 858,
                'groups_id' => 12,
                'pkey' => 'title',
                'content' => 'Chức danh',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            352 => 
            array (
                'id' => 859,
                'groups_id' => 12,
                'pkey' => 'enter_code_name_user',
                'content' => 'Nhập mã/ tên nhân viên',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            353 => 
            array (
                'id' => 860,
                'groups_id' => 12,
                'pkey' => 'name',
                'content' => 'Tên',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            354 => 
            array (
                'id' => 861,
                'groups_id' => 12,
                'pkey' => 'created_at',
                'content' => 'Ngày tạo',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            355 => 
            array (
                'id' => 862,
                'groups_id' => 12,
                'pkey' => 'note',
                'content' => 'Ghi chú',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            356 => 
            array (
                'id' => 863,
                'groups_id' => 13,
                'pkey' => 'select_function',
                'content' => 'Chọn chức năng',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            357 => 
            array (
                'id' => 864,
                'groups_id' => 13,
                'pkey' => 'user',
                'content' => 'Nhân viên',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            358 => 
            array (
                'id' => 865,
                'groups_id' => 13,
                'pkey' => 'from_date',
                'content' => 'Từ ngày',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            359 => 
            array (
                'id' => 866,
                'groups_id' => 13,
                'pkey' => 'to_date',
                'content' => 'Đến ngày',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            360 => 
            array (
                'id' => 867,
                'groups_id' => 13,
                'pkey' => 'action',
                'content' => 'Hành động',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            361 => 
            array (
                'id' => 868,
                'groups_id' => 13,
                'pkey' => 'note',
                'content' => 'Ghi chú',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            362 => 
            array (
                'id' => 869,
                'groups_id' => 13,
                'pkey' => 'creator',
                'content' => 'Người tạo',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            363 => 
            array (
                'id' => 870,
                'groups_id' => 13,
                'pkey' => 'time',
                'content' => 'Thời gian',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            364 => 
            array (
                'id' => 871,
                'groups_id' => 13,
                'pkey' => 'unit',
                'content' => 'Đơn vị',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            365 => 
            array (
                'id' => 872,
                'groups_id' => 13,
                'pkey' => 'area',
                'content' => 'Khu vực',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            366 => 
            array (
                'id' => 873,
                'groups_id' => 13,
                'pkey' => 'user_code',
                'content' => 'Mã nhân viên',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            367 => 
            array (
                'id' => 874,
                'groups_id' => 13,
                'pkey' => 'fullname',
                'content' => 'Tên nhân viên',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            368 => 
            array (
                'id' => 875,
                'groups_id' => 13,
                'pkey' => 'student',
                'content' => 'Học viên',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            369 => 
            array (
                'id' => 876,
                'groups_id' => 13,
                'pkey' => 'access_number',
                'content' => 'Lần truy cập',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            370 => 
            array (
                'id' => 877,
                'groups_id' => 13,
                'pkey' => 'time_start',
                'content' => 'Thời gian bắt đầu',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            371 => 
            array (
                'id' => 878,
                'groups_id' => 13,
                'pkey' => 'last_access',
                'content' => 'Lần truy cập cuối',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            372 => 
            array (
                'id' => 879,
                'groups_id' => 13,
                'pkey' => 'course',
                'content' => 'Khóa học',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            373 => 
            array (
                'id' => 880,
                'groups_id' => 13,
                'pkey' => 'code',
                'content' => 'Mã',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            374 => 
            array (
                'id' => 881,
                'groups_id' => 13,
                'pkey' => 'course_type',
                'content' => 'Loại khóa',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            375 => 
            array (
                'id' => 882,
                'groups_id' => 14,
                'pkey' => 'question',
                'content' => 'Câu hỏi',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            376 => 
            array (
                'id' => 883,
                'groups_id' => 14,
                'pkey' => 'content',
                'content' => 'Nội dung',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            377 => 
            array (
                'id' => 884,
                'groups_id' => 14,
                'pkey' => 'search',
                'content' => 'Tìm kiếm',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            378 => 
            array (
                'id' => 885,
                'groups_id' => 15,
                'pkey' => 'enter_name',
                'content' => 'Nhập tên',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            379 => 
            array (
                'id' => 886,
                'groups_id' => 15,
                'pkey' => 'type',
                'content' => 'Thể loại',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            380 => 
            array (
                'id' => 887,
                'groups_id' => 15,
                'pkey' => 'file',
                'content' => 'File',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            381 => 
            array (
                'id' => 888,
                'groups_id' => 15,
                'pkey' => 'video',
                'content' => 'Video',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            382 => 
            array (
                'id' => 889,
                'groups_id' => 15,
                'pkey' => 'post',
                'content' => 'Bài viết',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            383 => 
            array (
                'id' => 890,
                'groups_id' => 15,
                'pkey' => 'name',
                'content' => 'Tên',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            384 => 
            array (
                'id' => 891,
                'groups_id' => 15,
                'pkey' => 'file_video_post_guide',
                'content' => 'File/ Video/ Bài viết hướng dẫn',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            385 => 
            array (
                'id' => 892,
                'groups_id' => 15,
                'pkey' => 'info',
                'content' => 'Thông tin',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            386 => 
            array (
                'id' => 893,
                'groups_id' => 15,
                'pkey' => 'guide_name',
                'content' => 'Tên hướng dẫn',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            387 => 
            array (
                'id' => 894,
                'groups_id' => 15,
                'pkey' => 'file_pdf',
                'content' => 'File PDF',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            388 => 
            array (
                'id' => 895,
                'groups_id' => 15,
                'pkey' => 'attachments',
                'content' => 'File đính kèm',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            389 => 
            array (
                'id' => 896,
                'groups_id' => 15,
                'pkey' => 'choose_file',
                'content' => 'Chọn file',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            390 => 
            array (
                'id' => 897,
                'groups_id' => 15,
                'pkey' => 'content',
                'content' => 'Nội dung',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            391 => 
            array (
                'id' => 898,
                'groups_id' => 15,
                'pkey' => 'guide',
                'content' => 'Hướng dẫn',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            392 => 
            array (
                'id' => 899,
                'groups_id' => 15,
                'pkey' => 'download',
                'content' => 'Tải về',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            393 => 
            array (
                'id' => 900,
                'groups_id' => 15,
                'pkey' => 'watch_online',
                'content' => 'Xem trực tuyến',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            394 => 
            array (
                'id' => 901,
                'groups_id' => 16,
                'pkey' => 'filter_month',
                'content' => 'Lọc theo tháng',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            395 => 
            array (
                'id' => 902,
                'groups_id' => 16,
                'pkey' => 'month_i',
                'content' => 'Tháng :i',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            396 => 
            array (
                'id' => 903,
                'groups_id' => 16,
                'pkey' => 'filter_year',
                'content' => 'Lọc theo năm',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            397 => 
            array (
                'id' => 904,
                'groups_id' => 16,
                'pkey' => 'year_i',
                'content' => 'Năm :i',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            398 => 
            array (
                'id' => 905,
                'groups_id' => 16,
                'pkey' => 'filter_unit',
                'content' => 'Lọc theo đơn vị',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            399 => 
            array (
                'id' => 906,
                'groups_id' => 16,
                'pkey' => 'filter_status',
                'content' => 'Lọc theo trạng thái',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            400 => 
            array (
                'id' => 907,
                'groups_id' => 16,
                'pkey' => 'pending',
                'content' => 'Chờ duyệt',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            401 => 
            array (
                'id' => 908,
                'groups_id' => 16,
                'pkey' => 'approved',
                'content' => 'Đã duyệt',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            402 => 
            array (
                'id' => 909,
                'groups_id' => 16,
                'pkey' => 'deny',
                'content' => 'Từ chối',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            403 => 
            array (
                'id' => 910,
                'groups_id' => 16,
                'pkey' => 'unit',
                'content' => 'Đơn vị',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            404 => 
            array (
                'id' => 911,
                'groups_id' => 16,
                'pkey' => 'training_content',
                'content' => 'Nội dung đào tạo',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            405 => 
            array (
                'id' => 912,
                'groups_id' => 16,
                'pkey' => 'quantity',
                'content' => 'Số lượng',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            406 => 
            array (
                'id' => 913,
                'groups_id' => 16,
                'pkey' => 'form',
                'content' => 'Hình thức',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            407 => 
            array (
                'id' => 914,
                'groups_id' => 16,
                'pkey' => 'type',
                'content' => 'Loại',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            408 => 
            array (
                'id' => 915,
                'groups_id' => 16,
                'pkey' => 'time',
                'content' => 'Thời gian',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            409 => 
            array (
                'id' => 916,
                'groups_id' => 16,
                'pkey' => 'attach_file',
                'content' => 'File đính kèm',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            410 => 
            array (
                'id' => 917,
                'groups_id' => 16,
                'pkey' => 'report_file',
                'content' => 'File báo cáo',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            411 => 
            array (
                'id' => 918,
                'groups_id' => 16,
                'pkey' => 'status',
                'content' => 'Trạng thái',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            412 => 
            array (
                'id' => 919,
                'groups_id' => 16,
                'pkey' => 'info',
                'content' => 'Thông tin',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            413 => 
            array (
                'id' => 920,
                'groups_id' => 16,
                'pkey' => 'report',
                'content' => 'Báo cáo',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            414 => 
            array (
                'id' => 921,
                'groups_id' => 16,
                'pkey' => 'start_date',
                'content' => 'Ngày bắt đầu',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            415 => 
            array (
                'id' => 922,
                'groups_id' => 16,
                'pkey' => 'end_date',
                'content' => 'Ngày kết thúc',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            416 => 
            array (
                'id' => 923,
                'groups_id' => 16,
                'pkey' => 'choose_subject',
                'content' => 'Chọn chuyên đề',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            417 => 
            array (
                'id' => 924,
                'groups_id' => 16,
                'pkey' => 'document',
                'content' => 'Tài liệu',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            418 => 
            array (
                'id' => 925,
                'groups_id' => 16,
                'pkey' => 'enter_text',
                'content' => 'Nhập text',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            419 => 
            array (
                'id' => 926,
                'groups_id' => 16,
                'pkey' => 'object',
                'content' => 'Đối tượng',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            420 => 
            array (
                'id' => 927,
                'groups_id' => 16,
                'pkey' => 'choose_title',
                'content' => 'Chọn chức danh',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            421 => 
            array (
                'id' => 928,
                'groups_id' => 16,
                'pkey' => 'number_student',
                'content' => 'Số lượng học viên',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            422 => 
            array (
                'id' => 929,
                'groups_id' => 16,
                'pkey' => 'online',
                'content' => 'Online',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            423 => 
            array (
                'id' => 930,
                'groups_id' => 16,
                'pkey' => 'offline',
                'content' => 'Tập trung',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            424 => 
            array (
                'id' => 931,
                'groups_id' => 16,
                'pkey' => 'address',
                'content' => 'Địa điểm',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            425 => 
            array (
                'id' => 932,
                'groups_id' => 16,
                'pkey' => 'cost',
                'content' => 'Chi phí',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            426 => 
            array (
                'id' => 933,
                'groups_id' => 16,
                'pkey' => 'timer',
                'content' => 'Thời lượng',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            427 => 
            array (
                'id' => 934,
                'groups_id' => 16,
                'pkey' => 'enter_number_session',
                'content' => 'Nhập số buổi',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            428 => 
            array (
                'id' => 935,
                'groups_id' => 16,
                'pkey' => 'teacher',
                'content' => 'Giảng viên',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            429 => 
            array (
                'id' => 936,
                'groups_id' => 16,
                'pkey' => 'training_objectives',
                'content' => 'Mục tiêu đào tạo',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            430 => 
            array (
                'id' => 937,
                'groups_id' => 16,
                'pkey' => 'topical_content',
                'content' => 'Nội dung chuyên đề',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            431 => 
            array (
                'id' => 938,
                'groups_id' => 16,
                'pkey' => 'choose_file',
                'content' => 'Chọn tệp tin',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            432 => 
            array (
                'id' => 939,
                'groups_id' => 16,
                'pkey' => 'student_list',
                'content' => 'Danh sách học viên',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            433 => 
            array (
                'id' => 940,
                'groups_id' => 16,
                'pkey' => 'choose_student',
                'content' => 'Chọn học viên',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            434 => 
            array (
                'id' => 941,
                'groups_id' => 16,
                'pkey' => 'note',
                'content' => 'Ghi chú',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            435 => 
            array (
                'id' => 942,
                'groups_id' => 16,
                'pkey' => 'add_new',
                'content' => 'Thêm mới',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            436 => 
            array (
                'id' => 943,
                'groups_id' => 16,
                'pkey' => 'update',
                'content' => 'Cập nhật',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            437 => 
            array (
                'id' => 944,
                'groups_id' => 17,
                'pkey' => 'name',
                'content' => 'Tên',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            438 => 
            array (
                'id' => 945,
                'groups_id' => 17,
                'pkey' => 'status',
                'content' => 'Trạng thái',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            439 => 
            array (
                'id' => 946,
                'groups_id' => 17,
                'pkey' => 'sync',
                'content' => 'Chạy đồng bộ',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            440 => 
            array (
                'id' => 947,
                'groups_id' => 17,
                'pkey' => 'period',
                'content' => 'Khoảng thời gian',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            441 => 
            array (
                'id' => 948,
                'groups_id' => 17,
                'pkey' => 'date_updated',
                'content' => 'Ngày cập nhật',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            442 => 
            array (
                'id' => 949,
                'groups_id' => 17,
                'pkey' => 'success',
                'content' => 'Thành công',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            443 => 
            array (
                'id' => 950,
                'groups_id' => 17,
                'pkey' => 'fail',
                'content' => 'Thất bại',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            444 => 
            array (
                'id' => 951,
                'groups_id' => 18,
                'pkey' => 'enter_code_name_course',
                'content' => 'Nhập mã/ tên khóa học',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            445 => 
            array (
                'id' => 952,
                'groups_id' => 18,
                'pkey' => 'training_program',
                'content' => 'Chủ đề',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            446 => 
            array (
                'id' => 953,
                'groups_id' => 18,
                'pkey' => 'type_subject',
                'content' => 'Mảng nghiệp vụ',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            447 => 
            array (
                'id' => 954,
                'groups_id' => 18,
                'pkey' => 'subject',
                'content' => 'Khóa học',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            448 => 
            array (
                'id' => 955,
                'groups_id' => 18,
                'pkey' => 'start_date',
                'content' => 'Ngày bắt đầu',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            449 => 
            array (
                'id' => 956,
                'groups_id' => 18,
                'pkey' => 'end_date',
                'content' => 'Ngày kết thúc',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            450 => 
            array (
                'id' => 957,
                'groups_id' => 18,
                'pkey' => 'open_off',
                'content' => 'Bật/Tắt',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            451 => 
            array (
                'id' => 958,
                'groups_id' => 18,
                'pkey' => 'course',
                'content' => 'Khóa học',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            452 => 
            array (
                'id' => 959,
                'groups_id' => 18,
                'pkey' => 'plan',
                'content' => 'Kế hoạch',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            453 => 
            array (
                'id' => 960,
                'groups_id' => 18,
                'pkey' => 'register_deadline',
                'content' => 'Hạn đăng ký',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            454 => 
            array (
                'id' => 961,
                'groups_id' => 18,
                'pkey' => 'time',
                'content' => 'Thời gian',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            455 => 
            array (
                'id' => 962,
                'groups_id' => 18,
                'pkey' => 'created_at',
                'content' => 'Ngày tạo',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            456 => 
            array (
                'id' => 963,
                'groups_id' => 18,
                'pkey' => 'approve',
                'content' => 'Duyệt',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            457 => 
            array (
                'id' => 964,
                'groups_id' => 18,
                'pkey' => 'status',
                'content' => 'Trạng thái',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            458 => 
            array (
                'id' => 965,
                'groups_id' => 18,
                'pkey' => 'lock',
                'content' => 'Khóa',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            459 => 
            array (
                'id' => 966,
                'groups_id' => 18,
                'pkey' => 'register',
                'content' => 'Ghi danh',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            460 => 
            array (
                'id' => 967,
                'groups_id' => 18,
                'pkey' => 'creator',
                'content' => 'Người tạo',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            461 => 
            array (
                'id' => 968,
                'groups_id' => 18,
                'pkey' => 'editor',
                'content' => 'Người sửa',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            462 => 
            array (
                'id' => 969,
                'groups_id' => 18,
                'pkey' => 'internal_registration',
                'content' => 'Ghi danh nội bộ',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            463 => 
            array (
                'id' => 970,
                'groups_id' => 18,
                'pkey' => 'external_enrollment',
                'content' => 'Ghi danh bên ngoài',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            464 => 
            array (
                'id' => 971,
                'groups_id' => 18,
                'pkey' => 'training_result',
                'content' => 'Kết quả đào tạo',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            465 => 
            array (
                'id' => 972,
                'groups_id' => 18,
                'pkey' => 'quiz_list',
                'content' => 'Kỳ thi',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            466 => 
            array (
                'id' => 973,
                'groups_id' => 18,
                'pkey' => 'rating_level_result',
                'content' => 'Kết quả đánh giá',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            467 => 
            array (
                'id' => 974,
                'groups_id' => 18,
                'pkey' => 'info',
                'content' => 'Thông tin',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            468 => 
            array (
                'id' => 975,
                'groups_id' => 18,
                'pkey' => 'object_join',
                'content' => 'Đối tượng tham gia',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            469 => 
            array (
                'id' => 976,
                'groups_id' => 18,
                'pkey' => 'study_guide',
                'content' => 'Hướng dẫn học',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            470 => 
            array (
                'id' => 977,
                'groups_id' => 18,
                'pkey' => 'training_cost',
                'content' => 'Chi phí đào tạo',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            471 => 
            array (
                'id' => 978,
                'groups_id' => 18,
                'pkey' => 'activity_lesson',
                'content' => 'Các hoạt động / Bài học',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            472 => 
            array (
                'id' => 979,
                'groups_id' => 18,
                'pkey' => 'image_activity',
                'content' => 'Ảnh đại diện hoạt động',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            473 => 
            array (
                'id' => 980,
                'groups_id' => 18,
                'pkey' => 'conditions',
                'content' => 'Điều kiện hoàn thành',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            474 => 
            array (
                'id' => 981,
                'groups_id' => 18,
                'pkey' => 'history',
                'content' => 'Lịch sử',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            475 => 
            array (
                'id' => 982,
                'groups_id' => 18,
                'pkey' => 'library_file',
                'content' => 'Quản lý / Thư viện file',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            476 => 
            array (
                'id' => 983,
                'groups_id' => 18,
                'pkey' => 'note_evaluate',
                'content' => 'Học viên ghi chép / Đánh giá',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            477 => 
            array (
                'id' => 984,
                'groups_id' => 18,
                'pkey' => 'ask_answer',
                'content' => 'Hỏi / Đáp',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            478 => 
            array (
                'id' => 985,
                'groups_id' => 18,
                'pkey' => 'reward_points',
                'content' => 'Điểm thưởng',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            479 => 
            array (
                'id' => 986,
                'groups_id' => 18,
                'pkey' => 'setting_percent',
                'content' => 'Thiết lập trọng số',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            480 => 
            array (
                'id' => 987,
                'groups_id' => 18,
                'pkey' => 'rating_level',
                'content' => 'Đánh giá 4 cấp độ',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            481 => 
            array (
                'id' => 988,
                'groups_id' => 18,
                'pkey' => 'deny',
                'content' => 'Từ chối',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            482 => 
            array (
                'id' => 989,
                'groups_id' => 18,
                'pkey' => 'not_approved',
                'content' => 'Chưa duyệt',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            483 => 
            array (
                'id' => 990,
                'groups_id' => 18,
                'pkey' => 'course_code',
                'content' => 'Mã khóa học',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            484 => 
            array (
                'id' => 991,
                'groups_id' => 18,
                'pkey' => 'course_name',
                'content' => 'Tên khóa học',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            485 => 
            array (
                'id' => 992,
                'groups_id' => 18,
                'pkey' => 'limit_time',
                'content' => 'Giới hạn thời gian học',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            486 => 
            array (
                'id' => 993,
                'groups_id' => 18,
                'pkey' => 'time_limit',
                'content' => 'Thời gian giới hạn',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            487 => 
            array (
                'id' => 994,
                'groups_id' => 18,
                'pkey' => 'training_type',
                'content' => 'Loại hình đào tạo',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            488 => 
            array (
                'id' => 995,
                'groups_id' => 18,
                'pkey' => 'max_grades',
                'content' => 'Điểm tối đa',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            489 => 
            array (
                'id' => 996,
                'groups_id' => 18,
                'pkey' => 'min_grades',
                'content' => 'Điểm cần đạt',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            490 => 
            array (
                'id' => 997,
                'groups_id' => 18,
                'pkey' => 'title_join',
            'content' => 'Chức danh tham gia(bắt buộc)',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            491 => 
            array (
                'id' => 998,
                'groups_id' => 18,
                'pkey' => 'select_all',
                'content' => 'Chọn tất cả',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            492 => 
            array (
                'id' => 999,
                'groups_id' => 18,
                'pkey' => 'title_recommend',
                'content' => 'Chức danh khuyến khích',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            493 => 
            array (
                'id' => 1000,
                'groups_id' => 18,
                'pkey' => 'course_action',
                'content' => 'Khóa học thực hiện',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            494 => 
            array (
                'id' => 1001,
                'groups_id' => 18,
                'pkey' => 'choose',
                'content' => 'Chọn',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            495 => 
            array (
                'id' => 1002,
                'groups_id' => 18,
                'pkey' => 'incurred',
                'content' => 'Phát sinh',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            496 => 
            array (
                'id' => 1003,
                'groups_id' => 18,
                'pkey' => 'training_plan',
                'content' => 'Kế hoạch đào tạo năm',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            497 => 
            array (
                'id' => 1004,
                'groups_id' => 18,
                'pkey' => 'document',
                'content' => 'Tài liệu',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            498 => 
            array (
                'id' => 1005,
                'groups_id' => 18,
                'pkey' => 'choose_document',
                'content' => 'Chọn tài liệu',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            499 => 
            array (
                'id' => 1006,
                'groups_id' => 18,
                'pkey' => 'brief',
                'content' => 'Tóm tắt',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
        ));
        \DB::table('el_languages')->insert(array (
            0 => 
            array (
                'id' => 1007,
                'groups_id' => 18,
                'pkey' => 'description',
                'content' => 'Mô tả',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            1 => 
            array (
                'id' => 1008,
                'groups_id' => 18,
                'pkey' => 'aprove_course',
                'content' => 'Duyệt khóa',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            2 => 
            array (
                'id' => 1009,
                'groups_id' => 18,
                'pkey' => 'obligatory',
                'content' => 'Bắt buộc',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            3 => 
            array (
                'id' => 1010,
                'groups_id' => 18,
                'pkey' => 'auto',
                'content' => 'Tự động',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            4 => 
            array (
                'id' => 1011,
                'groups_id' => 18,
                'pkey' => 'picture',
                'content' => 'Hình ảnh',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            5 => 
            array (
                'id' => 1012,
                'groups_id' => 18,
                'pkey' => 'choose_picture',
                'content' => 'Chọn hình ảnh',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            6 => 
            array (
                'id' => 1013,
                'groups_id' => 18,
                'pkey' => 'date',
                'content' => 'Ngày',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            7 => 
            array (
                'id' => 1014,
                'groups_id' => 18,
                'pkey' => 'session',
                'content' => 'Buổi',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            8 => 
            array (
                'id' => 1015,
                'groups_id' => 18,
                'pkey' => 'hour',
                'content' => 'Giờ',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            9 => 
            array (
                'id' => 1016,
                'groups_id' => 18,
                'pkey' => 'number_lesson',
            'content' => 'Bài học (Số bài)',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            10 => 
            array (
                'id' => 1017,
                'groups_id' => 18,
                'pkey' => 'certificate',
                'content' => 'Mẫu chứng chỉ',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            11 => 
            array (
                'id' => 1018,
                'groups_id' => 18,
                'pkey' => 'enable',
                'content' => 'Bật',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            12 => 
            array (
                'id' => 1019,
                'groups_id' => 18,
                'pkey' => 'unit',
                'content' => 'Đơn vị',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            13 => 
            array (
                'id' => 1020,
                'groups_id' => 18,
                'pkey' => 'choose_unit',
                'content' => 'Chọn đơn vị',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            14 => 
            array (
                'id' => 1021,
                'groups_id' => 18,
                'pkey' => 'survey_code_QR',
                'content' => 'Mã khảo sát Qrcode',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            15 => 
            array (
                'id' => 1022,
                'groups_id' => 18,
                'pkey' => 'scan_code',
                'content' => 'Quét mã',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            16 => 
            array (
                'id' => 1023,
                'groups_id' => 18,
                'pkey' => 'print_qr_code',
                'content' => 'In mã QR',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            17 => 
            array (
                'id' => 1024,
                'groups_id' => 18,
                'pkey' => 'choose_title',
                'content' => 'Chọn chức danh',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            18 => 
            array (
                'id' => 1025,
                'groups_id' => 18,
                'pkey' => 'title',
                'content' => 'Chức danh',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            19 => 
            array (
                'id' => 1026,
                'groups_id' => 18,
                'pkey' => 'type_object',
                'content' => 'Loại đối tượng',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            20 => 
            array (
                'id' => 1027,
                'groups_id' => 18,
                'pkey' => 'post',
                'content' => 'Bài viết',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            21 => 
            array (
                'id' => 1028,
                'groups_id' => 18,
                'pkey' => 'file',
                'content' => 'Tệp tin',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            22 => 
            array (
                'id' => 1029,
                'groups_id' => 18,
                'pkey' => 'cost',
                'content' => 'Chi phí',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            23 => 
            array (
                'id' => 1030,
                'groups_id' => 18,
                'pkey' => 'type_cost',
                'content' => 'Loại chi phí',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            24 => 
            array (
                'id' => 1031,
                'groups_id' => 18,
                'pkey' => 'provisional_amount',
                'content' => 'Số tiền tạm tính',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            25 => 
            array (
                'id' => 1032,
                'groups_id' => 18,
                'pkey' => 'amount_paid',
                'content' => 'Số tiền thực chi',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            26 => 
            array (
                'id' => 1033,
                'groups_id' => 18,
                'pkey' => 'note',
                'content' => 'Ghi chú',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            27 => 
            array (
                'id' => 1034,
                'groups_id' => 18,
                'pkey' => 'total',
                'content' => 'Tổng',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            28 => 
            array (
                'id' => 1035,
                'groups_id' => 18,
                'pkey' => 'organizational_costs',
                'content' => 'Chi phí tổ chức',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            29 => 
            array (
                'id' => 1036,
                'groups_id' => 18,
                'pkey' => 'cost_training_room',
                'content' => 'Chi phí phòng đào tạo',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            30 => 
            array (
                'id' => 1037,
                'groups_id' => 18,
                'pkey' => 'cost_external_training',
                'content' => 'Chi phí đào tạo bên ngoài',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            31 => 
            array (
                'id' => 1038,
                'groups_id' => 18,
                'pkey' => 'lecturer_fees',
                'content' => 'Chi phí giảng viên',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            32 => 
            array (
                'id' => 1039,
                'groups_id' => 18,
                'pkey' => 'lesson_name',
                'content' => 'Tên bài học',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            33 => 
            array (
                'id' => 1040,
                'groups_id' => 18,
                'pkey' => 'activiti_scorm',
                'content' => 'Hoạt động: Scorm',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            34 => 
            array (
                'id' => 1041,
                'groups_id' => 18,
                'pkey' => 'general_setting',
                'content' => 'Thiết lập chung',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            35 => 
            array (
                'id' => 1042,
                'groups_id' => 18,
                'pkey' => 'belonging_lesson',
                'content' => 'Thuộc bài học',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            36 => 
            array (
                'id' => 1043,
                'groups_id' => 18,
                'pkey' => 'choose_lesson',
                'content' => 'Chọn bài học',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            37 => 
            array (
                'id' => 1044,
                'groups_id' => 18,
                'pkey' => 'activiti_name',
                'content' => 'Tên hoạt động',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            38 => 
            array (
                'id' => 1045,
                'groups_id' => 18,
                'pkey' => 'choose_file',
                'content' => 'Chọn tệp tin',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            39 => 
            array (
                'id' => 1046,
                'groups_id' => 18,
                'pkey' => 'attemps',
                'content' => 'Lần thử',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            40 => 
            array (
                'id' => 1047,
                'groups_id' => 18,
                'pkey' => 'number_of_attempts',
                'content' => 'Số lần thử',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            41 => 
            array (
                'id' => 1048,
                'groups_id' => 18,
                'pkey' => 'unlimited',
                'content' => 'Không giới hạn',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            42 => 
            array (
                'id' => 1049,
                'groups_id' => 18,
                'pkey' => 'times',
                'content' => ':i lần',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            43 => 
            array (
                'id' => 1050,
                'groups_id' => 18,
                'pkey' => 'force_new_attempt',
                'content' => 'Buộc lần thử mới',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            44 => 
            array (
                'id' => 1051,
                'groups_id' => 18,
                'pkey' => 'no',
                'content' => 'Không',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            45 => 
            array (
                'id' => 1052,
                'groups_id' => 18,
                'pkey' => 'when_attempt_completed_passed_failed',
                'content' => 'Khi lần thử trước hoàn thành, đạt hoặc không thành công',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            46 => 
            array (
                'id' => 1053,
                'groups_id' => 18,
                'pkey' => 'always',
                'content' => 'Luôn luôn',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            47 => 
            array (
                'id' => 1054,
                'groups_id' => 18,
                'pkey' => 'score',
                'content' => 'Điểm',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            48 => 
            array (
                'id' => 1055,
                'groups_id' => 18,
                'pkey' => 'scoring_method',
                'content' => 'Cách tính điểm',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            49 => 
            array (
                'id' => 1056,
                'groups_id' => 18,
                'pkey' => 'highest',
                'content' => 'Cao nhất',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            50 => 
            array (
                'id' => 1057,
                'groups_id' => 18,
                'pkey' => 'medium',
                'content' => 'Trung bình',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            51 => 
            array (
                'id' => 1058,
                'groups_id' => 18,
                'pkey' => 'first',
                'content' => 'Đầu tiên',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            52 => 
            array (
                'id' => 1059,
                'groups_id' => 18,
                'pkey' => 'end',
                'content' => 'Cuối',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            53 => 
            array (
                'id' => 1060,
                'groups_id' => 18,
                'pkey' => 'maximum_score',
                'content' => 'Điểm cao nhất',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            54 => 
            array (
                'id' => 1061,
                'groups_id' => 18,
                'pkey' => 'completion_conditions',
            'content' => 'Điều kiện hoàn thành (Thiết lập khi gói scorm có bài kiểm tra)',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            55 => 
            array (
                'id' => 1062,
                'groups_id' => 18,
                'pkey' => 'request_to_receive',
                'content' => 'Yêu cầu nhận điểm để hoàn thành',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            56 => 
            array (
                'id' => 1063,
                'groups_id' => 18,
                'pkey' => 'completed_dark_spot',
                'content' => 'Điểm tối thiểu để hoàn thành',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            57 => 
            array (
                'id' => 1064,
                'groups_id' => 18,
                'pkey' => 'passed',
                'content' => 'Đạt',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            58 => 
            array (
                'id' => 1065,
                'groups_id' => 18,
                'pkey' => 'completed',
                'content' => 'Hoàn thành',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            59 => 
            array (
                'id' => 1066,
                'groups_id' => 18,
                'pkey' => 'access_conditions',
                'content' => 'Điều kiện truy cập',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            60 => 
            array (
                'id' => 1067,
                'groups_id' => 18,
                'pkey' => 'activity_completed_first',
                'content' => 'Hoạt động cần hoàn thành trước',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            61 => 
            array (
                'id' => 1068,
                'groups_id' => 18,
                'pkey' => 'choose_activity',
                'content' => 'Chọn hoạt động',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            62 => 
            array (
                'id' => 1069,
                'groups_id' => 18,
                'pkey' => 'limit_time_act',
                'content' => 'Giới hạn thời gian',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            63 => 
            array (
                'id' => 1070,
                'groups_id' => 18,
                'pkey' => 'score_activity',
                'content' => 'Hoạt động tính điểm',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            64 => 
            array (
                'id' => 1071,
                'groups_id' => 18,
                'pkey' => 'greater_than_equal_to',
                'content' => 'Lớn hơn bằng',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            65 => 
            array (
                'id' => 1072,
                'groups_id' => 18,
                'pkey' => 'smaller',
                'content' => 'Nhỏ hơn',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            66 => 
            array (
                'id' => 1073,
                'groups_id' => 18,
                'pkey' => 'activiti_quiz',
                'content' => 'Hoạt động: Thi',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            67 => 
            array (
                'id' => 1074,
                'groups_id' => 18,
                'pkey' => 'quiz',
                'content' => 'Kỳ thi',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            68 => 
            array (
                'id' => 1075,
                'groups_id' => 18,
                'pkey' => 'choose_quiz',
                'content' => 'Chọn kỳ thi',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            69 => 
            array (
                'id' => 1076,
                'groups_id' => 18,
                'pkey' => 'add_new_quiz',
                'content' => 'Thêm kỳ thi',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            70 => 
            array (
                'id' => 1077,
                'groups_id' => 18,
                'pkey' => 'activiti_file_document',
                'content' => 'Hoạt động: Tệp tin / Tài liệu',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            71 => 
            array (
                'id' => 1078,
                'groups_id' => 18,
                'pkey' => 'activiti_url',
                'content' => 'Hoạt động: URL',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            72 => 
            array (
                'id' => 1079,
                'groups_id' => 18,
                'pkey' => 'link',
                'content' => 'Liên kết',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            73 => 
            array (
                'id' => 1080,
                'groups_id' => 18,
                'pkey' => 'activiti_video',
                'content' => 'Hoạt động: Video',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            74 => 
            array (
                'id' => 1081,
                'groups_id' => 18,
                'pkey' => 'setting',
                'content' => 'Cài đặt',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            75 => 
            array (
                'id' => 1082,
                'groups_id' => 18,
                'pkey' => 'delete',
                'content' => 'Xóa',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            76 => 
            array (
                'id' => 1083,
                'groups_id' => 18,
                'pkey' => 'hide',
                'content' => 'Ẩn',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            77 => 
            array (
                'id' => 1084,
                'groups_id' => 18,
                'pkey' => 'show',
                'content' => 'Hiện',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            78 => 
            array (
                'id' => 1085,
                'groups_id' => 18,
                'pkey' => 'go_course',
                'content' => 'Vào học',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            79 => 
            array (
                'id' => 1086,
                'groups_id' => 18,
                'pkey' => 'complete_evaluation',
                'content' => 'Hoàn thành đánh giá',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            80 => 
            array (
                'id' => 1087,
                'groups_id' => 18,
                'pkey' => 'complete_in_order',
                'content' => 'Hoàn thành hoạt động theo thứ tự',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            81 => 
            array (
                'id' => 1088,
                'groups_id' => 18,
                'pkey' => 'choose_grade_methor',
                'content' => 'Chọn cách tính điểm',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            82 => 
            array (
                'id' => 1089,
                'groups_id' => 18,
                'pkey' => 'highest_times',
                'content' => 'Lần cao nhất',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            83 => 
            array (
                'id' => 1090,
                'groups_id' => 18,
                'pkey' => 'medium_score',
                'content' => 'Trung bình',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            84 => 
            array (
                'id' => 1091,
                'groups_id' => 18,
                'pkey' => 'last_exam',
                'content' => 'Lần thi cuối',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            85 => 
            array (
                'id' => 1092,
                'groups_id' => 18,
                'pkey' => 'complete_act',
                'content' => 'Hoàn thành hoạt động',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            86 => 
            array (
                'id' => 1093,
                'groups_id' => 18,
                'pkey' => 'hided',
                'content' => 'Đã ẩn',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            87 => 
            array (
                'id' => 1094,
                'groups_id' => 18,
                'pkey' => 'fullname',
                'content' => 'Họ và tên',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            88 => 
            array (
                'id' => 1095,
                'groups_id' => 18,
                'pkey' => 'tab_edit',
                'content' => 'Tab chỉnh sửa',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            89 => 
            array (
                'id' => 1096,
                'groups_id' => 18,
                'pkey' => 'ip_address',
                'content' => 'Địa chỉ ip',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            90 => 
            array (
                'id' => 1097,
                'groups_id' => 18,
                'pkey' => 'enter_category',
                'content' => 'Nhập danh mục',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            91 => 
            array (
                'id' => 1098,
                'groups_id' => 18,
                'pkey' => 'file_list',
                'content' => 'Danh sách file',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            92 => 
            array (
                'id' => 1099,
                'groups_id' => 18,
                'pkey' => 'error_save_file',
                'content' => 'Xin lỗi! Có lỗi xảy ra khi lưu vào thư viên file.',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            93 => 
            array (
                'id' => 1100,
                'groups_id' => 18,
                'pkey' => 'enter_name',
                'content' => 'Nhập tên',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            94 => 
            array (
                'id' => 1101,
                'groups_id' => 18,
                'pkey' => 'work_unit',
                'content' => 'Đơn vị công tác',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            95 => 
            array (
                'id' => 1102,
                'groups_id' => 18,
                'pkey' => 'unit_manager',
                'content' => 'Đơn vị quản lý',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            96 => 
            array (
                'id' => 1103,
                'groups_id' => 18,
                'pkey' => 'take_notes',
                'content' => 'Ghi chép',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            97 => 
            array (
                'id' => 1104,
                'groups_id' => 18,
                'pkey' => 'evaluate',
                'content' => 'Đánh giá',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            98 => 
            array (
                'id' => 1105,
                'groups_id' => 18,
                'pkey' => 'question',
                'content' => 'Câu hỏi',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            99 => 
            array (
                'id' => 1106,
                'groups_id' => 18,
                'pkey' => 'answer',
                'content' => 'Câu trả lời',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            100 => 
            array (
                'id' => 1107,
                'groups_id' => 18,
                'pkey' => 'complete_course',
                'content' => 'Hoàn thành khóa học',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            101 => 
            array (
                'id' => 1108,
                'groups_id' => 18,
                'pkey' => 'get_point_completion_course',
                'content' => 'Nhận điểm khi hoàn thành khóa học',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            102 => 
            array (
                'id' => 1109,
                'groups_id' => 18,
                'pkey' => 'add_criteria',
                'content' => 'Thêm tiêu chí',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            103 => 
            array (
                'id' => 1110,
                'groups_id' => 18,
                'pkey' => 'time_complete',
                'content' => 'Thời gian hoàn thành',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            104 => 
            array (
                'id' => 1111,
                'groups_id' => 18,
                'pkey' => 'date_update',
                'content' => 'Ngày cập nhật',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            105 => 
            array (
                'id' => 1112,
                'groups_id' => 18,
                'pkey' => 'manipulation',
                'content' => 'Thao tác',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            106 => 
            array (
                'id' => 1113,
                'groups_id' => 18,
                'pkey' => 'get_point_completion_activity',
                'content' => 'Nhận điểm khi hoàn thành hoạt động',
                'content_en' => 'Nhận điểm khi hoàn thành hoạt động',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            107 => 
            array (
                'id' => 1114,
                'groups_id' => 18,
                'pkey' => 'activity',
                'content' => 'Hoạt động',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            108 => 
            array (
                'id' => 1115,
                'groups_id' => 18,
                'pkey' => 'codition',
                'content' => 'Điều kiện',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            109 => 
            array (
                'id' => 1116,
                'groups_id' => 18,
                'pkey' => 'get_point_content_other',
                'content' => 'Nhận điểm các nội dung khác',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            110 => 
            array (
                'id' => 1117,
                'groups_id' => 18,
                'pkey' => 'content',
                'content' => 'Nội dung',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            111 => 
            array (
                'id' => 1118,
                'groups_id' => 18,
                'pkey' => 'disable',
                'content' => 'Tắt',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            112 => 
            array (
                'id' => 1119,
                'groups_id' => 18,
                'pkey' => 'enter_score_after_dot',
            'content' => 'Nhập tối đa 2 số lẻ sau dấu chấm (VD: 1.00)',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            113 => 
            array (
                'id' => 1120,
                'groups_id' => 18,
                'pkey' => 'score_complete_course',
                'content' => 'Điểm khi hoàn thành khóa học',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            114 => 
            array (
                'id' => 1121,
                'groups_id' => 18,
                'pkey' => 'from_date',
                'content' => 'Từ ngày',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            115 => 
            array (
                'id' => 1122,
                'groups_id' => 18,
                'pkey' => 'minute',
                'content' => 'Phút',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            116 => 
            array (
                'id' => 1123,
                'groups_id' => 18,
                'pkey' => 'to_date',
                'content' => 'Đến ngày',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            117 => 
            array (
                'id' => 1124,
                'groups_id' => 18,
                'pkey' => 'score_complete_activity',
                'content' => 'Điểm khi hoàn thành hoạt động',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            118 => 
            array (
                'id' => 1125,
                'groups_id' => 18,
                'pkey' => 'start_date_error_user_point',
                'content' => 'Từ ngày phải nằm trong khoảng thời gian khóa học',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            119 => 
            array (
                'id' => 1126,
                'groups_id' => 18,
                'pkey' => 'end_date_error_user_point',
                'content' => 'Đến ngày phải nằm trong khoảng thời gian khóa học',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            120 => 
            array (
                'id' => 1127,
                'groups_id' => 18,
                'pkey' => 'score_conditions',
                'content' => 'Điều kiện tính điểm',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            121 => 
            array (
                'id' => 1128,
                'groups_id' => 18,
                'pkey' => 'scoring_over_time',
                'content' => 'Thang điểm theo thời gian',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            122 => 
            array (
                'id' => 1129,
                'groups_id' => 18,
                'pkey' => 'score_scale_number_times',
                'content' => 'Thang điểm theo số lần làm',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            123 => 
            array (
                'id' => 1130,
                'groups_id' => 18,
                'pkey' => 'score_scale_number_point_achieved',
                'content' => 'Thang điểm theo số điểm đạt được',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            124 => 
            array (
                'id' => 1131,
                'groups_id' => 18,
                'pkey' => 'from_score',
                'content' => 'Từ điểm',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            125 => 
            array (
                'id' => 1132,
                'groups_id' => 18,
                'pkey' => 'to_score',
                'content' => 'Đến điểm',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            126 => 
            array (
                'id' => 1133,
                'groups_id' => 18,
                'pkey' => 'from_times',
                'content' => 'Từ lần',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            127 => 
            array (
                'id' => 1134,
                'groups_id' => 18,
                'pkey' => 'to_times',
                'content' => 'Đến lần',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            128 => 
            array (
                'id' => 1135,
                'groups_id' => 18,
                'pkey' => 'weight_percent',
            'content' => 'Trọng số (%)',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            129 => 
            array (
                'id' => 1136,
                'groups_id' => 18,
                'pkey' => 'level_rating',
                'content' => 'Cấp độ đánh giá',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            130 => 
            array (
                'id' => 1137,
                'groups_id' => 18,
                'pkey' => 'choose_level',
                'content' => 'Chọn cấp độ',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            131 => 
            array (
                'id' => 1138,
                'groups_id' => 18,
                'pkey' => 'level_1',
                'content' => 'Cấp độ 1',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            132 => 
            array (
                'id' => 1139,
                'groups_id' => 18,
                'pkey' => 'level_2',
                'content' => 'Cấp độ 2',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            133 => 
            array (
                'id' => 1140,
                'groups_id' => 18,
                'pkey' => 'level_3',
                'content' => 'Cấp độ 3',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            134 => 
            array (
                'id' => 1141,
                'groups_id' => 18,
                'pkey' => 'level_4',
                'content' => 'Cấp độ 4',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            135 => 
            array (
                'id' => 1142,
                'groups_id' => 18,
                'pkey' => 'rating_template',
                'content' => 'Mẫu đánh giá',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            136 => 
            array (
                'id' => 1143,
                'groups_id' => 18,
                'pkey' => 'choose_rating_template',
                'content' => 'Chọn mẫu đánh giá',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            137 => 
            array (
                'id' => 1144,
                'groups_id' => 18,
                'pkey' => 'rating_name',
                'content' => 'Tên đánh giá',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            138 => 
            array (
                'id' => 1145,
                'groups_id' => 18,
                'pkey' => 'level',
                'content' => 'Cấp độ',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            139 => 
            array (
                'id' => 1146,
                'groups_id' => 18,
                'pkey' => 'object',
                'content' => 'Đối tượng',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            140 => 
            array (
                'id' => 1147,
                'groups_id' => 18,
                'pkey' => 'add_object',
                'content' => 'Thêm đối tượng',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            141 => 
            array (
                'id' => 1148,
                'groups_id' => 18,
                'pkey' => 'subjects_evaluated',
                'content' => 'Đối tượng được đánh giá',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            142 => 
            array (
                'id' => 1149,
                'groups_id' => 18,
                'pkey' => 'classroom',
                'content' => 'Lớp học',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            143 => 
            array (
                'id' => 1150,
                'groups_id' => 18,
                'pkey' => 'student',
                'content' => 'Học viên',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            144 => 
            array (
                'id' => 1151,
                'groups_id' => 18,
                'pkey' => 'evaluation_object',
                'content' => 'Đối tượng đánh giá',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            145 => 
            array (
                'id' => 1152,
                'groups_id' => 18,
                'pkey' => 'time_rating',
                'content' => 'Thời gian đánh giá',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            146 => 
            array (
                'id' => 1153,
                'groups_id' => 18,
                'pkey' => 'choose_time',
                'content' => 'Chọn thời gian',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            147 => 
            array (
                'id' => 1154,
                'groups_id' => 18,
                'pkey' => 'time_period',
                'content' => 'Khoảng thời gian',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            148 => 
            array (
                'id' => 1155,
                'groups_id' => 18,
                'pkey' => 'start_course',
                'content' => 'Bắt đầu khóa học',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            149 => 
            array (
                'id' => 1156,
                'groups_id' => 18,
                'pkey' => 'end_course',
                'content' => 'Kết thúc khóa học',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            150 => 
            array (
                'id' => 1157,
                'groups_id' => 18,
                'pkey' => 'num_date',
                'content' => 'Số ngày',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            151 => 
            array (
                'id' => 1158,
                'groups_id' => 18,
                'pkey' => 'object_view',
                'content' => 'Đối tượng xem',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            152 => 
            array (
                'id' => 1159,
                'groups_id' => 18,
                'pkey' => 'choose_object_view_rating',
                'content' => 'Chọn đối tượng xem đánh giá',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            153 => 
            array (
                'id' => 1160,
                'groups_id' => 18,
                'pkey' => 'unit_chief',
                'content' => 'Trưởng đơn vị',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            154 => 
            array (
                'id' => 1161,
                'groups_id' => 18,
                'pkey' => 'student_complete',
                'content' => 'Học viên hoàn thành',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            155 => 
            array (
                'id' => 1162,
                'groups_id' => 18,
                'pkey' => 'yes',
                'content' => 'Có',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            156 => 
            array (
                'id' => 1163,
                'groups_id' => 18,
                'pkey' => 'colleague',
                'content' => 'Đồng nghiệp',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            157 => 
            array (
                'id' => 1164,
                'groups_id' => 18,
                'pkey' => 'quantity',
                'content' => 'Số lượng',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            158 => 
            array (
                'id' => 1165,
                'groups_id' => 18,
                'pkey' => 'other',
                'content' => 'Khác',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            159 => 
            array (
                'id' => 1166,
                'groups_id' => 18,
                'pkey' => 'user',
                'content' => 'Nhân viên',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            160 => 
            array (
                'id' => 1167,
                'groups_id' => 2,
                'pkey' => 'filter',
                'content' => 'Bộ lọc',
                'content_en' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
        ));
        
        
    }
}