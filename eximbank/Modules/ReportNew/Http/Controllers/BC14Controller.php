<?php

namespace Modules\ReportNew\Http\Controllers;

use App\Models\Categories\AbsentReason;
use App\Models\Categories\StudentCost;
use App\Models\Categories\Unit;
use App\Models\Categories\UnitManager;
use Illuminate\Http\Request;
use Modules\Offline\Entities\OfflineCourseCost;
use Modules\Offline\Entities\OfflineRegister;
use Modules\Offline\Entities\OfflineSchedule;
use Modules\Offline\Entities\OfflineStudentCost;
use Modules\ReportNew\Entities\BC12;
use Modules\ReportNew\Entities\BC14;

class BC14Controller extends ReportNewController
{
    public function review(Request $request, $key)
    {
        $name_obj = $request->name_obj;
        $obj_arr = $this->categoriesList();
        $report = parent::reportList();
        $reportGroupList = $this->reportGroupList();
        return view('reportnew::review', [
            'report' => strtolower($key),
            'name'=> $report[strtoupper($key)],
            'obj_arr' => $obj_arr,
            'name_obj' => ($name_obj),
            'reportList' => $reportGroupList,
        ]);
    }

    public function getData(Request $request)
    {
        $obj = $request->name_obj;
        if (!$obj) return;

        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'asc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = BC14::$obj();

        $count = $query->count();
        $query->orderBy($sort, 'ASC');
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();

        foreach ($rows as $row) {
            $row->status = ($row->status == 1) ? trans('labutton.enable') : trans('labutton.disable');

            //unit
            if ($obj == 'Unit'){
                $unit_manager = UnitManager::query()
                    ->select([
                        \DB::raw('CONCAT(user_code ,\' - \', lastname, \' \', firstname) as fullname')
                    ])
                    ->from('el_unit_manager as a')
                    ->leftJoin('el_profile as b', 'b.code', '=', 'a.user_code')
                    ->where('a.unit_code', '=', $row->code)
                    ->pluck('fullname')->toArray();
                $row->unit_manager = implode('; ', $unit_manager);
            }

            //title
            if ($obj == 'Titles'){
                switch ($row->group) {
                    case 'CH': $row->group = 'Cửa hàng'; break;
                    case 'CNT': $row->group = 'Chi nhánh tỉnh'; break;
                    case 'VP': $row->group = 'Văn Phòng'; break;
                    case 'NM': $row->group = 'Công ty con nhà máy'; break;
                    default: $row->group = ''; break;
                }
            }

            //training_cost
            if ($obj == 'TrainingCost'){
                switch ($row->type){
                    case 1: $row->type = 'Chi phí tổ chức'; break;
                    case 2: $row->type = 'Chi phí phòng đào tạo'; break;
                    case 3: $row->type = 'Chi phí đào tạo bên ngoài'; break;
                    case 4: $row->type = 'Chi phí giảng viên'; break;
                }
            }
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    private function categoriesList(){
        return [
            'Unit' => trans('latraining.unit'),
            'Area' => trans('lamenu.area'),
            'UnitType' => 'Loại đơn vị',
            'Titles' => trans('latraining.title'),
            'Cert' => 'Trình độ',
            'Position' =>trans('laprofile.position'),
            'TrainingProgram' => trans('latraining.training_program'),
            'LevelSubject' => 'Mảng nghiệp vụ',
            'Subject' => trans('ladashboard.subject'),
            'TrainingLocation' => 'Địa điểm đào tạo',
            'TrainingForm' => 'Loại hình đào tạo',
            'TrainingType' => 'Hình thức đào tạo',
            'TrainingObject' => 'Nhóm đối tượng đào tạo',
            'Absent' => 'Loại nghỉ',
            'Discipline' => 'Danh sách vi phạm',
            'AbsentReason' => 'Lý do vắng mặt',
            'QuizType' => 'Loại kỳ thi',
            'TrainingCost' => 'Chi phí đào tạo',
            'StudentCost' =>  trans('latraining.student_cost'),
            'CommitMonth' => 'Cam kết',
            'TrainingTeacher' => trans('lareport.teacher'),
            'TeacherType' => 'Loại giảng viên',
            'TrainingPartner' => 'Đối tác',
            'Province' => 'Tỉnh thành',
            'District' => 'Quận huyện',
        ];
    }
}
