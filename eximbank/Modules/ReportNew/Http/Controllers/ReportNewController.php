<?php

namespace Modules\ReportNew\Http\Controllers;

use App\Models\Categories\Titles;
use App\Models\Categories\TrainingTeacher;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Excel;
use Modules\Online\Entities\OnlineCourse;
use Modules\Offline\Entities\OfflineCourse;
use Modules\ReportNew\Entities\HistoryExport;
use Modules\TrainingRoadmap\Entities\TrainingRoadmap;
use Modules\TrainingByTitle\Entities\TrainingByTitleDetail;
use Modules\Quiz\Entities\QuestionAnswer;
use Modules\Quiz\Entities\QuizQuestion;
use Modules\Quiz\Entities\Question;

class ReportNewController extends Controller
{
    public function index()
    {
        $group_reports = $this->reportGroupList();
        $count = [];
        foreach ($group_reports as $key => $group) {
            $count[$key] = 0;
            foreach ($group as $keyitem => $item) {
                if(userCan('report-'.(str_replace('BC', '', $keyitem)))) {
                    $count[$key] += 1;
                }
            }
        }
        return view('reportnew::index', [
            'group_reports' => $group_reports,
            'count' => $count,
        ]);
    }

    public function review(Request $request, $report) {
        $class_name = 'Modules\ReportNew\Http\Controllers\\'. strtoupper($report). 'Controller';
        if (class_exists($class_name)) {
            $controller = new $class_name();
            return $controller->review($request, $report);
        }
        abort(404);
    }

    public function getData(Request $request) {
        $report = $request->report;
        if (!$report) return;
        $class_name = "Modules\ReportNew\Http\Controllers\\". $report . 'Controller';
        if (class_exists($class_name)) {
            $controller = new $class_name();
            return $controller->getData($request);
        }
        abort(404);
    }

    public function export(Request $request)
    {
        $list_report = $this->reportList();

        $rpt = $request->report;
        $name_report = $list_report[$rpt];

        $class_name = "Modules\ReportNew\Export\\". $rpt . 'Export';
        if (class_exists($class_name)){
            $report = new $class_name($request);
            ob_end_clean();
            ob_start();
            return $report->download(Str::slug($name_report, '_') .'_'. date('d_m_Y') .'.xlsx', Excel::XLSX);
        }
        abort(404);
    }

    public function dataChart(Request $request) {
        $report = $request->report;
        if (!$report) {
            return false;
        }

        $class_name = "Modules\ReportNew\Http\Controllers\\". $report . 'Controller';
        if (class_exists($class_name)) {
            $controller = new $class_name();
            return $controller->dataChart($request);
        }

        abort(404);
    }

    public function filter(Request $request)
    {
        $search = $request->search;
        if ($request->type=='course') {
            $from_date = $request->from_date;
            $to_date = $request->to_date;

            if ($request->course_type==1) {
                $query = OnlineCourse::where('status','=',1);
            }elseif($request->course_type==2) {
                $query = OfflineCourse::where('status','=',1);
            }else{
                return null;
            }
            if ($search) {
                $query->where(function ($join) use ($search){
                    $join->where('name', 'like', '%'. $search .'%');
                    $join->orWhere('code', 'like', '%'. $search .'%');
                });
            }
            if ($from_date && $to_date){
                $query->where('start_date', '>=', date_convert($from_date));
                $query->where('start_date', '<=', date_convert($to_date, '23:59:59'));
            }

            $paginate = $query->paginate(10);
            $data['results'] = $query->get(\DB::raw('id, CONCAT(code, \' - \', name) AS text'));
            if ($paginate->nextPageUrl()) {
                $data['pagination'] = ['more' => true];
            }
            return json_result($data);
        }elseif ($request->type=='teacher'){
            return TrainingTeacher::getTeacherSelect2($request);
        }elseif ($request->type=='SubjectByTitle'){
            return TrainingRoadmap::getSubjectByTitle($request);
        }elseif ($request->type == 'titleAll'){
            $query = Titles::query();
            $query->where('status', '=', 1);

            if ($search) {
                $query->where('name', 'like', '%'. $search .'%');
            }

            $paginate = $query->paginate(10);
            $data['results'] = $query->get(['id', 'name AS text']);
            if ($paginate->nextPageUrl()) {
                $data['pagination'] = ['more' => true];
            }

            return json_result($data);
        } else if ($request->type == 'TrainingTitle') {
            $training_title_category = $request->training_title_category;
            $query = TrainingByTitleDetail::query();
            $query->where('training_title_category_id', '=', $training_title_category);

            $paginate = $query->paginate(10);
            $data['results'] = $query->get(['subject_id as id', 'subject_name AS text']);
            if ($paginate->nextPageUrl()) {
                $data['pagination'] = ['more' => true];
            }

            return json_result($data);
        } else if ($request->type == 'Quiz') {
            $quizCategory = QuizQuestion::where('quiz_id', $request->quiz_id)->pluck('qcategory_id')->toArray();
            $quizQuestion = Question::whereIn('category_id', $quizCategory)->pluck('id')->toArray();
            $maxCount = \DB::table('el_question_answer')
            ->select(\DB::raw('count(question_id) as total'))
            ->whereIn('question_id', $quizQuestion)
            ->orderBy('total', 'DESC')
            ->groupBy('question_id')
            ->first();
            return json_result($maxCount->total);
        }
    }

