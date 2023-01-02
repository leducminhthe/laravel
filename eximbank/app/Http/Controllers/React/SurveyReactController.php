<?php

namespace App\Http\Controllers\React;

use App\Models\Permission;
use App\Models\ProfileView;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Categories\Unit;
use Illuminate\Support\Str;
use App\Models\Profile;
use App\Scopes\CompanyScope;
use Modules\Survey\Entities\Survey;
use Modules\Survey\Entities\SurveyQuestion;
use Modules\Survey\Entities\SurveyQuestionAnswer;
use Modules\Survey\Entities\SurveyQuestionAnswer2;
use Modules\Survey\Entities\SurveyQuestionCategory;
use Modules\Survey\Entities\SurveyTemplate;
use Modules\Survey\Entities\SurveyTemplate2;
use Modules\Survey\Entities\SurveyUser;
use Modules\Survey\Entities\SurveyUserAnswer;
use Modules\Survey\Entities\SurveyUserAnswerMatrix;
use Modules\Survey\Entities\SurveyUserCategory;
use Modules\Survey\Entities\SurveyUserExport;
use Modules\Survey\Entities\SurveyUserQuestion;
use Modules\Promotion\Entities\PromotionCourseSetting;
use Modules\Survey\Entities\SurveyQuestion2;
use Modules\Survey\Entities\SurveyQuestionCategory2;
use App\Events\SurveyRealTime;
use App\Models\InteractionHistory;
use Modules\Survey\Entities\SurveyQuestionOnline;
use Modules\Survey\Entities\SurveyAnswerOnline;
use Modules\Survey\Entities\SurveyUserAnswerOnline;

class SurveyReactController extends Controller
{
    public function index(Request $request)
    {
        return view('react.survey.index');
    }

    public function getSurvey(Request $request)
    {
        $date = date('Y-m-d');
        $profile = ProfileView::select(['title_id','user_id','unit_id'])->find(profile()->user_id);
        Survey::addGlobalScope(new CompanyScope());
        $query = Survey::query();
        $query->select('el_survey.*',)->disableCache();
        $query->where('el_survey.status', '=', 1);
        $query->where('el_survey.type', '=', 1);
        if (!Permission::isAdmin()) {
            $query->where(function ($subquery) use ($profile) {
                $subquery->orWhereIn('el_survey.id', function ($subquery2) use ($profile) {
                    $subquery2->select(['survey_id'])
                        ->from('el_survey_object')
                        ->where('user_id', '=', $profile->user_id)
                        ->orWhere('title_id', '=', @$profile->title_id)
                        ->orWhere('unit_id', '=', @$profile->unit_id);
                });
            });
        }

        if ($request->dateStart) {
            $query->where('el_survey.start_date', '>=', date_convert($request->dateStart, '00:00:00'));
        }

        if ($request->dateEnd) {
            $query->where('el_survey.end_date', '<=', date_convert($request->dateEnd, '23:59:59'));
        }

        if ($request->status && $request->status == 2) {
            $query->leftJoin('el_survey_user as su','el_survey.id','=','su.survey_id');
            $query->where('su.user_id', profile()->user_id);
        } else if ($request->status && $request->status == 1) {
            $query->whereNotExists(function ($subquery) {
                $subquery->select(['id'])
                    ->from('el_survey_user as su')
                    ->whereColumn('el_survey.id', '=', 'su.survey_id')
                    ->where('su.user_id', profile()->user_id);
            });
        }

        $query->orderBy('el_survey.id', 'desc');
        $surveys = $query->paginate(8);
        foreach ($surveys as $key => $survey) {
            $count_survey_user = SurveyUser::where('survey_id', $survey->id)->count();

            $check_online = SurveyTemplate::find($survey->template_id);
            $survey->check_online = $check_online->type ? $check_online->type : 0;

            $format_endate = Carbon::parse($survey->end_date)->format('Y-m-d');
            $format_startDate = Carbon::parse($survey->start_date)->format('Y-m-d');
            $survey_user = SurveyUser::where('survey_id', '=', $survey->id)->where('user_id', '=', profile()->user_id)->first(['send']);
            $survey->image = ($survey->image ? image_survey($survey->image) : asset('images/design/survey_default.png'));
            $survey->send = $survey_user ? $survey_user->send : '';
            $survey->start_date = Carbon::parse($survey->start_date)->format('H:i d/m/Y');
            $survey->end_date = Carbon::parse($survey->end_date)->format('H:i d/m/Y');
            $survey->count_survey_user = $count_survey_user;

            if (!$survey_user && ($date <= $format_endate) && ($date >= $format_startDate)) {
                $survey->check = 1;
            } else if (!$survey_user && ($date > $format_endate)) {
                $survey->check = 2;
            } else if ($survey_user && $survey_user->send == 1) {
                $survey->check = 3;
            } else if ($date < $format_startDate) {
                $survey->check = 4;
            } else {
                $survey->check = 5;
            }
        }

        return response()->json([
            'surveys' => $surveys,
        ]);
    }

