<?php

namespace Modules\Quiz\Http\Controllers\Backend;

use App\Models\Profile;
use App\Models\Categories\Unit;
use App\Models\Categories\Area;
use App\Models\Categories\Titles;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\Quiz\Entities\QuizCameraImage;
use Modules\Quiz\Entities\QuizQuestionCategory;
use Modules\Quiz\Entities\Question;
use Modules\Quiz\Entities\QuestionAnswer;
use Modules\Quiz\Entities\QuizAttempts;
use Modules\Quiz\Entities\QuizAttemptsTemplate;
use Modules\Quiz\Entities\QuizPermission;
use Modules\Quiz\Entities\QuizRank;
use Modules\Quiz\Entities\QuizRegister;
use Modules\Quiz\Entities\QuizTemplate;
use Modules\Quiz\Entities\QuizTemplateQuestion;
use Modules\Quiz\Entities\Quiz;
use Modules\Quiz\Entities\QuizResult;
use Modules\Quiz\Entities\QuizPart;
use App\Models\Warehouse;
use Modules\Offline\Entities\OfflineCourse;
use Modules\Online\Entities\OnlineCourse;
use Modules\Quiz\Entities\QuizUserSecondary;
use Modules\Quiz\Exports\ResultExport;
use Modules\Quiz\Imports\ResultImport;

class ResultController extends Controller
{
    public function index($quiz_id) {
        $max_unit = Unit::getMaxUnitLevel();
        $level_name = function ($level) {
            return Unit::getLevelName($level);
        };
        $errors = session()->get('errors');
        \Session::forget('errors');
        $profile = profile();
        $unit = Unit::where('code', '=', $profile->unit_code)->first();

        $quiz_name = Quiz::find($quiz_id);
        $quiz_part = QuizPart::where('quiz_id', '=', $quiz_id)->get();
        $quiz_rank = QuizRank::where('quiz_id', '=', $quiz_id)->first();
        $export_result = QuizPermission::exportResult($quiz_name);
        $save_grade = QuizPermission::saveGradeResult($quiz_name);
        $save_reexamine = QuizPermission::saveReexamineResult($quiz_name);

        $course = '';
        if($quiz_name->course_type == 1){
            $course = OnlineCourse::find($quiz_name->course_id, ['name']);
        }elseif($quiz_name->course_type == 2){
            $course = OfflineCourse::find($quiz_name->course_id, ['name']);
        }
        $course_id = $quiz_name->course_id;

        return view('quiz::backend.result.index', [
            'quiz_name' => $quiz_name,
            'quiz_id' => $quiz_id,
            'quiz_part' => $quiz_part,
            'export_result' => $export_result,
            'save_grade' => $save_grade,
            'save_reexamine' => $save_reexamine,
            'unit' => $unit,
            'max_unit'=>$max_unit,
            'level_name'=>$level_name,
            'quiz_rank' => $quiz_rank,
            'course_id' => $course_id,
            'course' => $course,
        ]);
    }

