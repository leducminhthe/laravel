<?php

namespace Modules\Offline\Entities;

use App\Models\BaseModel;
use App\Models\Categories\TrainingLocation;
use App\Models\CourseBookmark;
use App\Models\CourseView;
use App\Models\Profile;
use App\Traits\ChangeLogs;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;
use Modules\Online\Entities\OnlineObject;
use Modules\PlanApp\Entities\PlanAppTemplate;
use phpDocumentor\Reflection\Types\Self_;

/**
 * Modules\Offline\Entities\OfflineCourse
 *
 * @property int $id
 * @property string $code
 * @property string $name
 * @property int|null $unit_id
 * @property int|null $in_plan
 * @property int|null $training_form_id
 * @property int|null $plan_detail_id
 * @property string|null $description
 * @property int $isopen
 * @property int $status
 * @property string $start_date
 * @property string $end_date
 * @property string|null $register_deadline
 * @property string|null $image
 * @property int $max_student
 * @property string|null $document
 * @property int $created_by
 * @property int $updated_by
 * @property int $subject_id
 * @property int|null $training_location_id
 * @property string|null $training_unit
 * @property int|null $training_area_id
 * @property int|null $training_partner_id
 * @property int|null $training_program_id
 * @property string|null $content
 * @property int $views
 * @property int|null $category_id
 * @property int|null $course_time
 * @property int|null $num_lesson
 * @property int $action_plan
 * @property int|null $plan_app_template
 * @property int|null $plan_app_day
 * @property int|null $cert_code
 * @property int|null $has_cert
 * @property int|null $teacher_id
 * @property int|null $rating
 * @property int|null $template_id
 * @property bool|null $commit
 * @property string|null $commit_date
 * @property float|null $coefficient
 * @property float|null $cost_class
 * @property int|null $quiz_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineCourse newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineCourse newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineCourse query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineCourse whereActionPlan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineCourse whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineCourse whereCertCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineCourse whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineCourse whereCoefficient($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineCourse whereCommit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineCourse whereCommitDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineCourse whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineCourse whereCostClass($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineCourse whereCourseTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineCourse whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineCourse whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineCourse whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineCourse whereDocument($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineCourse whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineCourse whereHasCert($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineCourse whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineCourse whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineCourse whereInPlan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineCourse whereIsopen($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineCourse whereMaxStudent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineCourse whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineCourse whereNumLesson($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineCourse wherePlanAppDay($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineCourse wherePlanAppTemplate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineCourse wherePlanDetailId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineCourse whereQuizId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineCourse whereRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineCourse whereRegisterDeadline($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineCourse whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineCourse whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineCourse whereSubjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineCourse whereTeacherId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineCourse whereTemplateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineCourse whereTrainingAreaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineCourse whereTrainingFormId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineCourse whereTrainingLocationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineCourse whereTrainingPartnerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineCourse whereTrainingProgramId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineCourse whereTrainingUnit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineCourse whereUnitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineCourse whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineCourse whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineCourse whereViews($value)
 * @mixin \Eloquent
 * @property-read \App\Models\CourseBookmark|null $bookmarked
 * @property-read \Modules\Promotion\Entities\PromotionCourseSetting|null $pointSetting
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Offline\Entities\OfflineRegister[] $register
 * @property-read int|null $register_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Offline\Entities\OfflineRegister[] $result
 * @property-read int|null $result_count
 * @property int $level_subject_id
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Offline\Entities\OfflineObject[] $onlineObjects
 * @property-read int|null $online_objects_count
 * @property-read PlanAppTemplate|null $planAppTemplate
 * @property-read TrainingLocation|null $training_location
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineCourse whereLevelSubjectId($value)
 */
class OfflineCourse extends BaseModel
{
    use ChangeLogs, Cachable;
    protected $table = 'el_offline_course';
    protected $table_name = 'Khóa học tập trung';
    protected $casts = [
        'coefficient' => 'float',
    ];
    protected $fillable = [
        'code',
        'name',
        'unit_id',
        'category_id',
        'description',
        'subject_id',
        'image',
        'training_program_id',
        'level_subject_id',
        'register_deadline',
        'content',
        'course_time',
        'plan_detail_id',
        'document',
        'num_lesson',
        'action_plan',
        'plan_app_template',
        'plan_app_day',
        'cert_code',
        'has_cert',
        'start_date',
        'end_date',
        'created_at',
        'created_by',
        'teacher_id',
        'rating',
        'template_id',
        'commit',
        'commit_date',
        'coefficient',
        'training_location_id',
        'training_unit',
        'in_plan',
        'quiz_id',
        'entrance_quiz_id',
        'register_quiz_id',
        'training_form_id',
        'unit_by',
        'max_grades',
        'min_grades',
        'course_employee',
        'course_action',
        'title_join_id',
        'title_recommend_id',
        'training_area_id',
        'max_student',
        'training_partner_id',
        'training_object_id',
        'teacher_type_id',
        'training_type_id',
        'lock_course',
        'enter_student_cost',
        'cost_class',
        'rating_end_date',
        'role_id',
        'approved_step',
        'course_time_unit',
        'views',
        'status',
        'training_partner_type',
        'training_unit_type',
        'color',
        'i_text',
        'b_text',
        'link_go_course',
        'plan_app_day_student',
        'plan_app_day_manager',
        'convert_course_plan',
        'template_rating_teacher_id',
        'survey_register',
    ];
    protected $primaryKey = 'id';