    public function getSurveyUser($id, $courseId, $courseType)
    {
        if($courseId && $courseType) {
            $survey_user = SurveyUser::where('survey_id', '=', $id)->where('user_id', '=', profile()->user_id)->where('course_id', $courseId)->where('course_type', $courseType)->exists();
        } else {
            $survey_user = SurveyUser::where('survey_id', '=', $id)->where('user_id', '=', profile()->user_id)->exists();
        }
        if($survey_user){
            $url_edit = '/survey-react/edit-user/'.$id;

            return response()->json([
                'url_edit' => $url_edit,
            ]);
        }

        $item = Survey::findOrFail($id);$item->name = \Illuminate\Support\Str::upper($item->name);
        $item->image = ($item->image ? image_survey($item->image) : asset('images/design/survey_default.png'));

        $template = SurveyTemplate2::whereSurveyId($item->id)->firstOrFail();
        $categories = SurveyQuestionCategory2::whereTemplateId($template->id)->where('survey_id', '=', $item->id)->get();

        foreach ($categories as $key => $category) {
            $category->nameStr = Str::ucfirst($category->name);
            $questions = SurveyQuestion2::whereCategoryId($category->id)->where('survey_id', '=', $item->id)->orderBy('num_order')->get();
            foreach ($questions as $key => $question) {
                if (in_array($question->type, ['matrix','matrix_text'])) {
                    $question->rows = SurveyQuestionAnswer2::where('question_id',$question->id)->where('survey_id', '=', $item->id)->where('is_row', '=', 1)->get();
                    $question->cols = SurveyQuestionAnswer2::where('question_id',$question->id)->where('survey_id', '=', $item->id)->where('is_row', '=', 0)->get();
                    foreach($question->rows as $ans_row_key => $answer_row) {
                        foreach ($question->cols as $key => $answer_col) {
                            $matrix_anser_code = $question->answers_matrix->where('answer_row_id', '=', $answer_row->id)->where('answer_col_id', '=', $answer_col->id)->first();
                            $answer_col->matrix_anser_code = $matrix_anser_code ? $matrix_anser_code : '';
                        }
                    }
                    $question->answer_row_col = $question->answers->where('survey_id', '=', $item->id)->where('is_row', '=', 10)->first();
                } else{
                    $question->answers = SurveyQuestionAnswer2::where('question_id',$question->id)->where('survey_id', '=', $item->id)->get();
                }

            }
            $category->questions = $questions;
        }

        $profile = ProfileView::select(['code', 'user_id', 'full_name', 'unit_name', 'title_name', 'email'])->find(profile()->user_id);
        $profile->image = $profile->getAvatar();

        return response()->json([
            'item' => $item,
            'categories' => $categories,
            'profile' => $profile,
        ]);
    }

