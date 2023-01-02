<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\ProfileView;
use Composer\Autoload\ClassMapGenerator;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Modules\PermissionApproved\Entities\ApprovedModelTracking;
use Modules\PermissionApproved\Entities\PermissionApproved;
use Modules\PermissionApproved\Entities\PermissionApprovedUser;
use Modules\Survey\Entities\Survey;
use Modules\User\Entities\User;
use App\Models\Categories\Unit;

use Modules\Notify\Entities\Notify;
use Modules\Notify\Entities\NotifyTemplate;
use App\Models\Profile;
use Illuminate\Support\Facades\DB;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function callAction($method, $parameters)
    {
        /*$route_name = \Request::route()->getName();
        $permission = \App\Models\Permission::where('code', $route_name)->first();
        if ($permission) {

            $haspermission = \permission($route_name);
            if (empty($haspermission)) {
                $extends = explode(',', $permission->extend);
                foreach ($extends as $extend) {
                    if (\permission(trim($extend))) {
                        $haspermission = true;
                    }
                }
            }

            if ($route_name) {
                if (!$haspermission) {
                    abort(403);
                }
            }
        }*/

        // if (\Auth::check()) {
        //     \Auth::user()->updateAnalytics();
        // }


        return parent::callAction($method, $parameters);
    }

    public function validateRequest($rules, Request $request, $attributeNames = null)
    {
        $validator = Validator::make($request->all(), $rules);

        if ($attributeNames) {
            $validator->setAttributeNames($attributeNames);
        }

        if ($validator->fails()) {
            json_message($validator->errors()->all()[0], 'error');
        }
    }

    public function checkSelectUnit()
    {
        $check = User::getRoleAndManagerUnitUser();
        $user_unit = session()->get('user_unit');
        $user_role = session()->get('user_role');
        $user_role_selected = session()->get('user_role_selected');

        if (!$user_role){
            $roles = User::getRoles();
            if (count($roles)>1)
                json_result(['modal'=>true,'type'=>'role']);
            else{
                \session()->put('user_role',$roles[0]->role);
                \session()->save();
            }
        }

        if(!$user_unit){
            if (count($check)>1)
                json_result(['modal'=>true,'type'=>'unit']);
            else{
                \session()->put('user_unit',$check[0]->id);
                \session()->save();
                json_result(['modal'=>false,'unit'=>false]);
            }
        }
        if (!isset($user_unit) || $user_role_selected != $user_role){//dd(2,$user_unit,$user_role,$user_role_selected);
            if(count($check)>1){
                json_result(['modal'=>true,'type'=>'unit']);
            }else{
                \session()->put('user_unit',$check[0]->id);
                \session()->put('user_role_selected',$user_role);
                \session()->save();
            }
        }

    }

    public function getUnitManagers()
    {
        $check = User::getRoleAndManagerUnitUser();
        $unitId = [];
        if (!empty($check)) {
            $result = array_reduce($check, function ($a, $b) {
                return $a ? ($a->level < $b->level ? $a : $b) : $b;
            });
            foreach ($check as $key => $value) {
                if ($value->level == $result->level) {
                    $unitId[] = $value->id;
                }
            }
        }
        return $unitId;
    }

    public function saveSelectUnit(Request $request)
    {
        $user_role = session()->get('user_role');
        if ($user_role=='unit_manager')
            return redirect()->route('module.dashboard_unit');
        return redirect()->route('module.dashboard');

    }

    public function saveSelectRole(Request $request)
    {
        $role_select = $request->input('role-select');
        \session()->put('user_role', $role_select);
        \session()->save();
        if ($role_select == 'unit_manager')
            $redirect = route('module.dashboard_unit');
        elseif ($role_select == 'manager')
            $redirect = route('module.dashboard');
        elseif ($role_select == 'teacher')
            $redirect = route('backend.category.training_teacher.list_permission');
        else
            $redirect = route('module.dashboard');
        json_result([
            'status' => 'ok',
            'redirect' => $redirect
        ]);
    }

    public function getRolesUser()
    {
        $user = \Auth::user();
        $roles = $user->roles()->get();

    }

    public function approve(Request $request)
    {
        $model = $request->model;
        $name = \Str::ucfirst(\Str::camel(substr($model, 3)));
        $slash = '\\';
        $modules = \Module::all();
        foreach ($modules as $index => $item) {
            $module = \Module::find($item->name);
            $isDir = is_dir($module->getPath() . '/Http/Controllers/Backend');
            if (!$isDir)
                continue;
            $classController = 'Modules' . $slash . $item->name . $slash . 'Http' . $slash . 'Controllers' . $slash . 'Backend' . $slash . $name . 'Controller';
            $class_name = class_exists($classController);
            if ($class_name) {
                $controller = new $classController();
                // if ($request->status == 1) {
                //     $this->sentNotificationAfterApproved($request, $model);
                // }
                return $controller->approve($request);
            }
        };
        return abort(404);
    }

    public function sentNotificationAfterApproved($request, $table)
    {
        $ids = $request->ids;
        $currentUserId = Auth::user()->id;

        foreach ($ids as $id) {
            $unitByOfModel = DB::table($table)->where('id', $id)->value('unit_by');
            // $modelName=DB::table($table)->where('id',$id)->value('name');
            //nhân viên
            $unitIdOfCurrentUsers = DB::table('el_permission_approved_user')->where('unit_id', $unitByOfModel)
                ->where('model_approved', $table)
                ->pluck('user_id')->toArray();
            foreach ($unitIdOfCurrentUsers as $value) {
                if ($currentUserId == $value) {
                    $level = DB::table('el_permission_approved_user')->where('user_id', $value)->value('level');
                    $ids = DB::table('el_permission_approved_user')->where('level', $level + 1)->where('model_approved', $table)->pluck('user_id')->toArray();
                    foreach ($ids as $id) {
                        $nottify_template = NotifyTemplate::query()->where('code', '=', 'approve_notification')->first();
                        $subject_notify =  $nottify_template->title;
                        $content_notify = $nottify_template->content;
                        $notify = new Notify();
                        $notify->subject = $subject_notify;
                        $notify->content = $content_notify;
                        $notify->url = '';
                        $notify->users = Profile::where('id', $id)->pluck('user_id')->toArray();
                        $notify->addMultiNotify();
                    }
                }
            }

            //chức danh
            $titleIdOfCurrentTitles = DB::table('el_permission_approved_title')->where('unit_id', $unitByOfModel)
                ->where('model_approved', $table)
                ->pluck('title_id')->toArray();
            foreach ($titleIdOfCurrentTitles as $titleIdOfCurrentTitle) {
                $userOfTitles = DB::table('el_profile')->where('title_id', $titleIdOfCurrentTitle)->pluck('user_id')->toArray();
                foreach ($userOfTitles as $user) {
                    $roleIdOfUserRole = DB::table('el_user_role')->where('user_id', $user)->value('role_id');
                    switch ($table) {
                        case('el_course_plan'):
                            $permissionId = 523;
                            break;
                        case('el_offline_course'):
                            $permissionId = 235;
                            break;
                        case ('el_offline_course_register'):
                            $permissionId = 241;
                            break;
                        case ('el_online_course'):
                            $permissionId = 220;
                            break;
                        case ('el_online_course_register'):
                            $permissionId = 227;
                            break;
                        case ('el_quiz'):
                            $permissionId = 272;
                            break;
                        case ('el_quiz_template'):
                            $permissionId = 302;
                            break;
                    }
                    $roleIdOfRolePermissionType = DB::table('el_role_permission_type')->where('permission_id', $permissionId)->value('role_id');
                    if ($roleIdOfUserRole === $roleIdOfRolePermissionType) {
                        $nottify_template = NotifyTemplate::query()->where('code', '=', 'approve_notification')->first();
                        $subject_notify = $nottify_template->title;
                        $content_notify = $nottify_template->content;
                        $notify = new Notify();
                        $notify->subject = $subject_notify;
                        $notify->content = $content_notify;
                        $notify->url = '';
                        $notify->users = Profile::where('id', $user)->pluck('user_id')->toArray();
                        $notify->addMultiNotify();
                    }
                }
            }

            //cấp duyệt
            $objectLevelOfCurrentObject = DB::table('el_permission_approved_object')->where('unit_id', $unitByOfModel)
                ->where('model_approved', $table)
                ->value('object_id');
            $createdById = DB::table($table)->where('id', $id)->value('created_by');
            $unitIdOfCreator = DB::table('el_profile')->where('id', $createdById)->value('unit_id');
            $levelOfUnit = DB::table('el_unit')->where('id', $unitIdOfCreator)->value('level');
            switch ($objectLevelOfCurrentObject) {
                case (1):
                    $level = $levelOfUnit;
                    break;
                case (2):
                    $level = $levelOfUnit - 1;
                    break;
                case (3):
                    $level = $levelOfUnit - 2;
                    break;
            }
            if ($level!=null && $level>=0){
                $unitLevel = DB::table('el_unit_view')->where('unit' . $levelOfUnit . '_id', $unitIdOfCreator)->value('unit' . $level . '_id');
            }
            if ($unitLevel >= 0) {
                $userOfObjects = DB::table('el_profile')->where('unit_id', $unitLevel)->pluck('user_id')->toArray();
                foreach ($userOfObjects as $user) {
                    $roleIdOfUserRole = DB::table('el_user_role')->where('user_id', $user)->value('role_id');
                    switch ($table) {
                        case('el_course_plan'):
                            $permissionId = 523;
                            break;
                        case('el_offline_course'):
                            $permissionId = 235;
                            break;
                        case ('el_offline_course_register'):
                            $permissionId = 241;
                            break;
                        case ('el_online_course'):
                            $permissionId = 220;
                            break;
                        case ('el_online_course_register'):
                            $permissionId = 227;
                            break;
                        case ('el_quiz'):
                            $permissionId = 272;
                            break;
                        case ('el_quiz_template'):
                            $permissionId = 302;
                            break;
                    }
                }
                $roleIdOfRolePermissionType = DB::table('el_role_permission_type')->where('permission_id', $permissionId)->value('role_id');
                if ($roleIdOfUserRole === $roleIdOfRolePermissionType) {
                    $nottify_template = NotifyTemplate::query()->where('code', '=', 'approve_notification')->first();
                    $subject_notify = $nottify_template->title;
                    $content_notify = $nottify_template->content;
                    $notify = new Notify();
                    $notify->subject = $subject_notify;
                    $notify->content = $content_notify;
                    $notify->url = '';
                    $notify->users = Profile::where('id', $user)->pluck('user_id')->toArray();
                    $notify->addMultiNotify();
                }
            }
        }
        return true;
    }

    public function showModalNoteApproved(Request $request)
    {
        $model = $request->model;
        return view('modal.backend.note_approved',
            [
                'model' => $model
            ]);
    }

    public function showModalStepApproved(Request $request)
    {
        return view('modal.backend.step_approved');
    }

    public function getApprovedStep(Request $request)
    {
        $user_unit = session()->get('user_unit');
        $unit = Unit::find($user_unit);
        if($unit->level != 0) {
            $parent_units = Unit::getTreeParentUnit($unit->code);
            foreach ($parent_units as $key => $parent) {
                if($parent->level == 0) {
                    $parent_unit = $parent->id;
                } else {
                    continue;
                }
            }
        } else {
            $parent_unit = $unit->id;
        }

        $search = $request->input('search');
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);
        $model_id = $request->input('model_id');
        $model = $request->input('model');
        $query = PermissionApproved::query();
        $query->select([
            'a.level',
            'b.id',
            'b.status',
            'b.note',
            'b.created_by_name',
            'b.created_at',
        ])->disableCache();
        $query->from('el_permission_approved as a');
        $query->leftjoin('el_approved_model_tracking as b', function($join) use ($model_id, $model) {
            $join->on('a.model_approved', '=', 'b.model');
            $join->on('a.level', '=', 'b.level');
            $join->where(['b.model_id' => $model_id, 'b.model' => $model]);
        });
        $query->where('a.model_approved', $model);
        $query->where('a.unit_id', $parent_unit);
        $count = $query->count();
        $query->orderBy('a.level', 'asc');
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row) {
            $row->status = isset($row->status) ? $row->status : 2;
            $row->approved_date = get_date($row->created_at, 'H:i d/m/Y');
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function updateSurveyPopup(Request $request)
    {
        $survey_id = $request->survey_id;

        $survey = Survey::find($survey_id);
        $survey->num_popup = $survey->num_popup - 1;
        $survey->save();

        json_result([
            'status' => 'ok',
        ]);
    }

    public function searchUser(Request $request)
    {
        $search = $request->search;
        $query = ProfileView::query();
        $query->where('user_id', '>', 2);
        $query->where('type_user', '=', 1);

        if ($search) {
            $query->where(function ($sub_query) use ($search) {
                $sub_query->orWhere('full_name', 'like', '%' . $search . '%');
                $sub_query->orWhere('code', '=', $search);
            });
        }

        $redirect = '';
        if ($query->exists()) {
            $user = $query->pluck('user_id')->toArray();

            if (count($user) > 1) {
                $redirect = route('module.backend.user');
            } else {
                $redirect = route('module.backend.user.edit', ['id' => $user[0]]);
            }
        }

        json_result([
            'status' => 'ok',
            'redirect' => $redirect
        ]);
    }

    // LẤY TẤT CẢ ĐƠN VỊ CON
    public function showChildUnitManager(Request $request) {
        $unit = Unit::find($request->parent_id);
        $arr_child = Unit::getArrayChild($unit->code, $unit->level);
        $childs = Unit::whereIn('id', $arr_child)->get(['id', 'code', 'name']);
        json_result([
            'childs' => $childs,
        ]);
    }

    // DỮ LIỆU CẤP DUYỆT KHI CHƯA DUYỆT
    public function getPermissionApprove(Request $request)
    {
        $unit_id = $request->unit_id;
        $model_approved = $request->model;

        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = PermissionApproved::query();
        $query->where('unit_id', $unit_id);
        $query->where('model_approved', $model_approved);
        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        $controller = new \Modules\PermissionApproved\Http\Controllers\PermissionApprovedController();
        foreach ($rows as $index => $row) {
            $permissionApprovedId = $row->id;
            $row->title_name= $controller->getGroupConcatTitle($permissionApprovedId);
            $row->full_name= $controller->getGroupConcatUser($permissionApprovedId);
            $row->object_name =  $controller->getObjectName($permissionApprovedId);
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }
}
