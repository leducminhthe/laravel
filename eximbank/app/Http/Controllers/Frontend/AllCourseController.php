<?php

namespace App\Http\Controllers\Frontend;

use App\Models\CourseView;
use App\Models\CourseRegisterView;
use App\Models\Automail;
use App\Models\Config;
use App\Models\CourseComplete;
use App\Models\Profile;
use App\Models\ProfileView;
use App\Events\Online\GoActivity;
use App\Models\Categories\Unit;
use App\Models\Permission;
use App\Models\Categories\Subject;
use App\Models\Categories\TrainingProgram;
use App\Models\Categories\TrainingForm;
use App\Scopes\CompanyScope;
use App\Scopes\DraftScope;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\AppNotification\Helpers\AppNotification;
use Modules\Notify\Entities\Notify;
use Modules\Online\Entities\OnlineCourse;
use Illuminate\Support\Facades\Auth;
use Modules\RefererHist\Entities\RefererRegisterCourse;
use Modules\User\Entities\TrainingProcess;
use App\Models\Categories\Titles;
use App\Models\Categories\LevelSubject;
use Modules\Promotion\Entities\PromotionCourseSetting;
use App\Models\CourseBookmark;
use App\Models\PlanApp;
use App\Models\PlanAppStatus;
use App\Models\RelatedSubject;
use Carbon\Carbon;
use Modules\Online\Entities\OnlineResult;
use Modules\Offline\Entities\OfflineResult;
use App\Models\UserViewCourse;
use Modules\Offline\Entities\OfflineCourse;
use Modules\TrainingRoadmap\Entities\TrainingRoadmap;
use Illuminate\Database\Query\Builder;
use Modules\Offline\Entities\OfflineRatingLevelObject;
use Modules\Quiz\Entities\QuizPart;
use Modules\Quiz\Entities\QuizRegister;
use Modules\Quiz\Entities\QuizResult;
use Modules\Rating\Entities\RatingLevelCourse;
use Modules\Online\Entities\OnlineCourseActivity;
use Modules\Online\Entities\OnlineCourseActivityCompletion;
use Modules\TrainingByTitle\Entities\TrainingByTitleCategory;
use Modules\TrainingByTitle\Entities\TrainingByTitleDetail;
use Modules\User\Entities\UserCompletedSubject;
use Modules\Online\Entities\OnlineRatingLevelObject;
use Modules\TrainingByTitle\Entities\TrainingByTitleUploadImage;
use Modules\CareerRoadmap\Entities\CareerRoadmap;
use \Modules\CareerRoadmap\Entities\CareerRoadmapTitle;
use Modules\Offline\Entities\OfflineCourseClass;
use Modules\Offline\Entities\OfflineCourseComplete;
use Modules\Offline\Entities\OfflineRegister;
use Modules\Offline\Entities\OfflineTeachingOrganizationTemplate;
use Modules\Offline\Entities\OfflineTeachingOrganizationUser;
use Modules\Online\Entities\OnlineCourseComplete;
use Modules\Survey\Entities\SurveyUser;
use Modules\Quiz\Entities\Quiz;

class AllCourseController extends Controller
{
    public function index($type, Request $request) {
        $user_id = profile()->user_id;
        $profile_name = profile();

        $items = $this->getItems($type, $request);
        $last_page = $items[1];
        $training_programs = TrainingProgram::where('status',1)->get(['id', 'name']);
        $level_subjects = LevelSubject::where('status',1)->get(['id', 'name']);

        $progress = 0;
        $count_subject = 0;
        $count_subject_complete = 0;
        $training_by_title_category = [];
        $count_subject_completed = 0;
        $imageTrainingByTitle = '';
        $sub_titles = [];
        $roadmaps = [];
        $progressCareerRoadmap = 0;
        $progressTrainingByTitle = 0;

        if ($profile_name->date_title_appointment) {
            $start_date = get_date($profile_name->date_title_appointment, 'Y-m-d');
        } elseif ($profile_name->effective_date) {
            $start_date = get_date($profile_name->effective_date, 'Y-m-d');
        } elseif ($profile_name->join_company) {
            $start_date = get_date($profile_name->join_company, 'Y-m-d');
        } else {
            $start_date = get_date($profile_name->created_at, 'Y-m-d');
        }

        if ($type == 3) {
            $career_roadmaps = CareerRoadmap::where('title_id', '=', @$profile_name->title_id)->where('primary', '=', 1)->latest()->first();
            if($career_roadmaps) {
                $countCarrerRoadmap = CareerRoadmapTitle::where('career_roadmap_id', @$career_roadmaps->id)->count();
                $totalAllResult = 0;
                $sub_titles = $career_roadmaps->getTitles();
                foreach ($sub_titles as $index => $sub_title) {
                    $total_subject = CareerRoadmapTitle::getSubjectRoadmap($sub_title->title->id);
                    $total_result = 0;
                    if ((count($total_subject) > 0)){
                        foreach ($total_subject as $subject){
                            $trainingForm = json_decode($subject->training_form);
                            if(!empty($trainingForm) || (in_array(1, $trainingForm) && in_array(2, $trainingForm))) {
                                if(in_array(1, $trainingForm)) {
                                    $checkCompleted = UserCompletedSubject::whereUserId($profile_name->user_id)->where('subject_id', $subject->subject_id)->where('course_type', 1)->first();
                                    if ($checkCompleted) {
                                        $total_result += 1;
                                    }
                                } else {
                                    $checkCompleted = UserCompletedSubject::whereUserId($profile_name->user_id)->where('subject_id', $subject->subject_id)->where('course_type', 2)->first();
                                    if ($checkCompleted) {
                                        $total_result += 1;
                                    }
                                }
                            } else {
                                $checkCompleted = UserCompletedSubject::whereUserId($profile_name->user_id)->where('subject_id', $subject->subject_id)->first();
                                if(isset($checkCompleted)) {
                                    $total_result += 1;
                                }
                            }
                        }
                    }
                    $sub_title->percent = ($total_result / ((count($total_subject) > 0) ? count($total_subject) : 1)) * 100;
                    $totalAllResult += $sub_title->percent;
                }
                $progressCareerRoadmap = $totalAllResult / (($countCarrerRoadmap > 0) ? $countCarrerRoadmap : 1);
            }

            $roadmaps = CareerRoadmap::where('title_id', '=', @$profile_name->title_id)->get(['id', 'name']);

            $imageTrainingByTitle = TrainingByTitleUploadImage::where('type', $profile_name->gender)->first();
            $imageTrainingByTitle->image2 = $imageTrainingByTitle->image;

            $training_by_title_category = TrainingByTitleCategory::where('title_id', '=', @$profile_name->title_id)->orderBy('id','desc')->get();

            $getTrainingByTitles = TrainingByTitleDetail::where('title_id', '=', @$profile_name->title_id)->get(['subject_id']);
            $count_training_by_title_detail = 0;
            foreach ($getTrainingByTitles as $item) {
                $countOnlineCourseBySubject = OnlineCourse::where('subject_id', $item->subject_id)->count();
                $countOffilneCourseBySubject = OfflineCourse::where('subject_id', $item->subject_id)->count();
                $total = $countOnlineCourseBySubject + $countOffilneCourseBySubject;
                $count_training_by_title_detail += $total;
            }
            $count_subject_completed = UserCompletedSubject::whereUserId($profile_name->user_id)->groupBy(['subject_id'])->count();

            $query = TrainingRoadmap::query();
            $query->select([
                'a.subject_id',
                'a.training_form'
            ]);
            $query->from('el_trainingroadmap AS a');
            $query->leftJoin('el_subject AS b', 'b.id', '=', 'a.subject_id');
            $query->where('a.title_id', '=', $profile_name->title_id);
            $rows = $query->get();
            $totalSubjectRoadmap = $query->count();
            $countSubjectRoadmapCompleted = 0;
            foreach ($rows as $row) {
                $trainingForm = json_decode($row->training_form);
                if(!empty($trainingForm) || (in_array(1, $trainingForm) && in_array(2, $trainingForm))) {
                    if(in_array(1, $trainingForm)) {
                        $checkCompleted = UserCompletedSubject::whereUserId($profile_name->user_id)->where('subject_id', $row->subject_id)->where('course_type', 1)->first();
                        if ($checkCompleted) {
                            $countSubjectRoadmapCompleted += 1;
                        }
                    } else {
                        $checkCompleted = UserCompletedSubject::whereUserId($profile_name->user_id)->where('subject_id', $row->subject_id)->where('course_type', 2)->first();
                        if ($checkCompleted) {
                            $countSubjectRoadmapCompleted += 1;
                        }
                    }
                } else {
                    $checkCompleted = UserCompletedSubject::whereUserId($profile_name->user_id)->where('subject_id', $row->subject_id)->first();
                    if(isset($checkCompleted)) {
                        $countSubjectRoadmapCompleted += 1;
                    }
                }
            }
            $progressRoadmap = round(($countSubjectRoadmapCompleted / ($totalSubjectRoadmap > 0 ? $totalSubjectRoadmap : 1))*100);

            $progressTrainingByTitle = round(($count_subject_completed / ($count_training_by_title_detail > 0 ? $count_training_by_title_detail : 1))*100);
        }
        $listType = $request->list;
        $data = '';
        if ($request->ajax()) {
            if($request->listType == 'horizontal') {
                $data = $this->loadDataHorizontal($items[0]);
            } else {
                $data = $this->loadDataVertical($items[0]);
            }
            return [$data, $last_page];
        }

        if($type == 3) {
            $course_subject = $this->relatedSubject($items[0], $items[2]);
        }

        return view('frontend.all_course.index', [
            'items' => $items,
            'course_type' => $type,
            'training_programs' => $training_programs,
            'level_subjects' => $level_subjects,
            'course_subject' => $course_subject,
            'profile_name' => $profile_name,
            'progressRoadmap' => $progressRoadmap,
            'count_course' => $items[3],
            'totalSubjectRoadmap' => $totalSubjectRoadmap,
            'countSubjectRoadmapCompleted' => $countSubjectRoadmapCompleted,
            'training_by_title_category' => $training_by_title_category,
            'start_date' => $start_date,
            'imageTrainingByTitle' => $imageTrainingByTitle,
            'count_subject_completed' => $count_subject_completed,
            'count_training_by_title_detail' => $count_training_by_title_detail,
            'progressTrainingByTitle' => $progressTrainingByTitle,
            'sub_titles' => $sub_titles,
            'roadmaps' => $roadmaps,
            'progressCareerRoadmap' => $progressCareerRoadmap,
            'listType' => $listType
        ]);
    }

