<?php
namespace Modules\Offline\Imports;

use App\Models\Categories\Subject;
use App\Models\Categories\Titles;
use App\Models\ProfileView;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Modules\Quiz\Entities\Quiz;
use Modules\Quiz\Entities\QuizPart;
use Modules\Quiz\Entities\QuizRegister;
use App\Events\SaveTrainingProcessRegister;
use Modules\Offline\Entities\OfflineCourse;
use Modules\Offline\Entities\OfflineCourseClass;
use Modules\Offline\Entities\OfflineObject;
use Modules\Offline\Entities\OfflineRegister;
use App\Models\Permission;
use App\Models\UserRole;
use App\Models\Categories\Unit;

class RegisterMultiCourseImport implements ToModel, WithStartRow
{
    public $errors;
    private $setStartRow = 1;

    public function __construct()
    {
        $this->errors = [];
    }

    public function model(array $row)
    {
        if($this->setStartRow == 1){
            if($row[0] != 'RegisterMultiCourseImport'){
                $this->errors[] = 'File import không đúng';

                return null;
            }
        }

        if($this->setStartRow >= 4){
            $error = false;
            $index = $row[0];
            $user_code = $row[1];
            $full_name = $row[2];
            $course_code = $row[3];
            $class_code = $row[4];

            $profile = ProfileView::where('code', '=', $user_code)->first();
            $course = OfflineCourse::whereCode($course_code)->first();

            if (empty($profile)) {
                $this->errors[] = 'Dòng '. $index .': Mã nhân viên <b>'. $user_code .'</b> không tồn tại';
                $error = true;
            }

            if (empty($course)) {
                $this->errors[] = 'Dòng '. $index .': Mã Khoá học <b>'. $course_code .'</b> không tồn tại';
                $error = true;
            }

            if(isset($profile) && isset($course)){
                $class= OfflineCourseClass::whereCourseId($course->id)->where('code', $class_code)->first();
                if (empty($class)) {
                    $this->errors[] = 'Dòng '. $index .': Mã lớp học <b>'. $class_code .'</b> không tồn tại trong Khoá học <b>'. $course->name .' ('. $course->code .')</b>';
                    $error = true;
                }

                $title = Titles::where('code', '=', $profile->title_code)->first();
                $fullname = $profile->full_name;
                $course_object = OfflineObject::where('course_id', '=', $course->id)->pluck('title_id')->toArray();

                if (!empty($course_object) && !in_array($title->id, $course_object)){
                    $this->errors[] = 'Dòng '. $index .': Chức danh của <b>'. $fullname .'</b> không thể đăng kí khóa học <b>'. $course->name .' ('. $course->code .')</b>';
                    $error = true;
                }

                $register = OfflineRegister::where('user_id', '=', $profile->user_id)
                    ->where('course_id', '=', $course->id)
                    ->first();

                if ($register) {
                    $this->errors[] = 'Dòng '. $index .': Mã nhân viên <b>'. $user_code .'</b> đã đăng kí khóa học <b>'. $course->name .' ('. $course->code .')</b>';
                    $error = true;
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
                            $this->errors[] = 'Dòng '. $row[0] .': Nhân viên Không thuộc đơn vị quản lý: '. $user_role->name .'';
                            $error = true;
                        }
                    } else {
                        if($profile->unit_id != $user_role->unit_id) {
                            $this->errors[] = 'Dòng '. $row[0] .': Nhân viên Không thuộc đơn vị quản lý: '. $user_role->name .'';
                            $error = true;
                        }
                    }
                }
            }

            if(!$error) {
                OfflineRegister::create([
                    'user_id' => (int) $profile->user_id,
                    'course_id' => $course->id,
                    'class_id' => $class->id,
                ]);

                // update training process
                $subject = Subject::find($course->subject_id, ['id', 'code', 'name']);
                event(new SaveTrainingProcessRegister($course, $subject, $profile->user_id, $class->id, 2));
            }
        }else{
            $this->setStartRow++;
        }
    }

    public function startRow(): int
    {
        return 1;
    }
}
