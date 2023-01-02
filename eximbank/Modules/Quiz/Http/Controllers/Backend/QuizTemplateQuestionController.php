<?php

namespace Modules\Quiz\Http\Controllers\Backend;

use App\Models\Profile;
use App\Models\Categories\Unit;
use App\Scopes\DraftScope;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\Query\Builder;

use Modules\Quiz\Entities\QuestionCategory;
use Modules\Quiz\Entities\Question;
use Modules\Quiz\Entities\QuestionAnswer;

use Modules\Quiz\Entities\QuizTemplates;
use Modules\Quiz\Entities\QuizTemplatesQuestion;
use Modules\Quiz\Entities\QuizTemplatesQuestionCategory;
use TorMorten\Eventy\Facades\Events as Eventy;

class QuizTemplateQuestionController extends Controller
{
    protected $template = [];
    protected $ramdom_questions = [0];
    protected $answer_text = [
        'a',
        'b',
        'c',
        'd',
        'e',
        'f',
        'g',
        'h',
        'i',
        'j',
        'k',
        'l',
        'm',
        'n',
        'p',
        'q',
    ];

    public function index($quiz_id) {
        $profile = profile();
        $unit = Unit::where('code', '=', $profile->unit_code)->first();

        $quiz = QuizTemplates::find($quiz_id);
        $quiz_questions = QuizTemplatesQuestion::getQuestions($quiz_id);
        $categories = function($cat_id){
            return QuestionCategory::find($cat_id);
        };
        $questions = function($ques_id){
            return Question::find($ques_id);
        };
        $qqc = function ($quiz_id, $num_order) {
            return QuizTemplatesQuestionCategory::where('quiz_id', '=', $quiz_id)
                ->where('num_order', '=', $num_order)
                ->orderBy('num_order', 'ASC')
                ->get();
        };

        return view('quiz::backend.quiz_template.question', [
            'quiz_questions' => $quiz_questions,
            'categories' => $categories,
            'questions' => $questions,
            'quiz' => $quiz,
            'qqc' => $qqc,
            'unit' => $unit,
            'disabled' => '',
        ]);
    }

    public function showModalCategory($id, Request $request) {
        QuestionCategory::addGlobalScope(new DraftScope());
        $categories = QuestionCategory::getCategories();
        $count_question = function ($cat_id) {
            return QuestionCategory::countQuestion($cat_id);
        };
        $quiz_id = $id;
        return view('quiz::backend.modal.quiz_template_question_random', [
            'categories' => $categories,
            'quiz_id' => $quiz_id,
            'count_question' => $count_question,
        ]);
    }

    public function showModal($id, Request $request) {
        QuestionCategory::addGlobalScope(new DraftScope());
        $categories = QuestionCategory::getCategories();
        $count_question = function ($cat_id) {
            return QuestionCategory::countQuestion($cat_id);
        };
        return view('quiz::backend.modal.quiz_template_question_category', [
            'categories' => $categories,
            'quiz_id' => $id,
            'count_question' => $count_question,
        ]);
    }

    public function showModalQQCategory($id, Request $request) {
        $quiz = QuizTemplates::find($id);
        $num_order = $request->num_order;
        $category = QuizTemplatesQuestionCategory::firstOrNew(['id' => $request->category_id]);
        return view('quiz::backend.modal.quiz_template_add_qqcategory', [
            'quiz' => $quiz,
            'num_order' => $num_order,
            'category' => $category
        ]);
    }

    public function saveQQCategory($id, Request $request) {
        $this->validateRequest([
            'name' => 'required',
            'num_order' => 'required',
            'percent_group' => 'required',
        ], $request, ['name' => 'Tên đề mục']);

        $num_order = $request->num_order;
        $percent_group = $request->percent_group;

        $model = QuizTemplatesQuestionCategory::firstOrNew(['id' => $request->id]);
        if (empty($request->id)) {
            $model->quiz_id = $id;
            $model->num_order = $num_order - 1;
        }
        $total = QuizTemplatesQuestionCategory::sumMaxScoreByQuizID($id);

        if ($request->id){
            if ($percent_group >= $model->percent_group){
                if ($total == 100){
                    json_message('Tổng phần trăm không thể lưu được nữa', 'error');
                }

                if (($total + ($percent_group - $model->percent_group)) > 100){
                    json_message('Tổng phần trăm chỉ còn ' . (100 - $total) , 'error');
                }
            }
        }else{
            if ($total == 100){
                json_message('Tổng phần trăm không thể lưu được nữa', 'error');
            }

            if (($total + $percent_group) > 100){
                json_message('Tổng phần trăm chỉ còn ' . (100 - $total) , 'error');
            }

        }

        $model->name = $request->name;
        $model->percent_group = $percent_group;

        if ($model->save()) {
            $questions = QuizTemplatesQuestion::where('quiz_id','=', $id)
                ->orderBy('num_order', 'ASC')
                ->get();

            $categories = QuizTemplatesQuestionCategory::where('quiz_id','=', $id)
                ->orderBy('num_order', 'ASC')
                ->get();

            $quiz = QuizTemplates::find($id);
            $quiz->status = 2;
            $quiz->save();

            foreach ($categories as $category){
                foreach ($questions as $key => $question){
                    if ($question->num_order >= ($category->num_order + 1)){
                        $question->qqcategory = $category->id;
                        $question->save();
                    }
                }
            }

            json_message('Lưu đề mục thành công');
        }

        json_message('Lỗi không thể thêm đề mục', 'error');
    }

