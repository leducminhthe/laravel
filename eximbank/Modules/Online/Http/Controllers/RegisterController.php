<?php

namespace Modules\Online\Http\Controllers;

use App\Models\Automail;
use App\Models\Categories\Subject;
use App\Models\Categories\Unit;
use App\Models\Categories\Area;
use App\Models\Permission;
use App\Models\Categories\UnitManager;
use App\Models\PermissionTypeUnit;
use App\Scopes\DraftScope;
use App\Models\UnitView;
use App\Models\UserPermissionType;
use App\Models\UserRole;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Online\Entities\OnlineCourseActivity;
use Modules\Online\Entities\OnlineInviteRegister;
use Modules\Online\Entities\OnlineObject;
use Modules\Online\Entities\OnlineRegister;
use Modules\Online\Entities\OnlineCourse;
use App\Models\Profile;
use App\Models\ProfileView;
use Modules\Online\Entities\OnlineRegisterApprove;
use Modules\Online\Entities\OnlineResult;
use Modules\Online\Imports\RegisterImport;
use Modules\Quiz\Entities\Quiz;
use Modules\Quiz\Entities\QuizPart;
use Modules\Quiz\Entities\QuizRegister;
use Modules\Quiz\Entities\QuizResult;
use Modules\User\Entities\TrainingProcess;
use Modules\Online\Exports\RegisterExport;
use App\Events\SaveTrainingProcessRegister;
use App\Events\SendMailRegister;

class RegisterController extends Controller
{
    public function index($course_id) {
        $errors = session()->get('errors');
        \Session::forget('errors');

        $online = OnlineCourse::findOrFail($course_id);

        $quiz_exists = OnlineCourseActivity::where('course_id', '=', $course_id)
            ->where('activity_id', '=', 2)
            ->get();
        $max_unit = Unit::getMaxUnitLevel();
        $level_name = function ($level) {
            return Unit::getLevelName($level);
        };

        $user_has_role_register = UserRole::query()
            ->whereIn('role_id', function ($sub){
                $sub->select(['a.role_id'])
                    ->from('el_role_has_permissions as a')
                    ->leftJoin('el_permissions as b', 'b.id', '=', 'a.permission_id')
                    ->whereIn('b.name', ['online-course-register', 'online-course-register-create'])
                    ->pluck('a.role_id')
                    ->toArray();
            })
            ->where('user_id', '!=', profile()->user_id)
            ->where('user_id', '>', 2)
            ->get();

        $user_invited = false;
        $check_user_invited = OnlineInviteRegister::query()
            ->where('course_id', '=', $course_id)
            ->where('user_id', '=', profile()->user_id);
        if ($check_user_invited->exists()){
            $user_invited = true;
        }

        return view('online::backend.register.index', [
            'online' => $online,
            'quiz_exists' => $quiz_exists,
            'max_unit' => $max_unit,
            'level_name' => $level_name,
            'user_has_role_register' => $user_has_role_register,
            'user_invited' => $user_invited,
        ]);
    }