    public function editSurveyUser($id) {
        $item = Survey::findOrFail($id);
        $item->name = \Illuminate\Support\Str::upper($item->name);
        $item->image = ($item->image ? image_survey($item->image) : asset('images/design/survey_default.png'));

        $survey_user = SurveyUser::where('survey_id', '=', $item->id)
            ->where('user_id', '=', profile()->user_id)->first(['id','send','more_suggestions']);
        $survey_user_categories = SurveyUserCategory::where('survey_user_id', '=', $survey_user->id)->get();
        $question_errors = session()->get('error');
        session()->forget('error');

        foreach ($survey_user_categories as $key => $survey_user_category) {
            $survey_user_category->category_name = Str::ucfirst($survey_user_category->category_name);
            $survey_user_category->questions = $survey_user_category->questions;
            foreach ($survey_user_category->questions as $key => $question) {
                if (in_array($question->type, ['matrix','matrix_text'])) {
                    $question->rows = SurveyUserAnswer::where('survey_user_question_id',$question->id)->where('is_row', '=', 1)->get();
                    $question->cols = SurveyUserAnswer::where('survey_user_question_id',$question->id)->where('is_row', '=', 0)->get();
                    foreach($question->rows as $ans_row_key => $answer_row) {
                        $check_answer_matrix = $answer_row->check_answer_matrix ? json_decode($answer_row->check_answer_matrix) : [];
                        $answer_row->check_answer_matrix = $check_answer_matrix;
                        $answer_matrix = json_decode($answer_row->answer_matrix);
                        $answer_row->answer_matrix = $answer_matrix;
                        foreach ($question->cols as $key => $answer_col) {
                            $matrix_anser_code = $question->answers_matrix->where('answer_row_id', '=', $answer_row->answer_id)->where('answer_col_id', '=', $answer_col->answer_id)->first();
                            $answer_col->matrix_anser_code = $matrix_anser_code ? $matrix_anser_code : '';
                        }
                    }
                    $question->answer_row_col = $question->answers->where('is_row', '=', 10)->first();
                } else if ($question->type == 'sort') {
                    $question->answers = SurveyUserAnswer::where('survey_user_question_id',$question->id)->orderBy('text_answer')->get();
                } else {
                    $question->answers = $question->answers;
                }
            }
        }

        $profile = ProfileView::select(['code', 'user_id', 'full_name', 'unit_name', 'title_name', 'email'])->find(profile()->user_id);
        $profile->image = $profile->getAvatar();

        return response()->json([
            'item' => $item,
            'survey_user' => $survey_user,
            'survey_user_categories' => $survey_user_categories,
            'question_errors' => $question_errors,
            'profile' => $profile,
        ]);
    }

