<?php

namespace Modules\Offline\Http\Controllers;

use App\Models\Categories\Unit;
use App\Models\Profile;
use App\Scopes\DraftScope;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Offline\Entities\OfflineCourse;
use Modules\Offline\Entities\OfflineRegister;
use Modules\Quiz\Entities\Question;
use Modules\Quiz\Entities\QuestionCategory;
use Modules\Quiz\Entities\Quiz;
use Modules\Quiz\Entities\QuizAttempts;
use Modules\Quiz\Entities\QuizPart;
use Modules\Quiz\Entities\QuizPermission;
use Modules\Quiz\Entities\QuizQuestion;
use Modules\Quiz\Entities\QuizQuestionCategory;
use Modules\Quiz\Entities\QuizRank;
use Modules\Quiz\Entities\QuizRegister;
use Modules\Quiz\Entities\QuizResult;
use Modules\Quiz\Entities\QuizSetting;
use Modules\Quiz\Entities\QuizStatistic;
use Modules\Quiz\Entities\QuizTeacher;
use Modules\Quiz\Entities\QuizTemplates;
use Modules\Quiz\Entities\QuizTemplatesQuestion;
use Modules\Quiz\Entities\QuizTemplatesQuestionCategory;
use Modules\Quiz\Entities\QuizTemplatesRank;
use Modules\Quiz\Entities\QuizTemplatesSetting;
use Modules\Quiz\Entities\QuizType;
use Modules\UserPoint\Entities\UserPointItem;
use Modules\UserPoint\Entities\UserPointSettings;

class QuizController extends Controller
{
    public function index($course_id, Request $request) {
        $course = OfflineCourse::find($course_id);
        $page_title = $course->name;

        return view('offline::backend.quiz.index', [
            'course_id' => $course_id,
            'page_title' => $page_title,
            'course' => $course,
        ]);
    }

