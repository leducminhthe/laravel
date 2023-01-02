<?php

namespace Modules\Offline\Http\Controllers;

use App\Models\Automail;
use App\Models\ProfileView;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Offline\Entities\OfflineCourse;
use Modules\Offline\Entities\OfflineCourseClass;
use Modules\Offline\Entities\OfflineRegister;
use Modules\Offline\Entities\OfflineTeacherClass;
use Modules\Offline\Entities\OfflineTeachingOrganizationAnswer;
use Modules\Offline\Entities\OfflineTeachingOrganizationAnswerMatrix;
use Modules\Offline\Entities\OfflineTeachingOrganizationCategory;
use Modules\Offline\Entities\OfflineTeachingOrganizationQuestion;
use Modules\Offline\Entities\OfflineTeachingOrganizationTemplate;
use Modules\Offline\Entities\OfflineTeachingOrganizationUser;
use Modules\Offline\Entities\OfflineTeachingOrganizationUserAnswer;
use Modules\Offline\Entities\OfflineTeachingOrganizationUserAnswerMatrix;
use Modules\Offline\Entities\OfflineTeachingOrganizationUserCategory;
use Modules\Offline\Entities\OfflineTeachingOrganizationUserQuestion;
use Modules\Offline\Exports\TeachingOrganizationExport;
use Modules\Rating\Entities\RatingAnswerMatrix;
use Modules\Rating\Entities\RatingCategory;
use Modules\Rating\Entities\RatingQuestion;
use Modules\Rating\Entities\RatingQuestionAnswer;
use Modules\Rating\Entities\RatingTemplate;

class TeachingOrganizationController extends Controller
{
    public function index($course_id, Request $request)
    {
        $course = OfflineCourse::find($course_id);
        $course_class = OfflineCourseClass::where('course_id', $course_id)->get();
        $templates_rating_teacher = RatingTemplate::where('teaching_organization', 1)->get(['id','name']); //Lấy mẫu có chọn đánh giá GV
        $offline_teaching_organization_template = OfflineTeachingOrganizationTemplate::where('course_id', $course_id)->first();

        $qrcode = '';
        if($course->template_rating_teacher_id){
            $route_qrcode = route('qrcode_process',['id' => $course->id, 'type' => 'rating-teaching-organization']);
            $qrcode = \QrCode::size(300)->generate($route_qrcode);
        }

        return view('offline::backend.teaching_organization.index',[
            'course' => $course,
            'course_class' => $course_class,
            'templates_rating_teacher' => $templates_rating_teacher,
            'offline_teaching_organization_template' => $offline_teaching_organization_template,
            'qrcode' => $qrcode,
        ]);
    }

