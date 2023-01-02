<?php

namespace Modules\Quiz\Http\Controllers\Backend;

use App\Models\Automail;
use App\Models\Permission;
use App\Models\Categories\TrainingTeacher;
use App\Models\Profile;
use App\Models\ProfileView;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\ModelHistory\Entities\ModelHistory;
use Modules\Online\Entities\OnlineCourseActivity;
use Modules\Online\Entities\OnlineCourseActivityCompletion;
use Modules\Quiz\Entities\Question;
use Modules\Quiz\Entities\QuestionAnswer;
use Modules\Quiz\Entities\Quiz;
use Modules\Quiz\Entities\QuizAttempts;
use Modules\Quiz\Entities\QuizAttemptsTemplate;
use Modules\Quiz\Entities\QuizGraded;
use Modules\Quiz\Entities\QuizPermissionTeacher;
use Modules\Quiz\Entities\QuizQuestion;
use Modules\Quiz\Entities\QuizQuestionCategory;
use Modules\Quiz\Entities\QuizRegister;
use Modules\Quiz\Entities\QuizResult;
use Modules\Quiz\Entities\QuizTeacherGraded;
use Modules\Quiz\Entities\QuizTemplate;
use Modules\Quiz\Entities\QuizTemplateQuestion;
use Modules\Quiz\Entities\QuizUpdateAttempts;
use Modules\Quiz\Entities\QuizUserSecondary;
use Modules\Quiz\Entities\ReportCorrectAnswerRate;

class GradingController extends Controller
{
    public function index() {
        return view('quiz::backend.grading.index',[
        ]);
    }

    public function getDataQuiz(Request $request) {
        $search = $request->input('search');
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $status_grading = $request->input('status_grading');

        $query = Quiz::active()
            ->where('teacher_grade', 1);

        if (!Auth::user()->isAdmin()) {
            $query->whereHas('teachers', function ($q) {
                $q->select(['id'])
                    ->where('teacher_id', '=', @Auth::user()->teacher->id);
            });
        }

        if ($search){
            $query->where(function ($sub_query) use ($search) {
                $sub_query->orWhere('name', 'like', '%'. $search .'%');
                $sub_query->orWhere('code', 'like', '%'. $search .'%');
            });
        }

        if($status_grading){
            $query->where('status_grading', $status_grading);
        }

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();

        foreach ($rows as $row) {
            $max_end_date = $row->parts()->max('end_date');
            $row->note = ($max_end_date && strtotime($max_end_date) < time() ? '' : 'Kỳ thi chưa kết thúc');

            $row->quantity = QuizRegister::where('quiz_id', '=', $row->id)->count();
            $row->quantity_quiz_attempts = QuizAttempts::where('quiz_id', '=', $row->id)->count();

            $row->edit_url = route('module.quiz.grading.user', [$row->id]);
            $row->info_url = route('module.quiz.grading.modal_teacher', ['quiz_id' => $row->id]);

            $row->status_grading = ($row->status_grading == 1 ? 'Đã chấm' : ($row->status_grading == 3 ? 'Dang dỡ' : 'Chưa chấm'));
        }

        return response()->json([
            'total' => $count,
            'rows' => $rows
        ]);
    }

    public function user($quiz_id) {
        $teacher_id = 0;
        $teacher = TrainingTeacher::where('user_id', '=', profile()->user_id)->first();
        if ($teacher) {
            $teacher_id = $teacher->id;
        }

        $query = Quiz::query();
        $query->where('id', '=', $quiz_id)
            ->where('status', '=', 1);

        if (!Permission::isAdmin()) {
            $query->whereIn('id', function ($subquery) use ($teacher_id) {
                $subquery->select(['quiz_id'])
                    ->from('el_quiz_teacher')
                    ->where('teacher_id', '=', $teacher_id);
            });
        }

        $quiz = $query->firstOrFail();
        return view('quiz::backend.grading.user', [
            'quiz' => $quiz
        ]);
    }

