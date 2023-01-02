<?php

namespace Modules\Quiz\Http\Controllers\Backend;

use App\Models\CourseView;
use App\Models\Profile;
use App\Models\ProfileView;
use App\Models\Automail;
use App\Models\Permission;
use App\Models\PermissionUser;
use App\Models\UserPermissionType;
use App\Scopes\DraftScope;
use Illuminate\Support\Facades\DB;
use Modules\Quiz\Entities\QuestionCategory;
use Modules\Quiz\Entities\QuizAttempts;
use App\Models\Categories\Titles;
use App\Models\Categories\Unit;
use App\Models\Categories\Area;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Modules\Online\Entities\OnlineCourse;
use Modules\Online\Entities\OnlineRegister;
use Modules\Quiz\Entities\Question;
use Modules\Quiz\Entities\QuestionAnswer;
use Modules\Quiz\Entities\Quiz;
use Modules\Quiz\Entities\QuizNoteByUserSecond;
use Modules\Quiz\Entities\QuizPart;
use Modules\Quiz\Entities\QuizPermission;
use Modules\Quiz\Entities\QuizPermissionTeacher;
use Modules\Quiz\Entities\QuizQuestionCategory;
use Modules\Quiz\Entities\QuizRank;
use Modules\Quiz\Entities\QuizQuestion;
use Modules\Quiz\Entities\QuizRegister;
use Modules\Quiz\Entities\QuizResult;
use Modules\Quiz\Entities\QuizSetting;
use Modules\Quiz\Entities\QuizSettingAlert;
use Modules\Quiz\Entities\QuizStatistic;
use Modules\Quiz\Entities\QuizTeacher;
use Modules\Quiz\Entities\QuizTemplate;
use Modules\Quiz\Entities\QuizTemplates;
use Modules\Quiz\Entities\QuizTemplatesQuestion;
use Modules\Quiz\Entities\QuizTemplatesQuestionCategory;
use Modules\Quiz\Entities\QuizTemplatesRank;
use Modules\Quiz\Entities\QuizTemplatesSetting;
use Modules\Quiz\Entities\QuizType;
use Modules\Quiz\Entities\QuizUserReview;
use Modules\Quiz\Entities\QuizUserSecondary;
use Modules\Quiz\Entities\QuizDataOld;
use Modules\Quiz\Exports\ExportDataOldQuiz;
use Modules\Quiz\Exports\DashboardExport;
use Modules\Quiz\Exports\HistoryUserExport;
use Modules\Quiz\Exports\HistoryUserSecondExport;
use Modules\UserPoint\Entities\UserPointSettings;
use PhpOffice\PhpWord\Style\Cell;
use PhpOffice\PhpWord\Style\Font;
use Illuminate\Database\Query\Builder;
use Modules\Quiz\Imports\ImportQuizDataOld;
use Modules\UserPoint\Entities\UserPointItem;
use Carbon\Carbon;
use Modules\Offline\Entities\OfflineCourse;
use Modules\Offline\Entities\OfflineRegister;
use Modules\PermissionApproved\Entities\PermissionApproved;

class BackendController extends Controller
{
    public $is_unit = 0;
    public $course_id = 0;
    public $course_type = 0;
    public $quiz_type_by_offline = '0';

    public function index()
    {
        return view('quiz::backend.quiz.index', [
        ]);
    }

    public function form($id = null)
    {
        $model = Quiz::firstOrNew(['id' => $id]);
        $quiz_type = QuizType::get();
        $page_title = $model->name ? $model->name : trans('labutton.add_new');
        $teachers = QuizTeacher::getTeacherByQuiz($id);
        $permission_save = userCan(['training-unit-quiz-create', 'training-unit-quiz-edit', 'quiz-create', 'quiz-edit']);
        $unit = Unit::firstOrNew(['id' => $model->unit_id]);
        $setting = QuizSetting::where('quiz_id', '=', $id)->first();
        $result = QuizResult::where('quiz_id', '=', $id)->whereNull('text_quiz')->first();
        $qrcode_quiz = json_encode(['quiz' => $id, 'course_type' => 1, 'survey' => $model->template_id, 'type' => 'survey_after_course']);

        $course = '';
        $type_quiz = 3; //Kỳ thi độc lập;
        if ($this->course_type == 1) {
            $course = OnlineCourse::find($this->course_id, ['name']);
            $type_quiz = 1;
        } elseif ($this->course_type == 2) {
            $course = OfflineCourse::find($this->course_id, ['name']);
            $type_quiz = 2;
        }

        $quiz_template = QuizTemplates::where('status', '=', 1)->where('quiz_type', '=', $type_quiz);
        if (!$id)
            $quiz_template->where('is_open', '=', 1);
        $quiz_template = $quiz_template->get();

        $quiz_questions = QuizQuestion::whereQuizId($id)
            ->select([
                'b.id',
                'b.name'
            ])
            ->from('el_quiz_question as a')
            ->leftJoin('el_question as b', 'b.id', '=', 'a.question_id')
            ->where('a.random', '=', 0)
            ->whereIn('b.type', ['essay', 'fill_in'])
            ->get();

        $userpoint = UserPointSettings::where('item_id', $id)->where("item_type", 4)->get();

        return view('quiz::backend.quiz.form', [
            'model' => $model,
            'page_title' => $page_title,
            'teachers' => $teachers,
            'is_unit' => $this->is_unit,
            'permission_save' => $permission_save,
            'unit' => $unit,
            // 'unit_user' => $user->unit,
            'setting' => $setting,
            'result' => $result,
            'quiz_type' => $quiz_type,
            'qrcode_quiz' => $qrcode_quiz,
            'quiz_template' => $quiz_template,
            'quiz_questions' => $quiz_questions,
            'userpoint' => $userpoint,
            'course' => $course,
            'course_id' => $this->course_id,
            'course_type' => $this->course_type,
            'quiz_type_by_offline' => $this->quiz_type_by_offline,
            'type_quiz' => $type_quiz, // Hình thức kỳ thi
        ]);
    }

