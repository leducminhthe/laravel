<?php

namespace Modules\Online\Entities;

use App\Models\BaseModel;
use App\Models\CourseView;
use App\Models\PlanApp;
use App\Traits\ChangeLogs;
use App\Traits\MultiLang;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;
use App\Models\Profile;
use Modules\PlanApp\Entities\PlanAppTemplate;
use App\Models\OnlineCourseStatistic;

/**
 * Modules\Online\Entities\OnlineCourse
 *
 * @property int $id
 * @property string $code
 * @property string $name
 * @property int|null $unit_id
 * @property int|null $moodlecourseid
 * @property int $isopen
 * @property string|null $image
 * @property string $start_date
 * @property string|null $end_date
 * @property int $created_by
 * @property int $updated_by
 * @property int|null $category_id
 * @property string|null $description
 * @property int $subject_id
 * @property int|null $plan_detail_id
 * @property int|null $in_plan
 * @property int $training_program_id
 * @property string $register_deadline
 * @property string $content
 * @property string|null $document
 * @property string|null $course_time
 * @property int|null $num_lesson
 * @property int $status
 * @property int $views
 * @property int $action_plan
 * @property int|null $plan_app_template
 * @property int|null $plan_app_day
 * @property int|null $cert_code
 * @property int|null $has_cert
 * @property int|null $rating
 * @property int|null $template_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property mixed $lb_content
 * @property-read mixed $lb_raw_content
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineCourse newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineCourse newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineCourse query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineCourse whereActionPlan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineCourse whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineCourse whereCertCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineCourse whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineCourse whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineCourse whereCourseTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineCourse whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineCourse whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineCourse whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineCourse whereDocument($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineCourse whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineCourse whereHasCert($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineCourse whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineCourse whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineCourse whereInPlan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineCourse whereIsopen($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineCourse whereMoodlecourseid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineCourse whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineCourse whereNumLesson($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineCourse wherePlanAppDay($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineCourse wherePlanAppTemplate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineCourse wherePlanDetailId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineCourse whereRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineCourse whereRegisterDeadline($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineCourse whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineCourse whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineCourse whereSubjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineCourse whereTemplateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineCourse whereTrainingProgramId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineCourse whereUnitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineCourse whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineCourse whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineCourse whereViews($value)
 * @mixin \Eloquent
 * @property int $auto
 * @property int|null $training_form_id
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineCourse whereAuto($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineCourse whereTrainingFormId($value)
 * @property-read \App\Models\CourseBookmark|null $bookmarked
 * @property-read \Modules\Promotion\Entities\PromotionCourseSetting|null $pointSetting
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Online\Entities\OnlineRegister[] $register
 * @property-read int|null $register_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Online\Entities\OnlineRegister[] $result
 * @property-read int|null $result_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Online\Entities\OnlineCourseActivity[] $activities
 * @property-read int|null $activities_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Online\Entities\OnlineRegister[] $registers
 * @property-read int|null $registers_count
 * @property int $level_subject_id
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Online\Entities\OnlineObject[] $onlineObjects
 * @property-read int|null $online_objects_count
 * @property-read PlanAppTemplate|null $planAppTemplate
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Online\Entities\OnlineRegister[] $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourse whereLevelSubjectId($value)
 */
class OnlineCourse extends BaseModel
{
    use ChangeLogs, MultiLang, Cachable;

    protected $table = 'el_online_course';
    protected $table_name = 'Khóa học online';
    protected $fillable = [
        'code',
        'name',
        'unit_id',
        'category_id',
        'description',
        'subject_id',
        'image',
        'image_activity',
        'tutorial',
        'training_program_id',
        'level_subject_id',
        'register_deadline',
        'content',
        'course_time',
        'course_time_unit',
        'document',
        'num_lesson',
        'action_plan',
        'plan_app_template' ,
        'plan_app_day' ,
        'cert_code',
        'has_cert',
        'start_date',
        'end_date',
        'created_at',
        'training_evaluation',
        'teacher_evaluation',
        'teacher_id',
        'rating',
        'template_id',
        'moodlecourseid',
        'in_plan',
        'max_grades',
		'min_grades',
		'title_join_id',
		'title_recommend_id',
		'training_object_id',
		'is_limit_time',
		'start_timeday',
		'end_timeday',
        'lock_course',
        'rating_end_date',
        'auto',
        'approved_step',
        'course_action',
        'training_form_id',
        'views',
        'color',
        'i_text',
        'b_text',
        'plan_app_day_student',
        'plan_app_day_manager',
        'offline',
        'convert_course_plan',
        'survey_register',
        'entrance_quiz_id',
        'register_quiz_id',
    ];

