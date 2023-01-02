<?php

namespace Modules\User\Http\Controllers\Backend;

use App\Imports\TrainingProgramLearnedImport;
use App\Imports\WorkingProcessImport;
use App\Models\Categories\Area;
use App\Models\Categories\TitleRank;
use App\Models\Certificate;
use App\Exports\UserExport;
use App\Imports\UserImport;
use App\Jobs\NotifyUserOfCompletedImportUser;
use App\Models\Categories\Position;
use App\Models\CourseRegisterView;
use App\Models\Notifications;
use App\Models\Permission;
use App\Models\Profile;
use App\Models\Categories\Titles;
use App\Models\Categories\TrainingTeacher;
use App\Models\Categories\Unit;
use App\Models\Categories\UnitManager;
use App\Models\ProfileView;
use App\Scopes\DraftScope;
use App\Models\User;
use App\Models\UserMeta;
use App\Models\TotalTimeHistoryUser;
use App\Models\TotalTimeUserLearnInYear;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;
use Intervention\Image\ImageManagerStatic;
use Modules\API\Entities\API;
use Modules\Offline\Entities\OfflineCourse;
use Modules\Offline\Entities\OfflineRegister;
use Modules\Offline\Entities\OfflineResult;
use Modules\Online\Entities\OnlineCourse;
use Modules\Online\Entities\OnlineRegister;
use Modules\Online\Entities\OnlineResult;
use App\Http\Controllers\Controller;
use Modules\Quiz\Entities\QuizRegister;
use Modules\TrainingByTitle\Entities\TrainingByTitleDetail;
use Modules\User\Entities\HistoryChangeInfo;
use Modules\User\Entities\TrainingProcess;
use App\Models\Categories\TrainingForm;
use App\Models\MyCertificate;
use Modules\User\Entities\ProfileProgressRoadmap;
use Modules\User\Entities\WorkingProcess;
use Modules\UserMedal\Entities\UserMedalResult;
use Modules\UserMedal\Entities\UserMedalSettingsItems;
use Illuminate\Support\Facades\DB;
use Modules\Online\Entities\OnlineCourseCondition;
use Modules\Online\Entities\OnlineCourseActivityCompletion;
use Modules\Online\Entities\OnlineCourseComplete;
use Modules\Offline\Entities\OfflineAttendance;
use Modules\Offline\Entities\OfflineCourseComplete;
use Modules\Offline\Entities\OfflineSchedule;
use Modules\Quiz\Entities\QuizAttempts;

class UserController extends Controller
{
/*******************************/
    public function index() {
        Profile::addGlobalScope(new DraftScope('user_id'));
        $max_unit = Unit::getMaxUnitLevel();
        $level_name = function ($level) {
            return Unit::getLevelName($level);
        };

        $max_area = Area::getMaxAreaLevel();
        $level_name_area = function ($level) {
            return Area::getLevelName($level);
        };

        $unit_manager = Permission::isUnitManager();
        if(!$unit_manager){
            $total_model = Profile::where('user_id', '>', 2)->count();
            $total_model_active = Profile::where('status', 1)->where('user_id', '>', 2)->count();
        }else{
            $unit_user = Unit::find(session('user_unit'));
            $child_arr = Unit::getArrayChild(@$unit_user->code);

            $total_model = Profile::where('user_id', '>', 2)
            ->where(function ($sub) use ($unit_user, $child_arr){
                $sub->orWhere('unit_id', '=', @$unit_user->id);
                $sub->orWhereIn('unit_id', $child_arr);
            })->count();

            $total_model_active = Profile::where('status', 1)
            ->where('user_id', '>', 2)
            ->where(function ($sub) use ($unit_user, $child_arr){
                $sub->orWhere('unit_id', '=', @$unit_user->id);
                $sub->orWhereIn('unit_id', $child_arr);
            })->count();
        }

        return view('user::backend.user.index2', [
            'max_unit' => $max_unit,
            'level_name' => $level_name,
            'max_area' => $max_area,
            'level_name_area' => $level_name_area,
            'total_model' => $total_model,
            'total_model_active' => $total_model_active,
        ]);
    }

    public function getData(Request $request) {
        $search = $request->input('search');
        $status = $request->input('status');
        $unit = $request->unit_id;
        $title = $request->input('title');
        $area = $request->input('area');

        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $unit_manager = Permission::isUnitManager();
        if(!$unit_manager){
            ProfileView::addGlobalScope(new DraftScope('user_id'));
        }

        $query = ProfileView::query();
        $query->select([
            'el_profile_view.id',
            'el_profile_view.user_id',
            'el_profile_view.code',
            'el_profile_view.full_name',
            'el_profile_view.email',
            'el_profile_view.title_id',
            'el_profile_view.title_name',
            'el_profile_view.unit_name',
            'el_profile_view.parent_unit_name',
            'el_profile_view.status_id',
            'time.total_time',
        ]);
        $query->from('el_profile_view');
        $query->leftjoin('user as u','u.id','=','el_profile_view.user_id');
        $query->leftjoin('el_total_time_user_learn_year as time','time.user_id','=','el_profile_view.user_id');
        //$query->leftjoin('el_titles as titles', 'titles.code', '=', 'el_profile_view.title_code');
        $query->where('el_profile_view.user_id', '>', 2);
        $query->where('el_profile_view.type_user', '=', 1);

        if ($unit_manager){
            $unit_user = Unit::find(session('user_unit'));
            $child_arr = Unit::getArrayChild(@$unit_user->code);

            $query->where(function ($sub) use ($unit_user, $child_arr){
                $sub->orWhere('el_profile_view.unit_id', '=', @$unit_user->id);
                $sub->orWhereIn('el_profile_view.unit_id', $child_arr);
            });
        }

        if ($search) {
            $query->where(function ($sub_query) use ($search) {
                $sub_query->orWhere('el_profile_view.full_name', 'like', '%' . $search . '%');
                $sub_query->orWhere('el_profile_view.code', 'like', '%'. $search .'%');
                $sub_query->orWhere('el_profile_view.email', 'like', '%'. $search .'%');
                $sub_query->orWhere('u.username', 'like', '%'. $search .'%');
            });
        }
        if ($request->area) {
            $query->leftJoin('el_unit AS c', 'c.code', '=', 'el_profile_view.unit_code');
            $query->leftJoin('el_area AS area', 'area.id', '=', 'c.area_id');

            $area = Area::find($request->area);
            $area_id = Area::getArrayChild($area->code);
            $query->where(function ($sub_query) use ($area, $area_id) {
                $sub_query->whereIn('area.id', $area_id);
                $sub_query->orWhere('area.id', '=', $area->id);
            });
        }
        if (!is_null($status)) {
            $query->where('el_profile_view.status_id', '=', $status);
        }

        if ($unit) {
            $unit = Unit::whereIn('id', explode(';', $unit))->latest('id')->first();
            $unit_id = Unit::getArrayChild($unit->code);

            $query->where(function ($sub_query) use ($unit_id, $unit) {
                $sub_query->orWhereIn('el_profile_view.unit_id', $unit_id);
                $sub_query->orWhere('el_profile_view.unit_id', '=', $unit->id);
            });
        }

        if ($title) {
            $query->where('el_profile_view.title_id', '=', $title);
        }

        $data['total'] = $query->count();

        $query->orderBy('el_profile_view.status_id', 'desc');
        $query->orderBy('el_profile_view.code', 'desc');
        $query->offset($offset);
        $query->limit($limit);

        $data['rows'] = $query->get();
        foreach ($data['rows'] as $row) {
            $row->edit_url = route('module.backend.user.edit', ['id' => $row->user_id]);
            $row->unit_url = route('module.backend.user.get_unit', ['user_id' => $row->user_id]);
            $row->area_url = route('module.backend.user.get_area', ['user_id' => $row->user_id]);
            $row->dashboard_url = route('module.backend.user.dashboard', ['user_id' => $row->user_id]);
            $row->avatar = Profile::avatar($row->id);

            $progress_roadmap = ProfileProgressRoadmap::where('user_id', $row->user_id)->where('title_id', $row->title_id)->first();
            $row->percent_roadmap = $progress_roadmap ? number_format($progress_roadmap->percent, 2) : '';
        }

        json_result(['total' => $data['total'], 'rows' => $data['rows']]);
    }

    public function form($id = 0) {
        $max_unit = Unit::getMaxUnitLevel();
        $level_name = function ($level) {
            return Unit::getLevelName($level);
        };
        $level_name_area = function ($level) {
            return Area::getLevelName($level);
        };

        $user_meta = function ($user_id, $key){
            return UserMeta::where('user_id', '=', $user_id)->where('key', '=', $key)->first(['value']);
        };
        $certs = Certificate::get();
        if ($id) {
            $model = Profile::where(['user_id'=>$id])->where('user_id','>',2)->firstOrFail();
            $user = User::findOrFail($model->user_id);
            $title = Titles::where('code', $model->title_code)->first(['id', 'name', 'group', 'status']);
            $title_rank = TitleRank::where('id', @$title->group)->first(['id', 'name', 'status']);
            // $unit = Unit::getTreeParentUnit($model->unit_code);
            $unit = Unit::find($model->unit_id);
            $area = Area::getTreeParentArea($model->area_code);
            $page_title = $model->lastname .' '. $model->firstname;
            $position = Position::find($model->position_id);
            $unit_name = $unit->code . ' - ' . $unit->name;
            return view('user::backend.user.form', [
                'model' => $model,
                'page_title' => $page_title,
                'user' => $user,
                'title' => $title,
                'unit' => $unit,
                'area' => $area,
                'max_unit' => $max_unit,
                'level_name' => $level_name,
                'user_id'=>$model->user_id,
                'level_name_area' => $level_name_area,
                'user_meta' => $user_meta,
                'certs' => $certs,
                'position' => $position,
                'title_rank' => $title_rank,
                'unit_name' => $unit_name
            ]);
        }

        $model = new Profile();

        $page_title = trans('laprofile.add_new');

        return view('user::backend.user.form', [
            'model' => $model,
            'page_title' => $page_title,
            'max_unit' => $max_unit,
            'level_name' => $level_name,
            'max_area' => $max_area,
            'level_name_area' => $level_name_area,
            'user_id'=>null,
            'certs' => $certs,
            'user_meta' => $user_meta,
        ]);
    }