    // TÌM KIẾM
    public function ajaxCourseTrainingProgram(Request $request)
    {
        $type = $request->course_type;
        $items = $this->getItems($type, $request);
        $last_page = $items[1];
        $data = '';
        if ($items) {
            if($request->listType == 'horizontal') {
                $data = $this->loadDataHorizontal($items[0]);
            } else {
                $data = $this->loadDataVertical($items[0]);
            }
        }
        return [$data, $last_page];
    }

    // DỮ LIỆU KHÓA HỌC LIÊN QUAN
    public function relatedSubject($items, $array_id)
    {
        $course_subject = [];
        $get_related_subject = [];
        foreach ($items->shuffle() as $key => $item) {
            $next = 0;
            if ($key > 5) {
                break;
            } else {
                $related_subject = RelatedSubject::where('subject_id', $item->subject_id)->first();
                $check_complete = CourseComplete::where('course_id', $item->course_id)->where('course_type', $item->course_type)->where('user_id', profile()->user_id)->first(['created_at']);
                if($item->course_type == 1) {
                    $check_result = OnlineResult::where('course_id', $item->course_id)->where('user_id', profile()->user_id)->first(['score']);
                } else {
                    $check_result = OfflineResult::where('course_id', $item->course_id)->where('user_id', profile()->user_id)->first(['score']);
                }
                $count_user_learn = UserViewCourse::where('course_id', $item->course_id)->where('course_type', $item->course_type)->where('user_id', profile()->user_id)->first(['count_user_view']);

                if(!empty($related_subject)) {
                    if(!empty($related_subject->compel)) {
                        $get_related_subject[] = $related_subject->compel;
                    } else {
                        if (!empty($related_subject->finish_5day) && !empty($check_complete)) {
                            $complete_coure = Carbon::parse($check_complete->created_at);
                            $start_coure = Carbon::parse($item->start_date);
                            $day_time = $complete_coure->diffInDays($start_coure);
                            if ($day_time < 5) {
                                $get_related_subject[] = $related_subject->finish_5day;
                                $next = 1;
                            }
                        }
                        if ((!empty($related_subject->score_5) || !empty($related_subject->score_8)) && !empty($check_result) && $next == 0 ) {
                            $score = $check_result->score;
                            if ($score <= 5) {
                                $get_related_subject[] = $related_subject->score_5;
                                $next = 1;
                            } else if ($score >= 8) {
                                $get_related_subject[] = $related_subject->score_8;
                                $next = 1;
                            }
                        }
                        if (!empty($related_subject->finish_soon_end) && !empty($check_complete) && $next == 0) {
                            $complete_coure = Carbon::parse($check_complete->created_at)->format('Y-m-d');
                            $end_coure = Carbon::parse($item->end_date)->format('Y-m-d');
                            if ($complete_coure < $end_coure) {
                                $get_related_subject[] = $related_subject->finish_soon_end;
                                $next = 1;
                            }
                        }
                        if (!empty($related_subject->number_lesson) && !empty($count_user_learn) && $count_user_learn->count_user_view > 8 && $next == 0 ) {
                            $get_related_subject[] = $related_subject->number_lesson;
                            $next = 1;
                        }
                        if (!empty($related_subject->new_subject) && empty($check_complete) && $next == 0 ) {
                            $get_related_subject[] = $related_subject->new_subject;
                        }
                    }
                }
            }
        }
        $query = CourseView::query();
        $query->select([
            'el_course_view.course_id',
            'el_course_view.code',
            'el_course_view.name',
            'el_course_view.course_type',
            'el_course_view.start_date',
            'el_course_view.end_date',
            'el_course_view.register_deadline',
            'el_course_view.image',
            'el_course_view.min_grades',
            'el_course_view.auto',
            'el_course_view.views',
            'el_course_view.subject_id',
        ]);
        $query->whereIn('subject_id', $get_related_subject);
        $query->whereNotIn('course_id', $array_id);
        $query->where('el_course_view.status', '=', 1);
        $query->where('el_course_view.isopen', '=', 1);
        $query->where('el_course_view.offline', '=', 0);
        $course_subject = $query->get()->take(4);
        // dd($course_subject);
        return $course_subject;
    }

