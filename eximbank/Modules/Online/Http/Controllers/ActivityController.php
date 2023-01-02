<?php

namespace Modules\Online\Http\Controllers;

use App\Traits\ZoomMeetingTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\Online\Entities\OnlineActivity;
use Modules\Online\Entities\OnlineCourse;
use Modules\Online\Entities\OnlineCourseActivity;
use Modules\Online\Entities\OnlineCourseActivityFile;
use Modules\Online\Entities\OnlineCourseActivityScorm;
use Modules\Online\Entities\OnlineCourseActivityUrl;
use Modules\Online\Entities\OnlineCourseActivityVideo;
use Modules\Online\Entities\OnlineCourseActivityXapi;
use Modules\Online\Entities\OnlineCourseActivityZoom;
use Modules\Online\Entities\OnlineCourseActivityQuiz;
use Modules\Online\Entities\OnlineCourseCondition;
use Modules\Online\Entities\OnlineHistoryEdit;
use Modules\Online\Entities\OnlineRegister;
use Modules\Online\Entities\Scorm;
use Modules\Online\Entities\Xapi;
use Modules\Quiz\Entities\Quiz;
use Modules\Quiz\Entities\QuizPart;
use Modules\Quiz\Entities\QuizRegister;
use Modules\VirtualClassroom\Entities\VirtualClassroom;
use Owenoj\LaravelGetId3\GetId3;
use Illuminate\Support\Str;
use Modules\Online\Entities\OnlineCourseLesson;
use Modules\Online\Entities\OnlineCourseActivitySurvey;
use Modules\Online\Entities\OnlineCourseSettingPercent;
use Modules\Online\Entities\OnlineSurveyAnswer;
use Modules\Online\Entities\OnlineSurveyAnswerMatrix;
use Modules\Online\Entities\OnlineSurveyCategory;
use Modules\Online\Entities\OnlineSurveyQuestion;
use Modules\Online\Entities\OnlineSurveyTemplate;
use Modules\Survey\Entities\Survey;
use Modules\Survey\Entities\SurveyAnswerMatrix;
use Modules\Survey\Entities\SurveyQuestion;
use Modules\Survey\Entities\SurveyQuestionAnswer;
use Modules\Survey\Entities\SurveyQuestionCategory;
use Modules\Survey\Entities\SurveyTemplate;

