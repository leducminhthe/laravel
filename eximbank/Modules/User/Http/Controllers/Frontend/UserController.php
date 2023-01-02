<?php

namespace Modules\User\Http\Controllers\Frontend;

use App\Models\Config;
use App\Models\CourseRegisterView;
use App\Models\CourseView;
use App\Models\Categories\LevelSubject;
use App\Models\PermissionTypeUnit;
use App\Models\PlanAppStatus;
use App\Models\Profile;
use App\Models\Categories\Subject;
use App\Models\Categories\Titles;
use App\Models\Categories\Unit;
use App\Models\Slider;
use App\Models\UnitView;
use App\Models\User;
use App\Models\UserMeta;
use App\Models\UserPermissionType;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Intervention\Image\ImageManagerStatic;
use Modules\AppNotification\Helpers\AppNotification;
use Modules\Capabilities\Entities\CapabilitiesResult;
use Modules\CareerRoadmap\Entities\CareerRoadmap;
use Modules\ConvertTitles\Entities\ConvertTitles;
use Modules\ConvertTitles\Entities\ConvertTitlesRoadmap;
use Modules\Messages\Entities\Message;
use Modules\Notify\Entities\Notify;
use Modules\Offline\Entities\OfflineCourse;
use Modules\Offline\Entities\OfflineCourseComplete;
use Modules\Offline\Entities\OfflineRegister;
use Modules\Online\Entities\OnlineCourse;
use Modules\Online\Entities\OnlineCourseActivity;
use Modules\Online\Entities\OnlineCourseActivityScorm;
use Modules\Online\Entities\OnlineCourseComplete;
use Modules\Online\Entities\OnlineRegister;
use Modules\Potential\Entities\Potential;
use Modules\Potential\Entities\PotentialRoadmap;
use Modules\Promotion\Entities\PromotionOrders;
use Modules\Promotion\Entities\PromotionUserHistory;
use Modules\Promotion\Entities\PromotionUserPoint;
use Modules\Quiz\Entities\Quiz;
use Modules\Quiz\Entities\QuizRegister;
use Modules\RefererHist\Entities\RefererHist;
use Modules\SubjectRegister\Entities\SubjectRegister;
use Modules\TrainingByTitle\Entities\TrainingByTitleCategory;
use Modules\TrainingByTitle\Entities\TrainingByTitleDetail;
use Modules\TrainingRoadmap\Entities\TrainingRoadmap;
use Modules\User\Entities\HistoryChangeInfo;
use Modules\User\Entities\TrainingProcess;
use Modules\User\Entities\UserCompletedSubject;
use Modules\CareerRoadmap\Entities\CareerRoadmapUser;
use App\Models\Categories\TrainingForm;
use Modules\Offline\Entities\OfflineRegisterView;
use Illuminate\Database\Query\Builder;
use App\Models\Categories\Absent;
use App\Models\Categories\AbsentReason;
use App\Models\Categories\Discipline;
use App\Models\ProfileView;
use App\Models\MyCertificate;
use Illuminate\Support\Facades\DB;
use Modules\Offline\Entities\OfflineSchedule;
use Modules\Promotion\Entities\PromotionLevel;
use App\Models\Categories\StudentCost;
use Jenssegers\Agent\Agent;
use Modules\Certificate\Entities\CertificateSetting;
use Modules\PlanSuggest\Entities\PlanSuggest;
use Modules\User\Entities\ProfileChangedPass;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Modules\TrainingByTitle\Entities\TrainingByTitleUploadImage;
use Modules\Certificate\Entities\CertificateDesign;
use App\Models\Categories\SubjectTypeSubject;
use App\Models\Categories\SubjectType;
use App\Models\Categories\SubjectTypeResult;
use Modules\User\Entities\WorkingProcess;
use Modules\Offline\Entities\OfflineCourseClass;

class UserController extends Controller
{
    use AuthenticatesUsers;

    public function index($id = 0)
    {
        $user = profile();
        $unit = Unit::getTreeParentUnit($user->unit_code);
        $max_unit = Unit::getMaxUnitLevel();
        $level_name = function ($level) {
            return Unit::getLevelName($level);
        };

        $promotion_user_point = PromotionUserPoint::firstOrNew(['user_id' => $user->user_id]);
        if(!$promotion_user_point){
            $promotion_user_point->user_id = $user->user_id;
            $promotion_user_point->point = 0;
            $promotion_user_point->level_id = 0;
            $promotion_user_point->save();
        }

        $promotion = PromotionUserPoint::whereUserId($user->user_id)->first();
        $promotion_level = '';
        if(!empty($promotion)) {
            $promotion_level = PromotionLevel::where('status',1)->where('level',$promotion->level_id)->first();
        }
        $orders = PromotionOrders::whereUserId($user->user_id)
            ->select('el_promotion_orders.*','el_promotion.name','el_promotion.images','el_promotion_group.name as group_name')
            ->join('el_promotion', 'promotion_id', 'el_promotion.id')
            ->join('el_promotion_group', 'el_promotion_group.id','promotion_group')
            ->paginate(8);
        foreach($orders as $order) {
            if($order->status == 'Quy đổi thành công') {
                $order->status = trans('laother.successful_conversion');
            } else if ($order->status == 'Đang chờ xử lý') {
                $order->status = trans('laother.promotion_pending');
            } else if ($order->status == 'Từ chối') {
                $order->status = trans('latraining.deny');
            } else if ($order->status == 'Hủy') {
                $order->status = trans('lacore.cancel');
            } else if ($order->status == 'Đang sử dụng quà tặng') {
                $order->status = trans('laother.using_gift');
            }
        }
        $info_qrcode = json_encode(['user_id'=>$user->user_id,'type'=>'profile']);
        $sliders = Slider::where('status', '=', 1)
            ->where('type', '=', 1)
            ->where('location', '!=', 1)
            ->where(function ($sub) use ($unit){
                $sub->whereNull('object');
                foreach ($unit as $item){
                    $sub->orWhereIn('object', [$item->id]);
                }
            })
            ->get();
        $referer = \Request::segment(2)=='referer'?$user->referer:null;

        $career_roadmaps = CareerRoadmap::where('title_id', '=', @$user->title_id)->where('primary', '=', 1)->latest()->first();

        $user_meta = function ($key) use($user){
            return UserMeta::where('user_id', '=', $user->user_id)->where('key', '=', $key)->first(['value']);
        };
        $user_name = User::find($user->user_id)->username;

        //Lộ trình đào tạo
        $training_by_title_category = TrainingByTitleCategory::where('title_id', '=', @$user->title_id)->orderBy('id','asc')->get();
        $f_percent_training_by_title = function($title_cate_id) use($user){
            $total = 0;
            $getTrainingByTitles = TrainingByTitleDetail::where('training_title_category_id', $title_cate_id)->where('title_id', '=', @$user->title_id)->get();
            foreach($getTrainingByTitles as $child){
                $check_finish_subject = UserCompletedSubject::whereSubjectId($child->subject_id)->whereUserId($user->user_id)->exists();
                if($check_finish_subject) {
                    $count = 100;
                } else {
                    $count = 0;
                }
                $total += $count;
            }
            return number_format($total/($getTrainingByTitles->count() > 0 ? $getTrainingByTitles->count() : 1), 2);
        };

        $count_training_by_title_detail = CourseView::query()
        ->leftJoin('el_training_by_title_detail as b', 'b.subject_id', '=', 'el_course_view.subject_id')
        ->where('b.title_id', '=', @$user->title_id)->count();

        $count_subject_completed = UserCompletedSubject::whereUserId($user->user_id)->groupBy(['subject_id'])->count();

        $roadmaps = CareerRoadmap::where('title_id', '=', @$user->title_id)->get(['id', 'name']);
        $roadmaps_user = CareerRoadmapUser::query()
            ->where('user_id', '=', $user->user_id)
            ->where('title_id', '=', @$user->title_id)
            ->get(['id', 'name']);

        //Thời gian bắt đầu của Lộ trình đào tạo
        if ($user->date_title_appointment) {
            $start_date = get_date($user->date_title_appointment, 'Y-m-d');
        } elseif ($user->effective_date) {
            $start_date = get_date($user->effective_date, 'Y-m-d');
        } elseif ($user->join_company) {
            $start_date = get_date($user->join_company, 'Y-m-d');
        } else {
            $start_date = get_date($user->created_at, 'Y-m-d');
        }

        if ($id > 0) {
            $model_my_certificate = MyCertificate::find($id);
        } else {
            $model_my_certificate = new MyCertificate();
        }

        $student_costs = StudentCost::where('status','=',1)->get();
        $agent = new Agent();

        $imageTrainingByTitle = TrainingByTitleUploadImage::where('type', $user->gender)->first();
        if($imageTrainingByTitle->image != 'null') {
            $imageTrainingByTitle->image2 = $imageTrainingByTitle->image;
        } else {
            if ($imageTrainingByTitle->type == 1) {
                $imageTrainingByTitle->image2 = asset('images/title_male.png');
            } else {
                $imageTrainingByTitle->image2 = asset('images/title_female.png');
            }
        }
        return view('user::frontend.index',[
            'user' => $user,
            'unit' => $unit,
            'max_unit' => $max_unit,
            'level_name' => $level_name,
            'promotion' => $promotion,
            'orders' => $orders,
            'info_qrcode'=>$info_qrcode,
            'sliders' => $sliders,
            'referer' => $referer,
            'user_meta' => $user_meta,
            'user_name' => $user_name,
            'count_training_by_title_detail' => $count_training_by_title_detail,
            'count_subject_completed' => $count_subject_completed,
            'training_by_title_category' => $training_by_title_category,
            'roadmaps' => $roadmaps,
            'career_roadmaps' => $career_roadmaps,
            'roadmaps_user' => $roadmaps_user,
            'start_date' => $start_date,
            'promotion_level' => $promotion_level,
            'student_costs' => $student_costs,
            'agent' => $agent,
            'model_my_certificate' => $model_my_certificate,
            'imageTrainingByTitle' => $imageTrainingByTitle,
            'f_percent_training_by_title' => $f_percent_training_by_title,
        ]);
    }

