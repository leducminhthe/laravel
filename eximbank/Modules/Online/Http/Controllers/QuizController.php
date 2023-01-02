<?php

namespace Modules\Online\Http\Controllers;

use App\Models\Categories\Unit;
use App\Models\Profile;
use App\Scopes\DraftScope;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Online\Entities\OnlineCourse;
use Modules\Online\Entities\OnlineRegister;
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
        $course = OnlineCourse::find($course_id);
        $page_title = $course->name;

        return view('online::backend.quiz.index', [
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
        $query->where('course_type', '=', 1);

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

            $row->edit_url = route('module.online.quiz.edit', ['course_id' => $course_id, 'id' => $row->id, 'quiz_type_by_offline' => $row->quiz_type_by_offline]);
            $row->start_date = get_date($start_date, 'H:i d/m/Y');
            $row->end_date = get_date($end_date, 'H:i d/m/Y');
            $row->created_at2 = get_date($row->created_at, 'd/m/Y h:i');

            $row->quiz_type = 'Online';
            $user_id = $row->updated_by ? $row->updated_by : $row->created_by;

            $row->report_url = route('module.online.quiz.report', ['course_id' => $course_id, 'id' => $row->id]);
            $row->user_url = route('module.quiz.get_user_create_quiz',['user_id' => $user_id]);
            $row->quantity = QuizRegister::where('quiz_id', '=', $row->id)->count();
            $row->quantity_quiz_attempts = QuizResult::where('quiz_id', '=', $row->id)->whereNull('text_quiz')->where('timecompleted', '>', 0)->count();

            $row->user_approved_url = $row->approved_by ? route('module.quiz_template.get_user_create_quiz_template',['user_id' => $row->approved_by]) : '';
            $row->time_approved = $row->time_approved ? get_date($row->time_approved, 'd/m/Y h:i') : '';

            $row->result = route('module.quiz.result', ['id' => $row->id, 'course_id' => $course_id, 'course_type' => 1]);
            $row->register_url = route('module.quiz.register', ['id' => $row->id, 'course_id' => $course_id, 'course_type' => 1]);
            $row->info_url = route('module.quiz.modal_info', ['id' => $row->id]);

            $quiz_type_by_offline = '';
            switch($row->quiz_type_by_offline){
                case 'register_quiz_id': $quiz_type_by_offline = 'Thi trước ghi danh'; break;
            }
            $row->quiz_type_by_offline = $quiz_type_by_offline;
        }


        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function form($course_id, $id = null, Request $request) {
        $controller = new \Modules\Quiz\Http\Controllers\Backend\BackendController();
        $controller->course_id = $course_id;
        $controller->course_type = 1;
        $controller->quiz_type_by_offline = $request->quiz_type_by_offline;
        return $controller->form($id);

        // $course = OnlineCourse::find($course_id);
        // $user = Profile::find(profile()->user_id);

        // $model = Quiz::firstOrNew(['id' => $id]);
        // $quiz_type = QuizType::get();
        // $page_title = $model->name ? $model->name : trans('labutton.add_new') ;
        // $teachers = QuizTeacher::getTeacherByQuiz($id);
        // $unit = Unit::firstOrNew(['id' => $model->unit_id]);
        // $setting = QuizSetting::where('quiz_id', '=', $id)->first();
        // $result = QuizResult::where('quiz_id', '=', $id)->first();
        // $qrcode_quiz = json_encode(['quiz'=>$id,'course_type'=>1,'survey'=>$model->template_id,'type'=>'survey_after_course']);

        // $quiz_template = QuizTemplates::where('status', '=', 1)->where('is_open', '=', 1)->where('quiz_type', 1)->get();
        // $userpoint = UserPointSettings::where('item_id', $id)->where("item_type", 4)->get();

        // return view('online::backend.quiz.form', [
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
        // ]);
    }

    public function save($course_id, Request $request) {
        $this->validateRequest([
            'code' => 'required|unique:el_quiz,code,'. $request->id,
            'name' => 'required',
            'limit_time' => 'required|min:1',
            'pass_score' => 'required|min:0|max:100',
            'max_score' => 'required|min:0|max:100',
            'max_attempts' => 'required',
            'questions_perpage' => 'required',
            'grade_methor' => 'required',
            'img' => 'nullable|string',
        ], $request, Quiz::getAttributeName());

        $checkCourse = OnlineCourse::find($course_id,['offline']);

        $questions_perpage = $request->input('questions_perpage');
        $limit_time = $request->input('limit_time');
        $pass_score = $request->input('pass_score');
        $max_score = $request->input('max_score');
        $shuffle = $request->post('shuffle_answers');

        $quiz_template_id = $request->quiz_template_id;

        $model = Quiz::firstOrNew(['id' => $request->post('id')]);
        $model->fill($request->all());

        if (isset($request->id) && isset($quiz_template_id) && $quiz_template_id != $model->quiz_template_id){
            QuizRank::query()->where('quiz_id', '=', $model->id)->delete();
            QuizSetting::query()->where('quiz_id', '=', $model->id)->delete();
            QuizQuestion::query()->where('quiz_id', '=', $model->id)->delete();
            QuizQuestionCategory::query()->where('quiz_id', '=', $model->id)->delete();
        }

        $model->shuffle_answers = if_empty($shuffle, 0);

        if ($request->img) {
            $sizes = config('image.sizes.medium');
            $model->img = upload_image($sizes, $request->img);
        }

        if($limit_time < 1){
            json_message('Thời gian làm bài phải lớn hơn 1 phút', 'error');
        }

        if($pass_score < 0 || $pass_score > 100){
            json_message('Điểm chuẩn trong khoảng 0 đến 100', 'error');
        }

        if($max_score < 0 || $max_score > 100){
            json_message('Điểm tối đa trong khoảng 0 đến 100', 'error');
        }

        if ($pass_score > $max_score){
            json_message('Điểm chuẩn không được lớn hơn điểm tối đa', 'error');
        }

        if($questions_perpage < 0){
            json_message('Số câu hỏi ít nhất là 0', 'error');
        }

        $model->unit_id = $request->input('unit_id');
        $model->status = 1;
        $model->is_open = 1;
        $model->view_result = 1;
        $model->course_type = 1;
        $model->course_id = $course_id;
        $model->approved_by = profile()->user_id;
        $model->time_approved = date('Y-m-d h:i:s');
        $model->quiz_type = 1;

        if ($model->save()) {
            $course = OnlineCourse::find($course_id);
            $quiz_part = QuizPart::whereQuizId($model->id)->first();
            if (!$quiz_part){
                $quiz_part = new QuizPart();
                $quiz_part->quiz_id = $model->id;
                $quiz_part->name = 'ca 1';
                $quiz_part->start_date = $course->start_date;
                $quiz_part->end_date = $course->end_date;
                $quiz_part->save();
            }

            $course_register = OnlineRegister::whereCourseId($course_id)->where('status', '=', 1)->get();
            if ($course_register->count() > 0){
                foreach ($course_register as $register){
                    QuizRegister::query()
                        ->updateOrCreate([
                            'quiz_id' => $model->id,
                            'user_id' => $register->user_id,
                            'type' => $register->user_type,
                            'part_id' => $quiz_part->id,
                        ],[
                            'quiz_id' => $model->id,
                            'user_id' => $register->user_id,
                            'type' => $register->user_type,
                            'part_id' => $quiz_part->id,
                        ]);
                }

            }

            if ($quiz_template_id){
                $rank = QuizRank::query()->where('quiz_id', '=', $model->id);
                if (!$rank->exists()){
                    $quiz_template_rank = QuizTemplatesRank::selectRaw($model->id . ', rank, score_min, score_max, '. current_datetime_sql() .', '. current_datetime_sql())->where('quiz_id', '=', $quiz_template_id);
                    DB::table('el_quiz_rank')->insertUsing(['quiz_id', 'rank', 'score_min', 'score_max', 'created_at', 'updated_at'], $quiz_template_rank);
                }

                $setting = QuizSetting::query()->where('quiz_id', '=', $model->id);
                if (!$setting->exists()){
                    $quiz_template_setting = QuizTemplatesSetting::selectRaw($model->id . ', after_test_review_test, after_test_yes_no, after_test_score, after_test_specific_feedback, after_test_general_feedback, after_test_correct_answer, exam_closed_review_test, exam_closed_yes_no, exam_closed_score, exam_closed_specific_feedback, exam_closed_general_feedback, exam_closed_correct_answer, '. current_datetime_sql() .', '. current_datetime_sql())->where('quiz_id', '=', $quiz_template_id);
                    DB::table('el_quiz_setting')->insertUsing(['quiz_id', 'after_test_review_test', 'after_test_yes_no', 'after_test_score', 'after_test_specific_feedback', 'after_test_general_feedback', 'after_test_correct_answer', 'exam_closed_review_test', 'exam_closed_yes_no', 'exam_closed_score', 'exam_closed_specific_feedback', 'exam_closed_general_feedback', 'exam_closed_correct_answer', 'created_at', 'updated_at'], $quiz_template_setting);
                }

                $quiz_question = QuizQuestion::query()->where('quiz_id', '=', $model->id);
                if (!$quiz_question->exists()){
                    $quiz_template_question_category = QuizTemplatesQuestionCategory::where('quiz_id', '=', $quiz_template_id)->get();
                    if (count($quiz_template_question_category) > 0){
                        foreach ($quiz_template_question_category as $item){
                            $quiz_question_category = new QuizQuestionCategory();
                            $quiz_question_category->quiz_id = $model->id;
                            $quiz_question_category->name = $item->name;
                            $quiz_question_category->num_order = $item->num_order;
                            $quiz_question_category->percent_group = $item->percent_group;
                            $quiz_question_category->save();

                            $quiz_template_question = QuizTemplatesQuestion::selectRaw($model->id . ', question_id, qcategory_id, random, num_order, '. $quiz_question_category->id .', max_score, '. current_datetime_sql() .', '. current_datetime_sql())->where('qqcategory', '=', $item->id)->where('quiz_id', '=', $quiz_template_id);
                            DB::table('el_quiz_question')->insertUsing(['quiz_id', 'question_id', 'qcategory_id', 'random', 'num_order', 'qqcategory', 'max_score', 'created_at', 'updated_at'], $quiz_template_question);
                        }
                    }else{
                        $quiz_template_question = QuizTemplatesQuestion::selectRaw($model->id . ', question_id, qcategory_id, random, num_order, qqcategory, max_score, '. current_datetime_sql() .', '. current_datetime_sql())->where('quiz_id', '=', $quiz_template_id);
                        DB::table('el_quiz_question')->insertUsing(['quiz_id', 'question_id', 'qcategory_id', 'random', 'num_order', 'qqcategory', 'max_score', 'created_at', 'updated_at'], $quiz_template_question);
                    }
                }
            }

            /********update thống kê quiz **********/
            QuizStatistic::update_statistic($model->id);
            /*********************end***********************/
            $redirect = route('module.online.quiz.edit', ['course_id' => $course_id, 'id' => $model->id]);

            return response()->json([
                'status' => 'success',
                'message' => trans('laother.successful_save'),
                'redirect' => $redirect
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => trans('laother.save_error'),
        ]);
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

    public function savePart($course_id, $id, Request $request){
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
        $check1->where('start_date', '<=', $start_date);
        $check1->where('end_date', '>=', $start_date);
        $check1->where('quiz_id', '=', $id);
        if ($check1->exists()) {
            json_result([
                'status' => 'error',
                'message' => 'Thời gian ca thi không họp lệ',
            ]);
        }

        if ($request->input('end_date')){
            $check2 = QuizPart::query();
            $check2->where('start_date', '<=', $end_date);
            $check2->where('end_date', '>=', $end_date);
            $check2->where('quiz_id', '=', $id);
            if ($check2->exists()) {
                json_result([
                    'status' => 'error',
                    'message' => 'Thời gian ca thi không họp lệ',
                ]);
            }
        }

        $model = QuizPart::firstOrNew(['quiz_id' => $id]);
        $model->quiz_id = $id;
        $model->name = $name_part;
        $model->start_date = $start_date;
        $model->end_date = $request->input('end_date') ? $end_date : null;

        if ($request->input('end_date')){
            if($model->start_date >= $model->end_date){
                json_result([
                    'status' => 'error',
                    'message' => 'Thời gian ca thi không họp lệ',
                ]);
            }
        }

        if($model->save()){
            json_message('ok');
        }

    }

    public function questionQuiz($course_id, $quiz_id) {
        $course = OnlineCourse::find($course_id);

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

        $result = QuizResult::where('quiz_id', '=', $quiz_id)->whereNull('text_quiz')
            ->first();
        if ($result){
            $disabled = 'disabled';
        }else{
            $disabled = '';
        }

        return view('online::backend.quiz.question', [
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
        $course = OnlineCourse::find($course_id);
        $page_title = $course->name;
        $quiz_name = Quiz::findOrFail($quiz_id)->name;
        return view('online::backend.quiz.report', [
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
