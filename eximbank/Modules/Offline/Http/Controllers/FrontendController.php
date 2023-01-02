<?php

namespace Modules\Offline\Http\Controllers;

use App\Events\Offline\GoActivity;
use Carbon\Carbon;
use App\Models\Automail;
use App\Models\Config;
use App\Models\CourseBookmark;
use App\Models\Categories\Area;
use App\Models\Categories\LevelSubject;
use App\Models\Categories\StudentCost;
use App\Models\Categories\Unit;
use App\Models\Categories\UnitManager;
use App\Models\Permission;
use App\Models\PermissionTypeUnit;
use App\Models\ProfileView;
use App\Scopes\DraftScope;
use App\Models\Slider;
use App\Models\Categories\Subject;
use App\Models\Categories\TrainingProgram;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Modules\AppNotification\Helpers\AppNotification;
use Modules\Indemnify\Entities\Indemnify;
use Modules\LogViewCourse\Entities\LogViewCourse;
use Modules\Notify\Entities\Notify;
use Modules\Offline\Entities\OfflineCourse;
use Modules\Offline\Entities\OfflineCourseActivity;
use Modules\Offline\Entities\OfflineCourseActivityTeams;
use Modules\Offline\Entities\OfflineCourseActivityZoom;
use Modules\Offline\Entities\OfflineRating;
use Modules\Offline\Entities\OfflineRatingLevel;
use Modules\Offline\Entities\OfflineRatingLevelObject;
use Modules\Offline\Entities\OfflineRegister;
use Modules\Offline\Entities\OfflineComment;
use Modules\Offline\Entities\OfflineObject;
use Modules\Offline\Entities\OfflineCourseComplete;
use Illuminate\Support\Facades\Auth;
use Modules\Offline\Entities\OfflineResult;
use Modules\Offline\Entities\OfflineStudentCostByUser;
use Modules\Offline\Entities\OfflineTeacher;
use Modules\PointHist\Entities\PointHist;
use Modules\Promotion\Entities\PromotionCourseSetting;
use Modules\Promotion\Entities\PromotionLevel;
use Modules\Promotion\Entities\PromotionShare;
use Modules\Promotion\Entities\PromotionUserHistory;
use Modules\Promotion\Entities\PromotionUserPoint;
use Modules\Rating\Entities\RatingCourse;
use App\Models\Profile;
use Modules\Rating\Entities\RatingLevelCourse;
use Modules\RefererHist\Entities\RefererRegisterCourse;
use Illuminate\Support\Facades\Crypt;
use Modules\User\Entities\TrainingProcess;
use App\Models\Categories\Titles;
use App\Models\Categories\TrainingForm;
use App\Models\InteractionHistory;
use App\Models\UserViewCourse;
use Modules\Offline\Entities\OfflineCourseClass;
use Modules\Quiz\Entities\QuizPart;
use Modules\Quiz\Entities\QuizRegister;
use Modules\Quiz\Entities\QuizUserSecondary;
use Modules\UserPoint\Entities\UserPointResult;
use Modules\UserPoint\Entities\UserPointSettings;
use Modules\Online\Entities\OnlineCourse;
use Modules\Online\Entities\OnlineRegister;
use Modules\Quiz\Entities\Quiz;
use App\Events\SaveTrainingProcessRegister;
use App\Models\Categories\Province;
use App\Models\Categories\District;
use App\Models\Categories\TrainingLocation;
use App\Models\Categories\TrainingTeacher;
use Modules\Notify\Entities\NotifyTemplate;
use Modules\Offline\Entities\OfflineAttendance;
use Modules\Offline\Entities\OfflineTeacherClass;
use Modules\Offline\Entities\OfflineTeachingOrganizationTemplate;
use Modules\Offline\Entities\OfflineTeachingOrganizationCategory;
use Modules\Offline\Entities\OfflineTeachingOrganizationQuestion;
use Modules\Offline\Entities\OfflineTeachingOrganizationAnswer;
use Modules\Offline\Entities\OfflineTeachingOrganizationAnswerMatrix;
use Modules\Offline\Entities\OfflineTeachingOrganizationUser;
use Modules\Offline\Entities\OfflineTeachingOrganizationUserAnswer;
use Modules\Offline\Entities\OfflineTeachingOrganizationUserAnswerMatrix;
use Modules\Offline\Entities\OfflineTeachingOrganizationUserCategory;
use Modules\Offline\Entities\OfflineTeachingOrganizationUserQuestion;
use Modules\PermissionApproved\Entities\PermissionApprovedUser;
use App\Models\Categories\TrainingTeacherStar;
use App\Models\SubjectPrerequisiteCourse;
use Modules\Offline\Entities\OfflineCondition;
use Modules\Offline\Entities\OfflineCourseActivityCompletion;
use Modules\Offline\Entities\OfflineCourseActivityFile;
use Modules\Offline\Entities\OfflineCourseActivityScorm;
use Modules\Offline\Entities\OfflineCourseActivityUrl;
use Modules\Offline\Entities\OfflineCourseActivityVideo;
use Modules\Offline\Entities\OfflineCourseActivityXapi;
use Modules\Offline\Entities\OfflineCourseTimeUserLearn;
use Modules\Offline\Entities\OfflineFinishVideo;
use Modules\Offline\Entities\OfflineNewTeacher;
use Modules\Offline\Entities\OfflineViewActivity;
use Modules\User\Entities\UserCompletedSubject;
use Modules\Offline\Entities\OfflineSchedule;
use Modules\Offline\Entities\OfflineActivityQuiz;
use Modules\Offline\Entities\OfflineCourseLesson;
use Modules\Offline\Entities\OfflineCourseDocument;
use Modules\Offline\Entities\OfflineCourseActivityCondition;
use Modules\Offline\Entities\OfflineCourseActivitySurvey;
use Modules\Offline\Entities\OfflineSurveyQuestion;
use Modules\Offline\Entities\OfflineSurveyTemplate;
use Modules\Offline\Entities\OfflineSurveyUser;
use Modules\Offline\Entities\OfflineSurveyUserAnswer;
use Modules\Offline\Entities\OfflineSurveyUserAnswerMatrix;
use Modules\Offline\Entities\OfflineSurveyUserCategory;
use Modules\Offline\Entities\OfflineSurveyUserQuestion;

class FrontendController extends Controller
{
    public function index(Request $request)
    {
        $items = $this->getItems($request);
        $text_status = function ($status) {
            return OfflineCourse::getStatusRegisterText($status);
        };
        $class_status = function ($status) {
            return OfflineCourse::getBtnClassStatusRegister($status);
        };
        $sliders = Slider::where('status', '=', 1)
            ->where('location', '=', 'offline')
            ->get();
        $check_bookmarks = function ($course_id, $course_type){
            return CourseBookmark::checkExist($course_id, $course_type);
        };

        return view('offline::frontend.index', [
            'items' => $items,
            'sliders' => $sliders,
            'text_status' => $text_status,
            'class_status' => $class_status,
            'check_bookmarks' => $check_bookmarks
        ]);
    }

    public function getItems(Request $request) {
        $search = $request->get('q');
        $fromdate = $request->get('fromdate');
        $todate = $request->get('todate');
        $training_program_id = $request->get('training_program_id');
        $subject_id = $request->get('subject_id');
        $status = $request->get('status');
        $user_id = profile()->user_id;
        $status = $request->get('status');

        $profile = profile();
        $unit_user = Unit::getTreeParentUnit($profile->unit_code);

        $query = OfflineCourse::query();
        $query->select(['a.*']);
        $query->from('el_offline_course as a');
        if($status && $status !== 5) {
            $query->leftjoin('el_offline_register as b','b.course_id','=','a.id');
        }
        $query->where('a.status', '=', 1);
        $query->where('a.isopen', '=', 1);

        if (!Permission::isAdmin() && !$status){
            $query->orWhereNull('unit_id');
            $query->where(function ($sub) use ($unit_user){
                $sub->whereNotNull('unit_id');
                foreach ($unit_user as $item){
                    $sub->orWhere('unit_id', 'like', '%'.$item->id.'%');
                }
            });
        }

        $get_course_id_register = OfflineRegister::where('user_id',$user_id)->pluck('course_id')->toArray();
        $get_course_id_complete = OfflineCourseComplete::where('user_id',$user_id)->pluck('course_id')->toArray();

        if($status && $status == 1) {
            $query->whereNotIn('a.id', $get_course_id_register);
            $query->where(function ($sub){
                $sub->orWhere('end_date', '>', date('Y-m-d'));
            });
        } elseif($status && $status == 2) {
            $query->where('b.user_id', $user_id);
            $query->where('b.status',1);
        } elseif($status && $status == 3) {
            $query->where('b.user_id', $user_id);
            $query->where('b.status',2);
        } elseif($status && $status == 4) {
            $query->leftjoin('el_offline_course_complete as c','c.course_id','=','a.id');
            $query->whereIn('a.id',$get_course_id_complete);
        } elseif($status && $status == 5) {
            $query->where('a.end_date', '<=', date('Y-m-d'));
        }

        if ($search) {
            $query->where(function ($subquery) use ($search) {
                $subquery->orWhere('a.code', 'like', '%'. $search .'%');
                $subquery->orWhere('a.name', 'like', '%'. $search .'%');
            });
        }

        if ($fromdate) {
            $query->where('a.start_date', '>=', date_convert($fromdate, '00:00:00'));
        }

        if ($todate) {
            $query->where('a.start_date', '<=', date_convert($todate, '23:59:59'));
        }

        if ($training_program_id) {
            $query->where('a.training_program_id', '=', $training_program_id);
        }

        if ($subject_id) {
            $query->where('a.subject_id', '=', $subject_id);
        }

        $query->orderByDesc('a.id');
        $items = $query->paginate(20);
        $items->appends($request->query());

        return $items;
    }

    public function detailFirst($id, Request $request){
        $user_id = profile()->user_id;

        $item = OfflineCourse::where('id', '=', $id)->where('status', '=', 1)->where('isopen', '=', 1)->firstOrFail();
        OfflineCourse::updateItemViews($id, $item->views);
        if($item->image) {
            $item->image = 'detail/'. $item->image;
        }
        $register = OfflineRegister::where('user_id', '=', $user_id)->where('course_id', '=', $id)->where('status', '=', 1)->first();
        $subject_prerequisite_course = SubjectPrerequisiteCourse::where(['course_id' => $id, 'course_type' => 2])->first();
        $comments = OfflineComment::select([
            'a.*',
            \DB::raw("CONCAT_WS(' ',lastname, firstname) AS fullname"),
            'b.avatar',
        ])
            ->from('el_offline_comment AS a')
            ->leftJoin('el_profile AS b', 'b.user_id', '=', 'a.user_id')
            ->where('a.course_id', '=', $id)
            ->orderBy('a.id', 'desc')
            ->get();

        $condition = OfflineCondition::where('course_id', '=', $id)->first();
        $offline_class = OfflineCourseClass::whereCourseId($id);
        if($register){
            $offline_class = $offline_class->where('id', $register->class_id)->first();
        }else{
            $offline_class = $offline_class->get();
        }

        $offline_rating_level = OfflineRatingLevel::query()
            ->whereIn('id', function ($sub) use ($id){
                $sub->select(['offline_rating_level_id'])
                    ->from('el_offline_rating_level_object')
                    ->where('course_id', '=', $id)
                    ->where('object_type', '=', 1)
                    ->pluck('offline_rating_level_id')
                    ->toArray();
            })
            ->where('course_id', '=', $id)->first();

        $go_quiz_url = '';
        $closed_quiz = 0;
        if($item->quiz_id){
            $quiz_register = QuizRegister::whereQuizId($item->quiz_id)
                ->where('user_id', '=', $user_id)
                ->where('type', '=', 1)
                ->first();
            if($quiz_register){
                $quiz_part = QuizPart::whereQuizId($item->quiz_id)
                ->where('id', $quiz_register->part_id)
                ->first();
                if ($quiz_part->end_date && $quiz_part->end_date < date('Y-m-d H:i:s')){
                    $closed_quiz = 1;
                }

                if ($quiz_part->start_date <= date('Y-m-d H:i:s') || $closed_quiz == 0){
                    $go_quiz_url = route('module.quiz.doquiz.index', [
                        'quiz_id' => $item->quiz_id,
                        'part_id' => $quiz_part->id,
                    ]);
                }
            }
        }

        $go_organization_user = '';
        if($item->template_rating_teacher_id){
            $organization_user = OfflineTeachingOrganizationUser::where('course_id', $id)->where('user_id', profile()->user_id)->first();
            if($organization_user){
                $go_organization_user = route('module.offline.edit_rating_teaching_organization', [$id]);
            }else{
                $go_organization_user = route('module.offline.rating_teaching_organization', [$id]);
            }
        }

        $offline_course_document = OfflineCourseDocument::where('course_id', $id)->exists();

        return view('offline::frontend.detail_first', [
            'item' => $item,
            'register' => $register,
            'subject_prerequisite_course' => $subject_prerequisite_course,
            'comments' => $comments,
            'condition' => $condition,
            'offline_class' => $offline_class,
            'offline_rating_level' => $offline_rating_level,
            'go_quiz_url' => $go_quiz_url,
            'go_organization_user' => $go_organization_user,
            'offline_course_document' => $offline_course_document,
        ]);
    }
    public function ajaxRatingLevelOffline($course_id, Request $request) {

        return view('offline::modal.modal_rating_level',[
            'course_id' => $course_id
        ]);
    }