    public function getDataPointHist(Request $request)
    {
        $sort = $request->input('sort', 'name');
        $order = $request->input('order', 'asc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);
        $query = PromotionUserHistory::query()
            ->select(['a.*', 'b.code','c.name as video_name','d.name as promotion_name'])
            ->from('el_promotion_user_point_get_history as a')
            ->leftJoin('el_promotion_course_setting as b', 'b.id', '=', 'a.promotion_course_setting_id')
            ->leftJoin('el_daily_training_video as c', 'c.id', '=', 'a.video_id')
            ->leftJoin('el_promotion as d', 'd.id', '=', 'a.promotion')
            ->where('a.user_id', '=', profile()->user_id)
            ->where('a.point', '>', '0');

        $count = $query->count();
        $query->orderBy('a.'.$sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();

        $arr_object = [
            '1' => trans('latraining.online'),
            '2' => trans('latraining.offline'),
            '3' => 'thi',
            '4' => 'khảo sát',
        ];

        foreach ($rows as $row) {
            if($row->daily_training == 1) {
                $row->content = 'Học liệu đào tạo video: '. $row->video_name;
            } else if ($row->donate_point == 1) {
                $row->content = 'Tặng điểm';
            } else if ($row->promotion) {
                $row->content = 'Quà tặng quy đổi '. $row->promotion_name;
            } else {
                switch ($row->code){
                    case 'complete':
                        $content = 'Hoàn thành '. $arr_object[$row->type]; break;
                    case 'landmarks':
                        $content = 'Đạt mốc điểm trong '. $arr_object[$row->type]; break;
                    case 'assessment_after_course':
                        $content = 'Thực hiện đánh giá sau khóa học của '. $arr_object[$row->type]; break;
                    case 'evaluate_training_effectiveness':
                        $content = 'Đánh giá hiệu quả sau đào tạo của '. $arr_object[$row->type]; break;
                    case 'rating_star':
                        $content = 'Đánh giá sao '. $arr_object[$row->type]; break;
                    case 'share_course':
                        $content = 'Share '. $arr_object[$row->type]; break;
                    case 'attendance':
                        $content = 'Tham gia '. $arr_object[$row->type]; break;
                    default:
                        $content = ''; break;
                }
                $row->content = $content;
            }

            $row->createdate = $row->created_at->format('d/m/Y');
        }
        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function showModalReferer(Request $request) {
        $schedule_id = $request->input('schedule_id');
        $course_id = $request->input('course_id');
        return view('user::frontend.referer.qrcode' );
    }

    public function getRefererHist(Request $request)
    {
        $query = RefererHist::query();
        $id_code = profile()->id_code;
        $query->select([
            'a.*',
            'b.full_name as name_referer'
        ]);
        $query->from("el_referer_hist AS a");
        $query->leftJoin('el_profile_view as b', 'a.user_id','=','b.user_id');
        $query->where('a.referer','=', $id_code);

        $count = $query->count();
        $rows = $query->get();
        foreach ($rows as $row) {
            $row->created_at = get_date($row->created_at,'d/m/Y h:i:s ');
        }
        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function saveReferer(Request $request)
    {
        $this->validateRequest([
            'referer' => 'nullable|min:6|max:10',
        ],$request, Profile::getAttributeName());
        if (!Profile::validRefer($request->referer)){
            json_message('Mã giới thiệu không hợp lệ','error');
        }
        $id = profile()->user_id;
        $model = Profile::firstOrCreate(['user_id'=>$id]);
        if ($model->referer){
            json_message('Cập nhật thành công');
        }else
            $model->referer = $request->referer;
        if($model->save())
        {
            PromotionUserPoint::updatePointReferer($request->referer);

        }
        json_result(['message'=>'Cập nhật thành công','status'=>'success','redirect'=>route('frontend.user.referer')]);
    }

    public function getDataRoadmap(Request $request)
    {
        $user = profile();

        $query = TrainingRoadmap::query();
        $query->select([
            'a.subject_id',
            'a.completion_time',
            'a.training_form',
            'd.code as training_program_code',
            'd.name as training_program_name',
            'subject.id',
            'subject.code as subject_code',
            'subject.name as subject_name',
        ]);
        $query->from("el_trainingroadmap AS a");
        $query->leftJoin('el_subject as subject', 'subject.id', '=', 'a.subject_id');
        $query->leftJoin('el_training_program as d','d.id','=','a.training_program_id');
        $query->where('a.title_id','=', $user->title_id);
        $count = $query->count();
        $rows = $query->get();
        foreach ($rows as $row) {
            $training_process = TrainingProcess::whereSubjectId($row->subject_id)
                ->where('titles_code','=', $user->title_code)
                ->where('user_id', '=', $user->user_id)
                ->where('pass', 1)
                ->orderByDesc('updated_at')
                ->first();

            $hasCourse = $this->checkCourseSubject($row->subject_id);
            $row->start_date = $training_process ? get_date($training_process->start_date) : '';
            $row->end_date = $training_process ? get_date($training_process->end_date) : '';
            $row->score = ($training_process && $training_process->mark) ? number_format($training_process->mark,2,',','.') : '';
            if ($row->training_program_code){
                $btn = $hasCourse?'<button class="btn load-modal" data-url="'.route('module.frontend.user.show_modal_roadmap',[$row->subject_id] ).'">Đăng ký</button>':'<button data-subject_id="'.$row->subject_id.'" class="btn btnRegisterSubject">Đăng ký</button>';

                $row->result = ($training_process && $training_process->pass==1)? trans('backend.finish') : trans('backend.incomplete').'<br/>'.$btn;
            }

            if ($row->completion_time && $training_process && $training_process->time_complete){
                if ($training_process->course_type == 1){
                    $row->start_effect = get_date($training_process->time_complete);
                    $end = strtotime(date("Y-m-d", strtotime($training_process->time_complete)) . " +{$row->completion_time} day");
                    $row->end_effect = strftime("%d/%m/%Y", $end);
                }else{
                    $row->start_effect = get_date($training_process->end_date);
                    $end = strtotime(date("Y-m-d", strtotime($training_process->end_date)) . " +{$row->completion_time} day");
                    $row->end_effect = strftime("%d/%m/%Y", $end);
                }
            }else{
                $row->start_effect = '-';
                $row->end_effect = '-';
            }

            $row->status = $training_process && $training_process->pass==1? trans('backend.finish') :trans('backend.incomplete');
            $row->note = $training_process ? $training_process->note : '';
            if ($training_process){
                if ($training_process->process_type==2)
                    $row->process_type = trans('backend.subject_complete');
                elseif ($training_process->process_type==4)
                    $row->process_type = trans('backend.merge_subject');
                elseif ($training_process->process_type==5)
                    $row->process_type = trans('backend.split_subject');
                else
                    $row->process_type = '-';
            }
            else
                $row->process_type = '-';


            if($row->training_form) {
                if($row->training_form == 1){
                    $row->training_form = 'Online';
                } else {
                    $row->training_form = trans("latraining.offline");
                }
            } else {
                $row->training_form = 'Online, Tập trung';
            }
        }
        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function checkCourseSubject($subject_id)
    {
        $courses_online = OnlineCourse::where(['subject_id'=>$subject_id,'status'=>1,'isopen'=>1])->exists();
        if ($courses_online)
            return true;
        $courses_offline = OfflineCourse::where(['subject_id'=>$subject_id,'status'=>1,'isopen'=>1])->exists();
        if ($courses_offline)
            return true;
        return false;
    }
    public function getModalContent(Request $request){
        $this->validateRequest([
            'roadmap_id' => 'required',
        ], $request);

        // $user_new_recruitment = NewRecruitment::query()
        //     ->where('user_id', '=', profile()->user_id)
        //     ->where('end_date','>',date('Y-m-d H:i:s'))
        //     ->first();

        $user_convert_titles = ConvertTitles::query()
            ->where('user_id','=',profile()->user_id)
            ->where('end_date','>',date('Y-m-d H:i:s'))
            ->first();

        $user_potential = Potential::query()
            ->where('user_id','=',profile()->user_id)
            ->where('end_date','>',date('Y-m-d H:i:s'))
            ->first();

        // if ($user_new_recruitment)
        //     $roadmap = NewRecruitmentRoadmap::find($request->roadmap_id);
        // else
        if ($user_convert_titles)
            $roadmap = ConvertTitlesRoadmap::find($request->roadmap_id);
        elseif ($user_potential)
            $roadmap = PotentialRoadmap::find($request->roadmap_id);
        else
            $roadmap = TrainingRoadmap::find($request->roadmap_id);

        $subject = Subject::find($roadmap->subject_id);

        return view('user::frontend.roadmap.modal_content', [
            'roadmap' => $roadmap,
            'subject' => $subject,
        ]);
    }

    public function getDataTrainingProcess() {
        $user_id = profile()->user_id;

        $query = TrainingProcess::query();
        $query->select([
            'mark',
            'id',
            'course_id',
            'course_code',
            'course_name',
            'titles_name',
            'course_type',
            'process_type',
            'pass as result',
            'start_date',
            'end_date',
            'certificate',
        ]);
        $query->where('user_id','=', $user_id);
        $count = $query->count();
        $rows = $query->get();
        foreach ($rows as $row) {
            if ($row->course_type==1){
                $course = OnlineCourse::find($row->course_id);
            }else{
                $course = OfflineCourse::find($row->course_id);
            }

            $row->image_cert = '';
            if (isset($course->cert_code) && $row->result == 1){
                $row->image_cert = route('module.frontend.user.trainingprocess.certificate', ['course_id' => $row->course_id, 'course_type' => $row->course_type, 'user_id' => $user_id]);
            }

            $row->training_form = '-';
            if($course) {
                $training_form = TrainingForm::where('id',$course->training_form_id)->first();
                $row->training_form = @$training_form->name;
            }

            $row->course_type = $row->course_type==1?trans('backend.onlines'):trans('latraining.offline');
            $row->start_date = get_date($row->start_date);
            $row->end_date = get_date($row->end_date);
            $row->score = $row->mark ? number_format($row->mark,2,',','.') : '';

            if ($row->process_type==2)
                $row->process_type = trans('backend.subject_complete');
            elseif ($row->process_type==4)
                $row->process_type = trans('backend.merge_subject');
            elseif ($row->process_type==5)
                $row->process_type = trans('backend.split_subject');
            else
                $row->process_type = '-';

        }
        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function getDataQuizResult() {
        $query = \DB::query()
            ->select([
                'a.quiz_id',
                'c.id',
                'c.code',
                'c.name',
                'c.limit_time',
                'b.start_date',
                'b.end_date',
                'd.grade',
                'd.result',
                'd.reexamine'
            ])
            ->from('el_quiz_register as a')
            ->join('el_quiz_part as b','b.id','=','a.part_id')
            ->join('el_quiz as c','c.id','=','b.quiz_id')
            ->leftJoin('el_quiz_result as d',function ($join){
                $join->on('a.user_id','=','d.user_id');
                $join->on('d.quiz_id','=','a.quiz_id');
            })
            ->where('a.user_id','=',profile()->user_id);
        $count = $query->count();
        $rows = $query->get();
        foreach ($rows as $row) {
            $row->start_date = get_date($row->start_date,'d/m/Y H:i');
            $row->end_date = get_date($row->end_date,'d/m/Y H:i');
            $row->grade = number_format(($row->reexamine ? $row->reexamine : $row->grade),2,',','.');
        }
        json_result(['total' => $count, 'rows' => $rows]);
    }

	public function getDataSubjectTypeResult() {
        $year = date('Y');
        $subQuery = \DB::query()
            ->select([
                'object.subject_type_id',
            ])
            ->from('el_profile as profile')
            ->leftJoin('el_subject_type_object as object', function($sub){
                $sub->orOn('object.user_id', '=', 'profile.user_id');
                $sub->orOn('object.title_id', '=', 'profile.title_id');
                $sub->orOn('object.unit_id', '=', 'profile.unit_id');
            })
            ->groupBy('object.subject_type_id')
            ->where('profile.user_id','=', profile()->user_id);

        $query = \DB::query()
            ->select([
                'a.*',
            ])
            ->from('el_subject_type as a')
            ->joinSub($subQuery,'b', function ($join){
                $join->on('b.subject_type_id', '=', 'a.id');
            });

        $count = $query->count();
        $rows = $query->get();
        foreach ($rows as $row) {
            $subject_list = SubjectTypeSubject::whereSubjectTypeId($row->id)->count();
            $result = SubjectTypeResult::whereSubjectTypeId($row->id)->where('user_id', profile()->user_id)->first();

            $row->finished_total = ($result ? $result->course_finished_total : 0) .'/'. $subject_list;
            $row->percent = ($result ? round(($result->course_finished_total/$subject_list)*100, 2) : 0) .'%';
            $row->date_complete = $result->updated_at ? get_date($result->updated_at, 'H:i:s d/m/Y') : '';

            $row->start_date = get_date($row->startdate);
            $row->end_date = get_date($row->enddate);

            if($row->certificate_expiry && ($year <= $row->certificate_expiry) || empty($row->certificate_expiry)) {
                $row->cert = $row->percent==100 ? route('module.frontend.user.subject_type.certificate', $row->id) : '';
            } else {
                $row->cert = 1;
            }
        }
        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function changeAvatar(Request $request)
    {
        $posts = ['selectavatar' => $request->file('selectavatar')];
        $rules = ['selectavatar' => 'required|image|max:10240'];
        $message = [
            'selectavatar.required' => 'Chưa chọn hình để upload',
            'selectavatar.image' => 'File hình không hợp lệ',
            'selectavatar.uploaded' => 'Dung lượng hình không được lớn hơn 10mb'
        ];

        $validator = \Validator::make($posts, $rules,$message);
        if ($validator->fails()){
            return redirect()->back();
        }

        $avatar = $request->file('selectavatar');
        $storage = \Storage::disk(config('app.datafile.upload_disk'));
        $extension = $avatar->getClientOriginalExtension();
        $filename = date('Y/m/d').'/avatar-'. profile()->user_id .'.'. $extension;

        if($storage->putFileAs('profile', $avatar, $filename))
        {
            $profile = profile();
            $model = HistoryChangeInfo::firstOrNew(['user_id' => profile()->user_id, 'key' => 'avatar']);
            $model->user_id = profile()->user_id;
            $model->key = 'avatar';
            $model->value_old = $profile->avatar;
            $model->value_new = $filename;
            $model->status = 1;
            $model->save();
            $profile->avatar = $filename;
            $profile->save();

            json_result([
                'status'=>'sucess',
                'message'=>'Đã thay đổi ảnh đại diện',
                'redirect' => route('module.frontend.user.info'),
            ]);
        }
        else{
            return redirect()->back();
        }
    }

    public function changePass(Request $request){
        $this->validateRequest([
            'password_old' => 'required|min:8|max:32',
            'password' => 'required|min:8|max:32',
            'repassword' => 'same:password',
        ], $request, Profile::getAttributeName());
        $password_old = $request->password_old;

        if ($request->password == $password_old){
            json_result(['status'=>'error','message'=>'Mật khẩu mới phải khác mật khẩu cũ']);
        }

        if (!check_password($request->password)){
            json_result(['status'=>'error','message'=>'Mật khẩu không đúng định dạng']);
        }

        $user = User::find(profile()->user_id);
        if ($user){
            $hash = $user->password;
            if (password_verify($password_old, $hash)) {
                $user->password = password_hash($request->password, PASSWORD_DEFAULT);
                $user->save();
                json_result([
                    'status'=>'success',
                    'message'=>'Đổi mật khẩu thành công',
                    'redirect' => route('login'),
                ]);
            }else{
                json_result(['status'=>'error','message'=>'Mật khẩu cũ không đúng']);
            }
        }
        return redirect(route('login'));
    }

    public function changePassFirst(Request $request){
        $this->validateRequest([
            'password_old' => 'required|min:8|max:32',
            'password' => 'required|min:8|max:32',
            'repassword' => 'same:password',
        ], $request, Profile::getAttributeName());
        $password_old = $request->password_old;

        if ($request->password == $password_old){
            json_result(['status'=>'error','message'=>'Mật khẩu mới phải khác mật khẩu cũ']);
        }

        if (!check_password($request->password)){
            json_result(['status'=>'error','message'=>'Mật khẩu không đúng định dạng']);
        }

        $user = User::find(profile()->user_id);
        if ($user){
            $hash = $user->password;
            if (password_verify($password_old, $hash)) {

                ProfileChangedPass::where('user_id', '=', profile()->user_id)
                    ->update([
                        'status' => 1
                    ]);

                $user->password = password_hash($request->password, PASSWORD_DEFAULT);
                $user->save();

                $this->guard()->logout();
                $request->session()->invalidate();

                return response()->json([
                    'status' => 'success',
                    'message' => 'Đổi mật khẩu thành công',
                    'redirect' => route('login'),
                ]);
            }else{
                json_result(['status'=>'error','message'=>'Mật khẩu cũ không đúng']);
            }
        }
        return response()->json([
            'status' => 'error',
            'message' => 'Tài khoản không tồn tại',
            'redirect' => route('login'),
        ]);
    }

    public function changeUserInfo(Request $request){
        $this->validateRequest([
            'key' => 'required',
            'value_new' => 'required',
        ], $request, HistoryChangeInfo::getAttributeName());

        $key = $request->key;
        $value_old = $request->value_old;
        $value_new = $request->value_new;
        $note = $request->note;

        $model = HistoryChangeInfo::firstOrNew(['user_id' => profile()->user_id, 'key' => $key]);
        $model->user_id = profile()->user_id;
        $model->key = $key;
        $model->value_old = $value_old ? $value_old : null;
        $model->value_new = $value_new;
        $model->note = $note ? $note : null;
        $model->status = 2;
        $model->save();

        $unit_id = [];
        $unit = Unit::getTreeParentUnit(Profile::getUnitCode());
        foreach ($unit as $item){
            $unit_id[] = $item->id;
        }

        $query = UserPermissionType::query()
            ->from('el_user_permission_type as a')
            ->leftJoin('el_permission_type_unit as b', 'b.permission_type_id', '=', 'a.permission_type_id')
            ->leftJoin('el_permissions as c', 'c.id', '=', 'a.permission_id')
            ->where(function ($sub) use ($unit_id){
                $sub->orWhere(function ($sub1) use ($unit_id){
                    $sub1->where('b.type', '=', 'group-child')
                        ->whereIn('b.unit_id', $unit_id);
                });
                $sub->orWhere(function ($sub2){
                    $sub2->where('b.type', '=', 'owner')
                        ->where('b.unit_id', '=', Profile::getUnitId());
                });
            })
            ->whereIn('c.name', function ($sub2){
                $sub2->select(['per.parent'])
                    ->from('el_model_has_permissions as model')
                    ->leftJoin('el_permissions as per', 'per.id', '=', 'model.permission_id')
                    ->whereColumn('model.model_id', '=', 'a.user_id')
                    ->where('per.name', '=', 'user-approve-change-info');
            })
            ->where('c.name', '=', 'user')
            ->pluck('a.user_id')->toArray();

        $user_managers = $query;
        if (count($user_managers) > 0){
            foreach ($user_managers as $user) {
                $model = new Notify();
                $model->user_id = $user;
                $model->subject = 'Duyệt thay đổi thông tin';
                $model->content = 'Nhân viên '. profile()->full_name .' vừa thay đổi thông tin. Vui lòng vào quản trị để duyệt thông tin thay đổi';
                $model->url = '';
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
                $notification->add($user);
            }
            $notification->save();
        }

        if (url_mobile()){
            $redirect = route('themes.mobile.frontend.profile');
        }else{
            $redirect = route('module.frontend.user.info');
        }

        json_result([
            'status'=>'sucess',
            'message'=>'Thông tin đã thay đổi. Xin chờ duyệt...!',
            'redirect' => $redirect,
        ]);
    }

    public function showPlanSuggest()
    {
        $user = profile();
        $title = Titles::where('code','=',$user->title_code)->first();
        return view('user::frontend.plansuggest.index',[
            'user' => $user,
            'title' => $title,
        ]);
    }

    public function getDataPlanSuggest(Request $request) {
        $search = $request->input('search');
        $sort = $request->input('sort', 'name');
        $order = $request->input('order', 'asc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);
        $unit_code = profile()->unit_code;
        $query = PlanSuggest::query()->where('unit_code','=',$unit_code);
        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();
//        foreach ($rows as $row) {
//            $row->start_date = get_date($row->start_date,'d/m/Y H:i');
//            $row->end_date = get_date($row->end_date,'d/m/Y H:i');
//            $row->grade = number_format($row->grade,2,',','.');
//        }
        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function createFormPlanSuggest(Request $request)
    {
        $data = array();
        $subject = Subject::where('status','=',1)->where('subsection', 0)->get();
        $title = Titles::select(['id','name','code'])->where('status','=',1)->get();
        $data['subject'] = $subject;
        $data['title'] = $title;
        if ($request->id){
            $id = (int) $request->id;
            $planSuggest = PlanSuggest::find($id);
            $data['planSuggest'] =$planSuggest;
            $data['subject_select'] =$planSuggest->subject_name;
            $data['title_select'] = $planSuggest->title? array_values( json_decode($planSuggest->title,true)):[];
        }
        json_result($data);
    }

    public function savePlanSuggest(Request $request)
    {
        $model = PlanSuggest::firstOrNew(['id' => $request->id]);
        $model->fill($request->all());
        $model->intend = date('Y-m-d', strtotime('01-'.str_replace('/', '-', $request->intend)));
        $model->unit_code = profile()->unit_code;
        $model->created_by =profile()->user_id;
        if ($model->save()) {
            /************************************/
            json_result([
                'status' => 'success',
                'message' => trans('laother.successful_save')
            ]);
        }
    }

    public function certificate($course_id, $course_type, $user_id){
        $query = CourseRegisterView::query();
        $query->select([
            'b.end_date',
            'a.score',
            'b.cert_code',
            'c.created_at as date_complete',
            'b.training_program_name',
            'b.subject_id',
            'b.name as course_name',
            'b.code as course_code',
        ]);
        $query->from('el_course_register_view AS a');
        $query->join('el_course_view AS b', function ($join){
            $join->on('a.course_id', '=', 'b.course_id');
            $join->on('a.course_type', '=', 'b.course_type');
        });
        $query->leftJoin('el_course_complete AS c', function ($join){
            $join->on('c.course_id', '=', 'b.course_id');
            $join->on('c.course_type', '=', 'b.course_type');
        });
        $query->where('a.user_id','=',profile()->user_id);
        $query->where('a.course_id', '=', $course_id);
        $query->where('a.course_type', '=', $course_type);
        $model = $query->first();

        $profile = ProfileView::find($user_id);
        $unit = @$profile->unit_name;
        $title = @$profile->title_name;
        $fullname = $profile->full_name;
        $date_complete = date('\n\g\à\y d \t\h\á\n\g m \n\ă\m Y', strtotime(@$model->date_complete));

        $certificate = \Modules\Certificate\Entities\Certificate::find($model->cert_code);
        $itemDesigns = CertificateDesign::where("certificate_id", $certificate->id)->get();

        $design = array();
        foreach ($itemDesigns as $v){
            $design[$v->name] = [$v->pleft, $v->ptop, $v->align ? $v->align : 'left', $v->font_size, $v->status, $v->color];
        }

        $storage = \Storage::disk('upload');
        $path = $storage->path($certificate->image);
        $temp = str_replace($certificate->image, str_replace('.', '_'.$course_id.'.', $certificate->image), $path);
        $image = ImageManagerStatic::make($path)->resize(1280, 848);

        $imge_size = 0;
        $exTop = 20;
        $align_center_image = 640;
        if($design["fullname"] && $design["fullname"][4] == 1){
            $left = $design["fullname"][0] - $extop + $imge_size;
            $top = $design["fullname"][1] + $exTop;
            $align = $design["fullname"][2];
            $font_size = (int) $design["fullname"][3];
            $color = $design["fullname"][5];
            $left = ($align == 'center' ? $align_center_image : $left + $font_size);
            $image->text($fullname, $left, $top, function ($font) use ($align, $font_size, $color) {
                $font->file(public_path('fonts/UTM Wedding K&T.ttf'));
                $font->size($font_size - 10);
                $font->color($color);
                $font->align($align);
                $font->valign("middle");
            });
        }

        // tên khóa học
        if($design["course_name"] && $design["course_name"][4] == 1){
            $exTop = 10;
            $left = $design["course_name"][0] + $imge_size;
            $top = $design["course_name"][1] + $exTop;
            $align = $design["course_name"][2];
            $color = $design["course_name"][5];
            $font_size = (int) $design["course_name"][3];
            $left = ($align=='center' ? $align_center_image : $left + $font_size);
            $image->text($model->course_name, $left, $top, function ($font) use ($align, $font_size, $color) {
                $font->file(public_path('fonts/FiraSansExtraCondensed-Bold.ttf'));
                $font->size($font_size);
                $font->color($color);
                $font->align($align);
                $font->valign("middle");
            });
        }

        // mã khóa học
        if($design["course_code"] && $design["course_code"][4] == 1){
            $exTop = 10;
            $left = $design["course_code"][0] + $imge_size;
            $top = $design["course_code"][1] + $exTop;
            $font_size = (int) $design["course_code"][3];
            $left = $left + $font_size;
            $color = $design["course_code"][5];
            $image->text($model->course_code, $left, $top, function ($font) use ($align, $font_size, $color) {
                $font->file(public_path('fonts/timesbd.ttf'));
                $font->size($font_size);
                $font->color($color);
                $font->valign("middle");
            });
        }

        // chức danh
        if($design["title"] && $design["title"][4] == 1){
            $exTop = 10;
            $font_size = $design["title"][3];
            $left = $design["title"][0] + $imge_size + $font_size;
            $top = $design["title"][1] + $exTop;
            $color = $design["title"][5];
            $align = $design["title"][2];
            $left = ($align == 'center' ? $align_center_image : $left + $font_size);
            $image->text($title, $left, $top, function ($font) use ($align, $font_size, $color) {
                $font->file(public_path('fonts/timesbd.ttf'));
                $font->size($font_size);
                $font->color($color);
                $font->align($align);
                $font->valign("middle");
            });
        }

        // ngày hoàn thành
        if($date_complete && $design["date"][4] == 1){
            $exTop = 10;
            $font_size = $design["date"][3] ? (int)$design["date"][3] : 18;
            $left = (isset($design["date"][0]) ? $design["date"][0] : 100) + $imge_size + $font_size;
            $top = (isset($design["date"][1]) ? $design["date"][1] : 100) + $exTop;
            $color = $design["date"][5];
            $image->text($date_complete, $left, $top, function ($font) use ($font_size, $color) {
                $font->file(public_path('fonts/timesbd.ttf'));
                $font->size($font_size);
                $font->color($color);
                $font->valign("middle");
            });
        }

        $image->save($temp);
        $headers = array(
            'Content-Type: application/pdf',
        );
        ob_end_clean();
        return response()->download($temp, 'chung_chi_'.Str::slug($fullname, '_').'.png', $headers);
    }

    public function previewCert(Request $request)
    {
        return $this->certificateSubjectType(0, 0, true, $request);
    }

    public function certificateSubjectType($id, $user_id, $ispreview=false, $info=null) {
        if(!$ispreview){
            if(!$user_id) $user_id = profile()->user_id;
            $query = \DB::query()
                ->select([
                    'b.id',
                    'b.code',
                    'b.name',
                    'b.startdate',
                    'b.enddate',
                    'a.course_finished_total',
                    'a.certificate_file',
                ])
                ->from('el_subject_type_result as a')
                ->join('el_subject_type as b','b.id','=','a.subject_type_id')
                ->where('b.certificate_id','=',$id)
                ->where('a.user_id','=',$user_id);

            $model = $query->first();

            $profile = ProfileView::find($user_id);
            $unit = @$profile->unit->name;
            $title = @$profile->titles->name;
            $fullname = $profile->lastname . ' ' . $profile->firstname;
            $day = get_date(@$model->date_complete, 'd');
            $month = get_date(@$model->date_complete, 'm');
            $year = get_date(@$model->date_complete, 'Y');

            $date_complete = date('\n\g\à\y d \t\h\á\n\g m \n\ă\m Y', strtotime(@$model->date_complete));
            $date_complete_en = date('F d, Y', strtotime(@$model->date_complete));

            $course = null;

            if ($course_type == 1){
                $course = OnlineCourse::find($course_id);
            }else{
                $course = OfflineCourse::find($course_id);
            }

            $certificate = \Modules\Certificate\Entities\Certificate::find($id);

            $course_name = $course->name;
            $title = $profile->title_name;
            $training_program_name = $model->name;

            $itemDesigns = CertificateDesign::where("certificate_id",$id)->get();
        } else {
            $certificate = \Modules\Certificate\Entities\Certificate::find($info->id);

            $itemDesigns = CertificateDesign::where("certificate_id",$info->id)->get();
            $course_name = $info->text_course_name ?? 'TÊN-KHÓA-HỌC';
            $course_code = $info->text_course_code ?? 'MÃ-KHÓA-HỌC';
            $training_program_name = $info->text_subject_type ?? 'TÊN-CHƯƠNG-TRÌNH-ĐÀO-TẠO';
            $fullname = $info->text_fullname ?? 'HỌ-VÀ-TÊN';
            $date_complete = $info->text_date ?? 'TP/NGÀY/THÁNG/NĂM';
            $title = $info->text_title ?? 'CHỨC-DANH';

            $user = $certificate->user;
            $position = $certificate->position;
            $course_id = 0;
        }

        $design = array();
        foreach ($itemDesigns as $v){
            $design[$v->name] = [$v->pleft, $v->ptop, $v->align ? $v->align : 'left', $v->font_size, $v->status, $v->color];
        }

        $storage = \Storage::disk('upload');
        $path = $storage->path($certificate->image);
        $temp = str_replace($certificate->image, str_replace('.', '_'.$course_id.'.', $certificate->image), $path);
        $image = ImageManagerStatic::make($path)->resize(1280, 848);

        $imge_size = 0;
        $exTop = 20;
        $align_center_image = 640;

        if($design["fullname"][4] == 1){
            $left = $design["fullname"][0] - $extop + $imge_size;
            $top = $design["fullname"][1] + $exTop;
            $align = $design["fullname"][2];
            $font_size = (int) $design["fullname"][3];
            $color = $design["fullname"][5];
            $left = ($align == 'center' ? $align_center_image : $left + $font_size);
            $image->text($fullname, $left, $top, function ($font) use ($align, $font_size, $color) {
                $font->file(public_path('fonts/UTM Wedding K&T.ttf'));
                $font->size($font_size - 10);
                $font->color($color);
                $font->align($align);
                $font->valign("middle");
            });
        }

        if($certificate->type == 2) {
            if($training_program_name){
                $exTop = 10;
                $left = $design["subject_type"][0] + $imge_size;
                if($design["subject_type"][4] == 1){
                    $top = $design["subject_type"][1] + $exTop;
                    $align = $design["subject_type"][2];
                    $color = $design["subject_type"][5];
                    $font_size = (int) $design["subject_type"][3];
                    $left = ($align=='center' ? $align_center_image : $left + $font_size);
                    $image->text($training_program_name, $left, $top, function ($font) use ($align, $font_size, $color) {
                        $font->file(public_path('fonts/FiraSansExtraCondensed-Bold.ttf'));
                        $font->size($font_size);
                        $font->color($color);
                        $font->align($align);
                        $font->valign("middle");
                    });
                }
            }
        } else {
            if($course_name){
                $exTop = 10;
                $left = $design["course_name"][0] + $imge_size;
                if($design["course_name"][4] == 1){
                    $top = $design["course_name"][1] + $exTop;
                    $align = $design["course_name"][2];
                    $color = $design["course_name"][5];
                    $font_size = (int) $design["course_name"][3];
                    $left = ($align=='center' ? $align_center_image : $left + $font_size);
                    $image->text($course_name, $left, $top, function ($font) use ($align, $font_size, $color) {
                        $font->file(public_path('fonts/FiraSansExtraCondensed-Bold.ttf'));
                        $font->size($font_size);
                        $font->color($color);
                        $font->align($align);
                        $font->valign("middle");
                    });
                }
            }

            if($course_code){
                $exTop = 10;
                $left = $design["course_code"][0] + $imge_size;
                if($design["course_code"][4] == 1){
                    $top = $design["course_code"][1] + $exTop;
                    $font_size = (int) $design["course_code"][3];
                    $left = $left + $font_size;
                    $color = $design["course_code"][5];
                    $image->text($course_code, $left, $top, function ($font) use ($align, $font_size, $color) {
                        $font->file(public_path('fonts/timesbd.ttf'));
                        $font->size($font_size);
                        $font->color($color);
                        $font->valign("middle");
                    });
                }
            }
        }

        // if($user && $design["user"][4] == 1){
        //     $exTop = 10;
        //     $left = (isset($design["user"][0]) ? $design["user"][0] : 100) + $imge_size;
        //     $top = (isset($design["user"][1]) ? $design["user"][1] : 100) + $exTop;
        //     $font_size = $design["user"][3] ? (int)$design["user"][3] : 18;
        //     $color = $design["course_code"][5];
        //     $image->text($user, $left, $top, function ($font) use ($align, $font_size, $color) {
        //         $font->file(public_path('fonts/timesbd.ttf'));
        //         $font->size($font_size);
        //         $font->color('#000000');
        //         $font->valign("middle");
        //     });
        // }

        // if($position && $design["position"][4] == 1){
        //     $exTop = 10;
        //     $font_size = $design["position"][3] ? (int)$design["position"][3] : 18;
        //     $left = (isset($design["position"][0]) ? $design["position"][0] : 100) + $imge_size + $font_size;
        //     $top = (isset($design["position"][1]) ? $design["position"][1] : 100) + $exTop;
        //     $color = $design["course_code"][5];
        //     $image->text($position, $left, $top, function ($font) use ($align, $font_size, $color) {
        //         $font->file(public_path('fonts/timesbd.ttf'));
        //         $font->size($font_size);
        //         $font->color('#000000');
        //         $font->valign("middle");
        //     });
        // }

        if($design["title"][4] == 1){
            $exTop = 10;
            $font_size = $design["title"][3];
            $left = $design["title"][0] + $imge_size + $font_size;
            $top = $design["title"][1] + $exTop;
            $color = $design["title"][5];
            $align = $design["title"][2];
            $left = ($align == 'center' ? $align_center_image : $left + $font_size);
            $image->text($title, $left, $top, function ($font) use ($align, $font_size, $color) {
                $font->file(public_path('fonts/timesbd.ttf'));
                $font->size($font_size);
                $font->color($color);
                $font->align($align);
                $font->valign("middle");
            });
        }

        if($date_complete && $design["date"][4] == 1){
            $exTop = 10;
            $font_size = $design["date"][3] ? (int)$design["date"][3] : 18;
            $left = (isset($design["date"][0]) ? $design["date"][0] : 100) + $imge_size + $font_size;
            $top = (isset($design["date"][1]) ? $design["date"][1] : 100) + $exTop;
            $color = $design["date"][5];
            $image->text($date_complete, $left, $top, function ($font) use ($font_size, $color) {
                $font->file(public_path('fonts/timesbd.ttf'));
                $font->size($font_size);
                $font->color($color);
                $font->valign("middle");
            });
        }

        // $exTop = 0;
        // $left = (isset($design["signature"][0]) ? $design["signature"][0] : 100);
        // if($design["signature"][4] == 1){
        //     $top = (isset($design["signature"][1]) ? $design["signature"][1] : 100) + $exTop;
        //     $image->insert($storage->path($certificate->signature), 'top-left', $left, $top);
        // }

        // $exTop = 0;
        // if($design["logo"][4] == 1) {
        //     $left = (isset($design["logo"][0]) ? $design["logo"][0] : 100);
        //     $top = (isset($design["logo"][1]) ? $design["logo"][1] : 100) + $exTop;
        //     $image->insert($storage->path($certificate->logo), 'top-left', $left, $top);
        // }

        $image->save($temp);

        $headers = array(
            'Content-Type: application/pdf',
        );
        ob_end_clean();
        return response()->download($temp, 'chung_chi_'.Str::slug($fullname, '_').'.png', $headers);
    }

    public function getMyCourse($type, Request $request)
    {
        $user_id = getUserId();
        $user_type = getUserType();

        $search = $request->get('q');
        $type_course = $request->get('type_course');
        $start_date = $request->get('start_date');
        $end_date = $request->get('end_date');
        $status = $request->get('status');

        $query = CourseView::query()
            ->from('el_course_view as a')
            ->select([
                'a.*',
            ])
            ->join('el_course_register_view as b',function($join){
                $join->on('a.course_id','=','b.course_id');
                $join->on('a.course_type','=','b.course_type');
            })

            ->where('b.user_id','=', $user_id)
            ->where('a.status', '=', 1)
            ->where('b.status', '=', 1)
            ->where('a.offline', '=', 0)
            ->where('a.isopen', '=', 1);

        if ($type == 1 || $type_course == 1) {
            $query->where('b.course_type','=', 1);
            $query->where('a.course_type','=', 1);
        } else if ($type == 2 || $type_course == 2) {
            $query->where('b.course_type','=', 2);
            $query->where('a.course_type','=', 2);
        }

        if ($search) {
            $query->where(function ($subquery) use ($search) {
                $subquery->orWhere('a.code', 'like', '%'. $search .'%');
                $subquery->orWhere('a.name', 'like', '%'. $search .'%');
            });
        }
        if ($start_date) {
            $query->where('a.start_date','>=',date_convert($start_date, '00:00:00'));
        }
        if ($end_date) {
            $query->where('a.end_date', '<=', date_convert($end_date, '23:59:59'));
        }
        $query->orderBy('a.id', 'desc');

        $count = $query->count();
        $rows = $query->paginate(12);
        foreach ($rows as $row) {
            $now = date('Y-m-d');
            $row->avg_rating_star = 0;//@OnlineCourse::find($row->id)->avgRatingStar();
            $row->start_date = get_date($row->start_date);
            $row->end_date = get_date($row->end_date);
            $row->course_url =$row->course_type==1?route('module.online.detail',['id'=>$row->course_id]):route('module.offline.detail',['id'=>$row->course_id]);

            $row->course_time_unit = preg_replace("/[^a-z]/", '', $row->course_time);
            $row->course_time = preg_replace("/[^0-9]./", '', $row->course_time);
        }

        if ($user_type == 1){
            $user = profile();
            $unit = Unit::getTreeParentUnit(@$user->unit_code);
            $title = Titles::whereCode(@$user->title_code)->first();

            PromotionUserPoint::firstOrCreate(['user_id' => auth()->id()], ['point' => 0, 'level_id' => 0]);
            $promotion = PromotionUserPoint::whereUserId(auth()->id())
                ->select('el_promotion_user_point.*','el_promotion_level.level','el_promotion_level.images','el_promotion_level.name')
                ->join('el_promotion_level', 'el_promotion_user_point.level_id','level')
                ->first();
            $sliders = Slider::where('status', '=', 1)->where('type', '=', 1)->where('location', '!=', 1)
                ->where(function ($sub) use ($unit){
                    $sub->whereNull('object');
                    foreach ($unit as $item){
                        $sub->orWhereIn('object', [$item->id]);
                    }
                })->get();
            $career_roadmaps = CareerRoadmap::where('title_id', '=', @$title->id)
                ->where('primary', '=', 1)
                ->latest()->first();
        }else{
            $promotion = '';
            $sliders = '';
            $career_roadmaps = '';
        }

        $agent = new Agent();
        return view('user::frontend.index', [
            'total' => $count,
            'items' => $rows,
            'promotion' => $promotion,
            'sliders' => $sliders,
            'career_roadmaps' => $career_roadmaps,
            'type' => $type,
            'user_type' => $user_type,
            'agent' => $agent
        ]);
    }

    public function getData(Request $request)
    {
        $sort = $request->input('sort', 'name');
        $order = $request->input('order', 'asc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = CourseView::query()
            ->from('el_course_view as a')
            ->join('el_course_register_view as b',function($join){
                $join->on('a.course_id','=','b.course_id');
                $join->on('a.course_type','=','b.course_type');
            })
            ->where('b.user_id','=',profile()->user_id)
            ->where('a.status', '=', 1)
            ->where('b.status', '=', 1)
            ->where('a.isopen', '=', 1)
            ->where('a.offline', '=', 0)
            ->select(['a.*']);

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();

        foreach ($rows as $row) {
            $now = date('Y-m-d');
            $row->start_date = get_date($row->start_date);
            $row->end_date = get_date($row->end_date);
            $row->course_url =$row->course_type==1?route('module.online.detail',['course_id'=>$row->id]):route('module.offline.detail',['course_id'=>$row->id]);
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    protected function calDate($date1, $date2) {
        $diff = abs(strtotime($date2) - strtotime($date1));
        $years = floor($diff / (365*60*60*24));
        $months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
        $days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));

        $total_date = $years*365 + $months*30 + $days;

        return number_format($total_date, 2);
    }

    private function isEvaluation($start_evaluation, $status)
    {
        $days = (strtotime(date('Y-m-d')) - strtotime($start_evaluation))/ (60 * 60 * 24);

        if (!$start_evaluation || $days<0 || $status<>2)
            return 0; // chưa tới hạn đánh giá
        if ($days>=0 && $days<=8)
            return 1; // đánh giá
        if ($days>8)
            return 2; // hết hạn đánh giá
        return 0;
    }

    public function getPromotionHistory(Request $request){
        $sort = $request->input('sort', 'name');
        $order = $request->input('order', 'asc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = PromotionUserHistory::whereUserId(auth()->id())
            ->select('el_promotion_user_point_get_history.*','el_online_course.name')
            ->join('el_online_course' ,'el_promotion_user_point_get_history.course_id','el_online_course.id');

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();

        foreach ($rows as $row) {
            $row->createdat = $row->created_at->format('d-m-Y');
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function myCapabilities()
    {
        $user = profile();
        $unit = Unit::where('code', '=', $user->unit_code)->first();
        $title = Titles::where('code', '=', $user->title_code)->first();

        return view('user::frontend.capabilities.course',[
            'user' => $user,
            'unit' => $unit,
            'title' => $title,
        ]);
    }

    public function showModalRoadmap(Request $request)
    {
        $subject_id = $request->subject;
        $subject = Subject::find($subject_id);
        if (url_mobile()){
            return view('trainingbytitle::mobile.modal_register_roadmap', [
                'subject' => $subject,
                'subject_id' => $subject_id,
            ]);
        }

        return view('user::frontend.roadmap.modal_register_roadmap', [
            'subject' => $subject,
            'subject_id' => $subject_id,
        ]);
    }

    public function getCourseBySubject(Request $request)
    {
        $date = date('Y-m-d');
        $subject_id = $request->subject;
        $sort = $request->input('sort', 'name');
        $order = $request->input('order', 'asc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $courses_offline = OfflineCourse::where(['subject_id'=>$subject_id,'status'=>1,'isopen'=>1])->where('end_date', '>=', $date)
            ->select('id','code','name',\DB::raw('2 as course_type'),'start_date','end_date');
        $courses_online = OnlineCourse::where(['subject_id'=>$subject_id,'status'=>1,'isopen'=>1])->where('end_date', '>=', $date)
            ->select('id','code','name',\DB::raw('1 as course_type'),'start_date','end_date');

        $query = $courses_online->union($courses_offline);
        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();

        foreach ($rows as $row) {
            if($row->course_type == 1) {
                $check_register = OnlineRegister::where(['user_id' => profile()->user_id, 'course_id' => $row->id])->first(['status']);
                $row->check_register = isset($check_register) ? $check_register->status : '';
                $row->online_detail = route('module.online.detail_online', ['id' => $row->id]);
            } else {
                $check_register = OfflineRegister::where(['user_id' => profile()->user_id, 'course_id' => $row->id])->first(['status']);
                $row->check_register = isset($check_register) ? $check_register->status : '';
                $row->offline_detail = route('module.offline.detail', ['id' => $row->id]);

            }
            $row->start_date = get_date($row->start_date, 'd/m/Y');
            $row->end_date = get_date($row->end_date, 'd/m/Y');
            $row->type = $row->course_type==1  ? 'Online' : trans('latraining.offline');
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function registerRoadmap(Request $request)
    {
        $course_id = $request->course_id;
        $course_type = $request->course_type;
        $subject_id = $request->subject_id;
        $user_id = profile()->user_id;
        $error = false;

        if (!$course_id){
            $exists = SubjectRegister::where(['user_id'=>$user_id,'subject_id'=>$subject_id])->exists();
            if ($exists)
                $error = true;
            else{
                $model = SubjectRegister::firstOrNew(['user_id' => $user_id, 'subject_id' => $subject_id]);
                $model->user_id = $user_id;
                $model->subject_id = $subject_id;
                $model->status=1;
                $model->note = 'Ghi danh từ tháp đào tạo';
                $model->save();
            }
        }else{
            if ($course_type==1) {
                if (OnlineRegister::where(['user_id' => $user_id, 'course_id' => $course_id])->exists())
                    $error = true;
                else{
                    $model = OnlineRegister::firstOrNew(['user_id' => $user_id, 'course_id' => $course_id]);
                    $model->user_id = $user_id;
                    $model->course_id = $course_id;
                    $model->status=1;
                    $model->note = 'Ghi danh từ tháp đào tạo';
                    $model->save();
                }
            }
            elseif ($course_type==2) {
                if (OfflineRegister::where(['user_id' => $user_id, 'course_id' => $course_id])->exists())
                    $error = true;
                else{
                    $class_default = OfflineCourseClass::where(['course_id'=>$course_id,'default'=>1])->first();

                    $model = OfflineRegister::firstOrCreate(['user_id'=>$user_id, 'course_id'=>$course_id, 'class_id'=>$class_default->id]);
                    $model->user_id = $user_id;
                    $model->course_id = $course_id;
                    $model->class_id = $class_default->id;
                    $model->status=1;
                    $model->note = 'Ghi danh từ tháp đào tạo';
                    $model->save();
                }
            }
        }
        if ($error)
            json_result([
                'status' => 'error',
                'message' => 'Bạn đã ghi danh khóa học này rồi!'
            ]);
        json_result([
            'status' => 'success',
            'message' => 'Ghi danh thành công'
        ]);
    }

    public function getSubjectRegister(Request $request)
    {
        $user_id = profile()->user_id;
        $search = $request->input('search');
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);
            //Profile::addGlobalScope(new DraftScope('user_id'));
        $prefix = \DB::getTablePrefix();
        $query = SubjectRegister::query();
        $query->select('el_subject_register.*',\DB::raw("concat(".$prefix."b.lastname,' ',".$prefix."b.firstname) as full_name"),'c.name as subject','c.code');
        $query->from('el_subject_register')->join('el_profile as b','el_subject_register.user_id','b.user_id')
            ->join('el_subject as c','el_subject_register.subject_id','c.id')
            ->where('el_subject_register.user_id','=',$user_id);
        if ($search) {
            $query->where(function ($sub_query) use ($search) {
                $sub_query->orWhere('c.name', 'like', '%' . $search . '%');
                $sub_query->orWhere('c.code', 'like', '%'. $search .'%');
            });
        }
        $count = $query->count();
        $query->orderBy( $sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row) {
            $row->created_date = get_date($row->created_at,'d/m/Y H:i:s');
            $row->status_name = $row->status==1?'Đã đăng ký':'Hủy đăng ký';
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function updateSubjectRegister(Request $request)
    {
        $model = SubjectRegister::findOrFail($request->id);
        $model->status=2;
        $model->save();
        json_message('Hủy thành công');
    }

    public function getChildLevelSubjectByTitleCategory(Request $request){
        $cate_id = $request->id;
        $start_date = $request->start_date;

        $childs_level_subject = TrainingByTitleDetail::query()
            ->from('el_training_by_title_detail as a')
            ->leftJoin('el_subject as b', 'b.id','=', 'a.subject_id')
            ->leftJoin('el_level_subject as c', 'c.id','=', 'b.level_subject_id')
            ->where('a.training_title_category_id', '=', $cate_id)
            ->groupBy(['c.id', 'c.name'])
            ->get(['c.id', 'c.name']);

        foreach ($childs_level_subject as $child){
            $child->cate_id = $cate_id;
            $child->start_date = $start_date;
        }

        return view('user::frontend.training_by_title.tree_child_level_subject', [
            'childs_level_subject' => $childs_level_subject,
            'cate_id' => $cate_id
        ]);
    }

    public function getChildTrainingByTitleCategory(Request $request){
        $cate_id = $request->id;
        $lv_subject_id = $request->lv_subject_id;
        $start_date = $request->start_date;

        $model = TrainingByTitleDetail::query();
        $model->select([
            'a.*'
        ]);
        $model->from('el_training_by_title_detail as a');
        $model->Join('el_subject as b', 'b.id', '=', 'a.subject_id');
        $model->where('b.level_subject_id', $lv_subject_id);
        $model->where('a.training_title_category_id', $cate_id);
        $getChilds = $model->get();

        foreach ($getChilds as $child){
            $end_date = Carbon::parse($start_date)->addDays($child->num_date)->format('d/m/Y');

            $child->start_date = get_date($start_date);
            $child->end_date = $end_date;

            // $count_course_by_subject = CourseView::whereSubjectId($child->subject_id)->whereStatus(1)->groupBy(['subject_id'])->count();
            // $count_course_completed_by_subject = UserCompletedSubject::whereSubjectId($child->subject_id)->whereUserId(profile()->user_id)->groupBy(['subject_id'])->count();
            $check_finish_subject = UserCompletedSubject::whereSubjectId($child->subject_id)->whereUserId(profile()->user_id)->exists();
            if($check_finish_subject) {
                $child->percent_subject = 100;
            } else {
                $child->percent_subject = 0;
            }

            // $child->percent_subject = ($count_course_completed_by_subject/($count_course_by_subject > 0 ? $count_course_by_subject : 1)) * 100;
            $child->has_course = $this->checkCourseSubject($child->subject_id);
        }

        return view('user::frontend.training_by_title.tree_child', [
            'childs' => $getChilds,
        ]);
    }

    public function violateGetData(Request $request){
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'asc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $shedule = OfflineSchedule::query()
            ->select(['a.course_id', 'b.user_id'])
            ->from('el_offline_schedule as a')
            ->leftJoin('el_offline_register as b', 'b.course_id', '=', 'a.course_id')
            ->leftJoin('el_offline_course as c', 'c.id', '=', 'a.course_id')
            ->where('b.user_id',profile()->user_id);

        $shedule->whereNotExists(function (Builder $builder) {
            $builder->select(['id'])
                ->from('el_offline_attendance as att')
                ->whereColumn('att.schedule_id', '=', 'a.id')
                ->whereColumn('att.user_id', '=', 'b.user_id')
                ->whereColumn('att.course_id', '=', 'b.course_id');
        });
        $list_shedule = $shedule->get();

        $user_arr = [];
        $course_arr = [];
        foreach ($list_shedule as $item){
            $user_arr[] = $item->user_id;
            $course_arr[] = $item->course_id;
        }

        $query = OfflineRegisterView::query();
        $query->select([
            'a.id',
            'a.user_id',
            'a.course_id',
            'a.note',
            'course.code as course_code',
            'course.name as course_name',
            'course.course_time',
            'course.start_date',
            'course.end_date',
            'c.name as unit_type_name',
            'd.name as area_name_unit'
        ]);
        $query->from('el_offline_register_view as a');
        $query->leftJoin('el_offline_course as course', 'course.id', '=', 'a.course_id');
        $query->leftjoin('el_unit as b','b.id','=','a.unit_id');
        $query->leftjoin('el_unit_type as c','c.id','=','b.type');
        $query->leftjoin('el_area as d','d.id','=','b.area_id');
        $query->where('a.status', '=', 1);
        $query->where('a.user_id',profile()->user_id);
        $query->where(function ($sub) use ($user_arr, $course_arr){
            $sub->orWhereNotExists(function (Builder $builder) {
                $builder->select(['id'])
                    ->from('el_offline_course_complete as occ')
                    ->whereColumn('occ.user_id', '=', 'a.user_id')
                    ->whereColumn('occ.course_id', '=', 'a.course_id');
            });
            $sub->orWhere(function ($sub2) use ($user_arr, $course_arr){
                $sub2->whereIn('a.course_id', $course_arr);
                $sub2->whereIn('a.user_id', $user_arr);
            });
        });

        $count = $query->count();
        $query->orderBy('a.'.$sort, 'ASC');
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();

        foreach ($rows as $row){
            $profile = ProfileView::whereUserId($row->user_id)->first();
            $course = OfflineCourse::find($row->course_id);

            $row->user_code = $profile->code;
            $row->full_name = $profile->full_name;
            $row->email = $profile->email;
            $row->phone = $profile->phone;
            $row->unit_name_1 = $profile->unit_name;
            $row->unit_name_2 = $profile->parent_unit_name;
            $row->position_name = $profile->position_name;
            $row->start_date = get_date($row->start_date);
            $row->end_date = get_date($row->end_date);

            $schedules = OfflineSchedule::query()
                ->select([
                    'a.end_time',
                    'a.lesson_date',
                    'b.absent_id',
                    'b.absent_reason_id',
                    'b.discipline_id',
                ])
                ->from('el_offline_schedule as a')
                ->leftJoin('el_offline_attendance as b', 'b.schedule_id', '=', 'a.id')
                ->where('a.course_id', '=', $row->course_id)
                ->where('b.register_id', '=', $row->id)
                ->get();
            foreach ($schedules as $schedule){
                if (get_date($schedule->end_time, 'H:i:s') <= '12:00:00'){
                    $row->time_schedule .= 'Sáng '. get_date($schedule->lesson_date) .'; ';
                }else{
                    $row->time_schedule .= 'Chiều '. get_date($schedule->lesson_date) .'; ';
                }

                if ($schedule->absent_id != 0 || $schedule->absent_reason_id != 0 || $schedule->discipline_id != 0){
                    if (get_date($schedule->end_time, 'H:i:s') <= '12:00:00'){
                        $row->schedule_discipline .= 'Sáng '. get_date($schedule->lesson_date) .'; ';
                    }else{
                        $row->schedule_discipline .= 'Chiều '. get_date($schedule->lesson_date) .'; ';
                    }

                    $discipline = Discipline::find($schedule->discipline_id);
                    $absent = Absent::find($schedule->absent_id);
                    $absent_reason = AbsentReason::find($schedule->absent_reason_id);
                    $row->discipline = $discipline ? $discipline->name.'; ' : '';
                    $row->absent = $absent ? $absent->name.'; ' : '';
                    $row->absent_reason = $absent_reason ? $absent_reason->name.'; ' : '';
                }
            }

            $row->attendance = $schedules->count();
            $row->result = 'Không đạt';

            switch ($profile->status_id) {
                case 0:
                    $row->status_user = trans('backend.inactivity'); break;
                case 1:
                    $row->status_user = trans('backend.doing'); break;
                case 2:
                    $row->status_user = trans('backend.probationary'); break;
                case 3:
                    $row->status_user = trans('backend.pause'); break;
            }

        }
        json_result(['total' => $count, 'rows' => $rows]);
    }
    public function updateNotifyMessage(Request $request, $user_id, $room_id)
    {
        if (profile()->user_id<>$user_id)
            return false;
        Message::where(['room'=>$room_id,'to'=>$user_id])->update(['seen'=>1]);
    }

    // LƯU CHỨNG CHỈ
    public function saveMyCertificate(Request $request)
    {
        $this->validateRequest([
            'time_start' => 'required',
            'date_license' => 'required',
            'certificate' => 'required_if:path_old,==,null|mimes:jpg,png'
        ],$request, MyCertificate::getAttributeName());

        if ($request->path_old) {
            $new_path = $request->path_old;
        } else {
            $file = $request->file('certificate');
            $type = 'file';
            $filename = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $new_filename = Str::slug(basename($filename, "." . $extension)) . '-' . time() . '.' . $extension;

            $storage = \Storage::disk('upload');
            $new_path = $storage->putFileAs(date('Y/m/d'), $file, $new_filename);
        }

        $model = MyCertificate::firstOrNew(['id' => $request->id]);
        $model->user_id = profile()->user_id;
        $model->name_certificate = $request->name_certificate;
        $model->name_school = $request->name_school;
        $model->rank = $request->rank;
        $model->time_start = get_date($request->time_start, 'Y-m-d');
        $model->date_license = get_date($request->date_license, 'Y-m-d');
        $model->score = $request->score;
        $model->result = $request->result;
        $model->note = $request->note;
        $model->certificate = $new_path;
        $model->save();

        json_result([
            'status' => 'success',
            'message' => trans('laother.successful_save'),
            'redirect' => route('module.frontend.user.my_certificate')
        ]);
    }

    // LẤY DỮ LIỆU CHỨNG CHỈ CỦA HỌC VIÊN
    public function getDataMyCertificate(Request $request)
    {
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = MyCertificate::query();
        $query->where('user_id', profile()->user_id);
        $count = $query->count();

        $query->orderBy('id');
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();
        foreach ($rows as $row) {
            $row->time_start = get_date($row->time_start, 'd/m/Y');
            $row->date_license = get_date($row->date_license, 'd/m/Y');
            $row->edit_url = route('module.frontend.user.chart_course', ['id' => $row->id]);
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    // XÓA DỮ LIỆU CHỨNG CHỈ HỌC VIÊN
    function removeMyCertificate(Request $request)
    {
        $ids = $request->input('ids', null);
        foreach ($ids as $id){
            MyCertificate::find($id)->delete();
        }
        json_result([
            'status' => 'success',
            'message' => trans('laother.delete_success'),
        ]);
    }

    // LẤY HÌNH ẢNH CHỨNG CHỈ
    public function getImgMyCertificate(Request $request)
    {
        $certificate = MyCertificate::find($request->id);
        $img = image_file($certificate->certificate);
        json_result([
            'status' => 'success',
            'img' => $img,
        ]);
    }

    // DỮ LIỆU QUÁ TRÌNH CÔNG TÁC
    public function getDataWorkingProcess(Request $request)
    {
        $user_id = profile()->user_id;

        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = WorkingProcess::query();
        $query->select([
            'a.*',
            'b.name as title_name',
            'c.name as unit_name',
            'd.email'
        ]);
        $query->from('el_working_process as a');
        $query->leftJoin('el_titles as b', 'b.code', '=', 'a.title_code');
        $query->leftJoin('el_unit as c', 'c.code', '=', 'a.unit_code');
        $query->leftJoin('el_unit as e', 'e.code', '=', 'c.parent_code');
        $query->leftJoin('el_profile as d', 'd.user_id', '=', 'a.user_id');
        $query->where('a.user_id', '=', profile()->user_id);

        $count = $query->count();
        $query->orderBy('a.'.$sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row) {
            $row->code = Profile::usercode($user_id);
            $row->fullname = Profile::fullname($user_id);
            $row->edit_url = route('module.backend.working_process.edit', ['user_id' => $user_id, 'id' => $row->id]);
            $row->start_date = get_date($row->start_date);
            $row->end_date = get_date($row->end_date);
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }


    public function viewCourse() {
        $user = \profile();

        return view('user::frontend.my_capabilities.index', [
            'user' => $user,
        ]);
    }

    public function chartCourse(Request $request){
        $user_id = profile()->user_id;
        $data = [];

        $data[] = [
            'Tháng',
            'Đánh giá hiện tại',
            'Đánh giá cũ',
        ];

        for ($i = 1; $i <= 12; $i++){
            $course_now = CapabilitiesResult::getCourseNowByMonth($user_id, $i);
            $course_old = CapabilitiesResult::getCourseOldByMonth($user_id, $i);
            $data[] = [
                ($i%2 != 0) ? 'T'.$i : '',
                $course_now,
                $course_old
            ];
        }

        return \response()->json($data);
    }
}
