<?php

namespace Modules\Quiz\Http\Controllers\Backend;

use App\Models\Profile;
use App\Models\ProfileView;
use App\Models\Permission;
use App\Scopes\DraftScope;
use App\Models\Categories\Titles;
use App\Models\Categories\Unit;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Modules\Offline\Entities\OfflineCourse;
use Modules\PermissionApproved\Entities\ApprovedModelTracking;
use Modules\Quiz\Entities\Question;
use Modules\Quiz\Entities\QuestionAnswer;
use Modules\Quiz\Entities\QuestionCategory;
use Modules\Quiz\Entities\QuizPermission;
use Modules\Quiz\Entities\QuizTemplates;
use Modules\Quiz\Entities\QuizTemplatesQuestion;
use Modules\Quiz\Entities\QuizTemplatesQuestionCategory;
use Modules\Quiz\Entities\QuizTemplatesRank;
use Modules\Quiz\Entities\QuizTemplatesSetting;
use Modules\Quiz\Entities\QuizType;
use Modules\Quiz\Entities\Quiz;
use PhpOffice\PhpWord\Style\Cell;

class QuizTemplatesController extends Controller
{
    public $is_unit = 0;

    public function index()
    {
        return view('quiz::backend.quiz_template.index', [
        ]);
    }

    public function form($id = null)
    {
        $model = QuizTemplates::firstOrNew(['id' => $id]);
        $quiz_type = QuizType::find($model->type_id, ['id', 'name']);
        $page_title = $model->name ? $model->name : trans('labutton.add_new');
        $unit = Unit::firstOrNew(['id' => $model->unit_id]);
        $setting = QuizTemplatesSetting::where('quiz_id', '=', $id)->first();
        return view('quiz::backend.quiz_template.form', [
            'model' => $model,
            'page_title' => $page_title,
            'is_unit' => $this->is_unit,
            'unit' => $unit,
            'setting' => $setting,
            'quiz_type' => $quiz_type,
        ]);
    }

    public function getData(Request $request)
    {
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $search = $request->input('search');

        QuizTemplates::addGlobalScope(new DraftScope());
        $query = QuizTemplates::query();
        $query->select(['*']);

        if ($search) {
            $query->where(function ($sub_query) use ($search) {
                $sub_query->orWhere('name', 'like', '%' . $search . '%');
                $sub_query->orWhere('code', 'like', '%' . $search . '%');
            });
        }

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();
        foreach ($rows as $row) {
            $row->question = '';
            if (QuizPermission::addQuestionQuiz($row)) {
                $row->question = route('module.quiz_template.question', ['id' => $row->id]);
            }

            $query = QuestionCategory::query();
            $query->select(['a.name']);
            $query->from('el_question_category as a');
            $query->leftJoin('el_question as question', 'question.category_id', '=', 'a.id');
            $query->leftjoin('el_quiz_templates_question as b', function ($join) {
                $join->on('question.id', '=', 'b.question_id');
                $join->orOn('a.id', '=', 'b.qcategory_id');
            });
            $query->where('b.quiz_id', $row->id);
            $query->groupBy('a.name');
            $get_cates_name = $query->get();
            if (!$get_cates_name->isEmpty()) {
                $get_cates = [];
                foreach ($get_cates_name as $get_cates_name) {
                    $get_cates[] = $get_cates_name->name;
                }
                $row->get_cates = $get_cates;
            } else {
                $row->get_cates = '';
            }

            $row->edit_url = route('module.quiz_template.edit', [$row->id]);

            $row->quiz_type = $row->quiz_type == 1 ? 'Online' : ($row->quiz_type == 2 ? trans("latraining.offline") : 'Thi độc lập');
            $created_by = $row->created_by ? $row->created_by : 2;
            $updated_by = $row->updated_by ? $row->updated_by : 2;
            $row->info = route('backend.get_user_info',['created' => $created_by, 'updated' => $updated_by]).'?created_at='. $row->created_at . '&updated_at='. $row->updated_at;

            $row->export_url = route('module.quiz_template.export_quiz', ['id' => $row->id]);

            $row->user_approved_url = $row->approved_by ? route('module.quiz_template.get_user_create_quiz_template', ['user_id' => $row->approved_by]) : '';
            $row->time_approved = $row->time_approved ? get_date($row->time_approved, 'd/m/Y h:i') : '';
        }

        return response()->json(['total' => $count, 'rows' => $rows]);
    }