    public function reportGroupList() {
        return [
            'training_activity' => [
                'BC09' => trans('lareport.report_title_9'), // 'Thống kê tình hình đào tạo nhân viên tân tuyển'
                'BC12' => trans('lareport.report_title_12'), // 'Thống kê chi tiết học viên theo đơn vị'
                'BC24' => trans('lareport.report_title_24'), // 'Tổng hợp tình hình tham gia đào tạo các khóa E-Learning theo đơn vị'
                'BC05' => trans('lareport.report_title_5'), // 'Báo cáo học viên tham gia khóa học tập trung / trực tuyến'
                'BC06' => trans('lareport.report_title_6'), // 'Danh sách học viên của đơn vị theo chuyên đề'
                'BC25' => trans('lareport.report_title_25'), // 'Tổng hợp tình hình tham gia đào tạo các khóa E-Learning theo chuyên đề'
                'BC08' => trans('lareport.report_title_8'), // 'Tổng hợp tình hình tổ chức các khóa học nội bộ và bên ngoài'
                'BC11' => trans('lareport.report_title_11'), //  'Thống kê Giảng viên Đào tạo (Nội bộ & bên ngoài) theo Tháng / Quý / Năm'
                'BC23' => trans('lareport.report_title_23'), // 'Thống kê tỷ lệ hoàn thành tháp đào tạo theo chức danh'
                'BC29' => trans('lareport.report_title_29'), //  'Báo cáo kết quả thực hiện so với kế hoạch quý / năm'
                'BC15' => trans('lareport.report_title_15'), //  'Báo cáo tổng hợp kết quả theo tháp đào tạo'
//                'BC30' => trans('lareport.report_title_30'), //  'Báo cáo kết quả đánh giá khóa học'
                'BC07' => trans('lareport.report_title_7'), // 'Báo cáo quá trình đào tạo của nhân viên'
                'BC10' => trans('lareport.report_title_10'), // 'Danh sách CBNV không chấp hành nội quy đào tạo'
                // 'BC21' => trans('lareport.report_title_21'), //  'Danh sách các khóa học trực tuyến đang mở'
                //'BC22' => trans('lareport.report_title_22'), // 'Danh sách các chuyên đề gộp / tách'
                'BC33' => trans('lareport.report_title_33'), // 'Danh sách khảo sát'
				'BC35' => trans('lareport.report_title_35'), // 'BÁO CÁO TÌNH HÌNH TỔ CHỨC ĐÀO TẠO E-LEARNING/TẬP TRUNG'
                'BC36' => trans('lareport.report_title_36'), // 'BÁO CÁO TỈ LỆ HỌC PHẦN THEO LỘ TRÌNH ĐÀO TẠO'
                'BC38' => trans('lareport.report_title_38'), // 'THỐNG KÊ TẤT CẢ NHÂN VIÊN THEO KHÓA HỌC'
            ],
            'quiz_manager' => [
                'BC34' => trans('lareport.report_title_34'), //Báo cáo thống kê ngân hàng câu hỏi
                'BC04' => trans('lareport.report_title_4'), // 'Báo cáo tỉ lệ trả lời đúng từng câu hỏi trong ngân hàng câu hỏi'
                'BC01' => trans('lareport.report_title_1'), // 'Báo cáo số liệu công tác khảo thi'
                'BC02' => trans('lareport.report_title_2'), // 'Báo cáo số liệu điểm thi chi tiết'
                'BC28' => trans('lareport.report_title_28'), // 'Báo cáo kết quả chi tiết theo kỳ thi'
                'BC37' => trans('lareport.report_title_37'), // 'Báo cáo kết quả chi tiết tỷ lệ trả lời câu hỏi theo kỳ thi'
                // 'BC03' => trans('lareport.report_title_3'), // 'Báo cáo cơ cấu đề thi'
            ],
            'cost' => [
                'BC13' => trans('lareport.report_title_13'), //  'Báo cáo chi phí đào tạo theo khu vực'
                'BC17' => trans('lareport.report_title_17'), //  'Danh sách xác nhận bồi hoàn chi phí đào tạo đối với CBNV tân tuyển'
                'BC18' => trans('lareport.report_title_18'), // 'Danh sách xác nhận bồi hoàn chi phí đào tạo đối với CBNV có cam kết'
                'BC26' => trans('lareport.report_title_26'), // 'Báo cáo thù lao giảng viên'
                'BC27' => trans('lareport.report_title_27'), //  'Báo cáo chi phí đào tạo'
            ],
            'other' => [
                // 'BC14' => trans('lareport.report_title_14'), // 'Export danh mục'
                'BC31' => trans('lareport.report_title_31'), //  'Báo cáo tổng giờ học của học viên'
                'BC40' => trans('lareport.report_title_40'), // 'Báo cáo chi tiết tổng giờ học của học viên theo khóa học'
                'BC32' => trans('lareport.report_title_32'), //  'Báo cáo tổng giờ học theo từng đơn vị, chức danh'
                'BC16' => trans('lareport.report_title_16'), //  'Báo cáo lịch sử giảng dạy'
                'BC41' => 'Báo cáo đánh giá khung năng lực theo chức danh', // 'Báo cáo đánh giá khung năng lực theo chức danh'
            ],
        ];
    }