class ActivityController extends Controller
{
    use ZoomMeetingTrait;
    public function saveActivity($course_id, $activity_id, $type, Request $request) {
        $this->validateRequest([
            'name' => 'required',
            'select_lesson_name' => 'required',
        ], $request, [
            'name' => 'Tên hoạt động',
            'select_lesson_name' => 'Tên bài học',
        ]);

        $setting_complete_course_activity_id = $request->setting_complete_course_activity_id ? $request->setting_complete_course_activity_id : [];

        $subject_id = $request->subject_id;

        if($activity_id == 8 && !$subject_id){
            json_message('Chưa chọn mẫu khảo sát', 'error');
        }

        $activity = OnlineActivity::findOrFail($activity_id);
        $namespace = 'Modules\Online\Http\Controllers\ActivityController';

        if (method_exists($namespace, 'addActivity'. ucfirst($activity->code))) {
            $subject_id = $this->{'addActivity'. ucfirst($activity->code)}($course_id, $request);
        }

        if ($subject_id) {
            $model = OnlineCourseActivity::firstOrNew(['id' => $request->post('id', null)]);

            $check_update_act_8 = 0;
            if($model->id){
                if($model->subject_id != $subject_id && $model->activity_id == 8){
                    $acti_survey = OnlineCourseActivity::where('course_id', '=', $course_id)
                    ->where('activity_id', '=', 8)
                    ->where('subject_id', '=', $subject_id)
                    ->first();
                    if ($acti_survey){
                        json_message('Hoạt động Khảo sát đã thêm mẫu. Mời chọn mẫu khảo sát khác', 'error');
                    }else{
                        //gán lại giá trị cũ để xử lý update
                        $check_update_act_8 = $model->subject_id;
                    }
                }
            }

            $model->fill($request->all());

            if(count($setting_complete_course_activity_id) > 0){
                $model->setting_complete_course_activity_id = implode(',', $setting_complete_course_activity_id);
            }

            if (!$request->setting_score_course_activity_id && ($request->setting_min_score || $request->setting_max_score)){
                json_message('Chọn hoạt động trước khi thiết lập điểm', 'error');
            }

            if ($request->setting_score_course_activity_id && !$request->setting_min_score && !$request->setting_max_score){
                json_message('Mời nhập điểm thiết lập cho hoạt động', 'error');
            }

            if ($request->setting_start_date){
                $setting_start_date = get_date($request->setting_start_date);
                $setting_start_time = get_date($request->setting_start_date, "H:i:s");

                $model->setting_start_date = date_convert($setting_start_date, $setting_start_time);
            }
            if ($request->setting_end_date){
                $setting_end_date = get_date($request->setting_end_date);
                $setting_end_time = get_date($request->setting_end_date, "H:i:s");

                $model->setting_end_date = date_convert($setting_end_date, $setting_end_time);
            }

            if (empty($model->id)) {
                $acti_survey = OnlineCourseActivity::where('course_id', '=', $course_id)
                ->where('activity_id', '=', 8)
                ->where('subject_id', '=', $subject_id)
                ->first();
                if ($acti_survey && $activity_id == 8){
                    json_message('Hoạt động Khảo sát đã thêm mẫu. Mời chọn mẫu khảo sát khác', 'error');
                }

                $acti = OnlineCourseActivity::where('course_id', '=', $course_id)
                ->where('activity_id', '=', 2)
                ->where('subject_id', '=', $subject_id)
                ->first();

                if ($acti && $activity_id == 2){
                    json_message('Hoạt động thi đã thêm. Mời chọn kỳ thi khác', 'error');
                }

                $num_order = (int) OnlineCourseActivity::query()->where('course_id', '=', $course_id)->max('num_order') + 1;

                $model->name = $request->name;
                $model->course_id = $course_id;
                $model->activity_id = $activity_id;
                $model->subject_id = $subject_id;
                $model->num_order = $num_order;
                $model->lesson_id = $request->select_lesson_name;
                $model->status = 1;

                if ($model->save()) {

                    if ($model->subject_id && $model->activity_id == 2){
                        Quiz::where('id','=',$model->subject_id)
                            ->update(['course_id'=>$model->course_id,'course_type'=>1]);

                        $course_register = OnlineRegister::whereCourseId($course_id)->where('status', '=', 1)->get();
                        if ($course_register->count() > 0){
                            $quiz_part = QuizPart::where('quiz_id', '=', $model->subject_id)->first();
                            foreach ($course_register as $register){
                                QuizRegister::query()
                                    ->updateOrCreate([
                                        'quiz_id' => $model->subject_id,
                                        'user_id' => $register->user_id,
                                        'type' => $register->user_type,
                                        'part_id' => $quiz_part->id,
                                    ],[
                                        'quiz_id' => $model->subject_id,
                                        'user_id' => $register->user_id,
                                        'type' => $register->user_type,
                                        'part_id' => $quiz_part->id,
                                    ]);
                            }
                        }

                        $save_activity_quiz = OnlineCourseActivityQuiz::firstOrNew(['course_id' => $course_id, 'quiz_id' => $subject_id]);
                        $save_activity_quiz->course_id = $course_id;
                        $save_activity_quiz->quiz_id = $subject_id;
                        $save_activity_quiz->description = $request->description;
                        $save_activity_quiz->save();
                    }

                    if ($model->subject_id && $model->activity_id == 6){
                        VirtualClassroom::where('id','=',$model->subject_id)
                            ->update(['course_id' => $model->course_id]);
                    }

                    if($model->subject_id && $model->activity_id == 8){
                        $save_activity_survey = OnlineCourseActivitySurvey::firstOrNew([
                            'course_id' => $course_id,
                            'survey_template_id' => $subject_id
                        ]);
                        $save_activity_survey->course_id = $course_id;
                        $save_activity_survey->survey_template_id = $subject_id;
                        $save_activity_survey->description = $request->description;
                        $save_activity_survey->save();

                        $template = SurveyTemplate::find($subject_id)->toArray();

                        $new_template = new OnlineSurveyTemplate();
                        $new_template->fill($template);
                        $new_template->id = $template['id'];
                        $new_template->course_id = $course_id;
                        $new_template->course_activity_id = $model->id;
                        $new_template->save();

                        $categories = SurveyQuestionCategory::query()->where('template_id', $template['id'])->get()->toArray();
                        foreach ($categories as $category){
                            $new_category = new OnlineSurveyCategory();
                            $new_category->fill($category);
                            $new_category->id = $category['id'];
                            $new_category->course_id = $course_id;
                            $new_category->course_activity_id = $model->id;
                            $new_category->save();

                            $questions = SurveyQuestion::query()->where('category_id', $category['id'])->get()->toArray();
                            foreach ($questions as $question){
                                $new_question = new OnlineSurveyQuestion();
                                $new_question->fill($question);
                                $new_question->id = $question['id'];
                                $new_question->course_id = $course_id;
                                $new_question->course_activity_id = $model->id;
                                $new_question->save();

                                $answers = SurveyQuestionAnswer::query()->where('question_id', $question['id'])->get()->toArray();
                                foreach ($answers as $answer){
                                    $new_answer = new OnlineSurveyAnswer();
                                    $new_answer->fill($answer);
                                    $new_answer->id = $answer['id'];
                                    $new_answer->course_id = $course_id;
                                    $new_answer->course_activity_id = $model->id;
                                    $new_answer->save();
                                }

                                $answers_matrix = SurveyAnswerMatrix::query()->where('question_id', $question['id'])->get()->toArray();
                                foreach ($answers_matrix as $answer_matrix){
                                    $new_answer_matrix = new OnlineSurveyAnswerMatrix();
                                    $new_answer_matrix->fill($answer_matrix);
                                    $new_answer_matrix->course_id = $course_id;
                                    $new_answer_matrix->course_activity_id = $model->id;
                                    $new_answer_matrix->save();
                                }
                            }
                        }
                    }

                    $history_edit = new OnlineHistoryEdit();
                    $history_edit->type = 1;
                    $history_edit->course_id = $course_id;
                    $history_edit->user_id = profile()->user_id;
                    $history_edit->tab_edit = 'Thêm hoạt động: '. $model->name;
                    $history_edit->ip_address = $request->ip();
                    $history_edit->save();

                    if($type == 1) {
                        $redirect = route('module.online.course_for_offline.edit_activity_lesson', ['id' => $course_id]);
                    } else {
                        $redirect = route('module.online.edit_activity_lesson', ['id' => $course_id]);
                    }

                    json_result([
                        'status' => 'success',
                        'message' => trans('laother.successful_save'),
                        'redirect' => $redirect
                    ]);
                }
            }else {
                $complete_course_activity = OnlineCourseActivity::whereCourseId($course_id)
                    ->whereIn('id', $setting_complete_course_activity_id)
                    ->get(['name', 'setting_complete_course_activity_id']);
                foreach($complete_course_activity as $complete){
                    $include = explode(',', $complete->setting_complete_course_activity_id);
                    if(in_array($model->id, $include)){
                        json_message('Hoạt động "'. $model->name .'" thuộc điều kiện hoàn thành của hoạt động "'. $complete->name .'". Không thể thiết lập chéo.', 'error');

                        continue;
                    }
                }

                $model->name = $request->name;
                $model->subject_id = $subject_id;
                $model->lesson_id = $request->select_lesson_name;
                $model->status = 1;
                if ($model->save()) {

                    /*update khóa học kỳ thi */
                    if ($model->subject_id && $model->activity_id == 2){
                        Quiz::where('id','=',$model->subject_id)
                            ->update(['course_id'=>$model->course_id, 'course_type'=>1]);

                        $course_register = OnlineRegister::whereCourseId($course_id)->where('status', '=', 1)->get();
                        if ($course_register->count() > 0){
                            $quiz_part = QuizPart::where('quiz_id', '=', $model->subject_id)->first();
                            foreach ($course_register as $register){
                                QuizRegister::query()
                                    ->updateOrCreate([
                                        'quiz_id' => $model->subject_id,
                                        'user_id' => $register->user_id,
                                        'type' => $register->user_type,
                                        'part_id' => $quiz_part->id,
                                    ],[
                                        'quiz_id' => $model->subject_id,
                                        'user_id' => $register->user_id,
                                        'type' => $register->user_type,
                                        'part_id' => $quiz_part->id,
                                    ]);
                            }
                        }
                    }

                    if ($model->subject_id && $model->activity_id == 6){
                        VirtualClassroom::where('id','=',$model->subject_id)
                            ->update(['course_id' => $model->course_id]);
                    }

                    //Cập nhật lại mẫu khảo sát khác khi chưa có user làm bài
                    if($check_update_act_8 != 0 && $model->activity_id == 8){
                        OnlineSurveyTemplate::where('course_activity_id', $model->id)->delete();
                        OnlineSurveyCategory::where('course_activity_id', $model->id)->delete();
                        OnlineSurveyQuestion::where('course_activity_id', $model->id)->delete();
                        OnlineSurveyAnswer::where('course_activity_id', $model->id)->delete();
                        OnlineSurveyAnswerMatrix::where('course_activity_id', $model->id)->delete();

                        $save_activity_survey = OnlineCourseActivitySurvey::firstOrNew([
                            'course_id' => $course_id,
                            'survey_template_id' => $check_update_act_8
                        ]);
                        $save_activity_survey->course_id = $course_id;
                        $save_activity_survey->survey_template_id = $subject_id;
                        $save_activity_survey->description = $request->description;
                        $save_activity_survey->save();

                        $template = SurveyTemplate::find($subject_id)->toArray();

                        $new_template = new OnlineSurveyTemplate();
                        $new_template->fill($template);
                        $new_template->id = $template['id'];
                        $new_template->course_id = $course_id;
                        $new_template->course_activity_id = $model->id;
                        $new_template->save();

                        $categories = SurveyQuestionCategory::query()->where('template_id', $template['id'])->get()->toArray();
                        foreach ($categories as $category){
                            $new_category = new OnlineSurveyCategory();
                            $new_category->fill($category);
                            $new_category->id = $category['id'];
                            $new_category->course_id = $course_id;
                            $new_category->course_activity_id = $model->id;
                            $new_category->save();

                            $questions = SurveyQuestion::query()->where('category_id', $category['id'])->get()->toArray();
                            foreach ($questions as $question){
                                $new_question = new OnlineSurveyQuestion();
                                $new_question->fill($question);
                                $new_question->id = $question['id'];
                                $new_question->course_id = $course_id;
                                $new_question->course_activity_id = $model->id;
                                $new_question->save();

                                $answers = SurveyQuestionAnswer::query()->where('question_id', $question['id'])->get()->toArray();
                                foreach ($answers as $answer){
                                    $new_answer = new OnlineSurveyAnswer();
                                    $new_answer->fill($answer);
                                    $new_answer->id = $answer['id'];
                                    $new_answer->course_id = $course_id;
                                    $new_answer->course_activity_id = $model->id;
                                    $new_answer->save();
                                }

                                $answers_matrix = SurveyAnswerMatrix::query()->where('question_id', $question['id'])->get()->toArray();
                                foreach ($answers_matrix as $answer_matrix){
                                    $new_answer_matrix = new OnlineSurveyAnswerMatrix();
                                    $new_answer_matrix->fill($answer_matrix);
                                    $new_answer_matrix->course_id = $course_id;
                                    $new_answer_matrix->course_activity_id = $model->id;
                                    $new_answer_matrix->save();
                                }
                            }
                        }
                    }

                    $history_edit = new OnlineHistoryEdit();
                    $history_edit->course_id = $course_id;
                    $history_edit->user_id = profile()->user_id;
                    $history_edit->tab_edit = 'Sửa các hoạt động';
                    $history_edit->ip_address = $request->ip();
                    $history_edit->type = 1;
                    $history_edit->save();

                    if($type == 1) {
                        $redirect = route('module.online.course_for_offline.edit_activity_lesson', ['id' => $course_id]);
                    } else {
                        $redirect = route('module.online.edit_activity_lesson', ['id' => $course_id]);
                    }

                    json_result([
                        'status' => 'success',
                        'message' => trans('laother.successful_save'),
                        'redirect' => $redirect
                    ]);
                }
            }
        }

        json_message(trans('laother.save_error'), 'error');
    }