    public function getItems($course_type = 0, Request $request) {
        $type = $course_type;
        $trainingProgramOnline = $request->get('trainingProgramId');
        $search = $request->get('search');
        $fromdate = $request->get('fromdate');
        $todate = $request->get('todate');
        $training_program_id = $request->get('training_program_id');
        $level_subject_id = $request->get('level_subject_id');
        $status = $request->get('search_status') ? $request->get('search_status') : [];
        $search_course_type = $request->get('search_course_type') ? $request->get('search_course_type') : [];
        $user_id = profile()->user_id;

        CourseView::addGlobalScope(new CompanyScope());
        $query = CourseView::query();
        $query->select([
            'el_course_view.course_id',
            'el_course_view.code',
            'el_course_view.name',
            'el_course_view.course_type',
            'el_course_view.start_date',
            'el_course_view.end_date',
            'el_course_view.register_deadline',
            'el_course_view.image',
            'el_course_view.min_grades',
            'el_course_view.auto',
            'el_course_view.views',
            'el_course_view.subject_id',
            'el_course_view.training_form_id',
            'el_course_view.survey_register',
            'el_course_view.entrance_quiz_id',
            'el_course_view.training_program_id',
            'el_course_view.register_quiz_id',
            'el_course_view.description',
        ])->disableCache();
        $query->where('el_course_view.offline', '=', 0);
        $query->where('el_course_view.status', '=', 1);
        $query->where('el_course_view.isopen', '=', 1);
        if (!in_array($type, [3,4])) {
            $query->where('el_course_view.course_type', $type);
            $query->whereNotExists(function (Builder $builder)  {
                $builder->select(['course_id'])
                    ->from('el_course_register_view AS register')
                    ->where(['register.status' => 1, 'user_id' => profile()->user_id])
                    ->whereColumn('register.course_type', '=', 'el_course_view.course_type')
                    ->whereColumn('register.course_id', '=', 'el_course_view.course_id');
            });
        }

        if($type == '1' && $trainingProgramOnline > 0) {
            $query->where('el_course_view.training_program_id', $trainingProgramOnline);
        }

        if((!empty($status) && !in_array(5, $status) && !in_array(1, $status)) || (in_array(3, $search_course_type) || in_array(4, $search_course_type)) || $type == 3) {
            $query->leftjoin('el_course_register_view as b',function($join){
                $join->on('el_course_view.course_id','=','b.course_id');
                $join->on('el_course_view.course_type','=','b.course_type');
            });

            if(in_array(2, $status) || in_array(3, $search_course_type) || $type == 3) {
                $query->where('b.user_id', $user_id);
                $query->where('b.status',1);
            }
            if(in_array(3, $status)) {
                $query->where('b.user_id', $user_id);
                $query->where('b.status', 2);
            }
            if(in_array(4, $search_course_type)) {
                $query->where('b.user_id', $user_id);
                $query->where('b.status',1);
                $query->whereNotExists(function (Builder $builder)  {
                    $builder->select(['id'])
                        ->from('el_course_complete AS completion')
                        ->whereColumn('completion.user_id', '=', 'b.user_id')
                        ->whereColumn('completion.course_type', '=', 'el_course_view.course_type')
                        ->whereColumn('completion.course_id', '=', 'el_course_view.course_id');
                });
            }
        }

        if(in_array(1, $status)) {
            $get_course_id_register = CourseRegisterView::where('user_id', $user_id)->where('course_type', $type)->pluck('course_id')->toArray();
            $query->whereNotIn('el_course_view.course_id', $get_course_id_register);
            $query->where(function ($sub){
                $sub->whereNull('el_course_view.end_date');
                $sub->orWhere('el_course_view.end_date', '>', date('Y-m-d'));
            });
        }
        if(in_array(4, $status)) {
            $query->leftJoin('el_course_complete as c', 'c.user_id', '=', 'b.user_id');
            $query->where(function ($sub){
                $sub->whereColumn('c.course_id', 'el_course_view.course_id');
                $sub->whereColumn('c.course_type', 'el_course_view.course_type');
            });
        }
        if(in_array(5, $status) ) {
            $query->where('el_course_view.end_date', '<=', date('Y-m-d'));
        }
        if (in_array(5, $search_course_type) || $type == "4") {
            $query->Join('el_course_bookmark as cb', function($join){
                $join->on('el_course_view.course_id','=','cb.course_id');
                $join->on('el_course_view.course_type','=','cb.type');
            });
            $query->where('cb.user_id', profile()->user_id);
        }
        if ($search) {
            $query->where(function ($subquery) use ($search) {
                $subquery->orWhere('el_course_view.code', 'like', '%'. $search .'%');
                $subquery->orWhere('el_course_view.name', 'like', '%'. $search .'%');
            });
        }
        if ($fromdate) {
            $query->where('el_course_view.start_date', '>=', date_convert($fromdate, '00:00:00'));
        }
        if ($todate) {
            $query->where(function ($sub) use ($todate){
                $sub->whereNull('el_course_view.end_date');
                $sub->orWhere('el_course_view.end_date', '<=', date_convert($todate, '23:59:59'));
            });
        }
        if ($training_program_id) {
            $query->whereIn('el_course_view.training_program_id', $training_program_id);
        }
        if ($level_subject_id){
            $query->whereIn('el_course_view.level_subject_id',$level_subject_id);
        }

        $query->orderByDesc('el_course_view.updated_at');
        $items = $query->paginate(12);
        $total = $query->paginate(12)->lastPage();
        $array_id = $query->pluck('course_id')->toArray();
        $count_course = $query->count();
        return [$items, $total, $array_id, $count_course];
    }

