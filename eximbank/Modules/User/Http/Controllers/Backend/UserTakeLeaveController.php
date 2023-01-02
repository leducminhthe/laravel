<?php

namespace Modules\User\Http\Controllers\Backend;

use App\Models\Categories\Absent;
use App\Models\Profile;
use App\Models\Categories\Titles;
use App\Models\Categories\Unit;
use App\Models\Categories\Area;
use App\Scopes\DraftScope;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\User\Entities\ProfileTakeLeave;
use App\Models\Notifications;
use App\Imports\UserTakeLeave;
use App\Jobs\NotifyUserOfCompletedImportUserTakeLeave;
use Illuminate\Support\Str;
use App\Exports\ExportUserTakeLeave;

class UserTakeLeaveController extends Controller
{
    public function index() {
        $max_unit = Unit::getMaxUnitLevel();
        $level_name = function ($level) {
            return Unit::getLevelName($level);
        };

        $absents = Absent::whereStatus(1)->get();
        return view('user::backend.user.index2', [
            'max_unit' => $max_unit,
            'level_name' => $level_name,
            'absents' => $absents,
        ]);
    }

    public function getData(Request $request) {
        $search = $request->input('search');
        $unit = $request->unit_id;
        $title = $request->input('title');

        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        ProfileTakeLeave::addGlobalScope(new DraftScope('user_id'));
        $query = ProfileTakeLeave::query();
        $query->select([
            'a.*',
            'profile.code',
            'profile.email',
            'b.name AS unit_name',
            'c.name AS title_name',
            'd.name as position_name',
            'e.name as unit_manager',
        ]);
        $query->from('el_profile_take_leave as a');
        $query->leftJoin('el_profile as profile', 'profile.user_id', '=', 'a.user_id');
        $query->leftJoin('el_unit AS b', 'b.code', '=', 'profile.unit_code');
        $query->leftJoin('el_unit AS e', 'e.code', '=', 'b.parent_code');
        $query->leftJoin('el_titles AS c', 'c.code', '=', 'profile.title_code');
        $query->leftJoin('el_position AS d', 'd.id', '=', 'profile.position_id');
        $query->where('profile.user_id', '>', 2);

        if ($search) {
            $query->where(function ($sub_query) use ($search) {
                $sub_query->orWhere('a.full_name', 'like', '%' . $search . '%');
                $sub_query->orWhere('profile.email', 'like', '%'. $search .'%');
                $sub_query->orWhere('profile.code', 'like', '%'. $search .'%');
            });
        }
        if ($request->area) {
            $query->leftJoin('el_area AS area', 'area.id', '=', 'b.area_id');
            $area = Area::find($request->area);
            $area_id = Area::getArrayChild($area->code);
            $query->where(function ($sub_query) use ($area, $area_id) {
                $sub_query->whereIn('area.id', $area_id);
                $sub_query->orWhere('area.id', '=', $area->id);
            });
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
            $query->where('profile.title_code', '=', $title->code);
        }

        $count = $query->count();
        $query->orderBy('a.'.$sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row) {
            $row->edit_url = route('module.backend.user_take_leave.edit', ['id' => $row->id]);
            $row->unit_url = route('module.backend.user.get_unit', ['user_id' => $row->user_id]);

            $absent = Absent::whereCode($row->absent_code)->first();
            $row->absent = $absent ? $absent->name : $row->absent_name;

            $row->date_take_leave = get_date($row->start_date). ($row->end_date ? ' => '.get_date($row->end_date) : '');
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function form(Request $request) {
        $model = ProfileTakeLeave::findOrFail($request->id);
        $start_date = get_date($model->start_date, 'Y-m-d');
        $end_date = get_date($model->end_date, 'Y-m-d');
        json_result([
            'model' => $model,
            'start_date' => $start_date,
            'end_date' => $end_date,
        ]);
    }

    public function form1($id = 0) {
        $model = ProfileTakeLeave::query()->firstOrNew(['id' => $id]);
        $page_title = $id ? $model->full_name : trans('labutton.add_new');
        $absents = Absent::whereStatus(1)->get();

        return view('user::backend.user_take_leave.form', [
            'model' => $model,
            'page_title' => $page_title,
            'absents' => $absents,
        ]);
    }

    public function save(Request $request) {
        $this->validateRequest([
            'user_id' => 'required',
            'start_date' => 'required',
        ], $request);

        $check_user_exstis = '';
        if (!$request->id) {
            $check_user_exstis = ProfileTakeLeave::where('user_id',$request->user_id)->exists();
        }

        if (!$request->absent_code && !$request->absent_name){
            json_message('Mời nhập lý do nghỉ phép', 'error');
        } else if ($request->end_date && $request->end_date < $request->start_date) {
            json_message('Ngày kết thúc phải lớn hơn ngày bắt đầu', 'error');
        } else if ($check_user_exstis) {
            json_message('Nhân viên nghỉ phép đã tồn tại', 'error');
        }

        $model = ProfileTakeLeave::firstOrNew(['id' => $request->id]);
        $model->fill($request->all());
        $model->absent_code = $request->absent_code ? $request->absent_code : null;
        $model->absent_name = $request->absent_name ? $request->absent_name : null;
        $model->full_name = Profile::fullname($request->user_id);
        $model->start_date = date_convert($model->start_date);
        $model->end_date = $model->end_date ? date_convert($model->end_date, '23:59:59') : null;

        if ($model->save()) {
            json_result([
                'status' => 'success',
                'message' => trans('laother.successful_save'),
            ]);
        }

        json_message(trans('laother.save_error'), 'error');
    }

    public function remove(Request $request) {
        $ids = $request->input('ids', null);
        foreach ($ids as $key => $id) {
            ProfileTakeLeave::destroy($id);
        }
        json_result([
            'status' => 'success',
            'message' => trans('laother.delete_success'),
        ]);
    }

    public function import(Request $request){
        $this->validateRequest([
            'import_file' => 'required|file'
        ], $request, [
            'import_file' => 'File import'
        ]);

        $file = $request->file('import_file');

        $import = new UserTakeLeave(\Auth::user());
        \Excel::import($import, $request->file('import_file'));

        if ($import->errors) {
            session()->put('errors', $import->errors);
            session()->save();
        } else {
            json_result([
                'status' => 'success',
                'message' => trans('laother.successful_save'),
                'redirect' => route('module.backend.user_take_leave')
            ]);
        }

        json_result([
            'status' => 'error',
            'message' => trans('laother.unable_upload'),
            'redirect' => route('module.backend.user_take_leave')
        ]);
    }

    public function export()
    {
        return (new ExportUserTakeLeave())->download('danh_sach_nhan_vien_nghi_phep_'. date('d_m_Y') .'.xlsx');
    }
}
