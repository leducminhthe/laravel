<?php

namespace App\Http\Controllers\Mobile;

use App\Events\Online\GoActivity;
use App\Models\Profile;
use App\Models\ProfileView;
use App\Scopes\CompanyScope;
use App\Models\UserViewCourse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Capabilities\Entities\CapabilitiesResult;
use Modules\Online\Entities\OnlineComment;
use Modules\Online\Entities\OnlineCourse;
use Modules\Online\Entities\OnlineCourseActivityHistory;
use Modules\Online\Entities\OnlineCourseActivityScorm;
use Modules\Online\Entities\OnlineCourseAskAnswer;
use Modules\Online\Entities\OnlineCourseCondition;
use Modules\Online\Entities\OnlineCourseNote;
use Modules\Online\Entities\OnlineResult;
use Modules\Rating\Entities\RatingCourse;
use Modules\Online\Entities\OnlineCourseLesson;
use App\Models\Categories\Subject;
use Modules\User\Entities\TrainingProcess;
use Modules\Online\Entities\OnlineRegister;
use Modules\Quiz\Entities\Quiz;
use Modules\Quiz\Entities\QuizPart;
use Modules\Quiz\Entities\QuizRegister;
use Modules\Online\Entities\OnlineCourseActivity;
use Modules\Online\Entities\OnlineCourseActivityFile;
use Modules\Online\Entities\OnlineCourseActivityUrl;
use Modules\Online\Entities\OnlineCourseActivityVideo;
use Modules\Online\Entities\OnlineCourseActivityXapi;
use Modules\Online\Entities\OnlineViewActivity;
use App\Events\SaveTrainingProcessRegister;
use App\Models\CourseBookmark;
use Modules\Online\Entities\ActivityScormScore;
use Modules\Online\Entities\OnlineObject;
use Modules\Online\Entities\OnlineRating;
use Modules\Quiz\Entities\QuizAttempts;
use Modules\Quiz\Entities\QuizSetting;
use Modules\Quiz\Entities\QuizTeacherGraded;

class OnlineController extends Controller
{
    public function index(Request $request){
        $items = $this->getItems($request);
        $lay = 'online';
        return view('themes.mobile.frontend.online_course.index', [
            'items' => $items,
            'lay' => $lay
        ]);
    }

    public function getItems(Request $request) {
        $type = $request->type;

        OnlineCourse::addGlobalScope(new CompanyScope());
        $query = OnlineCourse::query();
        $query->where('el_online_course.status', '=', 1);
        $query->where('el_online_course.isopen', '=', 1);
        $query->where('el_online_course.offline', '=', 0);
        $query->whereNotExists(function($sub){
            $sub->select(['id'])
            ->from('el_online_register')
            ->whereColumn('course_id', '=', 'el_online_course.id')
            ->where('user_id', '=', profile()->user_id)
            ->where('status', '=', 1);
        });

        if($type){
            if ($type == 1){
                $query->where(function ($sub){
                    $sub->whereNull('end_date');
                    $sub->orWhere('end_date', '>', date('Y-m-d'));
                })
                    ->where('start_date', '<', date('Y-m-d'));
            }
            if ($type == 2){
                $query->where('start_date', '>', date('Y-m-d'));
            }
            if ($type == 3){
                $query->leftJoin('el_online_register', 'el_online_register.course_id', '=', 'el_online_course.id')
                    ->where('el_online_register.user_id', '=', profile()->user_id)
                    ->where('el_online_register.status', '=', 1)
                    ->whereNotExists(function ($subquery) {
                        $subquery->select(['id'])
                            ->from('el_online_result')
                            ->whereColumn('register_id', '=', 'el_online_register.id')
                            ->where('result', '=', 1);
                    })
                    ->where(function ($sub){
                        $sub->whereNull('el_online_course.end_date');
                        $sub->orWhere('el_online_course.end_date', '>', date('Y-m-d'));
                    })
                    ->where('el_online_course.start_date', '<', date('Y-m-d'));
            }
            if ($type == 4){
                $query->where(\DB::raw('month(start_date)'), '=', date('m'));
            }
        }

        if (Profile::usertype() == 2){
            $query->orderBy('el_online_course.id');

            $items = $query->limit(3)->get();
        }else{
            $query->orderByDesc('el_online_course.id');

            $items = $query->paginate(10);
            $items->appends($request->query());

        }

        return $items;
    }

