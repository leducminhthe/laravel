<?php

namespace Modules\Offline\Http\Controllers;

use App\Models\CourseTabEdit;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Models\Categories\Unit;
use App\Traits\TeamsMeetingTrait;
use App\Traits\ZoomMeetingTrait;
use Carbon\Carbon;
use Modules\Offline\Entities\OfflineActivity;
use Modules\Offline\Entities\OfflineCourse;
use Modules\Offline\Entities\OfflineCourseActivity;
use Modules\Offline\Entities\OfflineCourseActivityFile;
use Modules\Offline\Entities\OfflineCourseActivityOnline;
use Modules\Offline\Entities\OfflineCourseActivityScorm;
use Modules\Offline\Entities\OfflineCourseActivityTeams;
use Modules\Offline\Entities\OfflineCourseActivityUrl;
use Modules\Offline\Entities\OfflineCourseActivityVideo;
use Modules\Offline\Entities\OfflineCourseActivityXapi;
use Modules\Offline\Entities\OfflineCourseActivityZoom;
use Modules\Offline\Entities\OfflineCourseClass;
use Modules\Offline\Entities\OfflineCourseLesson;
use Modules\Offline\Entities\OfflineSchedule;
use Modules\Offline\Entities\OfflineScorm;
use Modules\Offline\Entities\OfflineTeamsAttendanceReport;
use Modules\Offline\Entities\OfflineTeamsReport;
use Modules\Offline\Entities\OfflineXapi;
use Modules\Online\Entities\OnlineCourse;
use Modules\Online\Entities\OnlineHistoryEdit;
use Modules\Offline\Exports\ReportTeamsExport;
use Modules\Quiz\Entities\Quiz;
use Modules\Offline\Entities\OfflineActivityQuiz;
use Modules\Offline\Entities\OfflineCourseActivityCondition;
use Modules\Offline\Entities\OfflineCourseActivitySurvey;
use Modules\Offline\Entities\OfflineRegister;
use Modules\Offline\Entities\OfflineRegisterView;
use Modules\Offline\Entities\OfflineSurveyAnswer;
use Modules\Offline\Entities\OfflineSurveyAnswerMatrix;
use Modules\Offline\Entities\OfflineSurveyCategory;
use Modules\Offline\Entities\OfflineSurveyQuestion;
use Modules\Offline\Entities\OfflineSurveyTemplate;
use Modules\Quiz\Entities\QuizPart;
use Modules\Quiz\Entities\QuizRegister;
use Modules\Quiz\Entities\QuizResult;
use Modules\Survey\Entities\SurveyAnswerMatrix;
use Modules\Survey\Entities\SurveyQuestion;
use Modules\Survey\Entities\SurveyQuestionAnswer;
use Modules\Survey\Entities\SurveyQuestionCategory;
use Modules\Survey\Entities\SurveyTemplate;

class ActivityController extends Controller
{
    use ZoomMeetingTrait;
    use TeamsMeetingTrait;

    public function form(Request $request) {
        $course_id = $request->route('id');
        $course = OfflineCourse::findOrFail($course_id);
        $page_title = $course->name;

        $zoomLink= function ($id){
            $zoomActivity = OfflineCourseActivityZoom::findOrFail($id);
            return $zoomActivity->start_url;
        };

        $class = OfflineCourseClass::where(['default'=>1,'course_id'=>$course_id])->first();
        $permission_save = true;

        $activitieOnlines = OfflineCourseActivity::getByCourse($course_id);
        $activitieTeams = OfflineCourseActivity::where(['activity_id' => 6, 'course_id' => $course_id])->get();

        $activity_teams = function ($teams_id){
            return OfflineCourseActivityTeams::where(['id'=>$teams_id])->first();
        };
        return view('offline::backend.offline.form',[
            'page_title' => $page_title,
            'course'=>$course,
            'model'=>$course,
            'zoomLink'=>$zoomLink,
            'permission_save'=>$permission_save,
            'class'=>$class,
            'activitieOnlines' => $activitieOnlines,
            'activitieTeams' => $activitieTeams,
            'activity_teams' => $activity_teams,
        ]);
    }

    public function activityBySchedule($course_id, $class_id, $schedule_id, Request $request){
        $course = OfflineCourse::find($course_id, ['id', 'name', 'lock_course']);
        $class = OfflineCourseClass::find($class_id);
        $schedule = OfflineSchedule::find($schedule_id, ['id', 'session', 'end_date', 'end_time']);

        $anotherClass = OfflineCourseClass::where(['course_id'=>$course_id])->where('id','<>',$class_id)->get();
        $classArray = [];
        foreach ($anotherClass as $item) {
            $classArray[]=["name"=>$item->name,"url"=> route("module.offline.schedule",['id'=>$course_id,'class_id'=>$item->id])];
        }

        $activitieOnlines = OfflineCourseActivity::getByCourse($course_id, $class_id, $schedule_id);
        $conditions = OfflineCourseActivityCondition::where(['course_id' => $course_id, 'class_id' => $class_id, 'schedule_id' => $schedule_id])->pluck('course_activity_id')->toArray();

        $lessons = OfflineCourseLesson::where(['course_id' => $course_id, 'class_id' => $class_id, 'schedule_id' => $schedule_id])->get();
        foreach ($lessons as $lesson) {
            $lesson->activities = OfflineCourseActivity::getActivitiesByCourseLesson($lesson->id, $course_id);
        }

        $check_save = (get_date($schedule->end_date, 'Y-m-d') .' '. get_date($schedule->end_time, 'H:i:s')) < date('Y-m-d H:i:s') ? false : true;

        return view('offline::backend.schedule.activity',[
            'course' => $course,
            'class' => $class,
            'schedule' => $schedule,
            'classArray' => $classArray,
            'activitieOnlines' => $activitieOnlines,
            'conditions' => $conditions,
            'lessons' => $lessons,
            'check_save' => $check_save,
        ]);
    }

