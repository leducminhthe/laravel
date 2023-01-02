<?php

namespace Modules\Quiz\Http\Controllers\Backend;

use App\Models\Permission;
use App\Models\Categories\Unit;
use App\Scopes\DraftScope;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Modules\Quiz\Entities\Question;
use Modules\Quiz\Entities\QuestionAnswer;
use Modules\Quiz\Entities\QuestionCategory;
use Modules\Quiz\Entities\QuestionCategoryUser;
use Modules\Quiz\Entities\QuizQuestion;
use Modules\Quiz\Exports\QuestionExport;
use Modules\Quiz\Imports\QuestionImport;
use Modules\Quiz\Imports\QuestionImportV2;
use App\Models\Profile;
use PhpOffice\PhpWord\Style\Cell;
use PhpOffice\PhpWord\Style\Font;

class QuestionlibController extends Controller
{
    public function index()
    {
        QuestionCategory::addGlobalScope(new DraftScope());
        $categories = QuestionCategory::getCategories();

        return view('quiz::backend.questionlib.index', [
            'categories' => $categories,
        ]);
    }

    public function question($category_id)
    {
        $errors = session()->get('errors');
        \Session::forget('errors');

        $category = QuestionCategory::findOrFail($category_id);

        QuestionCategory::addGlobalScope(new DraftScope());
        $categories = QuestionCategory::getCategories();

        return view('quiz::backend.questionlib.question', [
            'category' => $category,
            'categories' => $categories,
        ]);
    }

    public function cateUser($category_id)
    {
        $category = QuestionCategory::findOrFail($category_id);
        $units = Unit::select(['id', 'name', 'code'])->where('status', '=', 1)->get();

        return view('quiz::backend.questionlib.cate_user', [
            'category' => $category,
            'units' => $units,
        ]);
    }

    public function questionForm($category_id, $question_id = null)
    {
        $category = QuestionCategory::findOrFail($category_id);
        $model = Question::firstOrNew(['id' => $question_id]);
        $answers = QuestionAnswer::where('question_id', '=', $model->id)->orderBy('id')->get();

        $feedbacks = json_decode($model->feedback, true);

        $page_title = strip_tags(substr(trim(html_entity_decode($model->name, ENT_QUOTES, 'UTF-8'), "\xc2\xa0"), 0, 100));
        return view('quiz::backend.questionlib.question_form', [
            'category' => $category,
            'model' => $model,
            'answers' => $answers,
            'page_title' => if_empty($page_title, trans('labutton.add_new')),
            'feedbacks' => $feedbacks,
        ]);
    }

    public function showModal(Request $request)
    {
        $model = QuestionCategory::firstOrNew(['id' => $request->id]);
        QuestionCategory::addGlobalScope(new DraftScope());
        $categories = QuestionCategory::getCategories();

        return view('quiz::backend.modal.addqcat', [
            'model' => $model,
            'categories' => $categories
        ]);
    }

