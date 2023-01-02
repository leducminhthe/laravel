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
use App\Models\PreviewImport;
use Modules\Offline\Entities\OfflineCourseClass;
use App\Models\Permission;
use App\Models\UserRole;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Modules\User\Entities\TrainingProcess;

class RegisterImportClass implements ToModel, WithStartRow
{
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
        $errors = [];
        $error = false;
        $user_code = (string) $row[1];

        if (empty($row[1])){
            $errors[1] = '<strong class="text-danger"> Không được trống </strong>';
            $error = true;
        }

        if (empty(trim($row[2]))){
            $errors[2] = '<strong class="text-danger"> Không được trống </strong>';
            $error = true;
        }

        $profile = ProfileView::where('code', '=', $user_code)->first();
        if (empty($profile) && !$errors[1]) {
            $errors[1] = '<strong class="text-danger"> Không tồn tại </strong>';
            $error = true;
        }

        // check mã lớp học
        $checkCodeClass = OfflineCourseClass::where('course_id', $this->course_id)->where('code', trim($row[2]))->first(['id']);
        if (empty($checkCodeClass) && !$errors[1]){
            $errors[1] = '<strong class="text-danger"> Không tồn tại </strong>';
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

            if (!in_array($user_code, $arr_user_code) && !$errors[1]){
                $errors[1] = '<strong class="text-danger"> Không thuộc đơn vị bạn quản lý </strong>';
                $error = true;
            }
        }

        $title = Titles::where('code', '=', $profile->title_code)->first();
        $unit = Unit::where('code', '=', $profile->unit_code)->first();

        $fullname = $profile->lastname . ' ' . $profile->firstname;
        $course_object_title = OfflineObject::where('course_id', '=', $this->course_id)->whereNotNull('title_id')->pluck('title_id')->toArray();

        $course_object_unit = OfflineObject::where('course_id', '=', $this->course_id)->whereNotNull('unit_id')->pluck('unit_id')->toArray();

        if (count($course_object_title) > 0 && !$errors[1]){
            if (!in_array($title->id, $course_object_title)){
                $errors[1] = '<strong class="text-danger"> Chức danh của nhân viên không thể đăng kí </strong>';
                $error = true;
            }
        }

        if (count($course_object_unit) > 0 && !$errors[1]){
            if (!in_array($unit->id, $course_object_unit)){
                $errors[1] = '<strong class="text-danger"> Đơn vị của nhân viên không thể đăng kí </strong>';
                $error = true;
            }
        }

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
                    $errors[1] = 'Nhân viên Không thuộc đơn vị quản lý: '. $user_role->name .'';
                    $error = true;
                }
            } else {
                if($profile->unit_id != $user_role->unit_id) {
                    $errors[1] = 'Nhân viên Không thuộc đơn vị quản lý: '. $user_role->name .'';
                    $error = true;
                }
            }
        }

        $register = OfflineRegister::where('user_id', '=', $profile->user_id)
        ->where('course_id', '=', $this->course_id)->first();

        if ($register && !$errors[1]) {
            $errors[1] = '<strong class="text-danger"> Nhân viên đã đăng kí </strong>';
            $error = true;
        }

        if ($user_invited){
            if ($num_register == 0 && !$errors[1]){
                $errors[1] = '<strong class="text-danger"> Đã đủ SL không thể đăng kí khóa học </strong>';
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
            $this->fail += 1;
        } else {
            $this->success += 1;

            $model = new PreviewImport();
            $model->name_import = 'register_offline';
            $model->column1 = (int) $profile->user_id;
            $model->column2 = $this->course_id;
            $model->column3 = $checkCodeClass->id;
            $model->save();
        }

        $this->data[] = [
            $row[0],
            $errors[1] ? $errors[1] : $row[1],
            $errors[2] ? $errors[2] : $row[2],
            $error ? 'error' : 'success'
        ];
    }

    public function startRow(): int
    {
        return 2;
    }

}
