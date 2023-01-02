<?php

namespace Modules\Offline\Http\Controllers;

use App\Models\Automail;
use App\Models\Categories\Subject;
use App\Models\Categories\TrainingLocation;
use App\Models\Categories\TrainingType;
use App\Models\Categories\Unit;
use App\Models\Permission;
use App\Models\Categories\UnitManager;
use App\Models\Categories\Area;
use App\Models\PermissionTypeUnit;
use App\Models\UserPermissionType;
use App\Models\UserRole;
use App\Scopes\DraftScope;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Offline\Entities\OfflineCourseClass;
use Modules\Offline\Entities\OfflineInviteRegister;
use Modules\Offline\Entities\OfflineRegister;
use Modules\Offline\Entities\OfflineCourse;
use App\Models\Profile;
use App\Models\ProfileView;
use Modules\Offline\Entities\OfflineRegisterApprove;
use Modules\Offline\Entities\OfflineRegisterView;
use Modules\Offline\Entities\OfflineResult;
use Modules\Offline\Exports\RegisterExport;
use Modules\Offline\Imports\RegisterImport;
use Modules\Offline\Imports\RegisterImportClass;
use Modules\Online\Entities\OnlineInviteRegister;
use Modules\Quiz\Entities\Quiz;
use Modules\Quiz\Entities\QuizPart;
use Modules\Quiz\Entities\QuizRegister;
use Modules\Quiz\Entities\QuizResult;
use Modules\Offline\Entities\OfflineObject;
use Modules\ReportNew\Entities\ReportNewExportBC11;
use Modules\User\Entities\TrainingProcess;
use App\Models\PreviewImport;
use App\Events\SaveTrainingProcessRegister;
use App\Events\SendMailRegister;

class RegisterController extends Controller
{
    public function index($course_id, $class_id) {
        $errors = session()->get('errors');
        \Session::forget('errors');
        $class = OfflineCourseClass::findOrFail($class_id);
        $course = OfflineCourse::findOrFail($course_id);

        $quiz_part = function ($quiz_id) {
            return QuizPart::where('quiz_id', '=', $quiz_id)->get();
        };
        $max_unit = Unit::getMaxUnitLevel();
        $level_name = function ($level) {
            return Unit::getLevelName($level);
        };

        $user_has_role_register = UserRole::query()
            ->whereIn('role_id', function ($sub){
                $sub->select(['a.role_id'])
                    ->from('el_role_has_permissions as a')
                    ->leftJoin('el_permissions as b', 'b.id', '=', 'a.permission_id')
                    ->whereIn('b.name', ['offline-course-register', 'offline-course-register-create'])
                    ->pluck('a.role_id')
                    ->toArray();
            })
            ->where('user_id', '!=', profile()->user_id)
            ->where('user_id', '>', 2)
            ->get();

        $user_invited = false;
        $check_user_invited = OfflineInviteRegister::query()
            ->where('course_id', '=', $course_id)
            ->where('user_id', '=', profile()->user_id);
        if ($check_user_invited->exists()){
            $user_invited = true;
        }
        $anotherClass = OfflineCourseClass::where(['course_id'=>$course_id])->where('id','<>',$class_id)->get();
        $classArray = [];
        foreach ($anotherClass as $item) {
            $classArray[]=["name"=>$item->name,"url"=> route("module.offline.register",['id'=>$course_id,'class_id'=>$item->id])];
        }
        return view('offline::backend.register.index', [
            'course' => $course,
            'course_id' => $course_id,
            'quiz_part' => $quiz_part,
            'max_unit' => $max_unit,
            'level_name' => $level_name,
            'user_has_role_register' => $user_has_role_register,
            'user_invited' => $user_invited,
            'class' => $class,
            'classArray' => $classArray,
        ]);
    }