    public function getDataCategory(Request $request)
    {
        $search = $request->input('search');
        $parent_id = $request->input('parent_id');
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        QuestionCategory::addGlobalScope(new DraftScope());
        $query = QuestionCategory::query();
//        $query = \DB::query();
//        $query->from('el_question_category');
        if ($search) {
            $query->where(function ($subquery) use ($search) {
                $subquery->orWhere('name', 'like', '%' . $search . '%');
                $subquery->orWhereIn('id', function ($subquery2) use ($search) {
                    $subquery2->select(['category_id'])
                        ->from('el_question')
                        ->where('name', 'like', '%' . $search . '%');
                });
            });
        }
        if ($parent_id) {
            $query->where('id', '=', $parent_id);
        }
        if ($search || $parent_id) {
            $rootIds = $query->pluck('root_id')->toArray();
            $query = \DB::table('el_question_category')->whereIn('root_id', $rootIds)->orWhereIn('id', $rootIds);
        }

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row) {
            $parent = DB::table('el_question_category')->where(['id' => $row->parent_id])->first();
            $row->parent_name = $parent ? $parent->name : '';
            $row->cate_user_url = route('module.quiz.questionlib.cate_user', ['id' => $row->id]);
            $row->question_url = route('module.quiz.questionlib.question', ['id' => $row->id]);

            $num_question_approved = QuestionCategory::countQuestion($row->id);
            $num_question = Question::where('category_id', '=', $row->id)->count();

            $row->quantity = $num_question_approved . '/' . $num_question;

            $row->export_word = route('module.quiz.questionlib.export_word_question', ['id' => $row->id]);
            $row->export_excel = route('module.quiz.questionlib.export_excel_question', ['id' => $row->id]);

            $row->num_child = DB::table('el_question_category')->where('parent_id', '=', $row->id)->count();
        }
        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function saveCategory(Request $request)
    {
        $this->validateRequest([
            'id' => 'nullable|exists:el_question_category,id',
            'name' => 'required',
            'parent_id' => 'nullable|exists:el_question_category,id',
        ], $request, QuestionCategory::getAttributeName());

        $model = QuestionCategory::firstOrNew(['id' => $request->id]);
        $model->fill($request->all());
        if (empty($request->id)) {
            $model->created_by = profile()->user_id;
        }
        $model->updated_by = profile()->user_id;
        if ($request->parent_id) {
            $cate = QuestionCategory::findOrFail($request->parent_id);
            $level = (int)$cate->level + 1;
            $model->level = $level;
            $model->root_id = $this->getRootCategory($cate->id);
            QuestionCategory::where('id', $request->parent_id)->update(['has_child' => 1]);
        } else {
            $model->level = 0;
        }

        if ($model->save()) {
            if (!Permission::isAdmin()) {
                $user = profile();
                $unit = Unit::where('code', '=', $user->unit_code)->first();

                $query = new QuestionCategoryUser();
                $query->category_id = $model->id;
                $query->unit_id = $unit->id;
                $query->save();
            }

            json_message(trans('laother.successful_save'));
        }

        json_message('Không thể lưu dữ liệu', 'error');
    }

    private function getRootCategory($id)
    {
        $cate = DB::table('el_question_category')->where(['id' => $id])->select('id', 'parent_id')->first();
        if ($cate->parent_id)
            return $this->getRootCategory($cate->parent_id);
        return $cate->id;
    }

    public function removeCategory(Request $request)
    {
        $this->validateRequest([
            'ids' => 'required'
        ], $request);
        $errors = [];
        $ids = $request->ids;
        foreach ($ids as $id) {
            $questionCategory = QuestionCategory::findOrFail($id);
            if (QuizQuestion::query()->where('qcategory_id', $id)->exists()) {
                $errors[] = 'Danh mục câu hỏi [' . $questionCategory->name . '] không được phép xóa. Do có câu hỏi được sử dụng';
            } elseif ($questionCategory->has_child)
                $errors[] = 'Danh mục câu hỏi [' . $questionCategory->name . '] không được phép xóa. Vui lòng xóa danh mục con trước khi xóa danh mục cha';
            else {
                Question::where('category_id', $id)->delete();
                QuestionCategory::where('id', $id)->delete();
            }
        }
        session()->put('errors', $errors);
        session()->save();

        json_result([
            'status' => 'success',
            'message' => trans('laother.delete_success'),
            'redirect' => route('module.quiz.questionlib')
        ]);
    }

    public function saveStatusCategory(Request $request)
    {
        $this->validateRequest([
            'ids' => 'required',
            'status' => 'required|in:0,1'
        ], $request, [
            'ids' => trans('laprofile.rank'),
        ]);

        $ids = $request->input('ids', null);
        $status = $request->input('status', 0);
        if (is_array($ids)) {
            foreach ($ids as $id) {
                $model = QuestionCategory::findOrFail($id);
                $model->status = $status;
                $model->save();
            }
        } else {
            $model = QuestionCategory::findOrFail($ids);
            $model->status = $status;
            $model->save();
        }

        json_result([
            'status' => 'success',
            'message' => trans('laother.successful_save')
        ]);
    }

