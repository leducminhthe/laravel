<?php

namespace Modules\Survey\Http\Controllers;

use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Online\Entities\OnlineCourseActivitySurvey;
use Modules\Survey\Entities\Survey;
use Modules\Survey\Entities\SurveyAnswerMatrix;
use Modules\Survey\Entities\SurveyQuestionCategory;
use Modules\Survey\Entities\SurveyQuestion;
use Modules\Survey\Entities\SurveyQuestionAnswer;
use Modules\Survey\Entities\SurveyTemplate;
use Modules\Survey\Entities\SurveyUser;
use Modules\Survey\Entities\SurveyAnswerMatrix2;
use Modules\Survey\Entities\SurveyQuestion2;
use Modules\Survey\Entities\SurveyQuestionAnswer2;
use Modules\Survey\Entities\SurveyQuestionCategory2;
use Modules\Survey\Entities\SurveyTemplate2;

class TemplateController extends Controller
{
    public function index() {
        return view('survey::backend.template.index',[
        ]);
    }

    public function getData(Request $request) {
        $search = $request->input('search');
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = SurveyTemplate::query();
        $query->whereNull('type');

        if ($search) {
            $query->where('name', 'like', '%'. $search .'%');
        }

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row) {
            $row->edit_url = route('module.survey.template.edit', ['id' => $row->id]);
            $row->created_by = Profile::fullname($row->created_by) .' ('. Profile::usercode($row->created_by) .')';
            $row->updated_by = Profile::fullname($row->updated_by) .' ('. Profile::usercode($row->updated_by) .')';

            $row->review = route('module.survey.template.review', [$row->id]);

            $row->course = ($row->course == 1 ? trans('latraining.course') : trans('lamenu.survey'));
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function form($survey_id, $id = 0) {
        $survey = Survey::find($survey_id, ['id', 'name']);
        if ($id) {
            $model = SurveyTemplate::find($id);
            $page_title = $model->name;
            $categories = SurveyQuestionCategory::where('template_id', '=', $model->id)->get();

            $fquestions = function($cate_id){
                return SurveyQuestion::whereCategoryId($cate_id)->orderBy('num_order')->get();
            };

            return view('survey::backend.template.form', [
                'model' => $model,
                'page_title' => $page_title,
                'categories' => $categories,
                'fquestions' => $fquestions,
                'survey' => $survey,
            ]);
        }

        $model = new SurveyTemplate();
        $page_title = trans('lasurvey.add_new') ;

        return view('survey::backend.template.form', [
            'model' => $model,
            'page_title' => $page_title,
            'survey' => $survey,
        ]);
    }

    public function save(Request $request) {
        $this->validateRequest([
            'category_name' => 'required',
            'question_name' => 'required',
            'type' => 'required',
        ], $request, [
            'category_name' => trans('lamenu.category'),
            'question_name' => trans('latraining.question'),
            'type' => trans('lasurvey.question_type')
        ]);

        $survey = Survey::find($request->survey_id, ['id', 'name', 'type']);

        $category_id = $request->category_id;
        $category_name = $request->category_name;

        $question_id = $request->question_id;
        $question_code = $request->question_code;
        $question_name = $request->question_name;
        $obligatory = $request->obligatory;
        $num_order = $request->num_order;

        $answer_id = $request->answer_id;
        $answer_code = $request->answer_code;
        $answer_name = $request->answer_name;

        $is_text = $request->is_text;
        $is_row = $request->is_row;
        $type = $request->type;
        $multiple = $request->multiple;
        $answer_icon = $request->answer_icon;

        $answer_matrix_code = $request->answer_matrix_code;

        $model = SurveyTemplate::firstOrNew(['id' => $request->id]);
        $model->name = $survey->name;
        if($survey->type == 2) {
            $model->course = 1;
        }
        $model->save();

        foreach($category_name as $cate_key => $cate_name) {
            $cate_id = $category_id[$cate_key];

            $category = SurveyQuestionCategory::firstOrNew(['id' => $cate_id]);
            $category->template_id = $model->id;
            $category->name = trim($cate_name);
            $category->save();

            foreach ($question_name[$cate_key] as $ques_key => $ques_name) {
                $ques_id = $question_id[$cate_key][$ques_key];
                $ques_code = isset($question_code[$cate_key][$ques_key]) ? $question_code[$cate_key][$ques_key] : null;
                $ques_type = $type[$cate_key][$ques_key];
                $ques_multiple = isset($multiple[$cate_key][$ques_key]) ? $multiple[$cate_key][$ques_key] : 0;
                $ques_obligatory = $obligatory[$cate_key][$ques_key] == 'on' ? 1 : 0;
                $ques_num_order = $num_order[$cate_key][$ques_key];

                $question = SurveyQuestion::firstOrNew(['id' => $ques_id]);

                $question->category_id = $category->id;
                $question->code = $ques_code;
                $question->name = $ques_name;
                $question->type = $ques_type;
                $question->multiple = $ques_multiple;
                $question->obligatory = $ques_obligatory;
                $question->num_order = $ques_num_order;
                $question->save();

                if(isset($answer_name[$cate_key][$ques_key])){
                    foreach($answer_name[$cate_key][$ques_key] as $ans_key => $ans_name){
                        $ans_id = $answer_id[$cate_key][$ques_key][$ans_key];
                        $ans_code = isset($answer_code[$cate_key][$ques_key][$ans_key]) ? $answer_code[$cate_key][$ques_key][$ans_key] : null;
                        $ans_is_text = isset($is_text[$cate_key][$ques_key][$ans_key]) ? $is_text[$cate_key][$ques_key][$ans_key] : 0;
                        $ans_is_row = isset($is_row[$cate_key][$ques_key][$ans_key]) ? $is_row[$cate_key][$ques_key][$ans_key] : 0;
                        $ans_icon = isset($answer_icon[$cate_key][$ques_key][$ans_key]) ? $answer_icon[$cate_key][$ques_key][$ans_key] : null;

                        $answer = SurveyQuestionAnswer::firstOrNew(['id' => $ans_id]);
                        $answer->question_id = $question->id;
                        $answer->code = $ans_code;
                        $answer->name = $ans_name;
                        $answer->is_text = in_array($question->type, ['matrix_text', 'text']) ? 1 : $ans_is_text;
                        $answer->is_row = $ans_is_row;
                        $answer->icon = $ans_icon;
                        $answer->save();
                    }
                }

                if (($question->type == 'matrix' && $question->multiple == 1) || $question->type == 'matrix_text'){
                    $rows = SurveyQuestionAnswer::whereQuestionId($question->id)->where('is_row', '=', 1)->pluck('id')->toArray();
                    $cols = SurveyQuestionAnswer::whereQuestionId($question->id)->where('is_row', '=', 0)->pluck('id')->toArray();

                    if(isset($answer_matrix_code[$cate_key][$ques_key])) {
                        foreach ($answer_matrix_code[$cate_key][$ques_key] as $ans_key => $answer_matrix) {
                            foreach ($answer_matrix as $matrix_key => $matrix_code){
                                SurveyAnswerMatrix::query()
                                    ->updateOrCreate([
                                        'question_id' => $question->id,
                                        'answer_row_id' => $rows[$ans_key],
                                        'answer_col_id' => $cols[$matrix_key]
                                    ],[
                                        'question_id' => $question->id,
                                        'answer_row_id' => $rows[$ans_key],
                                        'answer_col_id' => $cols[$matrix_key],
                                        'code' => $matrix_code
                                    ]);
                            }
                        }
                    }
                }
            }
        }
        
        SurveyTemplate2::whereSurveyId($survey->id)->delete();
        SurveyQuestionCategory2::whereSurveyId($survey->id)->delete();
        SurveyQuestion2::whereSurveyId($survey->id)->delete();
        SurveyQuestionAnswer2::whereSurveyId($survey->id)->delete();
        SurveyAnswerMatrix2::whereSurveyId($survey->id)->delete();

        $new_template = new SurveyTemplate2();
        $new_template->id = $model->id;
        $new_template->survey_id = $survey->id;
        $new_template->name = $survey->name;
        $new_template->save();

        $categories = SurveyQuestionCategory::query()->where('template_id', $model->id)->get()->toArray();
        foreach ($categories as $category){
            $new_category = new SurveyQuestionCategory2();
            $new_category->fill($category);
            $new_category->id = $category['id'];
            $new_category->survey_id = $survey->id;
            $new_category->save();

            $questions = SurveyQuestion::query()->where('category_id', $category['id'])->get()->toArray();
            foreach ($questions as $question){
                $new_question = new SurveyQuestion2();
                $new_question->fill($question);
                $new_question->id = $question['id'];
                $new_question->survey_id = $survey->id;
                $new_question->save();

                $answers = SurveyQuestionAnswer::query()->where('question_id', $question['id'])->get()->toArray();
                foreach ($answers as $answer){
                    $new_answer = new SurveyQuestionAnswer2();
                    $new_answer->fill($answer);
                    $new_answer->id = $answer['id'];
                    $new_answer->survey_id = $survey->id;
                    $new_answer->save();
                }

                $answers_matrix = SurveyAnswerMatrix::query()->where('question_id', $question['id'])->get()->toArray();
                foreach ($answers_matrix as $answer_matrix){
                    $new_answer_matrix = new SurveyAnswerMatrix2();
                    $new_answer_matrix->fill($answer_matrix);
                    $new_answer_matrix->survey_id = $survey->id;
                    $new_answer_matrix->save();
                }
            }
        }

        $survey->template_id = $model->id;
        $survey->save();

        json_result([
            'status' => 'success',
            'message' => trans('laother.successful_save'),
            'redirect' => route('module.survey.edit', ['id' => $survey->id])
        ]);
    }

    public function remove(Request $request) {
        $ids = $request->input('ids', null);
        foreach($ids as $id){
            $template = SurveyTemplate::find($id);

            $check_survey = Survey::where('template_id', $id)->exists();
            if($check_survey){
                json_message('Mẫu "' . $template->name . '" không thể xóa vì khảo sát đang sử dụng', 'error');
            }

            $check_online_survey = OnlineCourseActivitySurvey::where('survey_template_id', $id)->exists();
            if($check_online_survey){
                json_message('Mẫu "' . $template->name . '" không thể xóa vì Khoá học đang sử dụng', 'error');
            }

            $survey = SurveyUser::where('template_id', '=', $id)->get();
            foreach($survey as $item){
                if($item->send == 1){
                    json_message('Mẫu "' . $template->name . '" không thể xóa', 'error');
                }
            }

            $del_categories = SurveyQuestionCategory::getCategoryTemplate($id);
            if (!empty($del_categories)) {
                foreach($del_categories as $cate_id){
                    SurveyQuestionAnswer::whereIn('question_id', function ($sub) use ($cate_id){
                        $sub->select(['id'])
                            ->from('el_survey_template_question')
                            ->where('category_id', '=', $cate_id->id)
                            ->pluck('id')->toArray();
                    })->delete();
                    SurveyAnswerMatrix::whereIn('question_id', function ($sub) use ($cate_id){
                        $sub->select(['id'])
                            ->from('el_survey_template_question')
                            ->where('category_id', '=', $cate_id->id)
                            ->pluck('id')->toArray();
                    })->delete();
                    SurveyQuestion::whereCategoryId($cate_id->id)->delete();
                    SurveyQuestionCategory::whereId($cate_id->id)->delete();
                }
            }

            $template->delete();
        }

        json_result([
            'status' => 'success',
            'message' => trans('laother.delete_success'),
        ]);
    }

    public function removeCategory(Request $request) {
        $cate_id = $request->input('cate_id', null);

        SurveyQuestionAnswer::whereIn('question_id', function ($sub) use ($cate_id){
            $sub->select(['id'])
                ->from('el_survey_template_question')
                ->where('category_id', '=', $cate_id)
                ->pluck('id')->toArray();
        })->delete();
        SurveyAnswerMatrix::whereIn('question_id', function ($sub) use ($cate_id){
            $sub->select(['id'])
                ->from('el_survey_template_question')
                ->where('category_id', '=', $cate_id)
                ->pluck('id')->toArray();
        })->delete();
        SurveyQuestion::whereCategoryId($cate_id)->delete();
        SurveyQuestionCategory::whereId($cate_id)->delete();

        json_result([
            'status' => 'success',
            'message' => trans('laother.delete_success'),
        ]);
    }

    public function removeQuestion(Request $request) {
        $ques_id = $request->input('ques_id', null);

        SurveyQuestionAnswer::whereQuestionId($ques_id)->delete();
        SurveyAnswerMatrix::where('question_id', '=', $ques_id)->delete();
        SurveyQuestion::whereId($ques_id)->delete();

        json_result([
            'status' => 'success',
            'message' => trans('laother.delete_success'),
        ]);
    }

    public function removeAnswer(Request $request) {
        $ans_id = $request->input('ans_id', null);

        $answer = SurveyQuestionAnswer::whereId($ans_id)->first();

        if ($answer->is_row == 1){
            SurveyAnswerMatrix::query()
                ->where('question_id', '=', $answer->question_id)
                ->where('answer_row_id', '=', $answer->id)
                ->delete();
        }else{
            SurveyAnswerMatrix::query()
                ->where('question_id', '=', $answer->question_id)
                ->where('answer_col_id', '=', $answer->id)
                ->delete();
        }

        $answer->delete();

        json_result([
            'status' => 'success',
            'message' => trans('laother.delete_success'),
        ]);
    }

    public function reviewTemplate($id){
        $template = SurveyTemplate::find($id);

        $type = 1;
        return view('survey::modal.review_template', [
            'template' => $template,
            'type' => $type,
        ]);
    }

    public function modalViewQuestion(Request $request){
        $ques_type = $request->ques_type;
        $multi = $request->multi;

        return view('survey::modal.view_question', [
            'ques_type' => $ques_type,
            'multi' => $multi,
        ]);
    }

    // SAO CHÉP MẪU KHẢO SÁT
    public function copyTemplate(Request $request)
    {
        $ids = $request->input('ids', null);
        foreach ($ids as $id) {
            $template = SurveyTemplate::find($id);
            $newTemplate = $template->replicate();
            $newTemplate->name = $newTemplate->name . '_copy'. rand(2, 50);
            $newTemplate->save();

            $categories = SurveyQuestionCategory::where('template_id', '=', $id)->get();
            foreach ($categories as $category){
                $newCategory = $category->replicate();
                $newCategory->template_id = $newTemplate->id;
                $newCategory->save();

                $questions = SurveyQuestion::where('category_id', '=', $category->id)->get();
                foreach ($questions as $question){
                    $newQuestion = $question->replicate();
                    $newQuestion->category_id = $newCategory->id;
                    $newQuestion->save();

                    $answers = SurveyQuestionAnswer::where('question_id', '=', $question->id)->get();
                    foreach ($answers as $answer){
                        $newAnswer = $answer->replicate();
                        $newAnswer->question_id = $newQuestion->id;
                        $newAnswer->save();
                    }
                }
            }
        }
    }

    public function updateNumOrder($template, Request $request){
        $this->validateRequest([
            'question_id' => 'required',
        ], $request);

        $category_id = $request->category_id;
        $question_id = $request->question_id;

        foreach($category_id as $cate_key => $cate){
            $index = 0;
            foreach ($question_id[$cate_key] as $ques_id) {
                if (is_numeric($ques_id)) {
                    SurveyQuestion::where('id', '=', $ques_id)->update([
                        'num_order' => ($index + 1),
                    ]);
                    $index ++;
                }
            }
        }

        json_message('ok');
    }
}
