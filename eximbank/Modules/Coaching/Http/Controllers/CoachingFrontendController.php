<?php

namespace Modules\Coaching\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ProfileView;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Coaching\Entities\CoachingGroup;
use Modules\Coaching\Entities\CoachingMentorMethod;
use Modules\Coaching\Entities\CoachingTeacher;
use Modules\Coaching\Entities\CoachingTeacherRegister;

class CoachingFrontendController extends Controller
{
    public function index()
    {
        $coaching_group = CoachingGroup::where('status', 1)->get();
        $coaching_teacher = CoachingTeacher::query()
            ->with([
                'user' => function ($q){
                    $q->select('id','code','firstname','lastname');
                },
                'coaching_group' => function ($e){
                    $e->select('id','code','name');
                },
            ])
            ->where('el_coaching_teacher.status', 1)->get();

        $coaching_teacher_registers = CoachingTeacherRegister::query()
            ->with([
                'coaching_teacher' => function($q){
                    $q->select('id', 'user_id')
                    ->with([
                        'user' => function ($u){
                            $u->select('id','code','firstname','lastname');
                        },
                    ]);
                },
            ])
            ->where('el_coaching_teacher_register.created_by', profile()->user_id)->get();

        return view('coaching::frontend.coaching_teacher.index', [
            'coaching_group' => $coaching_group,
            'coaching_teacher' => $coaching_teacher,
            'coaching_teacher_registers' => $coaching_teacher_registers,
        ]);
    }

    //lịch sử kèm cặp
    public function history()
    {
        $coaching_teacher_registers = CoachingTeacherRegister::query()
            ->select([
                'el_coaching_teacher_register.*',
                'profile.code as user_code',
                'profile.firstname',
                'profile.lastname',
                'coaching_teacher.user_id as coaching_teacher_user_id',
            ])
            ->leftJoin('el_coaching_teacher as coaching_teacher', 'coaching_teacher.id', '=', 'el_coaching_teacher_register.coaching_teacher_id')
            ->leftJoin('el_profile as profile', 'profile.user_id', '=', 'el_coaching_teacher_register.created_by')
            ->where('coaching_teacher.user_id', profile()->user_id)
            ->get();

        return view('coaching::frontend.coaching_teacher.history', [
            'coaching_teacher_registers' => $coaching_teacher_registers,
        ]);
    }

    // Đăng ký thành GV
    public function registerTeacher(Request $request){
        $this->validateRequest ([
            'image' => 'required',
            'technique' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'coaching_group_id' => 'required',
            'number_coaching' => 'required',
        ], $request, CoachingTeacher::getAttributeName());

        $model = CoachingTeacher::firstOrNew(['id' => $request->id]);
        $model->fill($request->all());
        $model->user_id = profile()->user_id;
        $model->start_date = date_convert($request->start_date);
        $model->end_date = date_convert($request->end_date, '23:59:59');
        $model->image = upload_image([600,400], $request->image);

        if ($model->save()) {
            json_result([
                'status' => 'success',
                'message' => 'Đăng ký thành công. Xin chờ duyệt!',
                'redirect' => route('module.coaching.frontend'),
            ]);
        }
        json_message(trans('laother.save_error'), 'error');
    }

    //Form tạo/sửa nội dung/đăng ký
    public function form($id = null, Request $request){
        //Chọn sẵn GV khi bấm đăng ký
        $coaching_teacher_id = $request->coaching_teacher_id;

        //user_id của Coaching teacher
        $coaching_teacher_user_id = $request->coaching_teacher_user_id;

        $coaching_teachers = CoachingTeacher::query()
            ->with([
                'user' => function ($q){
                    $q->select('id','code','firstname','lastname');
                }
            ])
            ->where('el_coaching_teacher.status', 1);
            if(is_null($id)){
               $coaching_teachers->where('el_coaching_teacher.full_class', 0);
            }
        $coaching_teachers = $coaching_teachers->get();

        $coaching_mentor_methor = CoachingMentorMethod::where('status', 1)->get();

        $profile_view = '';

        $model = CoachingTeacherRegister::firstOrNew(['id' => $id]);
        if($model){
            if($model->students){
                $students = explode(',', $model->students);
                $profile_view = ProfileView::whereIn('user_id', $students)->get(['user_id', 'full_name', 'code']);
            }

            $plan_content = $model->plan_content ? explode(',', $model->plan_content) : '';
            $plan_start = $model->plan_start ? explode(',', $model->plan_start) : '';
            $plan_perform = $model->plan_perform ? explode(',', $model->plan_perform) : '';
            $plan_note = $model->plan_note ? explode(',', $model->plan_note) : '';
        }

        $disable_not_teacher = ($coaching_teacher_user_id == profile()->user_id) ? '' : 'disabled';

        return view('coaching::frontend.coaching_teacher.form', [
            'coaching_teachers' => $coaching_teachers,
            'coaching_teacher_id' => $coaching_teacher_id,
            'coaching_mentor_methor' => $coaching_mentor_methor,
            'model' => $model,
            'profile_view' => $profile_view,
            'plan_content' => $plan_content,
            'plan_start' => $plan_start,
            'plan_perform' => $plan_perform,
            'plan_note' => $plan_note,
            'coaching_teacher_user_id' => $coaching_teacher_user_id,
            'disable_not_teacher' => $disable_not_teacher,
        ]);
    }

    //Lưu nội dung/kỹ năng
    public function saveContentSkill(Request $request)
    {
        $view = $request->view;

        $this->validateRequest ([
            'coaching_teacher_id' => 'required',
            'content' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
        ], $request, CoachingTeacherRegister::getAttributeName());

        $model = CoachingTeacherRegister::firstOrNew(['id' => $request->id]);
        $model->fill($request->all());
        $model->start_date = date_convert($request->start_date);
        $model->end_date = date_convert($request->end_date, '23:59:59');
        $model->students = implode(',', $request->students);
        $model->plan_content = implode(',', $request->plan_content);
        $model->plan_start = implode(',', $request->plan_start);
        $model->plan_perform = implode(',', $request->plan_perform);
        $model->plan_note = implode(',', $request->plan_note);

        if ($model->save()) {
            $count_register = CoachingTeacherRegister::where('coaching_teacher_id', $request->coaching_teacher_id)->count();

            CoachingTeacher::where('id', $request->coaching_teacher_id)
                ->where('number_coaching', $count_register)
                ->update([
                    'full_class' => 1,
                ]);

            $redirect = ($view == 'history' ? route('module.coaching.frontend.history') : route('module.coaching.frontend'));

            json_result([
                'status' => 'success',
                'message' => trans('laother.successful_save'),
                'redirect' => $redirect,
            ]);
        }
        json_message(trans('laother.save_error'), 'error');
    }
}
