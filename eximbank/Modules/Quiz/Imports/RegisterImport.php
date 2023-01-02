<?php

namespace Modules\Quiz\Imports;

use App\Models\Automail;
use App\Models\ProfileView;
use Illuminate\Support\Carbon;
use Modules\Quiz\Entities\Quiz;
use Modules\Quiz\Entities\QuizPart;
use Modules\Quiz\Entities\QuizRegister;
use App\Models\Profile;
use App\Models\Permission;
use App\Models\UserRole;
use App\Models\Categories\Unit;
use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Modules\Quiz\Entities\QuizSettingAlert;
use Modules\Quiz\Entities\QuizUserSecondary;

class RegisterImport implements ToModel, WithStartRow
{
    public $errors;
    public $quiz_id;
    public $type;

    public function __construct($quiz_id, $type)
    {
        $this->type = $type;
        $this->errors = [];
        $this->quiz_id = $quiz_id;
    }

    public function model(array $row)
    {
        $error = false;
        $index = $row[0];
        if ($index) {
            $user_name = (string)$row[1];
            $full_name = (string)$row[2];
            $part_name = trim($row[3]);
            $type = $row[4];
            $position='<b> Dòng '.$index.': </b>';
            if ($this->type==1){
                $user = User::whereUsername($user_name)->first(['id']);
            }else{
                $user = User::whereCode($user_name)->first(['id']);
            }
            $profile = ProfileView::where('user_id', '=', @$user->id)->first();

            $quiz = Quiz::with('type')->find($this->quiz_id);
            $part = QuizPart::where('quiz_id', '=', $this->quiz_id)->where('name', '=', $part_name)->first();

            if (empty($user_name)) {
                $this->errors[] =$position. 'Tên đăng nhập dòng <b>' . $row[0] . '</b> không được trống';
                $error = true;
            }

            if (empty($part_name)) {
                $this->errors[] =$position. 'Ca thi <b>' . $row[0] . '</b> không được trống';
                $error = true;
            }
            if (empty($type)) {
                $this->errors[] =$position. 'Loại thí sinh <b>' . $row[0] . '</b> không được trống';
                $error = true;
            } elseif ($type < 1 || $type > 2) {
                $this->errors[] =$position. 'Loại thí sinh chỉ có giá trị 1 (Nội bộ) hoặc 2 (Bên ngoài)';
                $error = true;
            } elseif ($type != $this->type) {
                $this->errors[] =$position. 'Loại thí sinh chỉ phải là ' . $this->type;
                $error = true;
            }
            if (empty($profile)) {
                $this->errors[] =$position. 'Mã nhân viên <b>' . $row[1] . '</b> không tồn tại';
                $error = true;
            }
            if (empty($part)) {
                $this->errors[] =$position. 'Ca thi <b>' . $row[3] . '</b> không thuộc kỳ thi này';
                $error = true;
            }else{
                $now = date('Y-m-d H:i:s');
                if ($part->end_date<$now){
                    $this->errors[] =$position. 'Ca thi <b>' . $row[3] . '</b> đã kết thúc';
                    $error = true;
                }
            }
            if (isset($profile)) {
                $user_id = $profile->user_id;
                $register = QuizRegister::where('user_id', '=', $user_id)
                    ->where('quiz_id', '=', $this->quiz_id)
                    ->where('type', '=', $type)
                    ->where('part_id', $part->id)
                    ->first();
                if ($register) {
                    $this->errors[] = 'Mã nhân viên <b>' . $row[1] . '</b> đã đăng kí kỳ thi này';
                    $error = true;
                }

                $check = QuizRegister::query()
                ->from('el_quiz_register as a')
                ->leftJoin('el_quiz_result as b', function ($on) {
                    $on->on('b.user_id', '=', 'a.user_id')
                        ->on('b.quiz_id', '=', 'a.quiz_id')
                        ->on('b.part_id', '=', 'a.part_id');
                })
                ->where('a.user_id', '=', $user_id)
                ->where('a.quiz_id', $this->quiz_id)
                ->where('a.type', 1)
                ->where(function($sub){
                    $sub->whereNull('b.part_id');
                    $sub->orWhere('b.result', 1);
                })
                ->exists();
                if ($check) {
                    $this->errors[] = 'Mã nhân viên <b>' . $row[1] . '</b> phải rớt ca trước đó mới được đăng ký lại';
                    $error = true;
                }

                // kiểm tra nhân viên có thuộc đơn vị quản lý hay ko
                if (!Permission::isAdmin() && $this->type == 1) {
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
                    if ($user_role->type == 'group-child') {
                        $getArray = Unit::getArrayChild($user_role->code);
                        array_push($getArray, $user_role->unit_id);
                        if (!in_array((int)$profile->unit_id, $getArray)) {
                            $this->errors[] = 'Dòng ' . $row[0] . ': Nhân viên Không thuộc đơn vị quản lý: ' . $user_role->name . '';
                            return null;
                        }
                    } else {
                        if ($profile->unit_id != $user_role->unit_id) {
                            $this->errors[] = 'Dòng ' . $row[0] . ': Nhân viên Không thuộc đơn vị quản lý: ' . $user_role->name . '';
                            return null;
                        }
                    }
                }
            }
            if ($error) {
                return null;
            }
            QuizRegister::create([
                'user_id' => (int)$profile->user_id,
                'quiz_id' => $this->quiz_id,
                'part_id' => $part->id,
                'type' => $type,
            ]);

            if ($quiz->status == 1) {
                $user_id = $profile->user_id;
                $signature = getMailSignature($user_id, $type);
                $params = [
                    'signature' => $signature,
                    'gender' => $type == 1 ? ($profile->gender == '1' ? 'Anh' : 'Chị') : 'Anh/Chị',
                    'full_name' => $type == 1 ? $profile->full_name : $profile->name,
                    'firstname' => $type == 1 ? $profile->firstname : $profile->name,
                    'quiz_name' => $quiz->name,
                    'quiz_type' => $quiz->type ? $quiz->type->name : '',
                    'quiz_part_name' => $part->name,
                    'start_quiz_part' => get_datetime($part->start_date),
                    'end_quiz_part' => get_datetime($part->end_date),
                    'quiz_time' => $quiz->limit_time,
                    'pass_score' => $quiz->pass_score,
                    'url' => route('module.quiz.doquiz.index', ['quiz_id' => $this->quiz_id, 'part_id' => $part->id])
                ];
                $this->saveEmailQuizRegister($params, [$user_id], $part->id, $type);
            }
        }
    }

    public function startRow(): int
    {
        return 2;
    }

    public function saveEmailQuizRegister(array $params, array $user_id, int $part_id, int $user_type)
    {
        $automail = new Automail();
        $automail->template_code = 'quiz_registerd';
        $automail->params = $params;
        $automail->users = $user_id;
        $automail->user_type = $user_type;
        $automail->check_exists = true;
        $automail->check_exists_status = 0;
        $automail->object_id = $part_id;
        $automail->object_type = 'approve_quiz';
        $automail->addToAutomail();
    }
}
