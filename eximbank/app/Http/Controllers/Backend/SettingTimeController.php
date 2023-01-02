<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\SettingTimeModel;
use App\Models\SettingTimeObjectModel;
use App\Models\SettingTimeValueLanguages;
use App\Models\Categories\Unit;
use App\Models\LanguagesType;

class SettingTimeController extends Controller
{
    public function index() {
        return view('backend.setting_time.index');
    }

    public function getData(Request $request) {
        $localeLanguage = \App::getLocale();

        $sort = $request->get('sort', 'name');
        $order = $request->get('order', 'desc');
        $offset = $request->get('offset', 0);
        $limit = $request->get('limit', 20);
        $query = SettingTimeObjectModel::query();
        $query->select([
            'el_setting_time_object.*',
        ]);

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();
        foreach ($rows as $row) {
            $row->edit_url = route('backend.setting_time.edit', ['id' => $row->id]);
            if ($row->object != 'All') {
                $objects = json_decode($row->object);
                foreach ($objects as $key => $object) {
                    $unit = Unit::select('name')->where('id',$object)->first();
                    $unit_name[] = $unit->name;
                }
                $row->object = $unit_name;
            }
            $get_times = SettingTimeModel::where('object',$row->id)->get();
            $time = [] ;
            $value = [] ;
            foreach ($get_times as $key => $get_time) {
                $getValue = SettingTimeValueLanguages::where('setting_time_id', $get_time->id)->where('languages', $localeLanguage)->first(['value']);
                $time[] = $get_time->start_time . ' => ' . $get_time->end_time;
                $row->time = $time;
                $value[] = $getValue->value;
                $row->value = $value;
            }
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function form($id = null) {
        // $localeLanguage = \App::getLocale();
        // dump(\App::getLocale());
        $languagesType = LanguagesType::where('key','!=','vi')->get(['key','name']);
        $settingTimeObject = SettingTimeObjectModel::firstOrNew(['id' => $id]);
        $unit = Unit::select(['id','name','code'])->where('level', '=', 1)->get();
        $page_title = $id ? trans('lasetting.edit') : trans('lasetting.add_new');
        !empty($settingTimeObject->object) && $id ? $get_object = json_decode($settingTimeObject->object) : $get_object = [];
        !empty($settingTimeObject->languages) && $id ? $get_languages = json_decode($settingTimeObject->languages) : $get_languages = [];
        $get_setting_times = SettingTimeModel::where('object', $settingTimeObject->id)->get();
        foreach ($get_setting_times as $key => $setting_time) {
            $value = SettingTimeValueLanguages::where('setting_time_id', $setting_time->id)->get(['value','languages']);
            $setting_time->value = $value;
        }
        return view('backend.setting_time.form', [
            'page_title' => $page_title,
            'unit' => $unit,
            'get_setting_times' => $get_setting_times,
            'get_object' => $get_object,
            'get_languages' => $get_languages,
            'languagesType' => $languagesType,
            'settingTimeObject' => $settingTimeObject
        ]);
    }

    public function save(Request $request) {
        $languagesType = LanguagesType::pluck('key')->toArray();

        $model = SettingTimeObjectModel::firstOrNew(['id' => $request->id]);
        if ($request->object) {
            $model->object = json_encode($request->object);
        } else {
            $model->object = 'All';
        }
        $model->languages = json_encode($request->key_language);
        $save_object = $model->save();
        
        foreach ($request->time_1 as $key => $time_1) {
            $id = $key + 1;
            $setting = SettingTimeModel::firstOrNew(['session' => $id, 'object' => $model->id]);
            $setting->start_time = $time_1;
            $setting->end_time = $request->time_2[$key];
            $setting->i_text = !empty($request->{'i_text_'.$id}) ? 1 : 0;
            $setting->b_text = !empty($request->{'b_text_'.$id}) ? 1 : 0;
            $setting->color_text = $request->color_text[$key];
            $setting->object = $model->id;
            $setting->session = $id;
            $save_setting = $setting->save();
            foreach ($languagesType as $key) {
                if($request->{'value_'. $id .'_'. $key}) {
                    $saveValue = SettingTimeValueLanguages::firstOrNew(['setting_time_id' => $setting->id, 'languages' => $key]);
                    $saveValue->setting_time_id = $setting->id;
                    $saveValue->languages = $key;
                    $saveValue->value = $request->{'value_'. $id .'_'. $key};
                    $saveValue->save();
                } else {
                    continue;
                }
            }
        }

        if ($save_setting) {
            return response()->json([
                'status' => 'success',
                'message' => trans('laother.successful_save'),
                'redirect' => route('backend.setting_time')
            ]);
        }

        json_message(trans('laother.can_not_save'), 'error');
    }

    public function remove(Request $request) {
        $ids = $request->post('ids', []);
        foreach ($ids as $key => $id) {
            SettingTimeObjectModel::where('id',$id)->delete();
            SettingTimeModel::where('object',$id)->delete();
        }
        json_message(trans('laother.delete_success'));
    }
}