    public function removeQQCategory($id, Request $request) {
        QuizTemplatesQuestionCategory::destroy([$request->category_id]);

        $quiz = QuizTemplates::find($id);
        $quiz->status = 2;
        $quiz->save();

        json_message('ok');
    }

    public function saveQuestionRandom($id, Request $request) {
        $this->validateRequest([
            'category_id' => 'required|exists:el_question_category,id',
        ], $request, [
            'category_id' => 'Danh mục',
        ]);

        $cat_id = $request->category_id;
        $random_question = $request->random_question;
        $random_question_d = $request->random_question_d;
        $random_question_tb = $request->random_question_tb;
        $random_question_k = $request->random_question_k;

        if(!$random_question && !$random_question_d && !$random_question_tb && !$random_question_k){
            json_message('Số câu hỏi ngẫu nhiên chưa nhập', 'error');
        }

        if($random_question){
            $count_question = QuestionCategory::countQuestion($cat_id); //Tổng số câu hỏi active trong danh mục
            $total_question_random = QuizTemplatesQuestion::countQuestion($id, $cat_id); //Tổng số câu hỏi trong danh mục đã được thêm
            $rest = $count_question - $total_question_random; //Số câu còn lại được thêm trong danh mục

            if($random_question > $count_question){
                json_message('Số câu hỏi ngẫu nhiên vượt quá số câu hỏi trong danh mục', 'error');
            }

            if($random_question > $rest){
                json_message('Danh mục chỉ còn thêm được '.$rest.' câu hỏi', 'error');
            }

            $max_order = QuizTemplatesQuestion::getMaxOrder($id);
            for($ii = 1; $ii <= $random_question; $ii++){
                $max_order += 1;
                $model = new QuizTemplatesQuestion();
                $model->quiz_id = $id;
                $model->qcategory_id = $cat_id;
                $model->random = 1;
                $model->num_order = $max_order;
                $model->save();
            }
        }else {
            $count_question = QuestionCategory::countQuestion($cat_id); //Tổng số câu hỏi active trong danh mục
            $total_question_random = QuizTemplatesQuestion::countQuestion($id, $cat_id); //Tổng số câu hỏi trong danh mục đã được thêm
            $rest = $count_question - $total_question_random; //Số câu còn lại được thêm trong danh mục

            $total_random_question = ($random_question_d + $random_question_tb + $random_question_k);
            if($total_random_question > $rest){
                json_message('Danh mục chỉ còn thêm được '.$rest.' câu hỏi', 'error');
            }

            $num_d = $num_tb = $num_k = 0;
            if($random_question_d){
                $count_question_d = QuestionCategory::countQuestion($cat_id, 'D'); //Tổng số câu hỏi mức độ Dễ active trong danh mục
                $total_question_random_d = QuizTemplatesQuestion::countQuestion($id, $cat_id, 'D'); //Tổng số câu hỏi Dễ trong danh mục đã được thêm
                $rest_d = $count_question_d - $total_question_random_d; //Số câu Dễ còn lại được thêm trong danh mục

                if($random_question_d > $count_question_d){
                    json_message('Số câu hỏi ngẫu nhiên Dễ vượt quá số câu hỏi trong danh mục', 'error');
                }
                if($random_question_d > $rest_d){
                    json_message('Danh mục chỉ còn thêm được '.$rest_d.' câu hỏi Dễ', 'error');
                }
                $num_d = $random_question_d;
            }
            if($random_question_tb){
                $count_question_tb = QuestionCategory::countQuestion($cat_id, 'TB'); //Tổng số câu hỏi mức độ TB active trong danh mục
                $total_question_random_tb = QuizTemplatesQuestion::countQuestion($id, $cat_id, 'TB'); //Tổng số câu hỏi TB trong danh mục đã được thêm
                $rest_tb = $count_question_tb - $total_question_random_tb; //Số câu TB còn lại được thêm trong danh mục

                if($random_question_tb > $count_question_tb){
                    json_message('Số câu hỏi ngẫu nhiên Trung bình vượt quá số câu hỏi trong danh mục', 'error');
                }
                if($random_question_tb > $rest_tb){
                    json_message('Danh mục chỉ còn thêm được '.$rest_tb.' câu hỏi Trung bình', 'error');
                }
                $num_tb = $random_question_tb;
            }
            if($random_question_k){
                $count_question_k = QuestionCategory::countQuestion($cat_id, 'K'); //Tổng số câu hỏi mức độ Khó active trong danh mục
                $total_question_random_k = QuizTemplatesQuestion::countQuestion($id, $cat_id, 'K'); //Tổng số câu hỏi Khó trong danh mục đã được thêm
                $rest_k = $count_question_k - $total_question_random_k; //Số câu Khó còn lại được thêm trong danh mục

                if($random_question_k > $count_question_k){
                    json_message('Số câu hỏi ngẫu nhiên Khó vượt quá số câu hỏi trong danh mục', 'error');
                }
                if($random_question_k > $rest_k){
                    json_message('Danh mục chỉ còn thêm được '.$rest_k.' câu hỏi Khó', 'error');
                }
                $num_k = $random_question_k;
            }

            $max_order = QuizTemplatesQuestion::getMaxOrder($id);
            for($ii = 1; $ii <= $total_random_question; $ii++){
                $max_order += 1;

                $model = new QuizTemplatesQuestion();
                $model->quiz_id = $id;
                $model->qcategory_id = $cat_id;
                $model->random = 1;
                $model->num_order = $max_order;
                $model->difficulty = $num_d > 0 ? 'D' : ($num_tb > 0 ? 'TB' : ($num_k > 0 ? 'K' : ''));
                $model->save();

                if($num_d > 0){
                    $num_d -= 1;
                }else if($num_tb > 0 && $num_d == 0){
                    $num_tb -= 1;
                }else if($num_k > 0 && $num_tb == 0 && $num_d == 0){
                    $num_k -= 1;
                }
            }
        }

        $quiz = QuizTemplates::find($id);
        $quiz->status = 2;
        $quiz->save();

        $redirect = route('module.quiz_template.question', ['id' => $id]) ;
        json_result([
            'status' => 'success',
            'message' => trans('laother.successful_save'),
            'redirect' => $redirect
        ]);
    }

