<?php
namespace Modules\Offline\Imports;

use App\Models\Categories\Subject;
use App\Models\Categories\Titles;
use App\Models\Categories\Unit;
use App\Models\PermissionTypeUnit;
use App\Models\ProfileView;
use App\Models\UserPermissionType;
use Illuminate\Support\Facades\Auth;
use Modules\Offline\Entities\OfflineCourse;
use Modules\Offline\Entities\OfflineInviteRegister;
use Modules\Offline\Entities\OfflineObject;
use Modules\Offline\Entities\OfflineRegister;
use App\Models\Profile;
use App\Models\Permission;
use App\Models\UserRole;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Modules\User\Entities\TrainingProcess;
use App\Events\SaveTrainingProcessRegister;
use App\Models\User;

class RegisterImport implements ToModel, WithStartRow
{
    public $errors;
    public $data;
    public $success = 0;
    public $fail = 0;
    public $course_id;
    public $class_id;

    public function __construct($course_id, $class_id)
    {
        $this->course_id = $course_id;
        $this->class_id = $class_id;
    }

    public function model(array $row)
    {
        $this->errors = [];
        $error = false;
        //$user_code = (string) $row[1];
        $user_name = (string)$row[1];

        $user = User::whereUsername($user_name)->first(['id']);
        $profile = ProfileView::where('user_id', '=', @$user->id)->first();

        if (empty($profile)) {
            $this->errors[] = '<strong class="text-danger"> Không tồn tại </strong>';
            $error = true;
        }

        $user_invited = false;
        $check_user_invited = OfflineInviteRegister::query()
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

        $title = Titles::where('code', '=', $profile->title_code)->first();
        $unit = Unit::where('code', '=', $profile->unit_code)->first();

        $fullname = $profile->full_name;
        $course_object_title = OfflineObject::where('course_id', '=', $this->course_id)->whereNotNull('title_id')->pluck('title_id')->toArray();

        $course_object_unit = OfflineObject::where('course_id', '=', $this->course_id)->whereNotNull('unit_id')->pluck('unit_id')->toArray();

        if (count($course_object_title) > 0){
            if (!in_array($title->id, $course_object_title)){
                $this->errors[] = 'Chức danh của <b>'. $fullname .'</b> không thể đăng kí khóa học';
                $error = true;
            }
        }

        if (count($course_object_unit) > 0){
            if (!in_array($unit->id, $course_object_unit)){
                $this->errors[] = 'Đơn vị của <b>'. $fullname .'</b> không thể đăng kí khóa học';
                $error = true;
            }
        }

        $register = OfflineRegister::where('user_id', '=', $profile->user_id)->where('course_id', '=', $this->course_id)->exists();

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

        if ($user_invited){
            if ($num_register == 0){
                $this->errors[] = 'Đã đủ SL. Mã nhân viên <b>'. $row[1] .'</b> không thể đăng kí khóa học';
                $error = true;
            }else{
                $num_register -= 1;

                OfflineInviteRegister::query()
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

        OfflineRegister::create([
            'user_id' =>(int) $profile->user_id,
            'course_id' => $this->course_id,
            'class_id' => $this->class_id,
        ]);
        $offline_course = OfflineCourse::find($this->course_id, ['id', 'code', 'name', 'start_date', 'end_date', 'cert_code', 'subject_id']);
        $subject = Subject::find($offline_course->subject_id, ['id', 'code', 'name']);
        event(new SaveTrainingProcessRegister($offline_course, $subject, $profile->user_id, $this->class_id, 2));
    }

    public function startRow(): int
    {
        return 2;
    }

}
