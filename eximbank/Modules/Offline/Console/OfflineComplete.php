<?php

namespace Modules\Offline\Console;

use App\Models\Automail;
use App\Models\Categories\Unit;
use App\Models\Categories\UnitType;
use App\Models\Config;
use App\Models\Categories\Area;
use App\Models\Categories\Position;
use App\Models\Categories\Subject;
use App\Models\Categories\SubjectType;
use App\Models\Categories\SubjectTypeObject;
use App\Models\Categories\SubjectTypeResult;
use App\Models\Categories\SubjectTypeSubject;
use App\Models\Categories\SubjectTypeUser;
use App\Models\Categories\TrainingForm;
use App\Models\Categories\TrainingType;
use App\Models\CourseComplete;
use App\Models\Profile;
use App\Models\ProfileView;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Offline\Entities\OfflineAttendance;
use Modules\Offline\Entities\OfflineCourse;
use Modules\Offline\Entities\OfflineCourseClass;
use Modules\Offline\Entities\OfflineCourseComplete;
use Modules\Offline\Entities\OfflineRegister;
use Modules\Offline\Entities\OfflineRegisterView;
use Modules\Offline\Entities\OfflineResult;
use Modules\PointHist\Entities\PointHist;
use Modules\Promotion\Entities\PromotionCourseSetting;
use Modules\Promotion\Entities\PromotionLevel;
use Modules\Promotion\Entities\PromotionUserHistory;
use Modules\Promotion\Entities\PromotionUserPoint;
use Modules\Quiz\Entities\QuizResult;
use Modules\RefererHist\Entities\RefererRegisterCourse;
use Modules\ReportNew\Entities\BC15;
use Modules\ReportNew\Entities\ReportNewExportBC05;
use Modules\TrainingRoadmap\Entities\TrainingRoadmap;
use Modules\TrainingRoadmap\Entities\TrainingRoadmapFinish;
use Modules\User\Entities\TrainingProcess;
use Modules\User\Entities\UserCompletedSubject;
use Modules\UserMedal\Entities\UserMedalResult;
use Modules\UserMedal\Entities\UserMedalSettings;
use Modules\UserMedal\Entities\UserMedalSettingsItems;
use Modules\UserPoint\Entities\UserPointSettings;
use Modules\UserPoint\Entities\UserPointResult;
use Modules\UserMedal\Entities\UserMedalObject;

class OfflineComplete extends Command
{
    protected $signature = 'command:offline_complete {user_id?} {course_id?}';

    protected $description = 'chạy hoàn thành khóa học offline 1 phút 1 lần (* * * * *)';

    protected $expression = "* * * * *";
    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $param_user_id = $this->argument('user_id');
        $param_course_id = $this->argument('course_id');

        $query = DB::query();
        $query->select([
            'register.id',
            'register.user_id',
            'course.id AS course_id',
            'course.quiz_id',
            'course.subject_id',
            'condition.minscore',
            'course.level_subject_id',
            'register.title_id',
            'register.class_id'
        ]);

        $query->from('el_offline_register_view AS register')
            ->join('el_offline_course AS course', 'course.id', '=', 'register.course_id')
            ->leftjoin('el_offline_condition AS condition', 'condition.course_id', '=', 'course.id')
            ->where('course.status', '=', 1)
            ->where('register.status', '=', 1);
        if($param_user_id && $param_course_id){
            $query = $query->where('register.user_id', '=', $param_user_id)->where('register.course_id', '=', $param_course_id);
        }else{
            $query = $query->where('register.cron_complete', '!=', 1);
        }

