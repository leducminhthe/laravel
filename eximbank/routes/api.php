<?php

use Illuminate\Http\Request;
use Orion\Facades\Orion;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});
Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('login', 'Auth\AuthAPIController@login');
    Route::post('logout', 'Auth\AuthAPIController@logout');
    Route::post('refresh', 'Auth\AuthAPIController@refresh');
    Route::post('me', 'Auth\AuthAPIController@me');
});
Route::prefix('v1')->group(function (){
//    Route::resource('unit','Api\UnitController')->except(['create','edit']);
    Orion::resource('profile','Api\ProfileController');
    Orion::resource('subject','Api\SubjectController');
    Orion::resource('area','Api\AreaController');
    Orion::resource('title','Api\TitleController');
    Orion::resource('position','Api\PositionController');
    Orion::resource('absent','Api\Absent\AbsentController');
    Orion::resource('absent_reason','Api\AbsentReason\AbsentReasonController');
    Orion::resource('commit_group','Api\CommitGroup\CommitGroupController');
    Orion::resource('commitment_title','Api\CommitMentTitle\CommitMentTitleController');
    Orion::resource('commitment','Api\CommitMonth\CommitMonthController');
    Orion::resource('discipline','Api\Discipline\DisciplineController');
    Orion::resource('province','Api\Province\ProvinceController');
    Orion::resource('district','Api\District\DistrictController');
    Orion::hasManyResource('province', 'district', 'Api\Province\ProvinceDistrictController');
    Orion::belongsToResource('district', 'province', 'Api\District\DistrictProvinceController');
    Orion::resource('level_subject', 'Api\LevelSubject\LevelSubjectController');
    Orion::resource('student_cost', 'Api\StudentCost\StudentCostController');
    Orion::resource('teacher_type', 'Api\TeacherType\TeacherTypeController');
    Orion::resource('title_rank', 'Api\TitleRank\TitleRankController');
    Orion::resource('training_cost', 'Api\TrainingCost\TrainingCostController');
    Orion::resource('training_form', 'Api\TrainingForm\TrainingFormController');
    Orion::resource('training_location', 'Api\TrainingLocation\TrainingLocationController');
    Orion::resource('training_object', 'Api\TrainingObject\TrainingObjectController');
    Orion::resource('training_partner', 'Api\TrainingPartner\TrainingPartnerController');
    Orion::resource('training_program', 'Api\TrainingProgram\TrainingProgramController');
    Orion::hasManyResource('training_program', 'subject', 'Api\TrainingProgram\TrainingProgramSubjectController');
    Orion::resource('training_teacher', 'Api\TrainingTeacher\TrainingTeacherController');
    Orion::resource('training_type', 'Api\TrainingType\TrainingTypeController');
    Orion::resource('unit_type_code', 'Api\UnitTypeCode\UnitTypeCodeController');
    Orion::hasManyResource('unit_type', 'unit_type_code', 'Api\UnitType\UnitTypeUnitTypeCodeController');
    Orion::belongsToResource('unit_type_code', 'unit_type', 'Api\UnitTypeCode\UnitTypeCodeUnitTypeController');
    Orion::resource('advertising_photo', 'Api\AdvertisingPhoto\AdvertisingPhotoController');
    Orion::resource('app_mobile', 'Api\AppMobile\AppMobileController');
    Orion::resource('boxmaps', 'Api\Boxmap\BoxmapController');
    Orion::resource('cert', 'Api\Cert\CertController'); //Này là danh mục Trình độ
    Orion::resource('con_fig', 'Api\Config\ConfigController'); //Đổi tên gọi route do trùng giá trị mặc định config
    Orion::resource('contact', 'Api\Contact\ContactController');
    Orion::resource('cost_lessons', 'Api\CostLessons\CostLessonsController');
    Orion::resource('donate_points', 'Api\DonatePoints\DonatePointsController');
    Orion::resource('feedback', 'Api\Feedback\FeedbackController');
    Orion::resource('footer', 'Api\Footer\FooterController');
    Orion::resource('guide', 'Api\Guide\GuideController');
    Orion::resource('infomation_company', 'Api\InfomationCompany\InfomationCompanyController');
    Orion::resource('login_image', 'Api\LoginImage\LoginImageController');
    Orion::resource('logo', 'Api\Logo\LogoController');
    Orion::resource('mail_signature', 'Api\MailSignature\MailSignatureController');
    Orion::resource('mail_template', 'Api\MailTemplate\MailTemplateController');
    Orion::resource('note', 'Api\Note\NoteController');
    Orion::resource('permission_group', 'Api\PermissionGroup\PermissionGroupController');
    Orion::resource('permission_type', 'Api\PermissionType\PermissionTypeController');
    Orion::resource('roles', 'Api\Role\RoleController');
    Orion::resource('setting_time', 'Api\SettingTime\SettingTimeController');
    Orion::resource('setting_time_object', 'Api\SettingTimeObject\SettingTimeObjectController');
    Orion::resource('slider', 'Api\Slider\SliderController');
    Orion::resource('slider_outside', 'Api\SliderOutside\SliderOutsideController');
    Orion::resource('type_cost', 'Api\TypeCost\TypeCostController');
    Orion::resource('user_contact', 'Api\UserContactOutside\UserContactOutsideController');
    Orion::resource('career_roadmap', 'Api\CareerRoadmap\CareerRoadmapController');
    Orion::resource('career_roadmap_titles', 'Api\CareerRoadmapTitle\CareerRoadmapTitleController');
    Orion::hasManyResource('career_roadmap', 'career_roadmap_titles', 'Api\CareerRoadmap\CareerRoadmapCareerRoadmapTitleController');
    Orion::resource('career_roadmap_user', 'Api\CareerRoadmapUser\CareerRoadmapUserController');
    Orion::resource('career_roadmap_titles_user', 'Api\CareerRoadmapTitleUser\CareerRoadmapTitleUserController');
    Orion::hasManyResource('career_roadmap_user', 'career_roadmap_titles_user', 'Api\CareerRoadmapUser\CareerRoadmapUserCareerRoadmapTitleUserController');
    Orion::resource('certificate', 'Api\Certificate\CertificateController');
    Orion::resource('course_educate_plan', 'Api\CourseEducatePlan\CourseEducatePlanController');
    Orion::resource('course_educate_plan_condition', 'Api\CourseEducatePlanCondition\CourseEducatePlanConditionController');
    Orion::resource('course_educate_plan_cost', 'Api\CourseEducatePlanCost\CourseEducatePlanCostController');
    Orion::resource('course_educate_plan_object', 'Api\CourseEducatePlanObject\CourseEducatePlanObjectController');
    Orion::resource('course_educate_plan_schedule', 'Api\CourseEducatePlanSchedule\CourseEducatePlanScheduleController');
    Orion::resource('course_educate_plan_teacher', 'Api\CourseEducatePlanTeacher\CourseEducatePlanTeacherController');
    Orion::resource('course_old', 'Api\CourseOld\CourseOldController');
    Orion::resource('course_plan', 'Api\CoursePlan\CoursePlanController');
    Orion::resource('course_plan_condition', 'Api\CoursePlanCondition\CoursePlanConditionController');
    Orion::resource('course_plan_cost', 'Api\CoursePlanCost\CoursePlanCostController');
    Orion::resource('course_plan_object', 'Api\CoursePlanObject\CoursePlanObjectController');
    Orion::resource('course_plan_schedule', 'Api\CoursePlanSchedule\CoursePlanScheduleController');
    Orion::resource('course_plan_teacher', 'Api\CoursePlanTeacher\CoursePlanTeacherController');
    Orion::resource('daily_training_category', 'Api\DailyTrainingCategory\DailyTrainingCategoryController');
    Orion::resource('daily_training_per_user_cate', 'Api\DailyTrainingPermissionUserCategory\DailyTrainingPermissionUserCategoryController');
    Orion::resource('daily_training_set_score_cmt', 'Api\DailyTrainingSettingScoreComment\DailyTrainingSettingScoreCommentController');
    Orion::resource('daily_training_set_score_like', 'Api\DailyTrainingSettingScoreLike\DailyTrainingSettingScoreLikeController');
    Orion::resource('daily_training_set_score_views', 'Api\DailyTrainingSettingScoreViews\DailyTrainingSettingScoreViewsController');
    Orion::resource('daily_training_user_cmt_video', 'Api\DailyTrainingUserCommentVideo\DailyTrainingUserCommentVideoController');
    Orion::resource('daily_train_user_like_cmt_video', 'Api\DailyTrainingUserLikeCommentVideo\DailyTrainingUserLikeCommentVideoController');
    Orion::resource('daily_training_user_like_video', 'Api\DailyTrainingUserLikeVideo\DailyTrainingUserLikeVideoController');
    Orion::resource('daily_training_user_view_video', 'Api\DailyTrainingUserViewVideo\DailyTrainingUserViewVideoController');
    Orion::resource('daily_training_video', 'Api\DailyTrainingVideo\DailyTrainingVideoController');
    Orion::resource('faq', 'Api\FAQ\FAQController');
    Orion::resource('filter_word', 'Api\FilterWord\FilterWordController');
    Orion::resource('forum_category', 'Api\ForumCategory\ForumCategoryController');
    Orion::hasManyResource('forum_category', 'forum', 'Api\ForumCategory\ForumCategoryForumController');
    Orion::resource('forum_category_permission', 'Api\ForumCategoryPermission\ForumCategoryPermissionController');
    Orion::resource('forum', 'Api\Forum\ForumController');
    Orion::resource('forum_thread', 'Api\ForumThread\ForumThreadController');
    Orion::resource('forum_comment', 'Api\ForumComment\ForumCommentController');
    Orion::resource('forum_user_like_comment', 'Api\ForumUserLikeComment\ForumUserLikeCommentController');
});