    public function save(Request $request)
    {
        $this->validateRequest([
            'code' => 'required|unique:el_quiz_templates,code,' . $request->id,
            'name' => 'required',
            'limit_time' => 'required|min:1',
            'pass_score' => 'required|min:0|max:100',
            'max_score' => 'required|min:0|max:100',
            'max_attempts' => 'required',
            'questions_perpage' => 'required',
            'grade_methor' => 'required',
            'img' => 'nullable|string',
        ], $request, QuizTemplates::getAttributeName());

        $questions_perpage = $request->input('questions_perpage');
        $limit_time = $request->input('limit_time');
        $pass_score = $request->input('pass_score');
        $max_score = $request->input('max_score');
        $shuffle = $request->post('shuffle_answers');

        $model = QuizTemplates::firstOrNew(['id' => $request->post('id')]);
        $model->fill($request->all());
        $model->shuffle_answers = if_empty($shuffle, 0);

        if ($request->img) {
            $sizes = config('image.sizes.medium');
            $model->img = upload_image($sizes, $request->img);
        }

        if ($limit_time < 1) {
            json_message('Thời gian làm bài phải lớn hơn 1 phút', 'error');
        }

        if ($pass_score < 0 || $pass_score > 100) {
            json_message('Điểm chuẩn trong khoảng 0 đến 100', 'error');
        }

        if ($max_score < 0 || $max_score > 100) {
            json_message('Điểm tối đa trong khoảng 0 đến 100', 'error');
        }

        if ($pass_score > $max_score) {
            json_message('Điểm chuẩn không được lớn hơn điểm tối đa', 'error');
        }

        if ($questions_perpage < 0) {
            json_message('Số câu hỏi ít nhất là 0', 'error');
        }

        $model->created_by = $request->created_by ? $request->created_by : profile()->user_id;
        $model->updated_by = profile()->user_id;
        $model->unit_id = $request->is_unit > 0 ? $request->is_unit : $request->input('unit_id');
        if (empty($model->id)) {
            $model->status = 2;
        }

        if ($model->save()) {
            $redirect = route('module.quiz_template.edit', [$model->id]);

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

    public function remove(Request $request)
    {
        $ids = $request->input('ids', null);
        foreach ($ids as $id) {
            $check = Quiz::where('quiz_template_id', $id)->first(['name']);
            if (!empty($check)) {
                json_message('Không thể xóa vì kỳ thi: ' . $check->name . ' đang sử dụng', 'error');
            }
            $quiz = QuizTemplates::find($id);
            $quiz->delete();
        }

        json_result([
            'status' => 'success',
            'message' => trans('laother.delete_success'),
        ]);
    }

    public function saveIsOpen(Request $request)
    {
        $this->validateRequest([
            'ids' => 'required',
            'status' => 'required|in:0,1'
        ], $request, [
            'ids' => trans('lamenu.quiz'),
        ]);

        $ids = $request->input('ids', null);
        $status = $request->input('status', 0);
        if (is_array($ids)) {
            foreach ($ids as $id) {
                $model = QuizTemplates::findOrFail($id);
                $model->is_open = $status;
                $model->save();
            }
        } else {
            $model = QuizTemplates::findOrFail($ids);
            $model->is_open = $status;
            $model->save();
        }

        json_result([
            'status' => 'success',
            'message' => trans('laother.successful_save'),
        ]);
    }

    public function approve(Request $request)
    {
        $this->validateRequest([
            'ids' => 'required',
            'status' => 'required|in:0,1'
        ], $request, [
            'ids' => trans('lamenu.quiz'),
        ]);

        $ids = $request->input('ids', null);
        $status = $request->input('status', 0);
        $note = $request->input('note', null);
        foreach ($ids as $id) {
            (new ApprovedModelTracking())->updateApprovedTracking(QuizTemplates::getModel(), $id, $status, $note);
        }

        if ($status == 0) {
            json_message('Đã từ chối', 'success');
        } else {
            json_message('Duyệt thành công', 'success');
        }
    }

    public function getDataRank($id, Request $request)
    {
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = QuizTemplatesRank::where('quiz_id', '=', $id);
        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();

        foreach ($rows as $row) {
            $row->score_min = number_format($row->score_min, 2);
            $row->score_max = number_format($row->score_max, 2);
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function saveRank($id, Request $request)
    {
        $this->validateRequest([
            'rank' => 'required',
            'score_min' => 'required',
            'score_max' => 'required',
        ], $request);

        $rank = $request->input('rank');
        $score_min = $request->input('score_min');
        $score_max = $request->input('score_max');

        $quiz = QuizTemplates::find($id);
        if ($score_min < 0) {
            json_result([
                'status' => 'error',
                'message' => 'Điểm min phải lớn hơn bằng 0',
            ]);
        } elseif ($score_min > $score_max) {
            json_result([
                'status' => 'error',
                'message' => 'Điểm min phải nhỏ hơn Điểm max',
            ]);
        } elseif ($score_max > $quiz->max_score || $score_min > $quiz->max_score) {
            json_result([
                'status' => 'error',
                'message' => 'Điểm phải nhỏ hơn điểm tối đa',
            ]);
        }

        $check1 = QuizTemplatesRank::query();
        $check1->where('score_min', '<=', $score_min);
        $check1->where('score_max', '>=', $score_min);
        $check1->where('quiz_id', '=', $id);
        if ($check1->exists()) {
            json_result([
                'status' => 'error',
                'message' => 'Điểm min nhập không họp lệ',
            ]);
        }

        $check2 = QuizTemplatesRank::query();
        $check2->where('score_min', '<=', $score_max);
        $check2->where('score_max', '>=', $score_max);
        $check2->where('quiz_id', '=', $id);
        if ($check2->exists()) {
            json_result([
                'status' => 'error',
                'message' => 'Điểm max nhập không họp lệ',
            ]);
        }

        $model = new QuizTemplatesRank();
        $model->quiz_id = $id;
        $model->rank = $rank;
        $model->score_min = $score_min;
        $model->score_max = $score_max;

        if ($model->save()) {
            json_message('ok');
        }
    }

    public function removeRank($id, Request $request)
    {
        $ids = $request->input('ids', null);
        QuizTemplatesRank::destroy($ids);
        json_result([
            'status' => 'success',
            'message' => trans('laother.delete_success'),
        ]);
    }

    public function saveSetting($id, Request $request)
    {

        $model = QuizTemplatesSetting::firstOrNew(['id' => $request->id]);
        $model->after_test_review_test = $request->after_test_review_test;
        $model->after_test_yes_no = $request->after_test_yes_no;
        $model->after_test_score = $request->after_test_score;
        $model->after_test_specific_feedback = $request->after_test_specific_feedback;
        $model->after_test_general_feedback = $request->after_test_general_feedback;
        $model->after_test_correct_answer = $request->after_test_correct_answer;
        $model->exam_closed_review_test = $request->exam_closed_review_test;
        $model->exam_closed_yes_no = $request->exam_closed_yes_no;
        $model->exam_closed_score = $request->exam_closed_score;
        $model->exam_closed_specific_feedback = $request->exam_closed_specific_feedback;
        $model->exam_closed_general_feedback = $request->exam_closed_general_feedback;
        $model->exam_closed_correct_answer = $request->exam_closed_correct_answer;
        $model->quiz_id = $id;

        $model->save();

        json_message(trans('laother.successful_save'));
    }

    public function loadUnit(Request $request)
    {
        $search = $request->search;
        $query = Unit::query();
        $query->select(\DB::raw('id, CONCAT(code, \' - \', name) AS text'));
        $query->where('status', '=', 1);
        $managers = Permission::getIdUnitManagerByUser('module.training_unit');

        if ($managers) {
            $query->whereIn('id', $managers);
        }

        if ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        $paginate = $query->paginate(10);
        $data['results'] = $query->get();
        if ($paginate->nextPageUrl()) {
            $data['pagination'] = ['more' => true];
        }

        json_result($data);
    }

    public function getUserCreateQuiz(Request $request)
    {
        $user = Profile::find($request->user_id);
        $unit = Unit::where('code', '=', $user->unit_code)->first();
        $title = Titles::where('code', '=', $user->title_code)->first();
        return view('quiz::backend.modal.user_create_quiz', [
            'user' => $user,
            'unit' => $unit,
            'title' => $title,
        ]);
    }

    public function exportQuiz($id)
    {

        $quiz = QuizTemplates::findOrFail($id);
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        \PhpOffice\PhpWord\Settings::setOutputEscapingEnabled(true);
        $section = $phpWord->addSection();
        $section->addText(Str::upper('BÀI KIỂM TRA'), [
            'name' => 'Times New Roman',
            'size' => 12,
            'bold' => true,
        ], [
            'align' => Cell::VALIGN_CENTER
        ]);
        $section->addText(Str::upper($quiz->name), [
            'name' => 'Times New Roman',
            'size' => 12,
            'bold' => true,
        ], [
            'align' => Cell::VALIGN_CENTER
        ]);
        $section->addText($quiz->description, [
            'name' => 'Times New Roman',
            'size' => 12,
            'bold' => true,
        ], [
            'align' => Cell::VALIGN_CENTER
        ]);

        $section->addText('Thời gian làm bài: ' . $quiz->limit_time . ' phút', [
            'name' => 'Times New Roman',
            'size' => 12,
            'bold' => true,
        ], [
            'align' => Cell::VALIGN_CENTER
        ]);

        $arrawser = range('a', 'z');

        $query = QuizTemplatesQuestion::query()
            ->where('quiz_id', '=', $quiz->id);
        $rows = $query->get([
            'id',
            'random',
            'qcategory_id',
            'question_id',
        ]);
        $qqc = function ($quiz_id, $num_order) {
            return QuizTemplatesQuestionCategory::where('quiz_id', '=', $quiz_id)
                ->where('num_order', '=', $num_order)
                ->orderBy('num_order', 'ASC')
                ->first();
        };

        $ramdom_questions = [0];
        foreach ($rows as $qindex => $row) {
            if ($row->random == 1) {
                $random = \DB::table('el_question')->where('category_id', '=', $row->qcategory_id)
                    ->whereNotIn('id', $ramdom_questions)
                    ->whereNotExists(function (Builder $builder) use ($quiz) {
                        $builder->select(['question_id', 'qcategory_id', 'question_id'])
                            ->from('el_quiz_question')
                            ->where('quiz_id', '=', $quiz->id)
                            ->whereColumn('question_id', '=', 'el_question.id')
                            ->whereNotNull('question_id');
                    })
                    ->where('status', 1)
                    ->inRandomOrder()
                    ->first();
                $question = Question::find($random->id);
                $random_questions[] = $random->id;
            } else {
                $question = Question::find($row->question_id);
            }
            $questionId = $question->id;
            $row->name = $question->name;
            $row->type = $question->type;
            $row->image_drag_drop = $question->image_drag_drop;
            $qqcategorys = $qqc($quiz->id, $qindex);
            if ($qqcategorys) {
                $section->addText(Str::upper($qqcategorys->name), [
                    'name' => 'Times New Roman',
                    'size' => 12,
                    'bold' => true,
                ]);
            }
            $text = trim(html_entity_decode(strip_tags($row->name), ENT_QUOTES), "\xc2\xa0");
            $textlines = explode("\n", $text);

            for ($i = 0; $i < sizeof($textlines); $i++) {
                $text = str_replace("\r", "", $textlines[$i]);
                if ($text != '') {
                    $section->addText($i == 0 ? ($qindex + 1) . '. ' . $text : $text, [
                        'name' => 'Times New Roman',
                        'size' => 12,
                    ]);
                }
            }

            if ($row->type) {
                switch ($row->type) {
                    case ('essay'):
                        $section->addText(str_repeat('_', 675));
                        break;
                    case('matching'):
                        $questionsOfMatching = [];
                        $answersOfMatching = '';
                        $questions = QuestionAnswer::query()->where('question_id', $questionId)->get(['title', 'matching_answer']);
                        foreach ($questions as $index => $value) {
                            $question = str_repeat(' ', 5) . strtoupper($arrawser[$index]) . '. ' . trim(html_entity_decode(strip_tags($value->title), ENT_QUOTES), "\xc2\xa0") . ' ___';
                            $answer = $arrawser[$index] . '. ' . html_entity_decode($value->matching_answer, ENT_QUOTES) . str_repeat(' ', 5);
                            $answersOfMatching .= $answer;
                            $section->addText($question, [
                                'name' => 'Times New Roman',
                                'size' => 12,
                            ]);
                        }
                        $section->addText($answersOfMatching, [
                            'name' => 'Times New Roman',
                            'size' => 12,
                        ]);
                        unset($questionsOfMatching, $answersOfMatching);
                        break;
                    case('drag_drop_image'):
                        $section->addImage(image_file($row->image_drag_drop), array('width' => 210, 'height' => 210, 'align' => 'center', 'ratio' => true));
                        $questions = QuestionAnswer::query()->where('question_id', $questionId)->get(['title', 'image_answer']);
                        foreach ($questions as $index => $value) {
                            if ($value->image_answer) {
                                $section->addImage(image_file($value->image_answer), array('width' => 50, 'height' => 50, 'ratio' => true));
                            } else {
                                $val = str_repeat(' ', 5) . $arrawser[$index] . '. ' . trim(html_entity_decode(strip_tags($value->title), ENT_QUOTES), "\xc2\xa0");
                                $section->addText($val, [
                                    'name' => 'Times New Roman',
                                    'size' => 12,
                                ]);
                            }
                        }
                        break;
                    case('drag_drop_marker'):
                        $section->addImage(image_file($row->image_drag_drop), array('width' => 210, 'height' => 210, 'align' => 'center', 'ration' => true));
                        $answers = QuestionAnswer::query()->where('question_id', $questionId)->get(['title']);
                        foreach ($answers as $index => $answer) {
                            $val = str_repeat(' ', 5) . $arrawser[$index] . '. ' . trim(html_entity_decode(strip_tags($answer->title), ENT_QUOTES), "\xc2\xa0") . ' ' . trim(html_entity_decode(strip_tags($answer->matching_answer), ENT_QUOTES), "\xc2\xa0");
                            $section->addText($val, [
                                'name' => 'Times New Roman',
                                'size' => 12,
                            ]);
                        }
                        break;
                    case('fill_in'):
                    case('drag_drop_document'):
                    case('fill_in_correct'):
                        if ($row->type == 'drag_drop_document') {
                            $section->addText('Điền số vị trí vào sau câu trả lời phù hợp: ', [
                                'name' => 'Times New Roman',
                                'size' => 12,
                            ]);
                        }
                        $questions = QuestionAnswer::query()->where('question_id', $questionId)->get('title');
                        foreach ($questions as $index => $value) {
                            $question = str_repeat(' ', 5) . $arrawser[$index] . '. ' . ($row->type == 'drag_drop_document' ? trim(html_entity_decode(strip_tags($value->title), ENT_QUOTES), "\xc2\xa0") . '___' : str_replace("?", "___", trim(html_entity_decode(strip_tags($value->title), ENT_QUOTES), "\xc2\xa0")));
                            $section->addText($question, [
                                'name' => 'Times New Roman',
                                'size' => 12,
                            ]);
                        }
                        break;
                    default:
                        $answers = QuestionAnswer::query()->where('question_id', $questionId)->get(['title']);
                        foreach ($answers as $index => $answer) {
                            $val = str_repeat(' ', 5) . $arrawser[$index] . '. ' . trim(html_entity_decode(strip_tags($answer->title), ENT_QUOTES), "\xc2\xa0") . ' ' . trim(html_entity_decode(strip_tags($answer->matching_answer), ENT_QUOTES), "\xc2\xa0");
                            $section->addText($val, [
                                'name' => 'Times New Roman',
                                'size' => 12,
                            ]);
                        }
                }
            }
        }
        $section->addText('-- Hết --', [
            'name' => 'Times New Roman',
            'size' => 12,
        ], [
            'align' => Cell::VALIGN_CENTER
        ]);

        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $file_name = Str::slug($quiz->name);
        header("Content-Disposition: attachment; filename=" . $file_name . ".docx");
        ob_clean();
        $objWriter->save("php://output");
        exit();
        //QuizTemplate::deleteTemplate($template_id);
    }
}