    public function save(Request $request) {
        $this->validateRequest([
            'username' => 'required_if:id,==,|min:1|max:32|unique:user,username,'. $request->id,
            // 'repassword' => 'same:password',
            'code' => 'required|unique:el_profile,code,'. $request->id,
            'lastname' => 'required',
            'firstname' => 'required',
            'email' => 'required_if:id,==,|email|unique:el_profile,email,'. $request->id,
            'gender' => 'required|in:1,0',
            'status' => 'required|in:0,1,2,3',
            'title_id' => 'required',
            'unit_id' => 'required|exists:el_unit,id',
        ],$request, Profile::getAttributeName());

        if ($request->auth == 'manual'){
            $this->validateRequest([
                'password' => 'nullable|min:8|max:32|required_if:id,==,',
            ], $request, Profile::getAttributeName());
        }
        if ($request->id && $request->id<=2){
            json_message('Không thể lưu user này', 'error');
        }


        if ($request->date_range) {
            if (!check_format_date($request->date_range)){
                json_message('Ngày cấp CMND phải định dạng kiểu Ngày/Tháng/Năm', 'error');
            }
        }
        if ($request->contract_signing_date) {
            if (!check_format_date($request->contract_signing_date)){
                json_message('Ngày kí Hợp đồng lao động phải định dạng kiểu Ngày/Tháng/Năm', 'error');
            }
        }
        if ($request->effective_date) {
            if (!check_format_date($request->effective_date)){
                json_message('Ngày hiệu lực / bổ nhiệm phải định dạng kiểu Ngày/Tháng/Năm', 'error');
            }
        }
        if ($request->expiration_date) {
            if (!check_format_date($request->expiration_date)){
                json_message('Ngày kết thúc / bổ nhiệm phải định dạng kiểu Ngày/Tháng/Năm', 'error');
            }
        }
        if ($request->dob) {
            if (!check_format_date($request->dob)){
                json_message('Ngày sinh phải định dạng kiểu Ngày/Tháng/Năm', 'error');
            }
        }
        if ($request->join_company) {
            if (!check_format_date($request->join_company)){
                json_message('Ngày vào làm phải định dạng kiểu Ngày/Tháng/Năm', 'error');
            }
        }
        if ($request->date_off) {
            if (!check_format_date($request->date_off)){
                json_message('Ngày nghỉ việc phải định dạng kiểu Ngày/Tháng/Năm', 'error');
            }
        }
        $effective_date = date_convert($request->effective_date);
        $expiration_date = date_convert($request->expiration_date);
        if($request->expiration_date && $expiration_date < $effective_date){
            json_message('Ngày hết hạn phải sau ngày hiệu lực', 'error');
        }
        if($request->join_company && $request->join_company < $request->date_off){
            json_message('Ngày nghỉ việc phải sau ngày vào làm', 'error');
        }
        if ($request->date_title_appointment){
            if (!check_format_date($request->date_title_appointment)){
                json_message('Ngày bổ nhiệm chức danh phải định dạng kiểu Ngày/Tháng/Năm', 'error');
            }
        }
        if ($request->end_date_title_appointment && $request->date_title_appointment < $request->end_date_title_appointment){
            json_message('Ngày kết thúc bổ nhiệm chức danh phải sau ngày bổ nhiệm chức danh', 'error');
        }
        $unit = Unit::where('id', '=', $request->unit_id)->first();
        $title = Titles::where('id', '=', $request->title_id)->first();
        $area = Area::where('id', '=', $request->area_id)->first();

        $arr_user_meta = [
            'current_address' => $request->current_address,
            'current_address_map' => $request->current_address_map,
            'type_labor_contract' => $request->type_labor_contract,
            'name_contact_person' => $request->name_contact_person,
            'relationship' => $request->relationship,
            'phone_contact_person' => $request->phone_contact_person,
            'school' => $request->school,
            'majors' => $request->majors,
            'license' => $request->license,
            'suspension_date' => $request->suspension_date,
            'reason' => $request->reason,
            'commendation' => $request->commendation,
            'discipline' => $request->discipline,
            'marital_status' => $request->marital_status,
            'special_skills' => $request->special_skills,
            'note' => $request->note
        ];


        $user = User::firstOrNew(['id' => $request->id]);
        $user->username = $user->username ? $user->username : $request->username;
        $user->auth = $request->auth;
        if ($request->auth == 'ldap') {
            $user->password = '';
        } else {
            $user->password = $request->password ? password_hash($request->password, PASSWORD_DEFAULT) : $user->password;
        }
        $user->email = $request->email;
        $user->firstname = $request->firstname;
        $user->lastname = $request->lastname;
        $user->save();

        if ($user->id) {
            foreach ($arr_user_meta as $key => $value) {
                $user_meta = UserMeta::query()->where('user_id', '=', $user->id)->where('key', '=', $key);

                if ($user_meta->exists()) {
                    $user_meta->update([
                        'value' => $value,
                    ]);
                } else {
                    $user_meta = new UserMeta();
                    $user_meta->user_id = $user->id;
                    $user_meta->key = $key;
                    $user_meta->value = $value;
                    $user_meta->save();
                }
            }

            $model = Profile::firstOrNew(['id' => $user->id]);
            if($request->id) {
                $titleOld = $model->title_id;
            }
            $model->fill($request->all());
            $model->id = $user->id;
            $model->user_id = $user->id;
            $model->area_code = $area ? $area->code : null;
            $model->unit_code = $unit->code;
            $model->unit_id = $unit->id;
            $model->title_code = $title->code;
            $model->certificate_code = $request->certificate_code;
            $model->position_id = $request->position_id;
            if($model->join_company) {
                $date_explank = cal_date_by_month(now(), date_convert($model->join_company));
            } else {
                $date_explank = '';
            }
            $model->expbank = $model->expbank ? $model->expbank : $date_explank;
            if ($request->date_range)
                $model->date_range = date_convert($request->date_range);
            if ($request->contract_signing_date)
                $model->contract_signing_date = date_convert($request->contract_signing_date);
            if ($request->effective_date)
                $model->effective_date = date_convert($request->effective_date);
            if ($request->expiration_date)
                $model->expiration_date = date_convert($request->expiration_date);
            if ($request->dob)
                $model->dob = date_convert($request->dob);
            if ($request->join_company)
                $model->join_company = date_convert($request->join_company);
            if ($request->date_off)
                $model->date_off = date_convert($request->date_off);
            if ($request->date_title_appointment)
                $model->date_title_appointment = date_convert($request->date_title_appointment);
            if ($request->end_date_title_appointment)
                $model->end_date_title_appointment = date_convert($request->end_date_title_appointment);

            if (empty($request->id)) {
                $model->id_code = Profile::generateShuffle();
            } elseif (!$model->id_code) {
                $model->id_code = Profile::generateShuffle();
            }

            if ($model->save()) {
                //Tạo quá trình công tác lúc đầu khi khỏi tạo nhân viên có ngày Bổ nhiệm chức danh
                if(empty($request->id) && $request->date_title_appointment){
                    $working_process = new WorkingProcess();
                    $working_process->user_id = $user->id;
                    $working_process->unit_code = $unit->code;
                    $working_process->title_code = $title->code;
                    $working_process->start_date = date_convert($request->date_title_appointment);
                    $working_process->save();
                }

                if($request->id) {
                    if($titleOld != $request->title_id){
                        $year = date('Y');
                        $totalTimeUserLearn = TotalTimeUserLearnInYear::where('user_id', $user->id)->where('year', $year)->first();

                        $saveHistoryTimeUser = new TotalTimeHistoryUser();
                        $saveHistoryTimeUser->user_id = $user->id;
                        $saveHistoryTimeUser->title_id = $titleOld;
                        $saveHistoryTimeUser->time_second = $totalTimeUserLearn ? $totalTimeUserLearn->title_time_new : 0;
                        $saveHistoryTimeUser->year =  $year;
                        $saveHistoryTimeUser->save();

                        //Cập nhật thời gian kết thúc quá trình công tác theo chức danh cũ trước
                        $title_old = Titles::find($titleOld);
                        WorkingProcess::whereUserId($user->id)
                            ->where('title_code', $title_old->code)
                            ->whereNull('api')
                            ->whereNull('end_date')
                            ->update([
                                'end_date' => now()->subDays(1)
                            ]);

                        //Tạo quá trình công tác theo chức danh hiện tại
                        $working_process = new WorkingProcess();
                        $working_process->user_id = $user->id;
                        $working_process->unit_code = $unit->code;
                        $working_process->title_code = $title->code;
                        $working_process->start_date = now();
                        $working_process->save();
                    }else{
                        //Kiếm tra quá trình công tác theo chức danh chưa thay đổi đã có chưa
                        if($request->date_title_appointment){
                            $check_working_process = WorkingProcess::whereUserId($user->id)->where('title_code', $title->code)->whereNull('api');
                            if(!$check_working_process->exists()){

                                $working_process = new WorkingProcess();
                                $working_process->user_id = $user->id;
                                $working_process->unit_code = $unit->code;
                                $working_process->title_code = $title->code;
                                $working_process->start_date = date_convert($request->date_title_appointment);
                                $working_process->save();
                            }
                        }
                    }
                }

                json_result([
                    'status' => 'success',
                    'message' => trans('laother.successful_save'),
                    'redirect' => route('module.backend.user.edit', ['id' => $user->id])
                ]);
            }
        }
        json_message(trans('laother.save_error'), 'error');
    }