    public function saveCategoryQuestion($id, Request $request) {
        $this->validateRequest([
            'ids' => 'nullable|exists:el_question,id',
        ], $request);

        $cate_id = $request->cate_id;
        $ids = $request->ids;

        $max_order = QuizTemplatesQuestion::getMaxOrder($id);

        foreach($ids as $ques_id){
            $question = Question::find($ques_id, ['difficulty']);
            if(QuizTemplatesQuestion::where('quiz_id', '=', $id)->where('question_id', '=', $ques_id)->exists()){
                continue;
            }

            $max_order += 1;
            $model = new QuizTemplatesQuestion();
            $model->quiz_id = $id;
            $model->question_id = $ques_id;
            $model->num_order = $max_order;
            $model->qcategory_id = $cate_id;
            $model->difficulty = $question->difficulty;
            $model->save();
        }

        $quiz = QuizTemplates::find($id);
        $quiz->status = 2;
        $quiz->save();

        json_result([
            'status' => 'success',
            'message' => trans('laother.successful_save'),
            'redirect' => route('module.quiz_template.question', ['id' => $id])
        ]);
    }

    public function getDataQuestion($quiz_id, Request $request) {
        $this->validateRequest([
            'category_id' => 'nullable|exists:el_question_category,id',
        ], $request);

        $search = $request->input('search');
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);
        $exclude_ids = QuizTemplatesQuestion::getArrayQuestions($quiz_id);

        $query = Question::query();
        $query->where('category_id', '=', $request->category_id);
        $query->where('status', '=', 1);
        $query->whereNotIn('id', $exclude_ids);

        if ($search) {
            $query->where('name', 'like', '%'. $search .'%');
        }

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function removeQuizQuestion($quiz_id, Request $request){
        $this->validateRequest([
            'quiz_ques_id' => 'required',
        ], $request);

        $id = $request->quiz_ques_id;

        QuizTemplatesQuestion::find($id)->delete();

        $quiz = QuizTemplates::find($quiz_id);
        $quiz->status = 2;
        $quiz->save();

        json_message('ok');
    }

