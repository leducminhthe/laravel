<?php
namespace Modules\TrainingUnit\Imports;

use App\Models\Categories\Subject;
use App\Models\PermissionTypeUnit;
use App\Models\Profile;
use App\Models\Categories\Titles;
use App\Models\ProfileView;
use App\Models\UserPermissionType;
use Illuminate\Support\Facades\Auth;
use Modules\Online\Entities\OnlineCourse;
use Modules\Online\Entities\OnlineObject;
use Modules\Online\Entities\OnlineRegister;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Modules\Quiz\Entities\Quiz;
use Modules\Quiz\Entities\QuizPart;
use Modules\Quiz\Entities\QuizRegister;
use Modules\User\Entities\TrainingProcess;
use Modules\Offline\Entities\OfflineCourse;
use Modules\Offline\Entities\OfflineObject;
use Modules\Offline\Entities\OfflineRegister;
use App\Models\Categories\UnitManager;

class RegisterImport implements ToModel, WithStartRow
{
    public $errors;
    public $course_id;
    public $course_type;

    public function __construct($course_id, $course_type)
    {
        $this->errors = [];
        $this->course_id = $course_id;
        $this->course_type = $course_type;
    }

    public function model(array $row)
    {
        $error = false;
        $user_code = $row[1];

        $managers = UnitManager::getIdUnitManagedByUser(profile()->user_id);
        $profile = ProfileView::where('code', '=', $user_code)->whereIn('unit_id', $managers)->first();

        if(isset($profile)){
            if ($this->course_type == 1) {
                $course_object = OnlineObject::where('course_id', '=', $this->course_id)->pluck('title_id')->toArray();

                $register = OnlineRegister::where('user_id', '=', $profile->user_id)
                ->where('user_type', '=', 1)
                ->where('course_id', '=', $this->course_id)
                ->first();
            } else {
                $course_object = OfflineObject::where('course_id', '=', $this->course_id)->whereNotNull('title_id')->pluck('title_id')->toArray();

                $register = OfflineRegister::where('user_id', '=', $profile->user_id)
                ->where('course_id', '=', $this->course_id)->first();
            }

            if (!empty($course_object) && !in_array($profile->title_id, $course_object)){
                $this->errors[] = 'Chức danh của <b>'. $profile->full_name .'</b> không thể đăng kí khóa học';
                $error = true;
            }

            if ($register) {
                $this->errors[] = 'Mã nhân viên <b>'. $row[1] .'</b> đã đăng kí khóa học';
                $error = true;
            }
        }

        if (empty($profile)) {
            $this->errors[] = 'Mã nhân viên <b>'. $row[1] .'</b> không tồn tại hoặc không thuộc đơn vị';
            $error = true;
        }

        if($error) {
            return null;
        }

        if ($this->course_type == 1) {
            $course = OnlineCourse::findOrFail($this->course_id);

            OnlineRegister::create([
                'user_id' =>(int) $profile->user_id,
                'course_id' => $this->course_id,
                'user_type' => 1
            ]);
            $model = OnlineRegister::orderBy('id', 'DESC')->first();
            if ($course->auto == 1){
                $model->status = 1;
    
                $quizs = Quiz::where('course_id', '=', $this->course_id)
                    ->where('status', '=', 1)->get();
                if ($quizs){
                    foreach ($quizs as $quiz){
                        $quiz_part = QuizPart::where('quiz_id', '=', $quiz->id)->first();
                        if ($quiz_part){
                            QuizRegister::query()
                                ->updateOrCreate([
                                    'quiz_id' => $quiz->id,
                                    'user_id' => $model->user_id,
                                    'type' => 1,
                                ],[
                                    'quiz_id' => $quiz->id,
                                    'user_id' => $model->user_id,
                                    'type' => 1,
                                    'part_id' => $quiz_part->id,
                                ]);
                        }else{
                            continue;
                        }
                    }
                }
                $model->save();
            }
        } else {
            $course = OfflineCourse::findOrFail($this->course_id);

            OfflineRegister::create([
                'user_id' =>(int) $profile->user_id,
                'course_id' => $this->course_id,
            ]);
        }
        // update training process
        $subject = Subject::find($course->subject_id);
        TrainingProcess::create([
            'user_id'=> $profile->user_id,
            'user_type' => 1,
            'course_id'=> $this->course_id,
            'course_code'=> $course->code,
            'course_name'=> $course->name,
            'course_type'=> $this->course_type,
            'subject_id'=> $subject->id,
            'subject_code'=> $subject->code,
            'subject_name'=> $subject->name,
            'titles_code'=> $profile->titles_code,
            'titles_name'=> $profile->titles_name,
            'unit_code'=> $profile->unit_code,
            'unit_name'=> $profile->unit_name,
            'start_date'=> $course->start_date,
            'end_date'=> $course->end_date,
            'process_type'=> 1,
            'certificate'=> $course->cert_code,
        ]);
    }

    public function startRow(): int
    {
        return 2;
    }

}