    public static function getAttributeName() {
        return [
            'code' => trans('lacourse.course_code'),
            'name' => trans('lacourse.course_name'),
            'category_id' => 'Cấp cha',
            'description' => trans("latraining.description"),
            'subject_id' => trans('laprofile.subject'),
            'training_program_id' => trans('latraining.training_program'),
            'content' => trans("latraining.content"),
            'course_time' => trans('lareport.duration'),
            'num_lesson' => 'Bài học',
            'action_plan' => 'Đánh giá hiệu quả đào tạo',
            'plan_app_template' => 'Mẫu Đánh giá hiệu quả đào tạo',
            'plan_app_day' => 'Thời gian thực hiện Đánh giá hiệu quả đào tạo',
            'cert_code' => trans('lamenu.certificate'),
            'has_cert' => 'Chứng chỉ',
            'start_date' => trans('latraining.start_date'),
            'end_date' => trans('latraining.end_date'),
            'created_at' => trans("latraining.created_at"),
            'register_deadline' => 'Hạn đăng ký',
            'training_evaluation' => 'Đào tạo đánh giá',
            'teacher_evaluation' => 'Giảng viên đánh giá',
            'teacher_id' => trans('latraining.teacher'),
            'rating' => 'Đánh giá sau khóa học',
            'template_id' => 'Mẫu đánh giá',
            'commit'=>"Cam kết đào tạo",
            'commit_date'=>'Ngày cam kết',
            'coefficient'=>'Hệ số',
            'training_location_id' =>'Địa điểm đào tạo',
            'training_unit' =>'Đơn vị đào tạo',
            'in_plan' =>'Trong kế hoạch',
            'quiz_id' => trans('latraining.quiz_code'),
            'training_form_id' => trans('latraining.training_form'),
        ];
    }

    public static function getItems (){
        $query = self::query();
        $query->where('status', '=', 1);
        $query->where('isopen', '=', 1);
        return $query->paginate(20);
    }

    public static function updateItemViews($id, $views){
        self::whereId($id)->update([
            'views' => ($views + 1)
        ]);
    }

    public static function getCourseCategory($training_program_id, $current_id = 0){
        $query = self::query();
        $query->where('training_program_id', '=', $training_program_id);
        $query->where('id', '!=', $current_id);
        return $query->get();
    }

    public static function getNewCourse($length = 8){
        $query = self::query();
        $query->where('status', '=', 1);
        $query->where('isopen', '=', 1);
        $query->orderBy('id', 'DESC');
        $query->limit($length);
        return $query->get();
    }

    public static function getStatusRegisterText($status) {
        switch ($status) {
            case 1: return trans('laother.register');
            case 2: return trans('laother.expired_registration');
            case 3: return trans('laother.finished');
            case 4: return trans('latraining.go_course');
            case 5: return trans('latraining.not_approved');
            case 6: return trans('latraining.deny');
            case 7: return trans('laother.unopened');
        }
    }

    public static function getBtnClassStatusRegister($status) {
        switch ($status) {
            case 1: return 'success';
            case 2: return 'danger';
            case 3: return 'danger';
            case 4: return 'success';
            case 5: return 'warning';
            case 6: return 'danger';
            case 7: return 'info';
        }
    }

    public function countUserRegister() {
        $query = OfflineRegister::query();
        $query->where('course_id', '=', $this->id);
        $query->where('status', '=', 1);
        return $query->count();
    }

    public function getObject() {
        $query = OnlineObject::query()
            ->from('el_offline_object AS a')
            ->leftJoin('el_titles AS b', 'b.id', '=', 'a.title_id')
            ->leftJoin('el_unit as c', 'c.id', '=', 'a.unit_id')
            ->where('a.course_id', '=', $this->id)
            ->get([
                'b.name as title_name',
                'c.name as unit_name'
            ]);

        $obj = [];
        foreach ($query as $item){
            if ($item->title_name){
                $obj[] = $item->title_name;
            }
            if ($item->unit_name){
                $obj[] = $item->unit_name;
            }
        }

        return implode(', ', $obj);
    }