    public function modalAddActivity($course_id, Request $request){
        $course = OfflineCourse::findOrFail($course_id);
        $activities = OfflineActivity::where('id', '!=', 6)->get();
        $class_id = $request->class_id;
        $schedule_id = $request->schedule_id;
        $lesson_id = $request->lessonId;

        return view('offline::modal.add_activity', [
            'course' => $course,
            'activities' => $activities,
            'class_id' => $class_id,
            'schedule_id' => $schedule_id,
            'lesson_id' => $lesson_id,
        ]);
    }

    public function modalActivity($course_id, Request $request) {
        $this->validateRequest([
            'activity' => 'required'
        ], $request);

        $class_id = $request->class_id;
        $schedule_id = $request->schedule_id;
        $lesson_id = $request->lesson_id;
        $subject_id = $request->input('subject_id');
        $activity = $request->input('activity');

        $course = OfflineCourse::findOrFail($course_id);
        $model = OfflineCourseActivity::firstOrNew(['id' => $request->post('id', null)]);

        $module_class = 'Modules\Offline\Entities\OfflineCourseActivity'. ucfirst($activity);
        if ($activity == 'survey') {
            $module = class_exists($module_class) ? $module_class::firstOrNew(['course_id' => $course_id,'survey_template_id' => $subject_id]) : null;
        } else {
            $module = class_exists($module_class) ? $module_class::firstOrNew(['id' => $subject_id]) : null;
        }
        $model_other = OfflineCourseActivity::whereCourseId($course_id)->where('id', '!=', $request->post('id', null))->get();

        $checkOnlineActivityIsset = OfflineCourseActivity::where(['course_id' => $course_id, 'activity_id' => 1])->pluck('subject_id')->toArray();

        $online_courses = OnlineCourse::where('offline', 1)->where('status', 1)->where('isopen', 1)->whereNotIn('id', $checkOnlineActivityIsset)->get(['id', 'code', 'name']);
        $list_class = OfflineCourseClass::where(['course_id'=>$course_id])->get();

        $schedule = '';
        if($module){
            $schedule = OfflineSchedule::where('course_id', '=', $course_id)->where('class_id', $module->class_id)->where('id', $module->schedule_id)->first();
        }

        return view('offline::modal.add_'. $activity .'_activity', [
            'course' => $course,
            'model' => $model,
            'module' => $module,
            'subject_id' => $subject_id,
            'model_other' => $model_other,
            'online_courses' => $online_courses,
            'list_class' => $list_class,
            'schedule' => $schedule,
            'class_id' => $class_id,
            'schedule_id' => $schedule_id,
            'lesson_id' => $lesson_id,
        ]);
    }