    public function getData($quiz_id, Request $request) {
        $search = $request->input('search');
        $title = $request->input('title');
        $unit = $request->input('unit_id');
        $status = $request->input('status');
        $result_quiz = $request->input('result_quiz');

        $part = $request->input('part');
        $type = $request->input('type');
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = QuizRegister::query();
        $query->select([
            'a.*',
            'b.lastname',
            'b.firstname',
            'b.code AS profile_code',
            'b.email AS profile_email',
            'b.dob AS profile_dob',
            'b.identity_card AS profile_identity_card',
            'c.name AS title_name',
            'd.name AS unit_name',
            'h.name AS parent_name',
            'e.name as part_name',
            'f.id AS secondary_id',
            'f.full_name AS secondary_name',
            'f.code AS user_secon_code',
            'f.dob AS user_secon_dob',
            'f.email AS user_secon_email',
            'f.identity_card AS user_secon_identity_card',
            'g.id AS result_id',
            'g.grade',
            'g.reexamine',
            'g.attach_file',
        ])->disableCache();
        $query->from('el_quiz_register AS a');
        $query->leftJoin('el_profile_view AS b', function ($join) {
            $join->on('b.user_id', '=', 'a.user_id')
                ->where('a.type', '=', 1);
        });
        $query->leftJoin('el_titles AS c', 'c.code', '=', 'b.title_code');
        $query->leftJoin('el_unit AS d', 'd.code', '=', 'b.unit_code');
        $query->leftJoin('el_area AS area', 'area.id', '=', 'd.area_id');
        $query->leftJoin('el_unit AS h', 'h.code', '=', 'd.parent_code');
        $query->leftJoin('el_quiz_part AS e', 'e.id', '=', 'a.part_id');
        $query->leftJoin('el_profile_view AS f', function ($join){
            $join->on('f.user_id', '=', 'a.user_id')
                ->where('a.type', '=', 2);
        });
        $query->leftJoin('el_quiz_result AS g', function ($join){
            $join->on('g.quiz_id', '=', 'a.quiz_id')
                ->on('g.part_id', '=', 'a.part_id')
                ->on('g.user_id', '=', 'a.user_id')
                ->on('g.type', '=', 'a.type');
        });
        $query->where('a.user_id', '>', 2);
        $query->where('a.quiz_id', '=', $quiz_id);

        if ($search) {
            $query->where(function ($sub_query) use ($search) {
                $sub_query->orWhere('b.full_name', 'like', '%'. $search .'%');
                $sub_query->orWhere('b.code', 'like', '%'. $search .'%');
                $sub_query->orWhere('b.email', 'like', '%'. $search .'%');
                $sub_query->orWhere('f.full_name', 'like', '%'. $search .'%');
                $sub_query->orWhere('f.code', 'like', '%'. $search .'%');
                $sub_query->orWhere('f.email', 'like', '%'. $search .'%');
            });
        }

        if (!is_null($status)) {
            $query->where('b.status', '=', $status);
        }

        if ($title) {
            $query->where('c.id', '=', $title);
        }
        if ($request->area) {
            $area = Area::find($request->area);
            $area_id = Area::getArrayChild($area->code);
            $query->where(function ($sub_query) use ($area, $area_id) {
                $sub_query->orWhereIn('area.id', $area_id);
                $sub_query->orWhere('area.id', '=', $area->id);
            });
        }
        if ($unit) {
            $query->where('d.id', '=',  $unit);
        }

        if ($part) {
            $query->where('e.id', '=',  $part);
        }

        if ($type) {
            $query->where('a.type', '=',  $type);
        }

        if ($result_quiz){
            $quizAttempt = QuizAttempts::whereQuizId($quiz_id)->where('state', '=', 'completed')->pluck('user_id')->toArray();
            if ($result_quiz == 1){
                $query->whereIn('a.user_id', $quizAttempt);
            }

            if ($result_quiz == 2){
                $query->whereNotIn('a.user_id', $quizAttempt);
            }

            if ($result_quiz == 3){
                $query->where('g.result', '=', 1);
            }

            if ($result_quiz == 4){
                $query->where('g.result', '=', 0);
            }

        }

        $count = $query->count();
        $query->orderBy('a.'.$sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $quiz = Quiz::find($quiz_id);
        //$user_type = Quiz::getUserType();
        $result = QuizResult::where('quiz_id', '=', $quiz->id)->whereNull('text_quiz')->first();
        $rows = $query->get();

        foreach ($rows as $row) {
            $current_file = '';
            if ($row->attach_file) {
                $warequery = Warehouse::where('file_path', '=', $row->attach_file);
                if ($warequery->exists()) {
                    $current_file = $warequery->first()->file_name;
                }
            }

            if ($row->reexamine) {
                $row->reexamine ? ($row->reexamine >= $quiz->pass_score ? $res = 1 : $res = 0) : $res = -1;
            }else{
                is_numeric($row->grade) ? (($row->grade) >= $quiz->pass_score ? $res = 1 : $res = 0) : $res = -1;
            }

            $row->profile_dob = get_date($row->profile_dob, 'd/m/Y');
            $row->user_secon_dob = get_date($row->user_secon_dob, 'd/m/Y');
            $row->paper_exam = $quiz->paper_exam;
            $row->grade = number_format($row->grade,2);
            $row->reexamine = $row->reexamine ? number_format($row->reexamine,2) : '';
            $row->file = $row->attach_file ? $row->attach_file : '';
            $row->file_name = $current_file;
            $row->link_download = ($row->attach_file) ? \link_download('uploads/'.$row->attach_file) : '';
            $row->regid = $row->id;
            $row->res = $res;
            if (empty($row->parent_name)){
                $row->parent = $row->unit_name;
                $row->unit = '';
            }else{
                $row->parent = $row->parent_name;
                $row->unit = $row->unit_name;
            }

            $row->status = $this->getStatusUser($row->user_id, $quiz_id, $row->part_id);

            $attempt = QuizAttempts::whereQuizId($quiz_id)
                ->where('part_id', $row->part_id)
                ->where('user_id', '=', $row->user_id)
                ->where('type', '=', $row->type)
                ->latest()
                ->first();

            $row->review_link = route('module.quiz.result.user.view', [
                'id' => $quiz_id,
                'part_id' => $row->part_id,
                'type' => $row->type,
                'user_id' => $row->user_id,
                'attempt_id' => $attempt->id,
            ]);

            $row->url_image = route('module.quiz.result.user.image', [
                'id' => $quiz_id,
                'type' => $row->type,
                'user_id' => $row->user_id
            ]);

            $row->user_locked = $row->locked == 1 ? trans('laquiz.cheating_exam') : '';
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function getStatusUser($user_id, $quiz_id, $part_id) {
        $result = QuizResult::where('quiz_id', '=', $quiz_id)
            ->where('part_id', '=', $part_id)
            ->where('user_id', '=', $user_id)
            ->whereNull('text_quiz')
            ->where('timecompleted', '>', 0);
        if ($result->exists()) {
            return 1;
        }

        return 0;
    }

    public function updateUserLocked(Request $request) {
        $this->validateRequest([
            'ids' => 'required',
            'status' => 'required|in:0,1'
        ], $request, [
            'ids' => trans('lamenu.quiz'),
        ]);

        $ids = $request->input('ids', null);
        $status = $request->input('status', 0);

        QuizRegister::whereIn('id', $ids)->update([
            'locked' => $status,
        ]);

        json_result([
            'status' => 'success',
            'message' => trans('laother.successful_save')
        ]);
    }

    public function saveFile($quiz_id, Request $request) {
        $this->validateRequest([
            'result_id' => '',
            'regid' => 'required',
            'path' => 'required',
        ], $request);

        $result_id = $request->input('result_id');
        $regid = $request->input('regid');
        $file = $request->input('path');

        $result = QuizResult::find($result_id);
        $register = QuizRegister::find($regid);

        if($result){
            $result->attach_file = path_upload($file);
            $result->save();
        }else {
            $result = new QuizResult();
            $result->quiz_id = $quiz_id;
            $result->user_id = $register->user_id;
            $result->type = $register->type;
            $result->attach_file = path_upload($file);
            $result->part_id = $register->part_id;
            $result->save();
        }

        json_message('ok');
    }

    public function saveGrade($quiz_id, Request $request) {
        $this->validateRequest([
            'result_id' => '',
            'regid' => 'required',
            'grade' => 'required|min:0',
        ], $request,[
            'grade' => 'Điểm',
        ]);

        $result_id = $request->input('result_id');
        $regid = $request->input('regid');
        $grade = $request->input('grade');

        $result = QuizResult::find($result_id);
        $register = QuizRegister::find($regid);
        $quiz = Quiz::find($quiz_id);

        if($grade < 0 || $grade > $quiz->max_score){
            json_result([
                'status' => 'error',
                'message' => 'Điểm vượt quá điểm tối đa kỳ thi'
            ]);
        }

        if($result){
            $result->grade = number_format($grade,1);
            if ($grade >= $quiz->pass_score){
                $result->result = 1;
            }
            $result->save();
        }else {
            $result = new QuizResult();
            $result->quiz_id = $quiz_id;
            $result->user_id = $register->user_id;
            //$result->user_secondary_id = $register->user_secondary_id;
            $result->type = $register->type;
            $result->grade = number_format($grade,1);
            if ($grade >= $quiz->pass_score){
                $result->result = 1;
            }
            //$result->attempt_id = null;
            $result->save();
        }

    }

    public function saveReexamine($quiz_id, Request $request) {
        $this->validateRequest([
            'result_id' => '',
            'regid' => 'required',
            'reexamine' => 'required|min:0',
        ], $request,[
            'reexamine' => 'Điểm phúc khảo',
        ]);

        $result_id = $request->input('result_id');
        $regid = $request->input('regid');
        $reexamine = $request->input('reexamine');

        $result = QuizResult::find($result_id);
        $register = QuizRegister::find($regid);
        $quiz = Quiz::find($quiz_id);

        if($reexamine < 0 || $reexamine > $quiz->max_score){
            json_result([
                'status' => 'error',
                'message' => 'Điểm vượt quá điểm tối đa kỳ thi'
            ]);
        }

        if($result){
            $result->reexamine = $reexamine;
            if ($reexamine >= $quiz->pass_score){
                $result->result = 1;
            }
            $result->save();
        }else {
            $result = new QuizResult();
            $result->quiz_id = $quiz_id;
            $result->user_id = $register->user_id;
            //$result->user_secondary_id = $register->user_secondary_id;
            $result->type = $register->type;
            $result->reexamine = $reexamine;
            if ($reexamine >= $quiz->pass_score){
                $result->result = 1;
            }
            //$result->attempt_id = null;
            $result->save();
        }

    }

    public function importResult($quiz_id, Request $request){
        $this->validateRequest([
            'import_file' => 'required|file'
        ], $request, ['import_file' => 'File import']);

        $unit = $request->input('unit');

        $import = new ResultImport($quiz_id);
        \Excel::import($import, $request->file('import_file'));

        if ($import->errors) {
            session()->put('errors', $import->errors);
            session()->save();
        }

        $redirect = route('module.quiz.result', ['id' => $quiz_id]);

        json_result([
            'status' => 'success',
            'message' => 'Import ' . trans('laapi.success'),
            'redirect' => $redirect,
        ]);
    }

    public function exportResult($quiz_id, Request $request){
        $status = $request->status;
        $type = $request->type;
        $part = $request->part;
        $unit = isset($request->unit) ? $request->unit : '';
        $title = isset($request->title) ? $request->title : '';
        $result_quiz = $request->result_quiz;
        $search = $request->search;

        return (new ResultExport($quiz_id, $status, $type, $part, $unit, $title, $result_quiz, $search))->download('ket_qua_ky_thi_'. date('d_m_Y') .'.xlsx');
    }

    public function view($quiz_id, $part_id, $type, $user_id, Request $request) {
        $attempt_id = $request->attempt_id;

        $quiz = Quiz::findOrFail($quiz_id);
        $result = QuizResult::where('quiz_id', '=', $quiz_id)->where('user_id', $user_id)->whereNull('text_quiz')->where('type', $type)->where('part_id', $part_id)->first();
        $attempt = $quiz->attempts()
            ->where('quiz_id', '=', $quiz_id)
            ->where('part_id', '=', $part_id)
            ->where('user_id', '=', $user_id)
            ->where('type', '=', $type);
        if($attempt_id){
            $attempt = $attempt->where('id', $attempt_id)->firstOrFail();
        }else{
            $attempt = $attempt->latest()->firstOrFail();
        }

        $template = QuizAttempts::getQuizData($attempt->id);
        $questions = $template['questions'];
        usort($questions, function ($a, $b) {
            return $a['qindex'] <=> $b['qindex'];
        });
        $qqcategorys = $template['categories'];

        $qqcategory = [];
        foreach ($qqcategorys as $item) {
            $qqcategory['num_' . $item['num_order']] = $item['name'];
            $qqcategory['percent_' . $item['num_order']] = $item['percent_group'];
        }
        if ($type == 1){
            $profile = Profile::find($user_id);
            $full_name = $profile->lastname . ' ' . $profile->firstname;
        }else{
            $profile = QuizUserSecondary::find($user_id);
            $full_name = $profile->name;
        }

        $list_attempt = QuizAttempts::whereQuizId($quiz_id)
            ->where('part_id', '=', $part_id)
            ->where('user_id', '=', $user_id)
            ->where('type', '=', $type)
            ->get();

        $course = '';
        if($quiz->course_type == 1){
            $course = OnlineCourse::find($quiz->course_id, ['name']);
        }elseif($quiz->course_type == 2){
            $course = OfflineCourse::find($quiz->course_id, ['name']);
        }
        $course_id = $quiz->course_id;

        return view('quiz::backend.result.view', [
            'quiz' => $quiz,
            'attempt' => $attempt,
            'user_id' => $user_id,
            'type' => $type,
            'questions' => $questions,
            'disabled' => 1,
            'qqcategory' => $qqcategory,
            'profile' => $profile,
            'full_name' => $full_name,
            'result' => $result,
            'list_attempt' => $list_attempt,
            'course_id' => $course_id,
            'course' => $course,
        ]);
    }

    public function getQuestion($quiz_id, $type, $user_id, $attempt_id, Request $request) {
        $quiz = Quiz::findOrFail($quiz_id);
        $attempt = $quiz->attempts()
            ->where('quiz_id', '=', $quiz_id)
            ->where('user_id', '=', $user_id)
            ->where('type', '=', $type)
            ->where('id', '=', $attempt_id)
            ->firstOrFail();

        $template = QuizAttempts::getQuizData($attempt->id);

        $total = count( $template['questions'] );
        $total_page = ceil( $total / $quiz->questions_perpage );

        $page = $request->get('page');
        $offset = ($page - 1) * $quiz->questions_perpage;
        if( $offset < 0 ) $offset = 0;

        $rows = array_slice( $template['questions'], $offset, $quiz->questions_perpage );

        $next = false;
        if ($page < $total_page) {
            $next = true;
        }

        $data = ['rows' => $rows, 'next' => $next];
        return \response()->json($data);
    }

    public function imageShooting($quiz_id, $type, $user_id) {
        if ($type == 1){
            $fullname = Profile::fullname($user_id);
        }else{
            $fullname = QuizUserSecondary::find($user_id)->name;
        }

        $quiz_name = Quiz::find($quiz_id);

        $course = '';
        if($quiz_name->course_type == 1){
            $course = OnlineCourse::find($quiz_name->course_id, ['name']);
        }elseif($quiz_name->course_type == 2){
            $course = OfflineCourse::find($quiz_name->course_id, ['name']);
        }
        $course_id = $quiz_name->course_id;

        return view('quiz::backend.result.image', [
            'quiz_name' => $quiz_name,
            'quiz_id' => $quiz_id,
            'fullname' => $fullname,
            'user_id' => $user_id,
            'type' => $type,
            'course_id' => $course_id,
            'course' => $course,
        ]);
    }

    public function getDataImageShooting($quiz_id, $type, $user_id, Request $request) {
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = QuizAttempts::query();
        $query->select([
            'a.*',
        ]);
        $query->from('el_quiz_camera_images AS a');
        $query->leftJoin('el_quiz_attempts as b', function ($join){
            $join->on('b.id', '=', 'a.attempt_id')
                ->on('b.user_id', '=', 'a.user_id')
                ->on('b.type', '=', 'a.user_type');
        });
        $query->where('b.quiz_id', '=', $quiz_id);
        $query->where('a.user_id', '=', $user_id);
        $query->where('a.user_type', '=', $type);

        $count = $query->count();
        $query->orderBy('a.'.$sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row) {
            $row->url_image = route('stream.asset', [$row->path]);
            $row->time = get_date($row->created_at, 'H:i:s d/m/Y');
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }
}