    public function loadDataVertical($items) {
        $data = '';
        foreach ($items as $item) {
            $training_form = TrainingForm::where('id', $item->training_form_id)->first(['name']);
            $type = $item->course_type;
            $get_promotion = PromotionCourseSetting::where('course_id', $item->course_id)->where('type', $type)->first();
            $get_bookmarked = CourseBookmark::where('course_id', $item->course_id)->where('type', $type)->where('user_id',profile()->user_id)->exists();

            $check_course_complete = CourseComplete::where('course_id',$item->course_id)->where('course_type',$item->course_type)->where('user_id', profile()->user_id)->exists();
            $status = $item->getStatusRegister($item->course_type);
            $text = status_register_text($status);

            $count_activity = 0;
            $count_activity_complete = 0;
            $load_modal_activity = route('frontend.ajax_modal_activity', [$item->course_id, $item->course_type]);
            $check_link_go_course = '';
            if ($type == 1) {
                $percent = OnlineCourse::percentCompleteCourseByUser($item->course_id, profile()->user_id);
                $img_course = image_online($item->image);
                $url_go_course = route('module.online.detail_first', ['id' => $item->course_id]);

                $rating_level_object = OnlineRatingLevelObject::query()
                    ->where('course_id', '=', $item->course_id)
                    ->where('object_type', '=', 1)
                    ->pluck('online_rating_level_id')->toArray();
                $count_activity += count($rating_level_object);

                $rating_level_course = RatingLevelCourse::query()
                    ->whereIn('course_rating_level_id', $rating_level_object)
                    ->where('user_id', '=', profile()->user_id)
                    ->where('user_type', '=', 1)
                    ->where('course_id', '=', $item->course_id)
                    ->where('course_type', '=', $item->course_type)
                    ->where('send', 1)
                    ->count();
                $count_activity_complete += $rating_level_course;

                if($item->action_plan == 1){
                    $count_activity += 1;

                    $plan_app = PlanApp::where('user_id','=', profile()->user_id)
                        ->where('course_id', '=', $item->course_id)
                        ->where('course_type', '=', $item->course_type)
                        ->where('status', 5)
                        ->first();
                    if($plan_app){
                        $count_activity_complete += 1;
                    }
                }
                //kiểm tra hoàn thành khóa học trước theo chức danh trong khoảng thời gian
                $check_setting_join_course = OnlineCourse::checkSettingJoinCourse($item->course_id, profile()->user_id);
            } else {
                $offline = OfflineCourse::find($item->course_id);
                $percent = 0;
                $img_course = image_offline($item->image);
                $check_link_go_course = $offline->link_go_course ? $offline->link_go_course : '';
                $url_go_course = route('module.offline.detail_first', ['id' => $item->course_id]);

                if($offline->quiz_id > 0){
                    $count_activity += 1;

                    $quiz_result = QuizResult::where('quiz_id', '=', $offline->quiz_id)
                        ->where('user_id', '=', profile()->user_id)
                        ->whereNull('text_quiz')
                        ->where('type', '=', 1)
                        ->where('result', 1);
                    if($quiz_result->exists()){
                        $count_activity_complete += 1;
                    }
                }
                if($offline->entrance_quiz_id > 0){
                    $count_activity += 1;

                    $quiz_result = QuizResult::where('quiz_id', '=', $offline->entrance_quiz_id)
                        ->where('user_id', '=', profile()->user_id)
                        ->whereNull('text_quiz')
                        ->where('type', '=', 1)
                        ->where('result', 1);
                    if($quiz_result->exists()){
                        $count_activity_complete += 1;
                    }
                }

                if($offline->template_rating_teacher_id > 0){
                    $count_activity += 1;

                    $organization_user = OfflineTeachingOrganizationUser::where('course_id', $item->course_id)->where('user_id', profile()->user_id);
                    if($organization_user->exists()){
                        $count_activity_complete += 1;
                    }
                }

                $rating_level_object = OfflineRatingLevelObject::query()
                    ->where('course_id', '=', $item->course_id)
                    ->where('object_type', '=', 1)
                    ->pluck('offline_rating_level_id')->toArray();
                $count_activity += count($rating_level_object);

                $rating_level_course = RatingLevelCourse::query()
                    ->whereIn('course_rating_level_id', $rating_level_object)
                    ->where('user_id', '=', profile()->user_id)
                    ->where('user_type', '=', 1)
                    ->where('course_id', '=', $item->course_id)
                    ->where('course_type', '=', $item->course_type)
                    ->where('send', 1)
                    ->count();
                $count_activity_complete += $rating_level_course;

                if($offline->action_plan == 1){
                    $count_activity += 1;

                    $plan_app = PlanApp::where('user_id','=', profile()->user_id)
                        ->where('course_id', '=', $item->course_id)
                        ->where('course_type', '=', $item->course_type)
                        ->where('status', 5)
                        ->first();
                    if($plan_app){
                        $count_activity_complete += 1;
                    }
                }
                //kiểm tra hoàn thành khóa học trước theo chức danh trong khoảng thời gian
                $check_setting_join_course = OfflineCourse::checkSettingJoinCourse($item->course_id, profile()->user_id);

                //lấy các lớp
                $load_modal_class = route('frontend.ajax_modal_class', ['course_id' => $item->course_id]);
            }
            if($item->end_date) {
                $end_time = ' - ' . get_date($item->end_date);
            } else {
                $end_time = '';
            }

            if($item->survey_register) {
                $check_survey = SurveyUser::where(['user_id' => profile()->user_id, 'course_id' => $item->course_id, 'course_type' => $item->course_type, 'survey_id' => $item->survey_register])->exists();
            } else {
                $item->survey_register = 0;
            }

            if($item->register_quiz_id) {
                $check_quiz = QuizResult::where(['user_id' => profile()->user_id, 'quiz_id' => $item->register_quiz_id])->whereNull('text_quiz')->exists();
            } else {
                $item->register_quiz_id = 0;
            }

            $brief = '';
            if($item->description) {
                $brief = '<div class="vdtodt">
                                <span class="vdt14 brief_course">
                                    <span class="font-weight-bold">'. trans('latraining.brief') .': </span>
                                    <span>'. $item->description .'</span>
                                </span>
                            </div>';
            }

            if ($get_bookmarked) {
                $html_bookmark = '<a onclick="bookmarkHandle('. $item->course_id .', '. $type .')" class="item-bookmark item_bookmark_'. $item->course_id .'_'. $type .'">
                                    '. trans('app.unbookmark') .'
                                </a>';
            } else {
                $html_bookmark = '<a onclick="bookmarkHandle('. $item->course_id .', '. $type .')" class="item-bookmark item_bookmark_'. $item->course_id .'_'. $type .'">
                                    '.trans('app.bookmark').'
                                </a>';
            }

            $html_promotion = '';
            $html_point_promotion = '';
            if (!empty($get_promotion)) {
                if ($get_promotion->method == 1) {
                    $point = $get_promotion->point;
                } else {
                    $setting = $get_promotion->methodSetting->sortByDesc('point');
                    $point = $setting->count() > 0 ? $setting->first()->point : 0;
                }
                $html_point_promotion = '<div class="badge_seller">
                                            '. $point .'
                                            <img class="point ml-1" width="20px" height="20px" src="'. asset('styles/images/level/point.png') .'" alt="">
                                        </div>';
                $html_promotion = '<span onclick="openModalBonus('.$item->course_id.','.$type.')">
                                        <img class="image_bonus_courses" src="'.asset("images/level/point.png").'" alt="" width="29px" height="15px"> Điểm thưởng
                                    </span>';
            }

            $html_count_activity = '';
            if ($count_activity != 0 && $status == 4 && $check_setting_join_course[0]) {
                $html_count_activity = '<p class="load-modal mb-0 count_setting_join" data-url="'.$load_modal_activity.'" style="cursor: pointer;">
                                            ('.$count_activity_complete.'/'.$count_activity.')
                                        </p>';
            }

            $html_canvas = '';
            if ($status == 4 && $type == 1) {
                $html_canvas = '<canvas id="chartProgress_'.$item->course_id.'_'.$type.'" width="80px" height="80px"></canvas>';
            }

            if($status == 1 && !$check_course_complete) {
                if (($item->survey_register && !$check_survey) || ($item->register_quiz_id && !$check_quiz)) {
                    $html_status = '<div class="mt-2 item item-btn">
                                        <a class="btn btn_adcart btn_register" onclick="conditionRegister('.$item->course_id.','.$type.','. $item->survey_register .','. $item->register_quiz_id .','. $item->training_program_id .')">'.$text.'</a>
                                    </div>';
                } else {
                    $html_status = '<div class="mt-2 item item-btn">
                                        <a id="btn_register_'.$item->course_id.'_'.$type.'" class="btn btn_adcart btn_register" onclick="submitRegister('.$item->course_id.','.$type.')">'.$text.'</a>
                                    </div>';
                }
            } elseif($status == 4 && !$check_course_complete) {
                $html_check_setting = '';
                if(!$check_setting_join_course[0]) {
                    $html_check_setting = '<input class="noty_setting_join_'. $item->course_id .'_'. $type .'" type="hidden" value="'. $check_setting_join_course[1] .'">
                                            <img class="cursor_pointer" src="'. asset('images/lock.png') .'" alt="" width="32px" onclick="notySettingJoin('.$item->course_id.','.$type.')">';
                }

                $html_status = '<div class="mt-2">
                                    '. $html_check_setting .'
                                    <a href="'.($check_link_go_course ? $check_link_go_course : $url_go_course).'" class="btn btn_adcart btn_gocourse" '.($check_link_go_course ? 'target="_blank"' : '').' >'.trans('latraining.go_course').'</a>
                                </div>';
            } elseif ($check_course_complete){
                $html_status = '<div class="mt-2">
                                    <a href="'.$url_go_course.'" class="btn btn_adcart btn_endcourse">'.trans('latraining.completed').'</a>
                                </div>';
            } else if ($status == 3) {
                $html_status = '<div class="mt-2">
                                    <a onclick="endCourse('.$item->course_id.','.$type.','.$status.')" type="button" class="btn btn_adcart btn_endcourse">'.$text.'</a>
                                </div>';
            } else {
                $html_status = '<div class="mt-2">
                                    <a type="button" class="btn btn_adcart btn_endcourse">'.$text.'</a>
                                </div>';
            }

            $data .= '<div class="col-lg-3 col-big p-1 list_course">
                        <div class="fcrse_1 mb-20">
                            <a class="img_promotion">
                                <div class="img_course text-center">
                                    <img class="picture_course" src="'. $img_course .'" alt="" width="100%">
                                </div>
                                <div class="course-overlay">
                                    '. $html_point_promotion .'
                                    <div class="crse_reviews">
                                        <i class="uil uil-star"></i>'.$item->avgRatingStar($type) .'
                                    </div>
                                </div>
                            </a>
                            <div class="fcrse_content">
                                <div class="eps_dots more_dropdown check_course">
                                    <a href="javascript:void(0)"><i class="uil uil-ellipsis-v"></i></a>
                                    <div class="dropdown-content">
                                        <input type="hidden" class="bookmark_'. $item->course_id .'_'. $type .'" value="'. ($get_bookmarked ? 1 : 0) .'">
                                        <span>
                                            <i class="uil uil-heart-alt"></i>
                                            '. $html_bookmark .'
                                        </span>
                                        '. $html_promotion .'
                                        <span href="javascript:void(0)" style="cursor: pointer" class="ml-1" onclick="shareCourse('.$item->course_id.','.$type.')">
                                            <i class="fas fa-link mr-2"></i> Share
                                        </span>
                                    </div>
                                </div>
                                <div class="wrraped_detail_info_course">
                                    <div class="course_names">
                                        <a href="'. $url_go_course .'" class="crse14s course_name">'. $item->name .'</a>
                                        <span class="hidden_name">'. $item->name .'</span>
                                    </div>
                                    <div class="vdtodt">
                                        <span class="vdt14"><i class="far fa-calendar-alt"></i> '.get_date($item->start_date).' '. $end_time .'</span>
                                    </div>
                                    <div class="detail_info_course">
                                        <div class="vdtodt description_course">
                                            <span onclick="openModalSummary('.$item->course_id.','.$type.')" class="vdt14" style="cursor: pointer"><b>'.trans('latraining.description').':</b> '.trans('latraining.brief').'</span> |
                                            <span onclick="openModalDescription('.$item->course_id.','.$type.')">'.trans('latraining.detail').'</span>
                                        </div>
                                        <div class="vdtodt register_deadline">
                                            <span class="vdt14"><b>'.trans("app.register_deadline").':</b> '.get_date($item->register_deadline).'</span>
                                        </div>
                                        <div class="vdtodt passing_score">
                                            <span class="vdt14"><b>'.trans('latraining.pass_score').':</b> '.$item->min_grades.'</span>
                                        </div>
                                        <div class="vdtodt course_type_item">
                                            <span class="vdt14"><b>'.trans('lacategory.form').':</b> '. $training_form->name .'</span>
                                        </div>
                                        <div class="vdtodt course_object" onclick="openModalObject('.$item->course_id.','.$type.')" style="cursor: pointer">
                                            <span class="vdt14 import-plan"><b>'.trans('latraining.object').':</b> <i title="'.$item->getStatus($item->course_type).'">'.trans('latraining.detail').'</i></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="auth1lnkprce">
                                    <div class="row m-0">
                                        <div class="col-4 chart">
                                            <div class="chartProgress">
                                                <input type="hidden" name="text" class="canvas_percent" value="'.$item->course_id.','. $type .','.$percent.','.$status.'">
                                                '. $html_canvas .'
                                            </div>
                                        </div>
                                        <div class="prce142 col-8 button_course px-1">
                                            '. $html_status .'
                                        </div>
                                    </div>
                                    <div class="mt-2 name_type row">
                                        <div class="col-7">
                                            '. ($type == 1 ? trans('latraining.online') : trans('latraining.offline')) .'
                                        </div>
                                        <div class="col-5 text-right">
                                            '. $html_count_activity .'
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="info_all_cousre_hiden">
                                <div class="course_names">
                                    <a href="'. $url_go_course .'" class="crse14s course_name">'.$item->name.'</a>
                                </div>
                                <div class="vdtodt">
                                    <span class="vdt14"><span class="font-weight-bold">'.trans('latraining.course_code').':</span> '.$item->code.'</span>
                                </div>
                                <div class="vdtodt">
                                    <span class="vdt14">
                                    <span class="font-weight-bold">'. trans('ladashboard.time') .':</span> '.get_date($item->start_date).' '. $end_time .'
                                    </span>
                                </div>
                                <div class="vdtodt register_deadline">
                                    <span class="vdt14"><span class="font-weight-bold">'.trans("app.register_deadline").':</span> '.get_date($item->register_deadline).'</span>
                                </div>
                                <div class="vdtodt passing_score">
                                    <span class="vdt14"><span class="font-weight-bold">'.trans('latraining.pass_score').':</span> '.$item->min_grades.'</span>
                                </div>
                                <div class="vdtodt course_type_item">
                                    <span class="vdt14"><span class="font-weight-bold">'.trans('lacategory.form').':</span> '. $training_form->name .'</span>
                                </div>
                                '. $brief .'
                            </div>
                        </div>
                    </div>';

        }
        return $data;
    }

