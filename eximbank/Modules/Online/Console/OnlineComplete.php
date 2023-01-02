<?php

namespace Modules\Online\Console;

use App\Models\Automail;
use App\Models\CourseResultStatistic;
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
use Arcanedev\LogViewer\Entities\Log;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Modules\Online\Entities\OnlineCourse;
use Modules\Online\Entities\OnlineCourseActivity;
use Modules\Online\Entities\OnlineCourseComplete;
use Modules\Online\Entities\OnlineCourseCondition;
use Modules\Online\Entities\OnlineCourseSettingPercent;
use Modules\Online\Entities\OnlineRegister;
use Modules\Online\Entities\OnlineRegisterView;
use Modules\Online\Entities\OnlineResult;
use Modules\Online\Events\CourseCompleted;
use Modules\Rating\Entities\RatingCourse;
use Modules\ReportNew\Entities\BC15;
use Modules\ReportNew\Entities\ReportNewExportBC05;
use Modules\TrainingRoadmap\Entities\TrainingRoadmap;
use Modules\TrainingRoadmap\Entities\TrainingRoadmapFinish;
use Modules\User\Entities\TrainingProcess;
use Modules\User\Entities\UserCompletedSubject;
use Modules\UserMedal\Entities\UserMedalObject;
use Modules\UserMedal\Entities\UserMedalResult;
use Modules\UserMedal\Entities\UserMedalSettings;
use Modules\UserMedal\Entities\UserMedalSettingsItems;
use Modules\UserPoint\Entities\UserPointSettings;
use Modules\UserPoint\Entities\UserPointResult;
use Modules\Promotion\Entities\PromotionUserPoint;
use Modules\Promotion\Entities\PromotionLevel;

class OnlineComplete extends Command
{
    protected $signature = 'online:complete {user_id?} {course_id?}';

    protected $description = 'Hoàn thành khóa học online 1 phút 1 lần (* * * * *)';
    protected $expression = "* * * * *";
    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $param_user_id = $this->argument('user_id');
        $param_course_id = $this->argument('course_id');

        $query = OnlineRegisterView::query();
        $query->select([
            'register.id',
            'register.course_id',
            'register.user_id',
            'register.user_type',
            'course.subject_id',
            'course.level_subject_id',
            'register.title_id'
        ])->disableCache();

        $query->from('el_online_register_view AS register')
            ->join('el_online_course AS course', 'course.id', '=', 'register.course_id')
            ->where('course.status', '=', 1)
            ->where('course.offline', '=', 0)
            ->where('register.status', '=', 1);

        if($param_user_id && $param_course_id){
            $query = $query->where('register.user_id', '=', $param_user_id)->where('register.course_id', '=', $param_course_id);
        }else{
            $query = $query->where('register.cron_complete', '!=', 1);
        }

        $rows = $query->get();
        foreach ($rows as $row) {
            $result = $this->getResult($row);

            if (empty($result)) {
                continue;
            }

            $model = OnlineResult::firstOrNew(['register_id' => $row->id, 'user_id' => $row->user_id, 'course_id' => $row->course_id]);
            $model->register_id = $row->id;
            $model->user_id = $row->user_id;
            $model->user_type = $row->user_type;
            $model->course_id = $row->course_id;
            $model->pass_score = $result->pass_score;
            $model->score = OnlineCourseSettingPercent::getScore($row->course_id, $row->user_id, $row->user_type, $result->score);
            $model->result = $result->result;
            $model->save();

            $this->updateTrainingProcess($row->user_id, $row->course_id, $result->score, $result->result, $row->user_type);
            $this->updateCompleteCronUser($row->id);

            if ($row->user_type == 1){
                $this->updateReport05($model);

                $this->updateCertificateTraining($row->subject_id, $row->user_id, $model->result, $model->created_at);
            }

            if ($result->result == 1) {
                $this->updateCompleteCourse($row->user_id,$row->course_id, $row->user_type);
                $this->pointPromotion($row->user_id,$row->course_id, $model);
                $this->pointUserMedal($row->user_id,$row->course_id, $result->score);
                $this->updateCompletedRoadmapByTitle($row->subject_id, $row->title_id,$row->level_subject_id);
                event(new CourseCompleted($model));
                if ($row->user_type == 1) {
                    $this->updateUserCompletedSubject($row->user_id, $row->subject_id, $row->course_id);
                }
            }
        }