    public function updateMaxScore($quiz_id, Request $request){
        $this->validateRequest([
            'quiz_ques_id' => 'required',
            'max_score' => 'required',
        ], $request);

        $id = $request->quiz_ques_id;
        $max_score = $request->max_score;

        $quiz_question = QuizTemplatesQuestion::find($id);
        $quiz_question->max_score = $max_score;
        $quiz_question->save();

        $quiz = QuizTemplates::find($quiz_id);
        $quiz->status = 2;
        $quiz->save();

        json_message('ok');
    }

    public function updateNumOrder($quiz_id, Request $request){
        $this->validateRequest([
            'question' => 'required',
        ], $request);

        $questions = $request->question;
        $category = 0;
        $index = 0;
        foreach ($questions as $question) {
            if (is_numeric($question)) {
                QuizTemplatesQuestion::where('id', '=', $question)->update([
                    'num_order' => ($index + 1),
                    'qqcategory' => $category
                ]);

                $index ++;
            }
            else {
                $catid = str_replace('c_', '', $question);
                QuizTemplatesQuestionCategory::where('id', '=', $catid)->update([
                    'num_order' => ($index)
                ]);
                $category = $catid;
            }
        }

        $quiz = QuizTemplates::find($quiz_id);
        $quiz->status = 2;
        $quiz->save();

        json_message('ok');
    }

    public function reviewQuiz($quiz_id, Request $request){
        $quiz = QuizTemplates::findOrFail($quiz_id);

        $this->create($quiz);

        $template = $this->getTemplateData($quiz);

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

        return view('quiz::backend.quiz_template.view', [
            'quiz' => $quiz,
            'questions' => $questions,
            'disabled' => 1,
            'qqcategory' => $qqcategory,
        ]);
    }