    public function getDataQuestion($category_id, Request $request)
    {
        $search = $request->input('search');
        $type = $request->input('type');
        $difficulty = $request->input('difficulty');

        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        Question::addGlobalScope(new DraftScope());
        $query = Question::query();
        $query->where('category_id', '=', $category_id);

        if ($search) {
            $query->where(function($sub) use ($search) {
                $sub->orWhere('name', 'like', '%' . $search . '%');
                $sub->orWhere('code', 'like', '%' . $search . '%');
            });

        }

        if ($type) {
            $query->where('type', '=', $type);
        }

        if ($difficulty) {
            $query->where('difficulty', '=', $difficulty);
        }

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();

        foreach ($rows as $row) {
            $row->edit_url = route('module.quiz.questionlib.question.edit', ['id' => $category_id, 'qid' => $row->id]);
            $row->view_question = route('module.quiz.questionlib.view_question', ['id' => $category_id, 'qid' => $row->id]);

            $row->answers = QuestionAnswer::whereQuestionId($row->id)->get();
            foreach ($row->answers as $key => $answer) {
                $row->answers[$key]['image_answer'] = $answer->image_answer ? image_file($answer->image_answer) : "";
            }

            $row->created_by = Profile::fullname($row->updated_by);
            $row->created_time = 'tạo lúc ' . get_date($row->created_at, 'h:i d/m/Y');

            $row->approved_by = $row->approved_by ? Profile::fullname($row->approved_by) : 'Chưa duyệt';
            $row->time_approved = $row->time_approved ? ('duyệt lúc ' . get_date($row->time_approved, 'h:i d/m/Y')) : '';

            switch ($row->type) {
                case 'multiple-choise':
                    $row->text_type = trans('lasurvey.choice') . ($row->multiple == 1 ? '(Chọn nhiều)' : '(Chọn một)');
                    break;
                case 'essay':
                    $row->text_type = trans("lasurvey.essay");
                    break;
                case 'matching':
                    $row->text_type = trans("latraining.sentence_matching");
                    break;
                case 'fill_in':
                    $row->text_type = trans("latraining.fill_blank");
                    break;
                case 'fill_in_correct':
                    $row->text_type = trans('latraining.fill_correct_answer');
                    break;
                case 'select_word_correct':
                    $row->text_type = trans('latraining.choose_missing_word');
                    break;
                case 'drag_drop_marker':
                    $row->text_type = trans('latraining.drag_marker');
                    break;
                case 'drag_drop_image':
                    $row->text_type = trans('latraining.drag_image');
                    break;
                case 'drag_drop_document':
                    $row->text_type = trans('latraining.drag_text');
                    break;
                default:
                    $row->text_type = '';
                    break;
            }

            switch ($row->difficulty) {
                case 'D':
                    $row->difficulty = 'Dễ';
                    break;
                case 'TB':
                    $row->difficulty = 'Trung bình';
                    break;
                case 'K':
                    $row->difficulty = 'Khó';
                    break;
                default:
                    $row->difficulty = '';
                    break;
            }
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function saveQuestion($category_id, Request $request)
    {
        $this->validateRequest([
            'code' => 'required|unique:el_question,code,' . $request->id,
            'name' => 'required',
            'type' => 'required_if:id,',
        ], $request, [
            'code' => 'Mã câu hỏi',
            'name' => 'Tên câu hỏi',
            'type' => 'Loại',
        ]);

        $type = $request->type;
        $answer = $request->answer;
        $correct_answer = $request->correct_answer;
        $ans_id = $request->ans_id;
        $feedbacks = $request->feedback;
        $feedback_answer = $request->feedback_answer;
        $matching_answer = $request->matching_answer;
        $percent_answer = $request->percent_answer;
        $image_answer = $request->image_answer;
        $fill_in_correct_answer = $request->fill_in_correct_answer;
        $select_word_correct = $request->select_word_correct;
        $image_drag_drop = $request->image_drag_drop;
        $marker_answers = $request->marker_answer;
        $countAnswerOfSelectWordCorrect = substr_count($request->name, ']]');

        // lấy key đáp án theo từng nhóm
        foreach ($select_word_correct as $key => $value) {
            if (array_key_exists($value, $keysAnswerOfSelectWordCorrect)) {
                $keysAnswerOfSelectWordCorrect[$value][] = $key;
            } else {
                $keysAnswerOfSelectWordCorrect[$value][] = $key;
            }
        }
        if (!$type) {
            json_message('Vui lòng chọn loại câu hỏi.', 'error');
        }
        if ($type != 'essay' && empty($answer)) {
            json_message('Vui lòng nhập câu trả lời và đáp án.', 'error');
        }
        foreach ($answer as $ans) {
            if ($ans == null) {
                json_message('Vui lòng nhập nội dung câu trả lời.', 'error');
            }
        }

        switch ($type) {
            case ('multiple-choise'):
                if ($percent_answer && $request->multiple == 1) {
                    $total = 0;
                    foreach ($percent_answer as $item) {
                        $total += $item;
                    }
                    if ($total > 100) {
                        json_message('Tổng % đáp án không thể vượt quá 100%.', 'error');
                    }
                    if ($total < 100) {
                        json_message('Tổng % đáp án không đủ 100%.', 'error');
                    }
                }
                if ($request->multiple == 0) {
                    if (array_count_values($correct_answer)[1] > 1) {
                        json_message('Câu hỏi chọn 1 không thể có nhiều đáp án đúng.', 'error');
                    } else if (!in_array(1, $correct_answer)) {
                        json_message('Chưa chọn đáp án đúng cho câu hỏi.', 'error');
                    }
                }
                if(count($answer)<=1){
                    json_message('Vui lòng nhập nhiều hơn 1 câu trả lời.', 'error');
                }
                break;
            case('drag_drop_document'):
                $questionName = $request->name;
                for ($i = 0; $i < strlen($questionName); $i++) {
                    if ($questionName[$i] . $questionName[$i + 1] === '[[') {
                        $indexOfGroup[] = $questionName[$i + 2];
                    }
                }

                if (array_diff($indexOfGroup, $select_word_correct) != null) {
                    json_message('Số vị trí ở câu hỏi và câu trả lời không trùng khớp.', 'error');
                }

                if ($countAnswerOfSelectWordCorrect == 0) {
                    json_message('Vui lòng đánh dấu nhóm đáp án trong câu hỏi. Đánh dấu nhóm đáp án có dạng [[vị trí]]. Ví dụ: [[1]]', 'error');
                }
                if ($countAnswerOfSelectWordCorrect != count($keysAnswerOfSelectWordCorrect)) {
                    json_message('Số lượng nhóm đáp án ở câu hỏi và câu trả lời trùng khớp.', 'error');
                }
                foreach ($keysAnswerOfSelectWordCorrect as $keyOfKeysGroup => $keysGroup) {
                    if (count($keysGroup) > 1) {
                        json_message('Nhóm đáp án ' . $keyOfKeysGroup . ' bị trùng. Vui lòng kiển tra lại', 'error');
                    }
                }
                break;
            case('select_word_correct'):
                $questionName = $request->name;
                for ($i = 0; $i < strlen($questionName); $i++) {
                    if ($questionName[$i] . $questionName[$i + 1] === '[[') {
                        $indexOfGroup[] = $questionName[$i + 2];
                    }
                }

                if(count($indexOfGroup)<=1){
                    json_message('Vui lòng nhập nhiều hơn 1 vị trí cần chọn từ.', 'error');
                }

                if (array_diff($indexOfGroup, $select_word_correct) != null) {
                    json_message('Số vị trí ở câu hỏi và câu trả lời không trùng khớp.', 'error');
                }

                // câu hỏi không có vị trí nhóm đáp án
                if ($countAnswerOfSelectWordCorrect == 0) {
                    json_message('Vui lòng đánh dấu nhóm đáp án trong câu hỏi. Đánh dấu nhóm đáp án có dạng [[vị trí]]. Ví dụ: [[1]]', 'error');
                }
                if ($countAnswerOfSelectWordCorrect != count($keysAnswerOfSelectWordCorrect)) {
                    json_message('Số lượng nhóm đáp án ở câu hỏi và câu trả lời trùng khớp.', 'error');
                }

                foreach ($keysAnswerOfSelectWordCorrect as $keyOfKeysGroup => $keysGroup) {
                    foreach ($keysGroup as $keyGroup) {
                        foreach ($correct_answer as $key => $value) {
                            if ($key == $keyGroup) {
                                $arrayAnswer[] = $value;
                            }
                        }
                    }
                    $countAnswer = array_count_values($arrayAnswer);
                    if ($countAnswer[1] == 0) {
                        json_message('Chưa chọn đáp án đúng cho nhóm ' . $keyOfKeysGroup, 'error');
                    }
                    if ($countAnswer[1] > 1) {
                        json_message('Chỉ được chọn 1 đáp án đúng cho nhóm ' . $keyOfKeysGroup, 'error');
                    }
                    unset($arrayAnswer);
                }
                break;
            case('fill_in_correct'):
                if(count($answer)<=1){
                    json_message('Vui lòng nhập nhiều hơn 1 câu trả lời.', 'error');
                }
                if (count(array_filter($answer)) != count(array_filter($fill_in_correct_answer, function ($val) {
                        return ($val || is_numeric($val));
                    }))) {
                    json_message('Đáp án điền từ chính xác chưa đủ.', 'error');
                }
                break;
            case ('matching'):
                if(count($answer)<=1){
                    json_message('Vui lòng nhập nhiều hơn 1 câu trả lời.', 'error');
                }
                if (count(array_filter($answer)) != count(array_filter($matching_answer))) {
                    json_message('Đáp án nối câu chưa đủ.', 'error');
                }
                break;
            case ('drag_drop_marker'):
            case ('drag_drop_image'):
                foreach ($marker_answers as $marker_answer) {
                    if ($marker_answer == null) {
                        json_message('Vui lòng nhập toạ độ X và Y.', 'error');
                    }
                }
                if (!$image_drag_drop) {
                    json_message('Câu kéo thả phải chọn hình nền.', 'error');
                }
                break;
        }

        $arr = [];
        if ($feedbacks) {
            foreach ($feedbacks as $feedback) {
                $arr[] = $feedback;
            }
        }

        $model = Question::firstOrNew(['id' => $request->id]);
        $model->fill($request->all());
        $model->name = html_entity_decode($request->name);
        $model->category_id = $category_id;
        $model->feedback = json_encode($arr);
        $model->image_drag_drop = $image_drag_drop ? path_upload($image_drag_drop) : null;
        $model->status = 2;
        $model->save();

        if (!empty($answer)) {
            if (in_array($type, ['drag_drop_document'])) {
                if (preg_match_all('/\[\[(.*?)\]\]/is', $request->name, $matches))
                    $correct_answer = $matches[1];
            }
            $i = 1;
            foreach ($answer as $ans_key => $ans) {
                $answers = QuestionAnswer::firstOrNew(['id' => $ans_id[$ans_key]]);
                $answers->question_id = $model->id;

                if (isset($ans)) {
                    if (in_array($type, ['drag_drop_document'])) {
                        if (in_array($i, $correct_answer)) {
                            $answers->correct_answer = $i;
                        } else {
                            $answers->correct_answer = 0;
                        }
                    } else {
                        $answers->correct_answer = $correct_answer[$ans_key] ? $correct_answer[$ans_key] : 0;
                    }
                    $answers->image_answer = $image_answer[$ans_key] ? path_upload($image_answer[$ans_key]) : null;
                    $answers->title = html_entity_decode($ans);
                    $answers->feedback_answer = $feedback_answer[$ans_key];
                    $answers->matching_answer = $matching_answer[$ans_key];
                    $answers->fill_in_correct_answer = $fill_in_correct_answer[$ans_key];
                    $answers->percent_answer = $percent_answer[$ans_key] ? $percent_answer[$ans_key] : 0;
                    $answers->select_word_correct = $select_word_correct[$ans_key];
                    $answers->marker_answer = $marker_answers[$ans_key];
                    $answers->save();
                }
                $i++;
            }
        }

        json_result([
            'status' => 'success',
            'message' => trans('laother.successful_save'),
            'redirect' => route('module.quiz.questionlib.question', ['id' => $category_id]),
        ]);
    }

    public function removeQuestion(Request $request)
    {
        $this->validateRequest([
            'ids' => 'required'
        ], $request);

        $ids = $request->ids;
        foreach ($ids as $id) {
            if (QuizQuestion::query()->where('question_id', $id)->exists()) {
                continue;
            } else {
                Question::where('id', $id)->delete();
                QuestionAnswer::where(['question_id' => $id])->delete();
            }
        }

        json_result([
            'status' => 'success',
            'message' => trans('laother.delete_success'),
        ]);
    }

    public function removeQuestionAnswer(Request $request)
    {
        $this->validateRequest([
            'ans_id' => 'required'
        ], $request);

        $ans_id = $request->ans_id;

        QuestionAnswer::where('id', '=', $ans_id)->delete();

        json_result([
            'status' => 'success',
            'message' => trans('laother.delete_success'),
        ]);
    }

    public function saveStatus(Request $request)
    {
        $this->validateRequest([
            'ids' => 'required',
            'status' => 'required|in:0,1'
        ], $request, [
            'ids' => trans('lamenu.quiz'),
        ]);

        $ids = $request->input('ids', null);
        $status = $request->input('status', 0);
        foreach ($ids as $id) {
            $model = Question::findOrFail($id);
            $model->status = $status;
            $model->approved_by = profile()->user_id;
            $model->time_approved = date('Y-m-d h:i:s');
            $model->save();
        }

        json_result([
            'status' => 'success',
            'message' => trans('laother.successful_save')
        ]);
    }

    public function copyQuestion($cate_id, Request $request)
    {
        $this->validateRequest([
            'ids' => 'required',
        ], $request, [
            'ids' => 'Câu hỏi',
        ]);
        $category_id = $request->input('category_id', null);

        $ids = $request->input('ids', null);
        foreach ($ids as $id) {
            $model = Question::find($id);
            $newModel = $model->replicate();
            $newModel->category_id = $category_id ?? $cate_id;
            $newModel->status = 2;
            $newModel->approved_by = null;
            $newModel->time_approved = null;
            $newModel->save();

            $answers = QuestionAnswer::whereQuestionId($id)->get();
            foreach ($answers as $answer) {
                $newQuestionAnswer = $answer->replicate();
                $newQuestionAnswer->question_id = $newModel->id;
                $newQuestionAnswer->save();
            }
        }

        if ($category_id) {
            json_result([
                'status' => 'success',
                'message' => trans('laother.successful_save'),
                'redirect' => route('module.quiz.questionlib.question', [$category_id]),
            ]);
        }

        json_result([
            'status' => 'success',
            'message' => trans('laother.successful_save')
        ]);
    }

    public function saveCateUser($category_id, Request $request)
    {
        $this->validateRequest([
            'unit_id' => 'nullable|exists:el_unit,id',
        ], $request, [
            'unit_id' => trans('lamenu.unit'),
        ]);

        $unit_id = $request->unit_id;
        if ($unit_id) {
            foreach ($unit_id as $item) {
                if (QuestionCategoryUser::checkExists($category_id, $item)) {
                    continue;
                }
                $model = new QuestionCategoryUser();
                $model->category_id = $category_id;
                $model->unit_id = $item;
                $model->save();
            }

            json_result([
                'status' => 'success',
                'message' => trans('laother.successful_save')
            ]);
        }
    }

    public function getCateUser($category_id, Request $request)
    {
        $search = $request->input('search');
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = QuestionCategoryUser::query();
        $query->select([
            'a.*',
            'b.code AS unit_code',
            'b.name AS unit_name'
        ]);
        $query->from('el_question_category_user AS a');
        $query->join('el_unit AS b', 'b.id', '=', 'a.unit_id');
        $query->where('a.category_id', '=', $category_id);

        if ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function removeCateUser($category_id, Request $request)
    {
        $ids = $request->input('ids', null);
        QuestionCategoryUser::destroy($ids);
        json_result([
            'status' => 'success',
            'message' => trans('laother.delete_success'),
        ]);
    }

    public function importQuestion($category_id, Request $request)
    {
        $this->validateRequest([
            'import_file' => 'required|file'
        ], $request, ['import_file' => 'File import']);

        $import = new QuestionImportV2($category_id);
        \Excel::import($import, $request->file('import_file'));

        //Cập nhật % câu trả lời theo import cũ
        // if($import->arr_question_multiple){
        //     foreach($import->arr_question_multiple as $question_id => $percent){
        //         QuestionAnswer::whereQuestionId($question_id)
        //             ->where('percent_answer', 1)
        //             ->update([
        //                 'percent_answer' => $percent,
        //             ]);
        //     }
        // }

        if ($import->errors) {
            session()->put('errors', $import->errors);
            session()->save();
        }
        json_result([
            'status' => 'success',
            'message' => 'Import ' . trans('laapi.success'),
            'redirect' => route('module.quiz.questionlib.question', ['id' => $category_id]),
        ]);
    }

    public function exportWordQuestion($category_id)
    {
        \PhpOffice\PhpWord\Settings::setOutputEscapingEnabled(true);
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $section = $phpWord->addSection();
        $section->addText(Str::upper('Danh sách câu hỏi'), [
            'name' => 'Times New Roman',
            'size' => 12,
            'bold' => true,
        ], [
            'align' => Cell::VALIGN_CENTER
        ]);

        $arrawser = range('a', 'z');

        $query = Question::query()
            ->where('category_id', '=', $category_id);
        $rows = $query->get([
            'id',
            'name',
            'type',
            'status'
        ]);

        foreach ($rows as $qindex => $row) {
            $status = ($row->status == '1') ? '(Đã duyệt)' : '(Chưa duyệt)';

            $text = trim(htmlspecialchars_decode(strip_tags($row->name)), "\xc2\xa0");
            $textlines = explode("\n", $text);

            for ($i = 0; $i < sizeof($textlines); $i++) {
                $text = str_replace("\r", "", $textlines[$i]);
                if ($text != '') {
                    $section->addText($i == 0 ? (($qindex + 1) . '. ' . $text . ' ' . $status) : ($text . ' ' . $status), [
                        'name' => 'Times New Roman',
                        'size' => 12,
                    ]);
                }

            }

            if ($row->type == 'essay') {
                $section->addText(str_repeat('-', 675));
            } else {
                $answers = QuestionAnswer::query()->where('question_id', '=', $row->id)->get(['title', 'matching_answer', 'correct_answer', 'percent_answer']);
                foreach ($answers as $index => $answer) {
                    $val = str_repeat(' ', 5) . $arrawser[$index] . '. ' . trim(html_entity_decode(strip_tags($answer->title), ENT_QUOTES), "\xc2\xa0") . ' ' . html_entity_decode($answer->matching_answer, ENT_QUOTES);

                    if ($answer->correct_answer == 1 || $answer->percent_answer > 0) {
                        $section->addText($val, [
                            'name' => 'Times New Roman',
                            'size' => 12,
                            'underline' => Font::UNDERLINE_SINGLE,
                        ]);
                    } else {
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
        $file_name = Str::slug('danhsachcauhoi');
        header("Content-Disposition: attachment; filename=" . $file_name . ".docx");
        ob_clean();
        $objWriter->save("php://output");
        exit();
    }

    public function exportExcelQuestion($category_id)
    {
        return (new QuestionExport($category_id))->download('danh_sach_cau_hoi.xlsx');
    }

    public function viewQuestion($category_id, $question_id)
    {
        $category = QuestionCategory::findOrFail($category_id);
        $question = Question::findOrFail($question_id);

        $answers = QuestionAnswer::where('question_id', '=', $question_id);
        if ($question->type == 'drag_drop_document') {
            $answers = $answers->orderBy('select_word_correct');
        }
        $answers = $answers->get();

        return view('quiz::backend.questionlib.view_question', [
            'category' => $category,
            'question' => $question,
            'answers' => $answers
        ]);
    }


}