    public function loadDataHorizontal($items)
    {
        $data = '';
        foreach ($items as $item) {
            $training_form = TrainingForm::where('id', $item->training_form_id)->first(['name']);
            $type = $item->course_type;
            $check_course_complete = CourseComplete::where('course_id',$item->course_id)->where('course_type',$item->course_type)->where('user_id', profile()->user_id)->exists();
            $status = $item->getStatusRegister($item->course_type);
            $text = status_register_text($status);
            $get_bookmarked = CourseBookmark::where('course_id', $item->course_id)->where('type', $type)->where('user_id',profile()->user_id)->exists();
            $check_link_go_course = '';
            if ($type == 1) {
                $name_type = 'Online';
                $percent = OnlineCourse::percentCompleteCourseByUser($item->course_id, profile()->user_id);
                $img_course = image_online($item->image);
                $url_go_course = route('module.online.detail_first', ['id' => $item->course_id]);
            } else {
                $name_type = 'Offline';
                $offline = OfflineCourse::find($item->course_id, ['link_go_course']);
                $checkResult = OfflineResult::where(['course_id' => $item->course_id, 'user_id' => profile()->user_id])->first(['result']);
                if(isset($checkResult) && $checkResult->result == 1) {
                    $percent = 100;
                } else {
                    $percent = OfflineCourse::percent($item->course_id, profile()->user_id);
                }
                $img_course = image_offline($item->image);
                $check_link_go_course = $offline->link_go_course ? $offline->link_go_course : '';
                $url_go_course = route('module.offline.detail_first', ['id' => $item->course_id]);
                //lấy các lớp
                $load_modal_class = route('frontend.ajax_modal_class', ['course_id' => $item->course_id]);
            }
            if($item->end_date) {
                $end_time = get_date($item->end_date);
            } else {
                $end_time = '_';
            }

            if($status == 1 && !$check_course_complete) {
                $btnStatus = '<div class="item item-btn">
                                    <a id="btn_register_'.$item->course_id.'_'.$type.'" class="btn btn_adcart btn_register" onclick="submitRegister('.$item->course_id.','.$type.')">'.$text.'</a>
                                </div>';
            } elseif($status == 4 && !$check_course_complete) {
                $btnStatus = '<div class="">
                                <a href="'.($check_link_go_course ? $check_link_go_course : $url_go_course).'" class="btn btn_adcart btn_gocourse" '.($check_link_go_course ? 'target="_blank"' : '').' >'.trans('latraining.go_course').'</a>
                            </div>';
            } elseif ($check_course_complete){
                $btnStatus = '<div class="">
                                <a href="'.$url_go_course.'" class="btn btn_adcart btn_endcourse">'.trans('latraining.completed').'</a>
                            </div>';
            } else {
                $btnStatus = '<div class="">
                                <a onclick="endCourse('.$item->course_id.','.$type.','.$status.')" type="button" class="btn btn_adcart btn_endcourse">'.$text.'</a>
                            </div>';
            }

            $brief = '';
            if($item->description) {
                $brief = '<div class="vdtodt ml-2 mb-1 brief_course">
                            <span class="vdt14"><b>'.trans('latraining.description').':</b> '. $item->description .'</span>
                          </div>';
            }

            if($get_bookmarked) {
                $icon_bookmark = '<i class="fas fa-heart check-heart" title="Bỏ đánh dấu"></i>';
            } else {
                $icon_bookmark = '<i class="far fa-heart" title="Đánh dấu"></i>';
            }

            $data .= '<div class="col-12 fcrse_1 mb-2 mt-1 data_horizontal">
                        <div class="row wrapped_data">
                            <div class="col-md-auto col-12 p-1 text-center">
                                <a href="'. $url_go_course .'" class="img_promotion">
                                    <img class="picture_course" src="'. $img_course .'" alt="" width="100%">
                                </a>
                            </div>
                            <div class="col-md-8 col-12">
                                <div class="text-right wrapped_setting mt-2">
                                    <input type="hidden" class="bookmark_'. $item->course_id .'_'. $type .'" value="'. ($get_bookmarked ? 1 : 0) .'">
                                    <span>'. $item->views .'<i class="uil uil-eye"></i></span>
                                    <span class="cursor_pointer check_bookmark_'. $item->course_id .'_'. $type .'" onclick="bookmarkHandle('. $item->course_id .', '. $type .')">
                                        '. $icon_bookmark .'
                                    </span>
                                    <span class="ml-2 cursor_pointer" onclick="shareCourse('.$item->course_id.','.$type.')">
                                        <img class="mb-1" src="'. asset('images/pngwing.png') .'" alt="" width="18px" height="18px" title="Sao chép">
                                    </span>
                                </div>
                                <div class="course_names">
                                    <h4 class="mt-1 ml-2">
                                        <a href="'.$url_go_course .'" class="course_name">'.$item->name.'</a>
                                    </h4>
                                </div>
                               '. $brief .'
                                <div class="wrappred_percent row ml-1 mr-0">
                                    <div class="col-md-12 col-12 pl-1 pr-0">
                                        <div class="mb-1 progress progress2 bg-white" style="border-radius: 10px;">
                                            <div class="progress-bar" role="progressbar" style="width: '. $percent .'%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100">
                                                <span>'. round($percent, 2) .'%</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="all_info row m-0">
                                    <div class="col-md-4 col-12 info text-left pl-2 pr-1">
                                        <p class="mb-0">'. trans('latraining.start_date') .': <strong>'.get_date($item->start_date) .'</strong></p>
                                    </div>
                                    <div class="col-md-4 col-12 text-left pl-2 pr-1">
                                        <p class="mb-0">'. trans('latraining.end_date') .': <strong>'. $end_time .'</strong></p>
                                    </div>
                                    <div class="col-md-4 col-12 text-left pl-2 pr-1">
                                        <p class="mb-0">'. trans('app.register_deadline') .': <strong>'. ($item->register_deadline ? get_date($item->register_deadline) : '-') .'</strong></p>
                                    </div>
                                    <div class="col-md-4 col-12 text-left pl-2 pr-1">
                                        <p class="mb-0 training_form" data-toggle="tooltip" data-placement="top" title="'. $training_form->name .'">'. trans('lacategory.form') .': <strong>'. $training_form->name .'</strong></p>
                                    </div>
                                    <div class="col-md-4 col-12 text-left pl-2 pr-1">
                                        <p class="mb-0">'. trans('lamenu.course') .': <strong>'. $name_type .'</strong></p>
                                    </div>
                                    <div class="col-md-4 col-12 p-1 info">
                                        '. $btnStatus .'
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>';
        }
        return $data;
    }