    public function remove(Request $request) {
        $ids = $request->input('ids', null);
        foreach ($ids as $id){
            if ($id<=2)
                continue;
            $profile = Profile::where('user_id', $id)->first();
            $user_manager = UnitManager::where('user_code', '=', @$profile->code)->first();
            $user1 = OfflineRegister::where('user_id', $id)->first();
            $user2 = OnlineRegister::where('user_id', $id)->first();
            $user3 = TrainingTeacher::where('user_id', $id)->first();

            if (!empty($user_manager) || !empty($user1) || !empty($user2) || !empty($user3)){
                continue;
            }
            User::find($id)->delete();
            Profile::where('user_id', $id)->delete();
            ProfileView::where('user_id', $id)->delete();
        }
        json_result([
            'status' => 'success',
            'message' => trans('laother.delete_success'),
        ]);
    }

    public function getUnitByUser(Request $request, $id){
        $user = Profile::where('user_id', $id)->first();
        $unit = Unit::getTreeParentUnit($user->unit_code);

        $max_unit = Unit::getMaxUnitLevelWithValue();

        return view('user::backend.modal.unit_by_user', [
            'user' => $user,
            'unit' => $unit,
            'max_unit' => $max_unit,
        ]);
    }

    public function getAreaByUser(Request $request){
        $user = Profile::find($request->user_id);
        $area = Area::getTreeParentArea($user->area_code);

        $max_area = Area::getMaxAreaLevel();

        return view('user::backend.modal.area_by_user', [
            'user' => $user,
            'area' => $area,
            'max_area' => $max_area,
        ]);
    }

    public function importUser(Request $request){
        ini_set('memory_limit', '5048M');
        set_time_limit(3000);
        $this->validateRequest([
            'import_file' => 'required|file'
        ], $request, [
            'import_file' => 'File import'
        ]);
        $file = $request->file('import_file');

        // kiểm tra nhân viên có thuộc đơn vị quản lý hay ko
        $user_role = '';
        if(!Permission::isAdmin()){
            $userUnit = session()->get('user_unit');
            $user_role = UserRole::query()
                ->select(['c.unit_id', 'c.type', 'd.code', 'd.name'])->disableCache()
                ->from('el_user_role as a')
                ->join('el_role_has_permission_type as b', 'b.role_id', '=', 'a.role_id')
                ->join('el_permission_type_unit as c', 'c.permission_type_id', '=', 'b.permission_type_id')
                ->join('el_unit as d', 'd.id', '=', 'c.unit_id')
                ->where('a.user_id', '=', profile()->user_id)
                ->where('c.unit_id', '=', $userUnit)
                ->first();
        }

        $import = new UserImport();
        \Excel::import($import, $file);
        DB::statement('TRUNCATE TABLE preview_imports');
        foreach (array_chunk($import->dataImport,500) as  $data_chunk) {
            \DB::table('preview_imports')->insert($data_chunk);
        }
        $resultData = $this->saveImportUser();
        json_result([
            'data_erros' => $resultData['data_errors'],
            'total_success' => $resultData['success'],
            'total_fail' => $resultData['fail'],
        ]);
    }