    public function addActivityVideo($course_id, Request $request) {
        $this->validateRequest([
            'path' => 'required_if:path_old,==,null|mimes:mp4'
        ], $request, [
            'path' => 'file phải có đuôi mp4',
        ]);
        $model = OnlineCourseActivityVideo::firstOrNew(['id' => $request->subject_id]);
        if ($request->path_old) {
            $model->path = $request->path_old;
        } else {
            $getID3 = new \getID3;
            $file_time = $getID3->analyze($request->path);
            $playtime_string = $file_time['playtime_string'];

            $file = $request->path;
            $folder_id = '';
            if (empty($folder_id)) {
                $folder_id = null;
            }
            $type = 'file';
            $filename = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $new_filename = Str::slug(basename($filename, "." . $extension)) . '-' . time() . '.' . $extension;

            $storage = \Storage::disk('upload');
            $new_path = $storage->putFileAs(date('Y/m/d'), $file, $new_filename);

            $model->path = $new_path;
            $model->time_play = $playtime_string.'s';
            $model->extension = $file->getClientOriginalExtension();
        }
        $model->description = $request->description;
        $model->required_video_timeout = $request->required_video_timeout;
        $model->course_id = $course_id;

        if ($model->save()) {
            return $model->id;
        }

        return false;
    }
    public function addActivityFile($course_id, Request $request) {
        $this->validateRequest([
            'path' => 'required|string',
        ], $request, [
            'path' => 'Tệp tin',
        ]);

        $model = OnlineCourseActivityFile::firstOrNew(['id' => $request->subject_id]);
        $model->path = path_upload($request->path);
        $model->extension = pathinfo($request->path, PATHINFO_EXTENSION);
        $model->description = $request->description;
        $model->course_id = $course_id;

        if ($model->save()) {
            return $model->id;
        }

        return false;
    }
    public function addActivityUrl($course_id, Request $request) {
        $this->validateRequest([
            'url' => 'required|string',
        ], $request, [
            'url' => 'Url',
        ]);

        $model = OnlineCourseActivityUrl::firstOrNew([
            'id' => $request->input('subject_id')
        ]);
        $model->fill($request->all());
        $model->course_id = $course_id;
        if($request->page) {
            $model->page = 1;
        } else {
            $model->page = 0;
        }

        if ($model->save()) {
            return $model->id;
        }

        return false;
    }
    public function addActivityScorm($course_id, Request $request) {
        $this->validateRequest([
            'path' => 'required|string|max:150',
        ], $request, [
            'path' => 'Scorm',
        ]);

        $scorm_path = path_upload($request->path);
        $scorm = Scorm::firstOrNew(['origin_path' => $scorm_path]);
        $scorm->origin_path = $scorm_path;
        $scorm->save();

        $model = OnlineCourseActivityScorm::firstOrNew([
            'id' => $request->input('subject_id')
        ]);

        $model->fill($request->all());
        $model->path = $scorm_path;
        $model->status_passed = $request->status_passed ? $request->status_passed : 0;
        $model->status_completed = $request->status_completed ? $request->status_completed : 0;
        $model->course_id = $course_id;
        $model->scorm_id = $scorm->id;

        if ($model->save()) {
            return $model->id;
        }

        return false;
    }
    public function addActivityXapi($course_id, Request $request) {
        $this->validateRequest([
            'path' => 'required|string|max:150',
        ], $request, [
            'path' => 'Gói Tin can (Xapi)',
        ]);

        $xapi_path = path_upload($request->path);
        $xapi = Xapi::firstOrCreate([
            'origin_path' => $xapi_path,
        ]);

        $model = OnlineCourseActivityXapi::firstOrNew([
            'id' => $request->input('subject_id')
        ]);

        $model->fill($request->all());
        $model->path = $xapi_path;
        $model->status_passed = $request->status_passed ? $request->status_passed : 0;
        $model->status_completed = $request->status_completed ? $request->status_completed : 0;
        $model->course_id = $course_id;
        $model->xapi_id = $xapi->id;

        if ($model->save()) {
            return $model->id;
        }

        return false;
    }
    public function addActivityZoom($course_id, Request $request) {
        $model = OnlineCourseActivityZoom::firstOrNew(['id' => $request->subject_id]);
        $data =[
            'topic' =>$request->name,
            'start_time' =>$request->start_time,
            'duration' =>$request->duration,
            'alternative_hosts' =>'nvtdien@gmail.com',
        ];
        $zoom = $this->createZoom($data);
        $model->topic = $request->name;
        $model->description = $request->description;
        $model->start_time = date_convert($request->start_time);
        $model->duration = $request->duration;
        $model->course_id = $course_id;
        $model->status = $zoom['data']->status;
        $model->join_url = $zoom['data']->join_url;
        $model->start_url = $zoom['data']->start_url;
        $model->password = $zoom['data']->password;
        $model->zoom_id = $zoom['data']->id;

        if ($model->save()) {
            return $model->id;
        }

        return false;
    }
    public function modalAddActivity($course_id, $type, Request $request) {
        $course = OnlineCourse::findOrFail($course_id);
        $activities = OnlineActivity::where('code', '!=', 'virtualclassroom')->get();
        $lessonId = $request->lessonId;

        return view('online::modal.add_activity', [
            'course' => $course,
            'activities' => $activities,
            'type' => $type,
            'lessonId' => $lessonId,
        ]);
    }

