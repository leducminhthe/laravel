<?php

namespace App\Http\Controllers\Mobile;

use App\Events\Online\GoActivity;
use App\Models\Profile;
use App\Scopes\CompanyScope;
use App\Models\Slider;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CourseBookmark;
use Illuminate\Support\Facades\Auth;
use Modules\Indemnify\Entities\Indemnify;
use Modules\Offline\Entities\OfflineAttendance;
use Modules\Offline\Entities\OfflineComment;
use Modules\Offline\Entities\OfflineCourse;
use Modules\Offline\Entities\OfflineCourseActivity;
use Modules\Offline\Entities\OfflineCourseActivityTeams;
use Modules\Offline\Entities\OfflineCourseActivityZoom;
use Modules\Offline\Entities\OfflineCourseClass;
use Modules\Offline\Entities\OfflineRating;
use Modules\Offline\Entities\OfflineRegister;
use Modules\Online\Entities\OnlineCourse;
use Modules\Online\Entities\OnlineCourseActivity;
use Modules\Online\Entities\OnlineRegister;
use Modules\Online\Entities\OnlineViewActivity;
use Modules\Quiz\Entities\Quiz;
use Modules\Quiz\Entities\QuizPart;
use Modules\Quiz\Entities\QuizRegister;
use Modules\Rating\Entities\RatingCourse;
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
use Modules\Offline\Entities\OfflineTeacherClass;
use App\Models\Categories\TrainingTeacherStar;

class OfflineController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request){
        $items = $this->getItems($request);
        $lay = 'offline';
        $text_status = function ($status) {
            return OfflineCourse::getStatusRegisterText($status);
        };
        $class_status = function ($status) {
            return OfflineCourse::getBtnClassStatusRegister($status);
        };