    public function saveSurveyUser(Request $request){
        $this->validateRequest([
            'survey_id' => 'required',
        ], $request);

        $errors = [];
        $title_report = [];
        $content_report = [];

        $survey_user_id = $request->survey_user_id;
        $template_id = $request->template_id;
        $survey_id = $request->survey_id;

        $user_category_id = $request->user_category_id;
        $category_id = $request->category_id;
        $category_name = $request->category_name;

        $user_question_id = $request->user_question_id;
        $question_id = $request->question_id;
        $question_code = $request->question_code;
        $question_name = $request->question_name;
        $type = $request->type;
        $multiple = $request->multiple;
        $answer_essay = $request->answer_essay;

        $user_answer_id = $request->user_answer_id;
        $answer_id = $request->answer_id;
        $answer_code = $request->answer_code;
        $answer_name = $request->answer_name;
        $is_text = $request->is_text;
        $text_answer = $request->text_answer;
        $is_check = $request->is_check;
        $is_row = $request->is_row;
        $answer_matrix = $request->answer_matrix;
        $check_answer_matrix = $request->check_answer_matrix;
        $answer_icon = $request->answer_icon;

        $send = $request->send;
        $more_suggestions = $request->more_suggestions;
        $answer_matrix_code = $request->answer_matrix_code;

        if($send == 1) {
            $surveyCategory = SurveyQuestionCategory2::where(['survey_id' => $survey_id, 'template_id' => $template_id])->get(['id']);
            foreach ($surveyCategory as $key => $category) {
                $questions = SurveyQuestion2::where('category_id', $category->id)->where('survey_id', '=', $survey_id)->get(['id', 'obligatory', 'type', 'name']);
                foreach ($questions as $key => $question) {
                    if ($question->obligatory == 0) {
                        continue;
                    } else {
                        $answerEssay = $answer_essay[$category->id][$question->id];
                        $check = $is_check[$category->id][$question->id];
                        $userAnswer = 0;
                        if (!empty($answerEssay) || !empty($check)) {
                            $userAnswer = 1;
                        } else {
                            foreach($answer_id[$category->id][$question->id] as $ans_key => $ans_id){
                                if($question->type == 'matrix' && isset($check_answer_matrix[$category->id][$question->id][$ans_id])) {
                                    $checkAnswerMatrix = $check_answer_matrix[$category->id][$question->id][$ans_id];
                                    foreach ($checkAnswerMatrix as $key => $checkMatrix) {
                                        if(isset($checkMatrix)) {
                                            $userAnswer = 1;
                                        }
                                    }
                                } else if ($question->type == 'matrix_text' && isset($answer_matrix[$category->id][$question->id][$ans_id])) {
                                    $answerMatrix = $answer_matrix[$category->id][$question->id][$ans_id];
                                    foreach ($answerMatrix as $key => $answer) {
                                        if(isset($answer)) {
                                            $userAnswer = 1;
                                        }
                                    }
                                } else {
                                    $checkAns = $is_check[$category->id][$question->id][$ans_id];
                                    $textAnswer = $text_answer[$category->id][$question->id][$ans_id];
                                    if(!empty($checkAns) || !empty($textAnswer)) {
                                        $userAnswer = 1;
                                    }
                                }
                            }
                        }
                        if($userAnswer == 0) {
                            return response()->json([
                                'status' => 'warning',
                                'message' => 'Câu hỏi: '. $question->name .' là câu hỏi bắt buộc. Vui lòng bạn trả lời',
                            ]);
                        }
                    }
                    if($question->type == 'percent'){
                        $total = 0;
                        $arr_answer_percent = $text_answer[$cate_id][$ques_id];
                        foreach ($arr_answer_percent as $percent){
                            $total += floatval(preg_replace("/[^0-9]/", '', $percent));
                        }
                        if ($total > 100){
                            return response()->json([
                                'status' => 'warning',
                                'message' => 'Tổng phần trăm câu hỏi: '. $question->name .' vượt quá 100%',
                            ]);
                        }
                    }
                }
            }
        }

        $model = SurveyUser::firstOrNew(['id' => $survey_user_id, 'user_id' =>  profile()->user_id, 'survey_id' => $survey_id]);
        $model->user_id = profile()->user_id;
        $model->survey_id = $survey_id;
        $model->send = $send;
        $model->template_id = $template_id;
        $model->more_suggestions = $more_suggestions ? $more_suggestions : '';
        $model->course_id = $request->course_id ? $request->course_id : null;
        $model->course_type = $request->course_type ? $request->course_type : null;
        $model->save();

        foreach($category_id as $cate_key => $cate_id){
            $categories = SurveyUserCategory::firstOrNew(['id' => $user_category_id[$cate_key]]);
            $categories->survey_user_id = $model->id;
            $categories->category_id = $cate_id;
            $categories->category_name = $category_name[$cate_id];
            $categories->save();

            if(isset($question_id[$cate_id])){
                foreach($question_id[$cate_id] as $ques_key => $ques_id){
                    if (empty($ques_id)) {
                        continue;
                    }
                    $user_ques_id = $user_question_id[$cate_id][$ques_key];
                    $ques_code = $question_code[$cate_id][$ques_id];
                    $ques_name = $question_name[$cate_id][$ques_id];

                    $survey_user_question = SurveyUserQuestion::firstOrNew(['id' => $user_ques_id]);
                    $survey_user_question->survey_user_category_id = $categories->id;
                    $survey_user_question->question_id = $ques_id;
                    $survey_user_question->question_code = isset($ques_code) ? $ques_code : null;
                    $survey_user_question->question_name = $ques_name;
                    $survey_user_question->type = $type[$cate_id][$ques_id];
                    $survey_user_question->multiple = $multiple[$cate_id][$ques_id];
                    $survey_user_question->answer_essay = isset($answer_essay[$cate_id][$ques_id]) ? $answer_essay[$cate_id][$ques_id] : '';
                    $survey_user_question->save();

                    if ($survey_user_question->type == 'choice' && $survey_user_question->multiple == 0){
                        $title_report[] = isset($ques_code) ? $ques_code : 'null';
                    }
                    if ($survey_user_question->type == 'essay' || $survey_user_question->type == 'time'){
                        $title_report[] = isset($ques_code) ? $ques_code : 'null';
                        $content_report[] = isset($survey_user_question->answer_essay) ? $survey_user_question->answer_essay : 'null';
                    }
                    if ($survey_user_question->type == 'dropdown' || $survey_user_question->type == 'rank' || $survey_user_question->type == 'rank_icon'){
                        $title_report[] = isset($ques_code) ? $ques_code : 'null';
                        $content_report[] = isset($answer_code[$cate_id][$ques_id][$survey_user_question->answer_essay]) ? $answer_name[$cate_id][$ques_id][$survey_user_question->answer_essay] : 'null';
                    }

                    if(isset($answer_id[$cate_id][$ques_id])){
                        foreach($answer_id[$cate_id][$ques_id] as $ans_key => $ans_id){
                            if (empty($ans_id)) {
                                continue;
                            }
                            $user_ans_id = $user_answer_id[$cate_id][$ques_id][$ans_key];
                            $ans_code = $answer_code[$cate_id][$ques_id][$ans_id];
                            $ans_name = $answer_name[$cate_id][$ques_id][$ans_id];
                            $text = $is_text[$cate_id][$ques_id][$ans_id];
                            $row = $is_row[$cate_id][$ques_id][$ans_id];
                            $icon = $answer_icon[$cate_id][$ques_id][$ans_id];

                            $survey_user_answer = SurveyUserAnswer::firstOrNew(['id' => $user_ans_id]);
                            $survey_user_answer->survey_user_question_id = $survey_user_question->id;
                            $survey_user_answer->answer_id = $ans_id;
                            $survey_user_answer->answer_code = isset($ans_code) ? $ans_code : '';
                            $survey_user_answer->answer_name = isset($ans_name) ? $ans_name : '';
                            $survey_user_answer->is_text = $text;
                            $survey_user_answer->is_row = $row;
                            $survey_user_answer->icon = isset($icon) ? $icon : null;

                            if ($survey_user_question->multiple == 1){
                                $survey_user_answer->is_check = isset($is_check[$cate_id][$ques_id][$ans_id]) ? $is_check[$cate_id][$ques_id][$ans_id] : 0;

                                if ($survey_user_question->type == 'choice'){
                                    $title_report[] = isset($ans_code) ? $ans_code : 'null';
                                    $content_report[] = isset($text_answer[$cate_id][$ques_id][$ans_id]) ? $text_answer[$cate_id][$ques_id][$ans_id] : (isset($is_check[$cate_id][$ques_id][$ans_id]) ? 1 : 0);
                                }
                            }else{
                                if (isset($is_check[$cate_id][$ques_id]) && ($ans_id == $is_check[$cate_id][$ques_id])){
                                    $survey_user_answer->is_check = $ans_id;

                                    $content_report[] = (isset($ans_code) ? $ans_code : 'null') . (isset($text_answer[$cate_id][$ques_id][$ans_id]) ? ' - '.$text_answer[$cate_id][$ques_id][$ans_id] : '');
                                }else{
                                    $survey_user_answer->is_check = 0;
                                }
                            }

                            if($survey_user_question->type == 'percent'){
                                $survey_user_answer->text_answer = isset($text_answer[$cate_id][$ques_id][$ans_id]) && $total <= 100 ? $text_answer[$cate_id][$ques_id][$ans_id] : '';
                            }else{
                                $survey_user_answer->text_answer = isset($text_answer[$cate_id][$ques_id][$ans_id]) ? $text_answer[$cate_id][$ques_id][$ans_id] : '';
                            }

                            $survey_user_answer->answer_matrix = isset($answer_matrix[$cate_id][$ques_id][$ans_id]) ? json_encode($answer_matrix[$cate_id][$ques_id][$ans_id]) : '';

                            $survey_user_answer->check_answer_matrix = isset($check_answer_matrix[$cate_id][$ques_id][$ans_id]) ? json_encode($check_answer_matrix[$cate_id][$ques_id][$ans_id]) : '';

                            $survey_user_answer->save();
                            if ($survey_user_question->type == 'matrix' && $survey_user_question->multiple == 0 && $survey_user_answer->is_row == 1){
                                $title_report[] = isset($ans_code) ? $ans_code : 'null';

                                if(isset($check_answer_matrix[$cate_id][$ques_id][$ans_id][0])){
                                    $arr_col_answer = SurveyQuestionAnswer2::where('survey_id', '=', $survey_id)
                                    ->where('question_id', '=', $survey_user_question->question_id)
                                    ->where('is_row', '=', 0)
                                    ->pluck('id')->toArray();

                                    $item_check = $check_answer_matrix[$cate_id][$ques_id][$ans_id][0];
                                    foreach ($arr_col_answer as $key => $item){
                                        if ($item == $item_check){
                                            $content_report[] = ($key + 1);
                                        }
                                    }
                                }else{
                                    $content_report[] = null;
                                }
                            }
                        }

                        if (in_array($survey_user_question->type, ['text', 'sort', 'percent', 'number'])){
                            $arr_export = SurveyUserAnswer::whereSurveyUserQuestionId($survey_user_question->id)->get();
                            foreach ($arr_export as $export){
                                $title_report[] = isset($export->answer_code) ? $export->answer_code : 'null';
                                $content_report[] = isset($export->text_answer) ? $export->text_answer : 'null';
                            }
                        }
                    }

                    if (($survey_user_question->type == 'matrix' && $survey_user_question->multiple == 1) || $survey_user_question->type == 'matrix_text'){
                        if(isset($answer_matrix_code[$cate_id][$ques_id])) {
                            foreach ($answer_matrix_code[$cate_id][$ques_id] as $ans_key => $matrix) {
                                if (empty($matrix)) {
                                    continue;
                                }
                                $answer_matrix_text = isset($answer_matrix[$cate_id][$ques_id][$ans_key]) ? $answer_matrix[$cate_id][$ques_id][$ans_key] : '';
                                // dd($answer_matrix_text);
                                $i = 0;
                                foreach ($matrix as $matrix_key => $matrix_code){
                                    SurveyUserAnswerMatrix::query()
                                        ->updateOrCreate([
                                            'survey_user_question_id' => $survey_user_question->id,
                                            'answer_row_id' => $ans_key,
                                            'answer_col_id' => $matrix_key
                                        ],[
                                            'survey_user_question_id' => $survey_user_question->id,
                                            'answer_row_id' => $ans_key,
                                            'answer_col_id' => $matrix_key,
                                            'answer_code' => $matrix_code
                                        ]);

                                    $title_report[] = isset($matrix_code) ? $matrix_code : 'null';

                                    $check = isset($check_answer_matrix[$cate_id][$ques_id][$ans_key]) ? $check_answer_matrix[$cate_id][$ques_id][$ans_key] : [];

                                    if(($survey_user_question->type == 'matrix' && $survey_user_question->multiple == 1)){
                                        $content_report[] = in_array($matrix_key, $check) ? 1 : 0;
                                    }

                                    if($survey_user_question->type == 'matrix_text'){
                                        if (!empty($answer_matrix_text) && !empty($answer_matrix_text[$i])) {
                                            $content_report[] = $answer_matrix_text[$i];
                                        } else {
                                            $content_report[] = 'null';
                                        }

                                    }

                                    $i += 1;
                                }
                            }
                        }
                    }
                }
            }
        }

        session()->forget('error');
        if (count($errors) > 0){
            session()->push('error', $errors);
            session()->save();
        }

        if ($send == 1){
            if (count($title_report) > 0){
                foreach ($title_report as $key => $title){
                    $export = new SurveyUserExport();
                    $export->user_id = profile()->user_id;
                    $export->survey_id = $survey_id;
                    $export->title = $title;
                    $export->content = isset($content_report[$key]) ? $content_report[$key] : '';
                    $export->save();
                }
            }

            /*Lưu lịch sử tương tác của HV*/
            $interaction_history = InteractionHistory::where(['user_id' => profile()->user_id, 'code' => 'survey'])->first();
            if($interaction_history){
                $interaction_history->number = ($interaction_history->number + 1);
                $interaction_history->save();
            }else{
                $interaction_history = new InteractionHistory();
                $interaction_history->user_id = profile()->user_id;
                $interaction_history->code = 'survey';
                $interaction_history->name = 'Khảo sát';
                $interaction_history->number = 1;
                $interaction_history->save();
            }
        }

        if($request->course_id) {
            if($request->course_type == 1) {
                $url = '/all-course/1?trainingProgramId='. $request->trainingProgramId;
            } else {
                $url = '/all-course/2';
            }
        } else {
            if ($send == 1){
                $url = '/survey-react';
            } else {
                $url = '/survey-react/edit-user/'.$survey_id;
            }
        }
        
        json_result([
            'status' => 'success',
            'message' => trans('laother.successful_save'),
            'redirect_url' => $url
        ]);
    }