        $rows = $query->get();
        foreach ($rows as $row) {
            $grade = QuizResult::where('quiz_id', '=', $row->quiz_id)
                ->where('user_id', '=', $row->user_id)
                ->whereNull('text_quiz')
                ->where('type', '=', 1);

            $model = OfflineResult::firstOrNew(['register_id' => $row->id]);
            $model->register_id = $row->id;
            $model->course_id = $row->course_id;
            $model->user_id = $row->user_id;
            $model->percent = OfflineResult::getPercent($row->id);
            $model->pass_score = $row->minscore;

            if ($grade->exists()) {
                $score = $grade->latest()->first();
                $score = isset($score->reexamine) ? $score->reexamine : (isset($score->grade) ? $score->grade : 0);

                $model->score_1 = $score;
                $model->score = $model->score_2 ?? $score;
            }
            $model->save();

            $model->updateResult();
            $this->updateTrainingProcess($row->user_id,$row->course_id,$model->score,$model->result);
            $this->updateReportNew05($model,$row->class_id);
            //$this->setUserPromotionPoint($row,$model);
            $this->updateCompleteCronUser($row->id);

            $this->updateCertificateTraining($row->subject_id, $row->user_id, $model->result, $model->created_at);

            if ($model->result == 1){
                $this->updateCompleteCourse($row->user_id,$row->course_id);
                $this->updateUserCompletedSubject($row->user_id,$row->subject_id,$row->course_id);
                $this->updateCompletedRoadmapByTitle($row->subject_id, $row->title_id,$row->level_subject_id);
                $this->pointPromotion($row->user_id,$row->course_id, $model);
                $this->pointUserMedal($row->user_id,$row->course_id, $model->score);
                $this->updateSendEmailUserCompleted($row->course_id);
            }
        }
    }
    private function updateCompleteCourse($user_id,$course_id){
        OfflineCourseComplete::updateOrCreate([
            'user_id' => $user_id,
            'course_id' => $course_id
        ],[
            'user_id' => $user_id,
            'course_id' => $course_id
        ]);
        CourseComplete::updateOrCreate([
            'user_id' => $user_id,
            'course_id' => $course_id,
            'course_type'=>2
        ],[
            'user_id' => $user_id,
            'course_id' => $course_id,
            'course_type'=>2
        ]);
    }
    private function updateCompleteCronUser($register_id){
        $offlineResult = OfflineRegister::find($register_id);
        $offlineResult->cron_complete = 1;
        $offlineResult->save();
    }
    private function updateCompletedRoadmapByTitle($subject_id, $title_id,$level_subject_id){
        $exists = TrainingRoadmap::where(['subject_id'=>$subject_id,'title_id'=>$title_id])->exists();
        if ($exists) {
            $userFinish=(int)TrainingRoadmapFinish::where(['title_id' => $title_id, 'level_subject_id' => $level_subject_id])->value('user_finish');
            TrainingRoadmapFinish::updateOrCreate(
                ['title_id' => $title_id, 'level_subject_id' => $level_subject_id],
                ['level_subject_id' => $level_subject_id, 'user_finish' => $userFinish+1]
            );
        }
    }
    private function updateUserCompletedSubject($user_id,$subject_id,$course_id){
        UserCompletedSubject::updateOrCreate(['user_id'=>$user_id,'subject_id'=>$subject_id],[
            'user_id'=>$user_id,
            'subject_id'=>$subject_id,
            'course_id'=>$course_id,
           'course_type'=>2,
           'date_completed'=>date('Y-m-d H:i:s'),
            'process_type'=>'O'
        ]);
        // update report bc15
        $this->updateReportBC15($user_id,$subject_id);
    }
    private function updateReportBC15($user_id,$subject_id){
        $subject_code = Subject::find($subject_id)->code;
        $subjects = BC15::where(['user_id'=>$user_id])->select('subject')->first();
        if ($subjects){
            $subjects = json_decode($subjects['subject'],true);
            foreach ($subjects as $index => $subject) {
                if ($subject['code']==$subject_code)
                    $subjects[$index]['type']='O';
            }
            $subjects = collect($subjects)->toJson();
            BC15::where(['user_id'=>$user_id])->update(['subject'=>$subjects]);
        }
    }
    private function updateTrainingProcess($user_id,$course_id,$score,$pass){

        TrainingProcess::where(['user_id'=>$user_id,'course_id'=>$course_id,'course_type'=>2])->update([
            'pass'=>$pass,'mark'=>$score,'time_complete'=>date('Y-m-d H:i:s')
        ]);
    }
    private function updateSendEmailUserCompleted($course_id){
        $users = OfflineCourseComplete::getUserCompleted($course_id);
        $course = OfflineCourse::find($course_id);
        foreach ($users as $user) {
            $progress = OfflineCourse::percent($course_id, $user->user_id);

            $automail = new Automail();
            $automail->template_code = 'course_completed';
            $automail->params = [
                'gender' => $user->gender=='1'?'Anh':'Chị',
                'full_name' => $user->full_name,
                'firstname' => $user->firstname,
                'course_code' => $course->code,
                'course_name' => $course->name,
                'course_type' => 'Tập trung',
                'start_date' => get_date($course->start_date),
                'end_date' => get_date($course->end_date),
                'completion' => 'Hoàn thành',
                'url' => route('module.offline.detail', ['id' => $course->id]),
                'progress' => $progress,
            ];
            $automail->users = [$user->user_id];
            $automail->check_exists = true;
            $automail->check_exists_status = 0;
            $automail->object_id = $course_id;
            $automail->object_type = 'offline_completed';
            $automail->addToAutomail();
        }
    }
    private function setUserPromotionPoint($row, $result)
    {
        $setting = PromotionCourseSetting::where('course_id',$row->course_id)
            ->where('type', 1)
            ->where('status',1)
            ->first();

        if ($setting->method == 1 && $result->result == 1) {
            $userPoint = PromotionUserPoint::firstOrCreate(['user_id' => $row->user_id], ['point' => 0, 'level_id' => 0]);
            $userPoint->point += $setting->point;
            $userPoint->level_id = PromotionLevel::levelUp($userPoint->point,$row->user_id);
            $userPoint->update();
            $this->saveHistoryPromotion($row->user_id, $setting->point,$setting->course_id);
        }
        $this->updatePointCourseComplete($row->user_id,$row->course_id);
    }

    private function levelUp($point)
    {
        $level = PromotionLevel::query()->where('point','<=',$point);

        if($level->exists())
            return $level->max('level');
        else
            return 0;
    }
    private function updatePointCourseComplete($user_id,$course_id){
        $model = RefererRegisterCourse::where('course_id','=',$course_id)->where('type','=',2)->where('user_id','=',$user_id)->whereNull('state');
        if ($model->exists()){
            $referer = $model->value('referer');
            $model->update(['state'=>1]);
            $point_course_referer_finish = Config::where('name','=','point_course_referer_finish')->value('value');
            /*** cộng điểm *****/
            $userPoint = PromotionUserPoint::firstOrCreate(['user_id' => $referer], ['point' => 0, 'level_id' => 0]);
            $userPoint->point += $point_course_referer_finish;
            $userPoint->level_id = PromotionLevel::levelUp($userPoint->point, $referer);
            $userPoint->update();
            /*** hist point ****/
            $pointHist = new PointHist();
            $pointHist->user_id=$user_id;
            $pointHist->name= PointHist::NAME_COURSE_COMPLETE;
            $pointHist->type= PointHist::TYPE_COURSE_COMPLETE;
            $pointHist->referer= $referer;
            $pointHist->point= $point_course_referer_finish;
            $pointHist->save();

        }
    }
    private function saveHistoryPromotion($user_id,$point,$course_id){
        $history = new PromotionUserHistory();
        $history->user_id = $user_id;
        $history->point = $point;
        $history->type = 1;
        $history->course_id = $course_id;
        $history->save();
    }

    private function updateReportNew05($model,$class_id){
        $profile = Profile::find($model->user_id);
        $position = Position::find($profile->position_id);
        $title = @$profile->titles;
        $unit_1 = @$profile->unit;
        $unit_2 = @$unit_1->parent;
        $unit_3 = @$unit_2->parent;
        $unit_type = UnitType::find(@$unit_1->type);
        $area = Area::find(@$unit_1->area_id);

        $course = OfflineCourse::find($model->course_id);
        $training_type = TrainingForm::find($course->training_form_id); //Loại hình đào tạo
        $training_form = TrainingType::find($training_type->training_type_id); // Hình thức đào tạo
        $subject = Subject::find($course->subject_id);
        $course_time = preg_replace("/[^0-9]/", '', $course->course_time);
        $class = OfflineCourseClass::find($class_id);
        $attendance = OfflineAttendance::where('register_id', '=', $model->register_id)->count();
        $training_area = Area::whereIn('id', json_decode(@$course->training_area_id))->pluck('name')->toArray();

        ReportNewExportBC05::updateOrCreate([
            'user_id' => $profile->user_id,
            'course_id' => $course->id,
            'course_type' => 2,
        ],[
            'course_id' => $course->id,
            'course_code' => $course->code,
            'course_name' => $course->name,
            'class_id' => $class->id,
            'class_code' => $class->code,
            'class_name' => $class->name,
            'course_type' => 2,
            'subject_id' => @$subject->id,
            'subject_name' => @$subject->name,
            'training_unit' => $course->training_unit,
            'training_type_id' => @$training_type->id,
            'training_type_name' => @$training_type->name,
            'training_form_id' => @$training_form->id,
            'training_form_name' => @$training_form->name,
            'training_area_id' => @$course->training_area_id,
            'training_area_name' => count($training_area) > 0 ? implode('; ', @$training_area) : '',
            'course_time' => $course_time,
            'attendance' => $attendance,
            'start_date' => $course->start_date,
            'end_date' => $course->end_date,
            'score' => @$model->score,
            'result' => $model->result,
            'user_id' => $profile->user_id,
            'user_code' => $profile->code,
            'fullname' => $profile->full_name,
            'email' => $profile->email,
            'phone' => $profile->phone,
            'area_id' => @$area->id,
            'area_code' => @$area->code,
            'area_name' => @$area->name,
            'unit_id_1' => @$unit_1->id,
            'unit_code_1' => @$unit_1->code,
            'unit_name_1' => @$unit_1->name,
            'unit_id_2' => @$unit_2->id,
            'unit_code_2' => @$unit_2->code,
            'unit_name_2' => @$unit_2->name,
            'unit_id_3' => @$unit_3->id,
            'unit_code_3' => @$unit_3->code,
            'unit_name_3' => @$unit_3->name,
            'position_name' => @$position->name,
            'title_id' => @$title->id,
            'title_code' => @$title->code,
            'title_name' => @$title->name,
            'status_user' => $profile->status,
            'unit_type' => @$unit_type->name,
            'note' => '',
            'course_employee' => $course->course_employee,
        ]);
    }

    private function pointPromotion($user_id, $course_id, $model) {
        $course = OfflineCourse::find($course_id);

        $setting = UserPointSettings::where("pkey","=","offline_complete")
        ->where("item_id","=",$course_id)
        ->where("item_type","=","3")
        ->first();

        $time = strtotime($model->created_at);

        if($setting){
            if($time>=$setting->start_date && $time<=$setting->end_date){

                $note = 'Hoàn thành khóa học tập trung <b>'. $course->name .' ('. $course->code .')</b>';

                $exists=UserPointResult::where("setting_id","=",$setting->id)->where("user_id","=",$user_id)->whereNull("type")->first();
                if(!$exists){
                    UserPointResult::create([
                        'setting_id' => $setting->id,
                        'user_id' => $user_id,
                        'content' => $note,
                        'point' => $setting->pvalue,
                        'ref' => $course_id,
                        'type_promotion' => 0,
                    ]);

                    $user_point = PromotionUserPoint::firstOrNew(['user_id' => $user_id]);
                    $user_point->point = $user_point->point + $setting->pvalue;
                    $user_point->level_id = PromotionLevel::levelUp($user_point->point, $user_id);
                    $user_point->save();
                }
            }

        }
    }

    private function pointUserMedal($user_id, $course_id, $point) {

        $course = OfflineCourse::find($course_id);

        $setting_item = UserMedalSettingsItems::where("item_id","=",$course_id)->where("item_type","=","3")->first();
        $time = time();

        $profile = ProfileView::select(['title_id','user_id','unit_id'])->find($user_id);
        $query = new UserMedalObject();
        $query->from('el_usermedal_object')->where('settings_id', '=', $setting_item->setting_id)
            ->where(function ($query)use ($profile) {
                $query->orwhere('user_id', '=', $profile->user_id)
                    ->orWhere('title_id', '=', $profile->title_id)
                    ->orWhere('unit_id', '=', $profile->unit_id);
            });
        $apply = $query->exists();

        if(!empty($setting_item) && $apply){
            $setting = UserMedalSettings::find($setting_item->setting_id);

            if($time >= $setting->start_date && $time <= $setting->end_date){
                $setting_items_point = UserMedalSettingsItems::where("setting_id","=",$setting->id)
                    ->where("item_type", "=", "5")
                    ->where('min_score', '<=', $point)
                    ->where('max_score', '>=', $point)
                    ->first();

                if(!empty($setting_items_point)){
                    $note = 'Hoàn thành khóa học tập trung <b>'. $course->name .' ('. $course->code .')</b>';

                    $exists = UserMedalResult::where("settings_items_id","=",$setting_item->id)->where("user_id","=",$user_id)->first();
                    if($exists){
                        $model= UserMedalResult::find($exists->id);
                        $model->content =$note;
                        $model->settings_items_id_got =$setting_items_point->id;
                        $model->point = $point;
                        $model->ref = $course_id;
                        $model->save();
                    }else{
                        UserMedalResult::create([
                            'settings_items_id' => $setting_item->id,
                            'settings_items_id_got' => $setting_items_point->id,
                            'user_id' => $user_id,
                            'content' => $note,
                            'point' => $point,
                            'ref' => $course_id
                        ]);
                    }
                }
            }
        }

    }

    // Cập nhật kết quả chuyên đề cho chứng chỉ chương trình đào tạo
    private function updateCertificateTraining($subject_id, $user_id, $result, $time_complete){

        //Lấy các chương trình đào tạo cấp chứng chỉ có chuyên đề theo $subject_id
        $subject_list = SubjectTypeSubject::whereSubjectId($subject_id)->get();
        foreach($subject_list as $subject){
            $object_title = SubjectTypeObject::whereSubjectTypeId($subject->subject_type_id)->pluck('title_id')->toArray();
            $object_unit = SubjectTypeObject::whereSubjectTypeId($subject->subject_type_id)->pluck('unit_id')->toArray();
            $object_user = SubjectTypeObject::whereSubjectTypeId($subject->subject_type_id)->pluck('user_id')->toArray();

            // check user có nằm trong đối tượng chương trình chứng chỉ hay không
            $profile = Profile::where('user_id', $user_id)
                ->where(function($sub) use($object_title, $object_unit, $object_user) {
                    $sub->orWhereIn('title_id', $object_title);
                    $sub->orWhereIn('unit_id', $object_unit);
                    $sub->orWhereIn('user_id', $object_user);
                });

            $subject_type = SubjectType::find($subject->subject_type_id);

            if($result == 1){
                //Nếu thời gian hoàn thành trong thời gian thiết lập và user nằm trong đối tượng cấp chứng chỉ
                if($subject_type->startdate <= $time_complete && $time_complete <= $subject_type->enddate && $profile->exists()){
                    SubjectTypeUser::updateOrCreate([
                        'subject_type_id' => $subject->subject_type_id,
                        'subject_id' => $subject_id,
                        'user_id' => $user_id,
                    ]);

                    $course_finished_total  = SubjectTypeUser::where('subject_type_id', $subject->subject_type_id)->where('user_id', $user_id)->count();

                    SubjectTypeResult::updateOrCreate([
                        'subject_type_id' => $subject->subject_type_id,
                        'user_id' => $user_id
                    ],[
                        'course_finished_total' => $course_finished_total
                    ]);
                }
            }else{
                $check = SubjectTypeResult::where('subject_type_id', $subject->subject_type_id)->where('user_id', $user_id);
                if(!$check->exists()){
                    SubjectTypeResult::create([
                        'subject_type_id' => $subject->subject_type_id,
                        'user_id' => $user_id,
                        'course_finished_total' => 0
                    ]);
                }
            }
        }
    }
}