    private function saveImportUser(){
        $data = DB::table('preview_imports as a')
            ->leftJoin('el_titles as b','a.title_code','=','b.code')
            ->leftJoin('el_unit as c','a.unit_code','=','c.code')
            ->leftJoin('user as d','a.username','=','d.username')
            ->select('a.*','b.code as title_code_org','c.code as unit_code_org','d.code as user_code_org','d.username as username_org',
                'd.id as user_id','c.id as unit_id','b.id as title_id')
            ->get();
        $checkUniqueUsername=[];$dataUserInserts =[];$dataProfileInserts =[];$dataUserUpdate=[];$dataProfileUpdate=[];$errors=[];$fail=0; $success=0;
        $dataMetaInsert =[]; $dataMetaUpdate =[];$dataUserId=[]; $metaInsert =[];
        $codeArray = User::select('code')->where('id','>',2)->pluck('code')->toArray();
        $codeArrayUpdate = $codeArray;
        foreach ($data as $index => $item) {
            $error = false;
            $row = json_decode($item->row);
            $userName = trim($row[1]); $userCode=trim($row[4]);
            if (empty($userName)) {
                $errorMsg = '<strong class="text-danger"> Tên đăng nhập không được trống </strong>';
//                $errors[1] = '<strong class="text-danger"> Không được trống </strong>';
                $error = true;
            }
            elseif (empty($userCode)) {
                $errorMsg = '<strong class="text-danger">Mã nhân viên không được trống </strong>';
//                $errors[4] = '<strong class="text-danger"> Không được trống </strong>';
                $error = true;
            }
            elseif (empty(trim($row[5]))) {
                $errorMsg = '<strong class="text-danger">Họ không được trống </strong>';
//                $errors[5] = '<strong class="text-danger"> Không được trống </strong>';
                $error = true;
            }
            elseif (empty(trim($row[6]))) {
                $errorMsg = '<strong class="text-danger">Tên không được trống </strong>';
//                $errors[6] = '<strong class="text-danger"> Không được trống </strong>';
                $error = true;
            }
            elseif (!$item->title_code || $item->title_code != $item->title_code_org) {
                $errorMsg = '<strong class="text-danger">Mã chức danh không tồn tại </strong>';
//                $errors[8] = '<strong class="text-danger"> Không tồn tại </strong>';
                $error = true;
            }
            elseif (!$item->unit_code || $item->unit_code != $item->unit_code_org) {
                $errorMsg = '<strong class="text-danger">Mã đơn vị không tồn tại </strong>';
//                $errors[9] = '<strong class="text-danger">Mã đơn vị không tồn tại </strong>';
                $error = true;
            }
            elseif (!in_array((int) $row[10], [1, 0])) {
                $errorMsg = '<strong class="text-danger">Giới tính không tồn tại </strong>';
//                $errors[10] = '<strong class="text-danger"> Không tồn tại </strong>';
                $error = true;
            }
            elseif (!in_array($row[24], [0, 1, 2, 3]) || empty($row[24])) {
                $errorMsg = '<strong class="text-danger">Trạng thái nhân viên không đúng </strong>';
//                $errors[24] = '<strong class="text-danger">Trạng thái nhân viên không đúng </strong>';
                $error = true;
            }
            elseif (!in_array($row[20], [0, 1, 2, 3])) {
                $errorMsg = '<strong class="text-danger">Loại hợp đồng Không đúng </strong>';
//                $errors[20] = '<strong class="text-danger">Loại hợp đồng Không đúng </strong>';
                $error = true;
            }

            // Ktra nusername đã tồn tại trong excel
            elseif (in_array($item->username, $checkUniqueUsername)) {
                $errorMsg = '<strong class="text-danger"> Nhân viên đã tồn tại trong file </strong>';
//                $errors[1] = '<strong class="text-danger"> Nhân viên đã tồn tại trong file </strong>';
                $error = true;
            } else {
                //Kiểm tra import nhân viên mới nhưng Mã NV đã tồn tại
                if (!$item->username) { // insert
                    if (in_array($userCode,$codeArray)){
                        $errorMsg = '<strong class="text-danger">Mã nhân viên đã tồn tại </strong>';
                        $error = true;
                    }
                }elseif($item->username== $item->username_org){//update

                    unset($codeArrayUpdate[array_flip($codeArrayUpdate)[$item->user_code]]);
                    if (in_array($userCode,$codeArrayUpdate)){
                        $errorMsg = '<strong class="text-danger">Mã nhân viên đã tồn tại </strong>';
                        $error = true;
                    }
                }
                $checkUniqueUsername[] = $item->username;
            }
            if ($error) {
                $fail += 1;
            } else
                $success += 1;

//            $dataImport[] = ['data' => $row, 'type' => ($item->username != $item->username_org) ? 1 : 2];
            if (!$error) {
                if ($item->username != $item->username_org) {
                    $dataUserInserts[] = [
                        'code' => trim($row[4]),
                        'auth' => 'manual',
                        'username' => trim($row[1]),
                        'password' => password_hash($row[2], PASSWORD_DEFAULT),
                        'email' => trim($row[7]),
                        'firstname' => trim($row[6]),
                        'lastname' => trim($row[5]),
                    ];
                    $dataProfileInserts[$item->username] = [
                        'code' => trim($row[4]),
                        'firstname' => trim($row[6]),
                        'lastname' => trim($row[5]),
                        'dob' => isset($row[12]) ? date_convert($row[12]) : null,
                        'address' => isset($row[14]) ? $row[14] : null,
                        'email' => isset($row[7]) ? trim($row[7]) : null,
                        'identity_card' => isset($row[17]) ? $row[17] : null,
                        'date_range' => isset($row[18]) ? date_convert($row[18]) : null,
                        'issued_by' => isset($row[19]) ? $row[19] : null,
                        'gender' => isset($row[10]) ? $row[10] : 1,
                        'phone' => isset($row[11]) ? $row[11] : null,
                        'contract_signing_date' => isset($row[21]) ? date_convert($row[21]) : null,
                        'effective_date' => isset($row[22]) ? date_convert($row[22]) : null,
                        'expiration_date' => isset($row[23]) ? date_convert($row[23]) : null,
                        'date_off' => isset($row[33]) ? date_convert($row[33]) : null,
                        'join_company' => isset($row[13]) ? date_convert($row[13]) : null,
                        'expbank' => isset($row[13]) && (strlen($row[13]) > 5) ? cal_date_by_month(now(), date_convert($row[13])) : null,
                        'title_code' => $row[8],
                        'title_id' => $item->title_id,
                        'unit_code' => $row[9],
                        'unit_id' => $item->unit_id,
                        'level' => isset($row[25]) ? $row[25] : null,
                        'certificate_code' => isset($row[29]) ? $row[29] : null,
                        'status' => isset($row[24]) ? $row[24] : 1,
                    ];
                    $dataMetaInsert[$item->username][] = ['key'=>'current_address','value'=>$row[15]];
                    $dataMetaInsert[$item->username][] = ['key'=>'current_address_map','value'=>$row[16]];
                    $dataMetaInsert[$item->username][] = ['key'=>'type_labor_contract','value'=>$row[20]];
                    $dataMetaInsert[$item->username][] = ['key'=>'name_contact_person','value'=>$row[26]];
                    $dataMetaInsert[$item->username][] = ['key'=>'relationship','value'=>$row[27]];
                    $dataMetaInsert[$item->username][] = ['key'=>'phone_contact_person','value'=>$row[28]];
                    $dataMetaInsert[$item->username][] = ['key'=>'school','value'=>$row[30]];
                    $dataMetaInsert[$item->username][] = ['key'=>'majors','value'=>$row[31]];
                    $dataMetaInsert[$item->username][] = ['key'=>'license','value'=>$row[32]];
                    $dataMetaInsert[$item->username][] = ['key'=>'suspension_date','value'=>isset($row[34]) ? date_convert($row[34]) : null];
                    $dataMetaInsert[$item->username][] = ['key'=>'reason','value'=>$row[35]];
                    $dataMetaInsert[$item->username][] = ['key'=>'commendation','value'=>$row[36]];
                    $dataMetaInsert[$item->username][] = ['key'=>'discipline','value'=>$row[37]];
                    $dataMetaInsert[$item->username][] = ['key'=>'marital_status','value'=>$row[38]];
                    $dataMetaInsert[$item->username][] = ['key'=>'special_skills','value'=>$row[39]];
                    $dataMetaInsert[$item->username][] = ['key'=>'note','value'=>$row[40]];
                } else {
                    $dataUserUpdate[] = [
                        'id' =>$item->user_id,
                        'code' => trim($row[4]),
                        'auth' => 'manual',
                        'username' => trim($row[1]),
                        'password' => password_hash($row[2], PASSWORD_DEFAULT),
                        'email' => trim($row[7]),
                        'firstname' => trim($row[6]),
                        'lastname' => trim($row[5]),
                    ];
                    $dataProfileUpdate[] = [
                        'id' => $item->user_id,
                        'user_id' => $item->user_id,
                        'code' => trim($row[4]),
                        'firstname' => trim($row[6]),
                        'lastname' => trim($row[5]),
                        'dob' => isset($row[12]) ? date_convert($row[12]) : null,
                        'address' => isset($row[14]) ? $row[14] : null,
                        'email' => isset($row[7]) ? trim($row[7]) : null,
                        'identity_card' => isset($row[17]) ? $row[17] : null,
                        'date_range' => isset($row[18]) ? date_convert($row[18]) : null,
                        'issued_by' => isset($row[19]) ? $row[19] : null,
                        'gender' => isset($row[10]) ? $row[10] : 1,
                        'phone' => isset($row[11]) ? $row[11] : null,
                        'contract_signing_date' => isset($row[21]) ? date_convert($row[21]) : null,
                        'effective_date' => isset($row[22]) ? date_convert($row[22]) : null,
                        'expiration_date' => isset($row[23]) ? date_convert($row[23]) : null,
                        'date_off' => isset($row[33]) ? date_convert($row[33]) : null,
                        'join_company' => isset($row[13]) ? date_convert($row[13]) : null,
                        'expbank' => isset($row[13]) && (strlen($row[13]) > 5) ? cal_date_by_month(now(), date_convert($row[13])) : null,
                        'title_code' => $row[8],
                        'title_id' => $item->title_id,
                        'unit_code' => $row[9],
                        'unit_id' => $item->unit_id,
                        'level' => isset($row[25]) ? $row[25] : null,
                        'certificate_code' => isset($row[29]) ? $row[29] : null,
                        'status' => isset($row[24]) ? $row[24] : 1,
                    ];
                    $dataUserId[]=$item->user_id;
                    $dataMetaUpdate[] = ['user_id'=>$item->user_id,'key'=>'current_address','value'=>$row[15]];
                    $dataMetaUpdate[] = ['user_id'=>$item->user_id,'key'=>'current_address_map','value'=>$row[16]];
                    $dataMetaUpdate[] = ['user_id'=>$item->user_id,'key'=>'type_labor_contract','value'=>$row[20]];
                    $dataMetaUpdate[] = ['user_id'=>$item->user_id,'key'=>'name_contact_person','value'=>$row[26]];
                    $dataMetaUpdate[] = ['user_id'=>$item->user_id,'key'=>'relationship','value'=>$row[27]];
                    $dataMetaUpdate[] = ['user_id'=>$item->user_id,'key'=>'phone_contact_person','value'=>$row[28]];
                    $dataMetaUpdate[] = ['user_id'=>$item->user_id,'key'=>'school','value'=>$row[30]];
                    $dataMetaUpdate[] = ['user_id'=>$item->user_id,'key'=>'majors','value'=>$row[31]];
                    $dataMetaUpdate[] = ['user_id'=>$item->user_id,'key'=>'license','value'=>$row[32]];
                    $dataMetaUpdate[] = ['user_id'=>$item->user_id,'key'=>'suspension_date','value'=>isset($row[34]) ? date_convert($row[34]) : null];
                    $dataMetaUpdate[] = ['user_id'=>$item->user_id,'key'=>'reason','value'=>$row[35]];
                    $dataMetaUpdate[] = ['user_id'=>$item->user_id,'key'=>'commendation','value'=>$row[36]];
                    $dataMetaUpdate[] = ['user_id'=>$item->user_id,'key'=>'discipline','value'=>$row[37]];
                    $dataMetaUpdate[] = ['user_id'=>$item->user_id,'key'=>'marital_status','value'=>$row[38]];
                    $dataMetaUpdate[] = ['user_id'=>$item->user_id,'key'=>'special_skills','value'=>$row[39]];
                    $dataMetaUpdate[] = ['user_id'=>$item->user_id,'key'=>'note','value'=>$row[40]];

                }

            }else{
                $dataErrors[] = [
                    $row[0],
                    $row[1],
                    $row[4],
                    $row[5],
                    $row[6],
                    $row[8],
                    $row[9],
                    ($row[10] == 0 ? 'Nữ' : 'Nam'),
                    $labor_contract,
                    $title_status,
                    $errorMsg
                ];
            }
        }
        if ($success > 0) {
            DB::statement('drop table IF EXISTS user_tmp; CREATE TEMPORARY TABLE user_tmp like user');
            if ($dataUserInserts) {
                foreach (array_chunk($dataUserInserts, 500) as $data_chunk) {
                    \DB::table('user')->insert($data_chunk);
                    \DB::table('user_tmp')->insert($data_chunk);
                }

                $userIds = DB::table('user_tmp as a')->join('user as b', 'a.username', '=', 'b.username')->select('b.id', 'b.username')->get();
                foreach ($userIds as $index => $user) {
                    $dataProfileInserts[$user->username]['id'] = $user->id;
                    $dataProfileInserts[$user->username]['user_id'] = $user->id;
                    for ($i = 0; $i < 16; $i++) {
                        $dataMetaInsert[$user->username][$i]['user_id'] = $user->id;
                        $metaInsert[] = $dataMetaInsert[$user->username][$i];
                    }

                }
                foreach (array_chunk($dataProfileInserts, 500) as $data_chunk) {
                    \DB::table('el_profile')->insert($data_chunk);
                }
                event(new ProfileEvent($dataProfileInserts, 1));

            }
            if ($dataUserUpdate) {
                /*********user************/
                $strSet = '';
                DB::statement('drop table IF EXISTS user_update_tmp; CREATE TEMPORARY TABLE user_update_tmp like user');
                foreach (array_chunk($dataUserUpdate, 500) as $data_chunk) {
                    \DB::table('user_tmp')->insert($data_chunk);
                    \DB::table('user_update_tmp')->insert($data_chunk);
//                $test[] = $dataMetaInsert[$user->username][$i];
                }
                unset($dataUserUpdate[0]['id']);
                foreach ($dataUserUpdate[0] as $k => $v) {
                    $strSet .= "user.$k = tmp.$k,";
                }
                $strSet = rtrim($strSet, ',');
                DB::statement('update user join user_tmp as tmp on user.username=tmp.username set ' . $strSet);


                /******* profile ********/
                $strSet = '';
                DB::statement('drop table IF EXISTS profile_tmp; CREATE TEMPORARY TABLE  IF NOT EXISTS profile_tmp like el_profile');
                foreach (array_chunk($dataProfileUpdate, 500) as $data_chunk) {
                    \DB::table('profile_tmp')->insert($data_chunk);
                }
                foreach ($dataProfileUpdate[0] as $k => $v) {
                    $strSet .= "el_profile.$k = tmp.$k,";
                }
                $strSet = rtrim($strSet, ',');
                DB::statement('update el_profile join profile_tmp as tmp on el_profile.user_id=tmp.user_id set ' . $strSet);
                DB::statement('DROP TABLE IF EXISTS profile_tmp');
                event(new ProfileEvent($dataProfileUpdate, 2));
                /******* user meta **************/
                /*$strSet = '';
                DB::statement('CREATE TEMPORARY TABLE  IF NOT EXISTS user_meta_tmp like user_meta');
                foreach (array_chunk($dataMetaUpdate,500) as  $data_chunk) {
                    \DB::table('user_meta_tmp')->insert($data_chunk);
                }
                foreach ($dataProfileUpdate[0] as $k => $v) {
                    $strSet .= "user_meta.$k = tmp.$k,";
                }
                $strSet = rtrim($strSet,',');
                DB::statement('update user_meta join user_meta_tmp as tmp on user_meta.user_id=tmp.user_id and user_meta.key=tmp.key set '.$strSet);
                DB::statement('DROP TABLE user_meta_tmp');*/
                DB::statement('delete user_meta from user_meta join user_update_tmp on user_meta.user_id = user_update_tmp.id');
                DB::statement('DROP TABLE IF EXISTS user_update_tmp');
            }
            /** user meta */
            $dataMeta = array_merge($metaInsert, $dataMetaUpdate);
            foreach (array_chunk($dataMeta, 500) as $data_chunk) {
                \DB::table('user_meta')->insert($data_chunk);
            }
            DB::statement('DROP TABLE IF EXISTS user_tmp');
            DB::statement('TRUNCATE TABLE preview_imports');
            Artisan::call('modelCache:clear', ['--model' => User::class]);
            Artisan::call('modelCache:clear', ['--model' => Profile::class]);
            Artisan::call('modelCache:clear', ['--model' => ProfileView::class]);
            Artisan::call('modelCache:clear', ['--model' => UserMeta::class]);
        }
        return ['fail'=>$fail,'success'=>$success,'data_errors'=>$dataErrors];
    }