    // KHẢO SÁT TRỰC TUYẾN
    public function getSurveyOnline($id)
    {
        $item = Survey::findOrFail($id);
        $questions = SurveyQuestionOnline::where('template_id', $item->template_id)->get();
        foreach($questions as $question) {
            $all_answer = [];
            $answers = SurveyAnswerOnline::where('question_id', $question->id)->get();
            foreach($answers as $answer) {
                $all_answer[] = $answer->id;
            }
            $question->answers = $answers;
            $question->all_answer = $all_answer;
        }

        $profile = ProfileView::select(['code', 'user_id', 'full_name', 'unit_name', 'title_name'])->find(profile()->user_id);
        return response()->json([
            'item' => $item,
            'questions' => $questions,
            'profile' => $profile,
        ]);
    }

    public function saveSurveyOnline(Request $request)
    {
        $user_id = profile()->user_id;
        $survey = Survey::find($request->survey_id, ['template_id']);
        $save_survey = SurveyUser::firstOrNew(['user_id'=> $user_id, 'survey_id' => $request->survey_id, 'template_id' => $survey->template_id]);
        $save_survey->user_id = $user_id;
        $save_survey->survey_id = $request->survey_id;
        $save_survey->template_id = $survey->template_id;
        $save_survey->send = $request->send ? $request->send : 0;
        $save_survey->more_suggestions = null;
        $save_survey->save();

        json_result([
            'status' => 'success',
            'message' => 'Đã gửi thành công',
            'redirect_url' => '/survey-react'
        ]);
    }

