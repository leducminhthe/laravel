<?php

namespace Modules\Survey\Http\Controllers;

use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Survey\Entities\Survey;
use Modules\Survey\Entities\SurveyUserAnswerOnline;
use Modules\Survey\Entities\SurveyQuestionOnline;
use Modules\Survey\Entities\SurveyAnswerOnline;
use Modules\Survey\Entities\SurveyTemplate;
use Modules\Survey\Entities\SurveyUser;

class TemplateOnlineController extends Controller
{
    public function index() {
        return view('survey::backend.template_online.index',[
        ]);
    }

    public function getData(Request $request) {
        $search = $request->input('search');
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = SurveyTemplate::query();
        $query->where('type', 1);

        if ($search) {
            $query->where('name', 'like', '%'. $search .'%');
        }

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row) {
            $row->edit_url = route('module.survey.template_online.edit', ['id' => $row->id]);
            $row->created_by = Profile::fullname($row->created_by) .' ('. Profile::usercode($row->created_by) .')';
            $row->updated_by = Profile::fullname($row->updated_by) .' ('. Profile::usercode($row->updated_by) .')';

            $row->review = route('module.survey.template_online.review', ['survey_id' => 0, 'id' => $row->id]);
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function form($id = 0) {
        if ($id) {
            $model = SurveyTemplate::find($id);
            $questions = SurveyQuestionOnline::where('template_id', $id)->get();
            foreach($questions as $question) {
                $answer = SurveyAnswerOnline::where('question_id', $question->id)->get();
                $question->answer = $answer;
            }
            $page_title = $model->name;

            return view('survey::backend.template_online.form', [
                'model' => $model,
                'page_title' => $page_title,
                'questions' => $questions
            ]);
        }

        $model = new SurveyTemplate();
        $page_title = trans('lasurvey.add_new') ;

        return view('survey::backend.template_online.form', [
            'model' => $model,
            'page_title' => $page_title,
        ]);
    }

    public function save(Request $request) {
        $this->validateRequest([
            'name' => 'required',
        ], $request, [
            'name' => 'Tên mẫu',
        ]);

        $survey_template = SurveyTemplate::firstOrNew(['id' => $request->id]);
        $survey_template->name = $request->name;
        $survey_template->created_by = $request->id ? profile()->user_id : $survey_template->created_by;
        $survey_template->updated_by = profile()->user_id;
        $survey_template->type = 1;
        $survey_template->save();

        foreach ($request->question as $key => $question) {
            $question_online = SurveyQuestionOnline::firstOrNew(['id' => $request->question_id[$key]]);
            $question_online->template_id =  $survey_template->id;
            $question_online->question = $question;
            $question_online->multiple = $request->{'multiple_answer_'. ($key + 1)};
            $question_online->save();  
            foreach ($request->{'answer_'. ($key + 1)} as $key_answer => $answer) {
                $answer_online = SurveyAnswerOnline::firstOrNew(['id' => $request->{'answer_id_'. ($key + 1)}[$key_answer]]);
                $answer_online->template_id =  $survey_template->id;
                $answer_online->answer = $answer;
                $answer_online->question_id = $question_online->id;
                $answer_online->save();
            }
        }

        json_result([
            'status' => 'success',
            'message' => trans('laother.successful_save'),
            'redirect' => route('module.survey.template_online')
        ]);
    }

    public function remove(Request $request) {
        $ids = $request->input('ids', null);
        foreach($ids as $id){
            $check_survey = Survey::where('template_id', $id)->exists();
            if($check_survey){
                json_message('Mẫu ' . $id . ' không thể xóa vì khảo sát đang sử dụng', 'error');
            }

            $survey = SurveyUser::where('template_id', '=', $id)->get();
            foreach($survey as $item){
                if($item->send == 1){
                    json_message('Mẫu ' . $id . ' không thể xóa', 'error');
                }
            }

            SurveyAnswerOnline::where('template_id', $id)->delete();
            SurveyQuestionOnline::where('template_id', $id)->delete();
        }
        SurveyTemplate::destroy($ids);
        json_result([
            'status' => 'success',
            'message' => trans('laother.delete_success'),
        ]);
    }

    public function removeQuestion(Request $request) {
        $ques_id = $request->input('question_id', null);
        SurveyQuestionOnline::whereId($ques_id)->delete();
        SurveyAnswerOnline::whereQuestionId($ques_id)->delete();
        json_result([
            'status' => 'success',
            'message' => trans('laother.delete_success'),
        ]);
    }

    public function removeAnswer(Request $request) {
        $answer_id = $request->input('answer_id', null);
        $answer = SurveyAnswerOnline::whereId($answer_id)->first();
        $answer->delete();

        json_result([
            'status' => 'success',
            'message' => trans('laother.delete_success'),
        ]);
    }

    public function reviewTemplate($survey_id, $id){
        $questions = SurveyQuestionOnline::where('template_id', $id)->get();
        foreach($questions as $question) {
            $answers = SurveyAnswerOnline::where('question_id', $question->id)->get();
            if ($survey_id != 0) {
                foreach ($answers as $key => $answer) {
                    $count = SurveyUserAnswerOnline::where('answer_id', $answer->id)->where('survey_id', $survey_id)->count();
                    $answer->count = $count;
                } 
            }
            $question->answers = $answers;
        }
        $all_user = [];
        $user_answers = SurveyUserAnswerOnline::select('user_id')->groupBy('user_id')->where('survey_id', $survey_id)->get();
        foreach($user_answers as $user_answer) {
            $get_answers = SurveyUserAnswerOnline::where('user_id', $user_answer->user_id)->where('survey_id', $survey_id)->pluck('answer_id')->toArray();
            $answersInt = array_map(
                function($value) { return (int)$value; },
                $get_answers
            );
            $all_user[] = [(int)$user_answer->user_id, $answersInt];
        }
        $all_user = json_encode($all_user);
        return view('survey::backend.template_online.review', [
            'questions' => $questions,
            'survey_id' => $survey_id,
            'all_user' => $all_user
        ]);
    }

    public function detailUserAnswer(Request $request)
    {
        $name_answer = SurveyAnswerOnline::find($request->answer_id, ['answer']);
        $model = SurveyUserAnswerOnline::query();
        $model->select([
            'b.avatar',
            'b.full_name',
        ]);
        $model->from('el_survey_user_answer_online as a');
        $model->leftJoin('el_profile_view as b','b.user_id','=','a.user_id');
        $model->where('a.answer_id', $request->answer_id);
        $model->where('a.survey_id', $request->survey_id);
        $users = $model->get();
        foreach ($users as $key => $user) {
            $user->avatar = image_user($user->avatar);
        }
        json_result([
            'users' => $users,
            'name_answer' => $name_answer->answer
        ]);
    }

    // SAO CHÉP MẪU KHẢO SÁT TRỰC TUYẾN
    public function copyTemplateOnline(Request $request)
    {
        $ids = $request->input('ids', null);
        foreach ($ids as $id) {
            $template = SurveyTemplate::find($id);
            $newTemplate = $template->replicate();
            $newTemplate->name = $newTemplate->name . '_copy'. rand(2, 50);
            $newTemplate->save();

            $questions = SurveyQuestionOnline::where('template_id', '=', $template->id)->get();
            foreach ($questions as $question){
                $newQuestion = $question->replicate();
                $newQuestion->template_id = $newTemplate->id;
                $newQuestion->save();

                $answers = SurveyAnswerOnline::where('question_id', '=', $question->id)->get();
                foreach ($answers as $answer){
                    $newAnswer = $answer->replicate();
                    $newAnswer->question_id = $newQuestion->id;
                    $newAnswer->save();
                }
            }
        }
    }
}