    public function getData(Request $request)
    {
        $user_unit = session()->get('user_unit');
        $parent_unit = getParentUnit($user_unit);
        $count_level_permission_approve = PermissionApproved::where(['unit_id' => $parent_unit, 'model_approved' => 'el_quiz'])->count();

        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $search = $request->input('search');
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        $quiz_type = $request->quiz_type;

        Quiz::addGlobalScope(new DraftScope());
        $query = Quiz::query();
        $query->select([
            'id',
            'is_open',
            'code',
            'name',
            'quiz_type',
            'limit_time',
            'view_result',
            'approved_step',
            'status',
            'created_at',
            'created_by',
            'updated_by',
            'approved_by',
            'time_approved',
            'course_id',
            'course_type',
        ])->where('quiz_type', 3);

        if ($search) {
            $query->where(function ($sub_query) use ($search) {
                $sub_query->orWhere('name', 'like', '%' . $search . '%');
                $sub_query->orWhere('code', 'like', '%' . $search . '%');
            });
        }

        $dbprefix = \DB::getTablePrefix();
        if ($start_date) {
            $start_date = date_convert($start_date, '00:00:00');
            $query->where(\DB::raw('(select MIN(start_date)
            from ' . $dbprefix . 'el_quiz_part
            where quiz_id = ' . $dbprefix . 'el_quiz.id)'), '>=', $start_date);
        }

        if ($end_date) {
            $end_date = date_convert($end_date, '23:59:59');
            $query->where(\DB::raw('(select MIN(start_date)
            from ' . $dbprefix . 'el_quiz_part
            where quiz_id = ' . $dbprefix . 'el_quiz.id)'), '<=', $end_date);
        }

        if ($quiz_type) {
            $query->where('quiz_type', $quiz_type);
        }

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();
        foreach ($rows as $row) {
            $row->start_date = '';
            $row->end_date = '';
            $qdate = QuizPart::query()->where('quiz_id', '=', $row->id);
            if ($qdate->exists()) {
                $start_date = $qdate->min('start_date');
                $end_date = $qdate->max('end_date');

                $row->start_date = get_date($start_date, 'H:i d/m/Y');
                $row->end_date = get_date($end_date, 'H:i d/m/Y');
            }

            $row->result = route('module.quiz.result', ['id' => $row->id]);
            $row->question = route('module.quiz.question', ['id' => $row->id]);
            $row->register_url = route('module.quiz.register', ['id' => $row->id]);
            $row->user_secondary_url = route('module.quiz.register.user_secondary', ['id' => $row->id]);
            $row->export_url = route('module.quiz.export_quiz', ['id' => $row->id]);
            $row->edit_url = route('module.quiz.edit', [$row->id]);
            $row->info_url = route('module.quiz.modal_info', ['id' => $row->id]);

            $row->modal_course_url = '';
            if ($row->course_id && $row->course_id > 0) {
                $row->modal_course_url = route('module.quiz.modal_course', [$row->course_id, $row->course_type]);
            }

            $row->quantity = QuizRegister::where('quiz_id', '=', $row->id)->count();
            $row->quantity_quiz_attempts = QuizResult::where('quiz_id', '=', $row->id)->where('timecompleted', '>', 0)->count();
            $row->count_level_permission_approve = $count_level_permission_approve;
            $row->quiz_type_text = ($row->quiz_type == 1 ? 'Online' : ($row->quiz_type == 2 ? trans("latraining.offline") : 'Thi độc lập'));
            $row->parent_unit = $parent_unit;
        }

        return response()->json(['total' => $count, 'rows' => $rows]);
    }

    public function save(Request $request)
    {
        $this->validateRequest([
            'code' => 'required|unique:el_quiz,code,' . $request->id,
            'name' => 'required',
            'limit_time' => 'required|min:1',
            'pass_score' => 'required|min:0|max:100',
            'max_score' => 'required|min:0|max:100',
            'questions_perpage' => 'required|min:0|max:10',
            'max_attempts' => 'required',
            'grade_methor' => 'required',
            'img' => 'nullable|string',
        ], $request, Quiz::getAttributeName());

        $id = $request->id;
        $course_id = $request->course_id;
        $course_type = $request->course_type;
        $quiz_type_by_offline = $request->quiz_type_by_offline;
        $quiz_type = $request->quiz_type;
        $questions_perpage = $request->questions_perpage;
        $limit_time = $request->input('limit_time');
        $pass_score = $request->input('pass_score');
        $max_score = $request->input('max_score');
        $shuffle = $request->post('shuffle_answers');
        $webcam_require = $request->webcam_require;
        $question_require = $request->question_require;
        $quiz_template_id = $request->quiz_template_id;

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

        if ((int)$questions_perpage <= 0 || (int)$questions_perpage >= 11) {
            json_message('Số câu hỏi 1 trang nằm trong khoảng 1 - 10', 'error');
        }

        if($webcam_require && $request->times_shooting_webcam == 0){
            json_message('Số lần chụp phải > 0', 'error');
        }
        if($question_require && $request->times_shooting_question == 0){
            json_message('Số lần trả lời câu hỏi phải > 0', 'error');
        }

        $model = Quiz::firstOrNew(['id' => $request->post('id')]);
        $model->fill($request->all());

        if ($request->img) {
            $sizes = config('image.sizes.medium');
            $model->img = upload_image($sizes, $request->img);
        }

        if (isset($request->id) && isset($quiz_template_id) && $quiz_template_id != $model->quiz_template_id) {
            QuizRank::query()->where('quiz_id', '=', $model->id)->delete();
            QuizSetting::query()->where('quiz_id', '=', $model->id)->delete();
            QuizQuestion::query()->where('quiz_id', '=', $model->id)->delete();
            QuizQuestionCategory::query()->where('quiz_id', '=', $model->id)->delete();
        }

        $model->shuffle_answers = if_empty($shuffle, 0);
        $model->webcam_require = $webcam_require ?? 0;
        $model->question_require = $question_require ?? 0;
        $model->unit_id = $request->is_unit > 0 ? $request->is_unit : $request->input('unit_id');
        if ($quiz_type != 3) {
            $model->status = 1;
            $model->is_open = 1;
            $model->view_result = 1;
            $model->approved_by = profile()->user_id;
            $model->time_approved = date('Y-m-d h:i:s');
        } else {
            if (empty($model->id)) {
                $model->status = 2;
            }
        }

        if ($model->save()) {
            if ($quiz_template_id) {
                $rank = QuizRank::query()->where('quiz_id', '=', $model->id);
                if (!$rank->exists()) {
                    $quiz_template_rank = QuizTemplatesRank::selectRaw($model->id . ', rank, score_min, score_max, ' . current_datetime_sql() . ', ' . current_datetime_sql())->where('quiz_id', '=', $quiz_template_id);
                    DB::table('el_quiz_rank')->insertUsing(['quiz_id', 'rank', 'score_min', 'score_max', 'created_at', 'updated_at'], $quiz_template_rank);
                }

                $setting = QuizSetting::query()->where('quiz_id', '=', $model->id);
                if (!$setting->exists()) {
                    $quiz_template_setting = QuizTemplatesSetting::selectRaw($model->id . ', after_test_review_test, after_test_yes_no, after_test_score, after_test_specific_feedback, after_test_general_feedback, after_test_correct_answer, exam_closed_review_test, exam_closed_yes_no, exam_closed_score, exam_closed_specific_feedback, exam_closed_general_feedback, exam_closed_correct_answer, ' . current_datetime_sql() . ', ' . current_datetime_sql())->where('quiz_id', '=', $quiz_template_id);

                    DB::table('el_quiz_setting')->insertUsing(['quiz_id', 'after_test_review_test', 'after_test_yes_no', 'after_test_score', 'after_test_specific_feedback', 'after_test_general_feedback', 'after_test_correct_answer', 'exam_closed_review_test', 'exam_closed_yes_no', 'exam_closed_score', 'exam_closed_specific_feedback', 'exam_closed_general_feedback', 'exam_closed_correct_answer', 'created_at', 'updated_at'], $quiz_template_setting);
                }

                $quiz_question = QuizQuestion::query()->where('quiz_id', '=', $model->id);
                if (!$quiz_question->exists()) {
                    $quiz_template_question_category = QuizTemplatesQuestionCategory::where('quiz_id', '=', $quiz_template_id)->get();
                    if (count($quiz_template_question_category) > 0) {
                        foreach ($quiz_template_question_category as $item) {
                            $quiz_question_category = new QuizQuestionCategory();
                            $quiz_question_category->quiz_id = $model->id;
                            $quiz_question_category->name = $item->name;
                            $quiz_question_category->num_order = $item->num_order;
                            $quiz_question_category->percent_group = $item->percent_group;
                            $quiz_question_category->save();

                            $quiz_template_question = QuizTemplatesQuestion::selectRaw($model->id . ', question_id, qcategory_id, random, num_order, ' . $quiz_question_category->id . ', max_score, difficulty, ' . current_datetime_sql() . ', ' . current_datetime_sql())->where('qqcategory', '=', $item->id)->where('quiz_id', '=', $quiz_template_id);

                            DB::table('el_quiz_question')->insertUsing(['quiz_id', 'question_id', 'qcategory_id', 'random', 'num_order', 'qqcategory', 'max_score', 'difficulty', 'created_at', 'updated_at'], $quiz_template_question);
                        }
                    } else {
                        $quiz_template_question = QuizTemplatesQuestion::selectRaw($model->id . ', question_id, qcategory_id, random, num_order, qqcategory, max_score, difficulty, ' . current_datetime_sql() . ', ' . current_datetime_sql())->where('quiz_id', '=', $quiz_template_id);

                        DB::table('el_quiz_question')->insertUsing(['quiz_id', 'question_id', 'qcategory_id', 'random', 'num_order', 'qqcategory', 'max_score', 'difficulty', 'created_at', 'updated_at'], $quiz_template_question);
                    }
                }
            }

            /********update thống kê quiz **********/
            QuizStatistic::update_statistic($model->id);
            /*********************end***********************/
            /************Lưu điểm thưởng mặc định***************/
            $user_point_quiz_complete = UserPointItem::where("type", "=", 4)->where('ikey', '=', 'quiz_complete')->first();
            if (empty($request->id) && $user_point_quiz_complete) {
                $complete = new UserPointSettings();
                $complete->pkey = 'quiz_complete';
                $complete->item_id = $model->id;
                $complete->item_type = 4;
                $complete->pvalue = $user_point_quiz_complete->default_value;
                $complete->note = 'complete';
                $complete->save();
            }

            if(!isset($id)) {
                if ($course_type == 1) {
                    $course = OnlineCourse::find($course_id);
                    if ($quiz_type_by_offline && $quiz_type_by_offline != 'register_quiz_id') {
                        $course->{$quiz_type_by_offline} = $model->id;
                        $course->save();
                    }

                    $quiz_part = QuizPart::whereQuizId($model->id)->first();
                    if (!$quiz_part) {
                        $quiz_part = new QuizPart();
                        $quiz_part->quiz_id = $model->id;
                        $quiz_part->name = 'ca 1';
                        $quiz_part->start_date = $course->start_date;
                        $quiz_part->end_date = $course->end_date;
                        $quiz_part->save();
                    }

                    if ($quiz_type_by_offline != 'register_quiz_id') {
                        $course_register = OnlineRegister::whereCourseId($course_id)->where('status', '=', 1)->get();
                        if ($course_register->count() > 0) {
                            foreach ($course_register as $register) {
                                QuizRegister::query()
                                    ->updateOrCreate([
                                        'quiz_id' => $model->id,
                                        'user_id' => $register->user_id,
                                        'type' => $register->user_type,
                                        'part_id' => $quiz_part->id,
                                    ], [
                                        'quiz_id' => $model->id,
                                        'user_id' => $register->user_id,
                                        'type' => $register->user_type,
                                        'part_id' => $quiz_part->id,
                                    ]);
                            }
                        }
                    }

                    $redirect = route('module.online.quiz.edit', ['course_id' => $course_id, 'id' => $model->id, 'quiz_type_by_offline' => $quiz_type_by_offline]);
                } elseif ($course_type == 2) {
                    $not_save = ['register_quiz_id', 'activity_quiz_id'];
                    if($quiz_type_by_offline) {

                        $course = OfflineCourse::find($course_id);
                        if (!in_array($quiz_type_by_offline, $not_save)) { //Chỉ lưu id kỳ thi vào khoá học khi là kỳ thi đầu vào hoặc cuối khoá
                            $course->{$quiz_type_by_offline} = $model->id;
                            $course->save();
                        }

                        if($quiz_type_by_offline != 'activity_quiz_id'){ //Kỳ thi hoạt động không tạo ca ở đây mà tạo ca khi thêm vào buổi học elearning
                            $quiz_part = QuizPart::whereQuizId($model->id)->first();
                            if (!$quiz_part) {
                                $quiz_part = new QuizPart();
                                $quiz_part->quiz_id = $model->id;
                                $quiz_part->name = 'ca 1';
                                $quiz_part->start_date = $course->start_date;
                                $quiz_part->end_date = $course->end_date;
                                $quiz_part->save();
                            }
                        }

                        //Kỳ thi ghi danh chỉ ghi danh khi HV vào làm bài.
                        //Kỳ thi hoạt động ghi danh khi thêm kỳ thi vào buổi học Elearning từng lớp
                        if(!in_array($quiz_type_by_offline, $not_save)) {
                            $course_register = OfflineRegister::whereCourseId($course_id)->where('status', '=', 1)->get();
                            if ($course_register->count() > 0) {
                                foreach ($course_register as $register) {
                                    QuizRegister::query()
                                        ->updateOrCreate([
                                            'quiz_id' => $model->id,
                                            'user_id' => $register->user_id,
                                            'type' => 1,
                                            'part_id' => $quiz_part->id,
                                        ], [
                                            'quiz_id' => $model->id,
                                            'user_id' => $register->user_id,
                                            'type' => 1,
                                            'part_id' => $quiz_part->id,
                                        ]);
                                }
                            }
                        }
                    }

                    $redirect = route('module.offline.quiz.edit', ['course_id' => $course_id, 'id' => $model->id, 'quiz_type_by_offline' => $quiz_type_by_offline]);
                } else {
                    $redirect = route('module.quiz.edit', [$model->id]);
                }
            } else {
                $redirect = '';
            }
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
            $quiz = Quiz::find($id);
            if ($quiz->status == 1) {
                json_message('Kỳ thi được duyệt. Không thể xoá', 'error');
            }

            if ($quiz->course_id > 0) {
                json_message('Kỳ thi được thêm trong khoá học. Không thể xoá', 'error');
            }

            $result = QuizResult::where('quiz_id', '=', $id)->whereNull('text_quiz');
            if ($result->exists()) {
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
                $model = Quiz::findOrFail($id);
                $model->is_open = $status;
                $model->save();
            }
        } else {
            $model = Quiz::findOrFail($ids);
            $model->is_open = $status;
            $model->save();
        }

        json_result([
            'status' => 'success',
            'message' => trans('laother.successful_save')
        ]);
    }

    public function saveViewResult(Request $request)
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
            $model = Quiz::findOrFail($id);
            $model->view_result = $status;
            $model->save();
        }
    }