    public function getQuestionReviewQuiz($quiz_id, Request $request) {
        $quiz = QuizTemplates::findOrFail($quiz_id);

        $template = $this->getTemplateData($quiz);

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

    protected function getTemplateData($quiz) {
        $storage = \Storage::disk('local');
        $template = 'review_quiz/quiz_template-' . $quiz->id .'.json';

        if ($storage->exists($template)) {
            return json_decode($storage->get($template), true);
        }
        return null;
    }

    protected function create($quiz) {
        $this->mapQuizQuestionCategories($quiz);
        $this->mapQuizQuestions($quiz);

        if ($this->template) {
            $storage = \Storage::disk('local');
            $attempt_folder = 'review_quiz';

            if (!$storage->exists($attempt_folder)) {
                \File::makeDirectory($storage->path($attempt_folder), 0777, true);
            }

            $quiz->save();
            $storage->put($attempt_folder . '/quiz_template-' . $quiz->id . '.json', json_encode($this->template));

            return true;
        }

        return false;
    }

    protected function mapQuizQuestionCategories($quiz) {
        $qqcategorys = QuizTemplatesQuestionCategory::whereQuizId($quiz->id)->orderBy('num_order', 'asc')->get();

        $categories = [];
        foreach ($qqcategorys as $qqcategory) {
            $max_score = $qqcategory->sumMaxScore();
            $per_score = $qqcategory->percent_group > 0 ? ($quiz->max_score * $qqcategory->percent_group / 100) / ($max_score ? $max_score : 1) : ($quiz->max_score / $max_score);

            $categories[] = [
                'name' => $qqcategory->name,
                'num_order' => $qqcategory->num_order,
                'percent_group' => $qqcategory->percent_group,
                'qqcategory' => $qqcategory->id,
                'max_score' => $max_score,
                'per_score' => $per_score,
            ];
        }

        $this->template['categories'] = $categories;
    }

    protected function mapQuizQuestions($quiz) {
        $questions = [];
        $max_score = QuizTemplatesQuestion::getTotalScore($quiz->id);
        $score_group = $max_score > 0 ? ($quiz->max_score / $max_score) : 0;

        if ($quiz->shuffle_question == 1){
            $list_questions = QuizTemplatesQuestion::with('question:id,name,type,multiple,multiple_full_score,answer_horizontal,image_drag_drop')
            ->whereQuizId($quiz->id)->orderby('num_order')->inRandomOrder()->get();
        }else{
            $list_questions = QuizTemplatesQuestion::with('question:id,name,type,multiple,multiple_full_score,answer_horizontal,image_drag_drop')
            ->whereQuizId($quiz->id)->orderby('num_order')->get();
        }

        foreach ($list_questions as $key => $question) {
            if ($question->random == 1) {
                $ranrom = Question::where('category_id','=', $question->qcategory_id)
                    ->whereNotIn('id', $this->ramdom_questions)
                    ->whereNotIn('id', function (Builder $builder) use ($quiz) {
                        $builder->select(['question_id'])
                            ->from('el_quiz_question')
                            ->where('quiz_id', '=', $quiz->id)
                            ->whereNotNull('question_id');
                    })
                    ->inRandomOrder()
                    ->first();

                $questions[$question->id] = [
                    'id' => $question->id,
                    'index' => $key,
                    'qindex' => $question->num_order,
                    'question_id' => $ranrom->id,
                    'name' => $ranrom->name,
                    'type' => $ranrom->type,
                    'category_id' => $ranrom->category_id,
                    'qqcategory_id' => $question->qqcategory,
                    'multiple' => $ranrom->multiple,
                    'score_group' => $score_group,
                    'max_score' => $question->max_score,
                    'answers' => $this->getAnwsersQuestion($quiz, $ranrom->id),
                    'correct_answers' => $this->getCorrectAnwsersQuestion($ranrom),
                    'answer_horizontal' => $ranrom->answer_horizontal,
                    'image_drag_drop' => $ranrom->image_drag_drop ? image_file($ranrom->image_drag_drop) : '',
                ];

                $this->ramdom_questions[] = $ranrom->id;
            }
            else {
                $questions[$question->id] = [
                    'id' => $question->id,
                    'index' => $key,
                    'qindex' => $question->num_order,
                    'question_id' => $question->question_id,
                    'name' => $question->question->name,
                    'type' => $question->question->type,
                    'category_id' => $question->question->category_id,
                    'qqcategory_id' => $question->qqcategory,
                    'multiple' => $question->question->multiple,
                    'score_group' => $score_group,
                    'max_score' => $question->max_score,
                    'answers' => $this->getAnwsersQuestion($quiz, $question->question_id),
                    'correct_answers' => $this->getCorrectAnwsersQuestion($question->question),
                    'answer_horizontal' => $question->question->answer_horizontal,
                    'image_drag_drop' => $question->question->image_drag_drop ? image_file($question->question->image_drag_drop) : '',
                ];
            }
        }

        $this->template['questions'] = $questions;
    }

    protected function getAnwsersQuestion($quiz, $question_id) {
        $question = Question::find($question_id);
        $anwsers = QuestionAnswer::whereQuestionId($question_id);
        if ($quiz->shuffle_answers == 1 && $question->shuffle_answers == 1){
            $anwsers->inRandomOrder();
        }
        $anwsers = $anwsers->get()->toArray();

        foreach ($anwsers as $index => $anwser) {
            if (strpos($anwser['title'], '<p>') !== false) {
                $title = str_replace(['<p>','</p>'], '', $anwser['title']);
                $anwsers[$index]['title'] = $title;
            }
            $anwsers[$index]['index_text'] = @$this->answer_text[$index];
            $anwsers[$index]['image_answer'] = $anwser['image_answer'] ? image_file($anwser['image_answer']) : '';
        }

        return $anwsers;
    }

    protected function getCorrectAnwsersQuestion($question){
        $correct_answers = [];
        if ($question->type == 'multiple-choise') {
            if ($question->multiple == 0){
                $correct_answers = QuestionAnswer::where('question_id', '=', $question->id)
                    ->where('correct_answer', '=', 1)
                    ->pluck('id')
                    ->toArray();
            }
            if ($question->multiple == 1){
                $correct_answers = QuestionAnswer::where('question_id', '=', $question->id)
                    ->where('percent_answer', '>', 0)
                    ->pluck('id')
                    ->toArray();
            }
        }
        if ($question->type == 'matching'){
            $answers = QuestionAnswer::query()->where('question_id', '=', $question->id)->whereNotNull('matching_answer')->get();
            foreach ($answers as $answer){
                $correct_answers[] = $answer->id;
            }
        }
        if ($question->type == 'select_word_correct'){
            $correct_answers = QuestionAnswer::where('question_id', '=', $question->id)
                ->where('correct_answer', '>', 0)
                ->pluck('id')
                ->toArray();
        }

        if ($question->type == 'drag_drop_marker'){
            $correct_answers = QuestionAnswer::where('question_id', '=', $question->id)
                ->whereNotNull('marker_answer')
                ->pluck('id')
                ->toArray();
        }

        return $correct_answers;
    }
}