        return view('themes.mobile.frontend.offline_course.index', [
            'items' => $items,
            'lay' => $lay,
            'text_status' => $text_status,
            'class_status' => $class_status,
        ]);
    }

    public function getItems(Request $request) {
        $type = $request->type;

        OfflineCourse::addGlobalScope(new CompanyScope());
        $query = OfflineCourse::query();
        $query->where('el_offline_course.status', '=', 1);
        $query->where('el_offline_course.isopen', '=', 1);
        $query->whereNotExists(function($sub){
            $sub->select(['id'])
            ->from('el_offline_register')
            ->whereColumn('el_offline_register.course_id', '=', 'el_offline_course.id')
            ->where('el_offline_register.user_id', '=', profile()->user_id)
            ->where('el_offline_register.status', '=', 1);
        });

        if($type){
            if ($type == 1){
                $query->where('end_date', '>', date('Y-m-d'))
                    ->where('start_date', '<', date('Y-m-d'));
            }
            if ($type == 2){
                $query->where('start_date', '>', date('Y-m-d'));
            }
            if ($type == 3){
                $query->leftJoin('el_offline_register', 'el_offline_register.course_id', '=', 'el_offline_course.id')
                    ->where('el_offline_register.user_id', '=', profile()->user_id)
                    ->where('el_offline_register.status', '=', 1)
                    ->whereNotExists(function ($subquery) {
                        $subquery->select(['id'])
                            ->from('el_offline_result')
                            ->whereColumn('register_id', '=', 'el_offline_register.id')
                            ->where('result', '=', 1);
                    })
                    ->where('el_offline_course.end_date', '>', date('Y-m-d'))
                    ->where('el_offline_course.start_date', '<', date('Y-m-d'));
            }
            if ($type == 4){
                $query->where(\DB::raw('month(start_date)'), '=', date('m'));
            }
        }

        $query->orderByDesc('el_offline_course.id');
        $items = $query->paginate(10);
        $items->appends($request->query());

        return $items;
    }

    public function detail($id, Request $request){
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

        $categories = OfflineCourse::getCourseCategory($item->training_program_id, $item->id);

        $comments = OfflineComment::where('course_id', '=', $id)->get();
        $profile = Profile::where('user_id', '=', $user_id)->first();
        $rating_course = RatingCourse::query()
            ->where('course_id', '=', $id)
            ->where('user_id', '=', $user_id)
            ->where('type', '=', 2)
            ->first();

        $indem = Indemnify::query()
            ->where('user_id', '=', $user_id)
            ->where('course_id', '=', $id)
            ->first();

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
        $course_time = preg_replace("/[^0-9]./", '', $item->course_time);
        $course_time_unit = preg_replace("/[^a-z]/", '', $item->course_time);

        $classes = OfflineCourseClass::whereCourseId($id)->get();

        $offlineActivityOnlines = OfflineCourseActivity::where(['course_id' => $id, 'activity_id' => 1])->get();
        foreach($offlineActivityOnlines as $offlineActivityOnline) {
            $courseOnline = OnlineCourse::find($offlineActivityOnline->subject_id, ['id', 'name']);

            $offlineActivityOnline->courseOnline = $courseOnline;
            $offlineActivityOnline->nameOnlineCourse = $courseOnline->name;

            if($register){
                $online_register = OnlineRegister::firstOrNew(['user_id' => $user_id, 'course_id' => $offlineActivityOnline->subject_id]);
                $online_register->user_id = $user_id;
                $online_register->course_id = $offlineActivityOnline->subject_id;
                $online_register->register_form = 1;
                $online_register->status = 1;
                $online_register->approved_step = '1/1';

                $quizs = Quiz::where('course_id', '=', $offlineActivityOnline->subject_id)->where('status', '=', 1)->get();
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
                $online_register->save();
            }
        }

        $offlineActivityMettings = OfflineCourseActivity::where(['course_id' => $id, 'activity_id' => 2])
            ->whereIn('subject_id', function($sub) use($register) {
                $sub->select(['id'])
                    ->from('offline_course_activity_teams')
                    ->where('course_id', $register->course_id)
                    ->where('class_id', $register->class_id);
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

            $attendance = OfflineAttendance::where('course_id', $id)
                ->where('user_id', $user_id)
                ->where('schedule_id', $activityMetting->schedule_id)
                ->where('status', 1)
                ->first();

            $offlineActivityMetting->check_attendance = $attendance ? 1 : 0;
        }

        $offlineActivityZooms = OfflineCourseActivity::where(['course_id' => $id, 'activity_id' => 3])
            ->whereIn('subject_id', function($sub) use($register) {
                $sub->select(['id'])
                    ->from('offline_course_activity_zoom')
                    ->where('course_id', $register->course_id)
                    ->where('class_id', $register->class_id);
            })
            ->get();
        foreach($offlineActivityZooms as $offlineActivityZoom) {
            $activityZoom = OfflineCourseActivityZoom::find($offlineActivityZoom->subject_id);

            $offlineActivityZoom->topic = $activityZoom->topic;
            $offlineActivityZoom->start_time = get_date($activityZoom->start_time, 'H:i d/m/Y');
            $offlineActivityZoom->end_time = get_date($activityZoom->end_time, 'H:i d/m/Y');
            $offlineActivityZoom->linkZoom = ($activityZoom->end_time < date('Y-m-d H:i:s') ? '#' : $activityZoom->join_url);
        }

        $check_activity_course = false;
        if($offlineActivityOnlines->count() > 0 || $offlineActivityMettings->count() > 0 || $offlineActivityZooms->count() > 0){
            $check_activity_course = true;
        }

        $get_bookmarked = CourseBookmark::where('course_id', $item->id)->where('type', 2)->where('user_id', $user_id)->exists();
        $isRating = OfflineRating::getRating($item->id, $user_id);

        $url_quiz = '';
        if($item->quiz_id){
            $quiz_register = QuizRegister::whereQuizId($item->quiz_id)->where('user_id', $user_id)->first();
            if($quiz_register){
                $url_quiz = route('module.quiz_mobile.doquiz.index', [$quiz_register->quiz_id, $quiz_register->part_id]);
            }
        }

        $my_course = $request->my_course;
        session(['my_course' => $my_course]);
        $organization_user = OfflineTeachingOrganizationUser::where('course_id', $item->id)->where('user_id', profile()->user_id)->exists();

        return view('themes.mobile.frontend.offline_course.detail', [
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
            'classes' => $classes,
            'offlineActivityOnlines' => $offlineActivityOnlines,
            'offlineActivityMettings' => $offlineActivityMettings,
            'offlineActivityZooms' => $offlineActivityZooms,
            'type_activity' => 0,
            'user_id' => $user_id,
            'check_activity_course' => $check_activity_course,
            'get_bookmarked' => $get_bookmarked,
            'isRating' => $isRating,
            'url_quiz' => $url_quiz,
            'my_course' => $my_course,
            'organization_user' => $organization_user
        ]);
    }

    public function goActivity($id, Request $request){
        $item = OfflineCourse::where('id', '=', $id)
            ->where('status', '=', 1)
            ->where('isopen', '=', 1)
            ->first(['id', 'name', 'code']);

        $user_id = profile()->user_id;
        $register = OfflineRegister::where('user_id', '=', $user_id)
                    ->where('course_id', '=', $id)
                    ->where('status', '=', 1)
                    ->first();

        $offlineActivityOnlines = OfflineCourseActivity::where(['course_id' => $id, 'activity_id' => 1])->get();
        foreach($offlineActivityOnlines as $offlineActivityOnline) {
            $courseOnline = OnlineCourse::find($offlineActivityOnline->subject_id, ['id', 'name']);

            $offlineActivityOnline->courseOnline = $courseOnline;
            $offlineActivityOnline->nameOnlineCourse = $courseOnline->name;
        }

        $offlineActivityMettings = OfflineCourseActivity::where(['course_id' => $id, 'activity_id' => 2])
            ->whereIn('subject_id', function($sub) use($register) {
                $sub->select(['id'])
                    ->from('offline_course_activity_teams')
                    ->where('course_id', $register->course_id)
                    ->where('class_id', $register->class_id);
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

            $attendance = OfflineAttendance::where('course_id', $id)
                ->where('user_id', $user_id)
                ->where('schedule_id', $activityMetting->schedule_id)
                ->where('status', 1)
                ->first();

            $offlineActivityMetting->check_attendance = $attendance ? 1 : 0;
        }

        $offlineActivityZooms = OfflineCourseActivity::where(['course_id' => $id, 'activity_id' => 3])
            ->whereIn('subject_id', function($sub) use($register) {
                $sub->select(['id'])
                    ->from('offline_course_activity_zoom')
                    ->where('course_id', $register->course_id)
                    ->where('class_id', $register->class_id);
            })
            ->get();
        foreach($offlineActivityZooms as $offlineActivityZoom) {
            $activityZoom = OfflineCourseActivityZoom::find($offlineActivityZoom->subject_id);

            $offlineActivityZoom->topic = $activityZoom->topic;
            $offlineActivityZoom->start_time = get_date($activityZoom->start_time, 'H:i d/m/Y');
            $offlineActivityZoom->end_time = get_date($activityZoom->end_time, 'H:i d/m/Y');
            $offlineActivityZoom->linkZoom = ($activityZoom->end_time < date('Y-m-d H:i:s') ? '#' : $activityZoom->join_url);
        }

        return view('themes.mobile.frontend.offline_course.go_activity', [
            'course_id' => $id,
            'item' => $item,
            'offlineActivityOnlines' => $offlineActivityOnlines,
            'offlineActivityMettings' => $offlineActivityMettings,
            'offlineActivityZooms' => $offlineActivityZooms,
            'type_activity' => 0,
            'user_id' => $user_id,
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

        $model = new OfflineComment();
        $model->course_id = $id;
        $model->user_id = profile()->user_id;
        $model->content = $request->post('content');

        if ($model->save()) {
            return json_result([
                'redirect' => route('themes.mobile.frontend.offline.detail', ['course_id' => $id, 'my_course' => $request->my_course])
            ]);
        }
    }

    public function checkPDF(Request $request){
        $path = explode('uploads/', $request->path);
        $file = explode('/', $path[1]);
        if (isFilePdf($file[3])){
            json_result([
                'status' => 'success',
                'path' => $request->path
            ]);
        }

        json_result([
            'status' => 'error'
        ]);
    }
    public function viewPDF(Request $request){
        $path = $request->path;
        $path = convert_url_web_to_app($path);

        return view('themes.mobile.frontend.offline_course.view_pdf', [
            'path' => $path,
        ]);
    }

    // ĐÁNH GIÁ CƠ CẤU TỔ CHỨ GIẢNG DẠY
    public function ratingTeacher($course_id) {
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

        return view('themes.mobile.frontend.offline_course.rating_teacher', [
            'template' => $template,
            'item' => $item,
            'categories' => $categories,
            'fquestions' => $fquestions,
            'fanswer' => $fanswer,
            'fanswer_matrix' => $fanswer_matrix,
            'teachers' => $teachers,
        ]);
    }

    // LƯU ĐÁNH GIÁ TỔ CHỨC GIẢNG VIÊN
    public function saveRatingTeaching($course_id, Request $request){
        $user_id = profile()->user_id;
        $register = OfflineRegister::whereCourseId($course_id)->whereUserId($user_id)->first('class_id');

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
        $organization_user = OfflineTeachingOrganizationUser::where('course_id', $course_id)->where('user_id', profile()->user_id)->exists();
        if($organization_user) {
            json_result([
                'status' => 'success',
                'message' => 'Bạn đã đánh giá cơ cấu này',
                'redirect' => route('themes.mobile.frontend.offline.detail', [$course_id]),
            ]);
        }

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
            'redirect' => route('themes.mobile.frontend.offline.detail', [$course_id]),
        ]);
    }

    // CHỈNH SỬA ĐÁNH GIÁ CƠ CẤU TỔ CHỨC GIẢNG DẠY
    public function editRatingTeaching($course_id, Request $request){
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

        return view('themes.mobile.frontend.offline_course.edit_rating_teacher', [
            'organization_user' => $organization_user,
            'item' => $item,
            'categories' => $categories,
            'fquestions' => $fquestions,
            'fanswer' => $fanswer,
            'fanswer_matrix' => $fanswer_matrix,
            'teachers' => $teachers,
        ]);
    }
}