    public function modalActivity($course_id, Request $request) {
        $this->validateRequest([
            'activity' => 'required'
        ], $request);

        $subject_id = $request->input('subject_id');
        $activity = $request->input('activity');
        $lessonId = $request->lessonId;
        $edit = $request->edit;

        if($edit == 1) {
            $get_lesson = OnlineCourseLesson::where('course_id', $course_id)->get();
        } else {
            $get_lesson = OnlineCourseLesson::find($lessonId);
        }

        $course = OnlineCourse::findOrFail($course_id);
        $model = OnlineCourseActivity::firstOrNew(['id' => $request->post('id', null)]);
        $module_class = 'Modules\Online\Entities\OnlineCourseActivity'. ucfirst($activity);
        if ($activity == 'quiz') {
            $module = class_exists($module_class) ? $module_class::firstOrNew(['quiz_id' => $subject_id]) : null;
        } elseif ($activity == 'survey') {
            $module = class_exists($module_class) ? $module_class::firstOrNew(['course_id' => $course_id,'survey_template_id' => $subject_id]) : null;
        } else {
            $module = class_exists($module_class) ? $module_class::firstOrNew(['id' => $subject_id]) : null;
        }
        $model_other = OnlineCourseActivity::whereCourseId($course_id)->where('id', '!=', $request->post('id', null))->get();

        if($request->type) {
            $type = 1;
        } else {
            $type = 0;
        }

        return view('online::modal.add_'. $activity .'_activity', [
            'course' => $course,
            'model' => $model,
            'module' => $module,
            'subject_id' => $subject_id,
            'model_other' => $model_other,
            'type' => $type,
            'get_lesson' => $get_lesson,
            'edit' => $edit,
            'lessonId' => $lessonId,
        ]);
    }