    public function copyQuiz(Request $request)
    {
        $this->validateRequest([
            'ids' => 'required',
        ], $request, [
            'ids' => trans('lamenu.quiz'),
        ]);

        $ids = $request->input('ids', null);
        foreach ($ids as $id) {
            $model = Quiz::find($id);
            $newModel = $model->replicate();
            $newModel->code = $newModel->code . '_copy';
            $newModel->status = 2;
            $newModel->course_id = 0;
            $newModel->course_type = 0;
            $newModel->is_open = 0;
            $newModel->approved_step = null;
            $newModel->save();

            /************Lưu điểm thưởng mặc định***************/
            $user_point_quiz_complete = UserPointItem::where("type", "=", 4)->where('ikey', '=', 'quiz_complete')->first();
            if ($user_point_quiz_complete) {
                $complete = new UserPointSettings();
                $complete->pkey = 'quiz_complete';
                $complete->item_id = $newModel->id;
                $complete->item_type = 4;
                $complete->pvalue = $user_point_quiz_complete->default_value;
                $complete->note = 'complete';
                $complete->save();
            }

            $quiz_cate_ques = QuizQuestionCategory::where('quiz_id', '=', $id)->get();
            foreach ($quiz_cate_ques as $key => $value) {
                $newQuizCateQues = $value->replicate();
                $newQuizCateQues->quiz_id = $newModel->id;
                $newQuizCateQues->save();
            }

            $quiz_ques = QuizQuestion::where('quiz_id', '=', $id)->get();
            foreach ($quiz_ques as $item) {
                $newQuizQues = $item->replicate();
                $newQuizQues->quiz_id = $newModel->id;
                $newQuizQues->save();
            }
        }

    }