    public function getData($course_id, Request $request) {
        $search = $request->input('search');
        $title = $request->input('title');
        $unit = $request->unit_id;
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        /*$manager = UnitManager::getIdUnitManagedByUser();

        $user_invited = false;
        $condition = '';
        $check_user_invited = OnlineInviteRegister::query()
            ->where('course_id', '=', $course_id)
            ->where('user_id', '=', profile()->user_id);
        if ($check_user_invited->exists()){
            $user_invited = true;
            $user_permission_type = UserPermissionType::query()
                ->select(['permission_type_id'])
                ->whereUserId(profile()->user_id)
                ->groupBy('permission_type_id')
                ->first();
            $condition=PermissionTypeUnit::conditionUnitGroup(@$user_permission_type->permission_type_id);
        }*/
        OnlineRegister::addGlobalScope(new DraftScope());
        $query = OnlineRegister::query();
        $query->select([
            'el_online_register.*',
            'b.full_name',
            'b.email',
            'b.code',
            'b.title_name',
            'b.parent_unit_name',
            'd.name AS unit_name'
        ]);

        $query->from('el_online_register');
        $query->join('el_profile_view AS b', 'b.user_id', '=', 'el_online_register.user_id');
        $query->leftJoin('el_unit AS d', 'd.id', '=', 'b.unit_id');
        $query->leftJoin('el_area AS area', 'area.id', '=', 'd.area_id');
        $query->where('el_online_register.course_id', '=', $course_id);
        $query->where('el_online_register.user_type', '=', 1);
        $query->where('el_online_register.user_id', '>', 2);

        /*if ($user_invited){
            $query->whereExists(function ($queryExists) use ($condition){
                $queryExists->select('id')
                    ->from('el_unit_view')
                    ->whereColumn(['id'=>'d.id']);
                if ($condition)
                    $queryExists->whereRaw($condition);
                else
                    $queryExists->whereRaw("1=-1");
            });
        }*/

        /*if (!Permission::isAdmin()){
            $query->whereIn('d.id', $manager);
        }*/

        if ($search) {
            $query->where(function ($sub_query) use ($search) {
                $sub_query->orWhere('b.full_name', 'like', '%'. $search .'%');
                $sub_query->orWhere('b.code', 'like', '%'. $search .'%');
                $sub_query->orWhere('b.email', 'like', '%'. $search .'%');
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
        if ($title) {
            $query->where('c.id', '=', $title);
        }

        if ($unit) {
            $unit = Unit::whereIn('id', explode(';', $unit))->latest('id')->first();
            $unit_id = Unit::getArrayChild($unit->code);

            $query->where(function ($sub_query) use ($unit_id, $unit) {
                $sub_query->orWhereIn('d.id', $unit_id);
                $sub_query->orWhere('d.id', '=', $unit->id);
            });
        }

        $count = $query->count();
        $query->orderBy('el_online_register.'.$sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();

        foreach ($rows as $row) {
            $row->info_url = route('module.online.register.modal_info', ['id' => $row->course_id, 'register_id' => $row->id]);

            $quiz_register = QuizRegister::where('user_id', '=', $row->user_id)->where('type', '=', 1)->get();

            $quiz_name = [];
            foreach ($quiz_register as $register){
                $quiz = Quiz::query()
                    ->select(['name'])
                    ->from('el_quiz')
                    ->where('id', '=', $register->quiz_id)
                    ->where('course_id','=', $row->course_id)
                    ->where('course_type', '=', 1)
                    ->get();

                foreach ($quiz as $item){
                    $quiz_name[] = $item->name;
                }
            }

            $row->quiz_name = implode(', ', $quiz_name);

            if (empty($row->parent_name)){
                $row->parent = $row->unit_name;
                $row->unit = '';
            }else{
                $row->parent = $row->parent_name;
                $row->unit = $row->unit_name;
            }

            // thời gian ghi danh, hiển thị cho ghi danh tự động. có register_form = 2
            $row->time_register = get_date($row->created_at);
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function getDataNotRegister($course_id, Request $request){
        $search = $request->input('search');
        $title = $request->input('title');
        $unit = $request->unit_id;

        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);
//        $course = OnlineCourse::where('id', '=', $course_id)->first();

//        $manager = UnitManager::getIdUnitManagedByUser();

        $user_invited = false;
        $condition = '';
        /*$check_user_invited = OnlineInviteRegister::query()
            ->where('course_id', '=', $course_id)
            ->where('user_id', '=', profile()->user_id);
        if ($check_user_invited->exists()){
            $user_invited = true;
            $user_permission_type = UserPermissionType::query()
                ->select(['permission_type_id'])
                ->whereUserId(profile()->user_id)
                ->groupBy('permission_type_id')
                ->first();
            $condition=PermissionTypeUnit::conditionUnitGroup(@$user_permission_type->permission_type_id);
        }*/

//        $online_register = OnlineRegister::where('course_id', '=', $course_id)->where('user_type', '=', 1)->pluck('user_id')->toArray();
        ProfileView::addGlobalScope(new DraftScope('user_id'));
        $query = ProfileView::query();
        $query->select([
            'el_profile_view.*',
        ]);
        $query->from('el_profile_view');
        $query->where('el_profile_view.user_id', '>', 2);
        $query->where('el_profile_view.type_user', '=', 1);

        $query->whereNotExists(function(Builder $sub) use($course_id){
            $sub->select('id')
                ->whereColumn('user_id','=','el_profile_view.user_id')
                ->where('course_id','=',$course_id)
                ->where('user_type','=',1)
                ->from('el_online_register');

        })->disableCache() ;

        /*if ($user_invited){
            $query->whereExists(function ($queryExists) use ($condition){
                $queryExists->select('id')
                    ->from('el_unit_view')
                    ->whereColumn(['id'=>'a.unit_id']);
                if ($condition)
                    $queryExists->whereRaw($condition);
                else
                    $queryExists->whereRaw("1=-1");
            });
        }*/
        if ($search) {
            $query->where(function ($sub_query) use ($search) {
                $sub_query->orWhere('full_name', 'like', '%'. $search .'%');
                $sub_query->orWhere('email', 'like', '%'. $search .'%');
                $sub_query->orWhere('code', 'like', '%'. $search .'%');
            });
        }

        if ($title) {
            $query->where('title_id', '=', $title);
        }

        if ($unit) {
            $unit = Unit::whereIn('id', explode(';', $unit))->latest('id')->first();
            $unit_id = Unit::getArrayChild($unit->code);

            $query->where(function ($sub_query) use ($unit_id, $unit) {
                $sub_query->orWhereIn('unit_id', $unit_id);
                $sub_query->orWhere('unit_id', '=', $unit->id);
            });
        }

        /*if ($course->unit_id) {
            $query->where('c.id', '=', $course->unit_id);
        }*/

        $count = $query->count();
        $query->orderBy( $sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();

        foreach ($rows as $row){

        }
        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function form($course_id) {
        $max_unit = Unit::getMaxUnitLevel();
        $level_name = function ($level) {
            return Unit::getLevelName($level);
        };
        $online = OnlineCourse::findOrFail($course_id);
        return view('online::backend.register.form', [
            'online' => $online,
            'max_unit' => $max_unit,
            'level_name' => $level_name,
        ]);
    }

    public function save($course_id, Request $request) {
        $this->validateRequest([
            'ids' => 'required',
        ], $request, OnlineRegister::getAttributeName());

        $user_invited = false;
        $check_user_invited = OnlineInviteRegister::query()
            ->where('course_id', '=', $course_id)
            ->where('user_id', '=', profile()->user_id);
        if ($check_user_invited->exists()){
            $user_invited = true;
            $num_register = $check_user_invited->first()->num_register;
        }

        $course = OnlineCourse::find($course_id, ['id', 'code', 'name', 'start_date', 'end_date', 'cert_code', 'subject_id', 'auto']);
        $ids = $request->input('ids', null);
        $subject = Subject::find($course->subject_id, ['id', 'code', 'name']);

        foreach($ids as $id){
            if ($user_invited){
                if ($num_register == 0){
                    continue;
                }else{
                    $num_register -= 1;

                    OnlineInviteRegister::query()
                        ->where('course_id', '=', $course_id)
                        ->where('user_id', '=', profile()->user_id)
                        ->update([
                            'num_register' => $num_register
                        ]);
                }
            }

            if (OnlineRegister::checkExists($id, $course_id)) {
                continue;
            }

            // update training process
            event(new SaveTrainingProcessRegister($course, $subject, $id, null, 1));

            $model = new OnlineRegister();
            $model->user_id = $id;
            $model->course_id = $course_id;
            if ($course->auto == 2){
                $model->status = 1;
                $model->approved_step = '1/1';

                $quizs = Quiz::where('course_id', '=', $course_id)
                    ->where('status', '=', 1)->get();
                if ($quizs){
                    foreach ($quizs as $quiz){
                        $quiz_part = QuizPart::where('quiz_id', '=', $quiz->id)->first();
                        if ($quiz_part){
                            QuizRegister::query()
                                ->updateOrCreate([
                                    'quiz_id' => $quiz->id,
                                    'user_id' => $id,
                                    'type' => 1,
                                ],[
                                    'quiz_id' => $quiz->id,
                                    'user_id' => $id,
                                    'type' => 1,
                                    'part_id' => $quiz_part->id,
                                ]);
                        }else{
                            continue;
                        }
                    }
                }
                $model->save();
            }else{
                if ($model->save()) {
                    $users = UnitManager::getManagerOfUser($model->user_id);
                    event(new SendMailRegister($users, $course, 1));
                }
            }
        }

        json_message(trans('laother.successful_save'));
    }

    public function remove(Request $request) {
        $ids = $request->input('ids', null);

        foreach ($ids as $id){
            $online_register = OnlineRegister::find($id);
            $profileName = ProfileView::where('user_id', $online_register->user_id)->first(['full_name']);
            $result = OnlineResult::where('register_id', '=', $id);
            if ($result->exists()){
                json_result([
                    'status' => 'error',
                    'message' => 'Không thể xóa vì Học viên:'. $profileName->full_name .' đã có kết quả',
                ]);
            }

            $quizs = Quiz::query()
                ->select(['a.id', 'b.user_id'])
                ->from('el_quiz as a')
                ->leftJoin('el_quiz_register as b', 'b.quiz_id', '=', 'a.id')
                ->where('a.course_id', '=', $online_register->course_id)
                ->where('b.user_id', '=', $online_register->user_id)
                ->where('b.type', '=', 1)
                ->get();

            if (count($quizs) > 0){
                $count = 0;
                foreach ($quizs as $quiz){
                    $result = QuizResult::where('quiz_id', '=', $quiz->id)
                        ->where('user_id', '=', $quiz->user_id)
                        ->where('type', '=', 1)
                        ->whereNull('text_quiz')
                        ->first();
                    if ($result){
                        $count++;
                        json_result([
                            'status' => 'error',
                            'message' => 'Không thể xóa vì Học viên:'. $profileName->full_name .' đã có kết quả kỳ thi khóa online',
                        ]);
                    }else{
                        QuizRegister::where('quiz_id', '=', $quiz->id)
                            ->where('user_id', '=', $quiz->user_id)
                            ->where('type', '=', 1)
                            ->delete();
                    }
                }
                if ($count == 0){
                    $user_invited = OnlineInviteRegister::query()
                        ->where('course_id', '=', $online_register->course_id)
                        ->where('user_id', '=', $online_register->created_by)
                        ->first();
                    if ($user_invited){
                        OnlineInviteRegister::query()
                            ->where('course_id', '=', $online_register->course_id)
                            ->where('user_id', '=', $online_register->created_by)
                            ->update([
                                'num_register' => $user_invited->num_register + 1
                            ]);
                    }

                    $online_register->delete();
                }
            }else{
                $user_invited = OnlineInviteRegister::query()
                    ->where('course_id', '=', $online_register->course_id)
                    ->where('user_id', '=', $online_register->created_by)
                    ->first();
                if ($user_invited){
                    OnlineInviteRegister::query()
                        ->where('course_id', '=', $online_register->course_id)
                        ->where('user_id', '=', $online_register->created_by)
                        ->update([
                            'num_register' => $user_invited->num_register + 1
                        ]);
                }

                $online_register->delete();
            }
            TrainingProcess::where(['user_id'=> $online_register->user_id, 'course_id' => $online_register->course_id, 'course_type' => 1,'user_type' => 1])->delete();
        }
        json_result([
            'status' => 'success',
            'message' => trans('laother.delete_success'),
        ]);
    }

    public function importRegister($course_id, Request $request){
        $this->validateRequest([
            'import_file' => 'required|file'
        ], $request, ['import_file' => 'File import']);

        $unit = $request->input('unit');

        $import = new RegisterImport($course_id);
        \Excel::import($import, $request->file('import_file'));

        if ($import->errors) {
            session()->put('errors', $import->errors);
            session()->save();
        }

        $redirect = route('module.online.register', ['id' => $course_id]);

        json_result([
            'status' => 'success',
            'message' => 'Import ' . trans('laapi.success'),
            'redirect' => $redirect,
        ]);
    }

    public function addToQuiz($course_id, Request $request) {
        $this->validateRequest([
            'ids' => 'required',
        ], $request, [
            'ids' => trans("latraining.student"),
        ]);

        $quiz_id = $request->input('quiz_id');
        $part_id = $request->part_id;
        $ids = $request->ids;
        $errors = [];

        foreach ($ids as $id){
            $register = OnlineRegister::find($id);
            $full_name = Profile::fullname($register->user_id);

            if ($register->status != 1){
                $errors[] = "Nhân viên <b>$full_name</b> chưa được duyệt";
                continue;
            }

            $result = QuizResult::where('quiz_id', '=', $quiz_id)
                ->where('user_id', '=', $register->user_id)
                ->where('type', '=', 1)
                ->whereNull('text_quiz')
                ->first();

            if ($result){
                $errors[] = "Nhân viên <b>$full_name</b> đã thi. Không thể sửa";
                continue;
            }

            QuizRegister::query()
            ->updateOrCreate([
                'quiz_id' => $quiz_id,
                'user_id' => $register->user_id,
                'type' => 1,
            ],[
                'quiz_id' => $quiz_id,
                'user_id' => $register->user_id,
                'type' => 1,
                'part_id' => $part_id,
            ]);
        }

        session()->put('errors', $errors);
        session()->save();

        json_message(trans('laother.successful_save'));
    }

    public function inviteUserRegister($course_id, Request $request) {
        $this->validateRequest([
            'user_id' => 'required',
            'num_register' => 'required',
        ], $request, [
            'user_id' => 'Người có vai trò',
            'num_register' => 'Số lượng được ghi danh',
        ]);

        $user_id = $request->user_id;
        $role_id = $request->role_id;
        $num_register = $request->num_register;
        $offline = OnlineCourse::whereId($course_id)->first();

        $model = OnlineInviteRegister::firstOrNew(['course_id' => $course_id, 'user_id' => $user_id]);
        $model->user_id = $user_id;
        $model->role_id = $role_id;
        $model->course_id = $course_id;
        $model->unit_by = $offline->unit_by;
        $model->num_register = $num_register;
        $model->save();

        json_message(trans('laother.successful_save'));
    }

    public function getDataInviteUserRegister($course_id, Request $request){
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = OnlineInviteRegister::query()
            ->where('course_id', '=', $course_id);

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();

        foreach ($rows as $row){
            $row->user_code = Profile::usercode($row->user_id);
            $row->user_name = Profile::fullname($row->user_id);
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function removeInviteUserRegister($course_id, Request $request) {
        $ids = $request->input('ids', null);
        OnlineInviteRegister::destroy($ids);

        json_result([
            'status' => 'success',
            'message' => trans('laother.delete_success'),
        ]);
    }

    public function sendMailUserRegisted($course_id, Request $request){
        $this->validateRequest([
            'ids' => 'required',
        ], $request,[
            'ids' => trans("latraining.student"),
        ]);

        $course = OnlineCourse::find($course_id);
        $ids = $request->input('ids', null);
        $users = OnlineRegister::whereIn('id', $ids)->get();
        foreach ($users as $index => $user) {
            $signature = getMailSignature($user->user_id);

            $automail = new Automail();
            $automail->template_code = 'registered_course';
            $automail->params = [
                'signature' => $signature,
                'gender' => $user->user->gender=='1'?'Anh':'Chị',
                'full_name' => $user->user->full_name,
                'firstname' => $user->user->firstname,
                'course_code' => $course->code,
                'course_name' => $course->name,
                'course_type' => 'Online',
                'start_date' => $course->start_date,
                'end_date' => $course->end_date,
                'training_location' => 'Elearning',
                'url' => route('module.online.detail_online', ['id' => $course->id])
            ];
            $automail->users = [$user->user_id];
            $automail->check_exists = true;
            $automail->check_exists_status = 0;
            $automail->object_id = $course->id;
            $automail->object_type = 'register_approved_online';
            $automail->addToAutomail();
        }

        json_message('Gửi mail thành công','success');
    }

    public function exportRegister($course_id){
        return (new RegisterExport($course_id))->download('danh_sach_ghi_danh_khoa_hoc_'. date('d_m_Y') .'.xlsx');
    }

    public function modalInfo($id, $register_id, Request $request){
        $onlineRegister = OnlineRegister::find($register_id);
        $created_at2 = get_date($onlineRegister->created_at, 'H:i d/m/Y');

        $created_by = $onlineRegister->created_by ? $onlineRegister->created_by : 2;
        $updated_by = $onlineRegister->updated_by ? $onlineRegister->updated_by : 2;
        $user_created = ProfileView::where('user_id', $created_by)->first();
        $user_updated = ProfileView::where('user_id', $updated_by)->first();

        return view('online::modal.modal_info', [
            'created_at2' => $created_at2,
            'user_created' => $user_created,
            'user_updated' => $user_updated,
        ]);
    }
}