    public function updateNumOrder($course_id, Request $request) {
        $this->validateRequest([
            'num_order' => 'required'
        ], $request);

        $num_orders = $request->num_order;
        foreach ($num_orders as $index => $num_order) {
            OnlineCourseActivity::where('course_id', '=', $course_id)
                ->where('id', '=', $num_order)
                ->update([
                    'num_order' => ($index+1)
                ]);
        }

        json_message('ok');
    }

    public function remove($course_id, Request $request) {
        $this->validateRequest([
            'id' => 'required'
        ], $request);

        $condition = OnlineCourseCondition::whereCourseId($course_id)->first();
        $activity_condition = $condition ? explode(',', $condition->activity) : [];

        if (in_array($request->id, $activity_condition)){
            json_message('Hoạt động đang thiết lập hoàn thành', 'error');
        }

        // Lấy những hoạt động khác trong khoá học. Check xem hoạt động đang xoá có là điều kiện cần hoàn thành của hoạt động khác hay không
        $check_complete_course_activity = OnlineCourseActivity::where('course_id', '=', $course_id)
            ->where('id', '!=', $request->id)
            ->whereNotNull('setting_complete_course_activity_id')
            ->get(['setting_complete_course_activity_id']);

        foreach($check_complete_course_activity as $course_activity){
            $complete_course_activitys = explode(',', $course_activity->setting_complete_course_activity_id);
            if(in_array($request->id, $complete_course_activitys)){
                json_message('Hoạt động là điều kiện hoàn thành của hoạt động khác', 'error');
            }
        }

        $check = OnlineCourseActivity::where('course_id', '=', $course_id)
            ->where('id', '=', $request->id)->first();
        /*if ($check->activity_id == 2){
            Quiz::where('course_id', '=', $course_id)
                ->where('course_type', '=', 1)
                ->where('id', '=', $check->subject_id)
                ->update([
                    'course_id' => 0,
                    'course_type' => 0,
                ]);
        }*/
        if ($check->activity_id == 6){
            VirtualClassroom::where('course_id', '=', $course_id)
                ->where('id', '=', $check->subject_id)
                ->update([
                    'course_id' => 0,
                ]);
        }
        if ($check->activity_id==7){
            $xapiId = OnlineCourseActivityXapi::find($check->subject_id)->xapi_id;
            Xapi::destroy($xapiId);
            OnlineCourseActivityXapi::destroy($check->subject_id);
        }

        if($check->activity_id == 8){
            OnlineSurveyTemplate::where('course_activity_id', $check->id)->delete();
            OnlineSurveyCategory::where('course_activity_id', $check->id)->delete();
            OnlineSurveyQuestion::where('course_activity_id', $check->id)->delete();
            OnlineSurveyAnswer::where('course_activity_id', $check->id)->delete();
            OnlineSurveyAnswerMatrix::where('course_activity_id', $check->id)->delete();

            OnlineCourseActivitySurvey::where(['course_id' => $course_id,'survey_template_id' => $check->subject_id])->delete();
        }

        $check->delete();

        OnlineCourseSettingPercent::whereCourseId($course_id)->where('course_activity_id', $request->id)->delete();

        $history_edit = new OnlineHistoryEdit();
        $history_edit->course_id = $course_id;
        $history_edit->user_id = profile()->user_id;
        $history_edit->tab_edit = 'Xoá các hoạt động';
        $history_edit->ip_address = \request()->ip();
        $history_edit->type = 1;
        $history_edit->save();

        json_message('ok');
    }

