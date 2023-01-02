<?php
namespace Modules\Offline\Imports;

use App\Models\Automail;
use App\Models\Categories\Area;
use App\Models\Categories\Position;
use App\Models\Categories\Subject;
use App\Models\Categories\TrainingForm;
use App\Models\Categories\TrainingType;
use App\Models\CourseComplete;
use App\Models\Profile;
use Modules\Offline\Entities\OfflineAttendance;
use Modules\Offline\Entities\OfflineCondition;
use Modules\Offline\Entities\OfflineCourse;
use Modules\Offline\Entities\OfflineCourseComplete;
use Modules\Offline\Entities\OfflineResult;
use Modules\Offline\Entities\OfflineRegister;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Illuminate\Support\Facades\Auth;
use Modules\ReportNew\Entities\BC15;
use Modules\ReportNew\Entities\ReportNewExportBC05;
use Modules\TrainingRoadmap\Entities\TrainingRoadmap;
use Modules\TrainingRoadmap\Entities\TrainingRoadmapFinish;
use Modules\User\Entities\TrainingProcess;
use Modules\User\Entities\UserCompletedSubject;
use App\Models\Permission;
use App\Models\UserRole;
use App\Models\Categories\Unit;
use App\Models\User;

class ResultImport implements ToModel, WithStartRow
{
    public $errors;
    public function __construct($course_id, $class_id, $user_role)
    {
        $this->errors = [];
        $this->course_id = $course_id;
        $this->class_id = $class_id;
        $this->user_role = $user_role;
    }

    public function model(array $row)
    {
        $error = false;
        //$code_register = $row[1];
        $code = trim($row[1]);
        $score = str_replace(',', '.', $row[4]);

        $profile = Profile::where('code', '=', $code)->first();
        $condition = OfflineCondition::where('course_id', '=', $this->course_id)->first();
        $course = OfflineCourse::find($this->course_id);

        if (empty($profile)) {
            $this->errors[] = 'Nhân viên <b>'. $row[1] .'</b> không tồn tại';
            $error = true;
        }

        if(empty($condition)){
            $this->errors[] = 'Khóa học cần thiết lập Điều kiện hoàn thành';
            $error = true;
        }

        if($profile){
            $register = OfflineRegister::where('user_id', '=', $profile->user_id)
            ->where('course_id', '=', $this->course_id)
            ->where('class_id', $this->class_id)->first();

            if (empty($register)) {
                $this->errors[] = 'Dòng <b>'. $row[0] .'</b>: Nhân viên không thuộc lớp học này';
                $error = true;
            }
        }

        if (empty($score) || $score == '#N/A'){
            $this->errors[] = 'Cột điểm dòng <b>'. $row[0] .'</b> không đúng';
            $error = true;
        }

        if ($error){
            return null;
        }

        if (isset($register)){
            // kiểm tra nhân viên có thuộc đơn vị quản lý hay ko
            if(!Permission::isAdmin()){
                if($this->user_role->type == 'group-child') {
                    $getArray = Unit::getArrayChild($this->user_role->code);
                    array_push($getArray, $this->user_role->unit_id);
                    if(!in_array($profile->unit_id, $getArray)) {
                        $this->errors[] = 'Dòng '. $row[0] .': Nhân viên Không thuộc đơn vị quản lý: '. $this->user_role->name .'';
                        return null;
                    }
                } else {
                    if($profile->unit_id != $this->user_role->unit_id) {
                        $this->errors[] = 'Dòng '. $row[0] .': Nhân viên Không thuộc đơn vị quản lý: '. $this->user_role->name .'';
                        return null;
                    }
                }
            }

            $model = OfflineResult::firstOrNew(['register_id' => $register->id]);
            $model->register_id = (int) $register->id;
            $model->user_id  = $register->user_id;
            $model->score = $score;
            $model->pass_score = $condition->minscore;
            $model->percent = OfflineResult::getPercent($register->id);
            $model->course_id = $this->course_id;
            $model->note = $row[7];
            $model->save();

            $model->updateResult();

            if ($model->result == 1){
                OfflineCourseComplete::updateOrCreate([
                    'user_id' => $model->user_id,
                    'course_id' => $model->course_id
                ],[
                    'user_id' => $model->user_id,
                    'course_id' => $model->course_id
                ]);
                CourseComplete::updateOrCreate([
                    'user_id' => $model->user_id,
                    'user_type' => 1,
                    'course_id' => $model->user_id,
                    'course_type'=> 2
                ],[
                    'user_id' => $model->user_id,
                    'user_type' => 1,
                    'course_id' => $model->user_id,
                    'course_type'=> 2
                ]);

                $this->updateUserCompletedSubject($model->user_id, $course->subject_id, $this->course_id);
                $this->updateCompletedRoadmapByTitle($course->subject_id, $register->title_id, $course->level_subject_id);
                $this->updateSendEmailUserCompleted($this->course_id);
            }

            $this->updateTrainingProcess($model->user_id, $this->course_id, $model->score, $model->result);
            $this->updateReportNew05($model);
        }
    }

    public function startRow(): int
    {
        return 3;
    }

    private function updateTrainingProcess($user_id,$course_id,$score,$pass){

        TrainingProcess::where(['user_id'=>$user_id,'course_id'=>$course_id,'course_type'=>2])->update([
            'pass'=>$pass,'mark'=>$score,'time_complete'=>date('Y-m-d H:i:s')
        ]);
    }
    private function updateReportNew05($model){
        $profile = Profile::find($model->user_id);
        $position = Position::find($profile->position_id);
        $title = @$profile->titles;
        $unit_1 = @$profile->unit;
        $unit_2 = @$unit_1->parent;
        $unit_3 = @$unit_2->parent;

        $area = Area::find(@$unit_1->area_id);

        $course = OfflineCourse::find($model->course_id);
        $training_type = TrainingForm::find($course->training_form_id);
        $training_form = TrainingType::find($course->training_type_id);
        $subject = Subject::find($course->subject_id);
        $course_time = preg_replace("/[^0-9]/", '', $course->course_time);
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
            'note' => '',
        ]);
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
    private function updateReportBC15($user_id,$subject_id){
        $subject_code = Subject::find($subject_id)->code;
        $subjects = BC15::where(['user_id'=>$user_id])->select('subject')->first();
        $subjects = json_decode($subjects['subject'],true);
        if ($subjects){
            foreach ($subjects as $index => $subject) {
                if ($subject['code']==$subject_code)
                    $subjects[$index]['type']='O';
            }
            $subjects = collect($subjects)->toJson();
            BC15::where(['user_id'=>$user_id])->update(['subject'=>$subjects]);
        }
    }
    private function updateSendEmailUserCompleted($course_id){
        $users = OfflineCourseComplete::getUserCompleted($course_id);
        $course = OfflineCourse::find($course_id);
        foreach ($users as $user) {
            $progress = OfflineCourse::percent($course_id, $user->user_id);
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
}