    public function saveSurveyAnwserOnline(Request $request)
    {
        $user_id = profile()->user_id;
        event(new SurveyRealTime($user_id, $request));
    }

    public function editSurveyUserOnline($id)
    {
        $item = Survey::findOrFail($id);
        $survey_user = SurveyUser::where('user_id', profile()->user_id)->where('survey_id', $item->id)->first('send');
        $item->send = $survey_user->send ? $survey_user->send : 0;

        $questions = SurveyQuestionOnline::where('template_id', $item->template_id)->get();
        foreach($questions as $question) {
            $all_answer = [];
            $answers = SurveyAnswerOnline::where('question_id', $question->id)->get();
            $question->answers = $answers;
            foreach($answers as $answer) {
                $all_answer[] = $answer->id;
            }
            $question->all_answer = $all_answer;
        }
        $get_user_answers = SurveyUserAnswerOnline::where('user_id', profile()->user_id)->where('survey_id', $item->id)->pluck('answer_id')->toArray();
        $user_answers = array_map(
            function($value) { return (int)$value; },
            $get_user_answers
        );

        $profile = ProfileView::select(['code', 'user_id', 'full_name', 'unit_name', 'title_name'])->find(profile()->user_id);
        return response()->json([
            'item' => $item,
            'questions' => $questions,
            'user_answers' => $user_answers,
            'profile' => $profile,
        ]);
    }
}