    public function updateStatusActivity($course_id, Request $request) {
        $this->validateRequest([
            'id' => 'required'
        ], $request);
        $status = $request->status;

        OnlineCourseActivity::where('course_id', '=', $course_id)
            ->where('id', '=', $request->id)
            ->update([
                'status' => $status,
            ]);

        if ($status == 0){
            $condition = OnlineCourseCondition::where('course_id', '=', $course_id)->first();
            if ($condition && $condition->activity){
                $activity = explode(',', $condition->activity);
                if (array_search($request->id, $activity) !== false){
                    unset($activity[$request->id - 1]);
                }
                $condition->activity = implode(',', $activity);
                $condition->save();
            }
        }

        $history_edit = new OnlineHistoryEdit();
        $history_edit->type = 1;
        $history_edit->course_id = $course_id;
        $history_edit->user_id = profile()->user_id;
        $history_edit->tab_edit = 'Thay đổi trạng thái các hoạt động';
        $history_edit->ip_address = \request()->ip();
        $history_edit->save();

        json_message('ok');
    }

    public function loadData($course_id, $func, Request $request) {
        if ($func) {
            if (method_exists('Modules\Online\Http\Controllers\ActivityController', $func)) {
                $this->{$func}($course_id, $request);
                exit();
            }
        }

        json_message('Yêu cầu không hợp lệ', 'error');
    }

