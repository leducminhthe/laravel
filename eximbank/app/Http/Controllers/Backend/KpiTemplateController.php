<?php

namespace App\Http\Controllers\Backend;

use App\Models\KpiTemplate;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\ImageManagerStatic;
use App\Models\ProfileView;
use Illuminate\Support\Str;
use App\Models\TotalTimeUserLearnInYear;
use App\Models\Categories\Titles;
use Modules\TargetManager\Entities\TargetManager;
use Modules\TargetManager\Entities\TargetManagerParent;
use Modules\TargetManager\Entities\TargetManagerGroup;

class KpiTemplateController extends Controller
{
    public function index() {
        return view('backend.kpi_template.index');
    }

    public function save(Request $request) {
        $this->validateRequest([
            'image' => 'required',
        ], $request, [
            'image' => 'Hình ảnh'
        ]);

        $model = KpiTemplate::firstOrNew(['id' => $request->id]);
        $model->fill($request->all());
        $model->image = upload_image([860, 1000],$request->image);

        if ($request->id) {
            $model->created_by = $model->created_by;
        } else {
            $model->created_by =profile()->user_id;
        }
        $model->updated_by = profile()->user_id;

        $save = $model->save();
        if($save)
            json_message(trans('laother.successful_save'), 'success');
        else
            json_message(trans('laother.can_not_save'), 'error');
    }

    public function form(Request $request) {
        $model = KpiTemplate::select()->where('id', $request->id)->first();
        $path_image = image_file($model->image);
        json_result([
            'model' => $model,
            'image' => $path_image
        ]);
    }

    public function getData(Request $request) {
        $sort = $request->get('sort', 'name');
        $order = $request->get('order', 'desc');
        $offset = $request->get('offset', 0);
        $limit = $request->get('limit', 20);

        $query = KpiTemplate::query();

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();

        foreach ($rows as $row) {
            $created_by = $row->created_by ? $row->created_by : 2;
            $updated_by = $row->updated_by ? $row->updated_by : 2;
            $row->info = route('backend.get_user_info',['created' => $created_by, 'updated' => $updated_by]);
            $row->image_url = image_file($row->image);
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function remove(Request $request) {
        $ids = $request->ids;
        KpiTemplate::destroy($ids);
        json_message(trans('laother.delete_success'));
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
                $model = KpiTemplate::findOrFail($id);
                $model->status = $status;
                $model->save();
            }
        } else {
            $model = KpiTemplate::findOrFail($ids);
            $model->status = $status;
            $model->save();
        }

        json_result([
            'status' => 'success',
            'message' => trans('laother.successful_save'),
        ]);
    }

    public function showKpi(Request $request)
    {
        $year = date('Y');
        $totalTimeUser = TotalTimeUserLearnInYear::where('user_id', profile()->user_id)->where('year', $year)->first(['time_second']);
        $hours = gmdate("H", $totalTimeUser->time_second);
        
        $profile = profile();
        $kpiTemplate = KpiTemplate::where('status', 1)->first();

        $targetManagerParent = TargetManagerParent::where('year', $year)->first(['id']);

        $targetManagerGroup = TargetManagerGroup::query()
        ->where(function($sub) use ($profile) {
            $sub->orWhere('user_id', $profile->user_id);
            $sub->orWhere('title_id', $profile->title_id);
        })
        ->groupBy('target_manager_id')
        ->pluck('target_manager_id')
        ->toArray();

        $kpiTitle = TargetManager::whereIn('id', $targetManagerGroup)->sum('num_hour_student');

        if(empty($kpiTemplate)) {
            return null;
        }
        
        $storage = \Storage::disk('upload');
        $path = $storage->path($kpiTemplate->image);
        $temp = str_replace($kpiTemplate->image, str_replace('.', '_kpi.', $kpiTemplate->image), $path);

        $fullname = $profile->full_name;
        $image = ImageManagerStatic::make($path)->resize(860, 1000);

        $gender = $profile->gender == 1 ? 'Anh' : 'Chị';

        $image->text($gender, 430, 355, function ($font){
            $font->file(public_path('fonts/timesbd.ttf'));
            $font->size(25);
            $font->color('#fffff');
            $font->align('center');
        });
        
        $image->text($fullname, 430, 410, function ($font){
            $font->file(public_path('fonts/timesbd.ttf'));
            $font->size(40);
            $font->color('#ffd633');
            $font->align('center');
        });

        $image->text($hours, 390, 795, function ($font){
            $font->file(public_path('fonts/timesbd.ttf'));
            $font->size(20);
            $font->color('#fffff');
        });

        if(!empty($kpiTitle) && !empty($kpiTitle)) {
            $calculateKpi = (int) $hours / ($kpiTitle > 0 ? $kpiTitle : 1) * 100;
            $totalKpi = $calculateKpi > 0 ?  round($calculateKpi, 2) : '__' ;
        } else {
            $totalKpi = '__';
        }        

        $image->text($totalKpi, 565, 795, function ($font){
            $font->file(public_path('fonts/timesbd.ttf'));
            $font->size(20);
            $font->color('#fffff');
        });

        $image->save($temp);

        $headers = array(
            'Content-Type: application/pdf',
        );
        ob_end_clean();
        return response()->download($temp, 'kpi_'.Str::slug($fullname, '_').'.png', $headers);
    }
}
