<?php

namespace Modules\Online\Http\Controllers;

use Carbon\Carbon;
use App\Models\Automail;
use App\Models\Categories\UnitManager;
use App\Models\Config;
use App\Helpers\VideoStream;
use App\Models\Categories\LevelSubject;
use App\Models\PermissionTypeUnit;
use App\Models\Profile;
use App\Models\ProfileView;
use App\Events\Online\GoActivity;
use App\Models\Categories\Unit;
use App\Models\Permission;
use App\Models\Categories\Subject;
use App\Models\Categories\TrainingProgram;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\AppNotification\Helpers\AppNotification;
use Modules\LogViewCourse\Entities\LogViewCourse;
use Modules\Notify\Entities\Notify;
use Modules\Online\Entities\OnlineCourse;
use Modules\Online\Entities\OnlineCourseActivity;
use Modules\Online\Entities\OnlineCourseActivityXapi;
use Modules\Online\Entities\OnlineCourseActivityZoom;
use Modules\Online\Entities\OnlineRating;
use Modules\Online\Entities\OnlineCourseNote;
use Modules\Online\Entities\OnlineRatingLevel;
use Modules\Online\Entities\OnlineRatingLevelObject;
use Modules\Online\Entities\OnlineRegister;
use Illuminate\Support\Facades\Auth;
use Modules\PointHist\Entities\PointHist;
use Modules\Promotion\Entities\PromotionCourseSetting;
use Modules\Promotion\Entities\PromotionLevel;
use Modules\Promotion\Entities\PromotionShare;
use Modules\Promotion\Entities\PromotionUserHistory;
use Modules\Promotion\Entities\PromotionUserPoint;
use Modules\Quiz\Entities\Quiz;
use Modules\Quiz\Entities\QuizPart;
use Modules\Quiz\Entities\QuizRegister;
use Modules\Rating\Entities\RatingCourse;
use Modules\Rating\Entities\RatingLevelCourse;
use Modules\RefererHist\Entities\RefererRegisterCourse;
use Modules\User\Entities\TrainingProcess;
use App\Models\Categories\Titles;
use App\Models\Categories\TrainingForm;
use Modules\Online\Entities\OnlineObject;
use Modules\Online\Entities\OnlineCourseLesson;
use Modules\Online\Entities\OnlineCourseComplete;
use App\Models\InteractionHistory;
use Modules\Online\Entities\OnlineCourseActivityFile;
use Modules\Online\Entities\OnlineCourseActivityVideo;
use Modules\Online\Entities\OnlineCourseActivityUrl;
use Modules\Online\Entities\OnlineCourseActivityQuiz;
use Modules\Online\Entities\OnlineCourseActivityScorm;
use Modules\Online\Entities\OnlineViewActivity;
use Modules\Online\Entities\OnlineResult;
use App\Models\UserViewCourse;
use Modules\Online\Entities\OnlineCourseActivityHistory;
use Modules\Online\Entities\OnlineCourseCondition;
use Modules\Online\Entities\OnlineCourseTimeUserLearn;
use Modules\Online\Entities\OnlineCourseActivityCompletion;
use Modules\Online\Entities\UserBookmarkActivity;
use Jenssegers\Agent\Agent;
use Modules\Quiz\Entities\QuizResult;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Modules\Online\Console\OnlineComplete;
use Modules\Online\Entities\OnlineComment;
use Modules\Quiz\Entities\QuizUserSecondary;
use Modules\Online\Entities\ActivityScormScore;
use Modules\Online\Entities\OnlineFinishVideo;
use Modules\Online\Entities\SettingJoinOnlineCourse;
use Modules\UserPoint\Entities\UserPointResult;
use Modules\UserPoint\Entities\UserPointSettings;
use App\Events\SaveTrainingProcessRegister;
use App\Models\CourseBookmark;
use Modules\Notify\Entities\NotifyTemplate;
use Modules\Online\Entities\OnlineCourseActivitySurvey;
use Modules\Online\Entities\OnlineSurveyTemplate;
use Modules\Online\Entities\OnlineSurveyUser;
use Modules\Online\Entities\OnlineSurveyUserAnswer;
use Modules\Online\Entities\OnlineSurveyUserAnswerMatrix;
use Modules\Online\Entities\OnlineSurveyUserCategory;
use Modules\Online\Entities\OnlineSurveyUserQuestion;
use Modules\Online\Entities\OnlineSurveyQuestion;
use Modules\PermissionApproved\Entities\PermissionApprovedUser;
use App\Models\SubjectPrerequisiteCourse;
use Modules\Online\Entities\OnlineCourseDocument;
use Modules\User\Entities\UserCompletedSubject;

class FrontendController extends Controller
{
    public function index(Request $request) {
        $items = $this->getItems($request);

        return view('online::frontend.index', [
            'items' => $items,
        ]);
    }