    public function countRatingStar() {
        return OfflineRating::where('course_id', '=', $this->id)
            ->count();
    }

    public function avgRatingStar() {
        $count = $this->countRatingStar();
        $total = OfflineRating::query()->where('course_id', '=', $this->id)->sum('num_star');

        return $count > 0 ? round($total / $count,1) : 0;
    }

    public function isComplete() {
        $user_id = profile()->user_id;
        $query = OfflineResult::where('course_id', '=', $this->id)
            ->where('user_id', '=', $user_id)
            ->where('result', '=', 1);
        return $query->exists();
    }

    public static function checkCompleteCourse($course_id, $user_id) {
        $query = OfflineResult::query();
        return $query->where('course_id', '=', $course_id)
            ->where('user_id', '=', $user_id)
            ->where('result', '=', 1)
            ->exists();
    }

    public function getStatusRegister() {
        $nowdate = date('Y-m-d H:i:s');
        if ($this->end_date < $nowdate) {
            return 3;
        }

        $user_id = profile()->user_id;
        $registed = OfflineRegister::where('user_id', '=', $user_id)
            ->where('course_id', '=', $this->id)
            ->first();

        if ($registed) {
            if ($registed->status == 1) {
                if ($this->start_date > $nowdate) {
                    return 7;
                }

                if ($this->start_date < $nowdate && $this->end_date > $nowdate) {
                    return 4;
                }
            }

            if ($registed->status == 2) {
                return 5;
            }

            if ($registed->status == 0) {
                return 6;
            }
        }

        if (empty($this->register_deadline)){
            return 1;
        }else{
            if ($this->register_deadline > $nowdate) {
                return 1;
            }

            if ($this->register_deadline < $nowdate) {
                return 2;
            }
        }

        return 0;
    }

    public function getStatusCourse() {
        $user_id = profile()->user_id;
        $now = date('Y-m-d H:i:s');

        /* chưa học */
        if ($this->start_date > $now || !OfflineRegister::checkExists($user_id, $this->id, 1))
        {
            return 0;
        }

        if (OfflineRegister::checkExists($user_id, $this->id, 1)){
            /* Đã học */
            if ($this->isComplete()) {
                return 1;
            }
            /* Đang học */
            if ($this->end_date > $now) {
                return 2;
            }

            /* Kết thúc khóa học, HV chưa hoàn thành */
            return 3;
        }
    }

    public static function getMyCourse($userId = null)
    {
        $userId = $userId ? $userId : profile()->user_id;
        $prefix = \DB::getTablePrefix();
        $query = CourseView::query()
            ->from('el_course_view as a')
            ->select(['a.*','c.status as plan_app_status','c.start_date as start_evaluation'])
            ->join('el_course_register_view as b',function($join){
                $join->on('a.course_id','=','b.course_id');
                $join->on('a.course_type','=','b.course_type');
            })
            ->leftJoin('el_plan_app as c',function ($join){
                $join->on('c.course_id', '=', 'a.course_id');
                $join->on('c.course_type', '=', 'a.course_type');
                $join->on('c.user_id', '=', 'b.user_id');
            })
            ->where('b.user_id','=', $userId)
            ->where('a.status', '=', 1)
            ->where('b.status', '=', 1)
            ->where('a.isopen', '=', 1)
            ->where('a.course_type',2)
            ->where('a.offline', '=', 0)
            ->limit('5')
            ->orderBy('a.start_date', 'desc');
        return $query->get();
    }

    public static function countCourse()
    {
        $query = self::query();
        $query->where('status', '=', 1);
        $query->where('isopen', '=', 1);
        return $query->count();
    }

    public static function getLastestCourse($limit = 5){
        $query = self::query();
        $query->where('isopen', 1);
        $query->where('status', 1);
        $query->orderBy('created_at', 'DESC');
        $query->limit($limit);
        return $query->get();
    }

    public static function percent($course_id, $user_id)
    {
        $register = OfflineRegister::where('course_id', '=', $course_id)
            ->where('user_id', '=', $user_id)
            ->where('status', '=', 1)
            ->first();

        $schedule = OfflineSchedule::where('course_id', '=', $course_id)->count();
        $attendance = isset($register) ? OfflineAttendance::where('register_id', '=', $register->id)->sum('percent') : 0;

        $percent = $schedule ? ($attendance / $schedule) : 0;

        return $percent;
    }