    public function getData($course_id, Request $request){
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'asc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $search = $request->search;
        $class_id = $request->class_id;

        $query = OfflineTeachingOrganizationUser::query()
            ->select([
                'a.id',
                'a.user_id',
                'a.created_at',
                'register.code',
                'register.full_name',
                'register.unit_name',
                'register.title_name',
                'class.name as class_name',
                'template.name as template_name',
            ])
            ->from('el_offline_teaching_organization_user as a')
            ->leftJoin('el_offline_register_view as register', function($sub){
                $sub->on('register.user_id', '=', 'a.user_id');
                $sub->on('register.course_id', '=', 'a.course_id');
            })
            ->leftJoin('offline_course_class as class', 'class.id', '=', 'register.class_id')
            ->leftJoin('el_offline_teaching_organization_template as template', function($sub){
                $sub->on('template.id', '=', 'a.template_id');
                $sub->on('template.course_id', '=', 'a.course_id');
            })
            ->where('a.course_id', '=', $course_id);

        if($search){
            $query->leftJoin('user', 'user.id', '=', 'a.user_id');
            $query->where(function($sub) use($search){
                $sub->orWhere('register.code', 'like', '%'.$search.'%');
                $sub->orWhere('register.full_name', 'like', '%'.$search.'%');
                $sub->orWhere('register.email', 'like', '%'.$search.'%');
                $sub->orWhere('user.username', 'like', '%'.$search.'%');
            });
        }

        if($class_id){
            $query->where('register.class_id', '=', $class_id);
        }

        $count = $query->count();
        $query->orderBy($sort, 'asc');
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row) {
            $row->time_send = get_date($row->created_at, 'H:i:s d/m/Y');

            $row->view_rating = route('module.offline.teaching_organization.view_rating', ['course_id' => $course_id, 'user_id' => $row->user_id]);
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function export($course_id, Request $request){

        return (new TeachingOrganizationExport($course_id, $request))->download('Danh_gia_cong_tac_to_chuc_giang_day_'. date('d_m_Y') .'.xlsx');
    }

    public function viewRatingTeacher($course_id, $user_id, Request $request){
        $item = OfflineCourse::find($course_id);
        $organization_user = OfflineTeachingOrganizationUser::where('course_id', $course_id)->where('user_id', $user_id)->first();

        $categories = OfflineTeachingOrganizationUserCategory::where('teaching_organization_user_id', $organization_user->id)->get();
        $fquestions = function($category_id, $teacher_id = null){
            return OfflineTeachingOrganizationUserQuestion::where('teaching_organization_category_id', $category_id)->where('teacher_id', $teacher_id)->get();
        };
        $fanswer = function($question_id, $is_row = 1){
            if($is_row == 10){
                return OfflineTeachingOrganizationUserAnswer::where('teaching_organization_question_id', $question_id)->where('is_row', $is_row)->first();
            }else{
                return OfflineTeachingOrganizationUserAnswer::where('teaching_organization_question_id', $question_id)->where('is_row', $is_row)->get();
            }
        };
        $fanswer_matrix = function($question_id, $answer_row_id, $answer_col_id){
            return OfflineTeachingOrganizationUserAnswerMatrix::where('teaching_organization_question_id', $question_id)->where('answer_row_id', '=', $answer_row_id)->where('answer_col_id', '=', $answer_col_id)->first();
        };

        $register = OfflineRegister::whereCourseId($course_id)->whereUserId($user_id)->first();
        $teachers = OfflineTeacherClass::query()
            ->select([
                'teacher.id',
                'teacher.code',
                'teacher.name',
                'el_offline_teacher_class.class_id',
            ])
            ->leftJoin('el_training_teacher as teacher', 'teacher.id', '=', 'el_offline_teacher_class.teacher_id')
            ->where('el_offline_teacher_class.course_id', $course_id)
            ->where('el_offline_teacher_class.class_id', $register->class_id)
            ->get();

        $profile = ProfileView::where('user_id', $user_id)->first(['full_name']);

        return view('offline::backend.teaching_organization.view_rating', [
            'profile' => $profile,
            'organization_user' => $organization_user,
            'item' => $item,
            'categories' => $categories,
            'fquestions' => $fquestions,
            'fanswer' => $fanswer,
            'fanswer_matrix' => $fanswer_matrix,
            'teachers' => $teachers,
        ]);
    }

    public function viewRatingTemplate($course_id, $template_id, Request $request){
        $item = OfflineCourse::find($course_id);

        $categories = OfflineTeachingOrganizationCategory::where('template_id', $template_id)->where('course_id', $course_id)->get();
        $fquestions = function($category_id) use($course_id){
            return OfflineTeachingOrganizationQuestion::where('category_id', $category_id)->where('course_id', $course_id)->get();
        };
        $fanswer = function($question_id, $is_row = 1) use($course_id){
            if($is_row == 10){
                return OfflineTeachingOrganizationAnswer::where('question_id', $question_id)->where('is_row', $is_row)->where('course_id', $course_id)->first();
            }else{
                return OfflineTeachingOrganizationAnswer::where('question_id', $question_id)->where('is_row', $is_row)->where('course_id', $course_id)->get();
            }
        };
        $fanswer_matrix = function($question_id, $answer_row_id, $answer_col_id) use($course_id){
            return OfflineTeachingOrganizationAnswerMatrix::where('question_id', $question_id)->where('answer_row_id', '=', $answer_row_id)->where('answer_col_id', '=', $answer_col_id)->where('course_id', $course_id)->first();
        };

        $teachers = OfflineTeacherClass::query()
            ->select([
                'teacher.id',
                'teacher.code',
                'teacher.name',
                'el_offline_teacher_class.class_id',
            ])
            ->leftJoin('el_training_teacher as teacher', 'teacher.id', '=', 'el_offline_teacher_class.teacher_id')
            ->where('el_offline_teacher_class.course_id', $course_id)
            ->get();

        return view('offline::backend.teaching_organization.view_rating_template', [
            'item' => $item,
            'categories' => $categories,
            'fquestions' => $fquestions,
            'fanswer' => $fanswer,
            'fanswer_matrix' => $fanswer_matrix,
            'teachers' => $teachers,
        ]);
    }

    public function updateTemplateRatingTeacher($course_id, Request $request){
        $model = OfflineCourse::find($course_id);
        $organization_user = OfflineTeachingOrganizationUser::where('course_id', $course_id)->first();

        if($organization_user){
            json_message('Đã có người tham gia. Không thể đổi mẫu', 'error');
        }

        OfflineTeachingOrganizationTemplate::where('course_id', $course_id)->delete();
        OfflineTeachingOrganizationCategory::where('course_id', $course_id)->delete();
        OfflineTeachingOrganizationQuestion::where('course_id', $course_id)->delete();
        OfflineTeachingOrganizationAnswer::where('course_id', $course_id)->delete();
        OfflineTeachingOrganizationAnswerMatrix::where('course_id', $course_id)->delete();

        $template_rating_teacher_id = $request->template_rating_teacher_id;
        $template = RatingTemplate::find($template_rating_teacher_id)->toArray();

        $new_template = new OfflineTeachingOrganizationTemplate();
        $new_template->fill($template);
        $new_template->id = $template['id'];
        $new_template->course_id = $model->id;
        $new_template->save();

        $categories = RatingCategory::query()->where('template_id', $template['id'])->get()->toArray();
        foreach ($categories as $category){
            $new_category = new OfflineTeachingOrganizationCategory();
            $new_category->fill($category);
            $new_category->id = $category['id'];
            $new_category->course_id = $model->id;
            $new_category->save();

            $questions = RatingQuestion::query()->where('category_id', $category['id'])->get()->toArray();
            foreach ($questions as $question){
                $new_question = new OfflineTeachingOrganizationQuestion();
                $new_question->fill($question);
                $new_question->id = $question['id'];
                $new_question->course_id = $model->id;
                $new_question->save();

                $answers = RatingQuestionAnswer::query()->where('question_id', $question['id'])->get()->toArray();
                foreach ($answers as $answer){
                    $new_answer = new OfflineTeachingOrganizationAnswer();
                    $new_answer->fill($answer);
                    $new_answer->id = $answer['id'];
                    $new_answer->course_id = $model->id;
                    $new_answer->save();
                }

                $answers_matrix = RatingAnswerMatrix::query()->where('question_id', $question['id'])->get()->toArray();
                foreach ($answers_matrix as $answer_matrix){
                    $new_answer_matrix = new OfflineTeachingOrganizationAnswerMatrix();
                    $new_answer_matrix->fill($answer_matrix);
                    $new_answer_matrix->course_id = $model->id;
                    $new_answer_matrix->save();
                }
            }
        }

        $model->template_rating_teacher_id = $template_rating_teacher_id;
        $model->save();

        json_result([
            'status' => 'success',
            'message' => 'Lưu mẫu thành công',
            'redirect' => route('module.offline.teaching_organization.index', [$course_id]),
        ]);
    }
}