    public function ajaxConentCourse(Request $request)
    {
        $course = CourseView::select('content')->where('course_id',$request->id)->where('course_type',$request->type)->first();
        $content_course = html_entity_decode($course->content);
        json_result($content_course);
    }

    public function ajaxSummaryCourse(Request $request)
    {
        $summary_course = CourseView::select('description')->where('course_id',$request->id)->where('course_type',$request->type)->first();
        json_result($summary_course);
    }

    public function ajaxObjectCourse(Request $request)
    {
        $item = CourseView::select('title_join_id','title_recommend_id')->where('course_id',$request->id)->where('course_type',$request->type)->first();
        $titles_join = [];
        $titles_recomment = [];
        $get_titles_join = json_decode($item->title_join_id);
        $get_titles_recomment = json_decode($item->title_recommend_id);
        if(!empty($get_titles_join) && !in_array(0,$get_titles_join)) {
            foreach ($get_titles_join as $key => $get_title_join) {
                $get_title = Titles::find($get_title_join);
                $titles_join[] = $get_title->name;
            }
        } elseif (!empty($get_titles_join) && in_array(0,$get_titles_join)) {
            $titles_join[] = 'Tất cả chức danh';
        }

        if(!empty($get_titles_recomment) && !in_array(0,$get_titles_recomment)) {
            foreach ($get_titles_recomment as $key => $get_title_recomment) {
                $title_recomment = Titles::find($get_title_recomment);
                $titles_recomment[] = $title_recomment->name;
            }
        } elseif (!empty($get_titles_recomment) && in_array(0,$get_titles_recomment)) {
            $titles_recomment[] = 'Tất cả chức danh';
        }
        json_result([
            'titles_join' => $titles_join,
            'titles_recomment' => $titles_recomment,
        ]);
    }