    public function importWorkingProcess(Request $request){
        $this->validateRequest([
            'import_file' => 'required|file'
        ], $request, [
            'import_file' => 'File import'
        ]);

        $file = $request->file('import_file');
        $name = 'import_working_process_' . Str::random(10) . '.' . $file->extension();
        $newfile = $file->move(storage_path('import_files'), $name);

        if($newfile) {
            (new WorkingProcessImport(\Auth::user()))->queue($newfile)->chain([
                new NotifyUserOfCompletedImportUser(\Auth::user()),
            ]);

            json_result([
                'status' => 'success',
                'message' => 'Đang import dữ liệu, bạn sẽ được thông báo khi hoàn thành...',
                'redirect' => route('module.backend.user')
            ]);
        }

        json_result([
            'status' => 'error',
            'message' => trans('laother.unable_upload'),
            'redirect' => route('module.backend.user')
        ]);
    }

    public function importTrainingProgramLearned(Request $request){
        $this->validateRequest([
            'import_file' => 'required|file'
        ], $request, [
            'import_file' => 'File import'
        ]);

        $file = $request->file('import_file');
        $name = 'import_training_program_learned_' . Str::random(10) . '.' . $file->extension();
        $newfile = $file->move(storage_path('import_files'), $name);

        if($newfile) {
            (new TrainingProgramLearnedImport(\Auth::user()))->queue($newfile)->chain([
                new NotifyUserOfCompletedImportUser(\Auth::user()),
            ]);

            json_result([
                'status' => 'success',
                'message' => 'Đang import dữ liệu, bạn sẽ được thông báo khi hoàn thành...',
                'redirect' => route('module.backend.user')
            ]);
        }

        json_result([
            'status' => 'error',
            'message' => trans('laother.unable_upload'),
            'redirect' => route('module.backend.user')
        ]);
    }

    public function exportUser(Request $request)
    {
        $search = $request->export_search;
        $unit = $request->export_unit;
        $area = $request->export_area;
        $title = $request->export_title;
        $status = $request->export_status;
        return (new UserExport($search, $unit, $area, $title, $status))->download('danh_sach_nguoi_dung_'. date('d_m_Y') .'.xlsx');
    }

    public function showTrainingProcess(Request $request, $user_id)
    {
        return view('user::backend.trainingprocess.index',[
            'user_id'=>$user_id,
            'full_name'=>$this->getFullNameUser($user_id),
        ]);
    }