    protected function loadSurveyTemplate($course_id, Request $request) {
        $search = $request->input('search');

        $query = SurveyTemplate::query();
        $query->select(\DB::raw('id, name AS text'));
        $query->where('course', '=', 1);

        if ($search) {
            $query->where('name', 'like', '%'. $search .'%');
        }

        $paginate = $query->paginate(10);
        $data['results'] = $query->get();
        if ($paginate->nextPageUrl()) {
            $data['pagination'] = ['more' => true];
        }

        json_result($data);
    }

    protected function loadQuiz($course_id, Request $request) {
        $search = $request->input('search');
        $query = Quiz::query();
        $query->select(\DB::raw('id, CONCAT(code, \' - \', name) AS text'));
        $query->where('status', '=', 1)
            ->where('quiz_type', '=', 1)
            ->where(function($where) use ($course_id){
                $where->orWhereNull('course_id');
                $where->orWhere('course_id', '=', 0);
                $where->orWhere('course_id', '=', $course_id);
            });

        if ($search) {
            $query->where('name', 'like', '%'. $search .'%');
        }

        $paginate = $query->paginate(10);
        $data['results'] = $query->get();
        if ($paginate->nextPageUrl()) {
            $data['pagination'] = ['more' => true];
        }

        json_result($data);
    }