    public function getDataUser($quiz_id, Request $request) {
        $graded = $request->input('graded');
        $search = $request->input('search');
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = QuizRegister::query();
        $query->select([
            'a.id',
            'a.type',
            'a.user_id',
            'a.part_id',
            'b.code AS user_code',
            'b.lastname',
            'b.firstname',
            'b.email',
            'c.name AS secondary_name',
            'c.code AS secondary_code',
        ]);
        $query->from('el_quiz_register AS a');
        $query->leftJoin('el_profile AS b', function ($join) {
            $join->on('b.user_id', '=', 'a.user_id')
                ->where('a.type', '=', 1);
        });
        $query->leftJoin('el_quiz_user_secondary AS c', function ($join) {
            $join->on('c.id', '=', 'a.user_id')
                ->where('a.type', '=', 2);
        });
        $query->where('quiz_id', '=', $quiz_id);

        if ($search){
            $query->where(function ($sub_query) use ($search) {
                $sub_query->orWhere('b.lastname', 'like', '%'. $search .'%');
                $sub_query->orWhere('b.firstname', 'like', '%'. $search .'%');
                $sub_query->orWhere('b.email', 'like', '%'. $search .'%');
                $sub_query->orWhere('b.code', 'like', '%'. $search .'%');
                $sub_query->orWhere('c.name', 'like', '%'. $search .'%');
                $sub_query->orWhere('c.code', 'like', '%'. $search .'%');
            });
        }

        if ($graded == 1) {
            $query->whereExists(function ($sub) {
                $sub->select(\DB::raw(1))
                    ->from('el_quiz_teacher_graded')
                    ->whereColumn('quiz_id', '=', 'a.quiz_id')
                    ->whereColumn('user_id', '=', 'a.user_id')
                    ->whereColumn('user_type', '=', 'a.type');
            });
        }

        if ($graded == 2) {
            $query->whereNotExists(function ($sub) {
                $sub->select(\DB::raw(1))
                    ->from('el_quiz_teacher_graded')
                    ->whereColumn('quiz_id', '=', 'a.quiz_id')
                    ->whereColumn('user_id', '=', 'a.user_id')
                    ->whereColumn('user_type', '=', 'a.type');
            });
        }

        $count = $query->count();
        $query->orderBy('a.'.$sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();

        foreach ($rows as $row) {
            $graded = QuizTeacherGraded::where('quiz_id', '=', $quiz_id)
                ->where('part_id', '=', $row->part_id)
                ->where('user_id', '=', $row->user_id)
                ->where('user_type', '=', $row->type)
                ->where('teacher_id', profile()->user_id)
                ->first();
            $row->graded = ($graded ? 'Đã chấm' : 'Chưa chấm');

            $row->status = $this->getStatusUser($row->user_id, $quiz_id, $row->part_id);

            $quiz_attempts = QuizAttempts::where('quiz_id', '=', $quiz_id)
            ->where('part_id', '=', $row->part_id)
            ->where('user_id', '=', $row->user_id)
            ->where('type', '=', $row->type)
            ->where('state', '=', 'completed')
            ->get();

            $attemp_list = '';
            foreach($quiz_attempts as $quiz_attempt){
                $route = route("module.quiz.grading.user.grading", [$quiz_id, $row->part_id, $row->type, $row->user_id, $quiz_attempt->id]);
                $attemp_list .= '<a href="'. $route .'" class="btn">'. $quiz_attempt->attempt .'</a>';
            }
            $row->attemp_list = $attemp_list;
        }

        return response()->json(['total' => $count, 'rows' => $rows]);
    }

    public function grading($quiz_id, $part_id, $type, $user_id, $attempt_id) {
        $teacher_id = 0;
        $teacher = TrainingTeacher::where('user_id', '=', profile()->user_id)->first();
        if ($teacher) {
            $teacher_id = $teacher->id;
        }

        $query = Quiz::query();
        $query->where('id', '=', $quiz_id)
            ->where('status', '=', 1);

        if (!Permission::isAdmin()) {
            $query->whereIn('id', function ($subquery) use ($teacher_id) {
                $subquery->select(['quiz_id'])
                    ->from('el_quiz_teacher')
                    ->where('teacher_id', '=', $teacher_id);
            });
        }
        $quiz = $query->firstOrFail();

        $attempt = QuizAttempts::where('id', '=', $attempt_id)
            ->where('quiz_id', '=', $quiz_id)
            ->where('part_id', '=', $part_id)
            ->where('user_id', '=', $user_id)
            ->where('type', '=', $type)
            ->latest()
            ->firstOrFail();

        $template = QuizAttempts::getQuizData($attempt->id);
        $questions_template = $template['questions'];
        $qqcategorys = $template['categories'];

        $permission_teacher = QuizPermissionTeacher::query()
            ->where('quiz_id', '=', $quiz_id)
            ->where('teacher_id', '=', $teacher_id)
            ->first();
        $permission_teacher_question = [];
        if ($permission_teacher){
            $permission_teacher_question = explode(',', $permission_teacher->question_id);
        }

        $questions = [];
        foreach ($questions_template as $key => $question){
            if (count($permission_teacher_question) > 0){
                if (in_array($question['type'], ['fill_in', 'essay']) && in_array($question['question_id'], $permission_teacher_question)){
                    $questions[] = $question;
                }
            }else{
                if (in_array($question['type'], ['fill_in', 'essay'])){
                    $questions[] = $question;
                }
            }
        }

        $qqcategory = [];
        foreach ($qqcategorys as $item) {
            $qqcategory['num_' . $item['num_order']] = $item['name'];
            $qqcategory['percent_' . $item['num_order']] = $item['percent_group'];
        }

        return view('quiz::backend.grading.grading', [
            'quiz' => $quiz,
            'part_id' => $part_id,
            'attempt' => $attempt,
            'user_id' => $user_id,
            'type' => $type,
            'questions' => $questions,
            'disabled' => 1,
            'qqcategory' => $qqcategory,
            'permission_teacher_question' => $permission_teacher_question,
        ]);
    }

    public function getStatusUser($user_id, $quiz_id, $part_id) {
        $result = QuizResult::where('quiz_id', '=', $quiz_id)
            ->where('part_id', '=', $part_id)
            ->where('user_id', '=', $user_id)
            ->whereNull('text_quiz')
            ->where('timecompleted', '>=', 0);
        if ($result->exists()) {
            return 1;
        }

        return 0;
    }

    public function getQuestion($quiz_id, $part_id, $type, $user_id, $attempt_id, Request $request) {
        $teacher_id = 0;
        $teacher = TrainingTeacher::where('user_id', '=', profile()->user_id)->first();
        if ($teacher) {
            $teacher_id = $teacher->id;
        }

        $quiz = Quiz::findOrFail($quiz_id);
        $attempt = QuizAttempts::where('id', '=', $attempt_id)
            ->where('quiz_id', '=', $quiz_id)
            ->where('part_id', '=', $part_id)
            ->where('user_id', '=', $user_id)
            ->where('type', '=', $type)
            ->latest()->firstOrFail();

        $template = QuizAttempts::getQuizData($attempt->id);
        $questions_template = $template['questions'];

        $permission_teacher = QuizPermissionTeacher::query()
            ->where('quiz_id', '=', $quiz_id)
            ->where('teacher_id', '=', $teacher_id)
            ->first();
        $permission_teacher_question = [];
        if ($permission_teacher){
            $permission_teacher_question = explode(',', $permission_teacher->question_id);
        }

        $questions = [];
        foreach ($questions_template as $key => $question){
            if (count($permission_teacher_question) > 0){
                if (in_array($question['type'], ['fill_in', 'essay']) && in_array($question['question_id'], $permission_teacher_question)){
                    $questions[] = $question;
                }
            }else{
                if (in_array($question['type'], ['fill_in', 'essay'])){
                    $questions[] = $question;
                }
            }
        }

        $total = count($questions);
        $total_page = ceil( $total / $quiz->questions_perpage );

        $page = $request->get('page');
        $offset = ($page - 1) * $quiz->questions_perpage;
        if( $offset < 0 ) $offset = 0;

        $rows = array_slice($questions, $offset, $quiz->questions_perpage );

        $next = false;
        if ($page < $total_page) {
            $next = true;
        }

        return response()->json([
            'rows' => $rows,
            'next' => $next
        ]);
    }

    public function saveScore($quiz_id, $part_id, $type, $user_id, $attempt_id, Request $request) {
        $this->validateRequest([
            'score' => 'required',
            'question_id' => 'required',
        ], $request, [
            'score' => 'Điểm',
            'question_id' => trans('latraining.question')
        ]);

        $question_id = $request->question_id;
        $score = $request->score;

        $attempt = QuizAttempts::query()
            ->where('id', '=', $attempt_id)
            ->where('quiz_id', '=', $quiz_id)
            ->where('part_id', '=', $part_id)
            ->where('user_id', '=', $user_id)
            ->where('type', '=', $type)
            ->latest()->firstOrFail();

        $template = QuizAttempts::getQuizData($attempt->id);
        $questions = $template['questions'];
        $question = $questions[$question_id];

        if ($score > $question['max_score']){
            return response()->json([
                'status' => 'error',
                'message' => 'Điểm không thể lớn hơn '. $question['max_score'],
            ]);
        }
        $question['score'] = $question['score_group'] * $score;
        $questions[$question_id] = $question;

        $template['questions'] = $questions;
        QuizAttempts::updateQuizData($attempt->id,$template);

        // hist
        $student = ProfileView::find($user_id)->full_name;
        $quiz = Quiz::find($quiz_id)->name;
        $modelHist= new ModelHistory();
        $modelHist->model_id=$attempt->id;
        $modelHist->model ='el_quiz_update_attempt';
        $modelHist->code ='Update';
        $modelHist->action ='Cập nhật chấm điểm học viên '.$student;
        $modelHist->note = $quiz;
        $modelHist->parent_id = $quiz_id;
        $modelHist->parent_model = 'el_quiz';
        $modelHist->save();
        ////
        return response()->json([
            'status' => 'success',
            'message' => trans('laother.update_successful'),
        ]);
    }

    public function saveComment($quiz_id, $part_id, $type, $user_id, $attempt_id, Request $request) {
        $this->validateRequest([
            'score' => 'required',
            'question_id' => 'required',
        ], $request, [
            'score' => 'Đánh giá',
            'question_id' => trans('latraining.question')
        ]);

        $question_id = $request->question_id;
        $score = $request->score;

        $attempt = QuizAttempts::query()
            ->where('id', '=', $attempt_id)
            ->where('quiz_id', '=', $quiz_id)
            ->where('part_id', '=', $part_id)
            ->where('user_id', '=', $user_id)
            ->where('type', '=', $type)
            ->latest()->firstOrFail();

        $template = QuizAttempts::getQuizData($attempt->id);
        $questions = $template['questions'];

        $question = $questions[$question_id];
        $question['grading_comment'] = $score;

        $questions[$question_id] = $question;

        $template['questions'] = $questions;
        QuizAttempts::updateQuizData($attempt->id,$template);

        return response()->json([
            'status' => 'success',
            'message' => trans('laother.update_successful'),
        ]);
    }

    public function gradeComplete($quiz_id, $part_id, $type, $user_id, $attempt_id, Request $request) {
        $quiz = Quiz::find($quiz_id);

        $template = QuizAttempts::getQuizData($attempt_id);
        $questions = $template['questions'];

        $grade = 0;
        foreach ($questions as $index => $question) {
            $grade += $question['score'];
        }

        QuizUpdateAttempts::query()
        ->where('attempt_id', '=', $attempt_id)
        ->where('quiz_id', '=', $quiz_id)
        ->where('part_id', '=', $part_id)
        ->where('user_id', '=', $user_id)
        ->where('type', '=', $type)
        ->update([
            'questions' => json_encode($questions),
        ]);

        QuizAttempts::where('id', $attempt_id)->update([
            'sumgrades' => $grade,
        ]);

        QuizTemplate::updateGradeQuiz($quiz_id, $part_id, $user_id, $type);

        QuizTeacherGraded::updateOrCreate([
            'quiz_id' => $quiz_id,
            'part_id' => $part_id,
            'user_id' => $user_id,
            'user_type' => $type,
            'teacher_id' => profile()->user_id,
        ],[
            'quiz_id' => $quiz_id,
            'part_id' => $part_id,
            'user_id' => $user_id,
            'user_type' => $type,
            'teacher_id' => profile()->user_id,
        ]);

        $permission_teacher = QuizPermissionTeacher::query()
            ->where('quiz_id', '=', $quiz_id)
            ->groupBy(['teacher_id'])
            ->count();
        $teacher_graded = QuizTeacherGraded::query()
            ->where('quiz_id', '=', $quiz_id)
            ->groupBy(['teacher_id'])
            ->count();

        if ($permission_teacher > 0){ //Nhiều GV chấm điểm
            if ($permission_teacher == $teacher_graded){ //GV chấm điểm hết mới gửi mail kết quả cho HV
                $this->updateSendEmailResultQuiz($quiz_id,$user_id,$type);
            }
        }else{ //Chỉ 1 GV chấm điểm. Xong là gửi cho HV
            $this->updateSendEmailResultQuiz($quiz_id,$user_id,$type);
        }

        $count_result = QuizResult::whereQuizId($quiz_id)->whereNull('text_quiz')->count('user_id');
        $count_graded = QuizTeacherGraded::where('quiz_id', $quiz_id)->groupBy(['user_id'])->count('user_id');
        if($count_graded >= $count_result){
            $quiz->update([
                'status_grading' => 1,
            ]);
        }else{
            $quiz->update([
                'status_grading' => 3,
            ]);
        }

        $quiz_result = QuizResult::where('quiz_id', '=', $quiz_id)->whereNull('text_quiz')->where('part_id', '=', $part_id)->where('user_id', '=', $user_id)->first('result');
        /***Hoàn thành hoạt động online */
        if ($quiz->quiz_type == 1) {
            $activity = OnlineCourseActivity::where(['course_id' => $quiz->course_id, 'activity_id'=>2,'subject_id'=>$quiz_id])->first('id');
            if($activity){
                $completionActivity = OnlineCourseActivityCompletion::firstOrNew([
                    'user_id' => $user_id,
                    'activity_id' => $activity->id,
                    'course_id'=>$quiz->course_id,
                ]);
                $completionActivity->user_id = $user_id;
                $completionActivity->activity_id = $activity->id;
                $completionActivity->course_id = $quiz->course_id;
                $completionActivity->status = $quiz_result->result;
                $completionActivity->save();

                if($completionActivity->status == 1){
                    \Artisan::call('online:complete '.$user_id .' '.$quiz->course_id);
                }
            }
        }

        /***Hoàn thành khóa học offline */
        if ($quiz->quiz_type == 2 && $quiz_result->result == 1) {
            \Artisan::call('command:offline_complete '.$user_id .' '.$quiz->course_id);
        }

        \Artisan::call('quiz:complete '.$user_id .' '.$quiz_id);

        return response()->json([
            'status' => 'success',
            'message' => trans('laother.update_successful'),
            'redirect' => route('module.quiz.grading.user', ['quiz_id' => $quiz_id]),
        ]);
    }
    public function updateSendEmailResultQuiz($quiz_id,$user_id,$user_type)
    {
        $quiz = Quiz::with('type')->find($quiz_id);
        $quizPartUsers = QuizRegister::with('quizparts:id,name,start_date,end_date')->where(['user_id'=>$user_id,'quiz_id'=>$quiz_id,'type'=>$user_type])->get()->pluck('quizparts')->flatten();

        $quiz_result = QuizResult::where(['quiz_id'=>$quiz_id,'user_id'=>$user_id,'type'=>$user_type])->whereNull('text_quiz')->first();
        if ($user_type)
            $user = Profile::where('user_id',$user_id)->first();
        else
            $user = QuizUserSecondary::find($user_id);
        foreach ($quizPartUsers as $quizPartUser) {
            $signature = getMailSignature($user_id, $user_type);
            $params = [
                'signature' => $signature,
                'gender' => $user_type==1?( $user->gender=='1'?'Anh':'Chị'):'Anh/Chị',
                'full_name' => $user_type==1?$user->full_name:$user->name,
                'firstname' => $user_type == 1 ? $user->firstname : $user->name,
                'quiz_name' => $quiz->name,
                'quiz_type' => $quiz->type?$quiz->type->name:'',
                'quiz_part_name' => $quizPartUser->name,
                'start_quiz_part' => $quizPartUser->start_date,
                'end_quiz_part' => $quizPartUser->end_date,
                'quiz_time' => $quiz->limit_time,
                'quiz_result' => $quiz_result->grade
            ];
            $user_id = [$user_id];
            $this->saveEmailQuizRegister($params,$user_id,$quiz_result->id);
        }
    }
    public function saveEmailQuizRegister(array $params,array $user_id,int $quiz_result_id)
    {
        $automail = new Automail();
        $automail->template_code = 'quiz_registerd';
        $automail->params = $params;
        $automail->users = $user_id;
        $automail->check_exists = true;
        $automail->check_exists_status = 0;
        $automail->object_id = $quiz_result_id;
        $automail->object_type = 'approve_quiz';
        $automail->addToAutomail();
    }

    public function modalTeacherGraded($quiz_id, Request $request){
        $teacher_graded = QuizTeacherGraded::query()
            ->from('el_quiz_teacher_graded as a')
            ->leftJoin('el_profile_view as b', 'b.user_id', '=', 'a.teacher_id')
            ->where('a.quiz_id', $quiz_id)
            ->get(['b.code', 'b.full_name', 'a.created_at']);

        return view('quiz::backend.modal.modal_teacher_graded', [
            'teacher_graded' => $teacher_graded,
        ]);
    }
}