    public function activities() {
        return $this->hasMany('Modules\Online\Entities\OnlineCourseActivity', 'course_id', 'id');
    }

    public function pointSetting() {
        return $this->hasOne('Modules\Promotion\Entities\PromotionCourseSetting','course_id','id')->where('type','0');
    }

    public function register() {
        return $this->hasMany('Modules\Online\Entities\OnlineRegister','course_id','id')
            ->where('status', '1');
    }

    public function bookmarked() {
        return $this->hasOne('App\Models\CourseBookmark','course_id','id')
            ->where('user_id','=',auth()->id())
            ->where('type',1);
    }

    public function result() {
        return $this->belongsToMany('Modules\Online\Entities\OnlineRegister','el_online_result','course_id','register_id','id');
    }

    public function registers() {
        return $this->hasMany('Modules\Online\Entities\OnlineRegister', 'course_id', 'id');
    }

    public function countUserRegister() {
        return $this->registers()->count(['id']);
    }

    public function onlineObjects()
    {
        return $this->hasMany(OnlineObject::class,'course_id');
    }

    public function getObject() {
        $query = OnlineObject::query();
        $rows = $query->from('el_online_object AS a')
            ->leftJoin('el_titles AS b', 'b.id', '=', 'a.title_id')
            ->leftjoin('el_unit as c', 'c.id', '=', 'a.unit_id')
            ->where('a.course_id', '=', $this->id)
            ->get([
                'b.name as title_name',
                'c.name as unit_name'
            ]);

        $obj = [];
        foreach ($rows as $item){
            if ($item->title_name){
                $obj[] = $item->title_name;
            }
            if ($item->unit_name){
                $obj[] = $item->unit_name;
            }
        }

        return implode(', ', $obj);
    }

    public function checkObject(){
        $profile = Profile::query()
            ->from('el_profile as profile')
            ->leftJoin('el_titles as title', 'title.code', '=', 'profile.title_code')
            ->leftJoin('el_unit as unit', 'unit.code', '=', 'profile.unit_code')
            ->leftJoin('el_area as area', 'area.code', '=', 'profile.area_code')
            ->where('profile.user_id', '=', profile()->user_id)
            ->where(function ($sub){
                $sub->orWhereIn('title.id', function ($obj){
                    $obj->select(['title_id'])
                        ->from('el_online_object')
                        ->where('course_id', '=', $this->id)
                        ->pluck('title_id')->toArray();
                });
                $sub->orWhereIn('unit.id', function ($obj){
                    $obj->select(['unit_id'])
                        ->from('el_online_object')
                        ->where('course_id', '=', $this->id)
                        ->pluck('unit_id')->toArray();
                });
                $sub->orWhereIn('area.id', function ($obj){
                    $obj->select(['area1'])
                        ->from('el_online_object')
                        ->where('course_id', '=', $this->id)
                        ->pluck('area1')->toArray();
                });
                $sub->orWhereIn('area.id', function ($obj){
                    $obj->select(['area2'])
                        ->from('el_online_object')
                        ->where('course_id', '=', $this->id)
                        ->pluck('area2')->toArray();
                });
                $sub->orWhereIn('area.id', function ($obj){
                    $obj->select(['area3'])
                        ->from('el_online_object')
                        ->where('course_id', '=', $this->id)
                        ->pluck('area3')->toArray();
                });
                $sub->orWhereIn('area.id', function ($obj){
                    $obj->select(['area4'])
                        ->from('el_online_object')
                        ->where('course_id', '=', $this->id)
                        ->pluck('area4')->toArray();
                });
            });

        return $profile->exists();
    }

    public function getLinkDownload() {
        return link_download('uploads/'.$this->document);
    }

    public function isFilePdf() {
        if (empty($this->document)) {
            return false;
        }

        $extention = pathinfo($this->document, PATHINFO_EXTENSION);
        if ($extention == 'pdf' || $extention == 'PDF') {
            return true;
        }

        return false;
    }