    public function getDataPart($quiz_id, Request $request)
    {
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
            $row->start_d = get_date($row->start_date);
            $row->start_h = get_date($row->start_date, 'H');
            $row->start_i = get_date($row->start_date, 'i');
            $row->end_d = get_date($row->end_date);
            $row->end_h = get_date($row->end_date, 'H');
            $row->end_i = get_date($row->end_date, 'i');

            $row->start_date = get_date($row->start_date, 'H:i d/m/Y');
            $row->end_date = get_date($row->end_date, 'H:i d/m/Y');
            $qrcode_quiz = route('qrcode_process',['quiz'=>$quiz_id,'part'=>$row->id,'type'=>'quiz']);
            $row->qrcode = \QrCode::size(300)->generate($qrcode_quiz);

            $row->quiz_result = \DB::table('el_quiz_result')->where('quiz_id', '=', $quiz_id)->where('part_id', $row->id)->exists() ? 1 : 0;
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function savePart($id, Request $request)
    {
        $this->validateRequest([
            'name_part' => 'required',
            'start_date' => 'required',
            'start_hour' => 'required',
            'start_min' => 'required',
        ], $request);
        $quiz = Quiz::find($id);

        if ($quiz->quiz_type != 1) {
            if (is_null($request->input('end_date'))) {
                json_result([
                    'status' => 'error',
                    'message' => 'Thời gian kết thúc không được trống',
                ]);
            }
        }

        $name_part = $request->input('name_part');
        $start_time = $request->input('start_hour') . ':' . $request->input('start_min') . ':00';
        $end_time = $request->input('end_hour') . ':' . $request->input('end_min') . ':00';

        $start_date = date_convert($request->input('start_date'), $start_time);
        $end_date = '';
        if ($request->input('end_date')) {
            $end_date = date_convert($request->input('end_date'), $end_time);
        }

        $check1 = QuizPart::query();
        $check1->where(function ($sub) use ($start_date) {
            $sub->where('start_date', '<=', $start_date);
            $sub->orWhere('start_date', '>=', $start_date);
        });
        // $check1->where('start_date', '<=', $start_date);
        $check1->where('end_date', '>=', $start_date);
        $check1->where('quiz_id', '=', $id);
        if ($check1->exists()) {
            json_result([
                'status' => 'error',
                'message' => 'Thời gian ca thi không họp lệ',
            ]);
        }

        if ($request->input('end_date')) {
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

            $to_date = Carbon::createFromFormat('Y-m-d H:i:s', $start_date);
            $from_date = Carbon::createFromFormat('Y-m-d H:i:s', $end_date);
            $answer_in_days = $to_date->diffInMinutes($from_date);
            if ((int)$answer_in_days <= (int)$quiz->limit_time) {
                json_result([
                    'status' => 'error',
                    'message' => 'Khoảng Thời gian của ca thi phải lớn hơn Thời gian làm bài của kỳ thi',
                ]);
            }
        }

        $model = new QuizPart();
        $model->quiz_id = $id;
        $model->name = $name_part;
        $model->start_date = $start_date;
        $model->end_date = $request->input('end_date') ? $end_date : null;

        if ($request->input('end_date')) {
            if ($model->start_date >= $model->end_date) {
                json_result([
                    'status' => 'error',
                    'message' => 'Thời gian ca thi không họp lệ',
                ]);
            }
        }

        if ($model->save()) {
            json_message('ok');
        }

    }

    public function editPart($id, Request $request)
    {
        $quiz = Quiz::find($id);

        if ($quiz->quiz_type != 1) {
            if (is_null($request->input('end_date'))) {
                json_result([
                    'status' => 'error',
                    'message' => 'Thời gian kết thúc không được trống',
                ]);
            }
        }

        $part_id = $request->part_id;
        $part_name = $request->part_name;

        $start_date = $request->start_date;
        $start_hour = $request->start_hour;
        $start_min = $request->start_min;

        $end_date = $request->end_date;
        $end_hour = $request->end_hour;
        $end_min = $request->end_min;

        $start_time = $start_hour . ':' . ($start_min ? $start_min : '00');
        $start_date = date_convert($start_date, $start_time);

        $end_time = $end_hour . ':' . ($end_min ? $end_min : '00');
        $end_date = date_convert($end_date, $end_time);

        $check1 = QuizPart::query();
        $check1->where('id', '!=', $part_id);
        $check1->where(function ($sub) use ($start_date) {
            $sub->where('start_date', '<=', $start_date);
            $sub->orWhere('start_date', '>=', $start_date);
        });
        $check1->where('end_date', '>=', $start_date);
        $check1->where('quiz_id', '=', $id);
        if ($check1->exists()) {
            json_result([
                'status' => 'error',
                'message' => 'Thời gian ca thi không họp lệ 1',
            ]);
        }

        if ($request->input('end_date')) {
            $check2 = QuizPart::query();
            $check2->where('id', '!=', $part_id);
            $check2->where('start_date', '<=', $end_date);
            $check2->where('end_date', '>=', $end_date);
            $check2->where('quiz_id', '=', $id);
            if ($check2->exists()) {
                json_result([
                    'status' => 'error',
                    'message' => 'Thời gian ca thi không họp lệ 2',
                ]);
            }

            $time_limit = $quiz->limit_time + 10;
            $date_now_add = Carbon::now()->addMinutes($time_limit)->format('Y-m-d H:i:s');
            if ($end_date <= $date_now_add) {
                json_result([
                    'status' => 'error',
                    'message' => 'Thời gian kết thúc ca thi phải lớn hơn (thời gian làm bài của kỳ thi + thời gian hiện tại + 10p)',
                ]);
            }

            $to_date = Carbon::createFromFormat('Y-m-d H:i:s', $start_date);
            $from_date = Carbon::createFromFormat('Y-m-d H:i:s', $end_date);
            $answer_in_days = $to_date->diffInMinutes($from_date);
            if ((int)$answer_in_days <= (int)$quiz->limit_time) {
                json_result([
                    'status' => 'error',
                    'message' => 'Khoảng Thời gian của ca thi phải lớn hơn Thời gian làm bài của kỳ thi',
                ]);
            }

            if ($start_date >= $end_date) {
                json_result([
                    'status' => 'error',
                    'message' => 'Thời gian kết thúc ca thi phải lớn hơn thời gian bắt đầu',
                ]);
            }
        }

        $quiz_part = QuizPart::find($part_id);
        $quiz_part->name = $part_name;
        $quiz_part->start_date = $start_date;
        $quiz_part->end_date = $request->input('end_date') ? $end_date : null;
        $quiz_part->userpoint_timefinish = 0;

        if ($quiz_part->save()) {
            json_message('ok');
        }
    }

    public function updatePart($id, Request $request)
    {
        $part_id = $request->part_id;
        $end_date = $request->end_date;
        $end_hour = $request->end_hour;
        $end_min = $request->end_min;

        $quiz_part = QuizPart::find($part_id);

        $end_time = $end_hour . ':' . ($end_min ? $end_min : '00');
        $end_date = date_convert($end_date, $end_time);

        $check1 = QuizPart::query();
        $check1->where('id', '!=', $part_id);
        $check1->where('end_date', '>', $quiz_part->start_date);
        $check1->where('end_date', '<', $end_date);
        $check1->where('quiz_id', '=', $id);
        if ($check1->exists()) {
            json_result([
                'status' => 'error',
                'message' => 'Thời gian ca thi Không hợp lẹ',
            ]);
        }

        $check2 = QuizPart::query();
        $check2->where('id', '!=', $part_id);
        $check2->where('start_date', '<=', $end_date);
        $check2->where('end_date', '>=', $end_date);
        $check2->where('quiz_id', '=', $id);
        if ($check2->exists()) {
            json_result([
                'status' => 'error',
                'message' => 'Thời gian ca thi đã tồn tại',
            ]);
        }

        if ($quiz_part->start_date >= $end_date) {
            json_result([
                'status' => 'error',
                'message' => 'Thời gian kết thúc ca thi phải lớn hơn thời gian bắt đầu',
            ]);
        }

        if ($quiz_part->end_date > $end_date) {
            json_result([
                'status' => 'error',
                'message' => 'Thời gian gia hạn ca thi phải lớn hơn thời gian kết thúc',
            ]);
        }

        $quiz_part->end_date = $end_date;
        $quiz_part->userpoint_timefinish = 0;
        if ($quiz_part->save()) {
            json_message('ok');
        }
    }

    public function removePart($id, Request $request)
    {
        $ids = $request->input('ids', null);
        foreach ($ids as $part_id) {
            $check = QuizRegister::where('quiz_id', '=', $id)
                ->where('part_id', '=', $part_id);
            if ($check->exists()) {
                json_message('Đã có người ghi danh', 'warning');
            }

            QuizPart::find($part_id)->delete();
        }

        json_result([
            'status' => 'success',
            'message' => trans('laother.delete_success'),
        ]);
    }

    public function updateDateQuiz($quiz_id)
    {
        $prefix = \DB::getTablePrefix();
        $quiz = Quiz::findOrFail($quiz_id);

        $quiz_date = QuizPart::whereQuizId($quiz_id);
        if ($quiz_date->exists()) {
            $quiz->update([
                'start_date' => $quiz_date->min('start_date') ? $quiz_date->min('start_date') : null,
                'end_date' => $quiz_date->max('end_date') ? $quiz_date->max('end_date') : null,
            ]);
        }
    }

    public function getDataRank($id, Request $request)
    {
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = QuizRank::where('quiz_id', '=', $id);
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
        $score_min = number_format($request->input('score_min'), 2);
        $score_max = number_format($request->input('score_max'), 2);
        $quiz = Quiz::find($id);

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

        $check1 = QuizRank::query();
        $check1->where('score_min', '<=', $score_min);
        $check1->where('score_max', '>=', $score_min);
        $check1->where('quiz_id', '=', $id);
        if ($check1->exists()) {
            json_result([
                'status' => 'error',
                'message' => 'Điểm nhập không họp lệ',
            ]);
        }

        $check2 = QuizRank::query();
        $check2->where('score_min', '<=', $score_max);
        $check2->where('score_max', '>=', $score_max);
        $check2->where('quiz_id', '=', $id);
        if ($check2->exists()) {
            json_result([
                'status' => 'error',
                'message' => 'Điểm nhập không họp lệ',
            ]);
        }

        $model = new QuizRank();
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
        QuizRank::destroy($ids);
        json_result([
            'status' => 'success',
            'message' => trans('laother.delete_success'),
        ]);
    }

    public function getDataPermissionTeacher($id, Request $request)
    {
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = QuizTeacher::query();
        $query->select([
            'a.*',
            'b.name as teacher_name',
        ]);
        $query->from('el_quiz_teacher as a');
        $query->join('el_training_teacher AS b', 'b.id', '=', 'a.teacher_id');
        $query->where('a.quiz_id', '=', $id);

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();

        foreach ($rows as $row) {
            $permission_teacher = QuizPermissionTeacher::where('quiz_id', '=', $id)->where('teacher_id', '=', $row->teacher_id)->first();
            if ($permission_teacher) {
                $row->question_ids = explode(',', $permission_teacher->question_id);

                $question = Question::query()
                    ->whereIn('id', explode(',', $permission_teacher->question_id))
                    ->pluck('name')
                    ->toArray();

                $row->question = implode('<br>', $question);
            }
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function saveTeacher($id, Request $request)
    {
        $teachers = (array)$request->input('teachers', []);
        $question = (array)$request->question;

        foreach ($teachers as $teacher) {
            if (count($question) > 0) {
                QuizPermissionTeacher::query()
                    ->updateOrCreate([
                        'quiz_id' => $id,
                        'teacher_id' => $teacher
                    ], [
                        'quiz_id' => $id,
                        'teacher_id' => $teacher,
                        'question_id' => implode(',', $question)
                    ]);
            }

            if (QuizTeacher::checkExists($id, $teacher)) {
                continue;
            } else {
                $model = new QuizTeacher();
                $model->quiz_id = $id;
                $model->teacher_id = $teacher;
                $model->save();
            }
        }

        json_message(trans('laother.successful_save'));
    }

    public function updatePermissionTeacher($id, Request $request)
    {
        $teachers = (array)$request->input('teachers', []);
        $question = (array)$request->question;

        foreach ($teachers as $teacher) {
            if (count($question) > 0) {
                QuizPermissionTeacher::query()
                    ->updateOrCreate([
                        'quiz_id' => $id,
                        'teacher_id' => $teacher
                    ], [
                        'quiz_id' => $id,
                        'teacher_id' => $teacher,
                        'question_id' => implode(',', $question)
                    ]);
            }
        }

        json_message(trans('laother.successful_save'));
    }

    public function removePermissionTeacher($quiz_id, Request $request)
    {
        $ids = $request->input('ids', null);
        foreach ($ids as $id) {
            $quiz_teacher = QuizTeacher::find($id);

            QuizPermissionTeacher::where('quiz_id', '=', $quiz_id)->where('teacher_id', '=', $quiz_teacher->teacher_id)->delete();

            $quiz_teacher->delete();
        }

        json_result([
            'status' => 'success',
            'message' => trans('laother.delete_success'),
        ]);
    }

    public function saveSetting($id, Request $request)
    {

        $model = QuizSetting::firstOrNew(['id' => $request->id]);
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

    public function exportQuiz($id)
    {
        $quiz = Quiz::findOrFail($id);
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

        $query = QuizQuestion::query()
            ->where('quiz_id', '=', $quiz->id);
        $rows = $query->get([
            'id',
            'random',
            'qcategory_id',
            'question_id',
        ]);

        $qqc = function ($quiz_id, $num_order) {
            return QuizQuestionCategory::where('quiz_id', '=', $quiz_id)
                ->where('num_order', '=', $num_order)
                ->orderBy('num_order', 'ASC')
                ->first();
        };

        $random_questions = [0];
        foreach ($rows as $qindex => $row) {
            if ($row->random == 1) {
                $random = \DB::table('el_question')->where('category_id', '=', $row->qcategory_id)
                    ->whereNotIn('id', $random_questions)
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

    /**
     * In để ký
     * @param int $id
     * @return void
     * @throws
     * */
    public function exportQuizUser($id)
    {
        $quiz = Quiz::findOrFail($id);

        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $section = $phpWord->addSection();
        $section->addText(Str::upper('Đề kiểm tra'), [
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

        $arrawser = ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l'];
        /*$query = QuizTemplateQuestion::query();
        $query->where('template_id', '=', $template_id);*/
        $query = QuizTemplateQuestionRand::query()
            ->where('quiz_id', '=', $quiz->id)
            ->where('template_id', '=', 1);
        $rows = $query->get([
            'id',
            'name',
            'type',
            'multiple',
            'max_score',
            'qindex',
            'question_id'
        ]);

        $qqc = function ($quiz_id, $num_order) {
            return QuizQuestionCategory::where('quiz_id', '=', $quiz_id)
                ->where('num_order', '=', $num_order)
                ->orderBy('num_order', 'ASC')
                ->first();
        };

        foreach ($rows as $qindex => $row) {
            $question = Question::find($row->question_id);
            $qqcategorys = $qqc($quiz->id, $qindex);

            if ($qqcategorys) {
                $section->addText(Str::upper($qqcategorys->name), [
                    'name' => 'Times New Roman',
                    'size' => 12,
                    'bold' => true,
                ]);
            }
            $text = trim(html_entity_decode(strip_tags($question->name)), "\xc2\xa0");
            $textlines = explode("\n", $text);

            for ($i = 0; $i < sizeof($textlines); $i++) {
                $text = str_replace("\r", "", $textlines[$i]);
                if ($text != '') {
                    $section->addText($i == 0 ? ($qindex + 1) . '. ' . $question->note . '. ' . $text : $text, [
                        'name' => 'Times New Roman',
                        'size' => 12,
                    ]);
                }
            }

            if ($row->type == 'essay') {
                $question_feedback = Question::find($row->question_id);

                if ($question_feedback) {
                    $feedback = json_decode(strip_tags(trim(html_entity_decode($question_feedback->feedback, ENT_QUOTES, 'UTF-8'), "\xc2\xa0")), true);

                    $section->addText('Đáp án gợi ý', [
                        'name' => 'Times New Roman',
                        'size' => 12,
                    ]);

                    foreach ($feedback as $key => $value) {
                        $section->addText(str_repeat(' ', 3) . ' - ' . $value, [
                            'name' => 'Times New Roman',
                            'size' => 12,
                        ]);
                    }
                }
            } else {
                $answers = QuestionAnswer::query()->where('question_id', '=', $row->question_id)->get(['title', 'matching_answer', 'correct_answer', 'percent_answer']);

                foreach ($answers as $index => $answer) {
                    $val = $arrawser[$index] . '. ' . html_entity_decode($answer->title) . ' ' . html_entity_decode($answer->matching_answer);

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
        //        QuizTemplate::deleteTemplate($template_id);
        // dd($arr);
        $section->addText('-- Hết --', [
            'name' => 'Times New Roman',
            'size' => 12,
        ], [
            'align' => Cell::VALIGN_CENTER
        ]);

        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $file_name = Str::slug($quiz->name);
        header("Content-Disposition: attachment; filename=" . $file_name . ".docx");
        $objWriter->save("php://output");

        //QuizTemplate::deleteTemplate($template_id);
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

    public function sendMailApprove(Request $request)
    {
        $this->validateRequest([
            'ids' => 'required',
        ], $request, ['ids' => trans('lamenu.quiz')]);

        $query = UserPermissionType::query()
            ->from('el_user_permission_type as a')
            ->leftJoin('el_permission_type_unit as b', 'b.permission_type_id', '=', 'a.permission_type_id')
            ->leftJoin('el_permissions as c', 'c.id', '=', 'a.permission_id')
            ->whereIn('c.name', function ($sub2) {
                $sub2->select(['per.parent'])
                    ->from('el_model_has_permissions as model')
                    ->leftJoin('el_permissions as per', 'per.id', '=', 'model.permission_id')
                    ->whereColumn('model.model_id', '=', 'a.user_id')
                    ->where('per.name', '=', 'quiz-approve');
            })
            ->where('c.name', '=', 'quiz')
            ->pluck('a.user_id')->toArray();

        $ids = $request->input('ids', []);
        foreach ($ids as $id) {
            $quiz = Quiz::find($id);
            $users = $query;
            $start_date = '';
            $qdate = QuizPart::query()->where('quiz_id', '=', $id);
            if ($qdate->exists()) {
                $start_date = $qdate->min('start_date');
            }

            if ($quiz->status != 1) {
                foreach ($users as $user_id) {
                    $profile = Profile::find($user_id);

                    $signature = getMailSignature($user_id);

                    $automail = new Automail();
                    $automail->template_code = 'approve_quiz';
                    $automail->params = [
                        'signature' => $signature,
                        'gender' => $profile->gender == '1' ? 'Anh' : 'Chị',
                        'full_name' => $profile->full_name,
                        'quiz_name' => $quiz->name . ' (' . $quiz->code . ')',
                        'quiz_type' => $quiz->quiz_type,
                        'quiz_time' => $start_date ? get_date($start_date, 'H:i d/m/Y') : '',
                        'url' => route('module.quiz.manager')
                    ];
                    $automail->users = [$user_id];
                    $automail->check_exists = true;
                    $automail->check_exists_status = 0;
                    $automail->object_id = $quiz->id;
                    $automail->object_type = 'approve_quiz';
                    $automail->addToAutomail();
                }
            }
        }

        json_message('Gửi mail thành công');
    }

    public function sendMailChange(Request $request)
    {
        $this->validateRequest([
            'ids' => 'required',
        ], $request, ['ids' => trans('lamenu.quiz')]);

        $ids = $request->input('ids', []);
        foreach ($ids as $id) {
            $quiz = Quiz::find($id);
            $users = QuizRegister::where('quiz_id', '=', $id)->get();

            foreach ($users as $user) {
                $signature = getMailSignature($user->user_id, $user->type);
                $automail = new Automail();
                $automail->template_code = 'quiz_change';
                $automail->params = [
                    'signature' => $signature,
                    'code' => $quiz->code,
                    'name' => $quiz->name,
                    'url' => route('module.quiz', ['id' => $id])
                ];
                $automail->users = [$user->user_id];
                $automail->check_exists = true;
                $automail->check_exists_status = 0;
                $automail->object_id = $quiz->id;
                $automail->object_type = 'quiz_change';
                $automail->addToAutomail();
            }

        }

        json_message('Gửi mail thành công');
    }

    public function sendMailInvitation(Request $request)
    {
        $this->validateRequest([
            'ids' => 'required',
        ], $request, ['ids' => trans('lamenu.quiz')]);

        $ids = $request->input('ids', []);
        foreach ($ids as $id) {
            $quiz = Quiz::find($id);
            $parts = QuizPart::where('quiz_id', '=', $quiz->id)->first();

            $users = QuizRegister::where('quiz_id', '=', $id)->get();
            foreach ($users as $user) {
                $signature = getMailSignature($user->user_id, $user->type);

                $automail = new Automail();
                $automail->template_code = 'register_quiz_remind';
                $automail->params = [
                    'signature' => $signature,
                    'code' => $quiz->code,
                    'name' => $quiz->name,
                    'start_date' => get_date($parts->start_date, 'H:i d/m/Y'),
                    'end_date' => get_date($parts->end_date, 'H:i d/m/Y'),
                    'url' => route('module.quiz', ['id' => $id])
                ];
                $automail->users = [$user->user_id];
                $automail->check_exists = true;
                $automail->check_exists_status = 0;
                $automail->object_id = $quiz->id;
                $automail->object_type = 'register_quiz_remind';
                $automail->addToAutomail();
            }
        }

        json_message('Gửi mail thành công');
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

    public function modalInfo($id, Request $request)
    {
        $model = Quiz::find($id);
        $created_at2 = get_date($model->created_at, 'H:i d/m/Y');

        $created_by = $model->created_by ? $model->created_by : 2;
        $updated_by = $model->updated_by ? $model->updated_by : 2;
        $user_created = ProfileView::where('user_id', $created_by)->first();
        $user_updated = ProfileView::where('user_id', $updated_by)->first();

        return view('quiz::backend.modal.modal_info', [
            'created_at2' => $created_at2,
            'user_created' => $user_created,
            'user_updated' => $user_updated,
        ]);
    }

    public function modalCourse($course_id, $course_type, Request $request)
    {
        $course_view = CourseView::whereCourseId($course_id)->whereCourseType($course_type)->first(['code', 'name', 'start_date', 'end_date']);

        if ($course_type == 1) {
            $course_type_text = 'Online';
            $course_url = userCan('online-course-edit') ? route('module.online.edit', ['id' => $course_id]) : '';
        } else {
            $course_type_text = trans("latraining.offline");
            $course_url = userCan('offline-course-edit') ? route('module.offline.edit', ['id' => $course_id]) : '';
        }

        return view('quiz::backend.modal.modal_course_quiz', [
            'course_view' => $course_view,
            'course_type_text' => $course_type_text,
            'course_url' => $course_url,
        ]);
    }

    public function loadExamTemplate(Request $request)
    {
        $data = QuizTemplates::query()->findOrFail($request->exam_template_id);
        $data->img_view = image_quiz($data->img);

        json_result([
            'status' => 'success',
            'data' => $data,
        ]);
    }

    public function settingAlert()
    {
        $model = QuizSettingAlert::first();
        return view('quiz::backend.setting_alert.index', [
            'model' => $model,
        ]);
    }

    public function saveSettingAlert(Request $request)
    {
        $this->validateRequest([
            'from_time' => 'required',
            'to_time' => 'required',
        ], $request);

        if ($request->from_time > $request->to_time) {
            json_message('Khoảng thời gian không đúng', 'error');
        }

        $model = QuizSettingAlert::query()->firstOrNew(['id' => $request->id]);
        $model->fill($request->all());
        $model->save();

        return response()->json([
            'status' => 'success',
            'message' => trans('laother.successful_save'),
            'redirect' => route('module.quiz.setting_alert')
        ]);
    }

    public function userSecondNote()
    {
        return view('quiz::backend.user_secondary.note', [
        ]);
    }

    public function getDataNoteByUserSecond(Request $request)
    {
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $search = $request->input('search');

        $query = QuizNoteByUserSecond::query();
        $query->select([
            'note.id',
            'note.user_id',
            'user_second.code',
            'user_second.name',
            'quiz.name as quiz_name',
            'quiz.code as quiz_code',
            'note.title',
            'note.content',
            'note.created_at',
        ]);
        $query->from('el_quiz_note_by_user_second as note');
        $query->leftJoin('el_quiz_user_secondary as user_second', 'user_second.id', '=', 'note.user_id');
        $query->leftJoin('el_quiz as quiz', 'quiz.id', '=', 'note.quiz_id');

        if ($search) {
            $query->where(function ($sub_query) use ($search) {
                $sub_query->orWhere('user_second.name', 'like', '%' . $search . '%');
                $sub_query->orWhere('user_second.code', 'like', '%' . $search . '%');
            });
        }

        $count = $query->count();
        $query->orderBy('note.user_id', $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();
        foreach ($rows as $row) {
            $row->created_at2 = date_format($row->created_at, 'd/m/Y h:i:s');
        }

        return response()->json(['total' => $count, 'rows' => $rows]);
    }

    public function removeUserSecondNote(Request $request)
    {
        $ids = $request->input('ids', null);

        QuizNoteByUserSecond::destroy($ids);

        json_result([
            'status' => 'success',
            'message' => trans('laother.delete_success'),
        ]);
    }

    public function dashboard()
    {
        $attempt_user_second = QuizAttempts::query()->where('type', '=', 2)->count();
        $attempt_user = QuizAttempts::query()->where('type', '=', 1)->count();
        $count_quiz = Quiz::query()->where('status', '=', 1)->where('is_open', '=', 1)->count();
        $quizs = Quiz::query()->where('status', '=', 1)->where('is_open', '=', 1)->get();
        $quiz_types = QuizType::get();

        return view('quiz::backend.dashboard.index', [
            'attempt_user_second' => $attempt_user_second,
            'attempt_user' => $attempt_user,
            'count_quiz' => $count_quiz,
            'quiz_types' => $quiz_types,
            'quizs' => $quizs,
        ]);
    }

    public function getChartUser(Request $request)
    {
        $start_date = date('Y-m-d', strtotime(str_replace('/', '-', $request->start_date)));
        $end_date = date('Y-m-d', strtotime(str_replace('/', '-', $request->end_date)));
        $user_type = $request->user_type;
        $title = $request->title;
        $quiz_type = $request->quiz_type;
        $quiz = $request->quiz;

        $forms = QuizRegister::query()
            ->select([
                'register.quiz_id',
                'register.user_id',
                'register.type',
            ])
            ->from('el_quiz_register as register')
            ->leftJoin('el_quiz_part as part', 'part.quiz_id', '=', 'register.quiz_id')
            ->leftJoin('el_quiz as quiz', 'quiz.id', '=', 'register.quiz_id')
            ->leftJoin('el_profile as profile', 'profile.user_id', '=', 'register.user_id')
            ->leftJoin('el_titles as title', 'title.code', '=', 'profile.title_code')
            ->where('part.start_date', '>=', ($start_date . ' 00:00:00'))
            ->where('part.end_date', '<=', ($end_date . ' 23:59:59'));
        if ($user_type) {
            $forms->where('register.type', '=', $user_type);
        }
        if ($title) {
            $forms->where('title.id', '=', $title);
        }

        if ($quiz_type) {
            $forms->whereIn('quiz.type_id', $quiz_type);
        }

        if ($quiz) {
            $forms->where('quiz.id', $quiz);
        }

        $forms = $forms->get();

        $data = [];
        $result_1 = 0;
        $result_0 = 0;
        $absent = 0;
        foreach ($forms as $form) {
            $result_1 += QuizResult::where('quiz_id', '=', $form->quiz_id)
                ->where('user_id', '=', $form->user_id)
                ->where('result', '=', 1)
                ->where('type', '=', $form->type)
                ->whereNull('text_quiz')
                ->count();

            $result_0 += QuizResult::where('quiz_id', '=', $form->quiz_id)
                ->where('user_id', '=', $form->user_id)
                ->where('result', '=', 0)
                ->where('type', '=', $form->type)
                ->whereNull('text_quiz')
                ->count();
        }

        $absent += $forms->count() - ($result_1 + $result_0);

        $data[] = ['', 'Thống kê'];
        $data[] = ['Đạt', $result_1];
        $data[] = ['Không đạt', $result_0];
        $data[] = ['Vắng thi', $absent];

        return \response()->json($data);
    }

    public function exportDashboard(Request $request)
    {
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $user_type = $request->user_type;
        $title = $request->title;
        $quiz_type = $request->quiz_type;
        $quiz = $request->quiz;

        return (new DashboardExport($start_date, $end_date, $user_type, $title, $quiz_type, $quiz))->download('thong_ke_so_luong_thi_sinh' . date('d_m_Y') . '.xlsx');
    }

    public function historyUser()
    {
        $max_unit = Unit::getMaxUnitLevel();
        $level_name = function ($level) {
            return Unit::getLevelName($level);
        };

        return view('backend.history_quiz.index', [
            'max_unit' => $max_unit,
            'level_name' => $level_name,
        ]);
    }

    public function getDataHistoryUser(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');
        $unit = $request->unit_id;
        $title = $request->input('title');

        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        /*Profile::addGlobalScope(new DraftScope('user_id'));*/
        $query = Profile::query();
        $query->select([
            'el_profile.id',
            'el_profile.user_id',
            'el_profile.code',
            'el_profile.email',
            'el_profile.firstname',
            'el_profile.lastname',
            'el_profile.status',
            'b.name AS unit_name',
            'c.name AS title_name',
            'd.name AS area_name',
            'e.name AS unit_manager',
        ]);
        $query->from('el_profile');
        $query->leftJoin('el_unit AS b', 'b.code', '=', 'el_profile.unit_code');
        $query->leftJoin('el_area AS area', 'area.id', '=', 'b.area_id');
        $query->leftJoin('el_unit AS e', 'e.code', '=', 'b.parent_code');
        $query->leftJoin('el_titles AS c', 'c.code', '=', 'el_profile.title_code');
        $query->leftJoin('el_area AS d', 'd.code', '=', 'el_profile.area_code');
        $query->where('el_profile.user_id', '>', 2);
        $query->where('el_profile.type_user', 1);
        $query->whereIn('el_profile.user_id', function ($sub) {
            $sub->select(['user_id'])
                ->from('el_quiz_result')
                ->groupBy('user_id')
                ->pluck('user_id')
                ->toArray();
        });

        if ($search) {
            $query->where(function ($sub_query) use ($search) {
                $sub_query->orWhere(\DB::raw('CONCAT(lastname, \' \', firstname)'), 'like', '%' . $search . '%');
                $sub_query->orWhere('el_profile.code', 'like', '%' . $search . '%');
                $sub_query->orWhere('el_profile.email', 'like', '%' . $search . '%');
            });
        }
        if ($request->area) {
            $area = Area::find($request->area);
            $area_id = Area::getArrayChild($area->code);
            $query->where(function ($sub_query) use ($area, $area_id) {
                $sub_query->orWhereIn('area.id', $area_id);
                $sub_query->orWhere('area.id', '=', $area->id);
            });
        }
        if (!is_null($status)) {
            $query->where('el_profile.status', '=', $status);
        }

        if ($unit) {
            $unit = Unit::whereIn('id', explode(';', $unit))->latest('id')->first();
            $unit_id = Unit::getArrayChild($unit->code);

            $query->where(function ($sub_query) use ($unit_id, $unit) {
                $sub_query->orWhereIn('b.id', $unit_id);
                $sub_query->orWhere('b.id', '=', $unit->id);
            });
        }

        if ($title) {
            $title = Titles::where('id', '=', $title)->first();
            $query->where('el_profile.title_code', '=', $title->code);
        }

        $count = $query->count();
        $query->orderBy('el_profile.' . $sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row) {
            $row->quiz_history_url = route('module.quiz.history_result_user', ['user_id' => $row->user_id]);
            $row->unit_url = route('module.backend.user.get_unit', ['user_id' => $row->user_id]);
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function historyResultUser($user_id)
    {
        return view('quiz::backend.history_user.form', [
            'user_id' => $user_id
        ]);
    }

    public function getDataHistoryResultUser($user_id, Request $request)
    {
        $search = $request->input('search');

        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = QuizAttempts::query()
            ->select([
                'attempts.*',
                'quiz.code as quiz_code',
                'quiz.name as quiz_name',
                'quiz.pass_score',
                'part.name as part_name',
            ])
            ->from('el_quiz_attempts as attempts')
            ->leftJoin('el_quiz as quiz', 'quiz.id', '=', 'attempts.quiz_id')
            ->leftJoin('el_quiz_part as part', 'part.id', '=', 'attempts.part_id')
            ->where('attempts.user_id', '=', $user_id)
            ->where('attempts.type', '=', 1);

        if ($search) {
            $query->where(function ($sub_query) use ($search) {
                $sub_query->orWhere('quiz.code', 'like', '%' . $search . '%');
                $sub_query->orWhere('quiz.name', 'like', '%' . $search . '%');
            });
        }

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row) {
            $row->time_start = date('H:i d/m/Y', $row->timestart);
            $row->time_end = $row->timefinish > 0 ? date('H:i d/m/Y', $row->timefinish) : '';

            $row->score = number_format($row->sumgrades, 2);
            $row->result = ($row->sumgrades >= $row->pass_score ? 'Đạt' : 'Không đạt');

            $row->review_url = route('module.quiz.history_user.view_quiz', [$row->quiz_id, $row->id, $user_id, 1]);
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function exportHistoryUser(Request $request)
    {
        $status = $request->status;
        $unit = isset($request->unit) ? $request->unit : '';
        $title = isset($request->title) ? $request->title : '';
        $search = $request->search;

        return (new HistoryUserExport($unit, $title, $status, $search))->download('lich_su_thi_tuyen_thi_sinh_noi_bo_' . date('d_m_Y') . '.xlsx');
    }

    public function historyUserSecond()
    {
        // return view('quiz::backend.history_user_second.index');
        return view('backend.history_quiz.index', [
        ]);
    }

    public function getDataHistoryUserSecond(Request $request)
    {
        $search = $request->input('search');
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        /*QuizUserSecondary::addGlobalScope(new DraftScope());*/
        $query = Profile::query();
        $query->where('type_user', 2);
        $query->whereIn('user_id', function ($sub) {
            $sub->select(['user_id'])
                ->from('el_quiz_result')
                ->groupBy('user_id')
                ->pluck('user_id')
                ->toArray();
        });

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
            $row->fullname = $row->lastname . ' ' . $row->firstname;
            $row->username = str_replace('secondary_', '', $row->fullname);
            $row->quiz_history_url = route('module.quiz.history_result_user_second', ['user_id' => $row->id]);
            $row->dob = get_date($row->dob, 'd/m/Y');
            $row->created_at2 = get_date($row->created_at, 'd/m/Y');
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function historyResultUserSecond($user_id)
    {
        $user_second = QuizUserSecondary::find($user_id);
        return view('quiz::backend.history_user_second.form', [
            'user_id' => $user_id,
            'user_second' => $user_second,
        ]);
    }

    public function getDataHistoryResultUserSecond($user_id, Request $request)
    {
        $search = $request->input('search');

        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = QuizAttempts::query()
            ->select([
                'attempts.*',
                'quiz.code as quiz_code',
                'quiz.name as quiz_name',
                'quiz.pass_score'
            ])
            ->from('el_quiz_attempts as attempts')
            ->leftJoin('el_quiz as quiz', 'quiz.id', '=', 'attempts.quiz_id')
            ->where('attempts.user_id', '=', $user_id)
            ->where('attempts.type', '=', 2);

        if ($search) {
            $query->where(function ($sub_query) use ($search) {
                $sub_query->orWhere('quiz.code', 'like', '%' . $search . '%');
                $sub_query->orWhere('quiz.name', 'like', '%' . $search . '%');
            });
        }

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row) {
            $part = QuizPart::find($row->part_id);
            $result = QuizResult::where('quiz_id', '=', $row->quiz_id)->whereNull('text_quiz')->where('user_id', '=', $user_id)->where('type', '=', 2)->first();

            $row->time_start = get_date($part->start_date);
            $row->time_end = get_date($part->end_date);
            $row->grade = $result ? number_format($result->grade, 2) : '';
            $row->result = $result ? ($result->grade >= $row->pass_score ? 'Đạt' : 'Không đạt') : '';

            $row->reexamine = $result ? number_format($result->reexamine, 2) : '';
            $row->result_reexamine = $result ? ($result->reexamine >= $row->pass_score ? 'Đạt' : 'Không đạt') : '';
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function exportHistoryUserSecond(Request $request)
    {
        $search = $request->search;

        return (new HistoryUserSecondExport($search))->download('lich_su_thi_tuyen_thi_sinh_ben_ngoai_' . date('d_m_Y') . '.xlsx');
    }

    public function dataOldQuiz()
    {
        return view('quiz::backend.data_old.index', [
        ]);
    }

    public function getDataOldQuiz(Request $request)
    {
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $title = $request->title;
        $search = $request->search;
        $result = $request->result;

        $query = QuizDataOld::query();

        if ($result) {
            $query->where('result', $result);
        }

        if ($search) {
            $query->where(function ($sub) use ($search) {
                $sub->where('user_code', 'like', '%' . $search . '%');
                $sub->orWhere('user_name', 'like', '%' . $search . '%');
                $sub->orWhere('quiz_code', 'like', '%' . $search . '%');
                $sub->orWhere('quiz_code', 'like', '%' . $search . '%');
            });
        }

        if ($title) {
            $get_title_name = Titles::find($title);
            $query->where('title', $get_title_name->name);
        }

        if ($request->unit_id) {
            $unit = explode(';', $request->unit_id);
            $get_unit_name = Unit::whereIn('id', $unit)->pluck('name')->toArray();
            $query->where(function ($sub) use ($get_unit_name) {
                $sub->whereIn('unit', $get_unit_name);
                $sub->orWhereIn('area', $get_unit_name);
            });
        }

        if ($request->start_date) {
            $start_date = date_convert($request->start_date);
            $query->where('start_date', '<=', $start_date);
        }

        if ($request->end_date) {
            $end_date = date_convert($request->end_date);
            $query->where('end_date', '>=', $end_date);
        }

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();

        foreach ($rows as $row) {
            $row->edit_url = route('module.quiz.data_old_quiz.edit', [$row->id]);
            $row->start_date = $row->start_date ? date('d/m/Y', strtotime($row->start_date)) : '';
            $row->end_date = $row->end_date ? date('d/m/Y', strtotime($row->end_date)) : '';
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function removeDataOldQuiz(Request $request)
    {
        $ids = $request->input('ids', null);
        QuizDataOld::destroy($ids);
        json_result([
            'status' => 'success',
            'message' => trans('laother.delete_success'),
        ]);
    }

    public function exportDataOldQuiz(Request $request)
    {
        $search = $request->search;
        $title = $request->title;
        $unit = $request->unit;
        $result = $request->result;
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        return (new ExportDataOldQuiz($search, $title, $unit, $result, $start_date, $end_date))->download('danh_sach_du_lieu_ky_thi_cu_' . date('d_m_Y') . '.xlsx');
    }

    public function importDataOldQuiz(Request $request)
    {
        $this->validateRequest([
            'import_file' => 'required|file'
        ], $request, ['import_file' => 'File import']);

        $import = new ImportQuizDataOld();
        \Excel::import($import, $request->file('import_file'));

        if ($import->errors) {
            session()->put('errors', $import->errors);
            session()->save();
        }

        json_result([
            'status' => 'success',
            'message' => 'Import ' . trans('laapi.success'),
            'redirect' => route('module.quiz.data_old_quiz'),
        ]);
    }

    public function editDataOldQuiz($id)
    {
        $model = QuizDataOld::firstOrNew(['id' => $id]);

        return view('quiz::backend.data_old.form', [
            'model' => $model,
        ]);
    }

    public function saveEditDataOldQuiz(Request $request)
    {
        $start_date = date_convert($request->input('start_date'));
        $end_date = date_convert($request->input('end_date'));;

        $model = QuizDataOld::find($request->id);
        $model->fill($request->all());
        $model->start_date = $start_date;
        $model->end_date = $end_date;
        $model->save();

        json_result([
            'status' => 'success',
            'message' => trans('laother.successful_save'),
            'redirect' => route('module.quiz.data_old_quiz'),
        ]);
    }

    public function getDataSuggestions($quiz_id, Request $request) {
        $search = $request->input('search');
        $title = $request->input('title');
        $unit = $request->input('unit_id');

        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = QuizUserReview::query();
        $query->select([
            'review.id',
            'review.content',
            'profile.full_name',
            'profile.title_name',
            'profile.unit_name',
            'profile.parent_unit_name',
            'profile.code',
        ]);
        $query->from('el_quiz_user_review as review');
        $query->join('el_profile_view as profile', function($join) {
            $join->on('profile.user_id', '=', 'review.user_id');
            $join->on('profile.type_user', '=', 'review.user_type');
        });
        $query->where('review.quiz_id', '=', $quiz_id);

        if ($search) {
            $query->where(function ($sub_query) use ($search) {
                $sub_query->orWhere('profile.code', 'like', '%'. $search .'%');
                $sub_query->orWhere('profile.full_name', 'like', '%'. $search .'%');
                $sub_query->orWhere('profile.email', 'like', '%'. $search .'%');
                $sub_query->orWhere('review.username', 'like', '%'. $search .'%');
            });
        }

        if ($title) {
            $query->where('profile.title_id', '=', $title);
        }
        if ($unit) {
            $unit = Unit::where('id', '=', $unit)->first();
            $unit_id = Unit::getArrayChild($unit->code);

            $query->where(function ($sub_query) use ($unit_id, $unit) {
                $sub_query->orWhereIn('profile.unit_id', $unit_id);
                $sub_query->orWhere('profile.unit_id', '=', $unit->id);
            });
        }

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row) {
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function viewQuizHistory($quiz_id, $attempt_id, $user_id, $type)
    {
        $quiz = Quiz::findOrFail($quiz_id);

        $attempt = $quiz->attempts()
            ->where('id', $attempt_id)
            ->where('quiz_id', '=', $quiz_id)
            ->where('user_id', '=', $user_id)
            ->where('type', '=', $type)
            ->firstOrFail();

        $template = QuizAttempts::getQuizData($attempt->id);

        $questions = $template['questions'];
        $qqcategorys = $template['categories'];

        $qqcategory = [];
        foreach ($qqcategorys as $item) {
            $qqcategory['num_' . $item['num_order']] = $item['name'];
            $qqcategory['percent_' . $item['num_order']] = $item['percent_group'];
        }
        if ($type == 1) {
            $profile = Profile::find($user_id);
            $full_name = $profile->lastname . ' ' . $profile->firstname;
        } else {
            $profile = QuizUserSecondary::find($user_id);
            $full_name = $profile->name;
        }

        return view('quiz::backend.history_user.view', [
            'quiz' => $quiz,
            'attempt' => $attempt,
            'user_id' => $user_id,
            'type' => $type,
            'questions' => $questions,
            'disabled' => 1,
            'qqcategory' => $qqcategory,
            'profile' => $profile,
            'full_name' => $full_name,
        ]);
    }

    public function getQuestionHistory($quiz_id, $attempt_id, $user_id, $type, Request $request)
    {
        $quiz = Quiz::findOrFail($quiz_id);
        $attempt = $quiz->attempts()
            ->where('id', $attempt_id)
            ->where('quiz_id', '=', $quiz_id)
            ->where('user_id', '=', $user_id)
            ->where('type', '=', $type)
            ->firstOrFail();

        $template = QuizAttempts::getQuizData($attempt->id);

        $total = count($template['questions']);
        $total_page = ceil($total / $quiz->questions_perpage);

        $page = $request->get('page');
        $offset = ($page - 1) * $quiz->questions_perpage;
        if ($offset < 0) $offset = 0;

        $rows = array_slice($template['questions'], $offset, $quiz->questions_perpage);

        $next = false;
        if ($page < $total_page) {
            $next = true;
        }

        $data = ['rows' => $rows, 'next' => $next];
        return \response()->json($data);
    }

    public function ajaxQuizPart(Request $request)
    {
        $quiz_questions = QuizQuestion::whereQuizId($request->quizId)->exists();
        if (!$quiz_questions) {
            json_message('Kỳ thi chưa có câu hỏi', 'error');
        }
        $quizId = $request->quizId;
        $quizPart = QuizPart::where('quiz_id', $quizId)->get();
        if ($quizPart->isEmpty()) {
            json_message('Kỳ thi chưa có ca thi', 'error');
        }
        Quiz::find($request->quizId)->update(['flag' => 1]);
        \Artisan::call('quiz:create_template');
        json_result($quizPart);
    }
}