    public function getStatus()
    {
        $result = $this->result()->wherePivot('user_id',auth()->id());
        $startDate = $this->start_date;
        $endDate = $this->end_date;
        if ($this->register()->exists()){
            if ($startDate > now()){
                $status = "Đã đăng ký";
            }else {
                if($endDate && $endDate < now()){
                    if ($result->exists()) {
                        $status = "Đã học (Đã có kết quả,Khóa đã kết thúc)";
                    } else
                        $status = "Đang học (Chưa có kết quả,Khóa đã kết thúc)";
                }elseif($endDate){
                    if ($result->exists()) {
                        $status = "Đã học (Đã có kết quả,Chưa kết thúc khóa)";
                    } else
                        $status = "Đang học (Chưa có kết quả,Chưa kết thúc khóa)";
                }else{
                    if ($result->exists()) {
                        $status = "Đã học (Đã có kết quả)";
                    } else
                        $status = "Đang học (Chưa có kết quả)";
                }
            }
        }else{
            $status = "Chưa đăng ký";
        }
        return $status;
    }

    public function pointSetting()
    {
        return $this->hasOne('Modules\Promotion\Entities\PromotionCourseSetting','course_id','id')->where('type','1');
    }

    public function register()
    {
        return $this->hasMany('Modules\Offline\Entities\OfflineRegister','course_id','id')
            ->where('status', '1');
            // ->where('user_id',auth()->id());
    }

    public function bookmarked()
    {
        return $this->hasOne('App\Models\CourseBookmark','course_id','id')
            ->where('user_id','=',auth()->id())
            ->where('type',2);
    }

    public function result()
    {
        return $this->belongsToMany('Modules\Offline\Entities\OfflineRegister','el_offline_result','course_id','register_id','id');
    }

    public function getCourseAttendance()
    {
        $query = self::query();
        return $query->select(['id','code','name','status','start_date','end_date'])
            ->where('status', '=', 1)
            ->where('start_date', '<=', date('Y-m-d'))
            ->orderBy('start_date','desc')
            ->get();
    }

    public function training_location()
    {
        return $this->belongsTo(TrainingLocation::class);
    }

    public function onlineObjects()
    {
        return $this->hasMany(OfflineObject::class,'course_id');
    }

    public function planAppTemplate()
    {
        return $this->belongsTo(PlanAppTemplate::class,'plan_app_template');
    }

    public function checkPdf($id, $document_key) {
        $model = OfflineCourse::find($id);
        $check = $model->document;

        if (empty($check)) {
            return false;
        }
        $documents = json_decode($model->document);
        foreach ($documents as $key => $document) {
            if($document_key == $key) {
                $extention = pathinfo($document, PATHINFO_EXTENSION);
                if ($extention == 'pdf' || $extention == 'PDF') {
                    return true;
                }
            }
        }
        return false;
    }

    public function getLinkViewPdf($id,$document_key) {
        $model = OfflineCourse::find($id);
        $documents = json_decode($model->document);
        foreach ($documents as $key => $document) {
            if($document_key == $key) {
                if (!$this->checkPdf($id,$key)) {
                    return false;
                }
                return upload_file(explode('|', $document)[0]);
            }
        }
    }

    public static function checkSettingJoinCourse($course_id, $user_id){
        $check_setting_join_course = SettingJoinOfflineCourse::query()->where('course_id', $course_id)->exists();
        if($check_setting_join_course){
            $profile_user = Profile::where('user_id', $user_id)->first();
            $check_setting_join_course_by_title = SettingJoinOfflineCourse::query()->where('course_id', $course_id)->where('title_id', $profile_user->title_id)->first();
            if ($check_setting_join_course_by_title) {
                $date_join = ($profile_user->date_title_appointment ? $profile_user->date_title_appointment : ($profile_user->join_company ? $profile_user->join_company : ''));
                if ($date_join) {

                    $date = strtotime(date("Y-m-d", strtotime($date_join)) . " +{$check_setting_join_course_by_title->date_register} day");
                    if(strftime("%Y-%m-%d", $date) < date('Y-m-d')){
                        return [false, 'Ngày bổ nhiệm phải lớn hơn ngày hiện tại'];
                    }else{
                        $check_course_complete = explode(',', $check_setting_join_course_by_title->course_complete_id);
                        foreach ($check_course_complete as $item) {
                            $course_complete = OfflineCourseComplete::where('user_id', $user_id)->where('user_type', 1)->where('course_id', $item)->exists();
                            if(!$course_complete) {
                                $courseOffline = OfflineCourse::find($item,['name','code']);
                                return [false, 'Bạn phải hoàn thành khóa học '. $courseOffline->name .' trước'];
                                break;
                            }
                        }
                    }
                }
                // else {
                //     return [false, 'Ngày bổ nhiệm chức danh hoặc ngày tham gia công ty không tồn tại'];
                // }
            }
        }

        return [true];
    }
}
