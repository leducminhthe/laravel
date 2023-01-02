<?php
namespace Modules\Online\Imports;

use App\Models\Categories\Titles;
use App\Models\UserPermissionType;
use Illuminate\Support\Facades\Auth;
use Modules\Online\Entities\OnlineCourse;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Modules\Online\Entities\SettingJoinOnlineCourse;
use App\Models\Categories\TitleRank;

class SettingJoinImport implements ToModel, WithStartRow
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
        $type = trim($row[1]);
        $code = trim($row[2]);
        $date_register = $row[3];
        $date_register_join_company = $row[4];

        if (empty($code)) {
            $this->errors[] = 'Mã chức danh hoặc cấp bậc chức danh dòng: <b>'. $row[0] .'</b> không được trống';
            return null;
        }

        if (empty($type)) {
            $this->errors[] = 'Hình thức dòng: <b>'. $row[0] .'</b> không được trống';
            return null;
        }

        if($type == 1) {
            $check_title = Titles::where('code', $code)->first(['id']);
            if (empty($check_title)) {
                $this->errors[] = 'Chức danh hoặc cấp bậc chức danh dòng: <b>'. $row[0] .'</b> không tồn tại';
                return null;
            }
        } else {
            $check_level_title = TitleRank::where('code', $code)->first(['id']);
            if (empty($check_level_title)) {
                $this->errors[] = 'Chức danh hoặc cấp bậc chức danh dòng: <b>'. $row[0] .'</b> không tồn tại';
                return null;
            }
        }

        if($type == 1) {
            SettingJoinOnlineCourse::updateOrCreate([
                'course_id' => $this->course_id,
                'title_id' => $check_title->id,
            ],[
                'course_id' => $this->course_id,
                'title_id' => $check_title->id,
                'date_register' => $date_register,
                'date_register_join_company' => $date_register_join_company,
                'auto_register' => 1,
            ]);
        } else {
            SettingJoinOnlineCourse::updateOrCreate([
                'course_id' => $this->course_id,
                'title_rank_id' => $check_level_title->id,
            ],[
                'course_id' => $this->course_id,
                'title_rank_id' => $check_level_title->id,
                'date_register' => $date_register,
                'date_register_join_company' => $date_register_join_company,
                'auto_register' => 1,
            ]);
        }
    }

    public function startRow(): int
    {
        return 2;
    }

}
