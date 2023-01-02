<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
// use App\Models\Permission;

class PermissionTableSeeder extends Seeder
{
    // NHÓM MODULE
    // 1:	Quản lý Phân quyền
    // 2:	Quản lý thông tin tổ chức
    // 3:	Quản lý đào tạo
    // 4:	Quản lý kỳ thi
    // 5: 	Quản lý giảng viên
    // 6:	Quà tặng
    // 7:	Chương trình thi đua
    // 8:	Quản lý người dùng
    // 9:	Chung
    // 10:	Diễn đàn
    // 11:  khảo sát
    // 12:	Đào tạo đơn vị
    // 13:  roadmap
    // 14:  kế hoạch đào tạo
    // 15:	Đào tạo trực tuyến
    // 16:  Đào tạo tập trung
    // 17:	thư viện
    // 18:	quản lý tin tức
    // 19:	cài đặt
    // 20:	Báo cáo
    // 21:	Khóa học ảo
    // 22:  Sales Kit
    public function run()
    {
        $permissions = [
            ['role','Quản lý vai trò','el_roles',null, 1],
            ['role-create','Thêm vai trò',null,'role', 1],
            ['role-edit','Chỉnh sửa vai trò',null,'role', 1],
            ['role-permission','Cấp quyền vai trò',null,'role', 1],
            ['role-delete', 'Xoá vai trò',null,'role', 1],
            ['role-export', 'Export vai trò',null,'role', 1],

            ['permission-group','Nhóm quyền','el_permission_type',null, 1],
            ['permission-group-create','Thêm nhóm quyền',null,'permission-group', 1],
            ['permission-group-edit','Chỉnh sửa nhóm quyền',null,'permission-group', 1],
            ['permission-group-delete', 'Xoá nhóm quyền',null,'permission-group', 1],

            ['category-unit','Quản lý danh mục đơn vị','el_unit',null, 2],
            ['category-unit-create','Thêm đơn vị',null,'category-unit', 2],
            ['category-unit-edit','Chỉnh sửa đơn vị',null,'category-unit', 2],
            ['category-unit-delete', 'Xoá đơn vị',null,'category-unit', 2],
            ['category-unit-import', 'Import đơn vị',null,'category-unit', 2],
            ['category-unit-export', 'Export đơn vị',null,'category-unit', 2],

            //    ['permission-user','Phân quyền người dùng','el_profile',null],
            //    ['permission-user-permission','Phân quyền',null,'permission-user'],
            //    ['permission-user-import','Import',null,'permission-user'],
            //    ['permission-user-export', 'Export',null,'permission-user'],

            ['category-area','Quản lý miền, khu, vùng','el_area',null, 2],
            ['category-area-create','Thêm miền, khu, vùng',null,'category-area', 2],
            ['category-area-edit','Chỉnh sửa miền, khu, vùng',null,'category-area', 2],
            ['category-area-delete', 'Xoá miền, khu, vùng',null,'category-area', 2],

            ['category-unit-type','Quản lý loại đơn vị','el_unit_type',null, 2],
            ['category-unit-type-create','Thêm loại đơn vị',null,'category-unit-type', 2],
            ['category-unit-type-edit','Chỉnh sửa loại đơn vị',null,'category-unit-type', 2],
            ['category-unit-type-delete', 'Xoá loại đơn vị',null,'category-unit-type', 2],

            ['category-titles','Quản lý chức danh','el_titles',null, 2],
            ['category-titles-create','Thêm chức danh',null,'category-titles', 2],
            ['category-titles-edit','Chỉnh sửa chức danh',null,'category-titles', 2],
            ['category-titles-delete', 'Xoá chức danh',null,'category-titles', 2],
            ['category-titles-import', 'Import chức danh',null,'category-titles', 2],
            ['category-titles-export', 'Export chức danh',null,'category-titles', 2],

            ['category-title-rank','Quản lý cấp bậc chức danh','el_title_rank',null, 2],
            ['category-title-rank-create','Thêm cấp bậc chức danh',null,'category-title-rank', 2],
            ['category-title-rank-edit','Chỉnh sửa cấp bậc chức danh',null,'category-title-rank', 2],
            ['category-title-rank-delete', 'Xoá cấp bậc chức danh',null,'category-title-rank', 2],
            ['category-title-rank-isopend', 'Bật/tắt cấp bậc chức danh',null,'category-title-rank', 2],

            ['category-cert','Quản lý trình độ','el_cert',null, 2],
            ['category-cert-create','Thêm trình độ',null,'category-cert', 2],
            ['category-cert-edit','Chỉnh sửa trình độ',null,'category-cert', 2],
            ['category-cert-delete', 'Xoá trình độ',null,'category-cert', 2],

            ['category-position','Quản lý Chức vụ','el_position',null , 2],
            ['category-position-create','Thêm Chức vụ',null,'category-position', 2],
            ['category-position-edit','Chỉnh sửa Chức vụ',null,'category-position', 2],
            ['category-position-delete', 'Xoá Chức vụ',null,'category-position', 2],

            ['category-subject-type','Quản lý Chương trình đào tạo','el_subject_type',null, 2],
            ['category-subject-type-create','Thêm Chương trình đào tạo',null,'category-subject-type', 2],
            ['category-subject-type-edit','Chỉnh sửa Chương trình đào tạo',null,'category-subject-type', 2],
            ['category-subject-type-delete', 'Xoá Chương trình đào tạo',null,'category-subject-type', 2],
            ['category-subject-type-export', 'Export Chương trình đào tạo',null,'category-subject-type', 2],
            ['subject-type-create-object', 'Thêm đối tượng Chương trình đào tạo',null,'category-subject-type', 2],
            ['subject-type-delete-object', 'Xoá đối tượng Chương trình đào tạo',null,'category-subject-type', 2],

            ['category-training-program','Quản lý Chủ đề','el_training_program',null, 3],
            ['category-training-program-create','Thêm Chủ đề',null,'category-training-program', 3],
            ['category-training-program-edit','Chỉnh sửa Chủ đề',null,'category-training-program', 3],
            ['category-training-program-delete', 'Xoá Chủ đề',null,'category-training-program', 3],
            ['category-training-program-export', 'Export Chủ đề',null,'category-training-program', 3],

            ['category-level-subject','Quản lý mảng nghiệp vụ','el_level_subject',null, 3],
            ['category-level-subject-create','Thêm mảng nghiệp vụ',null,'category-level-subject', 3],
            ['category-level-subject-edit','Chỉnh sửa mảng nghiệp vụ',null,'category-level-subject', 3],
            ['category-level-subject-delete', 'Xoá mảng nghiệp vụ',null,'category-level-subject', 3],
            ['category-level-subject-export', 'Export mảng nghiệp vụ',null,'category-level-subject', 3],

            ['category-subject','Quản lý chuyên đề','el_subject',null, 3],
            ['category-subject-create','Thêm chuyên đề',null,'category-subject', 3],
            ['category-subject-edit','Chỉnh sửa chuyên đề',null,'category-subject', 3],
            ['category-subject-delete', 'Xoá chuyên đề',null,'category-subject', 3],
            ['category-subject-import', 'Import chuyên đề',null,'category-subject', 3],
            ['category-subject-export', 'Export chuyên đề',null,'category-subject', 3],

            ['category-training-location','Quản lý địa điểm đào tạo','el_training_location',null, 3],
            ['category-training-location-create','Thêm địa điểm đào tạo',null,'category-training-location', 3],
            ['category-training-location-edit','Chỉnh sửa địa điểm đào tạo',null,'category-training-location', 3],
            ['category-training-location-delete', 'Xoá địa điểm đào tạo',null,'category-training-location', 3],

            ['category-training-form','Quản lý loại hình đào tạo','el_training_form',null, 3],
            ['category-training-form-create','Thêm loại hình đào tạo',null,'category-training-form', 3],
            ['category-training-form-edit','Chỉnh sửa loại hình đào tạo',null,'category-training-form', 3],
            ['category-training-form-delete', 'Xoá loại hình đào tạo',null,'category-training-form', 3],

            ['category-training-type','Quản lý Hình thức đào tạo','el_training_type',null, 3],
            ['category-training-type-create','Thêm Hình thức đào tạo',null,'category-training-type', 3],
            ['category-training-type-edit','Chỉnh sửa Hình thức đào tạo',null,'category-training-type', 3],

            ['category-quiz-type','Quản lý loại kỳ thi','el_quiz_type',null, 4],
            ['category-quiz-type-create','Thêm loại kỳ thi',null,'category-quiz-type', 4],
            ['category-quiz-type-edit','Chỉnh sửa loại kỳ thi',null,'category-quiz-type', 4],
            ['category-quiz-type-delete', 'Xoá loại kỳ thi',null,'category-quiz-type', 4],

            ['category-type-cost','Quản lý loại chi phí','el_type_cost',null, 2],
            ['category-type-cost-create','Thêm loại chi phí',null,'category-type-cost', 2],
            ['category-type-cost-edit','Chỉnh sửa loại chi phí',null,'category-type-cost', 2],
            ['category-type-cost-delete', 'Xoá loại chi phí',null,'category-type-cost', 2],

            ['category-training-cost','Quản lý chi phí đào tạo','el_training_cost',null, 3],
            ['category-training-cost-create','Thêm chi phí đào tạo',null,'category-training-cost', 3],
            ['category-training-cost-edit','Chỉnh sửa chi phí đào tạo',null,'category-training-cost', 3],
            ['category-training-cost-delete', 'Xoá chi phí đào tạo',null,'category-training-cost', 3],

            ['category-student-cost','Quản lý chi phí học viên','el_student_cost',null, 3],
            ['category-student-cost-create','Thêm chi phí học viên',null,'category-student-cost', 3],
            ['category-student-cost-edit','Chỉnh sửa chi phí học viên',null,'category-student-cost', 3],
            ['category-student-cost-delete', 'Xoá chi phí học viên',null,'category-student-cost', 3],

            ['commit-month','Khung tài trợ chi phí và thời gian cam kết','el_commitment',null, 3],
            ['commit-month-create','Thêm Khung tài trợ chi phí và thời gian cam kết',null,'commit-month', 3],
            ['commit-month-edit','Chỉnh sửa Khung tài trợ chi phí và thời gian cam kết',null,'commit-month', 3],
            ['commit-month-delete', 'Xoá Khung tài trợ chi phí và thời gian cam kết',null,'commit-month', 3],

            ['category-teacher','Quản lý giảng viên','el_training_teacher',null, 5],
            ['category-teacher-create','Thêm giảng viên',null,'category-teacher', 5],
            ['category-teacher-edit','Chỉnh sửa giảng viên',null,'category-teacher', 5],
            ['category-teacher-delete', 'Xoá giảng viên',null,'category-teacher', 5],
            ['category-teacher-import', 'Import giảng viên',null,'category-teacher', 5],
            ['category-teacher-export', 'Export giảng viên',null,'category-teacher', 5],

            ['category-teacher-type','Quản lý loại giảng viên','el_teacher_type',null, 5],
            ['category-teacher-type-create','Thêm loại giảng viên',null,'category-teacher-type', 5],
            ['category-teacher-type-edit','Chỉnh sửa loại giảng viên',null,'category-teacher-type', 5],
            ['category-teacher-type-delete', 'Xoá loại giảng viên',null,'category-teacher-type', 5],

            ['category-partner','Quản lý đối tác','el_training_partner',null, 5],
            ['category-partner-create','Thêm đối tác',null,'category-partner', 5],
            ['category-partner-edit','Chỉnh sửa đối tác',null,'category-partner', 5],
            ['category-partner-delete', 'Xoá đối tác',null,'category-partner', 5],
            ['category-partner-export', 'Export đối tác',null,'category-partner', 5],

            ['category-province','Quản lý tỉnh thành','el_province',null, 2],
            ['category-province-create','Thêm tỉnh thành',null,'category-province', 2],
            ['category-province-edit','Chỉnh sửa tỉnh thành',null,'category-province', 2],
            ['category-province-delete', 'Xoá tỉnh thành',null,'category-province', 2],
            ['category-province-import', 'Import tỉnh thành',null,'category-province', 2],

            ['category-district','Quản lý quận huyện','el_district',null, 2],
            ['category-district-create','Thêm quận huyện',null,'category-district', 2],
            ['category-district-edit','Chỉnh sửa quận huyện',null,'category-district', 2],
            ['category-district-delete', 'Xoá quận huyện',null,'category-district', 2],

            ['category-absent','Quản lý Loại nghỉ','el_absent',null, 3],
            ['category-absent-create','Thêm Loại nghỉ',null,'category-absent', 3],
            ['category-absent-edit','Chỉnh sửa Loại nghỉ',null,'category-absent', 3],
            ['category-absent-delete', 'Xoá Loại nghỉ',null,'category-absent', 3],

            ['category-discipline','Quản lý Danh sách vi phạm','el_discipline',null, 3],
            ['category-discipline-create','Thêm Danh sách vi phạm',null,'category-discipline', 3],
            ['category-discipline-edit','Chỉnh sửa Danh sách vi phạm',null,'category-discipline', 3],
            ['category-discipline-delete', 'Xoá Danh sách vi phạm',null,'category-discipline', 3],

            ['category-absent_reason','Quản lý Lý do vắng mặt','el_absent_reason',null, 3],
            ['category-absent_reason-create','Thêm Lý do vắng mặt',null,'category-absent_reason', 3],
            ['category-absent_reason-edit','Chỉnh sửa Lý do vắng mặt',null,'category-absent_reason', 3],
            ['category-absent_reason-delete', 'Xoá Lý do vắng mặt',null,'category-absent_reason', 3],

            ['category-training-object','Nhóm đối tượng đào tạo','el_training-object',null, 3],
            ['category-training-object-create','Thêm Nhóm đối tượng',null,'category-training-object', 3],
            ['category-training-object-edit','Chỉnh sửa Nhóm đối tượng',null,'category-training-object', 3],
            ['category-training-object-delete', 'Xoá Nhóm đối tượng',null,'category-training-object', 3],

            ['category-userpoint-item','Quản lý điểm thưởng','el_userpoint_item',null, 6],
            ['category-userpoint-item-edit','Chỉnh sửa điểm thưởng',null,'category-userpoint-item', 6],

            ['category-usermedal','Quản lý huy hiệu thi đua','el_usermedal',null, 7],
            ['category-usermedal-create','Thêm huy hiệu thi đua',null,'category-usermedal', 7],
            ['category-usermedal-edit','Chỉnh sửa huy hiệu thi đua',null,'category-usermedal', 7],
            ['category-usermedal-delete', 'Xoá huy hiệu thi đua',null,'category-usermedal', 7],

            ['category-capabilities-group','Quản lý nhóm năng lực','el_capabilities_group',null, 3],
            ['category-capabilities-group-create','Thêm nhóm năng lực',null,'category-capabilities-group', 3],
            ['category-capabilities-group-edit','Chỉnh sửa nhóm năng lực',null,'category-capabilities-group', 3],
            ['category-capabilities-group-delete', 'Xoá nhóm năng lực',null,'category-capabilities-group', 3],

            ['category-capabilities-category','Quản lý danh mục năng lực','el_capabilities_category',null, 3],
            ['category-capabilities-category-create','Thêm danh mục năng lực',null,'category-capabilities-category', 3],
            ['category-capabilities-category-edit','Chỉnh sửa danh mục năng lực',null,'category-capabilities-category', 3],
            ['category-capabilities-category-delete', 'Xoá danh mục năng lực',null,'category-capabilities-category', 3],

            ['category-capabilities','Quản lý khung năng lực','el_capabilities',null, 3],
            ['category-capabilities-create','Thêm khung năng lực',null,'category-capabilities', 3],
            ['category-capabilities-edit','Chỉnh sửa khung năng lực',null,'category-capabilities', 3],
            ['category-capabilities-delete', 'Xoá khung năng lực',null,'category-capabilities', 3],

            ['category-capabilities-title','Quản lý khung năng lực theo chức danh','el_capabilities_title',null, 3],
            ['category-capabilities-title-create','Thêm khung năng lực theo chức danh',null,'category-capabilities-title', 3],
            ['category-capabilities-title-edit','Chỉnh sửa khung năng lực theo chức danh',null,'category-capabilities-title', 3],
            ['category-capabilities-title-delete', 'Xoá khung năng lực theo chức danh',null,'category-capabilities-title', 3],

            ['category-capabilities-group-percent','Quản lý nhóm phần trăm','el_capabilities_group_percent',null, 3],
            ['category-capabilities-group-percent-create','Thêm nhóm phần trăm',null,'category-capabilities-group-percent', 3],
            ['category-capabilities-group-percent-edit','Chỉnh sửa nhóm phần trăm',null,'category-capabilities-group-percent', 3],
            ['category-capabilities-group-percent-delete', 'Xoá nhóm phần trăm',null,'category-capabilities-group-percent', 3],

            ['user','Người dùng','el_profile',null, 8],
            ['user-create','Thêm người dùng',null,'user', 8],
            ['user-edit','Chỉnh sửa người dùng',null,'user', 8],
            ['user-delete', 'Xoá người dùng',null,'user', 8],
            ['user-view-training-process', 'Xem quá trình đào tạo',null,'user', 8],
            ['user-view-quiz-result', 'Xem kết quả thi',null,'user', 8],
            ['user-view-roadmap', 'Xem chương trình khung',null,'user', 8],
            ['user-view-career-roadmap', 'Xem lộ trình nghề nghiệp',null,'user', 8],
            ['user-view-working-process', 'Xem quá trình công tác',null,'user', 8],
            ['user-view-training-program-learned', 'Xem chủ đề đã học',null,'user', 8],
            ['user-view-training-by-title', 'Xem lộ trình đào tạo',null,'user', 8],
            ['user-approve-change-info', 'Duyệt đổi thông tin',null,'user', 8],
            ['user-import', 'Import người dùng',null,'user', 8],
            ['user-export', 'Export người dùng',null,'user', 8],

            ['user-contact','Người dùng liên hệ','el_usermedal',null, 8],
            ['user-contact-delete', 'Xoá người dùng liên hệ',null,'user-contact', 8],

            ['login-history','Lịch sử truy cập','el_login_history',null, 9],

            /*['capabilities-review','Đánh giá khung năng lực',1,null],
            ['capabilities-review-create','Tạo đánh giá khung năng lực',null,'capabilities-review'],
            ['capabilities-review-edit','Chỉnh sửa đánh giá khung năng lực',null,'capabilities-review'],
            ['capabilities-review-delete', 'Xoá đánh giá khung năng lực',null,'capabilities-review'],
            ['capabilities-review-send', 'Gửi đánh giá khung năng lực',null,'capabilities-review'],

            ['capabilities-result','Xây dựng kế hoạch đào tạo',1,null],
            ['capabilities-result-create','Thêm xây dựng kế hoạch đào tạo',null,'capabilities-result'],
            ['capabilities-result-edit','Chỉnh sửa xây dựng kế hoạch đào tạo',null,'capabilities-result'],
            ['capabilities-result-delete', 'Xoá xây dựng kế hoạch đào tạo',null,'capabilities-result'],
            ['capabilities-result-send', 'Gửi xây dựng kế hoạch đào tạo',null,'capabilities-result'],

            ['potential','Nhân sự tiềm năng',1,null],
            ['potential-create','Thêm nhân sự tiềm năng',null,'potential'],
            ['potential-edit','Chỉnh sửa nhân sự tiềm năng',null,'potential'],
            ['potential-delete', 'Xoá nhân sự tiềm năng',null,'potential'],
            ['potential-export', 'Xuất file nhân sự tiềm năng',null,'potential'],
            ['potential-kpi', 'Xem danh sách KPI',null,'potential'],
            ['potential-roadmap','Xem chương trình khung nhân sự tiềm năng',null,'potential'],
            ['potential-roadmap-create','Thêm chương trình khung nhân sự tiềm năng',null,'potential'],
            ['potential-roadmap-edit','Chỉnh sửa chương trình khung nhân sự tiềm năng',null,'potential'],
            ['potential-roadmap-delete', 'Xoá chương trình khung nhân sự tiềm năng',null,'potential'],

            ['convert-titles','Quản lý chuyển đổi chức danh',1,null],
            ['convert-titles-evaluate', 'Đánh giá chuyển đổi chức danh',null,'convert-titles'],
            ['convert-titles-roadmap','Xem chương trình khung chuyển đổi chức danh',null,'convert-titles'],
            ['convert-titles-roadmap-create','Thêm chương trình khung chuyển đổi chức danh',null,'convert-titles'],
            ['convert-titles-roadmap-edit','Chỉnh sửa chương trình khung chuyển đổi chức danh',null,'convert-titles'],
            ['convert-titles-roadmap-delete', 'Xoá chương trình khung chuyển đổi chức danh',null,'convert-titles'],
            ['convert-titles-review','Xem mẫu đánh giá chuyển đổi chức danh',null,'convert-titles'],
            ['convert-titles-review-create','Thêm mẫu đánh giá chuyển đổi chức danh',null,'convert-titles'],
            ['convert-titles-review-edit','Chỉnh sửa mẫu đánh giá chuyển đổi chức danh',null,'convert-titles'],
            ['convert-titles-review-delete', 'Xoá mẫu đánh giá chuyển đổi chức danh',null,'convert-titles'],*/

            // ['feedback','Quản lý phản hồi',1,null],
            // ['feedback-create','Thêm phản hồi',null,'feedback'],
            // ['feedback-edit','Chỉnh sửa phản hồi',null,'feedback'],
            // ['feedback-delete', 'Xoá phản hồi',null,'feedback'],

            ['forum','Quản lý diễn đàn','el_forum_category',null, 10],
            ['forum-create','Thêm diễn đàn',null,'forum', 10],
            ['forum-edit','Chỉnh sửa diễn đàn',null,'forum', 10],
            ['forum-delete', 'Xoá diễn đàn',null,'forum', 10],
            ['forum-status', 'Bật/Tắt diễn đàn',null,'forum', 10],
            ['forum-approve-post', 'Duyệt bài đăng',null,'forum', 10],
            /*['forum-send-post', 'Gửi bài viết',null,'forum'],
            ['forum-delete-post', 'Xoá bài viết',null,'forum'],
            ['forum-delete-comment', 'Xoá bình luận',null,'forum'],*/

            ['forum_thread','Bài viết','el_forum_thread',null, 10],
            ['forum_thread-create','Thêm Bài viết',null,'forum_thread', 10],
            ['forum_thread-edit','Sửa Bài viết',null,'forum_thread', 10],
            ['forum_thread-remove','Xóa Bài viết',null,'forum_thread', 10],

            ['suggest','Quản lý góp ý','el_suggest',null, 9],
            ['suggest-comment','Bình luận góp ý',null,'suggest', 9],

            ['rating-template','Mẫu đánh giá 4 cấp độ','el_rating_template',null, 3],
            ['rating-template-create','Thêm mẫu đánh giá',null,'rating-template', 3],
            ['rating-template-edit','Chỉnh sửa mẫu đánh giá',null,'rating-template', 3],
            ['rating-template-delete', 'Xoá mẫu đánh giá',null,'rating-template', 3],
            /*['rating-template-view-result', 'Xem kết quả đánh giá sau khóa học',null,'rating-template'],*/

            ['plan-app-template','Mẫu đánh giá hiệu quả đào tạo','el_plan_app_template',null, 3],
            ['plan-app-template-create','Thêm mẫu đánh giá',null,'plan-app-template', 3],
            ['plan-app-template-edit','Chỉnh sửa mẫu đánh giá',null,'plan-app-template', 3],
            ['plan-app-template-delete', 'Xoá đánh mẫu giá',null,'plan-app-template', 3],

            ['rating-levels','Tổ chức đánh giá','el_rating_levels',null, 3],
            ['rating-levels-create','Thêm kỳ đánh giá đào tạo',null,'rating-levels', 3],
            ['rating-levels-edit','Sửa kỳ đánh giá đào tạo',null,'rating-levels', 3],
            ['rating-levels-delete','Xóa kỳ đánh giá đào tạo',null,'rating-levels', 3],
            ['rating-levels-register','Thêm HV kỳ đánh giá đào tạo',null,'rating-levels', 3],
            ['rating-levels-setting','Thiết lập kỳ đánh giá đào tạo',null,'rating-levels', 3],
            ['rating-levels-result','Xem/Đánh giá kỳ đánh giá đào tạo',null,'rating-levels', 3],

            ['survey','Khảo sát','el_survey',null, 11],
            ['survey-create','Thêm khảo sát',null,'survey', 11],
            ['survey-edit','Chỉnh sửa khảo sát',null,'survey', 11],
            ['survey-delete', 'Xoá khảo sát',null,'survey', 11],
            ['survey-status','Bật/Tắt khảo sát',null,'survey', 11],
            ['survey-view-report','Xem báo cáo chi tiết khảo sát',null,'survey', 11],
            ['survey-export-report', 'Xuất báo cáo tổng hợp khảo sát',null,'survey', 11],

            ['survey-template','Mẫu khảo sát','el_survey_template', null, 11],
            ['survey-template-create','Thêm mẫu khảo sát',null,'survey-template', 11],
            ['survey-template-edit','Chỉnh sửa mẫu khảo sát',null,'survey-template', 11],
            ['survey-template-delete', 'Xoá mẫu khảo sát',null,'survey-template', 11],

            ['plan-suggest','Đề xuất kế hoạch đào tạo','el_plan_suggest',null, 12],
            ['plan-suggest-create','Thêm đề xuất kế hoạch đào tạo',null,'plan-suggest', 12],
            ['plan-suggest-edit','Chỉnh sửa đề xuất kế hoạch đào tạo',null,'plan-suggest', 12],
            ['plan-suggest-delete', 'Xoá đề xuất kế hoạch đào tạo',null,'plan-suggest', 12],
            ['plan-suggest-approve', 'Duyệt/Từ chối đề xuất kế hoạch đào tạo',null,'plan-suggest', 12],
            ['plan-suggest-export', 'Xuất đề xuất kế hoạch đào tạo',null,'plan-suggest', 12],

            ['career-roadmap','Lộ trình nghề nghiệp','career_roadmap',null, 13],
            ['career-roadmap-create','Thêm lộ trình nghề nghiệp',null,'career-roadmap', 13],
            ['career-roadmap-edit','Sửa lộ trình nghề nghiệp',null,'career-roadmap', 13],
            ['career-roadmap-delete','Xóa lộ trình nghề nghiệp',null,'career-roadmap', 13],

            ['training-plan','Kế hoạch đào tạo năm','el_training_plan',null, 14],
            ['training-plan-create','Thêm kế hoạch đào tạo năm',null,'training-plan', 14],
            ['training-plan-edit','Chỉnh sửa kế hoạch đào tạo năm',null,'training-plan', 14],
            ['training-plan-delete', 'Xoá kế hoạch đào tạo năm',null,'training-plan', 14],
            ['training-plan-approved', 'Duyệt/Từ chối kế hoạch đào tạo năm',null,'training-plan', 14],
            ['training-plan-detail','Chi tiết kế hoạch đào tạo năm',null,'training-plan', 14],
            ['training-plan-detail-create','Thêm chi tiết kế hoạch đào tạo năm',null,'training-plan', 14],
            ['training-plan-detail-edit','Chỉnh sửa chi tiết kế hoạch đào tạo năm',null,'training-plan', 14],
            ['training-plan-detail-delete', 'Xoá chi tiết kế hoạch đào tạo năm',null,'training-plan', 14],

            ['online-course','Khóa học online','el_online_course',null, 15],
            ['online-course-create','Thêm khóa học online',null,'online-course', 15],
            ['online-course-edit','Chỉnh sửa khóa học online',null,'online-course', 15],
            ['online-course-delete', 'Xoá khóa học online',null,'online-course', 15],
            ['online-course-approve', 'Duyệt/Từ chối khóa học online',null,'online-course', 15],
            ['online-course-status', 'Bật/Tắt khóa học online',null,'online-course', 15],
            ['online-course-duplicate', 'Sao chép khóa học online',null,'online-course', 15],
            ['online-course-register','Ghi danh khóa học online','el_online_register',null, 15],
            ['online-course-register-create','Thêm ghi danh khóa học online',null,'online-course-register', 15],
            ['online-course-register-edit','Chỉnh sửa ghi danh khóa học online',null,'online-course-register', 15],
            ['online-course-register-delete', 'Xoá ghi danh khóa học online',null,'online-course-register', 15],
            ['online-course-register-approve', 'Duyệt/Từ chối ghi danh khóa học online',null,'online-course-register', 15],
            ['online-course-result','Kết quả đào tạo khóa học online',null,'online-course', 15],
            ['online-course-rating-result','Kết quả đánh giá khóa học online',null,'online-course', 15],
            ['online-course-rating-level-result','Kết quả đánh giá cấp độ khóa học online',null,'online-course', 15],

            ['offline-course','Khóa học tập trung','el_offline_course',null, 16],
            ['offline-course-create','Thêm khóa học tập trung',null,'offline-course', 16],
            ['offline-course-edit','Chỉnh sửa khóa học tập trung',null,'offline-course', 16],
            ['offline-course-delete', 'Xoá khóa học tập trung',null,'offline-course', 16],
            ['offline-course-approve', 'Duyệt/Từ chối khóa học tập trung',null,'offline-course', 16],
            ['offline-course-status', 'Bật/Tắt khóa học tập trung',null,'offline-course', 16],
            ['offline-course-duplicate', 'Sao chép khóa học tập trung',null,'offline-course', 16],

            ['offline-course-register','Ghi danh khóa học tập trung','el_offline_register',null, 16],
            ['offline-course-register-create','Thêm ghi danh khóa học tập trung',null,'offline-course-register', 16],
            ['offline-course-register-delete','Xóa ghi danh khóa học tập trung',null,'offline-course-register', 16],
            ['offline-course-register-approve', 'Duyệt/Từ chối ghi danh khóa học tập trung',null,'offline-course-register', 16],
            ['offline-course-register-import', 'Import ghi danh khóa học tập trung',null,'offline-course-register', 16],
            ['offline-course-register-export', 'Export ghi danh khóa học tập trung',null,'offline-course-register', 16],

            ['offline-course-teacher','Giảng viên khóa học tập trung',null,'offline-course', 16],
            ['offline-course-teacher-create','Thêm giảng viên khóa học tập trung',null,'offline-course', 16],
            ['offline-course-teacher-delete','Xóa giảng viên khóa học tập trung',null,'offline-course', 16],
            ['offline-course-attendance','Điểm danh khóa học tập trung',null,'offline-course', 16],
            ['offline-course-result','Kết quả đào tạo khóa học tập trung',null,'offline-course', 16],
            ['offline-course-result-create','Thêm kết quả đào tạo khóa học tập trung',null,'offline-course', 16],
            ['offline-course-rating-result','Kết quả đánh giá khóa học tập trung',null,'offline-course', 16],
            ['offline-course-rating-level-result','Kết quả đánh giá cấp độ khóa học tập trung',null,'offline-course', 16],

            /*['training-unit','Đào tạo đơn vị',1,null],
            ['training-unit-offline-course','Khóa học tập trung',null,'training-unit'],
           ['training-unit-offline-course-create','Thêm khóa học tập trung',null,'training-unit'],
            ['training-unit-offline-course-edit','Chỉnh sửa khóa học tập trung',null,'training-unit'],
            ['training-unit-offline-course-delete', 'Xoá khóa học tập trung',null,'training-unit'],
            ['training-unit-offline-course-approve', 'Duyệt/Từ chối khóa học tập trung',null,'training-unit'],
            ['training-unit-offline-course-status', 'Bật/Tắt khóa học tập trung',null,'training-unit'],
            ['training-unit-offline-course-register','Ghi danh khóa học tập trung',null,'training-unit'],
            ['training-unit-offline-course-register-create','Thêm ghi danh khóa học tập trung',null,'training-unit'],
            ['training-unit-offline-course-register-approve', 'Duyệt/Từ chối ghi danh khóa học tập trung',null,'training-unit'],
            ['training-unit-offline-course-teacher','Giảng viên khóa học tập trung',null,'training-unit'],
            ['training-unit-offline-course-teacher-create','Thêm giảng viên khóa học tập trung',null,'training-unit'],
            ['training-unit-offline-course-attendance','Điểm danh khóa học tập trung',null,'training-unit'],
            ['training-unit-offline-course-result-create','Thêm kết quả đào tạo khóa học tập trung',null,'training-unit'],
            ['training-unit-quiz','Kỳ thi',null,'training-unit'],
            ['training-unit-quiz-create','Thêm kỳ thi',null,'training-unit'],
            ['training-unit-quiz-edit','Chỉnh sửa kỳ thi',null,'training-unit'],
            ['training-unit-quiz-delete', 'Xoá kỳ thi',null,'training-unit'],
            ['training-unit-quiz-view-result', 'Cho/Tắt xem kết quả',null,'training-unit'],
            ['training-unit-quiz-status', 'Bật/Tắt kỳ thi',null,'training-unit'],
            ['training-unit-quiz-copy', 'Sao chép kỳ thi',null,'training-unit'],
            ['training-unit-quiz-add-question', 'Thêm câu hỏi vào kỳ thi',null,'training-unit'],
            ['training-unit-quiz-register', 'Ghi danh thí sinh nội bộ',null,'training-unit'],
            ['training-unit-quiz-register-user-secondary', 'Xem ghi danh người thi ngoài',null,'training-unit'],
            ['training-unit-quiz-result', 'Kết quả kỳ thi',null,'training-unit'],
            ['training-unit-quiz-update-grade', 'Sửa điểm kỳ thi',null,'training-unit'],
            ['training-unit-quiz-update-reexamine', 'Sửa điểm phúc khảo kỳ thi',null,'training-unit'],
            ['training-unit-quiz-export', 'Xuất kết quả kỳ thi',null,'training-unit'],
            ['training-unit-quiz-print-exam', 'In đề kỳ thi',null,'training-unit'],*/

            ['indemnify','Quản lý bồi hoàn','el_indemnify',null, 3],
            ['indemnify-update-committed-date','Chỉnh sửa số ngày cam kết',null,'indemnify', 3],

            ['certificate-template','Mẫu chứng chỉ','el_certificate',null, 3],
            ['certificate-template-create','Thêm mẫu chứng chỉ',null,'certificate-template', 3],
            ['certificate-template-edit','Chỉnh sửa mẫu chứng chỉ',null,'certificate-template', 3],
            ['certificate-template-delete','Xoá mẫu chứng chỉ',null,'certificate-template', 3],

            ['training-roadmap','Chương trình khung','el_trainingroadmap',null, 3],
            ['training-roadmap-create', 'Thêm mới Chương trình khung',null,'training-roadmap', 3],
            ['training-roadmap-delete', 'Xóa Chương trình khung',null,'training-roadmap', 3],
            ['training-roadmap-export', 'Export chương trình khung',null,'training-roadmap', 3],
            ['training-roadmap-import', 'Import chương trình khung',null,'training-roadmap', 3],

            ['training-roadmap-detail','Chi tiết Chương trình khung','el_trainingroadmap',null, 3],
            ['training-roadmap-detail-create','Thêm Chi tiết chương trình khung',null,'training-roadmap-detail', 3],
            ['training-roadmap-detail-edit','Chỉnh sửa Chi tiết chương trình khung',null,'training-roadmap-detail', 3],
            ['training-roadmap-detail-delete', 'Xoá Chi tiết chương trình khung',null,'training-roadmap-detail', 3],
            ['training-roadmap-detail-export', 'Export Chi tiết chương trình khung',null,'training-roadmap-detail', 3],

//            ['plan-app','Quản lý đánh giá hiệu quả đào tạo','el_plan_app',null],

            ['quiz','Kỳ thi','el_quiz',null, 4],
            ['quiz-create','Thêm kỳ thi',null,'quiz', 4],
            ['quiz-edit','Chỉnh sửa kỳ thi',null,'quiz', 4],
            ['quiz-delete', 'Xoá kỳ thi',null,'quiz', 4],
            ['quiz-view-result', 'Cho/Tắt xem kết quả',null,'quiz', 4],
            ['quiz-approve', 'Duyệt/Từ chối kỳ thi',null,'quiz', 4],
            ['quiz-status', 'Bật/Tắt kỳ thi',null,'quiz', 4],
            ['quiz-copy', 'Sao chép kỳ thi',null,'quiz', 4],
            ['quiz-add-question', 'Thêm câu hỏi vào kỳ thi',null,'quiz', 4],
            ['quiz-register', 'Ghi danh thí sinh nội bộ',null,'quiz', 4],
            ['quiz-register-user-secondary', 'Ghi danh thí sinh bên ngoài',null,'quiz', 4],
            ['quiz-result', 'Kết quả kỳ thi',null,'quiz', 4],
            ['quiz-update-grade', 'Sửa điểm kỳ thi',null,'quiz', 4],
            ['quiz-update-reexamine', 'Sửa điểm phúc khảo kỳ thi',null,'quiz', 4],
            ['quiz-export', 'Xuất kết quả kỳ thi',null,'quiz', 4],
            ['quiz-print-exam', 'In đề kỳ thi',null,'quiz', 4],
            ['quiz-grading', 'Chấm điểm kỳ thi',null,'quiz', 4],

            ['quiz-category-question','Ngân hàng câu hỏi','el_question_category',null, 4],
            ['quiz-category-question-create','Thêm danh mục ngân hàng câu hỏi',null,'quiz-category-question', 4],
            ['quiz-category-question-edit','Chỉnh sửa danh mục ngân hàng câu hỏi',null,'quiz-category-question', 4],
            ['quiz-category-question-delete', 'Xoá danh mục ngân hàng câu hỏi',null,'quiz-category-question', 4],
            ['quiz-category-question-permission', 'Phân quyền danh mục ngân hàng câu hỏi',null,'quiz-category-question', 4],

            ['quiz-question','Câu hỏi','el_question',null, 4],
            ['quiz-question-create','Thêm câu hỏi',null,'quiz-question', 4],
            ['quiz-question-edit','Chỉnh sửa câu hỏi',null,'quiz-question', 4],
            ['quiz-question-delete', 'Xoá câu hỏi',null,'quiz-question', 4],
            ['quiz-question-approve', 'Duyệt/Từ chối câu hỏi',null,'quiz-question', 4],
            ['quiz-question-import', 'Import câu hỏi',null,'quiz-question', 4],
            ['quiz-question-export', 'Export câu hỏi',null,'quiz-question', 4],

            ['quiz-user-secondary','Thí sinh bên ngoài','el_quiz_user_secondary',null, 4],
            ['quiz-user-secondary-create','Thêm thí sinh bên ngoài',null,'quiz-user-secondary', 4],
            ['quiz-user-secondary-edit','Chỉnh sửa thí sinh bên ngoài',null,'quiz-user-secondary', 4],
            ['quiz-user-secondary-delete', 'Xoá thí sinh bên ngoài',null,'quiz-user-secondary', 4],

            ['quiz-template','Cơ cấu đề thi','el_quiz_templates',null, 4],
            ['quiz-template-create','Thêm Cơ cấu đề thi',null,'quiz-template', 4],
            ['quiz-template-approved','Duyệt/Từ chối Cơ cấu đề thi',null,'quiz-template', 4],
            ['quiz-template-open','Bật/Tắt Cơ cấu đề thi',null,'quiz-template', 4],
            ['quiz-template-edit','Chỉnh sửa Cơ cấu đề thi',null,'quiz-template', 4],
            ['quiz-template-delete', 'Xoá Cơ cấu đề thi',null,'quiz-template', 4],
            ['quiz-template-rank', 'Thêm xếp loại Cơ cấu đề thi',null,'quiz-template', 4],
            ['quiz-template-setting', 'Thêm tùy chỉnh Cơ cấu đề thi',null,'quiz-template', 4],
            ['quiz-template-question', 'Thêm câu hỏi Cơ cấu đề thi',null,'quiz-template', 4],

            ['quiz-setting-alert','Thiết lập cảnh báo','el_quiz_user_secondary',null, 4],
            ['quiz-setting-alert-create','Thêm Thiết lập cảnh báo',null,'quiz-setting-alert', 4],

            ['quiz-dashboard','Thống kê kì thi',1,null, 4],
            ['quiz-dashboard-watch','Xem Thống kê kì thi',null,'quiz-dashboard', 4],

            ['quiz-history','Lịch sử thi tuyển thí sinh nội bộ',1,null, 4],
            ['quiz-history-watch','Xem Lịch sử thi tuyển thí sinh nội bộ',null,'quiz-history', 4],

            ['quiz-history-user-second','Lịch sử thi tuyển thí sinh bên ngoài',1,null, 4],
            ['quiz-history-user-second-watch','Xem Lịch sử thi tuyển thí sinh bên ngoài',null,'quiz-history-user-second', 4],

            ['libraries-category','Danh mục thư viện','el_libraries_category',null, 17],
            ['libraries-category-create','Thêm danh mục thư viện',null,'libraries-category', 17],
            ['libraries-category-edit','Chỉnh sửa danh mục thư viện',null,'libraries-category', 17],
            ['libraries-category-delete', 'Xoá danh mục thư viện',null,'libraries-category', 17],

            ['libraries-book','Sách trong thư viện','el_libraries',null, 17],
            ['libraries-book-create','Thêm sách trong thư viện',null,'libraries-book', 17],
            ['libraries-book-edit','Chỉnh sửa sách trong thư viện',null,'libraries-book', 17],
            ['libraries-book-delete', 'Xoá sách trong thư viện',null,'libraries-book', 17],

            ['libraries-book-register', 'Quản lý mượn sách trong thư viện','el_register_book',null, 17],
            ['libraries-book-register-approve', 'Duyệt mượn sách trong thư viện',null,'libraries-book-register', 17],
            ['libraries-book-register-borrow', 'Lấy/trả sách trong thư viện',null,'libraries-book-register', 17],
            ['libraries-book-register-delete', 'Xóa mượn sách trong thư viện',null,'libraries-book-register', 17],

            ['libraries-ebook','Sách điện tử trong thư viện','el_libraries',null, 17],
            ['libraries-ebook-create','Thêm sách điện tử trong thư viện',null,'libraries-ebook', 17],
            ['libraries-ebook-edit','Chỉnh sửa sách điện tử trong thư viện',null,'libraries-ebook', 17],
            ['libraries-ebook-delete', 'Xoá sách điện tử trong thư viện',null,'libraries-ebook', 17],

            ['libraries-document','Tài liệu trong thư viện','el_libraries',null, 17],
            ['libraries-document-create','Thêm tài liệu trong thư viện',null,'libraries-document', 17],
            ['libraries-document-edit','Chỉnh sửa tài liệu trong thư viện',null,'libraries-document', 17],
            ['libraries-document-delete', 'Xoá tài liệu trong thư viện',null,'libraries-document', 17],

            ['libraries-video','Video trong thư viện','el_libraries',null, 17],
            ['libraries-video-create','Thêm video trong thư viện',null,'libraries-video', 17],
            ['libraries-video-edit','Chỉnh sửa video trong thư viện',null,'libraries-video', 17],
            ['libraries-video-delete', 'Xoá video trong thư viện',null,'libraries-video', 17],

            ['libraries-audiobook','Sách nói trong thư viện','el_libraries',null, 17],
            ['libraries-audiobook-create','Thêm sách nói trong thư viện',null,'libraries-audiobook', 17],
            ['libraries-audiobook-edit','Chỉnh sửa sách nói trong thư viện',null,'libraries-audiobook', 17],
            ['libraries-audiobook-delete', 'Xoá sách nói trong thư viện',null,'libraries-audiobook', 17],

            // ['libraries-salekit','Salekit trong thư viện','el_libraries',null, 17],
            // ['libraries-salekit-create','Thêm Salekit trong thư viện',null,'libraries-salekit', 17],
            // ['libraries-salekit-edit','Chỉnh sửa Salekit trong thư viện',null,'libraries-salekit', 17],
            // ['libraries-salekit-delete', 'Xoá Salekit trong thư viện',null,'libraries-salekit', 17],

            ['news-category','Danh mục tin tức','el_news_category',null, 18],
            ['news-category-create','Thêm danh mục tin tức',null,'news-category', 18],
            ['news-category-edit','Chỉnh sửa danh mục tin tức',null,'news-category', 18],
            ['news-category-delete', 'Xoá danh mục tin tức',null,'news-category', 18],

            ['news-list','Danh sách tin tức','el_news',null, 18],
            ['news-list-create','Thêm danh sách tin tức',null,'news-list', 18],
            ['news-list-edit','Chỉnh sửa danh sách tin tức',null,'news-list', 18],
            ['news-list-delete', 'Xoá danh sách tin tức',null,'news-list', 18],
            ['news-list-status', 'Bật/Tắt danh sách tin tức',null,'news-list', 18],

            // ['advertising-photo','Danh sách ảnh quảng cáo','el_advertising_photo',null, 18],
            // ['advertising-photo-create','Thêm',null,'advertising-photo', 18],
            // ['advertising-photo-edit','Chỉnh sửa ',null,'advertising-photo', 18],
            // ['advertising-photo-delete', 'Xoá',null,'advertising-photo', 18],

            // ['news-outside-category','Danh mục tin tức chung','el_news_outside_category',null, 18],
            // ['news-outside-category-create','Thêm danh mục tin tức chung',null,'news-outside-category', 18],
            // ['news-outside-category-edit','Chỉnh sửa danh mục tin tức chung',null,'news-outside-category', 18],
            // ['news-outside-category-delete', 'Xoá danh mục tin tức chung',null,'news-outside-category', 18],

            // ['news-outside-list','Danh sách tin tức chung','el_news_outside',null, 18],
            // ['news-outside-list-create','Thêm danh sách tin tức chung',null,'news-outside-list', 18],
            // ['news-outside-list-edit','Chỉnh sửa danh sách tin tức chung',null,'news-outside-list', 18],
            // ['news-outside-list-delete', 'Xoá danh sách tin tức chung',null,'news-outside-list', 18],
            // ['news-outside-list-status', 'Bật/Tắt danh sách tin tức chung',null,'news-outside-list', 18],

            ['promotion','Tích lũy điểm thưởng','el_promotion',null, 6],
            ['promotion-create','Thêm tích lũy điểm thưởng',null,'promotion', 6],
            ['promotion-edit','Chỉnh sửa tích lũy điểm thưởng',null,'promotion', 6],
            ['promotion-delete', 'Xoá tích lũy điểm thưởng',null,'promotion', 6],

            ['promotion-group','Nhóm danh mục quà tặng (Tích lũy điểm thưởng)','el_promotion_group',null, 6],
            ['promotion-group-create','Thêm nhóm danh mục quà tặng',null,'promotion-group', 6],
            ['promotion-group-edit','Chỉnh sửa nhóm danh mục quà tặng',null,'promotion-group', 6],
            ['promotion-group-delete', 'Xoá nhóm danh mục quà tặng',null,'promotion-group', 6],

            ['promotion-level','Cấp bậc người dùng (Tích lũy điểm thưởng)','el_promotion_level',null, 8],
            ['promotion-level-create','Thêm cấp bậc người dùng',null,'promotion-level', 8],
            ['promotion-level-edit','Chỉnh sửa cấp bậc người dùng',null,'promotion-level', 8],
            ['promotion-level-delete', 'Xoá cấp bậc người dùng',null,'promotion-level', 8],

            ['promotion-orders','Lịch sử quà tặng','el_promotion_orders',null, 6],
            ['promotion-orders-edit','Chỉnh sửa',null,'promotion-orders', 6],
            ['promotion-orders-delete', 'Xoá',null,'promotion-orders', 6],

            ['promotion-purchase-history','Lịch sừ mua (Tích lũy điểm thưởng)','el_promotion_orders',null, 8],
            ['promotion-purchase-history-edit','Chỉnh sửa lịch sừ mua',null,'promotion-purchase-history', 8],

            ['logo','Logo',1,null, 19],
            ['logo-edit','Chỉnh sửa Logo',null,'logo', 19],

            ['favicon','Favicon',1,null, 19],
            ['favicon-edit','Chỉnh sửa favicon',null,'favicon', 19],

            ['notify','Thông báo',1,null, 19],
            ['notify-create','Thêm thông báo',null,'notify', 19],
            ['notify-edit','Chỉnh sửa thông báo',null,'notify', 19],
            ['notify-delete', 'Xoá thông báo',null,'notify', 19],
            ['notify-status', 'Bật/tắt thông báo',null,'notify', 19],

            ['mail-template','Mẫu mail template','el_mail_template',null, 19],
            ['mail-template-edit','Chỉnh sửa mẫu mail',null,'mail-template', 19],

            ['mail-history','Lịch sử gửi mail','el_mail_history',null, 19],

            ['guide','Hướng dẫn','el_guide',null, 19],
            ['guide-create','Thêm hướng dẫn',null,'guide', 19],
            ['guide-edit','Chỉnh sửa hướng dẫn',null,'guide', 19],
            ['guide-delete', 'Xoá hướng dẫn',null,'guide', 19],

            ['banner','Banner','el_slider',null, 19],
            ['banner-create','Thêm banner',null,'banner', 19],
            ['banner-edit','Chỉnh sửa banner',null,'banner', 19],
            ['banner-delete', 'Xoá banner',null,'banner', 19],

            ['report','Báo cáo',1,null, 20],
            ['report-01', 'Báo cáo số liệu công tác khảo thi',null, 'report', 20],
            ['report-02', 'Báo cáo số liệu điểm thi chi tiết',null, 'report', 20],
            ['report-03', 'Báo cáo cơ cấu đề thi',null, 'report', 20],
            ['report-04', 'Báo cáo tỉ lệ trả lời đúng từng câu hỏi trong ngân hàng câu hỏi',null, 'report', 20],
            ['report-05', 'Báo cáo học viên tham gia khóa học tập trung/trực tuyến',null, 'report', 20],
            ['report-06', 'Danh sách học viên của đơn vị theo chuyên đề',null, 'report', 20],
            ['report-07', 'Báo cáo quá trình đào tạo của nhân viên',null, 'report', 20],
            ['report-08', 'Tổng hợp tình hình tổ chức các khóa học nội bộ và bên ngoài',null, 'report', 20],
            ['report-09', 'Thống kê tình hình đào tạo nhân viên tân tuyển',null, 'report', 20],
            ['report-10', 'Danh sách CBNV không chấp hành nội quy đào tạo',null, 'report', 20],
            ['report-11', 'Thống kê Giảng viên Đào tạo (Nội bộ & bên ngoài) theo Tháng/Quý/Năm',null, 'report', 20],
            ['report-12', 'Thống kê chi tiết học viên theo đơn vị',null, 'report', 20],
            ['report-13', 'Báo cáo chi phí đào tạo theo khu vực',null, 'report', 20],
            // ['report-14', 'Export danh mục',null, 'report', 20],
            ['report-15', 'Báo cáo tổng hợp kết quả theo tháp đào tạo',null, 'report', 20],
            ['report-16', 'Báo cáo lịch sử giảng dạy',null, 'report', 20],
            ['report-17', 'Danh sách xác nhận bồi hoàn chi phí đào tạo đối với CBNV tân tuyển',null, 'report', 20],
            ['report-18', 'Danh sách xác nhận bồi hoàn chi phí đào tạo đối với CBNV có cam kết',null, 'report', 20],
            ['report-21', 'Danh sách các khóa học trực tuyến đang mở',null, 'report', 20],
            ['report-22', 'Danh sách các chuyên đề gộp/tách',null, 'report', 20],
            ['report-23', 'Thống kê tỷ lệ hoàn thành tháp đào tạo theo chức danh',null, 'report', 20],
            ['report-24', 'Tổng hợp tình hình tham gia đào tạo các khóa E-Learning theo đơn vị',null, 'report', 20],
            ['report-25', 'Tổng hợp tình hình tham gia đào tạo các khóa E-Learning theo chuyên đề',null, 'report', 20],
            ['report-26', 'Báo cáo thù lao giảng viên',null, 'report', 20],
            ['report-27', 'Báo cáo chi phí đào tạo',null, 'report', 20],
            ['report-28', 'Báo cáo kết quả chi tiết theo kỳ thi',null, 'report', 20],
            ['report-29', 'Báo cáo kết quả thực hiện so với kế hoạch quý/năm',null, 'report', 20],
            ['report-30', 'Báo cáo kết quả đánh giá khóa học',null,'report', 20],
            ['report-31', 'Báo cáo tổng giờ học của học viên',null,'report', 20],
            ['report-32', 'Báo cáo tổng giờ học theo từng đơn vị, chức danh',null,'report', 20],
            ['report-33', 'Báo cáo Danh sách khảo sát', null, 'report', 20],
            ['report-34', 'Báo cáo thống kê ngân hàng câu hỏi', null, 'report', 20],
            ['report-35', 'BÁO cáo tình hình tổ chức đào tạo E-LEARNING/TẬP TRUNG', null, 'report', 20],
            ['report-36', 'THỐNG KÊ TẤT CẢ NHÂN VIÊN THEO KHÓA HỌC', null, 'report', 20],
            ['report-37', 'Báo cáo thống kê chi tiết tỷ lệ Ttrả lời đúng từng câu hỏi', null, 'report', 20],
            ['report-38', 'Báo cáo thống kê tất cả nhân viên theo khóa học', null, 'report', 20],
            ['report-40', 'Báo cáo chi tiết tổng giờ học của học viên theo khóa học', null, 'report', 20],
            ['report-41', 'Báo cáo đánh giá khung năng lực theo chức danh', null, 'report', 20],

//            ['report-01','Danh sách ký cam kết bồi hoàn',null,'report'],
//            ['report-02','Danh sách học viên tham gia các khóa đào tạo',null,'report'],
//            ['report-03','Danh sách khóa đào tạo có chi phí',null,'report'],
//            ['report-04','Danh sách học viên tham gia khóa đào tạo',null,'report'],
//            ['report-05','Báo cáo giáo vụ',null,'report'],
//            ['report-06','Báo cáo kết quả khóa học tập trung',null,'report'],
//            ['report-07','Báo cáo kết quả khóa học Elearning',null,'report'],
//            ['report-08','Báo cáo đánh giá sau khóa học',null,'report'],
//            ['report-09','Báo cáo danh sách giảng viên',null,'report'],
//            ['report-10','Báo cáo đánh giá',null,'report'],
//            ['report-11','Thống kê đăng ký tham giá khóa học',null,'report'],
//            ['report-12','Danh sách vi phạm',null,'report'],
//            ['report-13','Thống kê kết quả đào tạo',null,'report'],
//            ['report-14','Quá trình đào tạo',null,'report'],
//            ['report-15','Báo cáo chi tiết kết quả kỳ thi',null,'report'],
//            ['report-16','Báo cáo số lần thi theo nhóm câu hỏi',null,'report'],
//            ['report-17','Báo cáo lần truy cập',null,'report'],
//            ['report-18','Thống kê thí sinh trong kỳ thi theo chức danh',null,'report'],
//            ['report-19','Thống kê tỷ lệ xếp loại trong kỳ thi theo chức danh',null,'report'],
//            ['report-20','Kế hoạch tự đào tạo',null,'report'],
//            ['report-21','Tổng hợp báo cáo đào tạo nội bộ',null,'report'],
//            ['report-22','Báo cáo chi tiết thực hiện đào tạo nội bộ',null,'report'],
//            ['report-23','Báo cáo thống kê kết quả khảo sát',null,'report'],
//            ['report-24','Báo cáo tình hình đào tạo theo kênh phân phối',null,'report'],
//            ['report-25','Thống kê kết quả đào tạo theo chức danh',null,'report'],
//            ['report-26','Báo cáo đào tạo',null,'report'],
//            ['report-27','Báo cáo hiệu quả sau đào tạo',null,'report'],
            // ['report-28','Báo cáo 28',null,'report'],
            // ['report-29','Báo cáo 29',null,'report'],
            // ['report-30','Báo cáo 30',null,'report'],
            // ['report-31','Báo cáo 31',null,'report'],
            // ['report-32','Báo cáo 32',null,'report'],
            // ['report-33','Báo cáo 33',null,'report'],
            // ['report-34','Báo cáo 34',null,'report'],
            // ['report-35','Báo cáo 35',null,'report'],

            /*['commit-month','Cam kết',1,null],
            ['commit-month-create','Thêm cam kết',null,'commit-month'],
            ['commit-month-edit','Chỉnh sửa cam kết',null,'commit-month'],
            ['commit-month-delete', 'Xoá cam kết',null,'commit-month'],*/

            ['donate-point','Điểm tặng','el_donate_points',null, 6],
            ['donate-point-create','Thêm Điểm tặng',null,'donate-point', 6],
            ['donate-point-edit','Chỉnh sửa Điểm tặng',null,'donate-point', 6],
            ['donate-point-delete', 'Xoá Điểm tặng',null,'donate-point', 6],
            ['donate-point-import', 'Import điểm',null,'donate-point', 6],
            ['donate-point-export', 'Export điểm',null,'donate-point', 6],
            ['donate-point-download', 'Download mẫu import',null,'donate-point', 6],

            ['FAQ','Câu hỏi thường gặp','el_faq',null, 9],
            ['FAQ-create','Thêm câu hỏi thường gặp',null,'FAQ', 9],
            ['FAQ-edit','Chỉnh sửa câu hỏi thường gặp',null,'FAQ', 9],
            ['FAQ-delete', 'Xoá câu hỏi thường gặp',null,'FAQ', 9],

            ['daily-training','Danh mục video','el_daily_training_category',null, 9],
            ['daily-training-create','Thêm danh mục video',null,'daily-training', 9],
            ['daily-training-edit','Chỉnh sửa danh mục video',null,'daily-training', 9],
            ['daily-training-delete', 'Xoá danh mục video',null,'daily-training', 9],
            ['daily-training-reawrd-point-create', 'Thêm điểm thưởng danh mục video',null,'daily-training', 9],
            ['daily-training-reawrd-point-edit', 'Chỉnh sửa điểm thưởng danh mục video',null,'daily-training', 9],
            ['daily-training-reawrd-point-delete', 'Xóa điểm thưởng danh mục video',null,'daily-training', 9],

            ['daily-training-video','Video',1,null, 9],
            ['daily-training-video-acceept','Duyệt video',null,'daily-training-video', 9],
            ['daily-training-video-delete', 'Xoá video',null,'daily-training-video', 9],

            // ['daily-training-permission','Quyền danh mục video',1,null],
            // ['daily-training-permission-save','Thêm quyền danh mục video',null,'daily-training-permission'],

            ['login-image','Hình nền đăng nhập',1,null, 19],
            ['login-image-choose','chọn hình nền đăng nhập',null,'login-image', 19],
            ['login-image-save','Lưu hình nền đăng nhập',null,'login-image', 19],

            ['setting-email','cài đăt cấu hình email',1,null, 19],
            ['setting-email-save','Lưu cài đăt cấu hình email',null,'setting-email', 19],

            ['config','cài đăt cấu hình chung (LDAP)','el_config',null, 19],
            ['config-save','Lưu cài đăt cấu hình chung',null,'config', 19],
            ['config-email','Cài đăt cấu hình email',null,'config', 19],
            ['config-email-save','Lưu cài đăt cấu hình email',null,'config', 19],
            ['config-point-refer','Cài đăt cấu hình điểm giới thiệu',null,'config', 19],
            ['config-point-refer-save','Lưu cài đăt cấu hình điểm giới thiệu',null,'config', 19],
            ['config-login-image','Hình nền đăng nhập',null,'config', 19],
            ['config-login-image-save','Lưu hình nền đăng nhập',null,'config', 19],
            ['config-logo','Logo',null,'config', 19],
            ['config-logo-save','Lưu logo',null,'config', 19],
            ['config-favicon','Favicon',null,'config', 19],
            ['config-favicon-save','Lưu favicon',null,'config', 19],
            ['config-app-mobile','App mobile',null,'config', 19],
            ['config-app-mobile-save','Lưu cài đặt App mobile',null,'config', 19],

            ['config-notify-send','cài đặt gửi thông báo','el_notify_send',null, 19],
            ['config-notify-create','Thêm',null,'config-notify-send', 19],
            ['config-notify-delete','Xóa',null,'config-notify-send', 19],
            ['config-notify-edit','Sửa',null,'config-notify-send', 19],
            ['config-notify-enable','Bật/tắt',null,'config-notify-send', 19],

            ['config-notify-template','cài đặt mẫu thông báo','el_notify_template',null, 19],
            ['config-notify-template-create','Thêm',null,'config-notify-template', 19],
            ['config-notify-template-edit','Sửa',null,'config-notify-template', 19],

            ['config-point-refer','Cài đặt điểm giới thiệu',1,null, 19],
            ['config-point-refer-save','Lưu cài đăt điểm giới thiệu',null,'config-point-refer', 19],

            ['training-plain-detail','Chi tiết kế hoạch đào tạo','el_training_plan_detail',null, 3],
            ['training-plain-detail-create','Thêm chi tiết kế hoạch đào tạo',null,'training-plain-detail', 3],
            ['training-plain-detail-edit','Sửa Chi tiết kế hoạch đào tạo',null,'training-plain-detail', 3],
            ['training-plain-detail-delete','Xóa Chi tiết kế hoạch đào tạo',null,'training-plain-detail', 3],

            ['dashboard','Quản lý Dashboard',1,null, 9],
            ['dashboard-unit','Quản lý Dashboard đơn vị',1,null, 9],
            ['category-master-data','Danh mục master data',1,null, 9],

            ['category-unit-type','Loại tổ chức','el_unit_type',null, 2],
            ['category-unit-type-create','Thêm',null,'category-unit-type', 2],
            ['category-unit-type-edit','Sửa',null,'category-unit-type', 2],
            ['category-unit-type-delete','Xóa',null,'category-unit-type', 2],
            ['category-unit-type-imoprt','Import',null,'category-unit-type', 2],
            ['category-unit-type-export','Export',null,'category-unit-type', 2],

            ['approve-register','Duyệt ghi danh','1',null, 3],

            ['quiz-register','Ghi danh thí sinh vào kỳ thi','el_quiz_register',null, 4],
            ['quiz-register-create','Thêm thí sinh',null,'quiz-register', 4],
            ['quiz-register-delete','Xóa thí sinh',null,'quiz-register', 4],
            ['quiz-register-import','Import thí sinh',null,'quiz-register', 4],
            ['quiz-register-export','Export thí sinh',null,'quiz-register', 4],

            ['usermedal-setting','Chương trình thi đua','el_usermedal_settings',null, 7],
            ['usermedal-setting-create','Thêm chương trình',null,'usermedal-setting', 7],
            ['usermedal-setting-edit','Chỉnh sửa chương trình',null,'usermedal-setting', 7],
            ['usermedal-setting-delete','Xóa chương trình',null,'usermedal-setting', 7],
            ['usermedal-setting-create-object','Thêm đối tượng chương trình',null,'usermedal-setting', 7],
            ['usermedal-setting-delete-object','Xóa đối tượng chương trình',null,'usermedal-setting', 7],

            ['mergesubject','Gộp chuyên đề','el_merge_subject',null, 3],
            ['mergesubject-create','Thêm gộp chuyên đề',null,'mergesubject', 3],
            ['mergesubject-edit','Chỉnh sửa gộp chuyên đề',null,'mergesubject', 3],
            ['mergesubject-approved','Duyệt/Từ chối gộp chuyên đề',null,'mergesubject', 3],
            ['mergesubject-delete','Xóa gộp chuyên đề',null,'mergesubject', 3],

            ['splitsubject','Tách chuyên đề','el_merge_subject',null, 3],
            ['splitsubject-create','Thêm tách chuyên đề',null,'splitsubject', 3],
            ['splitsubject-edit','Chỉnh sửa tách chuyên đề',null,'splitsubject', 3],
            ['splitsubject-approved','Duyệt/Từ chối tách chuyên đề',null,'splitsubject', 3],
            ['splitsubject-delete','Xóa tách chuyên đề',null,'splitsubject', 3],

            ['subjectcomplete','Hoàn thành quá trình đào tạo',1,null, 3],
            ['subjectcomplete-edit','Chỉnh sửa Hoàn thành quá trình đào tạo nhân viên',null,'subjectcomplete', 3],
            ['subjectcomplete-choose-subject','Chọn chuyên đề Hoàn thành quá trình đào tạo',null,'subjectcomplete', 3],
            ['subjectcomplete-watch-log','Xem logs Hoàn thành quá trình đào tạo',null,'subjectcomplete', 3],
            ['subjectcomplete-approved','Duyệt Hoàn thành quá trình đào tạo',null,'subjectcomplete', 3],
            ['subjectcomplete-import','Thêm Hoàn thành quá trình đào tạo',null,'subjectcomplete', 3],

            ['movetrainingprocess','Chuyển quá trình đào tạo','el_move_training_process',null, 3],
            ['movetrainingprocess-move','Chuyển quá trình đào tạo',null,'movetrainingprocess', 3],
            ['movetrainingprocess-edit','Sửa chuyển quá trình đào tạo',null,'movetrainingprocess', 3],
            ['movetrainingprocess-watch-log','Xem logs Chuyển quá trình đào tạo',null,'movetrainingprocess', 3],
            ['movetrainingprocess-approved','Duyệt/Từ chối Chuyển quá trình đào tạo',null,'movetrainingprocess', 3],
            ['movetrainingprocess-delete','Xóa Chuyển quá trình đào tạo',null,'movetrainingprocess', 3],

            ['subjectregister','Chuyên đề đã đăng ký','el_subject_register',null, 3],
            ['subjectregister-watch','Xem Chuyên đề đã đăng ký',null,'subjectregister', 3],

            ['note','Xem Ghi chú','el_note',null, 3],
            ['note-watch','Xem Ghi chú',null,'note', 3],

            ['course-plan','Kế hoạch đào tạo','el_course_plan',null, 3],
            ['course-plan-create','Thêm mới Kế hoạch đào tạo',null,'course-plan', 3],
            ['course-plan-edit','Sửa Kế hoạch đào tạo',null,'course-plan', 3],
            ['course-plan-approved','Duyệt/Từ chối Kế hoạch đào tạo',null,'course-plan', 3],
            ['course-plan-delete','Xóa Kế hoạch đào tạo',null,'course-plan', 3],
            ['course-plan-create-object','Thêm đối tượng Kế hoạch đào tạo',null,'course-plan', 3],
            ['course-plan-delete-object','Xóa đối tượng Kế hoạch đào tạo',null,'course-plan', 3],
            ['course-plan-create-cost','Thêm chi phí Kế hoạch đào tạo',null,'course-plan', 3],
            ['course-plan-create-schedule','Thêm Lịch học Kế hoạch đào tạo',null,'course-plan', 3],
            ['course-plan-delete-schedule','Xóa chi phí Kế hoạch đào tạo',null,'course-plan', 3],
            ['course-plan-create-condition','Thêm điều kiện Kế hoạch đào tạo',null,'course-plan', 3],
            ['course-plan-add-teacher','Thêm giảng viên Kế hoạch đào tạo',null,'course-plan', 3],
            ['course-plan-delete-teacher','Xóa giảng viên Kế hoạch đào tạo',null,'course-plan', 3],

            ['topic','Xử lý tình huống','el_topic',null, 9],
            ['topic-create','Thêm mới Xử lý tình huống',null,'topic', 9],
            ['topic-edit','Sửa Xử lý tình huống',null,'topic', 9],
            ['topic-delete','Xóa Xử lý tình huống',null,'topic', 9],
            ['topic-isopen','Bật/tắt Xử lý tình huống',null,'topic', 9],

            ['situation','Thảo luận Tình huống','el_situation',null, 9],
            ['situation-create','Thêm mới Thảo luận Tình huống',null,'situation', 9],
            ['situation-edit','Sửa Thảo luận Tình huống',null,'situation', 9],
            ['situation-delete','Xóa Thảo luận Tình huống',null,'situation', 9],

            ['user-take-leave','Nhân viên nghỉ phép','el_profile_take_leave',null, 8],
            ['user-take-leave-create','Thêm mới Nhân viên nghỉ phép',null,'user-take-leave', 8],
            ['user-take-leave-edit','Sửa Nhân viên nghỉ phép',null,'user-take-leave', 8],
            ['user-take-leave-delete','Xóa Nhân viên nghỉ phép',null,'user-take-leave', 8],

            ['training-by-title','Lộ trình đào tạo','el_training_by_title',null, 13],
            ['training-by-title-create','Thêm mới chức danh',null,'training-by-title', 13],
            ['training-by-title-delete','Xóa chức danh',null,'training-by-title', 13],
            ['training-by-title-detail-create','Thêm chuyên đề theo chức danh',null,'training-by-title', 13],
            ['training-by-title-detail-edit','Sửa chuyên đề theo chức danh',null,'training-by-title', 13],
            ['training-by-title-detail-delete','Xóa chuyên đề theo chức danh',null,'training-by-title', 13],
            ['training-by-title-result','Kết quả lộ trình đào tạo',null,'training-by-title', 13],

            ['approved-process','Phân quyền phê duyệt','el_approved_process',null, 1],
            ['approved-process-create','Thêm mới',null,'approved-process', 1],
            ['approved-process-edit','Sửa',null,'approved-process', 1],
            ['approved-process-delete','Xóa',null,'approved-process', 1],

            ['warehouse','Quản lý media file','el_warehouse',null, 19],
            ['warehouse-create','Thêm mới',null,'warehouse', 19],
            ['warehouse-edit','Sửa',null,'warehouse', 19],
            ['warehouse-delete','Xóa',null,'warehouse', 19],

            ['warehouse-folder','Quản lý media file (folder)','el_warehouse_folder',null, 19, 'el_warehouse'], // extend từ el_warehouse

            ['log-view-course','Lịch sử truy cập khóa học','el_log_view_course',null, 3],
            ['model-history','Lịch sử cập nhật','el_model_history',null, 3],

            ['api-manual','Quản lý API (IHRP)','el_api',null, 19],
            ['api-manual-sync','Cập nhật',null,'el_api', 19],

            ['unit-manager-setting','Setup trưởng đơn vị tự động','el_unit_manager_setting',null, 9],
            ['unit-manager-setting-create','Thêm mới',null,'unit-manager-setting', 9],
            ['unit-manager-setting-edit','Sửa',null,'unit-manager-setting', 9],
            ['unit-manager-setting-delete','Xóa',null,'unit-manager-setting', 9],
            ['unit-manager-setting-import','Import',null,'unit-manager-setting', 9],

            ['course-old','Khóa học cũ','el_course_old',null, 3],
            ['course-old-delete','Xóa Khóa học cũ',null,'course-old', 3],
            ['course-old-import','Import Khóa học cũ',null,'course-old', 3],
            ['course-old-export', 'Export Khóa học cũ',null,'course-old', 3],

            ['mail-template-history','Lịch sử mail','el_mail_history',null, 19],

            ['contact','Liên hệ','el_contact',null, 19],
            ['contact-create','Thêm mới',null,'contact', 19],
            ['contact-edit','Sửa',null,'contact', 19],
            ['contact-delete','Xóa',null,'contact', 19],

            ['google-map','Địa điểm đào tạo','el_boxmaps',null, 19],
            ['google-map-create','Thêm mới',null,'google-map', 19],
            ['google-map-edit','Sửa',null,'google-map', 19],
            ['google-map-delete','Xóa',null,'google-map', 19],

            ['infomation-company','Thông tin công ty','el_infomation_company',null, 19],
            ['infomation-company-create','Thêm mới',null,'infomation-company', 19],

            ['setting-color','Cài đặt màu','el_setting_color',null, 19],
            ['setting-color-create','Thêm mới',null,'setting-color', 19],

            ['languages','Cài đặt ngôn ngữ','el_languages',null, 19],
            ['languages-create','Thêm mới',null,'languages', 19],

            ['setting-time','Cài đặt Thời gian','el_setting_time',null, 19],
            ['setting-time-create','Thêm mới',null,'setting-time', 19],
            ['setting-time-edit','Sửa',null,'setting-time', 19],
            ['setting-time-delete','Xóa',null,'setting-time', 19],

            ['setting-experience-navigate','Cài đặt điều hướng trải nghiệm','el_setting_experience_navigate',null, 19],
            ['setting-experience-navigate-create','Thêm mới',null,'setting-experience-navigate', 19],
            ['setting-experience-navigate-edit','Sửa',null,'setting-experience-navigate', 19],
            ['setting-experience-navigate-delete','Xóa',null,'setting-experience-navigate', 19],
            ['setting-experience-navigate-object-create','Thêm mới đối tượng',null,'setting-experience-navigate', 19],
            ['setting-experience-navigate-object-delete','Xóa đối tượng',null,'setting-experience-navigate', 19],
            ['setting-experience-navigate-name-edit','Chỉnh sửa tên',null,'setting-experience-navigate', 19],

            ['dashboard-by-user','Thống kê người dùng','el_dashboard_by_user',null, 9],
            ['dashboard-by-user-edit','Chỉnh sửa',null,'dashboard-by-user', 9],

            ['interaction-history-clear','Xóa Lịch sử tương tác','el_interaction_history_clear',null, 9],
            ['interaction-history-clear-create','Thêm',null,'interaction-history-clear', 9],

            ['course-educate-plan','Kế hoạch tự đào tạo','el_course_educate_plan',null, 3],
            ['course-educate-plan-create','Thêm mới',null,'course-educate-plan', 3],
            ['course-educate-plan-edit','Sửa',null,'course-educate-plan', 3],
            ['course-educate-plan-delete','Xóa',null,'course-educate-plan', 3],
            ['course-educate-plan-object-create','Thêm mới đối tượng',null,'course-educate-plan', 3],
            ['course-educate-plan-object-delete','Xóa đối tượng',null,'course-educate-plan', 3],

            ['promotion-history','Lịch sử điểm','el_userpoint_result',null, 9],

            ['coaching-group','Nhóm coaching','el_coaching_group',null, 9],
            ['coaching-group-create','Thêm mới',null,'coaching-group', 9],
            ['coaching-group-edit','Sửa',null,'coaching-group', 9],
            ['coaching-group-remove','Xóa',null,'coaching-group', 9],

            ['coaching-mentor-method','Phương pháp kèm cặp','el_coaching_mentor_method',null, 9],
            ['coaching-mentor-method-create','Thêm mới',null,'coaching-mentor-method', 9],
            ['coaching-mentor-method-edit','Sửa',null,'coaching-mentor-method', 9],
            ['coaching-mentor-method-remove','Xóa',null,'coaching-mentor-method', 9],

            ['coaching-teacher','Giảng viên Coaching','el_coaching_teacher',null, 9],

            ['target-manager-parent','Quản lý chỉ tiêu','el_target_manager_parent',null, 3],
            ['target-manager-parent-create','Thêm mới Quản lý chỉ tiêu',null,'target-manager-parent', 3],
            ['target-manager-parent-edit','Sửa Quản lý chỉ tiêu',null,'target-manager-parent', 3],
            ['target-manager-parent-delete','Xóa Quản lý chỉ tiêu',null,'target-manager-parent', 3],

            ['target-manager','Nhóm quản lý chỉ tiêu','el_target_manager',null, 3],
            ['target-manager-create','Thêm mới Nhóm quản lý chỉ tiêu',null,'target-manager', 3],
            ['target-manager-edit','Sửa Nhóm quản lý chỉ tiêu',null,'target-manager', 3],
            ['target-manager-delete','Xóa Nhóm quản lý chỉ tiêu',null,'target-manager', 3],
            ['target-manager-copoy','Sao chép Nhóm quản lý chỉ tiêu',null,'target-manager', 3],

            ['menu-setting','Thết lập Menu','menu_setting',null, 19],
            ['menu-setting-save','Lưu Thết lập Menu',null,'menu-setting', 19],
            ['menu-setting-delete','Xóa Thết lập Menu',null,'menu-setting', 19],

            ['training-teacher-register','Đăng ký giảng viên','el_training_teacher_register_schedule',null, 3],
            ['training-teacher-register-delete','Xóa',null,'training-teacher-register', 3],
            ['training-teacher-register-approve','Phê duyệt',null,'training-teacher-register', 3],

            ['certificate-template-kpi','Mẫu KPI','el_kpi_template',null, 3],
            ['certificate-template-kpi-create','Thêm mới',null,'certificate-template-kpi', 3],
            ['certificate-template-kpi-edit','Chỉnh sửa',null,'certificate-template-kpi', 3],
            ['certificate-template-kpi-delete','Xoá',null,'certificate-template-kpi', 3],

            ['emulation-badge','Huy hiệu thi đua','emulation_badge',null, 3],
            ['emulation-badge-create','Thêm mới',null,'emulation-badge', 3],
            ['emulation-badge-edit','Chỉnh sửa',null,'emulation-badge', 3],
            ['emulation-badge-delete','Xoá',null,'emulation-badge', 3],

            ['saleskit-category','Danh mục Sales Kit','el_sales_kit_category',null, 22],
            ['saleskit-category-create','Thêm danh mục Sales Kit',null,'saleskit-category', 22],
            ['saleskit-category-edit','Chỉnh sửa danh mục Sales Kit',null,'saleskit-category', 22],
            ['saleskit-category-delete', 'Xoá danh mục Sales Kit',null,'saleskit-category', 22],

            ['saleskit','Sales Kit','el_sales_kit',null, 22],
            ['saleskit-create','Thêm Sales Kit',null,'saleskit', 22],
            ['saleskit-edit','Chỉnh sửa Sales Kit',null,'saleskit', 22],
            ['saleskit-delete', 'Xoá Sales Kit',null,'saleskit', 22],

            ['training-calendar', 'Lịch đào tạo', 'el_course_view', null, 3],
        ];

        // \DB::table('el_permissions')->delete();
        // \DB::table('el_permissions')->truncate();
        foreach ($permissions as $key => $value) {
            $extend = isset($value[5]) ? $value[5] : null;
            $permission = Permission::query()->updateOrCreate(
                [
                    'name' => $value[0],
                ],
                [
                    'name' => $value[0],
                    'description' => $value[1],
                    'model' => $value[2],
                    'parent' => $value[3],
                    'group' => $value[4],
                    'extend' => $extend,
                ]
            );
            \DB::table('el_role_has_permissions')->updateOrInsert(
                [
                    'permission_id' => $permission->id,
                    'role_id' => 6,
                ],
                [
                    'permission_id' => $permission->id,
                    'role_id' => 6,
                ]
            );
            if (is_null($value[3])) {
                \DB::table('el_role_permission_type')->updateOrInsert(
                    [
                        'permission_id' => $permission->id,
                        'role_id' => 6,
                        'permission_type_id' => 6,
                    ],
                    [
                        'permission_id' => $permission->id,
                        'role_id' => 6,
                        'permission_type_id' => 6,
                    ]
                );
            }
        }
    }
}
