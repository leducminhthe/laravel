<?php

namespace Modules\Libraries\Http\Controllers;

use App\Models\Categories\Titles;
use App\Scopes\DraftScope;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Models\Profile;
use Modules\Libraries\Entities\Libraries;
use Modules\Libraries\Entities\LibrariesCategory;
use Illuminate\Support\Facades\Auth;
use Modules\Libraries\Entities\LibrariesObject;
use Modules\Libraries\Imports\ProfileImport;
use App\Models\LibrariesStatistic;
use App\Exports\ExportLibraries;
use Modules\Libraries\Entities\LibrariesFileZip;

class SalekitController extends Controller
{
    public function index()
    {
        $categories = LibrariesCategory::select(['id','name'])->where('type', '=', 6)->get();
        return view('libraries::backend.libraries.salekit.index', [
            'categories' => $categories,
        ]);
    }
    public function getData(Request $request)
    {
        $search = $request->input('search');
        $category_id = $request->input('category_id');
        $sort = $request->input('sort','id');
        $order = $request->input('order','desc');
        $offset = $request->input('offset',0);
        $limit = $request->input('limit',20);

        Libraries::addGlobalScope(new DraftScope());
        $query = Libraries::query();
        $query->select([
            'el_libraries.id',
            'el_libraries.name',
            'el_libraries.name_author',
            'el_libraries.updated_at',
            'el_libraries.updated_by',
            'el_libraries.status',
            'b.name AS category_name'
        ]);
        $query->leftJoin('el_libraries_category AS b', 'b.id', '=', 'el_libraries.category_id' );
        $query->where('el_libraries.type', '=', 6);
        if($search){
            $query->where(function($sub_query) use ($search){
                $sub_query->orWhere('el_libraries.name','like','%' . $search . '%');
                $sub_query->orWhere('el_libraries.name_author','like','%' . $search . '%');
            });
        }
        if ($category_id){
            $query->where('el_libraries.category_id', '=', $category_id);
        }

        $count = $query ->count();
        $query -> orderBy('el_libraries.'.$sort,$order);
        $query ->offset($offset);
        $query->limit($limit);

        $rows = $query ->get();
        foreach ($rows as $row) {
            $profile = Profile::select(['lastname','firstname'])->where('user_id', '=', $row->updated_by)->first();
            $row->user_name = $profile ? $profile->lastname . ' ' . $profile->firstname : '';
            $row->edit_url = route('module.libraries.salekit.edit', ['id' => $row->id]);
            $row->updated_at2 = get_date($row->updated_at, 'H:i d/m/Y');
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }
    public function form($id = 0) {
        $categories = LibrariesCategory::where('type','=',6)->get();

        if ($id) {
            $model = Libraries::find($id);
            $page_title = $model->name;
            $titles = Titles::select(['id','name','code'])->where('status', '=', 1)->get();

            $lib_zip = LibrariesFileZip::where('libraries_id', $id)->first();
            return view('libraries::backend.libraries.salekit.form', [
                'model' => $model,
                'page_title' => $page_title,
                'categories' => $categories,
                'titles' => $titles,
                'lib_zip' => $lib_zip,
            ]);
        }

        $model = new Libraries();
        $page_title = trans('labutton.add_new') ;

        return view('libraries::backend.libraries.salekit.form', [
            'model' => $model,
            'page_title' => $page_title,
            'categories' => $categories,
        ]);
    }
    public function save(Request $request) {
        $this->validateRequest([
            'name' => 'required',
            'attachment' => 'required',
            'status'=>'required',
            'image' => 'nullable|string',
            'category_id' => 'nullable|exists:el_libraries_category,id',
        ], $request, Libraries::getAttributeName());

        if(!isFilePdf($request->attachment)){
            json_message('File không đúng định dạng', 'error');
        }

        $get_parents_cate_id = LibrariesCategory::getTreeParentUnit($request->category_id);
        foreach($get_parents_cate_id as $get_parent_cate_id) {
            $cate_parent[] =  $get_parent_cate_id->name;
        }

        $model = Libraries::firstOrNew(['id' => $request->id,'type'=>$request->type]);
        $model->fill($request->all());
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
                'redirect' => route('module.libraries.salekit')
            ]);
        }

        json_message(trans('laother.save_error'), 'error');
    }
    public function remove(Request $request) {
        $ids = $request->input('ids', null);
        foreach ($ids as $id){
            $libraries = Libraries::find($id);
            $libraries->delete();
        }
        json_result([
            'status' => 'success',
            'message' => trans('laother.delete_success'),
        ]);
    }
    public function ajaxGetGroupName(Request $request){
        $this->validateRequest([
            'category_id' => 'required|exists:el_libraries_category,id',
        ], $request, [
            'category_id' => 'Danh mục salekit',
        ]);
        $category_id = $request->category_id;

        $category_id = LibrariesCategory::find($category_id);

        json_result($category_id);
    }

    public function saveObject($libraries_id, Request $request){
        $this->validateRequest([
            'unit_id' => 'required_if:status_title,true|required_if:object,1|exists:el_unit,id',
            'parent_id' => 'nullable|exists:el_unit,id',
            'title_id' => 'required_if:status_title,true|required_if:object,2',
        ], $request, LibrariesObject::getAttributeName());

        $title_id = explode(',', $request->input('title_id'));
        $unit_id = $request->input('unit_id');
        $parent_id = $request->input('parent_id');
        $status_unit = $request->input('status_unit');
        $status_title = $request->input('status_title');

        if ($parent_id && is_null($unit_id)){
            if (LibrariesObject::checkObjectUnit($libraries_id, $parent_id, 6)){

            }else{
                $model = new LibrariesObject();
                $model->libraries_id = $libraries_id;
                $model->unit_id = $parent_id;
                $model->type = 6;
                $model->status = $status_unit ? $status_unit : 3;
                $model->save();
            }
            json_result([
                'status' => 'success',
                'message' => trans('laother.success_add_unit'),
            ]);
        }
        if ($unit_id) {
            foreach ($unit_id as $item){
                $model = LibrariesObject::firstOrNew(['libraries_id' => $libraries_id, 'unit_id' => $item]);
                $model->libraries_id = $libraries_id;
                $model->unit_id = $item;
                $model->type = 6;
                $model->status = $status_unit ? $status_unit : 3;
                $model->save();
            }
            json_result([
                'status' => 'success',
                'message' => trans('laother.success_add_unit'),
            ]);
        }else{
            foreach ($title_id as $item){
                $model = LibrariesObject::firstOrNew(['libraries_id' => $libraries_id, 'title_id' => $item]);
                $model->libraries_id = $libraries_id;
                $model->title_id = $item;
                $model->type = 6;
                $model->status = $status_title ? $status_title : 3;
                $model->save();
            }
            json_result([
                'status' => 'success',
                'message' => trans('laother.success_add_title'),
            ]);
        }
    }

    public function getUserObject($libraries_id, Request $request){
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = LibrariesObject::query();
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
        $query->from('el_libraries_object AS a');
        $query->leftJoin('el_profile AS b', 'b.user_id', '=', 'a.user_id');
        $query->leftJoin('el_titles AS c', 'c.code', '=', 'b.title_code');
        $query->leftJoin('el_unit AS d', 'd.code', '=', 'b.unit_code');
        $query->leftJoin('el_unit AS e', 'e.code', '=', 'd.parent_code');
        $query->where('a.libraries_id', '=', $libraries_id);
        $query->where('a.type', '=', 6);
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

    public function getObject($libraries_id, Request $request){
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = LibrariesObject::query();
        $query->select([
            'a.*',
            'b.name AS title_name',
            'c.name AS unit_name',
            'd.name AS parent_name'
        ]);
        $query->from('el_libraries_object AS a');
        $query->leftJoin('el_titles AS b', 'b.id', '=', 'a.title_id');
        $query->leftJoin('el_unit AS c', 'c.id', '=', 'a.unit_id');
        $query->leftJoin('el_unit AS d', 'd.code', '=', 'c.parent_code');
        $query->where('a.libraries_id', '=', $libraries_id);
        $query->where('a.type', '=', 6);
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

    public function removeObject($libraries_id, Request $request){
        $this->validateRequest([
            'ids' => 'required',
        ], $request, [
            'ids' => trans('latraining.object'),
        ]);

        $item = $request->input('ids');
        LibrariesObject::destroy($item);
        json_result([
            'status' => 'success',
            'message' => trans('laother.delete_success'),
        ]);
    }

    public function importObject($libraries_id, Request $request){
        $this->validateRequest([
            'import_file' => 'required|file'
        ], $request, ['import_file' => 'File import']);

        $type_import = $request->type_import;
        $import = new ProfileImport($libraries_id, $type_import);
        \Excel::import($import, $request->file('import_file'));

        if ($import->errors) {
            session()->put('errors', $import->errors);
            session()->save();
        }
        json_result([
            'status' => 'success',
            'message' => 'Import ' . trans('laapi.success'),
            'redirect' => route('module.libraries.salekit.edit', ['id' => $libraries_id]),
        ]);
    }

    public function export()
    {
        return (new ExportLibraries(6))->download('danh_sach_salekit_'. date('d_m_Y') .'.xlsx');
    }

    public function ajaxIsopenPublish(Request $request) {
        $this->validateRequest([
            'ids' => 'required',
            'status' => 'required|in:0,1'
        ], $request, [
            'ids' => 'Sách điện tử',
        ]);

        $ids = $request->input('ids', null);
        $status = $request->input('status', 0);
        if(is_array($ids)) {
            foreach ($ids as $id) {
                $model = Libraries::findOrFail($id);
                $model->status = $status;
                $model->save();
            }
        } else {
            $model = Libraries::findOrFail($ids);
            $model->status = $status;
            $model->save();
        }

        json_result([
            'status' => 'success',
            'message' => trans('laother.successful_save'),
        ]);
    }
}
