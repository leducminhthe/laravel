<?php

namespace Modules\SalesKit\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Categories\Titles;
use App\Models\Profile;
use App\Scopes\DraftScope;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Modules\SalesKit\Entities\SalesKit;
use Modules\SalesKit\Entities\SalesKitCategory;
use Modules\SalesKit\Entities\SalesKitObject;
use Modules\SalesKit\Imports\ProfileImport;

class SalesKitController extends Controller
{
    public function index($category_id)
    {
        $categories = SalesKitCategory::find($category_id);
        return view('saleskit::backend.salekit.index', [
            'categories' => $categories,
        ]);
    }
    public function getData($category_id, Request $request)
    {
        $search = $request->input('search');
        $sort = $request->input('sort','id');
        $order = $request->input('order','desc');
        $offset = $request->input('offset',0);
        $limit = $request->input('limit',20);

        SalesKit::addGlobalScope(new DraftScope());
        $query = SalesKit::query();
        $query->select([
            'el_sales_kit.id',
            'el_sales_kit.name',
            'el_sales_kit.name_author',
            'el_sales_kit.updated_at',
            'el_sales_kit.updated_by',
            'el_sales_kit.status',
        ]);
        $query->where('el_sales_kit.category_id', $category_id);

        if($search){
            $query->where(function($sub_query) use ($search){
                $sub_query->orWhere('el_sales_kit.name','like','%' . $search . '%');
                $sub_query->orWhere('el_sales_kit.name_author','like','%' . $search . '%');
            });
        }

        $count = $query->count();
        $query->orderBy('el_sales_kit.'.$sort,$order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query ->get();
        foreach ($rows as $row) {
            $profile = Profile::select(['lastname','firstname'])->where('user_id', '=', $row->updated_by)->first();
            $row->user_name = $profile ? $profile->lastname . ' ' . $profile->firstname : '';
            $row->edit_url = route('module.saleskit.edit', ['cate_id' => $category_id, 'id' => $row->id]);
            $row->updated_at2 = get_date($row->updated_at, 'H:i d/m/Y');
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }
    public function form($category_id, $id = 0) {
        $categories = SalesKitCategory::find($category_id);

        if ($id) {
            $model = SalesKit::find($id);
            $page_title = $model->name;
            $titles = Titles::select(['id','name','code'])->where('status', '=', 1)->get();

            return view('saleskit::backend.salekit.form', [
                'model' => $model,
                'page_title' => $page_title,
                'categories' => $categories,
                'titles' => $titles,
            ]);
        }

        $model = new SalesKit();
        $page_title = trans('labutton.add_new') ;

        return view('saleskit::backend.salekit.form', [
            'model' => $model,
            'page_title' => $page_title,
            'categories' => $categories,
        ]);
    }
    public function save($category_id, Request $request) {
        $this->validateRequest([
            'name' => 'required',
            'attachment' => 'required',
            'status'=>'required',
            'image' => 'nullable|string',
        ], $request, SalesKit::getAttributeName());

        if(!isFilePdf($request->attachment)){
            json_message('File không đúng định dạng', 'error');
        }

        $get_parents_cate_id = SalesKitCategory::getTreeParentUnit($category_id);
        foreach($get_parents_cate_id as $get_parent_cate_id) {
            $cate_parent[] =  $get_parent_cate_id->name;
        }

        $model = SalesKit::firstOrNew(['id' => $request->id]);
        $model->fill($request->all());

        $model->category_id = $category_id;
        $model->attachment = path_upload($request->attachment);
        $model->name_author = $request->name_author ?? '';

        if ($request->image) {
            $sizes = config('image.sizes.library');
            $model->image = upload_image($sizes, $request->image);
        }

        $model->created_by = profile()->user_id;
        $model->updated_by = profile()->user_id;
        $model->category_parent = implode(',',$cate_parent);

        if ($model->save()) {

            json_result([
                'status' => 'success',
                'message' => trans('laother.successful_save'),
                'redirect' => route('module.saleskit', ['cate_id' => $category_id])
            ]);
        }

        json_message(trans('laother.save_error'), 'error');
    }
    public function remove($category_id, Request $request) {
        $ids = $request->input('ids', null);
        foreach ($ids as $id){
            $saleskit = SalesKit::find($id);
            $saleskit->delete();
        }
        json_result([
            'status' => 'success',
            'message' => trans('laother.delete_success'),
        ]);
    }

    public function saveObject($category_id, $saleskit_id, Request $request){
        $this->validateRequest([
            'unit_id' => 'required_if:status_title,true|required_if:object,1|exists:el_unit,id',
            'parent_id' => 'nullable|exists:el_unit,id',
            'title_id' => 'required_if:status_title,true|required_if:object,2',
        ], $request, SalesKitObject::getAttributeName());

        $title_id = explode(',', $request->input('title_id'));
        $unit_id = $request->input('unit_id');
        $parent_id = $request->input('parent_id');
        $status_unit = $request->input('status_unit');
        $status_title = $request->input('status_title');

        if ($parent_id && is_null($unit_id)){
            $model = SalesKitObject::firstOrNew(['saleskit_id' => $saleskit_id, 'unit_id' => $parent_id]);
            $model->saleskit_id = $saleskit_id;
            $model->unit_id = $parent_id;
            $model->status = $status_unit ? $status_unit : 3;
            $model->save();

            json_result([
                'status' => 'success',
                'message' => trans('laother.success_add_unit'),
            ]);
        }
        if ($unit_id) {
            foreach ($unit_id as $item){
                $model = SalesKitObject::firstOrNew(['saleskit_id' => $saleskit_id, 'unit_id' => $item]);
                $model->saleskit_id = $saleskit_id;
                $model->unit_id = $item;
                $model->status = $status_unit ? $status_unit : 3;
                $model->save();
            }
            json_result([
                'status' => 'success',
                'message' => trans('laother.success_add_unit'),
            ]);
        }else{
            foreach ($title_id as $item){
                $model = SalesKitObject::firstOrNew(['saleskit_id' => $saleskit_id, 'title_id' => $item]);
                $model->saleskit_id = $saleskit_id;
                $model->title_id = $item;
                $model->status = $status_title ? $status_title : 3;
                $model->save();
            }
            json_result([
                'status' => 'success',
                'message' => trans('laother.success_add_title'),
            ]);
        }
    }

    public function getUserObject($category_id, $saleskit_id, Request $request){
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = SalesKitObject::query();
        $query->select([
            'a.*',
            'b.code AS profile_code',
            'b.lastname',
            'b.firstname',
            'b.email',
            'c.name AS title_name',
            'd.name AS unit_name',
            'e.name AS parent_name'
        ]);
        $query->from('el_sales_kit_object AS a');
        $query->leftJoin('el_profile AS b', 'b.user_id', '=', 'a.user_id');
        $query->leftJoin('el_titles AS c', 'c.code', '=', 'b.title_code');
        $query->leftJoin('el_unit AS d', 'd.code', '=', 'b.unit_code');
        $query->leftJoin('el_unit AS e', 'e.code', '=', 'd.parent_code');
        $query->where('a.saleskit_id', '=', $saleskit_id);
        $query->where('a.title_id', '=', null);
        $query->where('a.unit_id', '=', null);

        $count = $query->count();
        $query->orderBy('a.'.$sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row){
            $row->profile_name = $row->lastname . ' ' . $row->firstname;
            if (empty($row->parent_name)){
                $row->parent = $row->unit_name;
                $row->unit = '';
            }else{
                $row->parent = $row->parent_name;
                $row->unit = $row->unit_name;
            }
            if ($row->status == 1){
                $row->status = 'Xem';
            }elseif ($row->status == 2){
                $row->status = 'Tải về';
            }else{
                $row->status = 'Xem và Tải về';
            }
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function getObject($category_id, $saleskit_id, Request $request){
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = SalesKitObject::query();
        $query->select([
            'a.*',
            'b.name AS title_name',
            'c.name AS unit_name',
            'd.name AS parent_name'
        ]);
        $query->from('el_sales_kit_object AS a');
        $query->leftJoin('el_titles AS b', 'b.id', '=', 'a.title_id');
        $query->leftJoin('el_unit AS c', 'c.id', '=', 'a.unit_id');
        $query->leftJoin('el_unit AS d', 'd.code', '=', 'c.parent_code');
        $query->where('a.saleskit_id', '=', $saleskit_id);
        $query->where('a.user_id', '=', null);

        $count = $query->count();
        $query->orderBy('a.'.$sort, $order);
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
            if ($row->status == 1){
                $row->status = 'Xem';
            }elseif ($row->status == 2){
                $row->status = 'Tải về';
            }else{
                $row->status = 'Xem và Tải về';
            }
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function removeObject($category_id, $saleskit_id, Request $request){
        $this->validateRequest([
            'ids' => 'required',
        ], $request, [
            'ids' => trans('latraining.object'),
        ]);

        $item = $request->input('ids');
        SalesKitObject::destroy($item);

        json_result([
            'status' => 'success',
            'message' => trans('laother.delete_success'),
        ]);
    }

    public function importObject($category_id, $saleskit_id, Request $request){
        $this->validateRequest([
            'import_file' => 'required|file'
        ], $request, ['import_file' => 'File import']);

        $import = new ProfileImport($saleskit_id);
        \Excel::import($import, $request->file('import_file'));

        if ($import->errors) {
            session()->put('errors', $import->errors);
            session()->save();
        }
        json_result([
            'status' => 'success',
            'message' => 'Import ' . trans('laapi.success'),
            'redirect' => route('module.saleskit.edit', ['id' => $saleskit_id]),
        ]);
    }

    public function ajaxIsopenPublish($category_id, Request $request) {
        $this->validateRequest([
            'ids' => 'required',
            'status' => 'required|in:0,1'
        ], $request, [
            'ids' => 'Sales kit',
        ]);

        $ids = $request->input('ids', null);
        $status = $request->input('status', 0);

        if(is_array($ids)) {
            foreach ($ids as $id) {
                $model = SalesKit::findOrFail($id);
                $model->status = $status;
                $model->save();
            }
        } else {
            $model = SalesKit::findOrFail($ids);
            $model->status = $status;
            $model->save();
        }

        json_result([
            'status' => 'success',
            'message' => trans('laother.successful_save'),
        ]);
    }
}