    public function detail($id, Request $request){
        $item = OnlineCourse::where('id', '=', $id)
            ->where('status', '=', 1)
            ->where('isopen', '=', 1)
            ->firstOrFail();
        OnlineCourse::updateItemViews($id, $item->views);
        $checkObject = OnlineObject::where('course_id', $item->id)->exists();

        $course_time = preg_replace("/[^0-9]./", '', $item->course_time);
        $course_time_unit = preg_replace("/[^a-z]/", '', $item->course_time);
        $comments = OnlineComment::where('course_id', '=', $id)
            ->orderBy('created_at', 'DESC')
            ->paginate(5);
        $rating_course = RatingCourse::where('course_id', '=', $id)
            ->where('user_id', '=', profile()->user_id)
            ->where('type', '=', 1)
            ->first();

        $user_id = profile()->user_id;
        if($item->auto == 2) {
            $this->autoRegisterCourse($user_id, $id);
        }
        $get_activity_courses = OnlineCourseActivity::where('course_id',$id)->get();
        $lessons_course = OnlineCourseLesson::where('course_id',$id)->get();
        $time_user_view_course = UserViewCourse::updateOrCreate([
            'course_id' => $id,
            'course_type' => 1,
            'user_id' => $user_id,
        ], [
            'course_id' => $id,
            'course_type' => 1,
            'user_id' => $user_id,
            'time_view' => date('Y-m-d H:i'),
        ]);
        $get_result = OnlineResult::where('course_id',$id)->where('user_id',$user_id)->first(['result','score']);
        $check_register = OnlineRegister::where('course_id',$id)->where('user_id',$user_id)->first(['status']);
        $date_join = OnlineCourseActivityHistory::select('created_at')->where('course_id',$id)->where('user_id',$user_id)->first();
        $ask_answer = OnlineCourseAskAnswer::select([
            'a.*',
            \DB::raw("CONCAT_WS(' ',lastname, firstname) AS fullname"),
            'b.avatar',
            'c.name',
        ])
            ->from('el_online_course_ask_answer AS a')
            ->leftJoin('el_profile AS b', function ($sub){
                $sub->on('b.user_id', '=', 'a.user_id_ask')
                    ->where('a.user_type_ask', '=', 1);
            })
            ->leftJoin('el_quiz_user_secondary AS c', function ($sub){
                $sub->on('c.id', '=', 'a.user_id_ask')
                    ->where('a.user_type_ask', '=', 2);
            })
            ->where('a.course_id', '=', $id)
            ->where('a.user_id_ask', '=', getUserId())
            ->where('a.user_type_ask', '=', getUserType())
            ->where('a.status', '=', 1)
            ->orderBy('a.id', 'desc')
            ->paginate(5);

        $notes = OnlineCourseNote::select([
            'a.*',
            \DB::raw("CONCAT_WS(' ',lastname, firstname) AS fullname"),
            'b.avatar',
            'c.name',
        ])
            ->from('el_online_course_note AS a')
            ->leftJoin('el_profile AS b', function ($sub){
                $sub->on('b.user_id', '=', 'a.user_id')
                    ->where('a.user_type', '=', 1);
            })
            ->leftJoin('el_quiz_user_secondary AS c', function ($sub){
                $sub->on('c.id', '=', 'a.user_id')
                    ->where('a.user_type', '=', 2);
            })
            ->where('a.course_id', '=', $id)
            ->where('a.user_id', '=', getUserId())
            ->where('a.user_type', '=', getUserType())
            ->orderBy('a.id', 'desc')
            ->paginate(5);

        $profile = ProfileView::where('user_id', '=', $user_id)->first(['full_name','type_user']);

        $id_activity_scorm_xapi = '';
        $type_activity = 0;
        $link = '';
        $check_activity_active = OnlineViewActivity::where('course_id',$id)->where('user_id',$user_id)->first();
        $get_first_activity= '';

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
                $link = route('module.online.view_pdf', [$id]).'?path='. $file_path;
            } else if ($check_type_activity == 4) {
                $file = OnlineCourseActivityUrl::find($get_first_activity->subject_id);
                $link = $file->url;
                if (is_youtube_url($link)) {
                    $link = 'https://www.youtube.com/embed/' . get_youtube_id($link);
                }
            } else if ($check_type_activity == 5) {
                $file = OnlineCourseActivityVideo::find($get_first_activity->subject_id);
                $link = upload_file($file->path);
            } else if ($check_type_activity == 1) {
                $link = OnlineCourseActivityScorm::find($get_first_activity->subject_id);
                $id_activity_scorm_xapi = @$link->id;
                $type_activity = 1;
            } else if ($check_type_activity == 2) {
                $link = $get_first_activity->getLinkQuizCourse($get_first_activity->lesson_id);
                $type_activity = 2;
            }
            elseif($check_type_activity==7){
                $link = OnlineCourseActivityXapi::find($get_first_activity->subject_id);
                $id_activity_scorm_xapi = @$link->id;
                $type_activity = 7;
            }
        }

        $get_bookmarked = CourseBookmark::where('course_id', $item->id)->where('type', 1)->where('user_id', $user_id)->exists();
        $isRating = OnlineRating::getRating($item->id, $user_id);

        $my_course = $request->my_course;
        session(['my_course' => $my_course]);

        return view('themes.mobile.frontend.online_course.detail', [
            'item' => $item,
            'course_time' => $course_time,
            'course_time_unit' => $course_time_unit,
            'comments' => $comments,
            'rating_course' => $rating_course,
            'lessons_course' => $lessons_course,
            'get_activity_courses' => $get_activity_courses,
            'time_user_view_course' => $time_user_view_course,
            'get_result' => $get_result,
            'check_register' => $check_register,
            'date_join' => $date_join,
            'ask_answer' => $ask_answer,
            'notes' => $notes,
            'profile' => $profile,
            'type_activity' => $type_activity,
            'checkObject' => $checkObject,
            'get_bookmarked' => $get_bookmarked,
            'isRating' => $isRating,
            'my_course' => $my_course,
        ]);
    }

    public function comment($id, Request $request){
        $this->validateRequest([
            'content' => 'required',
        ], $request, ['content' => trans("latraining.content")]);

        $content = strtolower($request->post('content'));

        if (strpos($content, 'sex') !== false || strpos($content, 'xxx') !== false || strpos($content, 'địt') !== false){
            return response()->json([
                'message' => 'Nội dung có từ phản cảm',
                'status' => 'warning',
            ]);
        }

        $model = new OnlineComment();
        $model->course_id = $id;
        $model->user_id = profile()->user_id;
        $model->content = $request->post('content');
        $model->save();

        return response()->json([
            'message' => trans('laother.successful_save'),
            'status' => 'success',
            'redirect' => route('themes.mobile.frontend.online.detail', ['course_id' => $id, 'my_course' => $request->my_course]),
        ]);
    }

    public function ask_answer($id, Request $request) {
        $this->validateRequest([
            'ask_content' => 'required|string|max:1000',
        ], $request, ['ask_content' => 'Nội dung không được để trống']);

        $ask_content = $request->ask_content;
        if (strpos($ask_content, 'sex') !== false || strpos($ask_content, 'xxx') !== false || strpos($ask_content, 'địt') !== false){
            return response()->json([
                'message' => 'Nội dung có từ phản cảm',
                'status' => 'warning',
            ]);
        }

        $model = new OnlineCourseAskAnswer();
        $model->course_id = $id;
        $model->user_id_ask = getUserId();
        $model->user_type_ask = getUserType();
        $model->ask = $ask_content;
        $model->save();

        return response()->json([
            'message' => trans('laother.successful_save'),
            'status' => 'success',
            'redirect' => route('themes.mobile.frontend.online.detail', ['course_id' => $id]),
        ]);
    }

    public function note($id, Request $request) {
        $note_content = $request->note_content;
        if (strpos($note_content, 'sex') !== false || strpos($note_content, 'xxx') !== false || strpos($note_content, 'địt') !== false){
            return response()->json([
                'message' => 'Nội dung có từ phản cảm',
                'status' => 'warning',
            ]);
        }

        $model = OnlineCourseNote::firstOrNew(['id'=> $request->input('id')]);
        $model->course_id = $id;
        $model->user_id = getUserId();
        $model->user_type = getUserType();
        $model->note = $note_content ?? '';
        $model->save();

        return response()->json([
            'message' => trans('laother.successful_save'),
            'status' => 'success',
            'redirect' => route('themes.mobile.frontend.online.detail', ['course_id' => $id]),
        ]);
    }

    public function removeNoteCourse(Request $request) {

        OnlineCourseNote::find($request->id)->delete();

        return response()->json([
            'message' => 'Xoá thành công',
            'status' => 'success',
        ]);
    }

    public function viewScorm($course_id, $activity_id, $attempt_id, Request $request){
        $title = $request->title;

        $course = OnlineCourse::findOrFail($course_id);
        $activity = OnlineCourseActivityScorm::findOrFail($activity_id);
        $attempt = $activity->attempts()
            ->where('id', $attempt_id)
            ->firstOrFail(['id', 'suspend_data']);

        return view('themes.mobile.frontend.online_course.scorm.player', [
            'course' => $course,
            'activity' => $activity,
            'attempt' => $attempt,
            'user' => Auth::user(),
            'title' => $title,
        ]);

        /*return view('themes.mobile.frontend.online_course.scorm.player', [
            'course_id' => $course_id,
            'activity_id' => $activity_id,
            'attempt_id' => $attempt_id,
            'title' => $title
        ]);*/
    }
    public function viewXapi($course_id, $activity_id, $attempt_id, Request $request){
        $title = $request->title;
        $course = OnlineCourse::findOrFail($course_id);
        $activity = OnlineCourseActivityXapi::findOrFail($activity_id);
        $attempt = $activity->attempts()
            ->where('id', $attempt_id)
            ->firstOrFail(['id','uuid']);

        return view('themes.mobile.frontend.online_course.xapi.player', [
            'course' => $course,
            'activity' => $activity,
            'attempt' => $attempt,
            'title' => $title,
        ]);

        /*return view('themes.mobile.frontend.online_course.scorm.player', [
            'course_id' => $course_id,
            'activity_id' => $activity_id,
            'attempt_id' => $attempt_id,
            'title' => $title
        ]);*/
    }
    public function autoRegisterCourse($user_id, $course_id) {
        $course = OnlineCourse::find($course_id, ['id', 'code', 'name', 'start_date', 'end_date', 'cert_code', 'subject_id']);
        $subject = Subject::find($course->subject_id, ['id', 'code', 'name']);
        event(new SaveTrainingProcessRegister($course, $subject, $user_id, null, 1));

        $model = OnlineRegister::firstOrNew(['user_id' => $user_id, 'course_id' => $course_id]);
        $model->user_id = $user_id;
        $model->course_id = $course_id;
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

    public function goActivity($course_id, $course_activity_id,$lesson) {
        $course_activity = OnlineCourseActivity::findOrFail($course_activity_id);
        $link = $course_activity->getLink($lesson);
        if (empty($link)) {
            return abort(404);
        }

        event(new GoActivity($course_id, $course_activity_id));

        return redirect()->to($link);
    }

    public function modalObject($course_id)
    {
        return view('themes.mobile.frontend.online_course.modal_object', [
            'course_id' => $course_id,
        ]);
    }

    public function modalNoteCourse($id)
    {
        $item = OnlineCourse::where('id', '=', $id)
            ->where('status', '=', 1)
            ->where('isopen', '=', 1)
            ->firstOrFail();

        $notes = OnlineCourseNote::where('course_id', '=', $id)
            ->where('user_id', '=', getUserId())
            ->where('user_type', '=', getUserType())
            ->first();

        return view('themes.mobile.frontend.online_course.note', [
            'course_id' => $id,
            'item' => $item,
            'notes' => $notes,
        ]);
    }

    public function modalResultCourse($id)
    {
        $user_id = profile()->user_id;

        $item = OnlineCourse::where('id', '=', $id)->where('status', '=', 1)->where('isopen', '=', 1)->firstOrFail();
        $time_user_view_course = UserViewCourse::where(['course_id' => $id,'course_type' => 1,'user_id' => $user_id])->first();
        $get_result = OnlineResult::where('course_id',$id)->where('user_id',$user_id)->first(['result','score']);
        $check_register = OnlineRegister::where('course_id',$id)->where('user_id',$user_id)->first(['status']);
        $profile = profile();
        $get_activity_courses = OnlineCourseActivity::where('course_id',$id)->get();
        $date_join = OnlineCourseActivityHistory::select('created_at')->where('course_id',$id)->where('user_id',$user_id)->first();

        $condition_activity = OnlineCourseCondition::where('course_id', $item->id)->first();
        if(!empty($condition_activity)) {
            $condition_activity = explode(',',$condition_activity->activity);
        }

        return view('themes.mobile.frontend.online_course.modal_result', [
            'course_id' => $id,
            'item' => $item,
            'time_user_view_course' => $time_user_view_course,
            'get_result' => $get_result,
            'check_register' => $check_register,
            'profile' => $profile,
            'get_activity_courses' => $get_activity_courses,
            'date_join' => $date_join,
            'condition_activity' => $condition_activity
        ]);
    }

    public function modalHistoryCourse($id)
    {
        $get_activity_courses = OnlineCourseActivity::where('course_id',$id)->whereIn('activity_id', [1,2])->get();

        return view('themes.mobile.frontend.online_course.modal_history', [
            'course_id' => $id,
            'get_activity_courses' => $get_activity_courses,
        ]);
    }

    public function modalHistoryDetailCourse($id, $course_activity_id){
        $user_type = getUserType();
        $user_id = getUserId();

        $get_activity_quiz_scorm = OnlineCourseActivity::where('course_id',$id)->where('id', $course_activity_id)->first();
        if($get_activity_quiz_scorm->activity_id == 1){
            $activity_scorm = OnlineCourseActivityScorm::find($get_activity_quiz_scorm->subject_id);
            $activity_history_scorm = $activity_scorm->attempts()->where('user_id', '=', $user_id)->orderBy('id', 'desc')->paginate(12);
            foreach ($activity_history_scorm as $row){
                $score_scorm = ActivityScormScore::query()
                    ->where('user_id', '=', $user_id)
                    ->where('user_type', '=', $user_type)
                    ->where('activity_id', '=', $activity_scorm->id)
                    ->where('attempt_id', '=', $row->id)
                    ->first();

                if ($score_scorm){
                    $row->end_date = get_date($score_scorm->created_at, 'H:i:s d/m/Y');
                    if (!is_null($score_scorm->score)) {
                        $row->grade = number_format($score_scorm->score, 2);
                    }
                    else {
                        $row->grade = null;
                    }
                }

                $row->start_date = get_date($row->created_at, 'H:i:s d/m/Y');

            }
        }
        if($get_activity_quiz_scorm->activity_id == 2){
            $quiz_id = $get_activity_quiz_scorm->subject_id;
            $quiz = Quiz::where('id', '=', $quiz_id)->firstOrFail();
            $max_end_date = $quiz->end_quiz;

            $quiz_setting = QuizSetting::where('quiz_id', '=', $quiz_id)->first();
            $graded = QuizTeacherGraded::where('quiz_id', '=', $quiz_id)->where('user_id', '=', $user_id)->first();

            $activity_history_quiz = QuizAttempts::where('quiz_id', '=', $quiz_id)->orderBy('id', 'desc')->paginate(12);
            foreach ($activity_history_quiz as $row) {
                $status = '';
                switch ($row->state) {
                    case 'inprogress': $status = 'Đang làm bài'; break;
                    case 'completed': $status = 'Đã nộp bài'; break;
                }
                $row->start_date = date('H:i d/m/Y', $row->timestart);
                $row->end_date = $row->timefinish > 0 ? date('H:i d/m/Y', $row->timefinish) : '';

                if ($row->teacher_grade == 1 && !$graded){
                    $row->grade = '_';
                }else{
                    $row->grade = $quiz_setting ? (($quiz_setting->after_test_score == 1 || ($quiz_setting->exam_closed_score == 1 && date('H:i') > get_date($max_end_date, 'H:i') && date('Y-m-d') > get_date($max_end_date, 'Y-m-d'))) ? round($row->sumgrades, 2) : '_') : '_';
                }
                $row->status = $status;
            }
        }

        return view('themes.mobile.frontend.online_course.modal_history_detail', [
            'course_id' => $id,
            'get_activity_quiz_scorm' => $get_activity_quiz_scorm,
            'activity_history_scorm' => $activity_history_scorm,
            'activity_history_quiz' => $activity_history_quiz,
        ]);
    }

    public function goActivityDetail($id, Request $request){
        $item = OnlineCourse::where('id', '=', $id)
            ->where('status', '=', 1)
            ->where('isopen', '=', 1)
            ->first(['id', 'name', 'code']);

        $user_id = profile()->user_id;

        $lessons_course = OnlineCourseLesson::where('course_id',$id)->get();

        $check_activity_active = OnlineViewActivity::where('course_id',$id)->where('user_id',$user_id)->first();
        $get_first_activity= '';

        if(!empty($check_activity_active)) {
            $get_first_activity = OnlineCourseActivity::where('id',$check_activity_active->activity_id)->first();
        } else {
            $get_first_activity = OnlineCourseActivity::where('course_id',$id)->first();
            $get_first_activity && event(new GoActivity($id, @$get_first_activity->id));
        }
        $type_activity = $get_first_activity ? $get_first_activity->activity_id : 0;

        return view('themes.mobile.frontend.online_course.go_activity', [
            'course_id' => $id,
            'item' => $item,
            'lessons_course' => $lessons_course,
            'type_activity' => $type_activity,
            'get_first_activity' => $get_first_activity,
        ]);
    }
}
