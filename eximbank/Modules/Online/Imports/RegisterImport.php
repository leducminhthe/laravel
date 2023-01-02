<?php
namespace Modules\Online\Imports;

use App\Models\Categories\Subject;
use App\Models\PermissionTypeUnit;
use App\Models\Profile;
use App\Models\Categories\Titles;
use App\Models\ProfileView;
use App\Models\UserPermissionType;
use Illuminate\Support\Facades\Auth;
use Modules\Online\Entities\OnlineCourse;
use Modules\Online\Entities\OnlineInviteRegister;
use Modules\Online\Entities\OnlineObject;
use Modules\Online\Entities\OnlineRegister;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Modules\Quiz\Entities\Quiz;
use Modules\Quiz\Entities\QuizPart;
use Modules\Quiz\Entities\QuizRegister;
use Modules\User\Entities\TrainingProcess;
use App\Events\SaveTrainingProcessRegister;
use App\Models\Permission;
use App\Models\UserRole;
use App\Models\Categories\Unit;
use App\Models\User;

class RegisterImport implements ToModel, WithStartRow
{
    public $errors;
    public $course_id;

    public function __construct($course_id)
    {
        $this->errors = [];
        $this->course_id = $course_id;
    }

    public function model(array $row)
    {
        $error = false;
        //$user_code = $row[1];
        $user_name = $row[1];

        $user = User::whereUsername($user_name)->first(['id']);
        $profile = ProfileView::where('user_id', '=', @$user->id)->first();

        $user_invited = false;
        $check_user_invited = OnlineInviteRegister::query()
            ->where('course_id', '=', $this->course_id)
            ->where('user_id', '=', profile()->user_id);
        if ($check_user_invited->exists()){
            $user_invited = true;
            $num_register = $check_user_invited->first()->num_register;

            $user_permission_type = UserPermissionType::query()
                ->select(['permission_type_id'])
                ->whereUserId(profile()->user_id)
                ->groupBy('permission_type_id')
                ->first();
            $condition=PermissionTypeUnit::conditionUnitGroup(@$user_permission_type->permission_type_id);
            $arr_user_code = Profile::query()
                ->whereExists(function ($queryExists) use ($condition){
                    $queryExists->select('id')
                        ->from('el_unit_view')
                        ->whereColumn(['id'=>'unit_id']);
                    if ($condition)
                        $queryExists->whereRaw($condition);
                    else
                        $queryExists->whereRaw("1=-1");
                })->pluck('code')->toArray();

            if (!in_array($user_code, $arr_user_code)){
                $this->errors[] = 'Nhân viên có mã <b>'. $user_code .'</b> không thuộc đơn vị bạn quản lý';
                $error = true;
            }
        }

        if (empty($profile)) {
            $this->errors[] = 'Tên đăng nhập <b>'. $row[1] .'</b> không tồn tại';
            $error = true;
        }

        if(isset($profile)){
            $title = Titles::where('code', '=', $profile->title_code)->first();
            $fullname = $profile->full_name;
            $course_object = OnlineObject::where('course_id', '=', $this->course_id)->pluck('title_id')->toArray();

            if (!empty($course_object) && !in_array($title->id, $course_object)){
                $this->errors[] = 'Chức danh của <b>'. $fullname .'</b> không thể đăng kí khóa học';
                $error = true;
            }

            $register = OnlineRegister::where('user_id', '=', $profile->user_id)
                ->where('user_type', '=', 1)
                ->where('course_id', '=', $this->course_id)
                ->first();

            if ($register) {
                $this->errors[] = 'Dòng '. $row[0] .': Nhân viên đã đăng kí khóa học';
                $error = true;
            }

            // kiểm tra nhân viên có thuộc đơn vị quản lý hay ko
            if(!Permission::isAdmin()) {
                $userUnit = session()->get('user_unit');
                $user_role = UserRole::query()
                    ->select(['c.unit_id', 'c.type', 'd.code', 'd.name'])->disableCache()
                    ->from('el_user_role as a')
                    ->join('el_role_has_permission_type as b', 'b.role_id', '=', 'a.role_id')
                    ->join('el_permission_type_unit as c', 'c.permission_type_id', '=', 'b.permission_type_id')
                    ->join('el_unit as d', 'd.id', '=', 'c.unit_id')
                    ->where('a.user_id', '=', profile()->user_id)
                    ->where('c.unit_id', '=', $userUnit)
                    ->first();
                if($user_role->type == 'group-child') {
                    $getArray = Unit::getArrayChild($user_role->code);
                    array_push($getArray, $user_role->unit_id);
                    if(!in_array((int) $profile->unit_id, $getArray)) {
                        $this->errors[] = 'Dòng '. $row[0] .': Nhân viên Không thuộc đơn vị quản lý: '. $user_role->name .'';
                        return null;
                    }
                } else {
                    if($profile->unit_id != $user_role->unit_id) {
                        $this->errors[] = 'Dòng '. $row[0] .': Nhân viên Không thuộc đơn vị quản lý: '. $user_role->name .'';
                        return null;
                    }
                }
            }
        }

        if ($user_invited){
            if ($num_register == 0){
                $this->errors[] = 'Đã đủ SL. Mã nhân viên <b>'. $row[1] .'</b> không thể đăng kí khóa học';
                $error = true;
            }else{
                $num_register -= 1;

                OnlineInviteRegister::query()
                    ->where('course_id', '=', $this->course_id)
                    ->where('user_id', '=', profile()->user_id)
                    ->update([
                        'num_register' => $num_register
                    ]);
            }
        }

        if($error) {
            return null;
        }

        $course = OnlineCourse::findOrFail($this->course_id);

        OnlineRegister::create([
            'user_id' =>(int) $profile->user_id,
            'course_id' => $this->course_id,
            'user_type' => 1
        ]);
        $model = OnlineRegister::orderBy('id', 'DESC')->first();
        if ($course->auto == 2){
            $model->status = 1;
            $model->approved_step = '1/1';

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
        // update training process
        $online_course = OnlineCourse::find($this->course_id, ['id', 'code', 'name', 'start_date', 'end_date', 'cert_code', 'subject_id']);
        $subject = Subject::find($online_course->subject_id, ['id', 'code', 'name']);
        event(new SaveTrainingProcessRegister($online_course, $subject, $profile->user_id, null, 1));
    }

    public function startRow(): int
    {
        return 2;
    }

}