    public function ajaxBonusCourse(Request $request)
    {
        $type = $request->type;
        $course_id = $request->id;
        $arr_code = [
            'assessment_after_course' => 'Đánh giá sau khóa học',
            'evaluate_training_effectiveness' => 'Đánh giá hiệu quả đào tạo',
            'rating_star' => 'Đánh giá sao',
            'share_course' => 'Share khóa học'
        ];
        $complete = PromotionCourseSetting::getPromotionCourseSetting($course_id, $request->type, 'complete');
        $landmarks = PromotionCourseSetting::getPromotionCourseSetting($course_id, $request->type, 'landmarks');
        $rating_star = PromotionCourseSetting::getPromotionCourseSetting($course_id, $request->type, 'rating_star');
        $html = '';
        $rhtml = '';
        if ($complete) {
            $html .= '<div class="form-check form-check-inline">
                        <div class="custom-control custom-radio promotion_0_radio">
                            <input type="radio" class="custom-control-input point-type" id="promotion_0_'.$course_id.'_'.$type.'" onclick="checkBoxBonus('.$course_id.','.$type.')" name="method" value="0">
                            <label class="custom-control-label" for="promotion_0_'.$course_id.'_'.$type.'">'. trans('backend.complete_course') .'</label>
                        </div>
                    </div>';
            $rhtml .= '<div class="promotion_0_group_'.$course_id.'_'.$type.'">
                        <div class="form-group row">
                            <div class="col-sm-3 control-label"></div>
                            <div class="col-md-9">
                                <input name="start_date" readonly type="text" class="form-control w-25 d-inline-block datepicker" placeholder="Bắt đầu" autocomplete="off" value="'. ($complete && $complete->start_date ? get_date($complete->start_date) : '') .'">
                                <input name="end_date" readonly type="text" class="form-control w-25 d-inline-block datepicker" placeholder="Kết thúc" autocomplete="off" value="'.($complete && $complete->end_date ? get_date($complete->end_date) : '').'">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3 control-label"></div>
                            <div class="col-md-4">
                                <input name="point_complete" readonly type="text" class="form-control" placeholder="'.trans('backend.bonus_points').'" autocomplete="off" value="'. ($complete ? $complete->point : '') .'">
                            </div>
                        </div>
                    </div>';
        }
        if ($landmarks) {
            $html .= '<div class="form-check form-check-inline promotion_1_radio">
                        <div class="custom-control custom-radio">
                            <input type="radio" class="custom-control-input point-type" id="promotion_1_'.$course_id.'_'.$type.'" onclick="checkBoxBonus('.$course_id.','.$type.')" name="method" value="1">
                            <label class="custom-control-label" for="promotion_1_'.$course_id.'_'.$type.'">'.trans('backend.landmarks').'</label>
                        </div>
                    </div>';
            $rhtml .= '<div class="promotion_1_group_'.$course_id.'_'.$type.'">
                        <div class="row promotion-table">
                            <div class="col-md-12">
                                <table class="tDefault table table-hover bootstrap-table text-nowrap" id="table_setting_'.$course_id.'_'. $type .'">
                                    <thead>
                                        <tr>
                                            <th data-align="center" data-width="3%" data-formatter="stt_formatter_bonus">STT</th>
                                            <th data-field="score" data-align="center">'.trans('backend.landmarks') .'</th>
                                            <th data-field="point" data-align="center">'.trans('backend.bonus_points') .'</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>';
        }
        if ($rating_star) {
            $html .= '<div class="form-check form-check-inline promotion_2_radio">
                        <div class="custom-control custom-radio">
                            <input type="radio" class="custom-control-input point-type" id="promotion_2_'.$course_id.'_'.$type.'" onclick="checkBoxBonus('.$course_id.','.$type.')" name="method" value="2">
                            <label class="custom-control-label" for="promotion_2_'.$course_id.'_'.$type.'">'.trans('backend.other') .'</label>
                        </div>
                    </div>';
            $rhtml .= '';
        }
        $rhtml .= '<div class="promotion_2_group_'.$course_id.'_'.$type.'">';
        foreach($arr_code as $key => $code) {
            $other = PromotionCourseSetting::getPromotionCourseSetting($course_id, $type, $key);
            if ($other){
                $rhtml .= '<div class="form-group row">
                            <div class="col-sm-3 control-label"></div>
                            <div class="col-md-4">
                                '.$code.'
                            </div>
                            <div class="col-md-4">
                                <input name="point[]" readonly type="text" class="form-control" placeholder="'.trans('backend.bonus_points') .'" autocomplete="off" value="'. ($other ? $other->point : '') .'">
                            </div>
                        </div>';
            }
        }
        $rhtml .= '</div>';
        json_result([
            'complete' => $complete,
            'landmarks' => $landmarks,
            'other' => $other,
            'html' => $html,
            'rhtml' => $rhtml,
        ]);
    }