    public function countRatingStar() {
        return OnlineRating::where('course_id', '=', $this->id)
            ->count();
    }

    public function avgRatingStar() {
        $count = $this->countRatingStar();
        $total = OnlineRating::query()->where('course_id', '=', $this->id)->sum('num_star');

        return $count > 0 ? round($total / $count,1) : 0;
    }

    public function isComplete($user_id = null) {
        $user_id = empty($user_id) ? profile()->user_id: $user_id;
        return self::checkCompleteCourse($this->id, $user_id);
    }

    public function getStatusRegister() {
        $nowdate = date('Y-m-d H:i:s');
        if ($this->end_date && $this->end_date < $nowdate) {
            return 3;
        }

        $user_id = getUserId();
        $user_type = getUserType();
        $registed = OnlineRegister::where('user_id', '=', $user_id)
            ->where('user_type', '=', $user_type)
            ->where('course_id', '=', $this->id)
            ->first();

        if ($registed) {
            if ($registed->status == 1) {
                if ($this->start_date > $nowdate) {
                    return 7;
                }

                if ($this->start_date < $nowdate) {
                    if($this->end_date && $this->end_date > $nowdate) {
                        return 4;
                    }
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
        }else {
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
        $user_id = getUserId();
        $now = date('Y-m-d H:i:s');

        /* chưa học */
        if ($this->start_date > $now || !OnlineRegister::checkExists($user_id, $this->id, 1))
        {
            return 0;
        }

        if (OnlineRegister::checkExists($user_id, $this->id, 1)){
            /* Đã học */
            if ($this->isComplete()) {
                return 1;
            }

            if (isset($this->end_date)){
                /* Đang học */
                if ($this->end_date > $now) {
                    return 2;
                }else{
                    /* Kết thúc khóa học, HV chưa hoàn thành */
                    return 3;
                }
            }else{
                /* Đang học */
                return 2;
            }

        }
    }

    public function getActivities() {
        return $this->activities()
            ->where('status', '=', 1)
            ->orderBy('num_order', 'asc')
            ->get();
    }

    public function getActivitiesOfLesson($lesson_id) {
        return $this->activities()
            ->where('status', '=', 1)
            ->where('lesson_id', '=', $lesson_id)
            ->orderBy('num_order', 'asc')
            ->get();
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

    public static function getAttributeName() {
        return [
            'code' => trans('lacourse.course_code'),
            'name' => trans('lacourse.course_name'),
            'category_id' => 'Cấp cha',
            'description' => trans("latraining.description"),
            'subject_id' => 'Học phần',
            'training_program_id' => trans('latraining.training_program'),
            'register_deadline' => 'Hạn đăng ký',
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
            'training_evaluation' => 'Đào tạo đánh giá',
            'teacher_evaluation' => 'Giảng viên đánh giá',
            'teacher_id' => trans('lareport.teacher'),
            'rating' => 'Đánh giá sau khóa học',
            'template_id' => 'Mẫu đánh giá',
            'auto' => 'Duyệt khóa',
            'tutorial' => 'Hướng dẫn học',
            'training_form_id' => trans('latraining.training_form'),
        ];
    }

    public static function getItems (){
        $query = self::query();
        $query->where('status', '=', 1);
        $query->where('isopen', '=', 1);
        $query->orderByDesc('id');
        return $query->paginate(20);
    }

    public static function getStatusRegisterText($status) {
        switch ($status) {
            case 1: return trans('app.register');
            case 2: return trans('app.expired_registration');
            case 3: return trans('app.ended');
            case 4: return trans('app.come_in_class');
            case 5: return trans('app.unapproved');
            case 6: return trans('labutton.deny');
            case 7: return trans('app.unopened');
            default: return trans('app.ended');
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
            default: return 'danger';
        }
    }

    public static function updateItemViews($id, $views){
        self::whereId($id)->update([
            'views' => ($views + 1)
        ]);

        OnlineCourseStatistic::update_course_insert_statistic($id);
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

    public static function checkCompleteCourse($course_id, $user_id) {
        $query = OnlineResult::query();
        return $query->where('course_id', '=', $course_id)
            ->where('user_id', '=', $user_id)
            ->where('user_type', '=', getUserType())
            ->where('result', '=', 1)
            ->exists();
    }

    public static function getMyCourse($userId = null)
    {
        $userId = $userId ? $userId : getUserId();
        $prefix = \DB::getTablePrefix();
        $query = CourseView::query()
            ->select([
                'a.*',
                'c.status as plan_app_status',
                'c.start_date as start_evaluation'
            ])
            ->from('el_course_view as a')
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
            ->where('b.user_type','=', getUserType())
            ->where('a.status', '=', 1)
            ->where('b.status', '=', 1)
            ->where('a.isopen', '=', 1)
            ->where('a.course_type',1)
            ->where('a.offline', '=', 0)
            ->orderBy('a.start_date', 'desc')
            ->limit(5);

        return $query->get();
    }

    public static function countCourse()
    {
        $query = self::query();
        $query->where('status', '=', 1);
        $query->where('isopen', '=', 1);
        return $query->count();
    }

    public static function getLastestCourse($limit = 5)
    {
        $query = self::query();
        $query->orderBy('created_at', 'DESC');
        $query->where('status', 1);
        $query->where('isopen', 1);
        $query->limit($limit);
        return $query->get();
    }

    public static function getOnlineCourseByUser($user_id){
        $query = OnlineRegister::query()
            ->select(['b.*'])
            ->from('el_online_register as a')
            ->leftJoin('el_online_course as b', 'b.id', '=', 'a.course_id')
            ->where('a.status', '=', 1)
            ->where('b.status', '=', 1)
            ->where('b.isopen', '=', 1)
            ->where('b.offline', '=', 0)
            ->where('a.user_id', '=', $user_id)
            ->where(\DB::raw('month(start_date)'), '>=', date('m'))
            ->get();

        return $query;
    }

    public static function countUserRegisterOnline($user_id) {
        $query = OnlineRegister::query();
        $query->where('status', '=', 1);
        $query->where('user_id', '=', $user_id);
        return $query->count();
    }

    public static function percentCompleteCourseByUser($course_id, $user_id){
        $course = OnlineCourse::find($course_id);
        $activities = $course ? $course->getActivities() : [];

        $count_activities = (count($activities) > 0) ? $activities->count() : 1;
        $check_complete = 0;
        if (count($activities) > 0){
            foreach ($activities as $activity){
                $check = $activity->isComplete($user_id);
                if ($check){
                    $check_complete += 1;
                }
            }
        }


        $percent = ($check_complete / $count_activities) * 100;

        return $percent;
    }
    public function users() {
        return $this->belongsToMany(OnlineRegister::class,'el_online_course_complete','course_id','user_id','id','user_id','course_id');
    }
    public function planAppTemplate()
    {
        return $this->belongsTo(PlanAppTemplate::class,'plan_app_template');
    }

    public static function checkSettingJoinCourse($course_id, $user_id){
        $check_setting_join_course = SettingJoinOnlineCourse::query()->where('course_id', $course_id)->exists();
        if($check_setting_join_course){

            $profile_user = Profile::where('user_id', $user_id)->first(['date_title_appointment','join_company','title_id']);
            $check_setting_join_course_by_title = SettingJoinOnlineCourse::query()->where('course_id', $course_id)->where('title_id', $profile_user->title_id)->first();
            if ($check_setting_join_course_by_title) {

                $date_join = ($profile_user->date_title_appointment ? $profile_user->date_title_appointment : ($profile_user->join_company ? $profile_user->join_company : ''));
                if ($date_join) {

                    $date = strtotime(date("Y-m-d", strtotime($date_join)) . " +{$check_setting_join_course_by_title->date_register} day");
                    if(strftime("%Y-%m-%d", $date) < date('Y-m-d')){
                        return [false, 'Ngày bổ nhiệm phải lớn hơn ngày hiện tại'];
                    }else{
                        $check_course_complete = explode(',', $check_setting_join_course_by_title->course_complete_id);
                        foreach ($check_course_complete as $item) {
                            $course_complete = OnlineCourseComplete::where('user_id', $user_id)->where('user_type', 1)->where('course_id', $item)->first();
                            if(!isset($course_complete)) {
                                $courseOnline = OnlineCourse::find($item,['name','code']);
                                return [false, 'Bạn phải hoàn thành khóa học '. $courseOnline->name .' trước'];
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