    public function saveActivity($course_id, $activity_id, Request $request) {
        $this->validateRequest([
            'name' => 'required',
        ], $request, [
            'name' => 'Tên hoạt động',
        ]);

        $class_id = $request->class_id;
        $schedule_id = $request->schedule_id;
        $lesson_id = $request->lesson_id;
        $subject_id = $request->subject_id;

        if($activity_id == 6 && empty($request->post('id', null))){
            $acti_teams = OfflineCourseActivityTeams::whereCourseId($course_id)->where('class_id', $request->class_id)->where('schedule_id', $request->schedule_id);

            if ($acti_teams->exists()){
                json_message('Buổi học hoạt động Ms Teams của lớp đã thêm. Mời chọn buổi học khác', 'error');
            }
        }

        $check_quiz_acvity_class = OfflineActivityQuiz::where(['course_id' => $course_id, 'class_id' => $class_id])->exists();
        if($check_quiz_acvity_class && empty($request->id) && $activity_id == 7) {
            json_message('Lớp đã có hoạt động kỳ thi.', 'error');
        }

        if($activity_id == 8 && !$subject_id){
            json_message('Chưa chọn mẫu khảo sát.', 'error');
        }

        $activity = OfflineActivity::findOrFail($activity_id);
        $namespace = 'Modules\Offline\Http\Controllers\ActivityController';

        if (method_exists($namespace, 'addActivity'. ucfirst($activity->code))) {
            if ($activity_id != 6)
                $subject_id = $this->{'addActivity'. ucfirst($activity->code)}($course_id, $request);
        }

        if ($subject_id) {
            $model = OfflineCourseActivity::firstOrNew(['id' => $request->post('id', null)]);

            $check_update_act_8 = 0;
            if($model->id){
                if($model->subject_id != $subject_id && $model->activity_id == 8){
                    $acti_survey = OfflineCourseActivity::where('course_id', '=', $course_id)
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

            if (empty($model->id)) {
                $acti_survey = OfflineCourseActivity::where('course_id', '=', $course_id)
                ->where('activity_id', '=', 8)
                ->where('subject_id', '=', $subject_id)
                ->first();
                if ($acti_survey && $activity_id == 8){
                    json_message('Hoạt động Khảo sát đã thêm mẫu. Mời chọn mẫu khảo sát khác', 'error');
                }

                $num_order = (int) OfflineCourseActivity::query()->where('course_id', '=', $course_id)->max('num_order') + 1;

                $model->name = $request->name;
                $model->course_id = $course_id;
                $model->activity_id = $activity_id;
                $model->subject_id = $subject_id;
                $model->num_order = $num_order;
                $model->lesson_id = $lesson_id ?? 1;
                $model->status = 1;

                if ($model->save()) {

                    if($model->subject_id && $model->activity_id == 8){
                        $save_activity_survey = OfflineCourseActivitySurvey::firstOrNew([
                            'course_id' => $course_id,
                            'survey_template_id' => $subject_id
                        ]);
                        $save_activity_survey->course_id = $course_id;
                        $save_activity_survey->survey_template_id = $subject_id;
                        $save_activity_survey->description = $request->description;
                        $save_activity_survey->save();

                        $template = SurveyTemplate::find($subject_id)->toArray();

                        $new_template = new OfflineSurveyTemplate();
                        $new_template->fill($template);
                        $new_template->id = $template['id'];
                        $new_template->course_id = $course_id;
                        $new_template->course_activity_id = $model->id;
                        $new_template->save();

                        $categories = SurveyQuestionCategory::query()->where('template_id', $template['id'])->get()->toArray();
                        foreach ($categories as $category){
                            $new_category = new OfflineSurveyCategory();
                            $new_category->fill($category);
                            $new_category->id = $category['id'];
                            $new_category->course_id = $course_id;
                            $new_category->course_activity_id = $model->id;
                            $new_category->save();

                            $questions = SurveyQuestion::query()->where('category_id', $category['id'])->get()->toArray();
                            foreach ($questions as $question){
                                $new_question = new OfflineSurveyQuestion();
                                $new_question->fill($question);
                                $new_question->id = $question['id'];
                                $new_question->course_id = $course_id;
                                $new_question->course_activity_id = $model->id;
                                $new_question->save();

                                $answers = SurveyQuestionAnswer::query()->where('question_id', $question['id'])->get()->toArray();
                                foreach ($answers as $answer){
                                    $new_answer = new OfflineSurveyAnswer();
                                    $new_answer->fill($answer);
                                    $new_answer->id = $answer['id'];
                                    $new_answer->course_id = $course_id;
                                    $new_answer->course_activity_id = $model->id;
                                    $new_answer->save();
                                }

                                $answers_matrix = SurveyAnswerMatrix::query()->where('question_id', $question['id'])->get()->toArray();
                                foreach ($answers_matrix as $answer_matrix){
                                    $new_answer_matrix = new OfflineSurveyAnswerMatrix();
                                    $new_answer_matrix->fill($answer_matrix);
                                    $new_answer_matrix->course_id = $course_id;
                                    $new_answer_matrix->course_activity_id = $model->id;
                                    $new_answer_matrix->save();
                                }
                            }
                        }
                    }

                    $history_edit = new OnlineHistoryEdit();
                    $history_edit->type = 2;
                    $history_edit->course_id = $course_id;
                    $history_edit->user_id = profile()->user_id;
                    $history_edit->tab_edit = 'Thêm hoạt động: '. $model->name;
                    $history_edit->ip_address = $request->ip();
                    $history_edit->save();

                    json_result([
                        'status' => 'success',
                        'message' => trans('laother.successful_save'),
                        'redirect' => route('module.offline.activity_by_schedule', ['course_id' => $course_id,'class_id'=>$class_id,'schedule_id'=>$schedule_id])
                        // 'redirect' => route('module.offline.edit_activity_lesson', [$course_id])
                    ]);
                }
            } else {

                $model->name = $request->name;
                $model->subject_id = $subject_id;
                $model->status = 1;

                if ($model->save()) {

                    //Cập nhật lại mẫu khảo sát khác khi chưa có user làm bài
                    if($check_update_act_8 != 0 && $model->activity_id == 8){
                        OfflineSurveyTemplate::where('course_activity_id', $model->id)->delete();
                        OfflineSurveyCategory::where('course_activity_id', $model->id)->delete();
                        OfflineSurveyQuestion::where('course_activity_id', $model->id)->delete();
                        OfflineSurveyAnswer::where('course_activity_id', $model->id)->delete();
                        OfflineSurveyAnswerMatrix::where('course_activity_id', $model->id)->delete();

                        $save_activity_survey = OfflineCourseActivitySurvey::firstOrNew([
                            'course_id' => $course_id,
                            'survey_template_id' => $check_update_act_8
                        ]);
                        $save_activity_survey->course_id = $course_id;
                        $save_activity_survey->survey_template_id = $subject_id;
                        $save_activity_survey->description = $request->description;
                        $save_activity_survey->save();

                        $template = SurveyTemplate::find($subject_id)->toArray();

                        $new_template = new OfflineSurveyTemplate();
                        $new_template->fill($template);
                        $new_template->id = $template['id'];
                        $new_template->course_id = $course_id;
                        $new_template->course_activity_id = $model->id;
                        $new_template->save();

                        $categories = SurveyQuestionCategory::query()->where('template_id', $template['id'])->get()->toArray();
                        foreach ($categories as $category){
                            $new_category = new OfflineSurveyCategory();
                            $new_category->fill($category);
                            $new_category->id = $category['id'];
                            $new_category->course_id = $course_id;
                            $new_category->course_activity_id = $model->id;
                            $new_category->save();

                            $questions = SurveyQuestion::query()->where('category_id', $category['id'])->get()->toArray();
                            foreach ($questions as $question){
                                $new_question = new OfflineSurveyQuestion();
                                $new_question->fill($question);
                                $new_question->id = $question['id'];
                                $new_question->course_id = $course_id;
                                $new_question->course_activity_id = $model->id;
                                $new_question->save();

                                $answers = SurveyQuestionAnswer::query()->where('question_id', $question['id'])->get()->toArray();
                                foreach ($answers as $answer){
                                    $new_answer = new OfflineSurveyAnswer();
                                    $new_answer->fill($answer);
                                    $new_answer->id = $answer['id'];
                                    $new_answer->course_id = $course_id;
                                    $new_answer->course_activity_id = $model->id;
                                    $new_answer->save();
                                }

                                $answers_matrix = SurveyAnswerMatrix::query()->where('question_id', $question['id'])->get()->toArray();
                                foreach ($answers_matrix as $answer_matrix){
                                    $new_answer_matrix = new OfflineSurveyAnswerMatrix();
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
                    $history_edit->type = 2;
                    $history_edit->save();

                    json_result([
                        'status' => 'success',
                        'message' => trans('laother.successful_save'),
                        'redirect' => route('module.offline.activity_by_schedule', ['course_id' => $course_id,'class_id'=>$class_id,'schedule_id'=>$schedule_id])
                        // 'redirect' => route('module.offline.edit_activity_lesson', [$course_id])
                    ]);
                }
            }
        }

        json_message(trans('laother.save_error'), 'error');
    }

    public function addActivityScorm($course_id, Request $request) {
        $this->validateRequest([
            'path' => 'required|string|max:150',
        ], $request, [
            'path' => 'Scorm',
        ]);

        $scorm_path = path_upload($request->path);
        $scorm = OfflineScorm::firstOrNew(['origin_path' => $scorm_path]);
        $scorm->origin_path = $scorm_path;
        $scorm->save();

        $model = OfflineCourseActivityScorm::firstOrNew([
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
    public function addActivityVideo($course_id, Request $request) {
        $this->validateRequest([
            'path' => 'required_if:path_old,==,null|mimes:mp4'
        ], $request, [
            'path' => 'file phải có đuôi mp4',
        ]);
        $model = OfflineCourseActivityVideo::firstOrNew(['id' => $request->subject_id]);
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
            $new_filename = \Str::slug(basename($filename, "." . $extension)) . '-' . time() . '.' . $extension;

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

        $model = OfflineCourseActivityFile::firstOrNew(['id' => $request->subject_id]);
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

        $model = OfflineCourseActivityUrl::firstOrNew([
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
    public function addActivityXapi($course_id, Request $request) {
        $this->validateRequest([
            'path' => 'required|string|max:150',
        ], $request, [
            'path' => 'Gói Tin can (Xapi)',
        ]);

        $xapi_path = path_upload($request->path);
        $xapi = OfflineXapi::firstOrCreate([
            'origin_path' => $xapi_path,
        ]);

        $model = OfflineCourseActivityXapi::firstOrNew([
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
    public function addActivityTeams($course_id, Request $request) {
        $class_id = $request->class_id;
        $schedule_id = $request->schedule_id;

        $schedule = OfflineSchedule::where('course_id', '=', $course_id)->where('class_id', $class_id)->where('id', $schedule_id)->first();
        $start_time = get_date($schedule->lesson_date) .' '. $schedule->start_time;
        $end_time = get_date($schedule->lesson_date) .' '. $schedule->end_time;

        $start = Carbon::parse(get_date($schedule->start_time, 'H:i'));
        $end = Carbon::parse(get_date($schedule->end_time, 'H:i'));
        $duration = $end->diffInHours($start);

        $model = OfflineCourseActivityTeams::firstOrNew(['id' => $request->subject_id]);

        $data =[
            'subject' => $request->name,
            'start_time' => $start_time,
            'duration' => $duration*60,
            'end_time' => $end_time,
        ];
        if ($model->exists){
            if (time()>=strtotime($model->start_time)){
                return json_message(trans('latraining.teams_message_error_update'),'error');
            }
            $teams = $this->updateEvent($model->event_id,$data);
        }
        else
            $teams = $this->createEvent($data);
        $model->topic = $request->name;
        $model->description = $request->description;
        $model->start_time = datetime_convert($start_time);
        $model->end_time = datetime_convert($end_time);
        $model->duration = $duration;
        $model->course_id = $course_id;
        $model->class_id = $class_id;
        $model->schedule_id = $schedule_id;
        $model->join_url = $teams['data']->joinUrl;
        $model->join_web_url = $teams['data']->joinWebUrl;
        $model->meeting_code = $teams['data']->meetingCode;
        $model->teams_id = $teams['data']->id;
        $model->event_id = $teams['event_id'];

        if ($model->save()) {
            return $model->id;
        }

        return false;
    }
    public function addActivityZoom($course_id, Request $request) {
        $class_id = $request->class_id;
        $schedule_id = $request->schedule_id;

        $schedule = OfflineSchedule::where('course_id', '=', $course_id)->where('class_id', $class_id)->where('id', $schedule_id)->first();
        $start_time = get_date($schedule->lesson_date) .' '. $schedule->start_time;
        $end_time = get_date($schedule->lesson_date) .' '. $schedule->end_time;

        $start = Carbon::parse(get_date($schedule->start_time, 'H:i'));
        $end = Carbon::parse(get_date($schedule->end_time, 'H:i'));
        $duration = $end->diffInHours($start);

        $model = OfflineCourseActivityZoom::firstOrNew(['id' => $request->subject_id]);
        $data = [
            'topic' => $request->name,
            'start_time' => $start_time,
            'duration' => $duration,
            'alternative_hosts' =>'nvtdien@gmail.com',
        ];

        $zoom = $this->createZoom($data);

        $model->topic = $request->name;
        $model->description = $request->description;
        $model->start_time = datetime_convert($start_time);
        $model->end_time = datetime_convert($end_time);
        $model->duration = $duration;
        $model->course_id = $course_id;
        $model->course_id = $course_id;
        $model->class_id = $class_id;
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
    public function addActivityQuiz($course_id, Request $request) {
        $this->validateRequest([
            'subject_id' => 'required'
        ], $request, [
            'subject_id' => 'Kỳ thi',
        ]);

        $subject_id = $request->subject_id;
        $description = $request->description;

        $class_id = $request->class_id;
        $schedule_id = $request->schedule_id;

        $class = OfflineCourseClass::find($class_id);
        $offline_schedule = OfflineSchedule::find($schedule_id);

        if($request->id) {
            $old_quiz = OfflineActivityQuiz::where(['course_id' => $course_id, 'class_id' => $class_id, 'schedule_id' => $schedule_id])->first(['quiz_id']);
            if($old_quiz->quiz_id != $subject_id) {
                QuizRegister::where('quiz_id', $old_quiz->quiz_id)->delete();
            }
        }

        $model = OfflineActivityQuiz::firstOrNew(['course_id' => $course_id, 'class_id' => $class_id, 'schedule_id' => $schedule_id]);
        $model->course_id = $course_id;
        $model->class_id = $class_id;
        $model->schedule_id = $schedule_id;
        $model->quiz_id = $subject_id;
        $model->description = $description;

        if ($model->save()) {
            $quiz_part = QuizPart::firstOrNew(['quiz_id' => $model->quiz_id]);
            $quiz_part->quiz_id = $model->quiz_id;
            $quiz_part->name = $class->name . ' - Buổi '. $offline_schedule->session;
            $quiz_part->start_date = get_date($offline_schedule->lesson_date, 'Y-m-d') .' '. get_date($offline_schedule->start_time, 'H:i:s');
            $quiz_part->end_date = get_date($offline_schedule->end_date, 'Y-m-d') .' '. get_date($offline_schedule->end_time, 'H:i:s');
            $quiz_part->save();


            $course_register = OfflineRegister::whereCourseId($course_id)->where('class_id', $class_id)->where('status', '=', 1)->get();
            if ($course_register->count() > 0) {
                foreach ($course_register as $register) {
                    QuizRegister::query()
                        ->updateOrCreate([
                            'quiz_id' => $model->quiz_id,
                            'user_id' => $register->user_id,
                            'type' => 1,
                            'part_id' => $quiz_part->id,
                        ], [
                            'quiz_id' => $model->quiz_id,
                            'user_id' => $register->user_id,
                            'type' => 1,
                            'part_id' => $quiz_part->id,
                        ]);
                }
            }
            return $model->id;
        }

        return false;
    }

    public function updateNumOrder($course_id, Request $request) {
        $this->validateRequest([
            'num_order' => 'required'
        ], $request);

        $num_orders = $request->num_order;
        foreach ($num_orders as $index => $num_order) {
            OfflineCourseActivity::where('course_id', '=', $course_id)
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

        $class_id = $request->class_id;
        $schedule_id = $request->schedule_id;

        $activity_condition = OfflineCourseActivityCondition::where(['course_id' => $course_id, 'class_id' => $class_id, 'schedule_id' => $schedule_id])->pluck('course_activity_id')->toArray();
        if (in_array($request->id, $activity_condition)){
            json_message('Hoạt động đang thiết lập hoàn thành', 'error');
        }

        $check = OfflineCourseActivity::where('course_id', '=', $course_id)->where('id', '=', $request->id)->first();

        if ($check->activity_id == 1){
            OfflineCourseActivityOnline::where('course_id', $course_id)
                ->where('online_id', $check->subject_id)
                ->delete();
        }
        if ($check->activity_id == 6){
            $activity = OfflineCourseActivityTeams::find($check->subject_id);
            $teams_id = $activity->teams_id;
            $startTime = $activity->start_time;
            if (time()>=strtotime($startTime)){
                return json_message(trans('latraining.teams_message_error_update'),'error');
            }
            OfflineCourseActivityTeams::destroy($check->subject_id);
            $this->deleteTeams($teams_id);
        }
        // if ($check->activity_id == 3){
        //     $zoom_id = OfflineCourseActivityZoom::find($check->subject_id)->zoom_id;
        //     OfflineCourseActivityZoom::destroy($check->subject_id);
        //     $this->deleteZoom($zoom_id);
        // }

        if($check->activity_id == 8){
            OfflineSurveyTemplate::where('course_activity_id', $check->id)->delete();
            OfflineSurveyCategory::where('course_activity_id', $check->id)->delete();
            OfflineSurveyQuestion::where('course_activity_id', $check->id)->delete();
            OfflineSurveyAnswer::where('course_activity_id', $check->id)->delete();
            OfflineSurveyAnswerMatrix::where('course_activity_id', $check->id)->delete();

            OfflineCourseActivitySurvey::where(['course_id' => $course_id,'survey_template_id' => $check->subject_id])->delete();
        }

        if($check->activity_id == 7){
            $old_quiz = OfflineActivityQuiz::find($check->subject_id);
            QuizRegister::where('quiz_id', $old_quiz->quiz_id)->delete();
            QuizResult::where('quiz_id', $old_quiz->quiz_id)->delete();
            $old_quiz->delete();
        }

        $check->delete();

        $history_edit = new OnlineHistoryEdit();
        $history_edit->course_id = $course_id;
        $history_edit->user_id = profile()->user_id;
        $history_edit->tab_edit = 'Xoá các hoạt động';
        $history_edit->ip_address = \request()->ip();
        $history_edit->type = 2;
        $history_edit->save();

        json_message('ok');
    }

    public function updateStatusActivity($course_id, Request $request) {
        $this->validateRequest([
            'id' => 'required'
        ], $request);
        $status = $request->status;

        OfflineCourseActivity::where('course_id', '=', $course_id)
            ->where('id', '=', $request->id)
            ->update([
                'status' => $status,
            ]);

        $history_edit = new OnlineHistoryEdit();
        $history_edit->type = 2;
        $history_edit->course_id = $course_id;
        $history_edit->user_id = profile()->user_id;
        $history_edit->tab_edit = 'Thay đổi trạng thái các hoạt động';
        $history_edit->ip_address = \request()->ip();
        $history_edit->save();

        json_message('ok');
    }

    public function updateConditionActivity($course_id, $class_id, $schedule_id, Request $request){
        $course_activity_id = $request->id;

        $check = OfflineCourseActivityCondition::where(['course_id' => $course_id, 'class_id' => $class_id, 'schedule_id' => $schedule_id, 'course_activity_id' => $course_activity_id])->first();
        if($check){
            $check->delete();
        }else{
            $model = new OfflineCourseActivityCondition();
            $model->course_id = $course_id;
            $model->class_id = $class_id;
            $model->schedule_id = $schedule_id;
            $model->course_activity_id = $course_activity_id;
            $model->save();
        }

        json_message('Cập nhật thành công');
    }

    public function loadData($course_id, $func, Request $request) {
        if ($func) {
            if (method_exists('Modules\Offline\Http\Controllers\ActivityController', $func)) {
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
            ->where('quiz_type', '=', 2)
            ->where('quiz_type_by_offline', '=', 'activity_quiz_id')
            ->where('course_id', '=', $course_id)
            ->where('course_type', '=', 2);

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

    public function loadSchedule($course_id, Request $request){
        $class_id = $request->class_id;

        $query = OfflineSchedule::query();
        $query->where('course_id', '=', $course_id);
        $query->where('class_id', $class_id);

        $rows = $query->get();
        foreach($rows as $row){
            $row->start_time = get_date($row->start_time, 'H:i');
            $row->end_time = get_date($row->end_time, 'H:i');
            $row->lesson_date = get_date($row->lesson_date);
        }

        $data['results'] = $rows;

        json_result($data);
    }

    // CHỈNH SỬA TÊN HOẠT ĐỘNG
    public function editNameActivity($id, Request $request)
    {
        $model = OfflineCourseActivity::find($request->id);
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
        $model = OfflineCourseActivity::find($request->id);
        $model->lesson_id = $request->lesson_id;
        if ($model->save()) {
            json_message('Thay đổi thành công', 'success');
        } else {
            json_message('lỗi', 'error');
        }
    }

    public function saveLesson($course_id, Request $request)
    {
        // $this->validateRequest([
        //     'lesson_name' => 'required',
        // ], $request, OfflineCourseLesson::getAttributeName());

        $class_id = $request->class_id;
        $schedule_id = $request->schedule_id;
        $lesson_name = $request->input('lesson_name');

        $lesson_count = OfflineCourseLesson::where(['course_id' => $course_id, 'class_id' => $class_id, 'schedule_id' => $schedule_id])->count();

        $model = new OfflineCourseLesson();
        $model->lesson_name = $lesson_name ?? 'Chủ đề '.($lesson_count + 1);
        $model->course_id = $course_id;
        $model->class_id = $class_id;
        $model->schedule_id = $schedule_id;
        $model->save();

        $course_edit = CourseTabEdit::firstOrNew(['course_id' => $course_id, 'course_type' => 2, 'tab_edit' => 'activity-lesson']);
        $course_edit->course_id = $course_id;
        $course_edit->tab_edit = 'activity-lesson';
        $course_edit->course_type = 2;
        $course_edit->save();

        json_result([
            'status' => 'success',
            'message' => 'Thêm thành công',
            'redirect' => route('module.offline.activity_by_schedule', [$course_id, $class_id, $schedule_id]),
        ]);
    }

    //Chỉnh sửa tên bài học
    public function editLessonNameActivity($course_id, Request $request){
        $model = OfflineCourseLesson::find($request->id);
        $model->lesson_name = $request->name ?? '';

        if ($model->save()) {
            json_message('Thay đổi thành công', 'success');
        } else {
            json_message('lỗi', 'error');
        }
    }

    public function removeLesson(Request $request)
    {
        $this->validateRequest([
            'id' => 'required',
        ], $request, [
            'id' => 'Bài học',
        ]);
        $checkActivity = OfflineCourseActivity::where('lesson_id', $request->id)->exists();
        if($checkActivity){
            json_result([
                'status' => 'error',
                'message' => 'Xóa thất bại vì bài học có chứa học phần',
            ]);
        }
        OfflineCourseLesson::find($request->id)->delete();
        json_result([
            'status' => 'success',
            'message' => trans('laother.delete_success'),
        ]);
    }

    public function reportTeams(Request $request)
    {
        $course_id = $request->route('course_id');
        $class_id = $request->route('class_id');
        $schedule_id = $request->route('schedule_id');

        if ($request->ajax()){
            $search = $request->input('search');
            $report_id = $request->input('report_id');
            $sort = $request->input('sort', 'full_name');
            $order = $request->input('order', 'desc');
            $offset = $request->input('offset', 0);
            $limit = $request->input('limit', 20);

            $query = OfflineTeamsAttendanceReport::where(['course_id' => $course_id,'schedule_id'=>$schedule_id]);

            if(isset($report_id)) {
                $report = OfflineTeamsReport::find($report_id);
                $query->where('report_id', $report->report_id);
            }

            $count = $query->count();
            $query->orderBy($sort, 'asc');
            $query->offset($offset);
            $query->limit($limit);

            $rows = $query->get();
            foreach ($rows as $index => $row) {
                $row->join_time = get_datetime($row->join_time);
                $row->leave_time = get_datetime($row->leave_time);
                $row->duration = gmdate("H:i:s", $row->duration);
            }
            return json_result(['total' => $count, 'rows' => $rows]);
        }else {
            $course = OfflineCourse::findOrFail($course_id);
            $class = OfflineCourseClass::findOrFail($class_id);
            $offlineActivityTeams = OfflineCourseActivityTeams::where(['course_id'=>$course_id,'class_id'=>$class_id,'schedule_id'=>$schedule_id])->first();
            $report = OfflineTeamsReport::where(['course_id'=>$course_id,'teams_id'=>$offlineActivityTeams->teams_id])->first();
            $all_report = OfflineTeamsReport::where(['course_id' => $course_id, 'class_id' => $class_id, 'schedule_id' => $schedule_id])->get(['id','meeting_start']);
            return view('offline::backend.teams_report.index', [
                'course' => $course,
                'class' => $class,
                'class_id' => $class_id,
                'schedule_id' => $schedule_id,
                'report' => $report,
                'all_report' => $all_report
            ]);
        }
    }

    // THÔNG TIN BÁO CÁO
    public function reportTeamsInfo(Request $request) {
        $report = OfflineTeamsReport::find($request->reportId);
        $time = get_datetime($report->meeting_start) . ' - ' . get_datetime($report->meeting_end);

        $seconds = strtotime($report->meeting_end) - strtotime($report->meeting_start);
        $hours   = floor(($seconds - ($days * 86400)) / 3600);
        $minutes = floor(($seconds - ($days * 86400) - ($hours * 3600))/60);
        $seconds = floor(($seconds - ($days * 86400) - ($hours * 3600) - ($minutes*60)));
        $duration = ($hours ? $hours. 'h ' : '') . ($minutes ? $minutes. 'm ' : '') . ($seconds ? $seconds. 's' : '');

        json_result([
            'report' => $report,
            'time' => $time,
            'duration' => $duration
        ]);
    }

    // EXPORT REPORT TEAMS
    public function exportReportTeamsInfo($id) {
        $report = OfflineTeamsReport::find($id);
        return (new ReportTeamsExport($report))->download('bao_cao_teams_'. date('d_m_Y') .'.xlsx');
    }

    // UPDATE REPORT TEAMS
    public function updateReportTeamsInfo(Request $request) {
        $course_id = $request->courseId;
        $class_id = $request->classId;
        $schedule_id = $request->scheduleId;
        OfflineCourseActivityTeams::where(['course_id' => $course_id, 'schedule_id' => $schedule_id])->update(['report' => 0]);
        \Artisan::call('command:report_teams '.$course_id .' '.$schedule_id);

        json_result([
            'status' => 'success',
            'message' => 'Update thành công',
            'redirect' => route('module.offline.activity.report_teams', ['course_id' => $course_id, 'class_id' => $class_id, 'schedule_id' => $schedule_id])
        ]);
    }

    public function reportElearning(Request $request)
    {
        $course_id = $request->route('course_id');
        $class_id = $request->route('class_id');
        $schedule_id = $request->route('schedule_id');

        $activities = OfflineCourseActivity::getByCourse($course_id, $class_id, $schedule_id);
        $schedule_other = OfflineSchedule::where(['course_id' => $course_id, 'class_id' => $class_id, 'type_study' => 3])->where('id', '!=', $schedule_id)->get();

        if ($request->ajax()){
            $search = $request->input('search');
            $status = $request->input('status');
            $unit = $request->unit_id;
            $title = $request->input('title');
            $start_date = $request->input('start_date');
            $end_date = $request->input('end_date');

            $sort = $request->input('sort', 'full_name');
            $order = $request->input('order', 'desc');
            $offset = $request->input('offset', 0);
            $limit = $request->input('limit', 20);

            $query = OfflineRegisterView::where(['course_id' => $course_id,'class_id'=>$class_id]);

            if ($search) {
                $query->where(function ($sub_query) use ($search) {
                    $sub_query->orWhere('full_name', 'like', '%'. $search .'%');
                    $sub_query->orWhere('email', 'like', '%'. $search .'%');
                    $sub_query->orWhere('code', 'like', '%'. $search .'%');
                });
            }

            if ($start_date) {
                $query->where('updated_at', '>=', date_convert($start_date));
            }

            if ($end_date) {
                $query->where('updated_at', '<=', date_convert($end_date, '23:59:59'));
            }

            if ($unit) {
                $unit = Unit::whereIn('id', explode(';', $unit))->latest('id')->first();
                $unit_id = Unit::getArrayChild($unit->code);

                $query->where(function ($sub_query) use ($unit_id, $unit) {
                    $sub_query->orWhereIn('unit_id', $unit_id);
                    $sub_query->orWhere('unit_id', '=', $unit->id);
                });
            }

            if ($title) {
                $query->where('title_id', '=', $title);
            }
            $count = $query->count();
            $query->orderBy($sort, 'asc');
            $query->offset($offset);
            $query->limit($limit);

            $rows = $query->get();
            foreach ($rows as $index => $row) {

                foreach ($activities as $activity){
                    $check_complete = $activity->isComplete($row->user_id);

                    $row->{'activity_'. $activity->id} = ($check_complete ? trans("backend.finish") : trans("backend.incomplete"));

                    if ($activity->activity_code == 'scorm') {
                        $activity_scorm = OfflineCourseActivityScorm::find($activity->subject_id);
                        $score = $activity_scorm->getScoreScorm($row->user_id);
                        $row->{'score_'. $activity->id} = ($score ? number_format($score, 2) : '');
                    }elseif($activity->activity_code == 'xapi'){
                        $activity_xapi = OfflineCourseActivityXapi::find($activity->subject_id);
                        $score = $activity_xapi->getScoreXapi($row->user_id);
                        $row->{'score_'. $activity->id} = ($score ? number_format($score, 2) : '');
                    }
                }
            }
            return json_result(['total' => $count, 'rows' => $rows]);
        }else {
            $course = OfflineCourse::findOrFail($course_id);
            $class = OfflineCourseClass::findOrFail($class_id);
            $schedule = OfflineSchedule::findOrFail($schedule_id);

            return view('offline::backend.elearning_report.index', [
                'course' => $course,
                'class' => $class,
                'schedule' => $schedule,
                'class_id' => $class_id,
                'schedule_id' => $schedule_id,
                'activities' => $activities,
                'schedule_other' => $schedule_other,
            ]);
        }
    }
}
