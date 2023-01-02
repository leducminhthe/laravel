<?php

namespace Modules\Notify\Http\Controllers;

use App\Models\Profile;
use App\Scopes\DraftScope;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Modules\AppNotification\Helpers\AppNotification;
use Modules\Notify\Entities\NotifySend;
use Modules\Notify\Entities\NotifySendObject;
use Modules\Notify\Imports\ProfileImport;
use App\Models\Permission;
use App\Models\UserRole;
use App\Models\Categories\Unit;
use Modules\Notify\Entities\Notify;

class NotifySendController extends Controller
{
    public function index()
    {
        return view('notify::backend.notify_send.index');
    }

    public function getData(Request $request) {
        $search = $request->input('search');
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);
        NotifySend::addGlobalScope(new DraftScope());
        $query = NotifySend::query()->select(['*']);
        if ($search) {
            $query->where('subject', 'like', '%'. $search .'%');
        }

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row) {
            $profile = Profile::find($row->created_by);

            $row->edit_url = route('module.notify_send.edit', ['id' => $row->id]);
            $row->created_at2 = get_date($row->created_at);
            $row->created_by2 = $profile->lastname . ' ' . $profile->firstname;
        }
        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function save(Request $request) {
        $this->validateRequest([
            'subject' => 'required',
        ], $request, NotifySend::getAttributeName());

        $model = NotifySend::firstOrNew(['id' => $request->id]);
        $model->fill($request->all());
        $model->time_send = $request->time_send ? date_convert($request->time_send, $request->start_time.":00") : null;
        $model->created_by = profile()->user_id;

        if ($model->save()) {
            json_result([
                'status' => 'success',
                'message' => trans('laother.successful_save'),
                'redirect' => route('module.notify_send.edit', ['id' => $model->id])
            ]);
        }
        json_message(trans('laother.save_error'), 'error');
    }

    public function form($id = 0) {
        $errors = session()->get('errors');
        \Session::forget('errors');

        if ($id) {
            $model = NotifySend::find($id);
            $page_title = $model->subject;

            return view('notify::backend.notify_send.form', [
                'model' => $model,
                'page_title' => $page_title
            ]);
        }
        $model =  new NotifySend();
        $page_title = trans('lasetting.add_new') ;

        return view('notify::backend.notify_send.form', [
            'model' => $model,
            'page_title' => $page_title,
        ]);
    }

    public function remove(Request $request) {
        $ids = $request->input('ids', null);
        NotifySend::destroy($ids);
        json_result([
            'status' => 'success',
            'message' => trans('laother.delete_success'),
        ]);
    }

    public function saveObject($notify_send_id, Request $request){
        $this->validateRequest([
            'unit_id' => 'nullable|exists:el_unit,id',
            'title_id' => 'nullable|exists:el_titles,id',
        ], $request);

        $title_id = $request->input('title_id');
        $unit_id = $request->input('unit_id');

        if ($unit_id){
            foreach ($unit_id as $item){
                if (NotifySendObject::checkObjectUnit($notify_send_id, $item)){
                    continue;
                }
                $model = new NotifySendObject();
                $model->notify_send_id = $notify_send_id;
                $model->unit_id = $item;
                $model->save();
            }
            json_result([
                'status' => 'success',
                'message' => trans('laother.success_add_unit'),
            ]);
        }else{
            foreach ($title_id as $item){
                if (NotifySendObject::checkObjectTitle($notify_send_id, $item)){
                    continue;
                }
                $model = new NotifySendObject();
                $model->notify_send_id = $notify_send_id;
                $model->title_id = $item;
                $model->save();
            }
            json_result([
                'status' => 'success',
                'message' => trans('laother.success_add_title'),
            ]);
        }
    }

    public function getUserObject($notify_send_id, Request $request){
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = NotifySendObject::query();
        $query->select([
            'a.*',
            'b.title_name',
            'b.unit_name',
            'b.code AS profile_code',
            'b.lastname',
            'b.firstname',
            'b.parent_unit_name',
        ]);
        $query->from('el_notify_send_object AS a');
        $query->leftJoin('el_profile_view AS b', 'b.user_id', '=', 'a.user_id');
        $query->where('a.notify_send_id', '=', $notify_send_id);
        $query->whereNotNull('a.user_id');

        $count = $query->count();
        $query->orderBy('a.'.$sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row){
            $row->send_by = $row->send_by ? Profile::usercode($row->send_by) : '';
            $row->time_send = get_date($row->time_send, 'H:i:s d/m/Y');
            $row->profile_name = $row->lastname . ' ' . $row->firstname;
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function getObject($notify_send_id, Request $request){
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = NotifySendObject::query();
        $query->select([
            'a.*',
            'b.name AS title_name',
            'c.name AS unit_name',
            'e.name as unit_manager'
        ]);
        $query->from('el_notify_send_object AS a');
        $query->leftJoin('el_titles AS b', 'b.id', '=', 'a.title_id');
        $query->leftJoin('el_unit AS c', 'c.id', '=', 'a.unit_id');
        $query->leftJoin('el_unit AS e', 'e.code', '=', 'c.parent_code');
        $query->where('a.notify_send_id', '=', $notify_send_id);
        $query->whereNull('a.user_id');

        $count = $query->count();
        $query->orderBy('a.'.$sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row){
            $row->send_by = $row->send_by ? Profile::usercode($row->send_by) : '';
            $row->time_send = get_date($row->time_send, 'H:i:s d/m/Y');
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function removeObject($notify_send_id, Request $request){
        $this->validateRequest([
            'ids' => 'required',
        ], $request, [
            'ids' => trans('latraining.object'),
        ]);

        $item = $request->input('ids');
        NotifySendObject::destroy($item);
        json_result([
            'status' => 'success',
            'message' => trans('laother.delete_success'),
        ]);
    }

    public function importObject($notify_send_id, Request $request){
        $this->validateRequest([
            'import_file' => 'required|file'
        ], $request, ['import_file' => 'File import']);

        $type_import = $request->type_import;
        // kiểm tra nhân viên có thuộc đơn vị quản lý hay ko
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

        $import = new ProfileImport($notify_send_id, $user_role, $type_import);
        \Excel::import($import, $request->file('import_file'));

        if ($import->errors) {
            session()->put('errors', $import->errors);
            session()->save();
        }

        json_result([
            'status' => 'success',
            'message' => 'Import ' . trans('laapi.success'),
            'redirect' => route('module.notify_send.edit', ['id' => $notify_send_id]),
        ]);
    }

    public function ajaxIsopenPublish(Request $request) {
        $this->validateRequest([
            'ids' => 'required',
            'status' => 'required|in:0,1'
        ], $request, [
            'ids' => trans('laprofile.rank'),
        ]);

        $ids = $request->input('ids', null);
        $status = $request->input('status', 0);
        if(is_array($ids)) {
            foreach ($ids as $id) {
                $model = NotifySend::findOrFail($id);
                $model->status = $status;
                $model->save();
            }
        } else {
            $model = NotifySend::findOrFail($ids);
            $model->status = $status;
            $model->save();
        }

        json_result([
            'status' => 'success',
            'message' => trans('laother.successful_save'),
        ]);
    }

    public function sendObject(Request $request){
        $this->validateRequest([
            'ids' => 'required',
        ], $request, [
            'ids' => trans('latraining.object'),
        ]);

        $ids = $request->input('ids', null);
        foreach($ids as $id){
            $obj = NotifySendObject::findOrFail($id);
            $obj->status = 1;
            $obj->time_send = date('Y-m-d H:i:s');
            $obj->send_by = profile()->user_id;
            $obj->save();


            $model = NotifySend::where('id', '=', $obj->notify_send_id)
                ->where('status', '=', 1)
                ->first();

            if (empty($model)) {
                continue;
            }

            $profile = Profile::query()
                ->select(['profile.user_id'])
                ->from('el_profile as profile')
                ->leftJoin('el_unit as unit', 'unit.code', '=', 'profile.unit_code')
                ->leftJoin('el_titles as titles', 'titles.code', '=', 'profile.title_code')
                ->where(function ($sub) use ($obj){
                    $sub->orWhere('unit.id', '=', $obj->unit_id);
                    $sub->orWhere('titles.id', '=', $obj->title_id);
                    $sub->orWhere('profile.user_id', '=', $obj->user_id);
                })
                ->get();

            if (empty($profile)) {
                continue;
            }

            $notify = new Notify();
            $notify->subject = $model->subject;
            $notify->content = $model->content;
            $notify->url = '';
            $notify->users = $profile->pluck('user_id')->toArray();
            $notify->addMultiNotify();
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Gửi thành công',
        ]);
    }
}