    public function getData($course_id, $class_id, Request $request) {
        $search = $request->input('search');
        $join_company = $request->input('join_company');
        $title = $request->input('title');
        $unit = $request->unit_id;
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $user_invited = false;
        $condition = '';
        /*$check_user_invited = OfflineInviteRegister::query()
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
        OfflineRegisterView::addGlobalScope(new DraftScope());
        $query = OfflineRegisterView::query();
        $query->select(['el_offline_register_view.*' ]);
        $query->from('el_offline_register_view');
        $query->leftJoin('el_unit AS b', 'b.id', '=', 'el_offline_register_view.unit_id');
        $query->leftJoin('el_area AS area', 'area.id', '=', 'b.area_id');
        $query->where('el_offline_register_view.course_id', '=', $course_id);
        $query->where('el_offline_register_view.class_id', $class_id);

       /* if ($user_invited){
            $query->whereExists(function ($queryExists) use ($condition){
                $queryExists->select('id')
                    ->from('el_unit_view')
                    ->whereColumn(['id'=>'unit_id']);
                if ($condition)
                    $queryExists->whereRaw($condition);
                else
                    $queryExists->whereRaw("1=-1");
            });
        }*/

        if ($search) {
            $query->where(function ($sub_query) use ($search) {
                $sub_query->orWhere('el_offline_register_view.full_name', 'like', '%'. $search .'%');
                $sub_query->orWhere('el_offline_register_view.code', 'like', '%'. $search .'%');
                $sub_query->orWhere('el_offline_register_view.email', 'like', '%'. $search .'%');
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
            $query->where('el_offline_register_view.title_id', '=', $title);
        }
        if ($unit) {
            $unit = Unit::whereIn('id', explode(';', $unit))->latest('id')->first();
            $unit_id = Unit::getArrayChild($unit->code);

            $query->where(function ($sub_query) use ($unit_id, $unit) {
                $sub_query->orWhereIn('el_offline_register_view.unit_id', $unit_id);
                $sub_query->orWhere('el_offline_register_view.unit_id', '=', $unit->id);
            });
        }

        $count = $query->count();
        $query->orderBy('el_offline_register_view.'.$sort, 'asc');
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
       foreach ($rows as $row){
            $row->info_url = route('module.offline.register.modal_info', ['id' => $row->course_id, 'register_id' => $row->id]);

            // thời gian ghi danh, hiển thị cho ghi danh tự động. có register_form = 2
            $row->time_register = get_date($row->created_at);
       }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function getDataNotRegister($course_id, $class_id, Request $request){
        $search = $request->input('search');
        $join_company = $request->input('join_company');
        $title = $request->input('title');
        $unit = $request->unit_id;
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        /*$user_invited = false;
        $condition = '';
        $check_user_invited = OfflineInviteRegister::query()
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

//        $offline_register = OfflineRegister::where('course_id', '=', $course_id)->pluck('user_id')->toArray();

        ProfileView::addGlobalScope(new DraftScope('user_id'));
        $query = ProfileView::query();
        $query->select([
            'el_profile_view.*',
        ]);
        $query->from('el_profile_view');
        $query->where('el_profile_view.user_id', '>', 2);
        $query->where('el_profile_view.type_user', '=', 1);

        if (OfflineObject::where('course_id', $course_id)->exists()) {
            $query->where(function ($sub) use ($course_id){
                $sub->orWhere(function($sub_query) use ($course_id) {
                    $sub_query->whereIn('el_profile_view.title_id', function ($sub_query2) use ($course_id){
                        $sub_query2->select(['title_id']);
                        $sub_query2->from('el_offline_object');
                        $sub_query2->where('course_id', '=', $course_id);
                    });
                    $sub_query->whereIn('el_profile_view.unit_id', function ($sub_query3) use ($course_id){
                        $sub_query3->select(['unit_id']);
                        $sub_query3->from('el_offline_object');
                        $sub_query3->where('course_id', '=', $course_id);
                    });
                });
                $sub->orWhereIn('el_profile_view.unit_id',function ($sub_query) use ($course_id){
                    $sub_query->select(['unit_id']);
                    $sub_query->from('el_offline_object');
                    $sub_query->whereNull('title_id');
                    $sub_query->where('course_id', '=', $course_id);
                });
            });
        }
        $query->whereNotExists(function (Builder $sub) use($course_id){
            $sub->select('id')->whereColumn('user_id','=','el_profile_view.user_id')
                ->where('course_id',$course_id)
                ->from('el_offline_register');
        })->disableCache();

//        if ($user_invited){
//            $query->whereExists(function ($queryExists) use ($condition){
//                $queryExists->select('id')
//                    ->from('el_unit_view')
//                    ->whereColumn(['id'=>'c.id']);
//                if ($condition)
//                    $queryExists->whereRaw($condition);
//                else
//                    $queryExists->whereRaw("1=-1");
//            });
//        }
        if ($search) {
            $query->where(function ($sub_query) use ($search) {
                $sub_query->orWhere('el_profile_view.full_name', 'like', '%'. $search .'%');
                $sub_query->orWhere('el_profile_view.email', 'like', '%'. $search .'%');
                $sub_query->orWhere('el_profile_view.code', 'like', '%'. $search .'%');
            });
        }
        if ($join_company){
            $query->where('el_profile_view.expbank', '=', $join_company);
        }
        if ($title) {
            $query->where('el_profile_view.title_id', '=', $title);
        }
        if ($unit) {
            $unit = Unit::whereIn('id', explode(';', $unit))->latest('id')->first();
            $unit_id = Unit::getArrayChild($unit->code);

            $query->where(function ($sub_query) use ($unit_id, $unit) {
                $sub_query->orWhereIn('el_profile_view.unit_id', $unit_id);
                $sub_query->orWhere('el_profile_view.unit_id', '=', $unit->id);
            });
        }

        $count = $query->count();
        $query->orderBy('el_profile_view.'.$sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();

        foreach ($rows as $row){

            $row->join_company = get_date($row->join_company, 'd/m/Y');

            if (empty($row->parent_name)){
                $row->parent = $row->unit_name;
                $row->unit = '';
            }else{
                $row->parent = $row->parent_name;
                $row->unit = $row->unit_name;
            }
        }
        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function form($course_id, $class_id) {
        $class = OfflineCourseClass::findOrFail($class_id);
        $max_unit = Unit::getMaxUnitLevel();
        $level_name = function ($level) {
            return Unit::getLevelName($level);
        };
        $offline = OfflineCourse::findOrFail($course_id);
        $anotherClass = OfflineCourseClass::where(['course_id'=>$course_id])->where('id','<>',$class_id)->get();
        $classArray = [];
        foreach ($anotherClass as $item) {
            $classArray[]=["name"=>$item->name,"url"=> route("module.offline.register.class.create",['id'=>$course_id,'class_id'=>$item->id])];
        }
        return view('offline::backend.register.form', [
            'course_id' => $course_id,
            'class' => $class,
            'offline' => $offline,
            'max_unit' => $max_unit,
            'level_name' => $level_name,
            'classArray' => $classArray,
        ]);
    }

    // Lưu đăng ký ghi danh - Cũ.
    public function save($course_id, Request $request) {
        $this->validateRequest([
            'ids' => 'required',
        ], $request, OfflineRegister::getAttributeName());

        $user_invited = false;
        $check_user_invited = OfflineInviteRegister::query()
            ->where('course_id', '=', $course_id)
            ->where('user_id', '=', profile()->user_id);
        if ($check_user_invited->exists()){
            $user_invited = true;
            $num_register = $check_user_invited->first()->num_register;
        }

        $ids = $request->input('ids', null);
        $course = OfflineCourse::findOrFail($course_id);
        $subject = Subject::findOrFail($course->subject_id);
        foreach($ids as $id){
            if ($user_invited){
                if ($num_register == 0){
                    continue;
                }else{
                    $num_register -= 1;

                    OfflineInviteRegister::query()
                        ->where('course_id', '=', $course_id)
                        ->where('user_id', '=', profile()->user_id)
                        ->update([
                            'num_register' => $num_register
                        ]);
                }
            }

            if (OfflineRegister::checkExists($id, $course_id)) {
                continue;
            }
            $model = new OfflineRegister();
            $model->user_id = $id;
            $model->course_id = $course_id;
            if ($model->save()) {
                // update training process
                event(new SaveTrainingProcessRegister($course, $subject, $id, null, 2));

                $users = UnitManager::getManagerOfUser($model->user_id);
                event(new SendMailRegister($users, $course, 2));
            }
        }

        json_result([
            'status' => 'success',
            'message' => trans('laother.successful_save'),
        ]);
    }

    public function remove(Request $request) {
        $ids = $request->input('ids', null);
        foreach ($ids as $id){
            $offline_register = OfflineRegister::find($id);
            $result = OfflineResult::where('register_id', '=', $id);
            if ($result->exists() ){
                json_message($offline_register->user->full_name .' đã có kết quả', 'error');
            }
            $quizs = Quiz::query()
                ->select(['a.id', 'b.user_id'])
                ->from('el_quiz as a')
                ->leftJoin('el_quiz_register as b', 'b.quiz_id', '=', 'a.id')
                ->where('a.course_id', '=', $offline_register->course_id)
                ->where('b.user_id', '=', $offline_register->user_id)
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
                        json_message($offline_register->user->full_name .' đã có kết quả kỳ thi', 'error');
                    } else {
                        QuizRegister::where('quiz_id', '=', $quiz->id)->where('user_id', '=', $quiz->user_id)->delete();
                    }
                }
                if ($count == 0){
                    $user_invited = OfflineInviteRegister::query()
                        ->where('course_id', '=', $offline_register->course_id)
                        ->where('user_id', '=', $offline_register->created_by)
                        ->first();
                    if ($user_invited){
                        OfflineInviteRegister::query()
                            ->where('course_id', '=', $offline_register->course_id)
                            ->where('user_id', '=', $offline_register->created_by)
                            ->update([
                                'num_register' => $user_invited->num_register + 1
                            ]);
                    }

                    $offline_course = OfflineCourse::find($offline_register->course_id);
                    TrainingProcess::where(['user_id'=>$offline_register->user_id,'course_id'=>$offline_course->id,'course_type'=>2])->delete();
                    $offline_register->delete();
                }
            }else{
                $user_invited = OfflineInviteRegister::query()
                    ->where('course_id', '=', $offline_register->course_id)
                    ->where('user_id', '=', $offline_register->created_by)
                    ->first();
                if ($user_invited){
                    OfflineInviteRegister::query()
                        ->where('course_id', '=', $offline_register->course_id)
                        ->where('user_id', '=', $offline_register->created_by)
                        ->update([
                            'num_register' => $user_invited->num_register + 1
                        ]);
                }

                $offline_course = OfflineCourse::find($offline_register->course_id);
                TrainingProcess::where(['user_id'=>$offline_register->user_id,'course_id'=>$offline_course->id,'course_type'=>2])->delete();
                $offline_register->delete();
            }
        }
        json_result([
            'status' => 'success',
            'message' => trans('laother.delete_success'),
        ]);
    }

    public function importRegister($course_id, $class_id, Request $request){
        $this->validateRequest([
            'import_file' => 'required|file'
        ], $request, ['import_file' => 'File import']);

        $unit = $request->input('unit');

        $import = new RegisterImport($course_id, $class_id);
        \Excel::import($import, $request->file('import_file'));

        if ($import->errors) {
            session()->put('errors', $import->errors);
            session()->save();
        }

        $redirect = route('module.offline.register', ['id' => $course_id, 'class_id' => $class_id]);

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
            'ids' => 'Học viên',
        ]);

        $offline = OfflineCourse::find($course_id);

        $part_id = $request->part_id;
        $ids = $request->ids;
        $errors = [];

        foreach ($ids as $id){
            $register = OfflineRegister::find($id);
            $full_name = Profile::fullname($register->user_id);

            if ($register->status != 1){
                $errors[] = "Nhân viên <b>$full_name</b> chưa được duyệt";
                continue;
            }

            QuizRegister::query()
                ->updateOrCreate([
                    'quiz_id' => $offline->quiz_id,
                    'user_id' => $register->user_id,
                    'type' => 1,
                ],[
                    'quiz_id' => $offline->quiz_id,
                    'user_id' => $register->user_id,
                    'type' => 1,
                    'part_id' => $part_id,
                ]);
        }

        session()->put('errors', $errors);
        session()->save();

        json_message(trans('laother.successful_save'));
    }

    public function exportRegister($course_id, $class_id){
        return (new RegisterExport($course_id, $class_id))->download('danh_sach_ghi_danh_khoa_hoc_'. date('d_m_Y') .'.xlsx');
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
        $offline = OfflineCourse::whereId($course_id)->first();

        $model = OfflineInviteRegister::firstOrNew(['course_id' => $course_id, 'user_id' => $user_id]);
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

        $query = OfflineInviteRegister::query()
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
        OfflineInviteRegister::destroy($ids);

        json_result([
            'status' => 'success',
            'message' => trans('laother.delete_success'),
        ]);
    }

    public function sendMailUserRegisted($course_id, Request $request){
        $this->validateRequest([
            'ids' => 'required',
        ], $request,[
            'ids' => 'Học viên',
        ]);

        $course = OfflineCourse::find($course_id);
        $ids = $request->input('ids', null);
        $users = OfflineRegister::whereIn('id', $ids)->get();

        foreach ($users as $user) {
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
                'course_type' => 'Tập trung',
                'start_date' => get_date($course->start_date),
                'end_date' => get_date($course->end_date),
                'training_location' => $course->training_location?$course->training_location->name:'',
                'url' => route('module.offline.detail', ['id' => $course->id])
            ];
            $automail->users = [$user->user_id];
            $automail->check_exists = true;
            $automail->check_exists_status = 0;
            $automail->object_id = $course->id;
            $automail->object_type = 'register_approved_offline';
            $automail->addToAutomail();
        }

        json_message('Gửi mail thành công','success');
    }

    public function modalInfo($id, $register_id, Request $request){
        $offlineRegister = OfflineRegisterView::find($register_id);
        $created_at2 = get_date($offlineRegister->created_at, 'H:i d/m/Y');

        $created_by = $offlineRegister->created_by ? $offlineRegister->created_by : 2;
        $updated_by = $offlineRegister->updated_by ? $offlineRegister->updated_by : 2;
        $user_created = ProfileView::where('user_id', $created_by)->first();
        $user_updated = ProfileView::where('user_id', $updated_by)->first();

        return view('offline::modal.modal_info', [
            'created_at2' => $created_at2,
            'user_created' => $user_created,
            'user_updated' => $user_updated,
        ]);
    }

    public function classDefault($course_id, Request $request)
    {
        $class = OfflineCourseClass::where(['course_id'=>$course_id,'default'=>1])->firstOrFail();
        return redirect(route('module.offline.register',['id'=>$course_id,'class_id'=>$class->id]));
    }

    // IMPORT GHI DANH TRONG TAB LỚP HỌC
    public function importRegisterClass($course_id, Request $request)
    {
        $this->validateRequest([
            'import_file' => 'required|file'
        ], $request, ['import_file' => 'File import']);

        $unit = $request->input('unit');

        $import = new RegisterImportClass($course_id, $class_id);
        \Excel::import($import, $request->file('import_file'));

        json_result([
            'data' => $import->data,
            'total_success' => $import->success,
            'total_fail' => $import->fail,
        ]);
    }

    // LƯU GHI DANH IMPORT TRONG TAB LỚP HỌC
    public function saveImportRegisterClass($course_id, Request $request) {
        if($request->type == 0) {
            PreviewImport::where('name_import', 'register_offline')->delete();
        } else {
            $previewImports = PreviewImport::where('name_import', 'register_offline')->get();
            foreach ($previewImports as $import) {
                OfflineRegister::create([
                    'user_id' => (int) $import->column1,
                    'course_id' => $import->column2,
                    'class_id' => $import->column3,
                ]);
                $offline_course = OfflineCourse::find($course_id, ['id','subject_id','code','name','start_date','end_date','cert_code']);
                $subject = Subject::find($offline_course->subject_id);
                event(new SaveTrainingProcessRegister($offline_course, $subject, $import->column1, $import->column3, 2));
            }
            PreviewImport::where('name_import', 'register_offline')->delete();
        }

        json_result([
            'status' => 'success',
            'message' => trans('laother.successful_save'),
        ]);
    }
}