    public function ajaxModalActivity($course_id, $course_type, Request $request){
        $user_id = getUserId();
        $user_type = getUserType();

        $entrance_quiz = 0;
        $quiz = 0;
        $go_entrance_quiz_url = '';
        $go_quiz_url = '';
        $closed_entrance_quiz = 0;
        $closed_quiz = 0;
        $status_quiz_result = 0;
        $status_entrance_quiz_result = 0;
        $url_plan_app = '';
        $status_plan_app = 0;
        $offline_rating_level_object = '';
        $online_rating_level_object = '';
        $action_plan = 0;
        $text_status_plan_app = '';

        $url_organization_user = '';
        $text_organization_user = '';
        $status_organization_user = 0;
        $offlineTeachingOrganizationTemplate = '';

        if ($course_type == 2) {
            $offline = OfflineCourse::find($course_id);

            if($offline->entrance_quiz_id){
                $entrance_quiz = 1;

                $entrance_quiz_register = QuizRegister::whereQuizId($offline->entrance_quiz_id)->where('user_id', '=', $user_id)->where('type', '=', $user_type)->first();
                $entrance_quiz_part = QuizPart::whereQuizId($offline->entrance_quiz_id)->where('id', $entrance_quiz_register->part_id)->first();
                if (!empty($entrance_quiz_part) && $entrance_quiz_part->end_date && $entrance_quiz_part->end_date < date('Y-m-d H:i:s')){
                    $closed_entrance_quiz = 1;
                }

                if (!empty($entrance_quiz_part) && $entrance_quiz_part->start_date <= date('Y-m-d H:i:s') && $closed_entrance_quiz == 0){
                    $go_entrance_quiz_url = route('module.quiz.doquiz.index', [
                        'quiz_id' => $offline->entrance_quiz_id,
                        'part_id' => $entrance_quiz_part->id,
                    ]);
                }
                $entrance_quiz_result = QuizResult::where('quiz_id', '=', $offline->entrance_quiz_id)
                    ->where('user_id', '=', profile()->user_id)
                    ->whereNull('text_quiz')
                    ->where('type', '=', 1)
                    ->where('result', 1);
                if($entrance_quiz_result->exists()){
                    $status_entrance_quiz_result = 1;
                }
            }

            if($offline->quiz_id){
                $quiz = 1;

                $quiz_register = QuizRegister::whereQuizId($offline->quiz_id)->where('user_id', '=', $user_id)->where('type', '=', $user_type)->first();
                $quiz_part = QuizPart::whereQuizId($offline->quiz_id)->where('id', $quiz_register->part_id)->first();
                if (!empty($quiz_part) && $quiz_part->end_date && $quiz_part->end_date < date('Y-m-d H:i:s')){
                    $closed_quiz = 1;
                }

                if (!empty($quiz_part) && $quiz_part->start_date <= date('Y-m-d H:i:s') && $closed_quiz == 0){
                    $go_quiz_url = route('module.quiz.doquiz.index', [
                        'quiz_id' => $offline->quiz_id,
                        'part_id' => $quiz_part->id,
                    ]);
                }
                $quiz_result = QuizResult::where('quiz_id', '=', $offline->quiz_id)
                    ->where('user_id', '=', profile()->user_id)
                    ->whereNull('text_quiz')
                    ->where('type', '=', 1)
                    ->where('result', 1);
                if($quiz_result->exists()){
                    $status_quiz_result = 1;
                }
            }

            if($offline->action_plan == 1){
                $action_plan = 1;

                $offline_result = OfflineResult::whereCourseId($course_id)->where('user_id', profile()->user_id)->where('result', 1)->first();
                $plan_app = PlanApp::where('user_id','=', profile()->user_id)->where('course_id', '=', $course_id)->where('course_type', '=', $course_type)->first();

                if($offline_result){
                    $time_plan_app = strtotime(date("Y-m-d", strtotime($offline_result->created_at)) . " +{$offline->plan_app_day} day");
                    $time_plan_app_student = strtotime(date("Y-m-d", strtotime($offline_result->created_at)) . " +{$offline->plan_app_day_student} day");

                    if(date('Y-m-d', $time_plan_app) <= date('Y-m-d')){
                        $url_plan_app = route('frontend.plan_app.form', [
                            'course' => $course_id,
                            'type' => $course_type
                        ]);
                    }
                    if(date('Y-m-d', $time_plan_app_student) <= date('Y-m-d') && $plan_app->status != 3 && $plan_app->status >= 2){
                        $url_plan_app = route('frontend.plan_app.form.evaluation', [
                            'course' => $course_id,
                            'type' => $course_type
                        ]);
                    }
                }
                if($plan_app){
                    $status_plan_app = $plan_app->status;
                    $text_status_plan_app = PlanAppStatus::getStatus($plan_app->status);
                }
            }
            $offline_rating_level_object = OfflineRatingLevelObject::query()
                ->where('course_id', '=', $course_id)
                ->where('object_type', '=', 1)
                ->get();

            if($offline->template_rating_teacher_id > 0){
                $offlineTeachingOrganizationTemplate = OfflineTeachingOrganizationTemplate::where('course_id', $course_id)->first();
                $url_organization_user = route('module.offline.rating_teaching_organization', [$course_id]);
                $text_organization_user = 'Vào làm';

                $organization_user = OfflineTeachingOrganizationUser::where('course_id', $course_id)->where('user_id', profile()->user_id);
                if($organization_user->exists()){
                    $url_organization_user = route('module.offline.edit_rating_teaching_organization', [$course_id]);
                    $text_organization_user = 'Xem lại';
                    $status_organization_user = 1;
                }
            }
        }else{
            $online = OnlineCourse::find($course_id);
            if($online->action_plan == 1){
                $action_plan = 1;

                $online_result = OnlineResult::whereCourseId($course_id)->where('user_id', profile()->user_id)->where('result', 1)->first();
                $plan_app = PlanApp::where('user_id','=', profile()->user_id)->where('course_id', '=', $course_id)->where('course_type', '=', $course_type)->first();

                if($online_result){
                    $time_plan_app = strtotime(date("Y-m-d", strtotime($online_result->created_at)) . " +{$online->plan_app_day} day");
                    $time_plan_app_student = strtotime(date("Y-m-d", strtotime($online_result->created_at)) . " +{$online->plan_app_day_student} day");

                    if(date('Y-m-d', $time_plan_app) <= date('Y-m-d')){
                        $url_plan_app = route('frontend.plan_app.form', [
                            'course' => $course_id,
                            'type' => $course_type
                        ]);
                    }

                    //Tới thời gian đánh giá và đã duyệt kế hoạch, không bị từ chối
                    if(date('Y-m-d', $time_plan_app_student) <= date('Y-m-d') && $plan_app->status != 3 && $plan_app->status >= 2){
                        $url_plan_app = route('frontend.plan_app.form.evaluation', [
                            'course' => $course_id,
                            'type' => $course_type
                        ]);
                    }
                }
                if($plan_app){
                    $status_plan_app = $plan_app->status;
                    $text_status_plan_app = PlanAppStatus::getStatus($plan_app->status);
                }
            }
            $online_rating_level_object = OnlineRatingLevelObject::query()
                ->where('course_id', '=', $course_id)
                ->where('object_type', '=', 1)
                ->get();
        }

        return view('frontend.all_course.modal_activity', [
            'entrance_quiz' => $entrance_quiz,
            'go_entrance_quiz_url' => $go_entrance_quiz_url,
            'status_entrance_quiz_result' => $status_entrance_quiz_result,
            'closed_entrance_quiz' => $closed_entrance_quiz,

            'quiz' => $quiz,
            'go_quiz_url' => $go_quiz_url,
            'status_quiz_result' => $status_quiz_result,
            'closed_quiz' => $closed_quiz,

            'status_plan_app' => $status_plan_app,
            'url_plan_app' => $url_plan_app,
            'action_plan' => $action_plan,
            'text_status_plan_app' => $text_status_plan_app,

            'offline_rating_level_object' => $offline_rating_level_object,
            'online_rating_level_object' => $online_rating_level_object,

            'url_organization_user' => $url_organization_user,
            'text_organization_user' => $text_organization_user,
            'status_organization_user' => $status_organization_user,
            'offlineTeachingOrganizationTemplate' => $offlineTeachingOrganizationTemplate,
        ]);
    }

    public function ajaxModalClass($course_id, Request $request){
        $classes = OfflineCourseClass::whereCourseId($course_id)->get();

        return view('frontend.all_course.modal_class',[
            'classes' => $classes,
            'course_id' => $course_id,
        ]);
    }

    // ĐIỀU KIỆN GHI DANH
    public function ajaxModalConditionRegister(Request $request) {
        if($request->type == 1) {
            $course = OnlineCourse::find($request->id, ['survey_register', 'register_quiz_id']);
        } else {
            $course = OfflineCourse::find($request->id, ['survey_register', 'register_quiz_id']);
        }
        $check_survey = '';
        if($course->survey_register) {
            $check_survey = SurveyUser::where(['user_id' => profile()->user_id, 'course_id' => $request->id, 'course_type' => $request->type, 'survey_id' => $course->survey_register])->exists();
        }

        $check_quiz = '';
        $link_quiz = '';
        if($course->register_quiz_id) {
            $check_quiz = QuizResult::where(['user_id' => profile()->user_id, 'quiz_id' => $course->register_quiz_id])->whereNull('text_quiz')->exists();
        }

        json_result([
            'check_survey' => $check_survey,
            'check_quiz' => $check_quiz,
            'link_quiz' => $link_quiz
        ]);
    }

    // GHI DANH KỲ THI TRƯỚC GHI DANH
    public function ajaxRegisterQuiz(Request $request) {
        $user_id = profile()->user_id;
        $user_type = Quiz::getUserType();

        $quiz = Quiz::find($request->id);
        $part = QuizPart::where('quiz_id', $quiz->id)->first();

        $save = QuizRegister::firstOrNew(['quiz_id' => $quiz->id, 'part_id' => $part->id, 'user_id' => $user_id]);
        $save->quiz_id = $quiz->id;
        $save->user_id = $user_id;
        $save->type = $user_type;
        $save->part_id = $part->id;
        $save->created_by = 2;
        $save->updated_by = 2;
        $save->unit_by = 1;
        $save->save();

        $link_quiz = route('module.quiz.doquiz.index', ['quiz_id' => $quiz->id, 'part_id' => $part->id]);

        json_result([
            'link_quiz' => $link_quiz
        ]);
    }
}