        $this->updateResultCourseStatistic();
    }
    private function updateCompleteCourse($user_id,$course_id, $user_type=1){
        OnlineCourseComplete::updateOrCreate([
            'user_id' => $user_id,
            'user_type' => $user_type,
            'course_id' => $course_id
        ],[
            'user_id' => $user_id,
            'user_type' => $user_type,
            'course_id' => $course_id
        ]);
        CourseComplete::updateOrCreate([
            'user_id' => $user_id,
            'user_type' => $user_type,
            'course_id' => $course_id,
            'course_type'=>1
        ],[
            'user_id' => $user_id,
            'user_type' => $user_type,
            'course_id' => $course_id,
            'course_type'=>1
        ]);
    }
    private function updateCompleteCronUser($register_id){
        $offlineResult = OnlineRegister::whereId($register_id)->first();
        $offlineResult->cron_complete = 1;
        $offlineResult->save();
    }

    private function updateCompletedRoadmapByTitle($subject_id, $title_id,$level_subject_id){
        $exists = TrainingRoadmap::where(['subject_id'=>$subject_id,'title_id'=>$title_id])->exists();
        if ($exists) {
            $userFinish=(int)TrainingRoadmapFinish::where(['title_id' => $title_id, 'level_subject_id' => $level_subject_id])->value('user_finish');
            TrainingRoadmapFinish::updateOrCreate(
                ['title_id' => $title_id, 'level_subject_id' => $level_subject_id],
                ['title_id' => $title_id, 'level_subject_id' => $level_subject_id, 'user_finish' => $userFinish+1]
            );
        }
    }

    private function updateUserCompletedSubject($user_id,$subject_id,$course_id){
        UserCompletedSubject::updateOrCreate([
            'user_id'=>$user_id,
            'subject_id'=>$subject_id
        ],[
            'user_id'=>$user_id,
            'subject_id'=>$subject_id,
            'course_id'=>$course_id,
            'course_type'=>1,
            'date_completed'=>date('Y-m-d H:i:s'),
            'process_type'=>'E'
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
    private function updateSendEmailUserCompleted($course_id){
        $users = OnlineCourseComplete::getUserCompleted($course_id);
        $course = OnlineCourse::find($course_id);
        foreach ($users as $user) {
            $progress = OnlineCourse::percentCompleteCourseByUser($course_id, $user->user_id);
            $signature = getMailSignature($user->user_id);

            $automail = new Automail();
            $automail->template_code = 'course_completed';
            $automail->params = [
                'signature' => $signature,
                'gender' => $user->gender=='1'?'Anh':'Chị',
                'full_name' => $user->full_name,
                'firstname' => $user->firstname,
                'course_code' => $course->code,
                'course_name' => $course->name,
                'course_type' => 'Trực tuyến',
                'start_date' => get_date($course->start_date),
                'end_date' => get_date($course->end_date),
                'completion' => 'Hoàn thành',
                'url' => route('module.online.detail', ['id' => $course->id]),
                'progress' => $progress,
            ];
            $automail->users = [$user->user_id];
            $automail->check_exists = true;
            $automail->check_exists_status = 0;
            $automail->object_id = $course_id;
            $automail->object_type = 'online_completed';
            $automail->addToAutomail();
        }
    }
    private function updateResultCourseStatistic(){
        CourseResultStatistic::update_count_result_statistic(1,1); // khóa học online kết quả đậu
        CourseResultStatistic::update_count_result_statistic(1,0); // khóa học online kết quả rớt
        CourseResultStatistic::update_count_result_statistic(2,1); // khóa học offline kết quả đậu
        CourseResultStatistic::update_count_result_statistic(2,0); // khóa học offline kết quả rớt
    }
    private function updateTrainingProcess($user_id,$course_id,$score,$pass, $user_type){

        TrainingProcess::where([
            'user_id'=>$user_id,
            'course_id'=>$course_id,
            'course_type'=>1,
            'user_type' => $user_type
        ])->update([
            'pass'=>$pass,
            'mark'=>$score,
            'time_complete'=>date('Y-m-d H:i:s')
        ]);
    }
    private function getResult($row)
    {
        $object = new \stdClass();
        $object->result = 0;
        $object->score = null;
        $object->pass_score = 0;

        /* check result */
        $condition = OnlineCourseCondition::where('course_id', '=', $row->course_id)->first();
        if (empty($condition)) {
            return false;
        }

        $activity_condition = explode(',', $condition->activity);
        $count_condition = count($activity_condition);
        $count_complete = 0;

        $query = OnlineCourseActivity::where('course_id', '=', $row->course_id);
        $query->whereIn('id', $activity_condition);
        $activities = $query->get();

        /* Activities completed */
        $result = [];
        foreach ($activities as $activity) {
            $is_completed = $activity->isComplete($row->user_id, $row->user_type);
            $completed = $activity->checkComplete($row->user_id, $row->user_type);
            $result[] = $completed;

            if (in_array($activity->id, $activity_condition) && $is_completed) {
                $count_complete += 1;
            }
        }

        /* Get score */
        $score = [];
        $count_score = 0;
        foreach ($result as $item) {
            if (isset($item->score)) {
                $score[] = $item->score;
            }
            if (isset($item->pass_score)) {
                $object->pass_score = $item->pass_score;
            }
            if (isset($item->score) && isset($item->pass_score)) {
                if ($item->score >= $item->pass_score) {
                    $count_score += 1;
                }
            }
        }

        if ($condition->grade_methor) {
            if ($condition->grade_methor == 1) {
                $object->score = (count($score) > 0 ? max($score) : null);
            }
            if ($condition->grade_methor == 2) {
                if (count($score) > 0){
                    $total = 0;
                    foreach ($score as $item) {
                        $total += $item;
                    }
                    $object->score = $total / (count($score) > 0 ? count($score) : 1);
                }
            }
            if ($condition->grade_methor == 3) {
                if (count($score) > 0) {
                    foreach ($score as $item) {
                        $object->score = $item;
                    }
                }
            }
        } else {
            if (count($score) > 0){
                $total = 0;
                foreach ($score as $item) {
                    $total += $item;
                }
                $object->score = $total / (count($score) > 0 ? count($score) : 1);
            }
        }

        if ($condition->rating) {
            $count_condition += 1;
            $check = RatingCourse::where('course_id', '=', $row->course_id)
                ->where('user_id', '=', $row->user_id)
                ->where('user_type', '=', $row->user_type)
                ->where('send', '=', 1)
                ->exists();
            if ($check) {
                $count_complete += 1;
            }
        }

        if ($count_condition == $count_complete) {
            $object->result = count($score) > 0 ? (count($score) == $count_score ? 1 : 0) : 1;
        }

        return $object;
    }

    private function updateReport05($model){
        $profile = Profile::find($model->user_id);
        $position = Position::find($profile->position_id);
        $title = @$profile->titles;
        $unit_1 = @$profile->unit;
        $unit_2 = @$unit_1->parent;
        $unit_3 = @$unit_2->parent;

        $area = Area::find(@$unit_1->area_id);

        $course = OnlineCourse::find($model->course_id);
        $training_type = TrainingForm::find($course->training_form_id); //Loại hình đào tạo
        $training_form = TrainingType::find($training_type->training_type_id); // Hình thức đào tạo
        $subject = Subject::find($course->subject_id);
        $course_time = preg_replace("/[^0-9]/", '', $course->course_time);

        ReportNewExportBC05::updateOrCreate([
            'user_id' => $profile->user_id,
            'course_id' => $course->id,
            'course_type' => 1,
        ],[
            'course_id' => $course->id,
            'course_code' => $course->code,
            'course_name' => $course->name,
            'course_type' => 1,
            'subject_id' => @$subject->id,
            'subject_name' => @$subject->name,
            'training_unit' => $course->training_unit,
            'training_type_id' => @$training_type->id,
            'training_type_name' => @$training_type->name,
            'training_form_id' => @$training_form->id,
            'training_form_name' => @$training_form->name,
            'training_area_id' => null,
            'training_area_name' => null,
            'course_time' => $course_time,
            'attendance' => null,
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
            'note' => '',
        ]);
    }

    private function pointPromotion($user_id, $course_id, $result) {
        $course = OnlineCourse::find($course_id);

        $setting = UserPointSettings::where("pkey","=","online_complete")
        ->where("item_id","=",$course_id)
        ->where("item_type","=","2")
        ->first();

        $time = strtotime($result->created_at);

        if(!empty($setting)){
            if($time >= $setting->start_date && ($time <= $setting->end_date || !$setting->end_date)){
                $note = 'Hoàn thành khóa học online <b>'. $course->name .' ('. $course->code .')</b>';
                $exists = UserPointResult::where("setting_id","=",$setting->id)->where("user_id","=",$user_id)->whereNull("type")->first();
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
        $course = OnlineCourse::find($course_id);

        $setting_item = UserMedalSettingsItems::where("item_id","=",$course_id)->where("item_type","=","2")->first();
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

                $setting_items_point = UserMedalSettingsItems::where("setting_id", "=", $setting->id)
                    ->where("item_type", "=", "5")
                    ->where('min_score', '<=', $point)
                    ->where('max_score', '>=', $point)
                    ->first();

                if(!empty($setting_items_point)){
                    $note = 'Hoàn thành khóa học online <b>'. $course->name .' ('. $course->code .')</b>';

                    $exists = UserMedalResult::where("settings_items_id","=",$setting_item->id)->where("user_id","=",$user_id)->first();

                    if($exists){
                        $model = UserMedalResult::find($exists->id);
                        $model->content = $note;
                        $model->settings_items_id_got = $setting_items_point->id;
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

                    $course_finished_total = SubjectTypeUser::where('subject_type_id', $subject->subject_type_id)->where('user_id', $user_id)->count();

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
