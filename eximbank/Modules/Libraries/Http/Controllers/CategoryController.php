<?php

namespace Modules\Libraries\Http\Controllers;

use App\Scopes\DraftScope;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Modules\Libraries\Entities\Libraries;
use Modules\Libraries\Entities\LibrariesCategory;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = LibrariesCategory::select(['id','name'])->get();
        return view('libraries::backend.libraries.category.index',[
            'categories' => $categories,
        ]);
    }

    public function getData(Request $request)
    {
        $search = $request->input('search');
        $category_type = $request->input('category_type');
        $sort = $request->input('sort','id');
        $order = $request->input('order','desc');
        $offset = $request->input('offset',0);
        $limit = $request->input('limit',20);

        LibrariesCategory::addGlobalScope(new DraftScope());
        $query = LibrariesCategory::query();

        if($search){
            $query->where(function($sub_query) use ($search){
                $sub_query->orWhere('name','like','%' . $search . '%');
            });
        }

        if($category_type){
            $query->Where('type',$category_type);
        }

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query ->get();
        foreach ($rows as $row) {
            $row->edit_url = route('module.libraries.category.edit', ['id' => $row->id]);
            if (!empty($row->parent_id)) {
                $get_parent_id = LibrariesCategory::where('id','=', $row->parent_id)->first();
                $row->parent_name = $get_parent_id->name;
            } else {
                $row->parent_name = '-';
            }
            $row->parent_id = (int) $row->parent_id;
            switch ($row->type) {
                case "4":
                    $row->type = 'Danh mục video';
                    break;
                case "2":
                    $row->type = 'Danh mục ebook';
                    break;
                case "3":
                    $row->type = 'Danh mục tài liệu';
                    break;
                case "5":
                    $row->type = 'Danh mục sách nói';
                    break;
                case "6":
                    $row->type = 'Danh mục Salekit';
                    break;
                default:
                    $row->type = 'Danh mục sách';
            }
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function form(Request $request) {
        $model = LibrariesCategory::select(['id','parent_id','type','name','bg_mobile'])->where('id', $request->id)->first();
        $categories = LibrariesCategory::where('type',$model->type)->where('id', '!=', $model->id)->whereNull('parent_id')->get();
        json_result([
            'model' => $model,
            'categories' => $categories,
        ]);
    }
    public function save(Request $request) {
        $this->validateRequest([
            'name' => 'required',
            'type' => 'required|in:1,2,3,4,5,6',
        ], $request, LibrariesCategory::getAttributeName());

        $parent = LibrariesCategory::find($request->parent_id);

        $model = LibrariesCategory::firstOrNew(['id' => $request->id]);
        $model->fill($request->all());
        $model->parent_id = $parent ? $parent->id : null;

        if ($parent){
            if($parent->name==$request->name){
                json_message('Tên không được trùng', 'error');
            }
            $level = (int)$parent->level+1;
            $model->level = $level;
        } else {
            $model->level = 0;
        }
        if ($request->id) {
            $model->created_by = $model->created_by;
        }
        $model->updated_by = profile()->user_id;
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
        $check = Libraries::whereIn('category_id', $ids)->exists();
        if ($check) {
            json_message('Danh mục được sử dụng trong thư viện. Không thể xóa', 'error');
        }
        LibrariesCategory::destroy($ids);
        json_result([
            'status' => 'success',
            'message' => trans('laother.delete_success'),
        ]);
    }

    public function ajaxLoadParent(Request $request) {
        $type = $request->type;
        $id = $request->id ? $request->id : 0;
        $get_parents = LibrariesCategory::where('type',$type)->where('id', '!=', $id)->get();
        json_result($get_parents);
    }
}