    public function reportList() {
        return [
            'BC09' => trans('lareport.report_title_9'), // 'Thống kê tình hình đào tạo nhân viên tân tuyển'
            'BC12' => trans('lareport.report_title_12'), // 'Thống kê chi tiết học viên theo đơn vị'
            'BC24' => trans('lareport.report_title_24'), // 'Tổng hợp tình hình tham gia đào tạo các khóa E-Learning theo đơn vị'
            'BC05' => trans('lareport.report_title_5'), // 'Báo cáo học viên tham gia khóa học tập trung / trực tuyến'
            'BC06' => trans('lareport.report_title_6'), // 'Danh sách học viên của đơn vị theo chuyên đề'
            'BC25' => trans('lareport.report_title_25'), // 'Tổng hợp tình hình tham gia đào tạo các khóa E-Learning theo chuyên đề'
            'BC08' => trans('lareport.report_title_8'), // 'Tổng hợp tình hình tổ chức các khóa học nội bộ và bên ngoài'
            'BC11' => trans('lareport.report_title_11'), //  'Thống kê Giảng viên Đào tạo (Nội bộ & bên ngoài) theo Tháng / Quý / Năm'
            'BC23' => trans('lareport.report_title_23'), // 'Thống kê tỷ lệ hoàn thành tháp đào tạo theo chức danh'
            'BC29' => trans('lareport.report_title_29'), //  'Báo cáo kết quả thực hiện so với kế hoạch quý / năm'
            'BC15' => trans('lareport.report_title_15'), //  'Báo cáo tổng hợp kết quả theo tháp đào tạo'
            'BC30' => trans('lareport.report_title_30'), //  'Báo cáo kết quả đánh giá khóa học'
            'BC07' => trans('lareport.report_title_7'), // 'Báo cáo quá trình đào tạo của nhân viên'
            'BC10' => trans('lareport.report_title_10'), // 'Danh sách CBNV không chấp hành nội quy đào tạo'
            // 'BC21' => trans('lareport.report_title_21'), //  'Danh sách các khóa học trực tuyến đang mở'
            //'BC22' => trans('lareport.report_title_22'), // 'Danh sách các chuyên đề gộp / tách'

            'BC04' => trans('lareport.report_title_4'), // 'Báo cáo tỉ lệ trả lời đúng từng câu hỏi trong ngân hàng câu hỏi'
            'BC01' => trans('lareport.report_title_1'), // 'Báo cáo số liệu công tác khảo thi'
            'BC02' => trans('lareport.report_title_2'), // 'Báo cáo số liệu điểm thi chi tiết'
            'BC28' => trans('lareport.report_title_28'), // 'Báo cáo kết quả chi tiết theo kỳ thi'
            // 'BC03' => trans('lareport.report_title_3'), // 'Báo cáo cơ cấu đề thi'

            'BC13' => trans('lareport.report_title_13'), //  'Báo cáo chi phí đào tạo theo khu vực'
            'BC17' => trans('lareport.report_title_17'), //  'Danh sách xác nhận bồi hoàn chi phí đào tạo đối với CBNV tân tuyển'
            'BC18' => trans('lareport.report_title_18'), // 'Danh sách xác nhận bồi hoàn chi phí đào tạo đối với CBNV có cam kết'
            'BC26' => trans('lareport.report_title_26'), // 'Báo cáo thù lao giảng viên'
            'BC27' => trans('lareport.report_title_27'), //  'Báo cáo chi phí đào tạo'

            // 'BC14' => trans('lareport.report_title_14'), // 'Export danh mục'
            'BC31' => trans('lareport.report_title_31'), //  'Báo cáo tổng giờ học của học viên'
            'BC32' => trans('lareport.report_title_32'), //  'Báo cáo tổng giờ học theo từng đơn vị, chức danh'
            'BC16' => trans('lareport.report_title_16'), //  'Báo cáo lịch sử giảng dạy'
            'BC33' => trans('lareport.report_title_33'), //  'Báo cáo lịch sử giảng dạy'
            'BC09' => trans('lareport.report_title_9'), // 'Thống kê tình hình đào tạo nhân viên tân tuyển'
            'BC12' => trans('lareport.report_title_12'), // 'Thống kê chi tiết học viên theo đơn vị'
            'BC24' => trans('lareport.report_title_24'), // 'Tổng hợp tình hình tham gia đào tạo các khóa E-Learning theo đơn vị'
            'BC05' => trans('lareport.report_title_5'), // 'Báo cáo học viên tham gia khóa học tập trung / trực tuyến'
            'BC06' => trans('lareport.report_title_6'), // 'Danh sách học viên của đơn vị theo chuyên đề'
            'BC25' => trans('lareport.report_title_25'), // 'Tổng hợp tình hình tham gia đào tạo các khóa E-Learning theo chuyên đề'
            'BC08' => trans('lareport.report_title_8'), // 'Tổng hợp tình hình tổ chức các khóa học nội bộ và bên ngoài'
            'BC11' => trans('lareport.report_title_11'), //  'Thống kê Giảng viên Đào tạo (Nội bộ & bên ngoài) theo Tháng / Quý / Năm'
            'BC23' => trans('lareport.report_title_23'), // 'Thống kê tỷ lệ hoàn thành tháp đào tạo theo chức danh'
            'BC29' => trans('lareport.report_title_29'), //  'Báo cáo kết quả thực hiện so với kế hoạch quý / năm'
            'BC15' => trans('lareport.report_title_15'), //  'Báo cáo tổng hợp kết quả theo tháp đào tạo'
//            'BC30' => trans('lareport.report_title_30'), //  'Báo cáo kết quả đánh giá khóa học'
            'BC07' => trans('lareport.report_title_7'), // 'Báo cáo quá trình đào tạo của nhân viên'
            'BC10' => trans('lareport.report_title_10'), // 'Danh sách CBNV không chấp hành nội quy đào tạo'
            //'BC22' => trans('lareport.report_title_22'), // 'Danh sách các chuyên đề gộp / tách'
            'BC34' => trans('lareport.report_title_34'), // Báo cáo thống kê ngân hàng câu hỏi
            'BC04' => trans('lareport.report_title_4'), // 'Báo cáo tỉ lệ trả lời đúng từng câu hỏi trong ngân hàng câu hỏi'
            'BC01' => trans('lareport.report_title_1'), // 'Báo cáo số liệu công tác khảo thi'
            'BC02' => trans('lareport.report_title_2'), // 'Báo cáo số liệu điểm thi chi tiết'
            'BC28' => trans('lareport.report_title_28'), // 'Báo cáo kết quả chi tiết theo kỳ thi'
            'BC13' => trans('lareport.report_title_13'), //  'Báo cáo chi phí đào tạo theo khu vực'
            'BC17' => trans('lareport.report_title_17'), //  'Danh sách xác nhận bồi hoàn chi phí đào tạo đối với CBNV tân tuyển'
            'BC18' => trans('lareport.report_title_18'), // 'Danh sách xác nhận bồi hoàn chi phí đào tạo đối với CBNV có cam kết'
            'BC26' => trans('lareport.report_title_26'), // 'Báo cáo thù lao giảng viên'
            'BC27' => trans('lareport.report_title_27'), //  'Báo cáo chi phí đào tạo'
            // 'BC14' => trans('lareport.report_title_14'), // 'Export danh mục'
            'BC31' => trans('lareport.report_title_31'), //  'Báo cáo tổng giờ học của học viên'
            'BC32' => trans('lareport.report_title_32'), //  'Báo cáo tổng giờ học theo từng đơn vị, chức danh'
            'BC16' => trans('lareport.report_title_16'), //  'Báo cáo lịch sử giảng dạy'
			'BC35' => trans('lareport.report_title_35'), // 'BÁO CÁO TÌNH HÌNH TỔ CHỨC ĐÀO TẠO E-LEARNING/TẬP TRUNG'
            'BC36' => trans('lareport.report_title_36'), // 'BÁO CÁO TỈ LỆ HỌC PHẦN THEO LỘ TRÌNH ĐÀO TẠO'
            'BC37' => trans('lareport.report_title_37'), // 'Báo cáo kết quả chi tiết tỷ lệ trả lời câu hỏi theo kỳ thi'
            'BC38' => trans('lareport.report_title_38'), // 'THỐNG KÊ TẤT CẢ NHÂN VIÊN THEO KHÓA HỌC'
            'BC40' => trans('lareport.report_title_40'), // 'Báo cáo chi tiết tổng giờ học của học viên theo khóa học'
            'BC41' => 'Báo cáo đánh giá khung năng lực theo chức danh', // 'Báo cáo đánh giá khung năng lực theo chức danh'
        ];
    }

    public function history(){
        return view('reportnew::export',[
        ]);
    }

    public function download($history_id)
    {
        $history = HistoryExport::find($history_id);
        $storage = \Storage::disk('local');
        $file_name = $storage->path($history->file_name);

        //$file_name = Config('app.datafile.dataroot'). '/uploads/'. $history->file_name;
        if (file_exists($file_name)) {
            return \Response::download($file_name);
        }

        return abort(404);
    }

    public function getDataHistoryExport(Request $request)
    {
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = HistoryExport::query();
        $count = $query->count();
        $query->orderBy('created_at', 'DESC');
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();

        foreach ($rows as $row){
            $row->created_at2 = get_date($row->created_at, 'H:i d/m/Y');
            $file_name = \Storage::disk('local')->path($row->file_name);
            //$file_name = Config('app.datafile.dataroot') . '/uploads/' . $row->file_name;

            $row->size = file_exists($file_name) ? round(filesize($file_name)/1024/1024, 2) : 0;

            $row->download = route('module.report_new.download', ['history_id' => $row->id]);
        }

        return \response()->json([
            'total' => $count,
            'rows' => $rows
        ]);
    }
}