    public function getItems(Request $request) {
        $search = $request->get('q');
        $fromdate = $request->get('fromdate');
        $todate = $request->get('todate');
        $training_program_id = $request->get('training_program_id');
        $level_subject_id = $request->get('level_subject_id');
        $subject_id = $request->get('subject_id');
        $status = $request->get('status');
        $user_id = profile()->user_id;

        $query = OnlineCourse::query();
        $query->select(['a.*']);
        $query->from('el_online_course as a');
        if($status && $status !== 5 && $status !== 4) {
            $query->leftjoin('el_online_register as b','b.course_id','=','a.id');
        }
        $query->where('a.status', '=', 1);
        $query->where('a.isopen', '=', 1);
        $query->where('a.offline', '=', 0);

        $get_course_id_register = OnlineRegister::where('user_id',$user_id)->pluck('course_id')->toArray();
        $get_course_id_complete = OnlineCourseComplete::where('user_id',$user_id)->pluck('course_id')->toArray();

        if($status && $status == 1) {
            $query->whereNotIn('a.id', $get_course_id_register);

            $query->where(function ($sub){
                $sub->whereNull('end_date');
                $sub->orWhere('end_date', '>', date('Y-m-d'));
            });

        } elseif($status && $status == 2) {
            $query->where('b.user_id', $user_id);
            $query->where('b.status',1);
        } elseif($status && $status == 3) {
            $query->where('b.user_id', $user_id);
            $query->where('b.status',2);
        } elseif($status && $status == 4) {
            $query->leftjoin('el_online_course_complete as c','c.course_id','=','a.id');
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

        if ($level_subject_id){
            $query->where('a.level_subject_id', '=', $level_subject_id);
        }

        if ($subject_id) {
            $query->where('a.subject_id', '=', $subject_id);
        }

        $query->orderByDesc('a.id');
        $items = $query->paginate(20);
        $items->appends($request->query());

        return $items;
    }

    public function getData(Request $request) {
        $search = $request->get('search');
        $fromdate = $request->get('fromdate');
        $todate = $request->get('todate');
        $training_program_id = $request->get('training_program_id');
        $subject_id = $request->get('subject_id');
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = OnlineCourse::query();
        $query->where('status', '=', 1);
        $query->where('isopen', '=', 1);

        if ($search) {
            $query->where(function ($subquery) use ($search) {
                $subquery->orWhere('code', 'like', '%'. $search .'%');
                $subquery->orWhere('name', 'like', '%'. $search .'%');
            });
        }

        if ($fromdate) {
            $query->where('start_date', '>=', date_convert($fromdate, '00:00:00'));
        }

        if ($todate) {
            $query->where('start_date', '<=', date_convert($todate, '23:59:59'));
        }

        if ($training_program_id) {
            $query->where('training_program_id', '=', $training_program_id);
        }

        if ($subject_id) {
            $query->where('subject_id', '=', $subject_id);
        }

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        return \response()->json([
            'total' => $count,
            'rows' => $rows
        ]);
    }

    public function detailFirst($id, Request $request){
        $user_id = profile()->user_id;
        $item = OnlineCourse::where('id', '=', $id)->where('status', '=', 1)->where('isopen', '=', 1)->firstOrFail();
        OnlineCourse::updateItemViews($item->id, $item->views);
        if($item->image) {
            $item->image = 'detail/'. $item->image;
        }
        $register = OnlineRegister::where('user_id', '=', $user_id)->where('course_id', '=', $id)->where('status', '=', 1)->first();
        $subject_prerequisite_course = SubjectPrerequisiteCourse::where(['course_id' => $id, 'course_type' => 1])->first();
        $comments = OnlineComment::select([
            'a.*',
            \DB::raw("CONCAT_WS(' ',lastname, firstname) AS fullname"),
            'b.avatar',
        ])
            ->from('el_online_comment AS a')
            ->leftJoin('el_profile AS b', 'b.user_id', '=', 'a.user_id')
            ->where('a.course_id', '=', $id)
            ->orderBy('a.id', 'desc')
            ->get();

        $condition_arr = [];
        $condition = OnlineCourseCondition::where('course_id', '=', $id)->first();
        if(!empty($condition)) {
            $condition_arr = explode(',',$condition->activity);
        }

        $online_activity = OnlineCourseActivity::where('course_id',$id)->whereIn('id', $condition_arr)->get();

        $online_rating_level = OnlineRatingLevel::query()
            ->whereIn('id', function ($sub) use ($id){
                $sub->select(['online_rating_level_id'])
                    ->from('el_online_rating_level_object')
                    ->where('course_id', '=', $id)
                    ->where('object_type', '=', 1)
                    ->pluck('online_rating_level_id')
                    ->toArray();
            })
            ->where('course_id', '=', $id)->first();

        $online_course_document = OnlineCourseDocument::where('course_id', $id)->exists();

        return view('online::frontend.detail_first', [
            'item' => $item,
            'register' => $register,
            'subject_prerequisite_course' => $subject_prerequisite_course,
            'comments' => $comments,
            'condition' => $condition,
            'online_rating_level' => $online_rating_level,
            'online_activity' => $online_activity,
            'online_course_document' => $online_course_document,
        ]);
    }

    public function ajaxRatingLevelOnline($course_id, Request $request) {

        return view('online::modal.modal_rating_level',[
            'course_id' => $course_id
        ]);
    }

    public function detailNew($id, Request $request){
        $user_id = profile()->user_id;

        $course = OnlineCourse::where('id', '=', $id)
        ->where('isopen', '=', 1)
        ->where('status', '=', 1)
        ->firstOrFail();

        if($course->auto == 2) {
            $this->autoRegisterCourse($user_id, $id);
        }

        $check_register = OnlineRegister::select('status')->where('course_id',$id)->where('user_id',$user_id)->first();

        $time_user_view_course = UserViewCourse::firstOrNew(['course_id' => $id, 'course_type' => 1, 'user_id' => $user_id]);
        $time_user_view_course->course_id = $id;
        $time_user_view_course->course_type = 1;
        $time_user_view_course->user_id = $user_id;
        $time_user_view_course->time_view = date('Y-m-d H:i');
        if(!empty($check_register) && $check_register->status == 1) {
            $time_user_view_course->count_user_view = $time_user_view_course->count_user_view + 1;
        }
        $time_user_view_course->save();

        $date_join = OnlineCourseActivityHistory::where('course_id', $id)->where('user_id', $user_id)->first(['created_at']);

        $timeUserLearn = OnlineCourseTimeUserLearn::where('user_id', $user_id)->where('course_id', $id)->first();
        $totalTimeLearn = isset($timeUserLearn) && $timeUserLearn->time > 0 ? gmdate("H:i:s", $timeUserLearn->time) : 0;

        $get_result = OnlineResult::where('course_id',$id)->where('user_id',$user_id)->first(['score','result']);

        $profile = ProfileView::select(['full_name','user_id'])->where('user_id', '=', $user_id)->first(['user_id', 'full_name']);

        $lessons_course = OnlineCourseLesson::where('course_id',$id)->get();

        $comments = OnlineComment::select([
            'a.*',
            \DB::raw("CONCAT_WS(' ',lastname, firstname) AS fullname"),
            'b.avatar',
            'c.name',
        ])
            ->from('el_online_comment AS a')
            ->leftJoin('el_profile AS b', function ($sub){
                $sub->on('b.user_id', '=', 'a.user_id')
                    ->where('a.user_type', '=', 1);
            })
            ->leftJoin('el_quiz_user_secondary AS c', function ($sub){
                $sub->on('c.id', '=', 'a.user_id')
                    ->where('a.user_type', '=', 2);
            })
            ->where('a.course_id', '=', $id)
            ->orderBy('a.id', 'desc')
            ->get();

        $count_rating_level = OnlineRatingLevel::query()
            ->whereIn('id', function ($sub) use ($id){
                $sub->select(['online_rating_level_id'])
                    ->from('el_online_rating_level_object')
                    ->where('course_id', '=', $id)
                    ->where('object_type', '=', 1)
                    ->where(function ($sub2){
                        $sub2->orWhereNull('end_date');
                        $sub2->orWhere('end_date', '>=', now());
                    })
                    ->pluck('online_rating_level_id')
                    ->toArray();
            })
            ->whereExists(function ($sub2) use ($id, $user_id){
                $sub2->select(['id'])
                    ->from('el_online_register')
                    ->where('user_id', '=', $user_id)
                    ->where('course_id', '=', $id)
                    ->where('status', '=', 1);
            })
            ->where('course_id', '=', $id)
            ->whereNotExists(function ($sub) use ($user_id, $id){
                $sub->select(['id'])
                    ->from('el_rating_level_course as rlc')
                    ->whereColumn('rlc.course_rating_level_id', '=', 'el_online_rating_level.id')
                    ->where('rlc.user_id', '=', $user_id)
                    ->where('rlc.user_type', '=', 1)
                    ->where('rlc.course_id', '=', $id)
                    ->where('rlc.course_type', '=', 1);
            })
            ->count();

        $condition_activity = OnlineCourseCondition::where('course_id',$id)->first();
        if(!empty($condition_activity)) {
            $condition_activity = explode(',',$condition_activity->activity);
        }

        $activeLession = '';
        $id_activity_scorm_xapi = '';
        $type_activity = 0;
        $link = '';
        $required_video_timeout = '';
        $check_activity_active = OnlineViewActivity::where('course_id',$id)->where('user_id',$user_id)->first();
        $get_first_activity= '';
        $get_activity_courses = OnlineCourseActivity::where('course_id',$id)->get();

        $get_activity_courses_by_condition = OnlineCourseActivity::where('course_id', $id)->whereIn('id', $condition_activity??[])->get();

        if(!empty($check_register) && $check_register->status == 1) {
            if(!empty($check_activity_active)) {
                $get_first_activity = OnlineCourseActivity::where('id',$check_activity_active->activity_id)->first();
            } else {
                $get_first_activity = OnlineCourseActivity::where('course_id',$id)->first();
                $get_first_activity && event(new GoActivity($id, @$get_first_activity->id));
            }
            $check_type_activity = $get_first_activity ? $get_first_activity->activity_id : '';
            if($check_type_activity == 3) {
                $file = OnlineCourseActivityFile::where('id', '=', $get_first_activity->subject_id)->first();
                $file_path = upload_file(explode('|', $file->path)[0]);
                if($file->extension == 'docx' || $file->extension == 'doc' || $file->extension == 'pptx' || $file->extension == 'ppt' || $file->extension == 'xls' || $file->extension == 'xlsx') {
                    $link = 'https://view.officeapps.live.com/op/embed.aspx?src='. upload_file($file->path);
                } else if ($file->extension == 'PDF' || $file->extension == 'pdf') {
                    $link = route('module.online.view_pdf', [$id]).'?path='. $file_path;
                } else {
                    $link = upload_file($file->path);
                }
            } else if ($check_type_activity == 4) {
                $file = OnlineCourseActivityUrl::find($get_first_activity->subject_id);
                $link = $file->url;
                if (is_youtube_url($link)) {
                    $link = 'https://www.youtube.com/embed/' . get_youtube_id($link);
                }
            } else if ($check_type_activity == 5) {
                $file = OnlineCourseActivityVideo::find($get_first_activity->subject_id);
                $required_video_timeout = $file->required_video_timeout;
                $link = upload_file($file->path);
                $type_activity = 5;
            } else if ($check_type_activity == 1) {
                $link = OnlineCourseActivityScorm::find($get_first_activity->subject_id);
                $id_activity_scorm_xapi = @$link->id;
                $type_activity = 1;
            } else if ($check_type_activity == 2) {
                $link = $get_first_activity->getLinkQuizCourse($get_first_activity->lesson_id);
                $type_activity = 2;
            } elseif($check_type_activity == 7){
                $link = OnlineCourseActivityXapi::find($get_first_activity->subject_id);
                $id_activity_scorm_xapi = @$link->id;
                $type_activity = 7;
                $activeLession = OnlineCourseActivity::where(['course_id'=>$id,'subject_id'=>$id_activity_scorm_xapi])->value('lesson_id');
            }
        }
        $zoomLink= function ($id){
            $zoomActivity = OnlineCourseActivityZoom::findOrFail($id);
            return $zoomActivity->join_url;
        };

        $this->updateLogViewCourse($course);
        $agent = new Agent();

        /*Lưu lịch sử tương tác của HV*/
        if($course->training_form_id){
            $training_form = TrainingForm::find($course->training_form_id);
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

        $course_note = OnlineCourseNote::where(['course_id' => $id, 'user_id' => profile()->user_id])->first();

        return view('online::frontend.detail2_new', [
            'item' => $course,
            'profile' => $profile,
            'lessons_course' => $lessons_course,
            'link' => $link,
            'zoomLink' => $zoomLink,
            'type_activity' => $type_activity,
            'get_first_activity' => $get_first_activity,
            'id_activity_scorm_xapi' => $id_activity_scorm_xapi,
            'activeLession' => $activeLession,
            'get_result' => $get_result,
            'time_user_view_course' => $time_user_view_course,
            'date_join' => $date_join,
            'get_activity_courses' => $get_activity_courses,
            'condition_activity' => $condition_activity,
            'check_register' => $check_register,
            'check_activity_active' => !empty($check_activity_active) ? $check_activity_active->id : 0,
            'count_rating_level' => $count_rating_level,
            'agent' => $agent,
            'comments' => $comments,
            'required_video_timeout' => $required_video_timeout,
            'totalTimeLearn' => $totalTimeLearn,
            'get_activity_courses_by_condition' => $get_activity_courses_by_condition,
            'course_note' => $course_note,
        ]);
    }

    public function updateLogViewCourse(OnlineCourse $course)
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
                'course_type'=>1
            ],
            [
                'user_id'=>$user_id,
                'session_id'=>$session_id,
                'course_id'=>$course->id,
                'course_type'=>1,
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
    public function registerCourse($id, Request $request){
        $trainingProgramId = $request->trainingProgramId;
        $course = OnlineCourse::findOrFail($id);
        $referer = $request->post('referer');
        $user_id = profile()->user_id;
        $profile_user = profile();

        //kiểm tra hoàn thành khóa học trước theo chức danh trong khoảng thời gian
        $check_setting_join_course = OnlineCourse::checkSettingJoinCourse($id, $user_id);
        if(!$check_setting_join_course){
            json_result([
                'status' => 'warning',
                'message' => 'Anh/Chị không thuộc Thiết lập tham gia khóa học. Vui lòng liên hệ Trung tâm đào tạo',
            ]);
        }

        $model = OnlineRegister::firstOrCreate(['user_id'=>profile()->user_id,'course_id'=>$id]);
        $model->user_id = profile()->user_id;
        $model->course_id = $id;
        $model->unit_by = $profile_user->unit_id;
        $model->register_form = 1;

        // xét điều kiện đăng ký bắt buộc
        $online_objects = OnlineObject::where('course_id',$id)->where('type',1)->get();
        if (!empty($online_objects)) {
            foreach ($online_objects as $key => $online_object) {
                if ($online_object->title_id !== null && $online_object->title_id == $profile_user->title_id && $online_object->unit_id == $profile_user->unit_id) {
                    $model->status = 1;
                } else if ($profile_user->unit_id !== null && $online_object->unit_id == $profile_user->unit_id) {
                    $model->status = 1;
                }
            }
        }

        // XÉT ĐIỀU KIỆN TIÊN QUYẾT
        $prerequisite = SubjectPrerequisiteCourse::where(['course_id' => $id, 'course_type' => 1])->first();
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

        /* insert hist point  register referer*/
        if ($referer) {
            if (Profile::validRefer($referer)) {
                RefererRegisterCourse::saveReferRegisterOnlineCourse($id,$referer);
                PromotionUserPoint::updatePointRegisterCourse($referer);
                PointHist::savePointHist($referer);
            }else{
                json_message('Mã người giới thiệu không hợp lệ','error');
            }
        }
        if ($course->auto == 2) {
            $model->status = 1;
            $model->approved_step = '1/1';
        } else {
            $model->status = 2;
        }

        $quizs = Quiz::where('course_id', '=', $id)
            ->where('status', '=', 1)
            ->get();

        foreach ($quizs as $quiz){
            $quiz_part = QuizPart::where('quiz_id', '=', $quiz->id)->first();
            if ($quiz_part) {
                $query = QuizRegister::where('quiz_id', '=', $quiz->id)
                    ->where('user_id', '=', profile()->user_id)
                    ->where('type', '=', 1);

                if ($query->exists()) {
                    $query->update([
                        'part_id' => $quiz_part->id
                    ]);
                }else {
                    $query->insert([
                        'quiz_id' => $quiz->id,
                        'user_id' => profile()->user_id,
                        'part_id' => $quiz_part->id,
                        'type' => 1,
                    ]);
                }
            }else{
                continue;
            }
        }
        $save = $model->save();
        if ($save) {
            $get_las_register = OnlineRegister::select('status')->where('id',$model->id)->first();
            //update training process
            $subject = Subject::findOrFail($course->subject_id);
            $profile = ProfileView::where('user_id','=',$user_id)->first();
            $title = Titles::where('id',$profile->title_id)->first();
            TrainingProcess::updateOrCreate(
                [
                    'user_id'=>$user_id,
                    'course_id'=>$id,
                    'course_type'=>1
                ],
                [
                    'user_id'=>$user_id,
                    'course_id'=>$id,
                    'course_type'=>1,
                    'course_code'=>$course->code,
                    'course_name'=>$course->name,
                    'subject_id'=>$subject->id,
                    'subject_code'=>$subject->code,
                    'subject_name'=>$subject->name,
                    'titles_code'=>$title ? $title->code : null,
                    'titles_name'=>$profile->title_name,
                    'unit_code'=>$profile->unit_code,
                    'unit_name'=>$profile->unit_name,
                    'start_date'=>$course->start_date,
                    'end_date'=>$course->end_date,
                    'process_type'=>1,
                    'certificate'=>$course->cert_code,
                    'status'=> $get_las_register->status == 2 ? 0 : 1,
                ]
            );
            ////
            $this->sendMailManagerApprove($id,$model->id);
            if (url_mobile()){
                json_result([
                    'status' => 'success',
                    'message' => 'Đăng ký thành công',
                    'redirect' => route('themes.mobile.frontend.online.detail', [
                        'course_id' => $id
                    ])
                ]);
            }
            if ($get_las_register->status == 2) {
                $this->sendNotifyApproveRegister($model);

                return json_result([
                    'status' => 'success',
                    'message' => 'Đăng ký khóa học cần xét duyệt để tham gia',
                    'redirect' => route('frontend.all_course',['type' => 1]). '?trainingProgramId=' . $trainingProgramId,
                ]);
            } else {
                return json_result([
                    'status' => 'success',
                    'message' => 'Đăng ký thành công',
                    'redirect' => route('frontend.all_course',['type' => 1]). '?trainingProgramId=' . $trainingProgramId,
                ]);
            }
        }
    }

    public function sendNotifyApproveRegister($model){
        $course = OnlineCourse::findOrFail($model->course_id);
        $array_params = [
            'name' => $course->name,
            'url' => route('module.online.register',[$model->course_id]),
        ];

        $params = json_encode($array_params);
        /* thông báo */
        $nottify_template = NotifyTemplate::query()->where('code', '=', 'approve_register')->first();
        $subject_notify = $this->mapParams($nottify_template->title, $params);
        $content_notify = $this->mapParams($nottify_template->content, $params);
        $url = $this->getParams($params, 'url');

        $permissionApprovedUser = PermissionApprovedUser::where('model_approved', 'el_online_register')->pluck('user_id')->toArray();
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
        $course = OnlineCourse::find($course_id);
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
                'signature' => $signature,
                'code' => $course->code,
                'course_name' => $course->name,
                'student' => $full_name,
                'url' => route('module.training_unit.approve_course.course', ['id' => $course_id, 'type' => 1])
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
                    ->where('per.name', '=', 'online-course-register-approve');
            })
            ->where(['e.name'=>'online-course-register'])
            ->get();

        foreach ($users as $user){
            $signature = getMailSignature($user->user_id);
            $params = [
                'signature' => $signature,
                'code' => $course->code,
                'course_name' => $course->name,
                'student' => $full_name,
                'url' => route('module.training_unit.approve_course.course', ['id' => $course_id, 'type' => 1])
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
        $automail->object_id = '1'.$register_id;
        $automail->object_type = 'approve_online_register';
        $automail->addToAutomail();
    }

    public function rating($id, Request $request) {
        $this->validateRequest([
            'star' => 'required|min:1',
        ], $request, ['star' => 'Sao']);

        $user_id = getUserId();
        if (OnlineRating::getRating($id, $user_id)) {
            json_message('Bạn đã đánh giá khóa học này', 'warning');
        }

        $model = new OnlineRating();
        $model->course_id = $id;
        $model->user_id = $user_id;
        $model->user_type = getUserType();
        $model->num_star = $request->star;

        if ($model->save()) {
            $course = OnlineCourse::find($id);
            $setting = UserPointSettings::where("pkey","=","online_rating_star")
                ->where("item_id","=",$id)
                ->where("item_type","=","2")
                ->first();
            if ($setting && $setting->pvalue > 0) {
                $note = 'Đánh giá sao khóa học online <b>'. $course->name .' ('. $course->code .')</b>';

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

            return response()->json(['message' => "Cảm ơn bạn đã đánh giá"]);
        }

        return response()->json(['message'=>'Đã có lỗi sảy ra vui lòng thử lại', 'status'=>'error']);
    }

    public function search(Request $request) {
        $items = $this->getItems($request);
        $training_program = TrainingProgram::find($request->get('training_program_id'));
        $level_subject = LevelSubject::find($request->get('level_subject_id'));
        $subject = Subject::find($request->get('subject_id'));
        $status = $request->status;

        return view('online::frontend.index', [
            'items' => $items,
            'training_program' => $training_program,
            'subject' => $subject,
            'level_subject' => $level_subject,
            'status' => $request->status,
        ]);
    }

    public function goActivity($course_id, $course_activity_id, $lesson) {
        $course_activity = OnlineCourseActivity::findOrFail($course_activity_id);
        $link = $course_activity->getLink($lesson);
        if (empty($link)) {
            return abort(404);
        }

        event(new GoActivity($course_id, $course_activity_id));

        return redirect()->to($link);
    }
    public function goZoom($course_id, $activity_id,$type){
        if ($type==1){
            $zoomActivity =OnlineCourseActivityZoom::findOrFail($activity_id);
        }
    }
    public function viewPDF($id, Request $request){
        if (url_mobile()){
            $url = route('themes.mobile.frontend.online.detail', ['course_id' => $id]);
        }else{
            $url = route('module.online.detail', ['id' => $id]);
        }
        $course = OnlineCourse::find($id);
        if ($request->get('path')){
            $path = $request->get('path');
        }else{
            $path = upload_file($course->document);
        }

        return view('online::frontend.view_pdf', [
            'path' => $path,
            'url' => $url,
        ]);
    }

    public function viewVideo($file) {
        $file = decrypt_array($file);
        if (!isset($file['path'])) {
            return abort(404);
        }

        if (!file_exists($file['path'])) {
            return abort(404);
        }

        $stream = new VideoStream($file['path']);
        $stream->start();
    }

    // XEM PDF HƯỚNG DẪN HỌC
    public function tutorialViewPDF($id,$key, Request $request){
        if (url_mobile()){
            $url = route('themes.mobile.frontend.online.detail', ['course_id' => $id]);
        }else{
            $url = route('module.online.detail', ['id' => $id]);
        }
        $course = OnlineCourse::find($id);
        $get_tutorials = json_decode($course->tutorial);

        foreach ($get_tutorials as $key_tutorial => $value) {
            if ($key_tutorial == $key) {
                $path = upload_file($value);
            }
        }
        return view('online::frontend.view_pdf', [
            'path' => $path,
            'url' => $url,
        ]);
    }
    // END XEM PDF HƯỚNG DẪN HỌC

    public function showModalQrcodeReferer(Request $request) {
        $course_id = $request->input('course_id');
        return view('online::modal.qrcode_referer' );
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
        $history->user_type = getUserType();
        $history->point = $point;
        $history->type = 1;
        $history->course_id = $course_id;
        $history->promotion_course_setting_id = $promotion_course_setting_id;
        $history->save();

        $course_name = OnlineCourse::query()->find($course_id)->name;

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
        $promotion_share = PromotionShare::firstOrNew([
            'course_id' => $course_id,
            'type' => $type,
            'user_id' => getUserId(),
            'user_type' => getUserType(),
        ]);
        $promotion_share->share_key = $request->share_key;
        $promotion_share->save();
        json_result([
            'key' => $request->share_key,
        ]);
    }

    public function autoRegisterCourse($user_id, $course_id) {
        $course = OnlineCourse::findOrFail($course_id);
        $subject = Subject::findOrFail($course->subject_id);
        event(new SaveTrainingProcessRegister($course, $subject, $user_id, null, 1));

        $model = OnlineRegister::firstOrNew(['user_id' => $user_id, 'course_id' => $course_id]);
        $model->user_id = $user_id;
        $model->course_id = $course_id;
        $model->register_form = 1;
        $model->status = 1;
        $quizs = Quiz::where('course_id', '=', $course_id)->where('status', '=', 1)->get();
        if ($quizs){
            foreach ($quizs as $quiz){
                $quiz_part = QuizPart::where('quiz_id', '=', $quiz->id)->first();
                if ($quiz_part){
                    $query = QuizRegister::where('quiz_id', '=', $quiz->id)
                        ->where('user_id', '=', $user_id)
                        ->where('type', '=', 1);
                    if ($query->exists()) {
                        $query->update([
                            'part_id' => $quiz_part->id
                        ]);
                    }else {
                        $query->insert([
                            'quiz_id' => $quiz->id,
                            'user_id' => $user_id,
                            'part_id' => $quiz_part->id,
                            'type' => 1,
                        ]);
                    }
                }else{
                    continue;
                }
            }
        }
        $model->save();
        return;
    }

    public function detail2($id, Request $request){

        return redirect()->route('module.online.detail_first', [$id]);
        $user_id = profile()->user_id;
        $course = OnlineCourse::where('id', '=', $id)
        ->where('isopen', '=', 1)
        ->where('status', '=', 1)
        ->firstOrFail();
        OnlineCourse::updateItemViews($id, $course->views);
        if($course->auto == 2) {
            $this->autoRegisterCourse($user_id, $id);
        }

        $check_register = OnlineRegister::select('status')->where('course_id',$id)->where('user_id',$user_id)->first();

        $time_user_view_course = UserViewCourse::firstOrNew(['course_id' => $id, 'course_type' => 1, 'user_id' => $user_id]);
        $time_user_view_course->course_id = $id;
        $time_user_view_course->course_type = 1;
        $time_user_view_course->user_id = $user_id;
        $time_user_view_course->time_view = date('Y-m-d H:i');
        if(!empty($check_register) && $check_register->status == 1) {
            $time_user_view_course->count_user_view = $time_user_view_course->count_user_view + 1;
        }
        $time_user_view_course->save();

        $date_join = OnlineCourseActivityHistory::where('course_id', $id)->where('user_id', $user_id)->first(['created_at']);

        $timeUserLearn = OnlineCourseTimeUserLearn::where('user_id', $user_id)->where('course_id', $id)->first();
        $totalTimeLearn = isset($timeUserLearn) && $timeUserLearn->time > 0 ? gmdate("H:i:s", $timeUserLearn->time) : 0;

        $get_result = OnlineResult::where('course_id',$id)->where('user_id',$user_id)->first(['score','result']);

        $profile = ProfileView::select(['full_name','user_id'])->where('user_id', '=', $user_id)->first(['user_id', 'full_name']);

        $lessons_course = OnlineCourseLesson::where('course_id',$id)->get();

        $comments = OnlineComment::select([
            'a.*',
            \DB::raw("CONCAT_WS(' ',lastname, firstname) AS fullname"),
            'b.avatar',
            'c.name',
        ])
            ->from('el_online_comment AS a')
            ->leftJoin('el_profile AS b', function ($sub){
                $sub->on('b.user_id', '=', 'a.user_id')
                    ->where('a.user_type', '=', 1);
            })
            ->leftJoin('el_quiz_user_secondary AS c', function ($sub){
                $sub->on('c.id', '=', 'a.user_id')
                    ->where('a.user_type', '=', 2);
            })
            ->where('a.course_id', '=', $id)
            ->orderBy('a.id', 'desc')
            ->get();

        $count_rating_level = OnlineRatingLevel::query()
            ->whereIn('id', function ($sub) use ($id){
                $sub->select(['online_rating_level_id'])
                    ->from('el_online_rating_level_object')
                    ->where('course_id', '=', $id)
                    ->where('object_type', '=', 1)
                    ->where(function ($sub2){
                        $sub2->orWhereNull('end_date');
                        $sub2->orWhere('end_date', '>=', now());
                    })
                    ->pluck('online_rating_level_id')
                    ->toArray();
            })
            ->whereExists(function ($sub2) use ($id, $user_id){
                $sub2->select(['id'])
                    ->from('el_online_register')
                    ->where('user_id', '=', $user_id)
                    ->where('course_id', '=', $id)
                    ->where('status', '=', 1);
            })
            ->where('course_id', '=', $id)
            ->whereNotExists(function ($sub) use ($user_id, $id){
                $sub->select(['id'])
                    ->from('el_rating_level_course as rlc')
                    ->whereColumn('rlc.course_rating_level_id', '=', 'el_online_rating_level.id')
                    ->where('rlc.user_id', '=', $user_id)
                    ->where('rlc.user_type', '=', 1)
                    ->where('rlc.course_id', '=', $id)
                    ->where('rlc.course_type', '=', 1);
            })
            ->count();

        $condition_activity = OnlineCourseCondition::where('course_id',$id)->first();
        if(!empty($condition_activity)) {
            $condition_activity = explode(',',$condition_activity->activity);
        }

        $activeLession = '';
        $id_activity_scorm_xapi = '';
        $type_activity = 0;
        $link = '';
        $required_video_timeout = '';
        $check_activity_active = OnlineViewActivity::where('course_id',$id)->where('user_id',$user_id)->first();
        $get_first_activity= '';
        $get_activity_courses = OnlineCourseActivity::where('course_id',$id)->get();

        if(!empty($check_register) && $check_register->status == 1) {
            if(!empty($check_activity_active)) {
                $get_first_activity = OnlineCourseActivity::where('id',$check_activity_active->activity_id)->first();
            } else {
                $get_first_activity = OnlineCourseActivity::where('course_id',$id)->first();
                $get_first_activity && event(new GoActivity($id, @$get_first_activity->id));
            }
            $check_type_activity = $get_first_activity ? $get_first_activity->activity_id : '';
            if($check_type_activity == 3) {
                $file = OnlineCourseActivityFile::where('id', '=', $get_first_activity->subject_id)->first();
                $file_path = upload_file(explode('|', $file->path)[0]);
                if($file->extension == 'docx' || $file->extension == 'doc' || $file->extension == 'pptx' || $file->extension == 'ppt' || $file->extension == 'xls' || $file->extension == 'xlsx') {
                    $link = 'https://view.officeapps.live.com/op/embed.aspx?src='. upload_file($file->path);
                } else if ($file->extension == 'PDF' || $file->extension == 'pdf') {
                    $link = route('module.online.view_pdf', [$id]).'?path='. $file_path;
                } else {
                    $link = upload_file($file->path);
                }
            } else if ($check_type_activity == 4) {
                $file = OnlineCourseActivityUrl::find($get_first_activity->subject_id);
                $link = $file->url;
                if (is_youtube_url($link)) {
                    $link = 'https://www.youtube.com/embed/' . get_youtube_id($link);
                }
            } else if ($check_type_activity == 5) {
                $file = OnlineCourseActivityVideo::find($get_first_activity->subject_id);
                $required_video_timeout = $file->required_video_timeout;
                $link = upload_file($file->path);
                $type_activity = 5;
            } else if ($check_type_activity == 1) {
                $link = OnlineCourseActivityScorm::find($get_first_activity->subject_id);
                $id_activity_scorm_xapi = @$link->id;
                $type_activity = 1;
            } else if ($check_type_activity == 2) {
                $link = $get_first_activity->getLinkQuizCourse($get_first_activity->lesson_id);
                $type_activity = 2;
            } elseif($check_type_activity == 7){
                $link = OnlineCourseActivityXapi::find($get_first_activity->subject_id);
                $id_activity_scorm_xapi = @$link->id;
                $type_activity = 7;
                $activeLession = OnlineCourseActivity::where(['course_id'=>$id,'subject_id'=>$id_activity_scorm_xapi])->value('lesson_id');
            }
        }
        $zoomLink= function ($id){
            $zoomActivity = OnlineCourseActivityZoom::findOrFail($id);
            return $zoomActivity->join_url;
        };

        $this->updateLogViewCourse($course);
        $agent = new Agent();

        /*Lưu lịch sử tương tác của HV*/
        if($course->training_form_id){
            $training_form = TrainingForm::find($course->training_form_id);
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

        return view('online::frontend.detail2', [
            'item' => $course,
            'profile' => $profile,
            'lessons_course' => $lessons_course,
            'link' => $link,
            'zoomLink' => $zoomLink,
            'type_activity' => $type_activity,
            'get_first_activity' => $get_first_activity,
            'id_activity_scorm_xapi' => $id_activity_scorm_xapi,
            'activeLession' => $activeLession,
            'get_result' => $get_result,
            'time_user_view_course' => $time_user_view_course,
            'date_join' => $date_join,
            'get_activity_courses' => $get_activity_courses,
            'condition_activity' => $condition_activity,
            'check_register' => $check_register,
            'check_activity_active' => !empty($check_activity_active) ? $check_activity_active->id : 0,
            'count_rating_level' => $count_rating_level,
            'agent' => $agent,
            'comments' => $comments,
            'required_video_timeout' => $required_video_timeout,
            'totalTimeLearn' => $totalTimeLearn
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
        //kiểm tra hoàn thành khóa học trước theo chức danh trong khoảng thời gian
        $check_setting_join_course = OnlineCourse::checkSettingJoinCourse($course_id, $user_id);
        if(!$check_setting_join_course[0]){
            json_result([
                'status' => 'error',
                'message' => $check_setting_join_course[1],
            ]);
        }

        $prev = '';
        $next = '';
        $list_activity = OnlineCourseActivity::where('course_id', $course_id)->orderBy('lesson_id','asc')->orderBy('id','asc')->pluck('id')->toArray();
        if(!empty($list_activity)) {
            $index = array_search($activity_id ,$list_activity);
            $prev = $index > 0 ? $list_activity[$index - 1] : $list_activity[0];
            $next = $list_activity[$index + 1];
        }
        if($prev) {
            $activity_prev = OnlineCourseActivity::find($prev,['id','activity_id','lesson_id']);
        } else {
            $activity_prev = '';
        }
        if ($next) {
            $activity_next = OnlineCourseActivity::find($next,['id','activity_id','lesson_id']);
        } else {
            $activity_next = '';
        }

        OnlineViewActivity::updateOrCreate([
            'course_id' => $course_id,
            'user_id' => $user_id,
        ], [
            'course_id' => $course_id,
            'activity_id' => $activity_id,
            'user_id' => $user_id,
        ]);

        $course_activity = OnlineCourseActivity::findOrFail($activity_id);

        $check_complete_course_activity = $course_activity->setting_complete_course_activity_id;
        if($check_complete_course_activity) {
            $complete_course_activitys = explode(',', $check_complete_course_activity);
            foreach ($complete_course_activitys as $complete_course_activity) {
                $get_activity_finish_first = OnlineCourseActivity::find($complete_course_activity);
                if($get_activity_finish_first){
                    $check_complete = $get_activity_finish_first->isComplete(profile()->user_id);
                    if (!$check_complete) {
                        json_result([
                            'status' => 'warning',
                            'message' => 'Hoạt động chỉ học được khi hoàn thành hoạt động: '. $get_activity_finish_first->name,
                            'title' => 'Bạn chưa được phép xem nội dung',
                        ]);
                    }
                }
            }
        }

        $time_start_course_activity = $course_activity->setting_start_date;
        $time_end_course_activity = $course_activity->setting_end_date ? $course_activity->setting_end_date : '';
        if(!$time_end_course_activity && $time_start_course_activity > $date) {
            json_message('Hoạt động chỉ được học trong khung thời gian: '. get_date($time_start_course_activity, 'd/m/Y H:i'), 'error');
        } elseif ($time_end_course_activity && ($time_start_course_activity > $date || $time_end_course_activity < $date)) {
            json_message('Hoạt động chỉ được học trong khung thời gian: '. get_date($time_start_course_activity, 'd/m/Y H:i') . ' => ' . get_date($time_end_course_activity, 'd/m/Y H:i'), 'error');
        }

        $check_score_course_activity = $course_activity->setting_score_course_activity_id;
        if($check_score_course_activity) {
            $get_activity_score = OnlineCourseActivity::find($check_score_course_activity, ['name', 'activity_id','subject_id']);
            if ($get_activity_score->activity_id == 1) {
                $score = ActivityScormScore::where('activity_id', $get_activity_score->subject_id)->where('user_id', profile()->user_id)->first(['score']);
                if($score && ($score->score <  $course_activity->setting_min_score || $score->score <  $course_activity->setting_max_score)) {
                    json_message('Điểm bạn đạt được ở hoạt động: '.$get_activity_score->name.' không hợp lệ để học hoạt động này', 'error');
                }
            } else if ($get_activity_score->activity_id == 2) {
                $quiz_result = QuizResult::where('quiz_id', '=', $get_activity_score->subject_id)->whereNull('text_quiz')->where('user_id', '=', profile()->user_id)->first();
                $score = isset($quiz_result->reexamine) ? $quiz_result->reexamine : (isset($quiz_result->grade) ? $quiz_result->grade : null);
                if($score < $course_activity->setting_min_score || $score > $course_activity->setting_max_score) {
                    json_message('Điểm bạn đạt được ở hoạt động: '.$get_activity_score->name.' không hợp lệ để học hoạt động này', 'error');
                }
            }
        }

        event(new GoActivity($course_id, $activity_id));
        $this->checkCompleteActivityUser($course_id,$course_activity);
        $required_video_timeout = '';
        $msg_error = '';
        $checkPage = 0;
        if ($type == 1) {
            $link = OnlineCourseActivityScorm::findOrFail($course_activity->subject_id);
        } else if($type == 2) {
            $link = $course_activity->getLinkQuizCourse($lesson_id);
            if(empty($link)) {
                $msg_error = 'Kỳ thi chưa có ca thi';
            }
        } else if($type == 3) {
            $file = OnlineCourseActivityFile::where('id', '=', $course_activity->subject_id)->first();
            $file_path = upload_file(explode('|', $file->path)[0]);
            if($file->extension == 'docx' || $file->extension == 'doc' || $file->extension == 'pptx' || $file->extension == 'ppt' || $file->extension == 'xls' || $file->extension == 'xlsx') {
                $link = 'https://view.officeapps.live.com/op/embed.aspx?src='. upload_file($file->path);
            } else if ($file->extension == 'PDF' || $file->extension == 'pdf') {
                $link = route('module.online.view_pdf', [$course_id]).'?path='. $file_path;
            } else {
                $link = upload_file($file->path);
            }
        } else if ($type == 4) {
            $file = OnlineCourseActivityUrl::find($course_activity->subject_id);
            $link = $file->url;
            if (is_youtube_url($link) && $file->page == 0) {
                $link = 'https://www.youtube.com/embed/' . get_youtube_id($link);
            }
            $checkPage = $file->page;
        } else if ($type == 5) {
            $file = OnlineCourseActivityVideo::find($course_activity->subject_id,['path', 'required_video_timeout']);
            $required_video_timeout = $file->required_video_timeout;
            $link = upload_file($file->path);
            $link = convert_url_web_to_app($link);
        } else if ($type == 7) {
            $link = OnlineCourseActivityXapi::findOrFail($course_activity->subject_id);
        } else if($type == 8){
            $online_survey_user = OnlineSurveyUser::where(['course_id' => $course_id, 'course_activity_id' => $activity_id, 'user_id' => $user_id])->first();
            if($online_survey_user){
                $link = route('module.online.survey.user.edit', [$course_id, $activity_id, $user_id]);
            }else{
                $link = route('module.online.survey.user', [$course_id, $activity_id]);
            }
        }

        $get_activity_completes = OnlineCourseActivityCompletion::select('activity_id')->where('course_id',$course_id)->where('user_id',$user_id)->where('status',1)->pluck('activity_id')->toArray();

        $list_clocked = [];
        $check_clocked = OnlineCourseActivity::where('course_id',$course_id)->whereNotIn('id', $get_activity_completes)->where('status',1)->get();
        foreach ($check_clocked as $key => $item) {
            if($item->checkSettingActivity()) {
                $list_clocked[] = $item->id;
            }
        }

        // if (empty($link)) {
        //     return abort(404);
        // }

        return json_result([
            'status' => 'success',
            'link' => $link,
            'course_activity' => $course_activity,
            'get_activity_completes' => $get_activity_completes,
            'required_video_timeout' => $required_video_timeout,
            'activity_prev' => $activity_prev,
            'activity_next' => $activity_next,
            'list_clocked' => $list_clocked,
            'msg_error' => $msg_error,
            'checkPage' => $checkPage
        ]);
    }

    public function getDataRatingLevel($course_id, Request $request){
        $sort = $request->input('sort', 'id');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $course = OnlineCourse::find($course_id);
        $user_id = getUserId();

        $query = OnlineRatingLevel::query()
            ->whereIn('id', function ($sub) use ($course_id){
                $sub->select(['online_rating_level_id'])
                    ->from('el_online_rating_level_object')
                    ->where('course_id', '=', $course_id)
                    ->where('object_type', '=', 1)
                    ->pluck('online_rating_level_id')
                    ->toArray();
            })
            ->whereExists(function ($sub2) use ($course_id, $user_id){
                $sub2->select(['id'])
                    ->from('el_online_register')
                    ->where('user_id', '=', $user_id)
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

            $rating_level_object = OnlineRatingLevelObject::query()
                ->where('course_id', '=', $course_id)
                ->where('online_rating_level_id', '=', $row->id)
                ->where('object_type', '=', 1)
                ->first();
            if ($rating_level_object){
                $result = OnlineResult::query()
                    ->where('course_id', '=', $course_id)
                    ->where('user_id', '=', profile()->user_id)
                    ->where('result', '=', 1)
                    ->first();
                if ($result){
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
                $rating_level_url = route('module.rating_level.course', [$course_id, 1, $row->id, $user_id]).'?rating_level_object_id='.@$rating_level_object->id.'&view_type=course';
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
                    $rating_level_url = route('module.rating_level.course', [$course_id, 1, $row->id, $user_id]).'?rating_level_object_id='.@$rating_level_object->id.'&view_type=course';
                }
            }

            $user_rating_level_course = RatingLevelCourse::query()
                ->where('course_rating_level_id', '=', $row->id)
                ->where('user_id', '=', profile()->user_id)
                ->where('user_type', '=', 1)
                ->where('course_id', '=', $course_id)
                ->where('course_type', '=', 1)
                ->first();
            if ($user_rating_level_course){
                $rating_status = $user_rating_level_course->send == 1 ? 1 : 2;
                $rating_level_url = route('module.rating_level.edit_course', [$course_id, 1, $row->id, $user_rating_level_course->rating_user]).'?rating_level_object_id='.@$rating_level_object->id.'&view_type=course';
            }

            $row->course_name = $course->name;
            $row->course_time = get_date($course->start_date) . ($course->end_date ? ' đến '. get_date($course->end_date) : '');
            $row->rating_time = get_date($start_date_rating) . ($end_date_rating ? ' đến ' .get_date($end_date_rating) : '');
            $row->rating_status = $rating_status == 1 ? 'Đã đánh giá' : ($rating_status == 2 ? 'Chưa gửi đánh giá' : 'Chưa đánh giá');
            $row->rating_level_url = $rating_level_url;
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function getObject($course_id, Request $request)
    {
        $sort = $request->get('sort', 'id');
        $order = $request->get('order', 'desc');
        $offset = $request->get('offset', 0);
        $limit = $request->get('limit', 20);

        $query = OnlineCourse::query();
        $query->select([
            'a.title_join_id',
            'a.title_recommend_id',
        ]);

        $query->from('el_online_course AS a');
        $query->where('a.id', '=', $course_id);

        $count = $query->count();
        $query->orderBy('a.' . $sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row) {
            //dd($row->title_join_id);
        }

        json_result([
            'total' => $count,
            'rows' => $rows
        ]);
    }

    // GỌI AJAX CÁC HOẠT ĐỘNG ĐỒNG THỜI CHECK LUÔN HOÀN THÀNH HOẠT ĐỘNG TRƯỚC
    public function checkCompleteActivityUser($course_id,OnlineCourseActivity $course_activity) {
        if (!in_array($course_activity->activity_id,[3,4,5]))
            return;
        $user_id = profile()->user_id;
        $user_type = Profile::getUserType();
        $status = $course_activity->checkComplete($user_id, $user_type);
        $completion = OnlineCourseActivityCompletion::firstOrNew([
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

        if($completion->status == 1){
            \Artisan::call('online:complete '.$user_id .' '.$course_id);
        }
    }

    // BÌNH LUẬN KHÓA HỌC
    public function commentCourseOnline ($id, Request $request) {
        $this->validateRequest([
            'content' => 'required|string|max:1000',
        ], $request, [
            'content' => 'Nội dung',
        ]);
        $content = $request->content;
        if (strpos($content, 'sex') !== false || strpos($content, 'xxx') !== false || strpos($content, 'địt') !== false){
            json_result([
                'status' => 'warning',
                'message' => 'Bình luận có từ nhạy cảm'
            ]);
        } else {
            if (empty($request->comment_id)) {
                $model = new OnlineComment();
                $model->course_id = $id;
                $model->user_id = getUserId();
                $model->user_type = getUserType();
                $model->content = $content;
                $save = $model->save();
            }else{
                $model = OnlineComment::findOrFail($request->comment_id);
                $model->content = $content;
                $save = $model->save();
            }
        }
        if ($save) {
            $model->created_at2 = \Carbon\Carbon::parse($model->created_at)->diffForHumans();
            $model->content = ucfirst($model->content);
            if (getUserType() == 1) {
                $profile_user = ProfileView::where('user_id', getUserId())->first(['full_name', 'avatar', ]);
                $profile_user->avatar = image_user($profile_user->avatar);
            } else {
                $profile_user = QuizUserSecondary::where('id', getUserId())->first(['name']);
                $profile_user->avatar = asset('images/image_default.jpg');
            }

            $course = OnlineCourse::find($id);
            $setting = UserPointSettings::where("pkey","=","online_comment")
            ->where("item_id","=",$id)
            ->where("item_type","=","2")
            ->first();
            if ($setting && $setting->pvalue > 0) {
                $note = 'Bình luận khóa học online <b>'. $course->name .' ('. $course->code .')</b>';

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

        $total_comment = OnlineComment::where('course_id', $id)->count();

        json_result([
            'status' => 'success',
            'comment' => $model,
            'profile_user' => $profile_user,
            'total_comment' => $total_comment,
        ]);
    }

    public function deleteCommentCourseOnline($id) {
        $comment = OnlineComment::findOrFail($id)->delete();
    }

    // LẤY MÔ TẢ HOẠT ĐỘNG
    public function ajaxDescriptionActivity(Request $request)
    {
        $course_id = $request->course_id;
        $activity_id = $request->aid;
        $type = $request->type;
        $course_activity = OnlineCourseActivity::findOrFail($activity_id);
        switch ($type) {
            case 1:
                $description = OnlineCourseActivityScorm::find($course_activity->subject_id, ['description']);
                break;
            case 2:
                $description = OnlineCourseActivityQuiz::where('quiz_id', '=', $course_activity->subject_id)->first(['description']);
                break;
            case 3:
                $description = OnlineCourseActivityFile::where('id', '=', $course_activity->subject_id)->first(['description']);
                break;
            case 4:
                $description = OnlineCourseActivityUrl::find($course_activity->subject_id, ['description']);
                break;
            case 4:
                $description = OnlineCourseActivityVideo::find($course_activity->subject_id, ['description']);
                break;
            case 8:
                $description = OnlineCourseActivitySurvey::where(['course_id' => $course_id,'survey_template_id' => $course_activity->subject_id])->first(['description']);
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
        $model = OnlineFinishVideo::firstOrNew(['video_id' => $request->id, 'user_id' => profile()->user_id, 'course_id' => $request->course_id]);
        $model->user_id = profile()->user_id;
        $model->course_id = $request->course_id;
        $model->video_id = $request->id;
        $model->save();

        $saveComplete = OnlineCourseActivityCompletion::where(['user_id' => profile()->user_id, 'activity_id' => $request->id, 'course_id' => $request->course_id])->first();
        $saveComplete->status = 1;
        $saveComplete->save();

        $get_activity_completes = OnlineCourseActivityCompletion::where('course_id', $request->course_id)->where('user_id', profile()->user_id)->where('status', 1)->pluck('activity_id')->toArray();

        $list_clocked = [];
        $check_clocked = OnlineCourseActivity::where('course_id', $request->course_id)->whereNotIn('id', $get_activity_completes)->where('status', 1)->get();
        foreach ($check_clocked as $key => $item) {
            if($item->checkSettingActivity()) {
                $list_clocked[] = $item->id;
            }
        }

        json_result([
            'status' => 'success',
            'description' => 'Lưu thành công',
            'get_activity_completes' => $get_activity_completes,
            'list_clocked' => $list_clocked
        ]);
    }

    // HỌC VIÊN ĐÁNH DẤU HOẠT ĐỘNG
    public function userBookMarkActivity(Request $request)
    {
        $status_bookmark = $request->status == 0 ? 1 : 0;
        $model = UserBookmarkActivity::firstOrNew(['course_id' => $request->course_id, 'activity_id' => $request->activity_id, 'user_id' => profile()->user_id]);
        $model->status = $status_bookmark;
        $model->course_id = $request->course_id;
        $model->activity_id = $request->activity_id;
        $model->user_id = profile()->user_id;
        $model->save();

        json_result([
            'status_bookmark' => $status_bookmark,
        ]);
    }

    // LƯU TỔNG THỜI GIAN HỌC
    public function saveUserTimeLearn(Request $request)
    {
        $saveTimeUserLearn = OnlineCourseTimeUserLearn::firstOrNew(['course_id' => $request->courseId, 'user_id' => profile()->user_id]);
        $saveTimeUserLearn->course_id = $request->courseId;
        $saveTimeUserLearn->user_id = profile()->user_id;
        $saveTimeUserLearn->time = $saveTimeUserLearn->time + $request->time;
        $saveTimeUserLearn->save();

        json_result([
            'status' => 'success',
        ]);
    }

    //ĐÁNH DẤU KHÓA HỌC
    public function bookmarkOnline(Request $request) {
        $checkBookmark = CourseBookmark::where(['course_id' => $request->id, 'type' => 1, 'user_id' => profile()->user_id])->exists();
        if(!$checkBookmark) {
            $saveBookmark = new CourseBookmark();
            $saveBookmark->course_id = $request->id;
            $saveBookmark->type = 1;
            $saveBookmark->user_id = profile()->user_id;
            $saveBookmark->save();

            json_result(1);
        } else {
            CourseBookmark::where(['course_id' => $request->id, 'type' => 1, 'user_id' => profile()->user_id])->delete();
            json_result(0);
        }
    }

    //Lấy dữ liệu khảo sát từng hoạt động
    public function getSurveyUser($course_id, $course_activity_id){
        $template = OnlineSurveyTemplate::where(['course_id' => $course_id, 'course_activity_id' => $course_activity_id])->first();

        return view('online::survey.survey', [
            'template' => $template,
            'course_id' => $course_id,
            'course_activity_id' => $course_activity_id,
        ]);
    }

    public function editSurveyUser($course_id, $course_activity_id, $user_id){
        $survey_user = OnlineSurveyUser::where(['course_id' => $course_id, 'course_activity_id' => $course_activity_id, 'user_id' => $user_id])->first();

        $survey_user_categories = OnlineSurveyUserCategory::where(['survey_user_id' => $survey_user->id])->get();

        return view('online::survey.edit_survey', [
            'survey_user' => $survey_user,
            'survey_user_categories' => $survey_user_categories,
            'course_id' => $course_id,
            'course_activity_id' => $course_activity_id,
        ]);
    }

    public function saveOnlineSurveyUser(Request $request){
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
            $questions = OnlineSurveyQuestion::where('category_id', $category)->get(['id', 'obligatory', 'type', 'name']);
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

        $model = OnlineSurveyUser::firstOrNew(['id' => $survey_user_id]);
        $model->template_id = $template_id;
        $model->user_id = $user_id;
        $model->course_id = $course_id;
        $model->course_activity_id = $course_activity_id;
        $model->send = $send;
        $model->save();

        foreach($category_id as $cate_key => $cate_id){
            $categories = OnlineSurveyUserCategory::firstOrNew(['id' => $user_category_id[$cate_key]]);
            $categories->survey_user_id = $model->id;
            $categories->category_id = $cate_id;
            $categories->category_name = $category_name[$cate_id];
            $categories->save();

            if(isset($question_id[$cate_id])){
                foreach($question_id[$cate_id] as $ques_key => $ques_id){
                    $user_ques_id = $user_question_id[$cate_id][$ques_key];
                    $ques_code = $question_code[$cate_id][$ques_id];
                    $ques_name = $question_name[$cate_id][$ques_id];

                    $course_question = OnlineSurveyUserQuestion::firstOrNew(['id' => $user_ques_id]);
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

                            $course_answer = OnlineSurveyUserAnswer::firstOrNew(['id' => $user_ans_id]);
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
                                    OnlineSurveyUserAnswerMatrix::query()
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
            $completion = OnlineCourseActivityCompletion::firstOrNew([
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
                \Artisan::call('online:complete '.$user_id .' '.$course_id);
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

    public function ajaxRatingStar($course_id){
        $f_rating_star = function($num_star) use ($course_id){
            return OnlineRating::getRatingValue($course_id, $num_star);
        };

        return view('online::modal.modal_rating_star',[
            'f_rating_star' => $f_rating_star,
            'course_id' => $course_id,
        ]);
    }

    public function ajaxDocument($course_id, Request $request) {

        return view('online::modal.modal_document',[
            'course_id' => $course_id
        ]);
    }

    public function getDataDocument($course_id, Request $request) {
        $search = $request->input('search');

        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = OnlineCourseDocument::query();
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

    public function ajaxResultLearn($course_id, Request $request){
        $user_id = profile()->user_id;
        $get_activity_courses = OnlineCourseActivity::where('course_id',$course_id)->get();
        $result = OnlineResult::whereCourseId($course_id)->where('user_id', $user_id)->where('result', 1)->first();
        $condition_activity = OnlineCourseCondition::where('course_id',$course_id)->first();
        if(!empty($condition_activity)) {
            $condition_activity = explode(',',$condition_activity->activity);
        }

        return view('online::frontend.result_learn', [
            'user_id' => $user_id,
            'get_activity_courses' => $get_activity_courses,
            'result' => $result,
            'condition_activity' => $condition_activity,
        ]);
    }

    public function saveNoteCourse($course_id, Request $request){
        $content = $request->content;

        $course_note = OnlineCourseNote::firstOrNew(['course_id' => $course_id, 'user_id' => profile()->user_id]);
        $course_note->course_id = $course_id;
        $course_note->user_id = profile()->user_id;
        $course_note->user_type = getUserType();
        $course_note->note = $content ?? '';
        $course_note->save();

        json_message('ok');
    }

    public function ajaxHistoryActivityCourse($course_id, Request $request){
        $user_id = profile()->user_id;
        $user_type = profile()->type_user;
        $get_activity_courses = OnlineCourseActivity::where('course_id', $course_id)->whereIn('activity_id', [1,2])->get();
        $result = OnlineResult::whereCourseId($course_id)->where('user_id', $user_id)->where('result', 1)->first();
        $condition_activity = OnlineCourseCondition::where('course_id',$course_id)->first();
        if(!empty($condition_activity)) {
            $condition_activity = explode(',',$condition_activity->activity);
        }

        return view('online::frontend.history_activity_course', [
            'user_id' => $user_id,
            'user_type' => $user_type,
            'get_activity_courses' => $get_activity_courses,
            'result' => $result,
            'condition_activity' => $condition_activity,
        ]);
    }

}
