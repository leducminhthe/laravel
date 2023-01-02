<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\SettingExperienceNavigate;
use App\Models\TimeExperienceNavigate;
use App\Models\SettingExperienceNavigateName;
use App\Models\ObjectExperienceNavigate;
use App\Models\ProfileView;
use App\Models\Categories\Unit;
use App\Models\Categories\Titles;
use App\Models\CountUserExperienceNavigate;
use App\Models\LanguagesType;

class SettingExperienceNavigateController extends Controller
{
    public function index() {
        $languagesType = LanguagesType::where('key','!=','vi')->get(['key','name']);
        return view('backend.setting_experience_navigate.index', [
            'languagesType' => $languagesType,
        ]);
    }

    public function getData(Request $request) {
        $sort = $request->get('sort', 'name');
        $order = $request->get('order', 'desc');
        $offset = $request->get('offset', 0);
        $limit = $request->get('limit', 20);
        $query = SettingExperienceNavigate::query();
        $query->select([
            '*',
        ]);
        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();
        foreach ($rows as $row) {
            $row->start_date = get_date($row->start_date, 'd/m/Y');
            $row->end_date = get_date($row->end_date, 'd/m/Y');
            $row->edit_url = route('backend.experience_navigate.edit', ['id' => $row->id]);
            $get_time_experience_navigate = TimeExperienceNavigate::where('experience_navigate_id', $row->id)->get();
            $time = [];
            foreach ($get_time_experience_navigate as $key => $item) {
                $time[] = $item->time_start . ' => ' . $item->time_end;
                $row->time = $time;
            }
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function form($id = null) {
        $titles = Titles::where('status', 1)->get(['id','name']);
        $page_title = $id ? trans('lasetting.edit') : trans('lasetting.add_new');
        $model = SettingExperienceNavigate::firstOrNew(['id' => $id]);
        $model->start_date = get_date($model->start_date, 'd/m/Y');
        $model->end_date = get_date($model->end_date, 'd/m/Y');
        $get_time_experience_navigate = TimeExperienceNavigate::where('experience_navigate_id', $id)->get();
        return view('backend.setting_experience_navigate.form', [
            'page_title' => $page_title,
            'model' => $model,
            'get_time_experience_navigate' => $get_time_experience_navigate,
            'titles' => $titles
        ]);
    }

    public function save(Request $request) {
        if ($request->date_count > $request->total_count) {
            json_message('Số hiển thị ngày phải nhỏ hơn số ngày hiển thị tối đa', 'error');
        }
        $experience_navigate = SettingExperienceNavigate::get(['id','start_date', 'end_date']);
        foreach ($experience_navigate as $key => $value) {
            if($value->id != $request->id && get_date($value->start_date, 'Y-m-d') >= get_date($request->start_date, 'Y-m-d') && get_date($value->start_date , 'Y-m-d') <= get_date($request->end_date, 'Y-m-d')) {
                json_message('Ngày lưu đã tồn tại', 'error');
            } elseif ($value->id != $request->id && get_date($value->end_date, 'Y-m-d') >= get_date($request->start_date, 'Y-m-d') && get_date($value->end_date , 'Y-m-d') <= get_date($request->end_date, 'Y-m-d')) {
                json_message('Ngày lưu đã tồn tại', 'error');
            }
        }
        if(get_date($request->start_date, 'Y-m-d') >= get_date($request->end_date, 'Y-m-d')) {
            json_message('Ngày không hợp lệ', 'error');
        };
        $save_experience_navigate = SettingExperienceNavigate::firstOrNew(['id' => $request->id]);
        $save_experience_navigate->start_date = get_date($request->start_date, 'Y-m-d');
        $save_experience_navigate->end_date = get_date($request->end_date, 'Y-m-d');
        $save_experience_navigate->total_count = $request->total_count;
        $save_experience_navigate->date_count = $request->date_count;
        $save_experience_navigate->save();
        if($save_experience_navigate->id) {
            $remove_count = CountUserExperienceNavigate::where('experience_navigate_id', $save_experience_navigate->id)->delete();
        }
        foreach ($request->time_1 as $key => $time_1) {
            $model = TimeExperienceNavigate::firstOrNew(['id' => $request->time_id[$key] ]);
            $model->experience_navigate_id = $save_experience_navigate->id;
            $model->time_start = $time_1;
            $model->time_end = $request->time_2[$key];
            $save = $model->save();
        }

        if ($save) {
            return response()->json([
                'status' => 'success',
                'message' => trans('laother.successful_save'),
                'redirect' => route('backend.experience_navigate.edit', ['id' => $save_experience_navigate->id])
            ]);
        }

        json_message(trans('laother.can_not_save'), 'error');
    }

    public function remove(Request $request) {
        $ids = $request->ids;
        foreach ($ids as $key => $id) {
            ObjectExperienceNavigate::where('experience_navigate_id', $id)->delete();
            TimeExperienceNavigate::where('experience_navigate_id', $id)->delete();
            SettingExperienceNavigate::where('id', $id)->delete();
        }
        json_message(trans('laother.delete_success'));
    }

    public function saveObject($id, Request $request){
        $this->validateRequest([
            'unit_id' => 'nullable|exists:el_unit,id',
            'title_id' => 'nullable',
        ], $request);

        $title_id = explode(',', $request->input('title_id'));
        $unit_id = $request->input('unit_id');

        if ($unit_id) {
            foreach ($unit_id as $item){
                $model = ObjectExperienceNavigate::firstOrNew(['experience_navigate_id' => $id, 'unit_id' => $item]);
                $model->experience_navigate_id = $id;
                $model->unit_id = $item;
                $model->save();
            }
            json_result([
                'status' => 'success',
                'message' => trans('laother.success_add_unit'),
            ]);
        }else{
            foreach ($title_id as $item){
                $model = ObjectExperienceNavigate::firstOrNew(['experience_navigate_id' => $id, 'title_id' => $item]);
                $model->experience_navigate_id = $id;
                $model->title_id = $item;
                $model->save();
            }
            json_result([
                'status' => 'success',
                'message' => trans('laother.success_add_title'),
            ]);
        }
    }

    public function getObject($id, Request $request){
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = ObjectExperienceNavigate::query();
        $query->select(['a.*', 'b.name AS title_name', 'c.name AS unit_name']);
        $query->from('el_object_experience_navigate AS a');
        $query->leftJoin('el_titles AS b', 'b.id', '=', 'a.title_id');
        $query->leftJoin('el_unit AS c', 'c.id', '=', 'a.unit_id');
        $query->where('a.experience_navigate_id', '=', $id);

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row){
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

    public function removeObject($id, Request $request){
        $this->validateRequest([
            'ids' => 'required',
        ], $request, [
            'ids' => trans('latraining.object'),
        ]);

        $item = $request->input('ids');
        ObjectExperienceNavigate::destroy($item);
        json_result([
            'status' => 'success',
            'message' => trans('laother.delete_success'),
        ]);
    }

    // TÊN ĐIỀU CHỈNH
    public function formName(Request $request) {
        $model = SettingExperienceNavigateName::find($request->id);
        $name = (array) json_decode($model->name);
        $languagesType = LanguagesType::pluck('key')->toArray();
        json_result([
            'model' => $model,
            'name' => $name,
            'languagesType' => $languagesType,
        ]);
    }

    public function getDataName(Request $request) {
        $sort = $request->get('sort', 'name');
        $order = $request->get('order', 'desc');
        $offset = $request->get('offset', 0);
        $limit = $request->get('limit', 20);
        $query = SettingExperienceNavigateName::query();
        $query->select([
            '*',
        ]);
        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();
        foreach($rows as $row) {
            $getName = json_decode($row->name);
            $row->name = $getName->{\App::getLocale()} ? $getName->{\App::getLocale()} : $getName->vi;
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function saveName(Request $request) {
        $this->validateRequest([
            'name' => 'required_if:type,1|',
            'status' => 'required',
            'image' => 'required_if:type,2|'
        ], $request, [
            'name' => trans('latraining.navigating_name'),
            'status' => trans("latraining.status"),
            'image' => trans("latraining.picture")
        ]);
        $languagesType = LanguagesType::pluck('key')->toArray();
        $names = $request->name;
        $combine = array_combine($languagesType, $names);
        $jsonCombine = json_encode($combine);

        $save_name = SettingExperienceNavigateName::firstOrNew(['id' => $request->id]);
        $save_name->name = $jsonCombine;
        $save_name->status = $request->status;
        $save_name->image = $request->image;
        $save_name->type = $request->type;
        $save_name->save();

        if ($save_name) {
            return response()->json([
                'status' => 'success',
                'message' => trans('laother.successful_save'),
            ]);
        }

        json_message(trans('laother.can_not_save'), 'error');
    }

    public function ajaxIsopenPublish(Request $request) {
        $this->validateRequest([
            'ids' => 'required',
            'status' => 'required|in:0,1'
        ], $request, [
            'ids' => trans('latraining.navigating_name'),
            'status' => trans("latraining.status")
        ]);

        $ids = $request->input('ids', null);
        $status = $request->input('status', 0);
        if(is_array($ids)) {
            foreach ($ids as $id) {
                $model = SettingExperienceNavigateName::findOrFail($id);
                $model->status = $status;
                $model->save();
            }
        } else {
            $model = SettingExperienceNavigateName::findOrFail($ids);
            $model->status = $status;
            $model->save();
        }

        json_result([
            'status' => 'success',
            'message' => trans('laother.successful_save'),
        ]);
    }
}