    public function getDataTrainingProcess(Request $request, $user_id) {

        $query = TrainingProcess::query();
        $query->select([
            'mark',
            'id',
            'course_id',
            'course_code',
            'course_name',
            'titles_name',
            'course_type',
            'process_type',
            'pass as result',
            'start_date',
            'end_date',
            'certificate',
        ]);
        $query->from('el_training_process');
        $query->where('user_id','=',$user_id);
        $count = $query->count();
        $rows = $query->get();

        foreach ($rows as $row) {
            if ($row->course_type==1){
                $course = OnlineCourse::find($row->course_id);
            }else{
                $course = OfflineCourse::find($row->course_id);
            }

            $row->image_cert = '';
            if (isset($course->cert_code) && $row->result == 1){
                $row->image_cert = route('module.backend.user.trainingprocess.certificate', ['course_id' => $row->course_id, 'course_type' => $row->course_type, 'user_id' => $user_id]);
            }

            $row->training_form = '-';
            if($course) {
                $training_form = TrainingForm::where('id',$course->training_form_id)->first();
                $row->training_form = $training_form->name;
            }

            $row->course_type = $row->course_type==1?trans('backend.onlines'):trans('latraining.offline');
            $row->start_date = get_date($row->start_date);
            $row->end_date = get_date($row->end_date);
            $row->score = $row->mark ? number_format($row->mark,2,',','.') : '';

            if ($row->process_type==2)
                $row->process_type = trans('backend.subject_complete');
            elseif ($row->process_type==4)
                $row->process_type = trans('backend.merge_subject');
            elseif ($row->process_type==5)
                $row->process_type = trans('backend.split_subject');
            else
                $row->process_type = '-';
        }
        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function certificate($course_id, $course_type, $user_id){
        $query = CourseRegisterView::query();
        $query->select([
            'b.end_date',
            'a.score',
            'b.cert_code',
            'c.created_at as date_complete',
        ]);
        $query->from('el_course_register_view AS a');
        $query->join('el_course_view AS b', function ($join){
            $join->on('a.course_id', '=', 'b.course_id');
            $join->on('a.course_type', '=', 'b.course_type');
        });
        $query->leftJoin('el_course_complete AS c', function ($join){
            $join->on('c.course_id', '=', 'b.course_id');
            $join->on('c.course_type', '=', 'b.course_type');
        });
        $query->where('a.user_id','=', $user_id);
        $query->where('a.course_id', '=', $course_id);
        $query->where('a.course_type', '=', $course_type);

        $model = $query->first();

        $profile = Profile::find($user_id);
        $unit = @$profile->unit->name;
        $title = @$profile->titles->name;
        $fullname = $profile->full_name;
        //$fullname = mb_convert_case($fullname, MB_CASE_UPPER, "UTF-8");

        $day = get_date(@$model->date_complete, 'd');
        $month = get_date(@$model->date_complete, 'm');
        $year = get_date(@$model->date_complete, 'Y');

        $date_complete = date('\n\g\à\y d \t\h\á\n\g m \n\ă\m Y', strtotime(@$model->date_complete));
        $date_complete_en = date('F d, Y', strtotime(@$model->date_complete));

        if ($course_type == 1){
            $course = OnlineCourse::find($course_id);
        }else{
            $course = OfflineCourse::find($course_id);
        }
        $certificate = \Modules\Certificate\Entities\Certificate::find($course->cert_code);

        $course_name = $course->name;

        $storage = \Storage::disk('upload');
        $path = $storage->path($certificate->image);
        $temp = str_replace($certificate->image, str_replace('.', '_'.$course_id.'.', $certificate->image), $path);

        $image = ImageManagerStatic::make($path);

        $image->text($fullname, 1755, 1240, function ($font){
            $font->file(public_path('fonts/UTM Wedding K&T.ttf'));
            $font->size(300);
            $font->color('#bd8e34');
            $font->align('center');
        });

        /*$image->text($unit, 710, 1630, function ($font){
            $font->file(public_path('fonts/timesbd.ttf'));
            $font->size(50);
        });*/

        // $image->text($title, 870, 655, function ($font){
        //     $font->file(public_path('fonts/FiraSansExtraCondensed-Regular.ttf'));
        //     $font->size(50);
        //     $font->align('center');
        // });

        $center_x    = 1755;
        $center_y    = 1530;
        $max_len     = 100;
        $font_height = 20;

        $lines = explode("/n", wordwrap($course_name, $max_len,"/n", true));
        $y     = $center_y - ((count($lines) - 1) * $font_height);
        foreach ($lines as $line) {
            $line = Str::upper($line);

            $image->text($line, $center_x, $y, function ($font) {
                $font->file(public_path('fonts/FiraSansExtraCondensed-Bold.ttf'));
                $font->size(100);
                $font->align('center');
                $font->color('#E53336');
            });
            $y += $font_height * 2;
        }

        /*$image->text($day, 1535, 2325, function ($font){
            $font->file(public_path('fonts/timesbd.ttf'));
            $font->size(40);
        });

        $image->text($month, 1725, 2325, function ($font){
            $font->file(public_path('fonts/timesbd.ttf'));
            $font->size(40);
        });

        $image->text($year, 1895, 2325, function ($font){
            $font->file(public_path('fonts/timesbd.ttf'));
            $font->size(40);
        });*/

        $image->text($date_complete, 2620, 1885, function ($font){
            $font->file(public_path('fonts/timesbd.ttf'));
            $font->size(60);
            $font->color('#4E4E4E');
        });

        $image->text($date_complete_en, 2764, 1960, function ($font){
            $font->file(public_path('fonts/timesbd.ttf'));
            $font->size(50);
            $font->color('#4C4C4C');
        });

        $image->text($certificate->position, 670, 2300, function ($font){
            $font->file(public_path('fonts/FiraSansExtraCondensed-Regular.ttf'));
            $font->size(75);
            $font->align('center');
        });

        $image->text($certificate->user, 670, 2400, function ($font){
            $font->file(public_path('fonts/FiraSansExtraCondensed-Regular.ttf'));
            $font->size(75);
            $font->align('center');
        });

        $image->insert($storage->path($certificate->signature), 'bottom-left', 470, 290);

        if($certificate->location && $certificate->location == 'left'){
            $image->insert($storage->path($certificate->logo), 'top-left', 70, 70);
        }

        if($certificate->location && $certificate->location == 'center'){
            $image->insert($storage->path($certificate->logo), 'top-center', 0, 70);
        }

        if($certificate->location && $certificate->location == 'right'){
            $image->insert($storage->path($certificate->logo), 'top-right', 70, 70);
        }

        $image->save($temp);

        $headers = array(
            'Content-Type: application/pdf',
        );
        ob_end_clean();
        return response()->download($temp, 'chung_chi_'.Str::slug($fullname, '_').'.png', $headers);

        //return \Storage::download($temp);
    }

    public function showQuizResult(Request $request, $user_id)
    {
        return view('user::backend.quizresult.index',[
            'user_id'=>$user_id,
            'full_name'=>$this->getFullNameUser($user_id),
        ]);
    }

    public function getDataQuizResult(Request $request, $user_id) {
        $query = QuizRegister::query()
            ->select([
                'a.quiz_id',
                'a.user_id',
                'c.id',
                'c.code',
                'c.name',
                'c.limit_time',
                'c.pass_score',
                'c.quiz_type',
                'b.id as part_id',
                'b.name as part_name',
                'b.start_date',
                'b.end_date',
                'd.grade',
                'd.result',
                'd.reexamine'
            ])
            ->from('el_quiz_register as a')
            ->join('el_quiz_part as b','b.id','=','a.part_id')
            ->join('el_quiz as c','c.id','=','b.quiz_id')
            ->leftJoin('el_quiz_result as d',function ($join){
                $join->on('a.user_id','=','d.user_id');
                $join->on('d.quiz_id','=','a.quiz_id');
            })
            ->where('a.user_id','=',$user_id);
        $count = $query->count();
        $rows = $query->get();
        foreach ($rows as $row) {
            $row->count_attempt = QuizAttempts::where(['quiz_id' => $row->quiz_id, 'part_id' => $row->part_id, 'user_id' => $row->user_id])->count();
            $row->start_date = get_date($row->start_date,'d/m/Y H:i');
            $row->end_date = get_date($row->end_date,'d/m/Y H:i');
            $row->grade = number_format(($row->reexamine ? $row->reexamine : $row->grade),2,',','.');
        }
        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function showRoadmap(Request $request, $user_id)
    {
        return view('user::backend.roadmap.index',[
            'user_id'=>$user_id,
            'full_name'=>$this->getFullNameUser($user_id),
        ]);
    }

    public function getDataRoadmap(Request $request, $user_id)
    {

        $user = ProfileView::where(['user_id'=>$user_id])->first();

        $query = TrainingProcess::query();
        $query->select([
            'a.subject_id',
            'a.completion_time',
            'd.code as training_program_code',
            'd.name as training_program_name',
            'subject.id',
            'subject.code as subject_code',
            'subject.name as subject_name',
        ]);
        $query->from("el_trainingroadmap AS a");
        $query->leftJoin('el_subject as subject', 'subject.id', '=', 'a.subject_id');
        $query->leftJoin('el_training_program as d','d.id','=','a.training_program_id');
        $query->where('a.title_id','=', $user->title_id);
        $count = $query->count();
        $rows = $query->get();
        foreach ($rows as $row) {
            $training_process = TrainingProcess::whereSubjectId($row->subject_id)->where(['titles_code'=> $user->title_code,'user_id'=>$user_id,'pass'=>1])->first();

            $row->start_date = $training_process ? get_date($training_process->start_date) : '';
            $row->end_date = $training_process ? get_date($training_process->end_date) : '';
            if ($training_process )
                $row->score = ($training_process && $training_process->mark) ? number_format($training_process->mark,2,',','.') : '';
            else
                $row->score = '';
            if ($row->training_program_code){
                $row->result = ($training_process && $training_process->pass==1)? trans('backend.finish') :'';
            }
            $row->start_effect = $row->completion_time && $training_process && $training_process->time_complete ? get_date($training_process->time_complete) :'-';
            $row->end_effect = $row->completion_time && $training_process && $training_process->time_complete ? get_date(strtotime($training_process->time_complete.' '.$row->completion_time.' days')) :'-';
            $row->status = $row->result;
            $row->note = $training_process ? $training_process->note : '';
            if ($training_process){
                if ($training_process->process_type==2)
                    $row->process_type = trans('backend.subject_complete');
                elseif ($training_process->process_type==4)
                    $row->process_type = trans('backend.merge_subject');
                elseif ($training_process->process_type==5)
                    $row->process_type = trans('backend.split_subject');
                else
                    $row->process_type = '-';
            }
            else
                $row->process_type = '-';
        }
        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function infoChange(){
        return view('user::backend.user.approve_info',[
        ]);
    }

    public function getDataHistoryChange(Request $request) {
        $search = $request->input('search');

        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = HistoryChangeInfo::query();
        $query->select([
            'a.*',
            'b.code as user_code',
            'b.firstname',
            'b.lastname',
            'c.name as unit_name',
            'd.name as unit_manager',
        ]);
        $query->from('el_history_change_info AS a');
        $query->leftJoin('el_profile as b', 'b.user_id', '=', 'a.user_id');
        $query->leftJoin('el_unit AS c', 'c.code', '=', 'b.unit_code');
        $query->leftJoin('el_unit AS d', 'd.code', '=', 'c.parent_code');
        $query->where('key','!=','avatar');

        if ($search) {
            $query->where(function ($sub_query) use ($search) {
                $sub_query->orWhere('b.lastname', 'like', '%'. $search .'%');
                $sub_query->orWhere('b.firstname', 'like', '%'. $search .'%');
                $sub_query->orWhere('b.email', 'like', '%'. $search .'%');
                $sub_query->orWhere('b.code', 'like', '%'. $search .'%');
            });
        }

        $count = $query->count();
        $query->orderBy('a.'.$sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row) {
            $row->full_name = $row->lastname . ' ' . $row->firstname;
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function approveUserInfo(Request $request)
    {
        $ids = $request->input('ids', null);
        $status = $request->input('status', null);

        foreach ($ids as $id) {
            $history = HistoryChangeInfo::find($id);
            $history->status = $status;
            $history->approve_by = profile()->user_id;
            $history->approve_time = date('Y-m-d H:i:s');
            $history->save();

            if ($status == 1){
                if ($history->key == 'avatar'){
                    $model =Profile::where('user_id', '=', $history->user_id)->first();
                    $model->avatar = $history->value_new;
                    $model->save();
                } elseif($history->key == 'phone') {
                    $model = Profile::where('user_id', '=', $history->user_id)->first();
                    $model->phone = $history->value_new;
                    $model->save();
                } elseif($history->key == 'email') {
                    $model = Profile::where('user_id', '=', $history->user_id)->first();
                    $model->email = $history->value_new;
                    $model->save();
                } else {
                    $user_meta = UserMeta::where('user_id', $history->user_id)->where('key', $history->key)->first();
                    if ($user_meta){
                        UserMeta::where('user_id', $history->user_id)->where('key', $history->key)
                            ->update([
                                'value' => $history->value_new,
                            ]);
                    }else{
                       UserMeta::insert([
                            'user_id' => $history->user_id,
                            'key' => $history->key,
                            'value' => $history->value_new,
                       ]);
                    }
                }
                json_result([
                    'status' => 'success',
                    'message' => 'Duyệt thành công',
                ]);
            } else {
                json_result([
                    'status' => 'error',
                    'message' => 'Duyệt không thành công',
                ]);
            }
        }

    }

    private function getFullNameUser($user_id)
    {
        $query = Profile::where('user_id', '=', $user_id);
        if ($query->exists()) {
            $data = $query->first(['firstname', 'lastname']);
            return $data->lastname . ' '. $data->firstname;
        }

        return '';
    }

    public function showTrainingByTitle(Request $request, $user_id)
    {
        return view('user::backend.training_by_title.index',[
            'user_id' => $user_id,
            'full_name'=>$this->getFullNameUser($user_id)
        ]);
    }

    public function getDataTrainingByTitle(Request $request, $user_id)
    {
        $user = ProfileView::where('user_id', '=', $user_id)->first();

        $query = TrainingByTitleDetail::query();
        $query->select([
            'a.subject_code',
            'a.subject_name',
            'b.course_id',
            'b.code as course_code',
            'b.name as course_name',
            'b.course_type',
            'b.start_date',
            'b.end_date',
        ]);
        $query->from("el_training_by_title_detail AS a");
        $query->leftJoin('el_course_view as b', 'b.subject_id', '=', 'a.subject_id');
        $query->where('b.status','=', 1);
        $query->where('a.title_id','=', $user->title_id);

        $count = $query->count();
        $rows = $query->get();
        foreach ($rows as $row) {
            if ($row->course_type == 1){
                $result = OnlineResult::whereCourseId($row->course_id)->where('user_id', '=', $user_id)->first();
                $course_type = 'Trực tuyến';
            }else{
                $result = OfflineResult::whereCourseId($row->course_id)->where('user_id', '=', $user_id)->first();
                $course_type = trans("latraining.offline");
            }
            $row->score = $result ? $result->score : '';
            $row->result = $result ? ($result->result == 1 ? 'Hoàn thành' : 'Chưa hoàn thành') : '';

            $row->start_date = get_date($row->start_date);
            $row->end_date = get_date($row->end_date);
            $row->course_type = $course_type;
        }
        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function syncAPIUser(Request $request)
    {
         \Modules\User\Entities\User::syncAPIUser($request->id);
    }

    // CHỨNG CHỈ BÊN NGOÀI
    public function certificateUser($user_id)
    {
        $full_name = Profile::fullname($user_id);
        return view('user::backend.certificate.index', [
            'user_id' => $user_id,
            'full_name' => $full_name,
        ]);
    }

    // LẤY DỮ LIỆU CHỨNG CHỈ CỦA HỌC VIÊN
    public function getDataCertificate($user_id, Request $request)
    {
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = MyCertificate::query();
        $query->select([
            'a.*',
            'b.full_name'
        ]);
        $query->from('el_my_certificate as a');
        $query->leftJoin('el_profile_view as b', 'b.user_id', '=', 'a.user_id');
        $query->where('a.user_id', $user_id);
        $count = $query->count();

        $query->orderBy('id');
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();
        foreach ($rows as $row) {
            $row->time_start = get_date($row->time_start, 'd/m/Y');
            $row->date_license = get_date($row->date_license, 'd/m/Y');
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    // TẢI CHỬNG CHỈ BÊN NGOÀI CỦA HỌC VIÊN
    public function downloadCertificate(Request $request)
    {
        $certificate = MyCertificate::find($request->id);
        $link_download = link_download('uploads/'. $certificate->certificate);
        json_result([
            'link_download' => $link_download
        ]);
    }

    public function userMedal($user_id){
        $full_name = Profile::fullname($user_id);
        return view('user::backend.usermedal.index', [
            'user_id' => $user_id,
            'full_name' => $full_name,
        ]);
    }

    public function getDataUserMedal($user_id, Request $request){
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = UserMedalResult::query();
        $query->select([
            'a.id',
            'a.created_at',
            'd.name as user_medal',
            'c.name as submedal_name',
            'c.photo',
            'c.rank as submedal_rank',
        ]);
        $query->from('el_usermedal_completed AS a');
        $query->leftJoin('el_usermedal_settings_items AS b', 'b.id', 'a.settings_items_id_got');
        $query->leftJoin('el_usermedal AS c', 'c.id', 'b.usermedal_id');
        $query->leftJoin('el_usermedal AS d', 'd.id', 'c.parent_id');
        $query->where('a.user_id', $user_id);

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();

        foreach ($rows as $row) {
            $row->image_submedal = '<img src="'.image_file($row->photo).'" class="w-100">';
            $row->datecreated = get_date($row->created_at);
        }

        return response()->json(['total' => $count, 'rows' => $rows]);
    }

    public function dashboard($user_id){
        $pieChartOnlineCourse = $this->pieChartOnlineCourse($user_id);
        $pieChartOfflineCourse = $this->pieChartOfflineCourse($user_id);
        $pieChartQuiz = $this->pieChartQuiz($user_id);

        return view('user::backend.user.dashboard',[
            'user_id' => $user_id,
            'full_name'=> $this->getFullNameUser($user_id),
            'pieChartOnlineCourse' => $pieChartOnlineCourse,
            'pieChartOfflineCourse' => $pieChartOfflineCourse,
            'pieChartQuiz' => $pieChartQuiz,
        ]);
    }

    public function dataOnline($user_id, Request $request) {
        $sort = $request->get('sort', 'name');
        $order = $request->get('order', 'desc');
        $offset = $request->get('offset', 0);
        $limit = $request->get('limit', 50);

        $query = DB::table('el_online_register');
        $query->select([
            'el_online_register.id',
            'el_online_register.course_id',
            'el_online_register.user_id',
            'course.code',
            'course.name',
            'course.start_date',
            'course.end_date',
        ]);
        $query->leftJoin('el_online_course as course', 'course.id', '=', 'el_online_register.course_id');
        $query->where('el_online_register.user_id', $user_id);
        $query->where('el_online_register.status', 1);
        $query->where('course.status', 1);
        $query->where('course.isopen', 1);

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();

        foreach ($rows as $row) {
            $row->time = get_date($row->start_date) .' - '. ($row->end_date ? get_date($row->end_date) : 'Vô thời hạn');

            $condition = OnlineCourseCondition::where('course_id', '=', $row->course_id)->first();
            $activity_condition = ($condition && $condition->activity) ? explode(',', $condition->activity) : [];
            $activity_complete = OnlineCourseActivityCompletion::whereCourseId($row->course_id)->where('user_id', $row->user_id)->where('status', 1)->count();

            $bg_color = '';
            $percent = '';

            $studying = OnlineRegister::whereStatus(1)
                ->where('user_id', $row->user_id)
                ->where('course_id', $row->course_id)
                ->whereNotNull('cron_complete')
                ->where(DB::raw(1), '=', function($sub) use($activity_condition, $row){
                    $sub->select(DB::raw('CASE WHEN COUNT(activity_id) < '.count($activity_condition).' THEN 1 ELSE 0 END'))
                        ->from('el_online_course_activity_completion')
                        ->whereColumn('course_id', '=', 'el_online_register.course_id')
                        ->whereColumn('user_id', '=', 'el_online_register.user_id')
                        ->where('user_id', $row->user_id)
                        ->where('status', 1);
                })->exists();
            $not_learned = OnlineRegister::whereStatus(1)->whereCourseId($row->course_id)->whereUserId($row->user_id)->whereNull('cron_complete')->exists();

            $complete = OnlineCourseComplete::whereCourseId($row->course_id)->where('user_id', $row->user_id)->exists();
            if($complete){
                $bg_color = 'completed';
                $percent = '100%';
            }else if($not_learned){
                $bg_color = 'not_learned';
            }else if($studying){
                $bg_color = 'studying';
                $percent = count($activity_condition) > 0 ? number_format($activity_complete/count($activity_condition)*100) .'%' : '';
            }else{
                $bg_color = 'uncomplete';
                $percent = count($activity_condition) > 0 ? number_format($activity_complete/count($activity_condition)*100) .'%' : '';
            }

            $row->bg_color = $bg_color;
            $row->percent = $percent;
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function dataOffline($user_id, Request $request) {
        $sort = $request->get('sort', 'name');
        $order = $request->get('order', 'desc');
        $offset = $request->get('offset', 0);
        $limit = $request->get('limit', 50);

        $query = DB::table('el_offline_register');
        $query->select([
            'el_offline_register.id',
            'el_offline_register.course_id',
            'el_offline_register.class_id',
            'el_offline_register.user_id',
            'course.code',
            'course.name',
            'course.start_date',
            'course.end_date',
        ]);
        $query->leftJoin('el_offline_course as course', 'course.id', '=', 'el_offline_register.course_id');
        $query->where('el_offline_register.user_id', $user_id);
        $query->where('el_offline_register.status', 1);
        $query->where('course.status', 1);
        $query->where('course.isopen', 1);

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();

        foreach ($rows as $row) {
            $row->time = get_date($row->start_date) .' - '. ($row->end_date ? get_date($row->end_date) : 'Vô thời hạn');

            $schedule = OfflineSchedule::whereCourseId($row->course_id)->where('class_id', $row->class_id)->count();
            $attendance = OfflineAttendance::where('course_id', $row->course_id)->where('user_id', $row->user_id)->where('status', 1)->sum('percent');

            $bg_color = '';
            $percent = '';

            $studying = OfflineRegister::whereStatus(1)
                ->where('user_id', $row->user_id)
                ->where('course_id', $row->course_id)
                ->whereNotNull('cron_complete')
                ->whereExists(function($sub) {
                    $sub->select(\DB::raw(1))
                        ->from('el_offline_attendance')
                        ->whereColumn('course_id', '=', 'el_offline_register.course_id')
                        ->whereColumn('user_id', '=', 'el_offline_register.user_id')
                        ->where('status', 1);
                })
                ->whereNotExists(function($sub) {
                    $sub->select(\DB::raw(1))
                        ->from('el_offline_course_complete')
                        ->whereColumn('course_id', '=', 'el_offline_register.course_id')
                        ->whereColumn('user_id', '=', 'el_offline_register.user_id');
                })->exists();

            $not_learned = OfflineRegister::whereStatus(1)->whereCourseId($row->course_id)->whereUserId($row->user_id)->whereNull('cron_complete')->exists();

            $complete = OfflineCourseComplete::whereCourseId($row->course_id)->where('user_id', $row->user_id)->exists();
            if($complete){
                $bg_color = 'completed';
                $percent = '100%';
            }else if($not_learned){
                $bg_color = 'not_learned';
            }else if($studying){
                $bg_color = 'studying';
                $percent = $schedule ? number_format($attendance/$schedule) .'%' : '';
            }else{
                $bg_color = 'uncomplete';
                $percent = $schedule ? number_format($attendance/$schedule) .'%' : '';
            }

            $row->bg_color = $bg_color;
            $row->percent = $percent;
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function dataQuiz($user_id, Request $request) {
        $sort = $request->get('sort', 'name');
        $order = $request->get('order', 'desc');
        $offset = $request->get('offset', 0);
        $limit = $request->get('limit', 50);

        $query = DB::table('el_quiz_register');
        $query->select([
            'el_quiz_register.id',
            'el_quiz_register.quiz_id',
            'el_quiz_register.user_id',
            'quiz.code',
            'quiz.name',
            'quiz.start_quiz',
            'quiz.end_quiz',
        ]);
        $query->leftJoin('el_quiz as quiz', 'quiz.id', '=', 'el_quiz_register.quiz_id');
        $query->where('el_quiz_register.user_id', $user_id);
        $query->where('quiz.status', 1);
        $query->where('quiz.is_open', 1);

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();

        foreach ($rows as $row) {
            $row->time = get_date($row->start_quiz) .' - '. ($row->end_quiz ? get_date($row->end_quiz) : 'Vô thời hạn');

            $bg_color = '';
            $percent = '';

            $not_learned = QuizRegister::whereQuizId($row->quiz_id)
                ->where('user_id', $row->user_id)
                ->whereNotExists(function($sub){
                    $sub->select(\DB::raw(1))
                        ->from('el_quiz_attempts')
                        ->whereColumn('quiz_id', '=', 'el_quiz_register.quiz_id')
                        ->whereColumn('user_id', '=', 'el_quiz_register.user_id');
                })->exists();

            $complete = QuizRegister::whereQuizId($row->quiz_id)
                ->where('user_id', $row->user_id)
                ->whereExists(function($sub){
                    $sub->select(\DB::raw(1))
                        ->from('el_quiz_result')
                        ->whereColumn('quiz_id', '=', 'el_quiz_register.quiz_id')
                        ->whereColumn('user_id', '=', 'el_quiz_register.user_id')
                        ->where('result', 1);
                })->exists();

            if($complete){
                $bg_color = 'completed';
                $percent = '100%';
            }else if($not_learned){
                $bg_color = 'not_learned';
            }else{
                $bg_color = 'uncomplete';
                $percent = '0%';
            }

            $row->bg_color = $bg_color;
            $row->percent = $percent;
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    //Biểu đồ tròn khoá học Online
    private function pieChartOnlineCourse($user_id)
    {
        $total_register = OnlineRegister::whereStatus(1)->whereUserId($user_id)
            ->whereExists(function($sub){
                $sub->select(['id'])
                ->from('el_online_course')
                ->whereColumn('id', '=', 'el_online_register.course_id')
                ->where('status', 1)
                ->where('isopen', 1);
            })
            ->count('course_id');
        $not_learned = OnlineRegister::whereStatus(1)->whereUserId($user_id)->whereNull('cron_complete')
            ->whereExists(function($sub){
                $sub->select(['id'])
                ->from('el_online_course')
                ->whereColumn('id', '=', 'el_online_register.course_id')
                ->where('status', 1)
                ->where('isopen', 1);
            })
            ->count('course_id');
        $completed = OnlineCourseComplete::where('user_id', $user_id)
            ->whereExists(function($sub){
                $sub->select(['id'])
                ->from('el_online_course')
                ->whereColumn('id', '=', 'el_online_course_complete.course_id')
                ->where('status', 1)
                ->where('isopen', 1);
            })
            ->count('course_id');
        $studying = OnlineRegister::whereStatus(1)
            ->where('user_id', $user_id)
            ->whereNotNull('cron_complete')
            ->where(
                function($sub){
                    $sub->select(\DB::raw('COUNT(a.id)'))
                        ->from('el_online_course_activity as a')
                        ->join('el_online_course_condition as b', 'b.course_id', '=', 'a.course_id')
                        ->whereRaw('FIND_IN_SET(a.id,b.activity)')
                        ->whereColumn('a.course_id', '=', 'el_online_register.course_id')
                        ->groupBy('a.course_id');
                }, '>', function($sub2){
                    $sub2->select(\DB::raw('COUNT(activity_id)'))
                    ->from('el_online_course_activity_completion')
                    ->whereColumn('course_id', '=', 'el_online_register.course_id')
                    ->whereColumn('user_id', '=', 'el_online_register.user_id')
                    ->where('status', 1);
                }
            )
            ->whereExists(function($sub){
                $sub->select(['id'])
                ->from('el_online_course')
                ->whereColumn('id', '=', 'el_online_register.course_id')
                ->where('status', 1)
                ->where('isopen', 1);
            })
            ->count('course_id');
        $uncompleted = $total_register - ($studying + $not_learned + $completed);

        $result = [$studying, $not_learned, $completed, $uncompleted];

        return $result;
    }

    //Biểu đồ tròn khoá học Offline
    private function pieChartOfflineCourse($user_id)
    {
        $total_register = OfflineRegister::whereStatus(1)->whereUserId($user_id)
            ->whereExists(function($sub){
                $sub->select(['id'])
                ->from('el_offline_course')
                ->whereColumn('id', '=', 'el_offline_register.course_id')
                ->where('status', 1)
                ->where('isopen', 1);
            })
            ->count('course_id');
        $not_learned = OfflineRegister::whereStatus(1)->whereUserId($user_id)->whereNull('cron_complete')
            ->whereExists(function($sub){
                $sub->select(['id'])
                ->from('el_offline_course')
                ->whereColumn('id', '=', 'el_offline_register.course_id')
                ->where('status', 1)
                ->where('isopen', 1);
            })
            ->count('course_id');
        $completed = OfflineCourseComplete::where('user_id', $user_id)
            ->whereExists(function($sub){
                $sub->select(['id'])
                ->from('el_offline_course')
                ->whereColumn('id', '=', 'el_offline_course_complete.course_id')
                ->where('status', 1)
                ->where('isopen', 1);
            })
            ->count('course_id');
        $studying = OfflineRegister::whereStatus(1)
            ->where('user_id', $user_id)
            ->whereNotNull('cron_complete')
            ->whereExists(function($sub) {
                $sub->select(\DB::raw(1))
                    ->from('el_offline_attendance')
                    ->whereColumn('course_id', '=', 'el_offline_register.course_id')
                    ->whereColumn('user_id', '=', 'el_offline_register.user_id')
                    ->where('status', 1);
            })
            ->whereNotExists(function($sub) {
                $sub->select(\DB::raw(1))
                    ->from('el_offline_course_complete')
                    ->whereColumn('course_id', '=', 'el_offline_register.course_id')
                    ->whereColumn('user_id', '=', 'el_offline_register.user_id');
            })
            ->whereExists(function($sub){
                $sub->select(['id'])
                ->from('el_offline_course')
                ->whereColumn('id', '=', 'el_offline_register.course_id')
                ->where('status', 1)
                ->where('isopen', 1);
            })
            ->count('course_id');
        $uncompleted = $total_register - ($studying + $not_learned + $completed);

        $result = [$studying, $not_learned, $completed, $uncompleted];

        return $result;
    }

    //Biểu đồ tròn kỳ thi
    private function pieChartQuiz($user_id)
    {
        //Tổng số ghi danh kỳ thi
        $register = QuizRegister::where('user_id', $user_id)
            ->whereExists(function($sub){
                $sub->select(['id'])
                ->from('el_quiz')
                ->whereColumn('id', '=', 'el_quiz_register.quiz_id')
                ->where('status', 1)
                ->where('is_open', 1);
            })
            ->count('quiz_id');

        //HV chưa thi. Mới ghi danh, chưa nằm trong bảng lần làm bài thi (el_quiz_attempts)
        $unlearned = QuizRegister::where('user_id', $user_id)
            ->whereExists(function($sub){
                $sub->select(['id'])
                ->from('el_quiz')
                ->whereColumn('id', '=', 'el_quiz_register.quiz_id')
                ->where('status', 1)
                ->where('is_open', 1);
            })
            ->whereNotExists(function($sub){
                $sub->select(\DB::raw(1))
                    ->from('el_quiz_attempts')
                    ->whereColumn('quiz_id', '=', 'el_quiz_register.quiz_id')
                    ->whereColumn('user_id', '=', 'el_quiz_register.user_id');
            })->count('quiz_id');

        //HV hoàn thành thi
        $completed = QuizRegister::where('user_id', $user_id)
            ->whereExists(function($sub){
                $sub->select(['id'])
                ->from('el_quiz')
                ->whereColumn('id', '=', 'el_quiz_register.quiz_id')
                ->where('status', 1)
                ->where('is_open', 1);
            })
            ->whereExists(function($sub){
                $sub->select(\DB::raw(1))
                    ->from('el_quiz_result')
                    ->whereColumn('quiz_id', '=', 'el_quiz_register.quiz_id')
                    ->whereColumn('user_id', '=', 'el_quiz_register.user_id')
                    ->where('result', 1);
            })->count('quiz_id');

        //HV chưa hoàn thành thi
        $uncompleted = $register - ($unlearned + $completed);

        $result = [$unlearned, $completed, $uncompleted];

        return $result;
    }
}