    public function getData($course_id, Request $request) {
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $search = $request->input('search');
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');

        if ($start_date) {
            $start_date = date_convert($start_date, '00:00:00');
        }

        if ($end_date) {
            $end_date = date_convert($end_date, '23:59:59');
        }

        $query = Quiz::query();
        $query->where('course_id', '=', $course_id);
        $query->where('course_type', '=', 2);

        if ($search) {
            $query->where(function ($sub_query) use ($search) {
                $sub_query->orWhere('name', 'like', '%'. $search .'%');
                $sub_query->orWhere('code', 'like', '%'. $search .'%');
            });
        }

        $dbprefix = \DB::getTablePrefix();
        if ($start_date) {
            $query->where(\DB::raw('(select MIN(start_date)
            from '.$dbprefix.'el_quiz_part
            where quiz_id = '.$dbprefix.'el_quiz.id)'), '>=', $start_date);
        }

        if ($end_date) {
            $query->where(\DB::raw('(select MIN(start_date)
            from '.$dbprefix.'el_quiz_part
            where quiz_id = '.$dbprefix.'el_quiz.id)'), '<=', $end_date);
        }

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();
        foreach ($rows as $row) {
            $start_date = '';
            $end_date = '';

            $qdate = QuizPart::query()->where('quiz_id', '=', $row->id);
            if ($qdate->exists()) {
                $start_date = $qdate->min('start_date');
                $end_date = $qdate->max('end_date');
            }

            $row->question = '';
            if (QuizPermission::addQuestionQuiz($row)) {
                $row->question = route('module.quiz.question', ['course_id' => $course_id, 'id' => $row->id]);
            }

            $row->edit_url = route('module.offline.quiz.edit', ['course_id' => $course_id, 'id' => $row->id, 'quiz_type_by_offline' => $row->quiz_type_by_offline]);
            $row->start_date = get_date($start_date, 'H:i d/m/Y');
            $row->end_date = get_date($end_date, 'H:i d/m/Y');
            $row->created_at2 = get_date($row->created_at, 'd/m/Y h:i');

            $row->quiz_type = 'Offline';
            $user_id = $row->updated_by ? $row->updated_by : $row->created_by;

            $row->report_url = route('module.offline.quiz.report', ['course_id' => $course_id, 'id' => $row->id]);
            $row->user_url = route('module.quiz.get_user_create_quiz',['user_id' => $user_id]);
            $row->quantity = QuizRegister::where('quiz_id', '=', $row->id)->count();
            $row->quantity_quiz_attempts = QuizResult::where('quiz_id', '=', $row->id)->where('timecompleted', '>', 0)->whereNull('text_quiz')->count();

            $row->user_approved_url = $row->approved_by ? route('module.quiz_template.get_user_create_quiz_template',['user_id' => $row->approved_by]) : '';
            $row->time_approved = $row->time_approved ? get_date($row->time_approved, 'd/m/Y h:i') : '';

            $quiz_type_by_offline = '';
            switch($row->quiz_type_by_offline){
                case 'quiz_id': $quiz_type_by_offline = trans("latraining.final_quiz"); break;
                case 'entrance_quiz_id': $quiz_type_by_offline = trans("latraining.first_quiz"); break;
                case 'register_quiz_id': $quiz_type_by_offline = 'Thi trước ghi danh'; break;
                case 'activity_quiz_id': $quiz_type_by_offline = 'Hoạt động kỳ thi'; break;
            }
            $row->quiz_type_by_offline = $quiz_type_by_offline;

            $row->result = route('module.quiz.result', ['id' => $row->id, 'course_id' => $course_id, 'course_type' => 2]);
            $row->register_url = route('module.quiz.register', ['id' => $row->id, 'course_id' => $course_id, 'course_type' => 2]);
            $row->info_url = route('module.quiz.modal_info', ['id' => $row->id]);
        }


        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function form($course_id, $id = null, Request $request) {
        $controller = new \Modules\Quiz\Http\Controllers\Backend\BackendController();
        $controller->course_id = $course_id;
        $controller->course_type = 2;
        $controller->quiz_type_by_offline = $request->quiz_type_by_offline;
        return $controller->form($id);

        // $course = OfflineCourse::find($course_id);
        // $user = Profile::find(profile()->user_id);

        // $model = Quiz::firstOrNew(['id' => $id]);
        // $quiz_type = QuizType::get();
        // $page_title = $model->name ? $model->name : trans('labutton.add_new') ;
        // $teachers = QuizTeacher::getTeacherByQuiz($id);
        // $unit = Unit::firstOrNew(['id' => $model->unit_id]);
        // $setting = QuizSetting::where('quiz_id', '=', $id)->first();
        // $result = QuizResult::where('quiz_id', '=', $id)->first();
        // $qrcode_quiz = json_encode(['quiz'=>$id,'course_type'=>1,'survey'=>$model->template_id,'type'=>'survey_after_course']);

        // $quiz_template = QuizTemplates::where('status', '=', 1)->where('is_open', '=', 1)->where('quiz_type', 2)->get();
        // $userpoint = UserPointSettings::where('item_id', $id)->where("item_type", 4)->get();

        // $quiz_type_by_offline = $request->quiz_type_by_offline;

        // return view('offline::backend.quiz.form', [
        //     'model' => $model,
        //     'page_title' => $page_title,
        //     'teachers' => $teachers,
        //     'unit' => $unit,
        //     'unit_user' => $user->unit,
        //     'setting' => $setting,
        //     'result' => $result,
        //     'quiz_type' => $quiz_type,
        //     'qrcode_quiz' => $qrcode_quiz,
        //     'quiz_template' => $quiz_template,
        //     'course_id' => $course_id,
        //     'course' => $course,
        //     'userpoint' => $userpoint,
        //     'quiz_type_by_offline' => $quiz_type_by_offline,
        // ]);
    }

    public function remove($course_id, Request $request) {
        $ids = $request->input('ids', null);
        foreach ($ids as $id){
            $quiz = Quiz::find($id);
            if ($quiz->status == 1){
                json_message('Kỳ thi được duyệt. Không thể xoá', 'error');
            }

            if($quiz->course_id > 0){
                json_message('Kỳ thi được thêm trong khoá học. Không thể xoá', 'error');
            }

            $result = QuizResult::where('quiz_id', '=', $id)->whereNull('text_quiz');
            if ($result->exists()){
                json_message('Kỳ thi đã có người thi. Không thể xoá', 'error');
            }

            QuizStatistic::update_statistic_delete($quiz->created_at);
            $quiz->delete();
        }

        json_result([
            'status' => 'success',
            'message' => trans('laother.delete_success'),
        ]);
    }

    public function getDataPart($course_id, $quiz_id, Request $request) {
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = QuizPart::query();
        $query->where('quiz_id', '=', $quiz_id);

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row) {
            $row->startdate = get_date($row->start_date, 'd/m/Y');
            $row->enddate = get_date($row->end_date, 'd/m/Y');
            $row->start_hour = get_date($row->start_date, 'H');
            $row->start_min = get_date($row->start_date, 'i');
            $row->end_hour = get_date($row->end_date, 'H');
            $row->end_min = get_date($row->end_date, 'i');

            $row->start_date = get_date($row->start_date, 'H:i d/m/Y');
            $row->end_date = get_date($row->end_date, 'H:i d/m/Y');
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function savePart($course_id, $quiz_id, Request $request){
        $this->validateRequest([
            'name_part' => 'required',
            'start_date' => 'required',
            'start_hour' => 'required',
            'start_min' => 'required',
        ], $request);

        $name_part = $request->input('name_part');
        $start_time = $request->input('start_hour') . ':' . $request->input('start_min') . ':00';
        $end_time = $request->input('end_hour') . ':' . $request->input('end_min') . ':00';

        $start_date = date_convert($request->input('start_date'), $start_time);
        $end_date = '';
        if ($request->input('end_date')){
            $end_date = date_convert($request->input('end_date'), $end_time);
        }

        $check1 = QuizPart::query();
        $check1->where('id', '!=', $request->id_part);
        $check1->where('start_date', '<=', $start_date);
        $check1->where('end_date', '>=', $start_date);
        $check1->where('quiz_id', '=', $quiz_id);
        if ($check1->exists()) {
            json_result([
                'status' => 'error',
                'message' => 'Thời gian bắt đầu ca thi không họp lệ',
            ]);
        }

        if ($request->input('end_date')){
            $check2 = QuizPart::query();
            $check2->where('id', '!=', $request->id_part);
            $check2->where('start_date', '<=', $end_date);
            $check2->where('end_date', '>=', $end_date);
            $check2->where('quiz_id', '=', $quiz_id);
            if ($check2->exists()) {
                json_result([
                    'status' => 'error',
                    'message' => 'Thời gian kết thúc ca thi không họp lệ',
                ]);
            }
        }

        $model = QuizPart::firstOrNew(['id' => $request->id_part]);
        $model->quiz_id = $quiz_id;
        $model->name = $name_part;
        $model->start_date = $start_date;
        $model->end_date = $request->input('end_date') ? $end_date : null;

        if ($request->input('end_date')){
            if($model->start_date >= $model->end_date){
                json_result([
                    'status' => 'error',
                    'message' => 'Thời gian bắt đầu phải trước thời gian kết thúc',
                ]);
            }
        }

        if($model->save()){
            json_message('ok');
        }

    }

    public function questionQuiz($course_id, $quiz_id) {
        $course = OfflineCourse::find($course_id);

        $profile = profile();
        $unit = Unit::where('code', '=', $profile->unit_code)->first();

        $quiz = Quiz::find($quiz_id);
        $quiz_questions = QuizQuestion::getQuestions($quiz_id);
        $categories = function($cat_id){
            return QuestionCategory::find($cat_id);
        };
        $questions = function($ques_id){
            return Question::find($ques_id);
        };
        $qqc = function ($quiz_id, $num_order) {
            return QuizQuestionCategory::where('quiz_id', '=', $quiz_id)
                ->where('num_order', '=', $num_order)
                ->orderBy('num_order', 'ASC')
                ->get();
        };

        $result = QuizResult::where('quiz_id', '=', $quiz_id)->whereNull('text_quiz')->first();
        if ($result){
            $disabled = 'disabled';
        }else{
            $disabled = '';
        }

        return view('offline::backend.quiz.question', [
            'quiz_questions' => $quiz_questions,
            'categories' => $categories,
            'questions' => $questions,
            'quiz' => $quiz,
            'qqc' => $qqc,
            'unit' => $unit,
            'disabled' => $disabled,
            'course_id' => $course_id,
            'course' => $course
        ]);
    }

    public function report($course_id, $quiz_id, Request $request) {
        $course = OfflineCourse::find($course_id);
        $page_title = $course->name;
        $quiz_name = Quiz::findOrFail($quiz_id)->name;

        return view('offline::backend.quiz.report', [
            'course_name' =>$course->name,
            'course_id' => $course_id,
            'quiz_id' => $quiz_id,
            'quiz_name' => $quiz_name
        ]);
    }

    public function getReport($course_id, $quiz_id, Request $request)
    {

        $sort = $request->input('sort', 'b.full_name');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);
        $search = $request->input('search');

        $query = QuizAttempts::select('el_quiz_attempts.id','sumgrades','el_quiz_attempts.user_id','attempt','part_id',
            \DB::raw( unix_todatetime_sql('timestart').' as time_start'),
            \DB::raw( unix_todatetime_sql('timefinish') .' as time_finish'))
            ->with([
                'user'=>function ($q) use($search){
                    $q->select('id','code','firstname','lastname');
                },
                'part'=>function ($e){
                    $e->selectRaw('id , name as quiz_part');
                }
                ])
            ->whereHas('user', function($q) use($search){
                $q->when($search,function($q) use($search){
                    $q->where(function ($q2) use($search){
                        $q2->where('code', 'like', '%' . $search . '%');
                        $q2->orWhereRaw("concat(lastname,' ',firstname) like '%". $search . "%'");
                    });
                });
            })
            ->where('quiz_id',$quiz_id);

        $count = $query->count();
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->orderBy('el_quiz_attempts.user_id',$order)->orderBy('el_quiz_attempts.attempt',$order)->get();

        foreach($rows as $row){
            $row->time_start = get_date($row->time_start, 'H:i:s d/m/Y');
            $row->time_finish = get_date($row->time_finish, 'H:i:s d/m/Y');
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function removeAttempt($course_id, $quiz_id, Request $request)
    {
        $attempt_ids = $request->input('ids');
        $users = QuizAttempts::whereIn('id',$attempt_ids)->select('user_id')->distinct()->get();
        QuizAttempts::destroy($attempt_ids);
        foreach ($users as $index => $user) {
            $attempts = QuizAttempts::where('quiz_id',$quiz_id)->where('user_id',$user->user_id)->select('id','attempt')->orderBy('attempt')->get();
            foreach ($attempts as $i => $attempt) {
                QuizAttempts::where('id',$attempt->id)->update(['attempt'=>++$i]);
            }
            $this->updateGradeAttempt($quiz_id,$user->user_id);
        }
        json_result([
            'status' => 'success',
            'message' => trans('laother.delete_success'),
        ]);
    }

    private function updateGradeAttempt($quiz_id, $user_id){
        $quiz = Quiz::find($quiz_id);
        $grade = 0;
        if ($quiz->grade_methor == 1) { // lần cao nhất
            $sumgrade = QuizAttempts::where('quiz_id', '=', $quiz_id)
                ->where('user_id', '=', $user_id)
                ->select(\DB::raw('MAX(sumgrades) AS total_grade'))
                ->first();
            if ($sumgrade) {
                $grade = $sumgrade->total_grade;
            }
        }
        elseif ($quiz->grade_methor == 2) {// Điểm trung bình
            $sumgrade = QuizAttempts::where('quiz_id', '=', $quiz_id)
                ->where('user_id', '=', $user_id)
                ->select(\DB::raw('AVG(sumgrades) AS total_grade'))
                ->first();
            if ($sumgrade) {
                $grade = $sumgrade->total_grade;
            }
        }
        elseif ($quiz->grade_methor == 3) {// Điểm lần đầu
            $sumgrade = QuizAttempts::where('quiz_id', '=', $quiz_id)
                ->where('user_id', '=', $user_id)
                ->where('attempt', '=', 1)
                ->first();
            if ($sumgrade) {
                $grade = $sumgrade->sumgrades;
            }
        }
        elseif ($quiz->grade_methor == 4) {// Điểm lần cuối
            $sumgrade = QuizAttempts::where('quiz_id', '=', $quiz_id)
                ->where('user_id', '=', $user_id)
                ->whereColumn('attempt', '=', function ($subquery) use ($quiz_id, $user_id) {
                    $subquery->select(\DB::raw('MAX(attempt)'))
                        ->where('quiz_id', '=', $quiz_id)
                        ->where('user_id', '=', $user_id)
                        ->first();
                })->first();
            if ($sumgrade) {
                $grade = $sumgrade->sumgrades;
            }
        }
        $result = QuizResult::firstOrNew(['quiz_id'=> $quiz_id,'user_id'=>$user_id]);
        $result->quiz_id = $quiz_id;
        $result->user_id = $user_id;
        $result->grade = $grade;
        $result->result = ($grade >= $quiz->pass_score) ? 1 : 0;
//        $result->timecompleted = time();
        $result->save();
    }
}