    public function getDataSchedule($course_id, Request $request) {
        $user_id = profile()->user_id;
        $register = OfflineRegister::where('user_id', '=', $user_id)->where('course_id', '=', $course_id)->where('status', '=', 1)->first();
        $offline_class = OfflineCourseClass::whereCourseId($course_id)->where('default', 1)->first(['id']);

        $class_id = $request->class_id;

        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = OfflineSchedule::query();
        $query->where('course_id', $course_id);
        if($register){
            $query->where('class_id', $register->class_id);
        }elseif($class_id){
            $query->where('class_id', $class_id);
        }else{
            $query->where('class_id', $offline_class->id);
        }

        $count = $query->count();
        $query->orderBy('session', 'asc');
        $query->offset($offset);
        $query->limit($limit);

        $day_arr = [
            0 => 'CN',
            1 => 'T2',
            2 => 'T3',
            3 => 'T4',
            4 => 'T5',
            5 => 'T6',
            6 => 'T7',
        ];

        $rows = $query->get();
        foreach ($rows as $row) {
            $row->link_teams = '';
            $row->link_elearning = '';

            switch($row->type_study){
                case 1: $row->text_type_study = 'Học tại lớp'; break;
                case 2: $row->text_type_study = 'Học qua MS Teams'; break;
                case 3: $row->text_type_study = 'Học Elearning'; break;
                default: $row->text_type_study = ''; break;
            }

            $wday = getdate(strtotime($row->lesson_date))['wday'];

            $row->session = 'Buổi học '. $row->session;
            $start_time = get_date($row->start_time, 'H:i');
            $end_time = get_date($row->end_time, 'H:i');
            $lesson_date = get_date($row->lesson_date, 'd/m/Y');
            $end_date = get_date($row->end_date, 'd/m/Y');

            if($row->type_study == 3){
                $row->time = $start_time .' '. $lesson_date .' => '. $end_time .' '. $end_date;
                $start_convert = get_date($row->lesson_date, 'Y-m-d') .' '. $row->start_time;
                $end_convert = get_date($row->end_date, 'Y-m-d') .' '. $row->end_time;

                if($end_convert < date('Y-m-d H:i:s')){ //Hết ngày
                    if($register){
                        $offline_course_activity_condition = OfflineCourseActivityCondition::where('course_id', $course_id)
                            ->where('class_id', $row->class_id)
                            ->where('schedule_id', $row->id)
                            ->pluck('course_activity_id')->toArray();

                        $offline_course_activity_complete = OfflineCourseActivityCompletion::where('course_id', $course_id)
                            ->where('user_id', $user_id)
                            ->where('status', 1)
                            ->whereIn('activity_id', $offline_course_activity_condition)
                            ->count();

                        if($offline_course_activity_complete >= count($offline_course_activity_condition)){
                            $row->status = 'Hoàn thành';
                        }else{
                            $row->status = 'Chưa hoàn thành';
                        }
                    }else{
                        $row->status = 'Đã diễn ra';
                    }
                }elseif($start_convert > date('Y-m-d H:i:s')){ //Chưa tới ngày
                    $row->status = 'Chưa diễn ra';
                }else{ //Trong ngày
                    if($register){
                        $row->status = 'Vào học';
                    }else{
                        $row->status = 'Đang diễn ra';
                    }

                    $row->link_elearning = route('module.offline.detail_new', [$course_id, 'schedule_id'=>$row->id]);
                }
            }else{
                $row->time = $day_arr[$wday] .', '. $lesson_date .'<br>'. $start_time .' => '. $end_time;

                if($row->lesson_date < date('Y-m-d 00:00:00')){ //Hết ngày
                    if($register){
                        $offline_attendance = OfflineAttendance::whereCourseId($course_id)->where('class_id', $row->class_id)->where('schedule_id', $row->id)->where('user_id', $user_id)->where('status', 1)->exists();
                        $row->status = $offline_attendance ? 'Tham gia' : 'Không tham gia';
                    }else{
                        $row->status = 'Đã diễn ra';
                    }
                }elseif($row->lesson_date > date('Y-m-d 00:00:00')){ //Chưa tới ngày
                    $row->status = 'Chưa diễn ra';
                }else{ //Trong ngày
                    if($row->start_time > date('H:i:00')){ //Chưa tới giờ
                        $row->status = 'Chưa diễn ra';
                    }elseif($row->end_time > date('H:i:00')){ //Tới giờ học, chưa hết giờ
                        if($register && $row->type_study == 2){
                            $row->status = 'Vào học';
                        }else{
                            $row->status = 'Đang diễn ra';
                        }

                        if($row->type_study == 2){
                            $offline_activity_teams = OfflineCourseActivityTeams::where(['course_id' => $course_id,'class_id'=>$row->class_id,'schedule_id'=>$row->id])->first();
                            if($offline_activity_teams){
                                $row->link_teams = @$offline_activity_teams->join_url;
                            }
                        }

                    }else{ //Hết giờ
                        if($register){
                            $offline_attendance = OfflineAttendance::whereCourseId($course_id)->where('class_id', $row->class_id)->where('schedule_id', $row->id)->where('user_id', $user_id)->where('status', 1)->exists();
                            $row->status = $offline_attendance ? 'Tham gia' : 'Không tham gia';
                        }else{
                            $row->status = 'Đã diễn ra';
                        }
                    }
                }
            }

            $teacherName = [];
            if($row->teacher_main_id){
                $teacher = TrainingTeacher::find($row->teacher_main_id);
                $teacherName[] = $teacher->name .' (Chính)';
            }
            $teachers_main_other = OfflineNewTeacher::where(['course_id' => $course_id, 'class_id' => $row->class_id, 'schedule_id' => $row->id])->pluck('new_teacher_id')->toArray();
            if(count($teachers_main_other) > 0){
                foreach($teachers_main_other as $teacher_main_other){
                    $teacher = TrainingTeacher::find($teacher_main_other);
                    $teacherName[] = $teacher->name .' (Chính)';
                }
            }
            if(!empty($row->teach_id)) {
                $tutor_id = explode(',', $row->teach_id);
                foreach($tutor_id as $tutor) {
                    $teacher = TrainingTeacher::find($tutor);
                    $teacherName[] = $teacher->name .' (Trợ giảng)';
                }
            }
            $row->teacher = implode('<br> ', $teacherName);

            if($row->training_location_id){
                $training_location = TrainingLocation::find($row->training_location_id, ['name', 'province_id', 'district_id']);
                $province = Province::find($training_location->province_id, ['name']);
                $district = District::find($training_location->district_id, ['name']);

                $row->training_location = @$training_location->name .', '. @$district->name .', '. @$province->name;
            }
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function detailNew($id, Request $request){
        $schedule_id = $request->schedule_id;
        $user_id = profile()->user_id;
        $profile = profile();

        $item = OfflineCourse::where('id', '=', $id)->where('status', '=', 1)->where('isopen', '=', 1)->firstOrFail();
        $register = OfflineRegister::where('user_id', '=', $user_id)->where('course_id', '=', $id)->where('status', '=', 1)->first();
        $offline_class = OfflineCourseClass::whereCourseId($id)->where('id', $register->class_id)->first();
        $offline_schedule = OfflineSchedule::find($schedule_id);
        $condition = OfflineCondition::where('course_id', '=', $id)->first();

        $lessons = OfflineCourseLesson::where(['course_id' => $id, 'class_id' => $register->class_id, 'schedule_id' => $schedule_id])->get();
        foreach ($lessons as $lesson) {
            $lesson->activities = OfflineCourseActivity::getActivitiesByCourseLesson($lesson->id, $id);
        }
        $offline_activity_condition = OfflineCourseActivityCondition::where(['course_id' => $id, 'class_id' => $register->class_id, 'schedule_id' => $schedule_id])->pluck('course_activity_id')->toArray();
        $activity_condition = count($offline_activity_condition) > 0 ? $offline_activity_condition : [''];
        $offlineActivity = OfflineCourseActivity::getByCourse($id, $register->class_id, $schedule_id, null, $activity_condition);

        $time_user_view_course = UserViewCourse::firstOrNew(['course_id' => $id, 'course_type' => 2, 'user_id' => $user_id]);
        $time_user_view_course->course_id = $id;
        $time_user_view_course->course_type = 2;
        $time_user_view_course->user_id = $user_id;
        $time_user_view_course->time_view = date('Y-m-d H:i');
        if(!empty($register)) {
            $time_user_view_course->count_user_view = $time_user_view_course->count_user_view + 1;
        }
        $time_user_view_course->save();

        $this->updateLogViewCourse($item);

        $id_activity_scorm_xapi = '';
        $type_activity = 0;
        $link = '';
        $required_video_timeout = '';
        $check_activity_active = OfflineViewActivity::where('course_id',$id)->where('user_id',$user_id)->first();
        $get_first_activity= '';

        if(!empty($register) && $register->status == 1) {
            if(!empty($check_activity_active)) {
                $get_first_activity = OfflineCourseActivity::where('id',$check_activity_active->activity_id)->first();
            } else {
                $get_first_activity = OfflineCourseActivity::where('course_id',$id)->first();
                $get_first_activity && event(new GoActivity($id, @$get_first_activity->id));
            }
            $check_type_activity = $get_first_activity ? $get_first_activity->activity_id : '';

            if ($check_type_activity == 1) {
                $link = OfflineCourseActivityScorm::find($get_first_activity->subject_id);
                $id_activity_scorm_xapi = @$link->id;
                $type_activity = 1;
            } else if($check_type_activity == 2) {
                $file = OfflineCourseActivityFile::where('id', '=', $get_first_activity->subject_id)->first();
                $file_path = upload_file(explode('|', $file->path)[0]);
                if($file->extension == 'docx' || $file->extension == 'doc' || $file->extension == 'pptx' || $file->extension == 'ppt' || $file->extension == 'xls' || $file->extension == 'xlsx') {
                    $link = 'https://view.officeapps.live.com/op/embed.aspx?src='. upload_file($file->path);
                } else if ($file->extension == 'PDF' || $file->extension == 'pdf') {
                    $link = route('module.online.view_pdf', [$id]).'?path='. $file_path;
                } else {
                    $link = upload_file($file->path);
                }
                $type_activity = 2;
            } else if ($check_type_activity == 3) {
                $file = OfflineCourseActivityUrl::find($get_first_activity->subject_id);
                $link = $file->url;
                if (is_youtube_url($link)) {
                    $link = 'https://www.youtube.com/embed/' . get_youtube_id($link);
                }
                $type_activity = 3;
            } else if ($check_type_activity == 4) {
                $file = OfflineCourseActivityVideo::find($get_first_activity->subject_id);
                $required_video_timeout = $file->required_video_timeout;
                $link = upload_file($file->path);
                $type_activity = 4;
            } elseif($check_type_activity == 5){
                $link = OfflineCourseActivityXapi::find($get_first_activity->subject_id);
                $id_activity_scorm_xapi = @$link->id;
                $type_activity = 5;
            } elseif($check_type_activity == 7){
                $link = $get_first_activity->getLinkQuizCourse($get_first_activity->lesson_id);
                $type_activity = 7;
            }
        }

        $check_activity_course = false;
        if(OfflineCourseActivity::where(['course_id' => $id, 'class_id' => @$register->class_id])->exists()){
            $check_activity_course = true;
        }

        /*Lưu lịch sử tương tác của HV*/
        if($item->training_form_id){
            $training_form = TrainingForm::find($item->training_form_id);
            $interaction_history = InteractionHistory::where(['user_id' => profile()->user_id, 'code' => $training_form->code])->first();
            if($interaction_history){
                $interaction_history->number = ($interaction_history->number + 1);
                $interaction_history->save();
            }else{
                $interaction_history = new InteractionHistory();
                $interaction_history->user_id = profile()->user_id;
                $interaction_history->code = $training_form->code;
                $interaction_history->name =  $training_form->name;
                $interaction_history->number = 1;
                $interaction_history->save();
            }
        }
        /*******************************************/
        $closed_event_click = ($item->end_date < date('Y-m-d H:i:s') ? 1 : 0);

        return view('offline::frontend.detail2_new', [
            'item' => $item,
            'profile' => $profile,
            'register' => $register,
            'offlineActivity' => $offlineActivity,
            'closed_event_click' => $closed_event_click,
            'type_activity' => $type_activity,
            'user_id' => $user_id,
            'check_activity_course' => $check_activity_course,
            'link' => $link,
            'get_first_activity' => $get_first_activity,
            'id_activity_scorm_xapi' => $id_activity_scorm_xapi,
            'required_video_timeout' => $required_video_timeout,
            'check_activity_active' => !empty($check_activity_active) ? $check_activity_active->id : 0,
            'offline_schedule' => $offline_schedule,
            'offline_class' => $offline_class,
            'condition' => $condition,
            'lessons' => $lessons,
        ]);
    }

    //Chi tiết khoá học cũ (Chương trình, Bình luận, Mô tả, Nội dung, Kirkpatrick)
    public function detail($id, Request $request){

        return redirect()->route('module.offline.detail_first', $id);

        if ($request->share_key){
            $user_share = PromotionShare::query()
                ->where('course_id', '=', $id)
                ->where('type', '=', 2)
                ->where('share_key', '=', $request->share_key)
                ->first();

            if ($user_share->user_id != profile()->user_id){
                $course = OfflineCourse::find($id);
                $setting = UserPointSettings::where("pkey","=","offline_share")
                    ->where("item_id","=",$id)
                    ->where("item_type","=","3")
                    ->first();
                if ($setting && $setting->pvalue > 0) {
                    $note = 'Share khóa học tập trung <b>'. $course->name .' ('. $course->code .')</b>';

                    UserPointResult::create([
                        'setting_id' => $setting->id,
                        'user_id' => $user_share->user_id,
                        'content' => $note,
                        'point' => $setting->pvalue,
                        'ref' => $id,
                        'type_promotion' => 1,
                    ]);

                    $user_point = PromotionUserPoint::firstOrNew(['user_id' => $user_share->user_id]);
                    $user_point->point = $user_point->point + $setting->pvalue;
                    $user_point->level_id = PromotionLevel::levelUp($user_point->point, $user_share->user_id);
                    $user_point->save();
                }
            }

            return redirect()->route('module.offline.detail', ['id' => $id]);
        }

        
        $user_id = profile()->user_id;
        $item = OfflineCourse::where('id', '=', $id)
            ->where('status', '=', 1)
            ->where('isopen', '=', 1)
            ->firstOrFail();
        OfflineCourse::updateItemViews($id, $item->views);
        $register = OfflineRegister::where('user_id', '=', $user_id)
            ->where('course_id', '=', $id)
            ->where('status', '=', 1)
            ->first();

        $time_user_view_course = UserViewCourse::firstOrNew(['course_id' => $id, 'course_type' => 2, 'user_id' => $user_id]);
        $time_user_view_course->course_id = $id;
        $time_user_view_course->course_type = 2;
        $time_user_view_course->user_id = $user_id;
        $time_user_view_course->time_view = date('Y-m-d H:i');
        if(!empty($register)) {
            $time_user_view_course->count_user_view = $time_user_view_course->count_user_view + 1;
        }
        $time_user_view_course->save();

        $categories = OfflineCourse::getCourseCategory($item->training_program_id, $item->id);

        $comments = OfflineComment::select([
            'a.*',
            \DB::raw("CONCAT_WS(' ',lastname, firstname) AS fullname"),
            'b.avatar',
        ])
            ->from('el_offline_comment AS a')
            ->leftJoin('el_profile AS b', function ($sub){
                $sub->on('b.user_id', '=', 'a.user_id');
            })
            ->where('a.course_id', '=', $id)
            ->orderBy('a.id', 'desc')
            ->get();

        $profile = Profile::where('user_id', '=', $user_id)->first();
        $rating_course = RatingCourse::where('course_id', '=', $id)
            ->where('user_id', '=', $user_id)
            ->where('type', '=', 2)
            ->first();

        $indem = Indemnify::where('user_id', '=', $user_id)->where('course_id', '=', $id)->first();

        $sliders = Slider::where('status', '=', 1)
            ->where('location', '=', 'online')
            ->get();
        $rating_star = OfflineRating::where('course_id', '=', $id)
            ->where('user_id', '=', $user_id)
            ->first();
        $text_status = function ($status) {
            return OfflineCourse::getStatusRegisterText($status);
        };
        $class_status = function ($status) {
            return OfflineCourse::getBtnClassStatusRegister($status);
        };
        $course_time = $item->course_time;
        $course_time_unit = $item->course_time_unit;
        $this->updateLogViewCourse($item);

        $count_rating_level = OfflineRatingLevel::query()
            ->whereIn('id', function ($sub) use ($id){
                $sub->select(['offline_rating_level_id'])
                    ->from('el_offline_rating_level_object')
                    ->where('course_id', '=', $id)
                    ->where('object_type', '=', 1)
                    ->where(function ($sub2){
                        $sub2->orWhereNull('end_date');
                        $sub2->orWhere('end_date', '>=', now());
                    })
                    ->pluck('offline_rating_level_id')
                    ->toArray();
            })
            ->whereExists(function ($sub2) use ($id, $user_id){
                $sub2->select(['id'])
                    ->from('el_offline_register')
                    ->where('user_id', '=', $user_id)
                    ->where('course_id', '=', $id)
                    ->where('status', '=', 1);
            })
            ->where('course_id', '=', $id)
            ->whereNotExists(function ($sub) use ($user_id, $id){
                $sub->select(['id'])
                    ->from('el_rating_level_course as rlc')
                    ->whereColumn('rlc.course_rating_level_id', '=', 'el_offline_rating_level.id')
                    ->where('rlc.user_id', '=', $user_id)
                    ->where('rlc.user_type', '=', 1)
                    ->where('rlc.course_id', '=', $id)
                    ->where('rlc.course_type', '=', 2);
            })
            ->count();

        $go_quiz_url = '';
        $go_entrance_quiz_url = '';
        $closed_entrance_quiz = 0;
        $closed_quiz = 0;

        if($item->quiz_id){
            $quiz_register = QuizRegister::whereQuizId($item->quiz_id)
                ->where('user_id', '=', $user_id)
                ->where('type', '=', 1)
                ->first();
            if($quiz_register){
                $quiz_part = QuizPart::whereQuizId($item->quiz_id)
                ->where('id', $quiz_register->part_id)
                ->first();
                if ($quiz_part->end_date && $quiz_part->end_date < date('Y-m-d H:i:s')){
                    $closed_quiz = 1;
                }

                if ($quiz_part->start_date <= date('Y-m-d H:i:s') || $closed_quiz == 0){
                    $go_quiz_url = route('module.quiz.doquiz.index', [
                        'quiz_id' => $item->quiz_id,
                        'part_id' => $quiz_part->id,
                    ]);
                }
            }
        }
        if($item->entrance_quiz_id){
            $entrance_quiz_register = QuizRegister::whereQuizId($item->entrance_quiz_id)
                ->where('user_id', '=', $user_id)
                ->where('type', '=', 1)
                ->first();
            if($entrance_quiz_register){
                $entrance_quiz_part = QuizPart::whereQuizId($item->entrance_quiz_id)
                ->where('id', $entrance_quiz_register->part_id)
                ->first();
                if ($entrance_quiz_part->end_date && $entrance_quiz_part->end_date < date('Y-m-d H:i:s')){
                    $closed_entrance_quiz = 1;
                }

                if ($entrance_quiz_part->start_date <= date('Y-m-d H:i:s') || $closed_entrance_quiz == 0){
                    $go_entrance_quiz_url = route('module.quiz.doquiz.index', [
                        'quiz_id' => $item->entrance_quiz_id,
                        'part_id' => $entrance_quiz_part->id,
                    ]);
                }
            }
        }

        $f_offlineActivity = function($course_id, $class_id, $schedule_id){
            return OfflineCourseActivity::getByCourse($course_id, $class_id, $schedule_id);
        };

        $id_activity_scorm_xapi = '';
        $type_activity = 0;
        $link = '';
        $required_video_timeout = '';
        $check_activity_active = OfflineViewActivity::where('course_id',$id)->where('user_id',$user_id)->first();
        $get_first_activity= '';

        if(!empty($register) && $register->status == 1) {
            if(!empty($check_activity_active)) {
                $get_first_activity = OfflineCourseActivity::where('id',$check_activity_active->activity_id)->first();
            } else {
                $get_first_activity = OfflineCourseActivity::where('course_id',$id)->first();
                $get_first_activity && event(new GoActivity($id, @$get_first_activity->id));
            }
            $check_type_activity = $get_first_activity ? $get_first_activity->activity_id : '';

            if ($check_type_activity == 1) {
                $link = OfflineCourseActivityScorm::find($get_first_activity->subject_id);
                $id_activity_scorm_xapi = @$link->id;
                $type_activity = 1;
            } else if($check_type_activity == 2) {
                $file = OfflineCourseActivityFile::where('id', '=', $get_first_activity->subject_id)->first();
                $file_path = upload_file(explode('|', $file->path)[0]);
                if($file->extension == 'docx' || $file->extension == 'doc' || $file->extension == 'pptx' || $file->extension == 'ppt' || $file->extension == 'xls' || $file->extension == 'xlsx') {
                    $link = 'https://view.officeapps.live.com/op/embed.aspx?src='. upload_file($file->path);
                } else if ($file->extension == 'PDF' || $file->extension == 'pdf') {
                    $link = route('module.online.view_pdf', [$id]).'?path='. $file_path;
                } else {
                    $link = upload_file($file->path);
                }
                $type_activity = 2;
            } else if ($check_type_activity == 3) {
                $file = OfflineCourseActivityUrl::find($get_first_activity->subject_id);
                $link = $file->url;
                if (is_youtube_url($link)) {
                    $link = 'https://www.youtube.com/embed/' . get_youtube_id($link);
                }
                $type_activity = 3;
            } else if ($check_type_activity == 4) {
                $file = OfflineCourseActivityVideo::find($get_first_activity->subject_id);
                $required_video_timeout = $file->required_video_timeout;
                $link = upload_file($file->path);
                $type_activity = 4;
            } elseif($check_type_activity == 5){
                $link = OfflineCourseActivityXapi::find($get_first_activity->subject_id);
                $id_activity_scorm_xapi = @$link->id;
                $type_activity = 5;
            }
        }

        $f_offlineActivityMettings = function($course_id, $class_id, $schedule_id){
            $user_id = profile()->user_id;
            $offlineActivityMettings = OfflineCourseActivity::where(['course_id' => $course_id, 'activity_id' => 6])
            ->whereIn('subject_id', function($sub) use($course_id, $class_id, $schedule_id) {
                $sub->select(['id'])
                    ->from('offline_course_activity_teams')
                    ->where('course_id', $course_id)
                    ->where('class_id', $class_id)
                    ->where('schedule_id', $schedule_id);
            })
            ->get();
            foreach($offlineActivityMettings as $offlineActivityMetting) {
                $activityMetting = OfflineCourseActivityTeams::find($offlineActivityMetting->subject_id);

                $offlineActivityMetting->topic = $activityMetting->topic;
                $offlineActivityMetting->start_time = get_date($activityMetting->start_time, 'H:i d/m/Y');
                $offlineActivityMetting->end_time = get_date($activityMetting->end_time, 'H:i d/m/Y');
                if($activityMetting->start_time > date('Y-m-d H:i:s')) {
                    $offlineActivityMetting->linkMetting = '#';
                    $offlineActivityMetting->checkLink = 1;
                } else if ($activityMetting->end_time < date('Y-m-d H:i:s')) {
                    $offlineActivityMetting->linkMetting = '#';
                    $offlineActivityMetting->checkLink = 2;
                } else {
                    $offlineActivityMetting->linkMetting = $activityMetting->join_url;
                    $offlineActivityMetting->checkLink = 0;
                }

                $attendance = OfflineAttendance::where('course_id', $course_id)
                    ->where('user_id', $user_id)
                    ->where('schedule_id', $activityMetting->schedule_id)
                    ->where('status', 1)
                    ->first();

                $offlineActivityMetting->check_attendance = $attendance ? 1 : 0;
            }

            return $offlineActivityMettings;
        };

        $check_activity_course = false;
        if(OfflineCourseActivity::where(['course_id' => $id, 'class_id' => @$register->class_id])->exists()){
            $check_activity_course = true;
        }

        $teacher_class = \DB::table('el_offline_course_teachers as a')->join('el_training_teacher as b','a.teacher_id','b.id')->where(['a.course_id'=>$id,'b.user_id'=>$user_id])->exists();
        /*Lưu lịch sử tương tác của HV*/
        if($item->training_form_id){
            $training_form = TrainingForm::find($item->training_form_id);
            $interaction_history = InteractionHistory::where(['user_id' => profile()->user_id, 'code' => $training_form->code])->first();
            if($interaction_history){
                $interaction_history->number = ($interaction_history->number + 1);
                $interaction_history->save();
            }else{
                $interaction_history = new InteractionHistory();
                $interaction_history->user_id = profile()->user_id;
                $interaction_history->code = $training_form->code;
                $interaction_history->name =  $training_form->name;
                $interaction_history->number = 1;
                $interaction_history->save();
            }
        }
        /*******************************************/

        $closed_event_click = ($item->end_date < date('Y-m-d H:i:s') ? 1 : 0);

        $offline_schedule = OfflineSchedule::where('course_id', $id)->where('class_id', @$register->class_id)->get();

        return view('offline::frontend.detail2', [
            'item' => $item,
            'categories' => $categories,
            'comments' => $comments,
            'profile' => $profile,
            'rating_course' => $rating_course,
            'sliders' => $sliders,
            'rating_star' => $rating_star,
            'text_status' => $text_status,
            'class_status' => $class_status,
            'register' => $register,
            'course_time' => $course_time,
            'course_time_unit' => $course_time_unit,
            'indem' => $indem,
            'count_rating_level' => $count_rating_level,
            'go_quiz_url' => $go_quiz_url,
            'go_entrance_quiz_url' => $go_entrance_quiz_url,
            'teacher_class' => $teacher_class,
            'f_offlineActivity' => $f_offlineActivity,
            'f_offlineActivityMettings' => $f_offlineActivityMettings,
            'closed_event_click' => $closed_event_click,
            'type_activity' => $type_activity,
            'user_id' => $user_id,
            'check_activity_course' => $check_activity_course,
            'link' => $link,
            'get_first_activity' => $get_first_activity,
            'id_activity_scorm_xapi' => $id_activity_scorm_xapi,
            'required_video_timeout' => $required_video_timeout,
            'check_activity_active' => !empty($check_activity_active) ? $check_activity_active->id : 0,
            'offline_schedule' => $offline_schedule,
        ]);
    }

    public function ajaxActivity(Request $request){
        $date = date('Y-m-d H:i:s');

        $course_id = $request->id;
        $activity_id = $request->aid;
        $lesson_id = $request->lesson_id;
        $type = $request->type;
        $user_id = profile()->user_id;
        $status_course = $request->status_course;

        if(!$activity_id || !$lesson_id) {
            json_result([
                'status' => 'warning',
                'message' => 'Khóa học chưa có hoạt động',
            ]);
        }

        if($status_course && $status_course != 4){
            json_message(status_register_text($status_course), 'error');
        }

        $list_activity = OfflineCourseActivity::where('course_id', $course_id)->orderBy('lesson_id','asc')->orderBy('id','asc')->pluck('id')->toArray();
        $index = array_search($activity_id ,$list_activity);
        $prev = $list_activity[$index - 1];
        $next = $list_activity[$index + 1];
        if($prev) {
            $activity_prev = OfflineCourseActivity::find($prev,['id','activity_id','lesson_id']);
        } else {
            $activity_prev = '';
        }
        if ($next) {
            $activity_next = OfflineCourseActivity::find($next,['id','activity_id','lesson_id']);
        } else {
            $activity_next = '';
        }

        OfflineViewActivity::updateOrCreate([
            'course_id' => $course_id,
            'user_id' => $user_id,
        ], [
            'course_id' => $course_id,
            'activity_id' => $activity_id,
            'user_id' => $user_id,
        ]);

        $course_activity = OfflineCourseActivity::findOrFail($activity_id);

        event(new GoActivity($course_id, $activity_id));
        $this->checkCompleteActivityUser($course_id,$course_activity);
        $required_video_timeout = '';
        $msg_error = '';
        $checkPage = 0;
        if ($type == 1) {
            $link = OfflineCourseActivityScorm::findOrFail($course_activity->subject_id);
        } else if($type == 2) {
            $file = OfflineCourseActivityFile::where('id', '=', $course_activity->subject_id)->first();
            $file_path = upload_file(explode('|', $file->path)[0]);
            if($file->extension == 'docx' || $file->extension == 'doc' || $file->extension == 'pptx' || $file->extension == 'ppt' || $file->extension == 'xls' || $file->extension == 'xlsx') {
                $link = 'https://view.officeapps.live.com/op/embed.aspx?src='. upload_file($file->path);
            } else if ($file->extension == 'PDF' || $file->extension == 'pdf') {
                $link = route('module.online.view_pdf', [$course_id]).'?path='. $file_path;
            } else {
                $link = upload_file($file->path);
            }
        } else if ($type == 3) {
            $file = OfflineCourseActivityUrl::find($course_activity->subject_id);
            $link = $file->url;
            if (is_youtube_url($link) && $file->page == 0) {
                $link = 'https://www.youtube.com/embed/' . get_youtube_id($link);
            }
            $checkPage = $file->page;
        } else if ($type == 4) {
            $file = OfflineCourseActivityVideo::find($course_activity->subject_id,['path', 'required_video_timeout']);
            $required_video_timeout = $file->required_video_timeout;
            $link = upload_file($file->path);
            $link = convert_url_web_to_app($link);
        } else if ($type == 5) {
            $link = OfflineCourseActivityXapi::findOrFail($course_activity->subject_id);
        } else if ($type == 7) {
            $link = $course_activity->getLinkQuizCourse();
            if(empty($link)) {
                $msg_error = 'Kỳ thi chưa có ca thi hoặc chưa ghi danh';
            }
        }else if($type == 8){
            $offline_survey_user = OfflineSurveyUser::where(['course_id' => $course_id, 'course_activity_id' => $activity_id, 'user_id' => $user_id])->first();
            if($offline_survey_user){
                $link = route('module.offline.survey.user.edit', [$course_id, $activity_id, $user_id]);
            }else{
                $link = route('module.offline.survey.user', [$course_id, $activity_id]);
            }
        }

        $get_activity_completes = OfflineCourseActivityCompletion::select('activity_id')->where('course_id',$course_id)->where('user_id',$user_id)->where('status',1)->pluck('activity_id')->toArray();

        return json_result([
            'status' => 'success',
            'link' => $link,
            'course_activity' => $course_activity,
            'get_activity_completes' => $get_activity_completes,
            'required_video_timeout' => $required_video_timeout,
            'activity_prev' => $activity_prev,
            'activity_next' => $activity_next,
            'msg_error' => $msg_error,
            'checkPage' => $checkPage
        ]);
    }

    // GỌI AJAX CÁC HOẠT ĐỘNG ĐỒNG THỜI CHECK LUÔN HOÀN THÀNH HOẠT ĐỘNG TRƯỚC
    public function checkCompleteActivityUser($course_id, OfflineCourseActivity $course_activity) {
        if (!in_array($course_activity->activity_id,[2,3,4]))
            return;
        $user_id = profile()->user_id;
        $user_type = Profile::getUserType();
        $status = $course_activity->checkComplete($user_id, $user_type);
        $completion = OfflineCourseActivityCompletion::firstOrNew([
            'user_id' => $user_id,
            'course_id'=>$course_id,
            'activity_id' => $course_activity->id,
        ]);
        $completion->user_id = $user_id;
        $completion->user_type = $user_type;
        $completion->activity_id = $course_activity->id;
        $completion->course_id = $course_id;
        $completion->status = $status;
        $completion->save();
    }

    // LẤY MÔ TẢ HOẠT ĐỘNG
    public function ajaxDescriptionActivity(Request $request)
    {
        $course_id = $request->course_id;
        $activity_id = $request->aid;
        $type = $request->type;
        $course_activity = OfflineCourseActivity::findOrFail($activity_id);
        switch ($type) {
            case 1:
                $description = OfflineCourseActivityScorm::find($course_activity->subject_id, ['description']);
                break;
            case 2:
                $description = OfflineCourseActivityFile::where('id', '=', $course_activity->subject_id)->first(['description']);
                break;
            case 3:
                $description = OfflineCourseActivityUrl::find($course_activity->subject_id, ['description']);
                break;
            case 4:
                $description = OfflineCourseActivityVideo::find($course_activity->subject_id, ['description']);
                break;
            case 5:
                $description = OfflineCourseActivityXapi::find($course_activity->subject_id, ['description']);
                break;
            case 7:
                $description = OfflineActivityQuiz::find($course_activity->subject_id, ['description']);
                break;
            case 8:
                $description = OfflineCourseActivitySurvey::where(['course_id' => $course_id,'survey_template_id' => $course_activity->subject_id])->first(['description']);
                break;
            default:
                $description = '';
                break;
        }

        $description = $description ? html_entity_decode($description->description, ENT_HTML5, "UTF-8") : '';
        json_result([
            'status' => 'success',
            'description' => $description
        ]);
    }

    // HỌC VIÊN XEM HẾT VIDEO
    public function finishActivityVideo(Request $request)
    {
        $model = OfflineFinishVideo::firstOrNew(['video_id' => $request->id, 'user_id' => profile()->user_id, 'course_id' => $request->course_id]);
        $model->user_id = profile()->user_id;
        $model->course_id = $request->course_id;
        $model->video_id = $request->id;
        $model->save();

        $saveComplete = OfflineCourseActivityCompletion::where(['user_id' => profile()->user_id, 'activity_id' => $request->id, 'course_id' => $request->course_id])->first();
        $saveComplete->status = 1;
        $saveComplete->save();

        $get_activity_completes = OfflineCourseActivityCompletion::where('course_id', $request->course_id)->where('user_id', profile()->user_id)->where('status', 1)->pluck('activity_id')->toArray();

        json_result([
            'status' => 'success',
            'description' => 'Lưu thành công',
            'get_activity_completes' => $get_activity_completes,
        ]);
    }

    // LƯU TỔNG THỜI GIAN HỌC
    public function saveUserTimeLearn(Request $request)
    {
        $saveTimeUserLearn = OfflineCourseTimeUserLearn::firstOrNew(['course_id' => $request->courseId, 'user_id' => profile()->user_id]);
        $saveTimeUserLearn->course_id = $request->courseId;
        $saveTimeUserLearn->user_id = profile()->user_id;
        $saveTimeUserLearn->time = $saveTimeUserLearn->time + $request->time;
        $saveTimeUserLearn->save();

        json_result([
            'status' => 'success',
        ]);
    }

    public function updateLogViewCourse(OfflineCourse $course)
    {
        $user_id = profile()->user_id;
        $session_id = \Session::getId();
        $ip_address = \request()->ip();
        $user_agent = \request()->userAgent();
        $profile = profile();
        $user_full_name = $profile->full_name;
        $user_code = $profile->code;
        LogViewCourse::updateOrCreate(
            [
                'user_id'=>$user_id,
                'session_id'=>$session_id,
                'course_id'=>$course->id,
                'course_type'=>2
            ],
            [
                'user_id'=>$user_id,
                'session_id'=>$session_id,
                'course_id'=>$course->id,
                'course_type'=>2,
                'user_code' =>$user_code,
                'course_code'=>$course->code,
                'course_name'=>$course->name,
                'ip_address'=>$ip_address,
                'user_agent'=>$user_agent,
                'user_name'=>$user_full_name,
                'last_access'=>now(),
            ]
        );
    }
    public function registerCourse($id, Request $request)
    {
        $class_default = OfflineCourseClass::where(['course_id'=>$id,'default'=>1])->first();
        $class_id = $request->class_id ?? $class_default->id ?? 0;

        $course = OfflineCourse::findOrFail($id);
        $user_id = profile()->user_id;
        $profile_user = profile();

        $area_by_unit = Unit::where('id', $profile_user->unit_id)->first(['area_id']);

        $referer = $request->referer;
        /* insert hist point  register referer*/
        if ($referer) {
            if (Profile::validRefer($referer)) {
                RefererRegisterCourse::saveReferRegisterOfflineCourse($id,$referer);
                PromotionUserPoint::updatePointRegisterCourse($referer);
                PointHist::savePointHist($referer);
            }else{
                json_message('Mã người giới thiệu không hợp lệ','error');
            }
        }

        //kiểm tra hoàn thành khóa học trước theo chức danh trong khoảng thời gian
        $check_setting_join_course = OfflineCourse::checkSettingJoinCourse($id, $user_id);
        if(!$check_setting_join_course){
            json_result([
                'status' => 'warning',
                'message' => 'Anh/Chị không thuộc Thiết lập tham gia khóa học. Vui lòng liên hệ Trung tâm đào tạo',
            ]);
        }

        // check đối tượng tham gia
        $getObjects = OfflineObject::where('course_id', $id)->get();
        $chekObject = 0;
        $chekObjectType = '';
        foreach($getObjects as $object) {
            if($profile_user->title_id == $object->title_id && $profile_user->unit_id == $object->unit_id) {
                $chekObject = 1;
                $chekObjectType = $object->type;
            }
        }
        if(!$getObjects->isEmpty() && $chekObject == 0) {
            json_result([
                'status' => 'warning',
                'message' => 'Anh/Chị không thuộc đối tượng tham gia khóa học. Vui lòng liên hệ Trung tâm đào tạo',
            ]);
        }

        // XÉT ĐIỀU KIỆN TIÊN QUYẾT
        $prerequisite = SubjectPrerequisiteCourse::where(['course_id' => $id, 'course_type' => 2])->first();
        if(isset($prerequisite)) {
            $stringCondition ="";
            // check hoc phan can hoan thanh
            if($prerequisite->subject_prerequisite) {
                $query = UserCompletedSubject::query();
                $query->select([
                    'a.subject_id',
                    'b.score',
                    'b.created_at',
                ]);
                $query->from('el_user_completed_subject as a');
                $query->join('el_online_result as b', function($join) {
                    $join->on('b.course_id', '=', 'a.course_id');
                    $join->where('b.user_id', profile()->user_id);
                });
                $query->where('a.user_id', profile()->user_id);
                $query->where('a.subject_id', $prerequisite->subject_prerequisite);
                $complete = $query->first();
                $checkSubject_success = 0;
                $date_finish_add_prerequisite = Carbon::parse(@$complete->created_at)->addDays($prerequisite->date_finish_prerequisite)->format('Y-m-d H:i:s');
                if($complete && $prerequisite->finish_and_score == 1) {
                    if($complete->score >= $prerequisite->score_prerequisite && $date_finish_add_prerequisite < date('Y-m-d H:i:s')) {
                        $checkSubject_success = 1;
                    }
                } else if($complete && $prerequisite->finish_and_score == 2) {
                    if($complete->score >= $prerequisite->score_prerequisite || $date_finish_add_prerequisite < date('Y-m-d H:i:s')) {
                        $checkSubject_success = 1;
                    }
                }
                if ($checkSubject_success == 1) {
                    if ($prerequisite->select_subject_prerequisite == 1)
                        $stringCondition.= "1&&";
                    else
                        $stringCondition.= "1||";
                }else{
                    if ($prerequisite->select_subject_prerequisite == 1)
                        $stringCondition.= "0&&";
                    else
                        $stringCondition.= "0||";
                }
            }

            // check chức danh thỏa điều kiện
            if($prerequisite->title_id) {
                if($prerequisite->title_id == $profile_user->title_id) {
                    if ($prerequisite->select_title==1)
                        $stringCondition.= "1&&";
                    else
                        $stringCondition.= "1||";
                }else{
                    if ($prerequisite->select_title==1)
                        $stringCondition.= "0&&";
                    else
                        $stringCondition.= "0||";
                }
            }

            // check ngày bổ nhiệm chức danh thỏa điều kiện
            if($prerequisite->date_title_appointment) {
                $date_format_title_appointment = Carbon::parse(@$profile_user->date_title_appointment)->addDays($prerequisite->date_title_appointment)->format('Y-m-d H:i:s');
                if($date_format_title_appointment <= date('Y-m-d H:i:s')) {
                    if ($prerequisite->select_date_title_appointment==1)
                        $stringCondition.= "1&&";
                    else
                        $stringCondition.= "1||";
                }else{
                    if ($prerequisite->select_date_title_appointment==1)
                        $stringCondition.= "0&&";
                    else
                        $stringCondition.= "0||";
                }
            }

            //check ngày vào công ty thỏa điều kiện
            if($prerequisite->join_company) {
                $date_format_join_company = Carbon::parse(@$profile_user->join_company)->addDays($prerequisite->join_company)->format('Y-m-d H:i:s');
                if($date_format_join_company < date('Y-m-d H:i:s')) {
                    $stringCondition .=  "1&&";
                }else
                    $stringCondition .= "0&&";
            }

            $check_final = 0;
            $exeCondition = substr($stringCondition,0,-2);// '0&&0&&1||0&&0||0&&0';
            eval('$resultCondition= '.$exeCondition.';');
            if (!$resultCondition || $resultCondition == '0') {
                json_message('Bạn không thỏa điều kiện tiên quyết để ghi danh khóa học này. Vui lòng liên hệ quản trị để biết thêm thông tin', 'error');
            }
        }

        $model = OfflineRegister::firstOrCreate(['user_id'=>profile()->user_id,'course_id'=>$id, 'class_id'=>$class_id]);
        $model->register_form = 1;
        $model->class_id = $class_id;
        if($chekObjectType && $chekObjectType == 1) {
            $model->status = 1;
            $model->approved_step = '1/1';
        }else{
            $model->status = 2;
        }
        $save = $model->save();
        if ($save) {
            //update training process
            $subject = Subject::findOrFail($course->subject_id);
            $title = Titles::where('id',$profile_user->title_id)->first();
            event(new SaveTrainingProcessRegister($course, $subject, profile()->user_id, $class_id, 2));
            $this->sendMailManagerApprove($id,$model->id);

            if($model->status == 2){
                $this->sendNotifyApproveRegister($model);
            }

            if (url_mobile()){
                json_result([
                    'status' => 'success',
                    'message' => 'Đăng ký thành công',
                    'redirect' => route('themes.mobile.frontend.offline.index')
                ]);
            }

            json_result([
                'status' => 'success',
                'message' => 'Đăng ký thành công',
                'redirect' => route('frontend.all_course', [
                    'type' => 2
                ])
            ]);
        }
    }

    public function sendNotifyApproveRegister($model){
        $course = OfflineCourse::findOrFail($model->course_id);
        $array_params = [
            'name' => $course->name,
            'url' => route('module.offline.register',[$model->course_id, $model->class_id]),
        ];

        $params = json_encode($array_params);
        /* thông báo */
        $nottify_template = NotifyTemplate::query()->where('code', '=', 'approve_register')->first();
        $subject_notify = $this->mapParams($nottify_template->title, $params);
        $content_notify = $this->mapParams($nottify_template->content, $params);
        $url = $this->getParams($params, 'url');

        $permissionApprovedUser = PermissionApprovedUser::where('model_approved', 'el_offline_register')->pluck('user_id')->toArray();
        $notify = new Notify();
        $notify->subject = $subject_notify;
        $notify->content = $content_notify;
        $notify->url = $url;
        $notify->users = $permissionApprovedUser;
        $notify->addMultiNotify();

    }
    public function mapParams($content, $params) {
        $params = json_decode($params);
        foreach ($params as $key => $param) {
            if ($key == 'url') {
                $content = str_replace('{'. $key .'}', '<a target="_blank" href="'. $param .'">liên kết này</a>', $content);
            }
            else {
                $content = str_replace('{'. $key .'}', $param, $content);
            }
        }
        return $content;
    }
    public function getParams($params, $key) {
        $params = json_decode($params);
        if (isset($params->{$key})) {
            return $params->{$key};
        }

        return null;
    }

    public function sendMailManagerApprove($course_id,$register_id)
    {
        $user_id = profile()->user_id;
        $profile = profile();
        $full_name = $profile->full_name;
        $course = OfflineCourse::find($course_id);
        $unit_id = $profile->unit_id;
        //truong don vi
        $user_managers = UnitManager::query()
            ->from('el_unit_manager as a')
            ->join('el_unit as b','a.unit_code','=','b.code')
            ->join('el_profile as c','c.code','=','a.user_code')
            ->where(['b.id'=>$unit_id])
            ->select('c.user_id')
            ->get();
        foreach ($user_managers as $user){
            $signature = getMailSignature($user->user_id);
            $params = [
                'code' => $course->code,
                'course_name' => $course->name,
                'student' => $full_name,
                'url' => route('module.training_unit.approve_course.course', ['id' => $course_id, 'type' => 2]),
                'signature' => $signature
            ];
            $this->execMailManagerApprove($params,[$user->user_id],$register_id);
        }
        //duoc phan quyen quan ly don vi
        $unit_id_arr = [];
        $unit = Unit::getTreeParentUnit($profile->unit_code);
        foreach ($unit as $item){
            $unit_id_arr[] = $item->id;
        }
        $users= PermissionTypeUnit::query()
            ->select('d.user_id')
            ->from('el_permission_type_unit as a')
            ->join('el_permission_type as b','a.permission_type_id','b.id')
            ->join('el_user_permission_type as c','c.permission_type_id','a.permission_type_id')
            ->join('el_profile as d','d.user_id','c.user_id')
            ->join('el_permissions as e','e.id','c.permission_id')
            ->whereIn('d.unit_id',$unit_id_arr)
            ->whereIn('a.unit_id',$unit_id_arr)
            ->whereIn('e.name', function ($sub2){
                $sub2->select(['per.parent'])
                    ->from('el_model_has_permissions as model')
                    ->leftJoin('el_permissions as per', 'per.id', '=', 'model.permission_id')
                    ->whereColumn('model.model_id', '=', 'c.user_id')
                    ->where('per.name', '=', 'offline-course-register-approve');
            })
            ->where(['e.name'=>'offline-course-register'])
            ->get();
        foreach ($users as $user){
            $signature = getMailSignature($user->user_id);
            $params = [
                'code' => $course->code,
                'course_name' => $course->name,
                'student' => $full_name,
                'url' => route('module.training_unit.approve_course.course', ['id' => $course_id, 'type' => 2]),
                'signature' => $signature
            ];
            $this->execMailManagerApprove($params,[$user->user_id],$register_id);
        }
    }
    public function execMailManagerApprove(array $params, array $user_id,int $register_id)
    {
        $automail = new Automail();
        $automail->template_code = 'approve_register';
        $automail->params = $params;
        $automail->users = $user_id;
        $automail->object_id = '2'.$register_id;
        $automail->object_type = 'approve_offline_register';
        $automail->addToAutomail();
    }
    public function comment($id, Request $request)
    {
        $this->validateRequest([
            'content' => 'required',
        ], $request, ['content' => trans("latraining.content")]);

        $model = new OfflineComment();
        $model->course_id = $id;
        $model->user_id = profile()->user_id;
        $model->content = $request->post('content');

        if ($model->save()) {
            json_result([
                'message' => 'Cảm ơn đã bình luận',
                'redirect' => route('module.offline.detail', [
                    'id' => $id
                ])
            ]);
        }
    }

    public function rating($id, Request $request) {
        $this->validateRequest([
            'star' => 'required|min:1',
        ], $request, ['star' => 'Sao']);

        $user_id = profile()->user_id;
        if (OfflineRating::getRating($id, $user_id)) {
            json_message('Bạn đã đánh giá khóa học này', 'warning');
        }

        $model = new OfflineRating();
        $model->course_id = $id;
        $model->user_id = $user_id;
        $model->num_star = $request->star;

        if ($model->save()) {
            $course = OfflineCourse::find($id);
            $setting = UserPointSettings::where("pkey","=","offline_rating_star")
                ->where("item_id","=",$id)
                ->where("item_type","=","3")
                ->first();
            if ($setting && $setting->pvalue > 0) {
                $note = 'Đánh giá sao khóa học tập trung <b>'. $course->name .' ('. $course->code .')</b>';

                UserPointResult::create([
                    'setting_id' => $setting->id,
                    'user_id' => $user_id,
                    'content' => $note,
                    'point' => $setting->pvalue,
                    'ref' => $id,
                    'type_promotion' => 1,
                ]);

                $userPoint = PromotionUserPoint::firstOrCreate(['user_id' => $user_id], ['point' => 0, 'level_id' => 0]);
                $userPoint->point += $setting->pvalue;
                $userPoint->level_id = PromotionLevel::levelUp($setting->pvalue, $user_id);
                $userPoint->update();

                $history = new PromotionUserHistory();
                $history->user_id = $user_id;
                $history->point = $setting->pvalue;
                $history->type = 2;
                $history->course_id = $course->id;
                $history->save();
            }

            json_message('Cảm ơn bạn đã đánh giá');
        }

        json_message('Bạn đã đánh giá khóa học này', 'error');
    }

    public function search(Request $request)
    {
        $items = $this->getItems($request);
        $training_program = TrainingProgram::find($request->get('training_program_id'));
        $level_subject = LevelSubject::find($request->get('level_subject_id'));
        $subject = Subject::find($request->get('subject_id'));
        $status = $request->status;

        return view('offline::frontend.index', [
            'items' => $items,
            'training_program' => $training_program,
            'subject' => $subject,
            'level_subject' => $level_subject,
            'status' => $request->status,
        ]);
    }

    public function showModalQrcodeReferer(Request $request) {
        $course_id = $request->input('course_id');
        return view('online::modal.qrcode_referer' );
    }

    public function viewPDF($id,$key){
        $path = OfflineCourse::find($id)->getLinkViewPdf($id,$key);
        if (url_mobile()){
            $path = str_replace(config('app.url'), config('app.mobile_url'), $path);

            return view('themes.mobile.frontend.offline_course.view_pdf', [
                'path' => $path,
            ]);
        }
        return view('offline::frontend.view_pdf', [
            'path' => $path,
        ]);
    }

    private function levelUp($point)
    {
        $level = PromotionLevel::query()->where('point','<=', $point);

        if($level->exists())
            return $level->max('level');
        else
            return 0;
    }

    private function saveHistoryPromotion($user_id,$point,$course_id, $promotion_course_setting_id){
        $history = new PromotionUserHistory();
        $history->user_id = $user_id;
        $history->point = $point;
        $history->type = 2;
        $history->course_id = $course_id;
        $history->promotion_course_setting_id = $promotion_course_setting_id;
        $history->save();

        $course_name = OfflineCourse::query()->find($course_id)->name;

        $model = new Notify();
        $model->user_id = $user_id;
        $model->subject = 'Thông báo đạt điểm thưởng khoá học.';
        $model->content = 'Bạn đã đạt điểm thưởng là "'. $point .'" điểm của khoá học "'. $course_name .'"';
        $model->url = null;
        $model->created_by = 0;
        $model->save();

        $content = \Str::words(html_entity_decode(strip_tags($model->content)), 10);
        $redirect_url = route('module.notify.view', [
            'id' => $model->id,
            'type' => 1
        ]);

        $notification = new AppNotification();
        $notification->setTitle($model->subject);
        $notification->setMessage($content);
        $notification->setUrl($redirect_url);
        $notification->add($user_id);
        $notification->save();
    }

    public function shareCourse($course_id, $type, Request $request){
        $promotion_share = PromotionShare::firstOrNew(['course_id' => $course_id, 'type' => $type, 'user_id' => profile()->user_id]);
        $promotion_share->share_key = $request->share_key;
        $promotion_share->save();

        $linkShare = route('module.offline.detail', ['id' => $course_id]).'?share_key='. $promotion_share->share_key;

        json_result($linkShare);
    }

    public function studentCost(Request $request){
        $student_costs = StudentCost::where('status','=',1)->get();
        return view('offline::frontend.student_cost',[
            'student_costs' => $student_costs,
        ]);
    }

    public function getDataCourse(Request $request) {
        $search = $request->input('search');
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');

        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = OfflineCourse::query();
        $query->where('enter_student_cost', '=', 1);
        $query->where('end_date', '<', date('Y-m-d H:i:s'));
        $query->whereIn('id', function ($sub){
           $sub->select(['course_id'])
               ->from('el_offline_register')
               ->where('user_id', '=', profile()->user_id)
               ->pluck('course_id')
               ->toArray();
        });
        if ($search) {
            $query->where(function ($subquery) use ($search) {
                $subquery->orWhere('name', 'like', '%'. $search .'%');
                $subquery->orWhere('code', 'like', '%'. $search .'%');
            });
        }
        if ($start_date) {
            $query->where('start_date', '>=', date_convert($start_date));
        }

        if ($end_date) {
            $query->where('start_date', '<=', date_convert($end_date, '23:59:59'));
        }
        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row) {
            $register = OfflineRegister::whereCourseId($row->id)->where('user_id', '=', profile()->user_id)->first();
            $register_cost = OfflineStudentCostByUser::where('register_id', '=', @$register->id)->where('course_id',$row->id)->get();
            $total_student_cost = OfflineStudentCostByUser::getTotalStudentCost(@$register->id, $row->id);

            $row->start_date = get_date($row->start_date);
            $row->end_date = get_date($row->end_date);

            $student_costs = StudentCost::where('status','=',1)->get();
            foreach($student_costs as $key => $student_cost) {
                $check_register = (isset($register_cost[$key]) && $register_cost[$key]->manager_approved == 1) ? "readonly" : '';
                $count_register = count($register_cost) != 0 && isset($register_cost[$key]) ? number_format($register_cost[$key]->cost, 0) : '';

                $student_cost_row ='<input type="hidden" id="register_id_'. $student_cost->id .'_'. $row->id .'" name="regid" value="'. @$register->id .'">';
                $student_cost_row .='<input type="hidden" name="cost_id_'.$student_cost->id.'" value="'. $student_cost->id .'" id="cost_id_'.$student_cost->id.'_'. $row->id .'">';
                $student_cost_row .= '<input type="text" onchange="saveCost('.$student_cost->id.', '. $row->id .')"
                name="cost_'.$student_cost->id.'"
                value="'. $count_register .'"
                class="form-control is-number input_sudent_cost"
                id="input_sudent_cost_'.$student_cost->id.'_'. $row->id .'"
                autocomplete="off" '. $check_register .'>';

                $row->{'student_cost_'. $student_cost->id} = $student_cost_row;
            }

            $row->total_student_cost = number_format($total_student_cost, 0) . ' VNĐ';
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function getModalStudentCost(Request $request) {
        $this->validateRequest([
            'regid' => 'required',
        ], $request);
        $student_costs = StudentCost::where('status','=',1)->get();
        $register_cost = OfflineStudentCostByUser::where('register_id', '=', $request->regid)->get();
        $total_student_cost = OfflineStudentCostByUser::getTotalStudentCost($request->regid);
        return view('offline::modal.student_cost_by_user', [
            'regid' => $request->regid,
            'student_costs' => $student_costs,
            'register_cost' => $register_cost,
            'total_student_cost' => $total_student_cost
        ]);
    }

    public function saveStudentCost(Request $request){
        $register_id = $request->regid;
        $cost_id = $request->cost_id;
        $cost = str_replace(',','',$request->cost);
        $course_id = $request->course_id;
        // $notes = $request->note;
        if($cost > 0) {
            $model = OfflineStudentCostByUser::firstOrNew(['register_id' => $register_id, 'cost_id' => $cost_id, 'course_id' => $course_id]);
            $model->cost_id = $cost_id;
            $model->cost = (float) $cost;
            $model->register_id = $register_id;
            $model->course_id = $course_id;
            $model->save();
        }
        $total_student_cost = OfflineStudentCostByUser::getTotalStudentCost($register_id, $course_id);
        json_result([
            'status' => 'success',
            'message' => 'Thêm chi phí học viên thành công',
            'total_student_cost' => $total_student_cost,
        ]);
    }

    public function getDataRatingLevel($course_id, Request $request){
        $sort = $request->input('sort', 'id');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $course = OfflineCourse::find($course_id);
        $user_id = profile()->user_id;

        $query = OfflineRatingLevel::query()
            ->whereIn('id', function ($sub) use ($course_id){
                $sub->select(['offline_rating_level_id'])
                    ->from('el_offline_rating_level_object')
                    ->where('course_id', '=', $course_id)
                    ->where('object_type', '=', 1)
                    ->pluck('offline_rating_level_id')
                    ->toArray();
            })
            ->whereExists(function ($sub2) use ($course_id){
                $sub2->select(['id'])
                    ->from('el_offline_register')
                    ->where('user_id', '=', profile()->user_id)
                    ->where('course_id', '=', $course_id)
                    ->where('status', '=', 1);
            })
            ->where('course_id', '=', $course_id);

        $count = $query->count();
        $query->orderBy($sort, 'asc');
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row) {
            $check = [];
            $start_date_rating = '';
            $end_date_rating = '';
            $rating_level_url = '';
            $rating_status = 0;
            $user_completed = 0;
            $user_result = 0;
            $setting_time = 0;

            $rating_level_object = OfflineRatingLevelObject::query()
                ->where('course_id', '=', $course_id)
                ->where('offline_rating_level_id', '=', $row->id)
                ->where('object_type', '=', 1)
                ->first();
            if ($rating_level_object){
                $result = OfflineResult::query()
                    ->where('course_id', '=', $course_id)
                    ->where('user_id', '=', profile()->user_id)
                    ->where('result', '=', 1)
                    ->first();
                if ($result) {
                    $user_result = 1;
                }

                if ($rating_level_object->time_type == 1){
                    $setting_time = 1;
                    $start_date_rating = $rating_level_object->start_date;
                    $end_date_rating = $rating_level_object->end_date;
                }
                if ($rating_level_object->time_type == 2){
                    $setting_time = 1;
                    if (isset($rating_level_object->num_date)){
                        $start_date_rating = date("Y-m-d", strtotime($course->start_date)) . " +{$rating_level_object->num_date} day";
                    }else{
                        $start_date_rating= $course->start_date;
                    }
                }
                if ($rating_level_object->time_type == 3){
                    $setting_time = 1;
                    if (isset($rating_level_object->num_date)){
                        $start_date_rating = date("Y-m-d", strtotime($course->end_date)) . " +{$rating_level_object->num_date} day";
                    }else{
                        $start_date_rating = $course->end_date;
                    }
                }
                if ($rating_level_object->time_type == 4){
                    $setting_time = 1;
                    if ($result){
                        if (isset($rating_level_object->num_date)){
                            $start_date_rating = date("Y-m-d", strtotime($result->created_at)) . " +{$rating_level_object->num_date} day";
                        }else{
                            $start_date_rating = $result->created_at;
                        }
                    }
                }
                if($rating_level_object->user_completed == 1){
                    $user_completed = 1;
                }
            }

            if (empty($start_date_rating) && empty($end_date_rating) && $user_completed == 0 && $setting_time == 0){
                $rating_level_url = route('module.rating_level.course', [$course_id, 2, $row->id, $user_id]).'?rating_level_object_id='.@$rating_level_object->id.'&view_type=course';
            }else{
                if($setting_time == 1){
                    if(empty($start_date_rating) && empty($end_date_rating)){
                        $check[] = false;
                    }else{
                        if ($start_date_rating){
                            if ($start_date_rating <= now()){
                                $check[] = true;
                            }else{
                                $check[] = false;
                            }
                        }

                        if ($end_date_rating){
                            if ($end_date_rating >= now()){
                                $check[] = true;
                            }else{
                                $check[] = false;
                            }
                        }
                    }
                }

                if ($user_completed == 1){
                    if ($user_result == 1){
                        $check[] = true;
                    }else{
                        $check[] = false;
                    }
                }

                if (!in_array(false, $check)){
                    $rating_level_url = route('module.rating_level.course', [$course_id, 2, $row->id, $user_id]).'?rating_level_object_id='.@$rating_level_object->id.'&view_type=course';
                }
            }

            $user_rating_level_course = RatingLevelCourse::query()
                ->where('course_rating_level_id', '=', $row->id)
                ->where('user_id', '=', profile()->user_id)
                ->where('user_type', '=', 1)
                ->where('course_id', '=', $course_id)
                ->where('course_type', '=', 2)
                ->first();
            if ($user_rating_level_course){
                $rating_status = $user_rating_level_course->send == 1 ? 1 : 2;
                $rating_level_url = route('module.rating_level.edit_course', [$course_id, 2, $row->id, $user_rating_level_course->rating_user]).'?rating_level_object_id='.@$rating_level_object->id.'&view_type=course';
            }

            $row->course_name = $course->name;
            $row->course_time = get_date($course->start_date) . ($course->end_date ? ' đến '. get_date($course->end_date) : '');
            $row->rating_time = get_date($start_date_rating) . ($end_date_rating ? ' đến ' .get_date($end_date_rating) : '');
            $row->rating_status = $rating_status == 1 ? 'Đã đánh giá' : ($rating_status == 2 ? 'Chưa gửi đánh giá' : 'Chưa đánh giá');
            $row->rating_level_url = $rating_level_url;
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    // BÌNH LUẬN KHÓA HỌC
    public function commentCourseOffline ($id, Request $request) {
        $this->validateRequest([
            'content' => 'required|string|max:1000',
        ], $request, [
            'content' => 'Nội dung',
        ]);
        $content = $request->input('content');
        if (strpos($content, 'sex') !== false || strpos($content, 'xxx') !== false || strpos($content, 'địt') !== false){
            json_result([
                'status' => 'warning',
                'message' => 'Bình luận có từ nhạy cảm'
            ]);
        } else {
            if (empty($request->comment_id)) {
                $model = new OfflineComment();
                $model->course_id = $id;
                $model->user_id = getUserId();
                $model->content = $content;
                $save = $model->save();
            }else{
                $model = OfflineComment::findOrFail($request->comment_id);
                $model->content = $content;
                $save = $model->save();
            }
        }
        if ($save) {
            $model->created_at2 = \Carbon\Carbon::parse($model->created_at)->diffForHumans();
            $model->content = ucfirst($model->content);
            if (getUserType() == 1) {
                $profile_user = ProfileView::where('user_id', getUserId())->first(['full_name', 'avatar']);
                $profile_user->avatar = image_user($profile_user->avatar);
            } else {
                $profile_user = QuizUserSecondary::where('id', getUserId())->first(['name']);
                $profile_user->avatar = asset('images/image_default.jpg');
            }

            $course = OfflineCourse::find($id);
            $setting = UserPointSettings::where("pkey","=","offline_comment")
            ->where("item_id","=",$id)
            ->where("item_type","=","3")
            ->first();
            if ($setting && $setting->pvalue > 0) {
                $note = 'Bình luận khóa học tập trung <b>'. $course->name .' ('. $course->code .')</b>';

                $exists = UserPointResult::where("setting_id","=",$setting->id)->where("user_id","=",profile()->user_id)->whereNull("type")->first();
                if(!$exists){
                    UserPointResult::create([
                        'setting_id' => $setting->id,
                        'user_id' => profile()->user_id,
                        'content' => $note,
                        'point' => $setting->pvalue,
                        'ref' => $id,
                        'type_promotion' => 1,
                    ]);

                    $user_point = PromotionUserPoint::firstOrNew(['user_id' => profile()->user_id]);
                    $user_point->point = $user_point->point + $setting->pvalue;
                    $user_point->level_id = PromotionLevel::levelUp($user_point->point, profile()->user_id);
                    $user_point->save();
                }
            }
        }

        $total_comment = OfflineComment::where('course_id', $id)->count();

        json_result([
            'status' => 'success',
            'comment' => $model,
            'profile_user' => $profile_user,
            'total_comment' => $total_comment,
        ]);
    }

    public function deleteCommentCourseOffline($id) {
        $comment = OfflineComment::findOrFail($id)->delete();
    }

    public function zoom($id)
    {
        $full_name = Profile::fullname();
        $meeting = OfflineCourseActivityZoom::where(['course_id'=>$id])->first();
        return view('offline::frontend.zoom',[
            'meeting'=>$meeting,
            'course_id'=>$id,
            'full_name'=>$full_name,
        ]);
    }

    // TỰ ĐỘNG GHI DANH KHI CLICK VÀO HOẠT ĐỘNG KHÓA HỌC ONNLINE
    public function autoRegisterActivityOnline(Request $request)
    {
        $model = OnlineRegister::firstOrCreate(['user_id' => profile()->user_id, 'course_id' => $request->onlineId]);
        $model->user_id = profile()->user_id;
        $model->user_type = 1;
        $model->course_id = $request->onlineId;
        $model->register_form = 1;
        $model->status = 1;
        $model->approved_step = '1/1';
        $model->save();

        $checkQuizOnline = Quiz::where('course_id', $request->onlineId)->where('course_type', 1)->first();

        if(isset($checkQuizOnline)) {
            $quiz_part = QuizPart::whereQuizId($checkQuizOnline->id)->first();
            QuizRegister::query()
            ->firstOrCreate([
                'quiz_id' => $checkQuizOnline->id,
                'user_id' =>profile()->user_id,
                'type' => 1,
                'part_id' => $quiz_part->id,
            ],[
                'quiz_id' => $checkQuizOnline->id,
                'user_id' =>profile()->user_id,
                'type' => 1,
                'part_id' => $quiz_part->id,
            ]);
        }

        $linkCourseOnline = route('module.online.detail_online', [$request->onlineId]). '?courseIdOffline=' . $request->courseId;

        json_result($linkCourseOnline);
    }

    //ĐÁNH DẤU KHÓA HỌC
    public function bookmarkOffline(Request $request) {
        $checkBookmark = CourseBookmark::where(['course_id' => $request->id, 'type' => 2, 'user_id' => profile()->user_id])->exists();
        if(!$checkBookmark) {
            $saveBookmark = new CourseBookmark();
            $saveBookmark->course_id = $request->id;
            $saveBookmark->type = 2;
            $saveBookmark->user_id = profile()->user_id;
            $saveBookmark->save();

            json_result(1);
        } else {
            CourseBookmark::where(['course_id' => $request->id, 'type' => 2, 'user_id' => profile()->user_id])->delete();
            json_result(0);
        }
    }

    public function ratingTeachingOrganization($course_id, Request $request){
        $organization_user = OfflineTeachingOrganizationUser::where('course_id', $course_id)->where('user_id', profile()->user_id)->first();
        if($organization_user){
            return redirect()->route('module.offline.edit_rating_teaching_organization', [$course_id]);
        }

        $item = OfflineCourse::find($course_id);
        $template = OfflineTeachingOrganizationTemplate::where('course_id', $course_id)->first();

        $categories = OfflineTeachingOrganizationCategory::where('template_id', $template->id)->where('course_id', $course_id)->get();
        $fquestions = function($category_id) use($course_id){
            return OfflineTeachingOrganizationQuestion::where('category_id', $category_id)->where('course_id', $course_id)->get();
        };
        $fanswer = function($question_id, $is_row = 1) use($course_id){
            if($is_row == 10){
                return OfflineTeachingOrganizationAnswer::where('question_id', $question_id)->where('is_row', $is_row)->where('course_id', $course_id)->first();
            }else{
                return OfflineTeachingOrganizationAnswer::where('question_id', $question_id)->where('is_row', $is_row)->where('course_id', $course_id)->get();
            }
        };
        $fanswer_matrix = function($question_id, $answer_row_id, $answer_col_id) use($course_id){
            return OfflineTeachingOrganizationAnswerMatrix::where('question_id', $question_id)->where('answer_row_id', '=', $answer_row_id)->where('answer_col_id', '=', $answer_col_id)->where('course_id', $course_id)->first();
        };

        $register = OfflineRegister::whereCourseId($course_id)->whereUserId(profile()->user_id)->first();
        $teachers = OfflineTeacherClass::query()
            ->select([
                'teacher.id',
                'teacher.code',
                'teacher.name',
                'el_offline_teacher_class.class_id',
            ])
            ->leftJoin('el_training_teacher as teacher', 'teacher.id', '=', 'el_offline_teacher_class.teacher_id')
            ->where('el_offline_teacher_class.course_id', $course_id)
            ->where('el_offline_teacher_class.class_id', $register->class_id)
            ->get();

        return view('offline::frontend.rating_teaching_organization', [
            'template' => $template,
            'item' => $item,
            'categories' => $categories,
            'fquestions' => $fquestions,
            'fanswer' => $fanswer,
            'fanswer_matrix' => $fanswer_matrix,
            'teachers' => $teachers,
        ]);
    }

    public function editRatingTeachingOrganization($course_id, Request $request){
        $item = OfflineCourse::find($course_id);
        $organization_user = OfflineTeachingOrganizationUser::where('course_id', $course_id)->where('user_id', profile()->user_id)->first();

        $categories = OfflineTeachingOrganizationUserCategory::where('teaching_organization_user_id', $organization_user->id)->get();
        $fquestions = function($category_id, $teacher_id = null){
            return OfflineTeachingOrganizationUserQuestion::where('teaching_organization_category_id', $category_id)->where('teacher_id', $teacher_id)->get();
        };
        $fanswer = function($question_id, $is_row = 1){
            if($is_row == 10){
                return OfflineTeachingOrganizationUserAnswer::where('teaching_organization_question_id', $question_id)->where('is_row', $is_row)->first();
            }else{
                return OfflineTeachingOrganizationUserAnswer::where('teaching_organization_question_id', $question_id)->where('is_row', $is_row)->get();
            }
        };
        $fanswer_matrix = function($question_id, $answer_row_id, $answer_col_id){
            return OfflineTeachingOrganizationUserAnswerMatrix::where('teaching_organization_question_id', $question_id)->where('answer_row_id', '=', $answer_row_id)->where('answer_col_id', '=', $answer_col_id)->first();
        };

        $register = OfflineRegister::whereCourseId($course_id)->whereUserId(profile()->user_id)->first();
        $teachers = OfflineTeacherClass::query()
            ->select([
                'teacher.id',
                'teacher.code',
                'teacher.name',
                'el_offline_teacher_class.class_id',
            ])
            ->leftJoin('el_training_teacher as teacher', 'teacher.id', '=', 'el_offline_teacher_class.teacher_id')
            ->where('el_offline_teacher_class.course_id', $course_id)
            ->where('el_offline_teacher_class.class_id', $register->class_id)
            ->get();

        return view('offline::frontend.edit_rating_teaching_organization', [
            'organization_user' => $organization_user,
            'item' => $item,
            'categories' => $categories,
            'fquestions' => $fquestions,
            'fanswer' => $fanswer,
            'fanswer_matrix' => $fanswer_matrix,
            'teachers' => $teachers,
        ]);
    }

    public function saveRatingTeachingOrganization($course_id, Request $request){
        $user_id = profile()->user_id;
        $register = OfflineRegister::whereCourseId($course_id)->whereUserId($user_id)->first('class_id');

        $organization_user = OfflineTeachingOrganizationUser::where('course_id', $course_id)->where('user_id', $user_id)->first();
        if($organization_user){
            json_result([
                'status' => 'warning',
                'message' => 'Bạn đã làm đánh giá này!',
                'redirect' => route('module.offline.edit_rating_teaching_organization', [$course_id]),
            ]);
        }

        $teaching_organization_id = $request->teaching_organization_id;
        $template_id = $request->template_id;

        $teacher_id = $request->teacher_id;

        $user_category_id = $request->user_category_id;
        $category_id = $request->category_id;
        $category_name = $request->category_name;
        $rating_teacher = $request->rating_teacher;

        $user_question_id = $request->user_question_id;
        $question_id = $request->question_id;
        $question_code = $request->question_code;
        $question_name = $request->question_name;
        $type = $request->type;
        $multiple = $request->multiple;
        $answer_essay = $request->answer_essay;

        $user_answer_id = $request->user_answer_id;
        $answer_id = $request->answer_id;
        $answer_code = $request->answer_code;
        $answer_name = $request->answer_name;
        $is_text = $request->is_text;
        $text_answer = $request->text_answer;
        $is_check = $request->is_check;
        $is_row = $request->is_row;
        $answer_matrix = $request->answer_matrix;
        $check_answer_matrix = $request->check_answer_matrix;
        $answer_icon = $request->icon;
        $answer_matrix_code = $request->answer_matrix_code;

        $send = $request->send;

        if($send == 1) {
            foreach ($category_id as $key => $category) {
                $questions = OfflineTeachingOrganizationQuestion::where('course_id', $course_id)
                ->where('category_id', $category)
                ->where('obligatory', 1)
                ->get(['id', 'obligatory', 'type', 'name']);

                if($rating_teacher[$category] == 1){
                    foreach($teacher_id[$category] as $teacher){
                        foreach ($questions as $key => $question) {
                            $answerEssay = $answer_essay[$category][$teacher][$question->id];
                            if (!empty($answerEssay)) {
                                $userAnswer = 1;
                            } else {
                                $userAnswer = 0;
                            }

                            if($userAnswer == 0) {
                                json_result([
                                    'status' => 'warning',
                                    'message' => 'Câu hỏi: '. $question->name .' là câu hỏi bắt buộc. Vui lòng bạn trả lời',
                                ]);
                            }
                        }
                    }
                }else{
                    foreach ($questions as $key => $question) {
                        $answerEssay = $answer_essay[$category][$question->id];
                        $check = $is_check[$category][$question->id];
                        $userAnswer = 0;
                        if (!empty($answerEssay) || !empty($check)) {
                            $userAnswer = 1;
                        } else {
                            foreach($answer_id[$category][$question->id] as $ans_key => $ans_id){
                                if($question->type == 'matrix' && isset($check_answer_matrix[$category][$question->id][$ans_id])) {
                                    $checkAnswerMatrix = $check_answer_matrix[$category][$question->id][$ans_id];
                                    foreach ($checkAnswerMatrix as $key => $checkMatrix) {
                                        if(isset($checkMatrix)) {
                                            $userAnswer = 1;
                                        }
                                    }
                                } else if ($question->type == 'matrix_text' && isset($answer_matrix[$category][$question->id][$ans_id])) {
                                    $answerMatrix = $answer_matrix[$category][$question->id][$ans_id];
                                    foreach ($answerMatrix as $key => $answer) {
                                        if(isset($answer)) {
                                            $userAnswer = 1;
                                        }
                                    }
                                } else {
                                    $textAnswer = $text_answer[$category][$question->id][$ans_id];
                                    if(!empty($textAnswer)) {
                                        $userAnswer = 1;
                                    }
                                }
                            }
                        }

                        if($userAnswer == 0) {
                            json_result([
                                'status' => 'warning',
                                'message' => 'Câu hỏi: '. $question->name .' là câu hỏi bắt buộc. Vui lòng bạn trả lời',
                            ]);
                        }
                    }
                }

            }
        }

        $arr_star_teacher = [];

        $model = OfflineTeachingOrganizationUser::firstOrNew(['id' => $teaching_organization_id]);
        $model->user_id = $user_id;
        $model->course_id = $course_id;
        $model->send = $send;
        $model->template_id = $template_id;
        $model->save();

        foreach($category_id as $cate_key => $cate_id){
            $categories = OfflineTeachingOrganizationUserCategory::firstOrNew(['id' => $user_category_id[$cate_key]]);
            $categories->teaching_organization_user_id = $model->id;
            $categories->category_id = $cate_id;
            $categories->category_name = $category_name[$cate_id];
            $categories->rating_teacher = $rating_teacher[$cate_id];
            $categories->save();

            if($categories->rating_teacher == 1){
                foreach($teacher_id[$cate_id] as $teacher){
                    $num_star = 0;
                    $num_question = 0;
                    if(isset($question_id[$cate_id][$teacher])){
                        foreach($question_id[$cate_id][$teacher] as $ques_key => $ques_id){
                            $user_ques_id = $user_question_id[$cate_id][$teacher][$ques_key];
                            $ques_code = $question_code[$cate_id][$teacher][$ques_id];
                            $ques_name = $question_name[$cate_id][$teacher][$ques_id];

                            $course_question = OfflineTeachingOrganizationUserQuestion::firstOrNew(['id' => $user_ques_id, 'teacher_id' => $teacher]);
                            $course_question->teaching_organization_category_id = $categories->id;
                            $course_question->question_id = $ques_id;
                            $course_question->question_code = isset($ques_code) ? $ques_code : null;
                            $course_question->question_name = $ques_name;
                            $course_question->type = $type[$cate_id][$teacher][$ques_id];
                            $course_question->multiple = $multiple[$cate_id][$teacher][$ques_id];
                            $course_question->answer_essay = isset($answer_essay[$cate_id][$teacher][$ques_id]) ? $answer_essay[$cate_id][$teacher][$ques_id] : '';
                            $course_question->teacher_id = $teacher;
                            $course_question->save();

                            if($course_question->type == 'rank'){
                                $ans_code = $answer_code[$cate_id][$teacher][$ques_id][$course_question->answer_essay];
                                $num_star += (isset($ans_code) ? (int)$ans_code : 0);
                                $num_question += 1;
                            }

                            if(isset($answer_id[$cate_id][$teacher][$ques_id])){
                                foreach($answer_id[$cate_id][$teacher][$ques_id] as $ans_key => $ans_id){
                                    $user_ans_id = $user_answer_id[$cate_id][$teacher][$ques_id][$ans_key];
                                    $ans_code = $answer_code[$cate_id][$teacher][$ques_id][$ans_id];
                                    $ans_name = $answer_name[$cate_id][$teacher][$ques_id][$ans_id];
                                    $text = $is_text[$cate_id][$teacher][$ques_id][$ans_id];
                                    $row = $is_row[$cate_id][$teacher][$ques_id][$ans_id];
                                    $icon = $answer_icon[$cate_id][$teacher][$ques_id][$ans_id];

                                    $course_answer = OfflineTeachingOrganizationUserAnswer::firstOrNew(['id' => $user_ans_id]);
                                    $course_answer->teaching_organization_question_id = $course_question->id;
                                    $course_answer->answer_id = $ans_id;
                                    $course_answer->answer_code = isset($ans_code) ? $ans_code : '';
                                    $course_answer->answer_name = isset($ans_name) ? $ans_name : '';
                                    $course_answer->is_text = $text;
                                    $course_answer->is_row = $row;
                                    $course_answer->icon = isset($icon) ? $icon : null;
                                    $course_answer->is_check = 0;
                                    $course_answer->text_answer = '';
                                    $course_answer->answer_matrix = '';
                                    $course_answer->check_answer_matrix = '';
                                    $course_answer->save();
                                }
                            }
                        }
                    }

                    $arr_star_teacher[$teacher] = ($num_star > 0 ? $num_star/$num_question : 0);
                }
            }else{
                if(isset($question_id[$cate_id])){
                    foreach($question_id[$cate_id] as $ques_key => $ques_id){
                        $user_ques_id = $user_question_id[$cate_id][$ques_key];
                        $ques_code = $question_code[$cate_id][$ques_id];
                        $ques_name = $question_name[$cate_id][$ques_id];

                        $course_question = OfflineTeachingOrganizationUserQuestion::firstOrNew(['id' => $user_ques_id]);
                        $course_question->teaching_organization_category_id = $categories->id;
                        $course_question->question_id = $ques_id;
                        $course_question->question_code = isset($ques_code) ? $ques_code : null;
                        $course_question->question_name = $ques_name;
                        $course_question->type = $type[$cate_id][$ques_id];
                        $course_question->multiple = $multiple[$cate_id][$ques_id];
                        $course_question->answer_essay = isset($answer_essay[$cate_id][$ques_id]) ? $answer_essay[$cate_id][$ques_id] : '';
                        $course_question->save();

                        if(isset($answer_id[$cate_id][$ques_id])){
                            if($course_question->type == 'percent'){
                                $total = 0;
                                $arr_answer_percent = $text_answer[$cate_id][$ques_id];
                                foreach ($arr_answer_percent as $percent){
                                    $total += preg_replace("/[^0-9]/", '', $percent);
                                }

                                if ($total > 100){
                                    $errors[] = 'Tổng phần trăm câu hỏi: "'. $ques_name . '" vượt quá 100';
                                }
                            }

                            foreach($answer_id[$cate_id][$ques_id] as $ans_key => $ans_id){
                                $user_ans_id = $user_answer_id[$cate_id][$ques_id][$ans_key];
                                $ans_code = $answer_code[$cate_id][$ques_id][$ans_id];
                                $ans_name = $answer_name[$cate_id][$ques_id][$ans_id];
                                $text = $is_text[$cate_id][$ques_id][$ans_id];
                                $row = $is_row[$cate_id][$ques_id][$ans_id];
                                $icon = $answer_icon[$cate_id][$ques_id][$ans_id];

                                $course_answer = OfflineTeachingOrganizationUserAnswer::firstOrNew(['id' => $user_ans_id]);
                                $course_answer->teaching_organization_question_id = $course_question->id;
                                $course_answer->answer_id = $ans_id;
                                $course_answer->answer_code = isset($ans_code) ? $ans_code : '';
                                $course_answer->answer_name = isset($ans_name) ? $ans_name : '';
                                $course_answer->is_text = $text;
                                $course_answer->is_row = $row;
                                $course_answer->icon = isset($icon) ? $icon : null;

                                if ($course_question->multiple == 1){
                                    $course_answer->is_check = isset($is_check[$cate_id][$ques_id][$ans_id]) ? $is_check[$cate_id][$ques_id][$ans_id] : 0;
                                }else{
                                    if (isset($is_check[$cate_id][$ques_id]) && ($ans_id == $is_check[$cate_id][$ques_id])){
                                        $course_answer->is_check = $ans_id;
                                    }else{
                                        $course_answer->is_check = 0;
                                    }
                                }

                                if($course_question->type == 'percent'){
                                    $course_answer->text_answer = isset($text_answer[$cate_id][$ques_id][$ans_id]) && array_sum($text_answer[$cate_id][$ques_id]) <= 100 ? $text_answer[$cate_id][$ques_id][$ans_id] : '';
                                }else{
                                    $course_answer->text_answer = isset($text_answer[$cate_id][$ques_id][$ans_id]) ? $text_answer[$cate_id][$ques_id][$ans_id] : '';
                                }

                                $course_answer->answer_matrix = isset($answer_matrix[$cate_id][$ques_id][$ans_id]) ? json_encode($answer_matrix[$cate_id][$ques_id][$ans_id]) : '';

                                $course_answer->check_answer_matrix = isset($check_answer_matrix[$cate_id][$ques_id][$ans_id]) ? json_encode($check_answer_matrix[$cate_id][$ques_id][$ans_id]) : '';

                                $course_answer->save();
                            }
                        }

                        if (($course_question->type == 'matrix' && $course_question->multiple == 1) || $course_question->type == 'matrix_text'){
                            if(isset($answer_matrix_code[$cate_id][$ques_id])) {
                                foreach ($answer_matrix_code[$cate_id][$ques_id] as $ans_key => $matrix) {
                                    foreach ($matrix as $matrix_key => $matrix_code){
                                        OfflineTeachingOrganizationUserAnswerMatrix::query()
                                            ->updateOrCreate([
                                                'teaching_organization_question_id' => $course_question->id,
                                                'answer_row_id' => $ans_key,
                                                'answer_col_id' => $matrix_key
                                            ],[
                                                'teaching_organization_question_id' => $course_question->id,
                                                'answer_row_id' => $ans_key,
                                                'answer_col_id' => $matrix_key,
                                                'answer_code' => $matrix_code
                                            ]);
                                    }
                                }
                            }
                        }

                    }
                }
            }
        }

        if(count($arr_star_teacher)> 0){
            foreach($arr_star_teacher as $teacher => $star_teacher){
                TrainingTeacherStar::updateOrCreate([
                    'user_id' => $user_id,
                    'teacher_id' => $teacher,
                    'course_id' => $course_id,
                    'course_type' => 2,
                    'class_id' => $register->class_id,
                ],[
                    'num_star' => (int)$star_teacher,
                ]);
            }
        }

        json_result([
            'status' => 'success',
            'message' => 'Lưu thành công',
            'redirect' => route('module.offline.edit_rating_teaching_organization', [$course_id]),
        ]);
    }

    public function ajaxRatingStar($course_id){
        $f_rating_star = function($num_star) use ($course_id){
            return OfflineRating::getRatingValue($course_id, $num_star);
        };

        return view('offline::modal.modal_rating_star',[
            'f_rating_star' => $f_rating_star,
            'course_id' => $course_id,
        ]);
    }

    public function ajaxDocument($course_id, Request $request) {

        return view('offline::modal.modal_document',[
            'course_id' => $course_id
        ]);
    }

    public function getDataDocument($course_id, Request $request) {
        $search = $request->input('search');

        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = OfflineCourseDocument::query();
        $query->where('course_id',$course_id);

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();

        foreach($rows as $row){
            $row->link_download = link_download('uploads/'.$row->document);
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    //Lấy dữ liệu khảo sát từng hoạt động
    public function getSurveyUser($course_id, $course_activity_id){
        $template = OfflineSurveyTemplate::where(['course_id' => $course_id, 'course_activity_id' => $course_activity_id])->first();

        return view('offline::survey.survey', [
            'template' => $template,
            'course_id' => $course_id,
            'course_activity_id' => $course_activity_id,
        ]);
    }

    public function editSurveyUser($course_id, $course_activity_id, $user_id){
        $survey_user = OfflineSurveyUser::where(['course_id' => $course_id, 'course_activity_id' => $course_activity_id, 'user_id' => $user_id])->first();

        $survey_user_categories = OfflineSurveyUserCategory::where(['survey_user_id' => $survey_user->id])->get();

        return view('offline::survey.edit_survey', [
            'survey_user' => $survey_user,
            'survey_user_categories' => $survey_user_categories,
            'course_id' => $course_id,
            'course_activity_id' => $course_activity_id,
        ]);
    }

    public function saveOfflineSurveyUser(Request $request){
        $survey_user_id = $request->survey_user_id;
        $template_id = $request->template_id;
        $course_id = $request->course_id;
        $course_activity_id = $request->course_activity_id;

        $user_category_id = $request->user_category_id;
        $category_id = $request->category_id;
        $category_name = $request->category_name;

        $user_question_id = $request->user_question_id;
        $question_id = $request->question_id;
        $question_code = $request->question_code;
        $question_name = $request->question_name;
        $type = $request->type;
        $multiple = $request->multiple;
        $answer_essay = $request->answer_essay;

        $user_answer_id = $request->user_answer_id;
        $answer_id = $request->answer_id;
        $answer_code = $request->answer_code;
        $answer_name = $request->answer_name;
        $is_text = $request->is_text;
        $text_answer = $request->text_answer;
        $is_check = $request->is_check;
        $is_row = $request->is_row;
        $answer_matrix = $request->answer_matrix;
        $check_answer_matrix = $request->check_answer_matrix;
        $answer_icon = $request->icon;

        $send = $request->send;
        $answer_matrix_code = $request->answer_matrix_code;
        $user_id = profile()->user_id;

        foreach ($category_id as $key => $category) {
            $questions = OfflineSurveyQuestion::where('category_id', $category)->get(['id', 'obligatory', 'type', 'name']);
            foreach ($questions as $key => $question) {
                if ($question->obligatory == 0) {
                    continue;
                } else {
                    $answerEssay = $answer_essay[$category][$question->id];
                    $check = $is_check[$category][$question->id];
                    $userAnswer = 0;
                    if (!empty($answerEssay) || !empty($check)) {
                        $userAnswer = 1;
                    } else {
                        foreach($answer_id[$category][$question->id] as $ans_key => $ans_id){
                            if($question->type == 'matrix' && isset($check_answer_matrix[$category][$question->id][$ans_id])) {
                                $checkAnswerMatrix = $check_answer_matrix[$category][$question->id][$ans_id];
                                foreach ($checkAnswerMatrix as $key => $checkMatrix) {
                                    if(isset($checkMatrix)) {
                                        $userAnswer = 1;
                                    }
                                }
                            } else if ($question->type == 'matrix_text' && isset($answer_matrix[$category][$question->id][$ans_id])) {
                                $answerMatrix = $answer_matrix[$category][$question->id][$ans_id];
                                foreach ($answerMatrix as $key => $answer) {
                                    if(isset($answer)) {
                                        $userAnswer = 1;
                                    }
                                }
                            } else {
                                $checkAns = $is_check[$category][$question->id][$ans_id];
                                $textAnswer = $text_answer[$category][$question->id][$ans_id];
                                if(!empty($checkAns) || !empty($textAnswer)) {
                                    $userAnswer = 1;
                                }
                            }
                        }
                    }
                    if($userAnswer == 0) {
                        json_result([
                            'status' => 'warning',
                            'message' => 'Câu hỏi: '. $question->name .' là câu hỏi bắt buộc. Vui lòng bạn trả lời',
                        ]);
                    }
                }
                if($question->type == 'percent'){
                    $total = 0;
                    $arr_answer_percent = $text_answer[$category][$question->id];
                    foreach ($arr_answer_percent as $percent){
                        $total += preg_replace("/[^0-9]/", '', $percent);
                    }
                    if ($total > 100){
                        json_result([
                            'status' => 'warning',
                            'message' => 'Tổng phần trăm câu hỏi: '. $question->name .' vượt quá 100%',
                        ]);
                    }
                }
            }
        }

        $model = OfflineSurveyUser::firstOrNew(['id' => $survey_user_id]);
        $model->template_id = $template_id;
        $model->user_id = $user_id;
        $model->course_id = $course_id;
        $model->course_activity_id = $course_activity_id;
        $model->send = $send;
        $model->save();

        foreach($category_id as $cate_key => $cate_id){
            $categories = OfflineSurveyUserCategory::firstOrNew(['id' => $user_category_id[$cate_key]]);
            $categories->survey_user_id = $model->id;
            $categories->category_id = $cate_id;
            $categories->category_name = $category_name[$cate_id];
            $categories->save();

            if(isset($question_id[$cate_id])){
                foreach($question_id[$cate_id] as $ques_key => $ques_id){
                    $user_ques_id = $user_question_id[$cate_id][$ques_key];
                    $ques_code = $question_code[$cate_id][$ques_id];
                    $ques_name = $question_name[$cate_id][$ques_id];

                    $course_question = OfflineSurveyUserQuestion::firstOrNew(['id' => $user_ques_id]);
                    $course_question->survey_user_category_id = $categories->id;
                    $course_question->question_id = $ques_id;
                    $course_question->question_code = isset($ques_code) ? $ques_code : null;
                    $course_question->question_name = $ques_name;
                    $course_question->type = $type[$cate_id][$ques_id];
                    $course_question->multiple = $multiple[$cate_id][$ques_id];
                    $course_question->answer_essay = isset($answer_essay[$cate_id][$ques_id]) ? $answer_essay[$cate_id][$ques_id] : '';
                    $course_question->save();

                    if(isset($answer_id[$cate_id][$ques_id])){
                        foreach($answer_id[$cate_id][$ques_id] as $ans_key => $ans_id){
                            $user_ans_id = $user_answer_id[$cate_id][$ques_id][$ans_key];
                            $ans_code = $answer_code[$cate_id][$ques_id][$ans_id];
                            $ans_name = $answer_name[$cate_id][$ques_id][$ans_id];
                            $text = $is_text[$cate_id][$ques_id][$ans_id];
                            $row = $is_row[$cate_id][$ques_id][$ans_id];
                            $icon = $answer_icon[$cate_id][$ques_id][$ans_id];

                            $course_answer = OfflineSurveyUserAnswer::firstOrNew(['id' => $user_ans_id]);
                            $course_answer->survey_user_question_id = $course_question->id;
                            $course_answer->answer_id = $ans_id;
                            $course_answer->answer_code = isset($ans_code) ? $ans_code : '';
                            $course_answer->answer_name = isset($ans_name) ? $ans_name : '';
                            $course_answer->is_text = $text;
                            $course_answer->is_row = $row;
                            $course_answer->icon = isset($icon) ? $icon : null;

                            if ($course_question->multiple == 1){
                                $course_answer->is_check = isset($is_check[$cate_id][$ques_id][$ans_id]) ? $is_check[$cate_id][$ques_id][$ans_id] : 0;
                            }else{
                                if (isset($is_check[$cate_id][$ques_id]) && ($ans_id == $is_check[$cate_id][$ques_id])){
                                    $course_answer->is_check = $ans_id;
                                }else{
                                    $course_answer->is_check = 0;
                                }
                            }

                            if($course_question->type == 'percent'){
                                $course_answer->text_answer = isset($text_answer[$cate_id][$ques_id][$ans_id]) && array_sum($text_answer[$cate_id][$ques_id]) <= 100 ? $text_answer[$cate_id][$ques_id][$ans_id] : '';
                            }else{
                                $course_answer->text_answer = isset($text_answer[$cate_id][$ques_id][$ans_id]) ? $text_answer[$cate_id][$ques_id][$ans_id] : '';
                            }

                            $course_answer->answer_matrix = isset($answer_matrix[$cate_id][$ques_id][$ans_id]) ? json_encode($answer_matrix[$cate_id][$ques_id][$ans_id]) : '';

                            $course_answer->check_answer_matrix = isset($check_answer_matrix[$cate_id][$ques_id][$ans_id]) ? json_encode($check_answer_matrix[$cate_id][$ques_id][$ans_id]) : '';

                            $course_answer->save();
                        }
                    }

                    if (($course_question->type == 'matrix' && $course_question->multiple == 1) || $course_question->type == 'matrix_text'){
                        if(isset($answer_matrix_code[$cate_id][$ques_id])) {
                            foreach ($answer_matrix_code[$cate_id][$ques_id] as $ans_key => $matrix) {
                                foreach ($matrix as $matrix_key => $matrix_code){
                                    OfflineSurveyUserAnswerMatrix::query()
                                    ->updateOrCreate([
                                        'survey_user_question_id' => $course_question->id,
                                        'answer_row_id' => $ans_key,
                                        'answer_col_id' => $matrix_key
                                    ],[
                                        'survey_user_question_id' => $course_question->id,
                                        'answer_row_id' => $ans_key,
                                        'answer_col_id' => $matrix_key,
                                        'answer_code' => $matrix_code
                                    ]);
                                }
                            }
                        }
                    }

                }
            }
        }

        if ($send == 1){
            $completion = OfflineCourseActivityCompletion::firstOrNew([
                'user_id' => $user_id,
                'course_id' => $course_id,
                'activity_id' => $course_activity_id,
            ]);
            $completion->user_id = $user_id;
            $completion->user_type = 1;
            $completion->activity_id = $course_activity_id;
            $completion->course_id = $course_id;
            $completion->status = 1;
            $completion->save();

            if($completion->status == 1){
                \Artisan::call('command:offline_complete '.$user_id .' '.$course_id);
            }

            json_result([
                'status' => 'success',
                'message' => 'Đã gửi thành công',
            ]);
        }else{
            json_result([
                'status' => 'success',
                'message' => trans('laother.successful_save'),
            ]);
        }
    }

    public function ajaxHistoryActivityCourse($course_id, Request $request){
        $user_id = profile()->user_id;
        $user_type = profile()->type_user;
        $schedule_id = $request->schedule_id;

        $register = OfflineRegister::where('user_id', '=', $user_id)->where('course_id', '=', $course_id)->where('status', '=', 1)->first();
        $get_activity_courses = OfflineCourseActivity::getByCourse($course_id, $register->class_id, $schedule_id);

        return view('offline::frontend.history_activity_course', [
            'user_id' => $user_id,
            'user_type' => $user_type,
            'get_activity_courses' => $get_activity_courses,
        ]);
    }
}