    protected function loadBBB($course_id, Request $request)
    {
        $search = $request->search;
        $query = VirtualClassroom::query();
        $query->select(
            \DB::raw('id, CONCAT(code, \' - \', name, \' (\', DATE_FORMAT(start_date, \'%H:%i %d/%c/%Y\'), \' - \', DATE_FORMAT(end_date, \'%H:%i %d/%c/%Y\'), \') \') AS text')
        );
        $query->where('start_date', '>=', date('Y-m-d H:i:s'));
        $query->where('status', '=', 1)
            ->where(function($where) use ($course_id){
                $where->orWhere('course_id', '=', 0);
                $where->orWhere('course_id', '=', $course_id);
            });

        if ($search) {
            $query->orWhere('code', 'like', '%'. $search .'%');
            $query->orWhere('name', 'like', '%'. $search .'%');
        }

        $paginate = $query->paginate(10);
        $data['results'] = $query->get();
        if ($paginate->nextPageUrl()) {
            $data['pagination'] = ['more' => true];
        }

        json_result($data);
    }

    //Chỉnh sửa tên bài học
    public function editLessonNameActivity($course_id, Request $request){
        $model = OnlineCourseLesson::find($request->id);
        $model->lesson_name = $request->name ?? '';
        if ($model->save()) {
            json_message('Thay đổi thành công', 'success');
        } else {
            json_message('lỗi', 'error');
        }
    }

    // CHỈNH SỬA TÊN HOẠT ĐỘNG
    public function editNameActivity($id, Request $request)
    {
        $model = OnlineCourseActivity::find($request->id);
        $model->name = $request->name;
        if ($model->save()) {
            json_message('Thay đổi thành công', 'success');
        } else {
            json_message('lỗi', 'error');
        }
    }

    // ĐỔI HOẠT ĐỘNG SANG BÀI HỌC KHÁC
    public function updateLesson(Request $request)
    {
        $model = OnlineCourseActivity::find($request->id);
        $model->lesson_id = $request->lesson_id;
        if ($model->save()) {
            json_message('Thay đổi thành công', 'success');
        } else {
            json_message('lỗi', 'error');
        }
    }

    // TÌM KIẾM HOẠT ĐỘNGTHEO BÀI HỌC
    public function searchActivity($course_id, Request $request)
    {
        $model = OnlineCourseActivity::where('course_id', $course_id)->where('name', 'like', '%'. $request->search .'%');
        $lesson_id = $model->pluck('lesson_id')->toArray();
        $activity_id = $model->pluck('id')->toArray();
        json_result([
            'lesson_id' => $lesson_id,
            'activity_id' => $activity_id,
        ]);
    }
}
