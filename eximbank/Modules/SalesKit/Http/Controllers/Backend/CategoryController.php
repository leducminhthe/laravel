<?php

namespace Modules\SalesKit\Http\Controllers\Backend;

use App\Scopes\DraftScope;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\SalesKit\Entities\SalesKit;
use Modules\SalesKit\Entities\SalesKitCategory;

class CategoryController extends Controller
{
    public function index()
    {
        return view('saleskit::backend.category.index',[
        ]);
    }

    public function getData(Request $request)
    {
        $search = $request -> input('search');
        $sort = $request ->input('sort','id');
        $order = $request ->input('order','desc');
        $offset =$request ->input('offset',0);
        $limit = $request ->input('limit',20);

        SalesKitCategory::addGlobalScope(new DraftScope());
        $query = SalesKitCategory::query();

        if($search){
            $query->where(function($sub_query) use ($search){
                $sub_query->orWhere('name','like','%' . $search . '%');
            });
        }

        $count = $query ->count();
        $query->orderBy($sort, $order);
        $query ->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        // dd($rows);
        foreach ($rows as $row) {
            $row->edit_url = route('module.saleskit.category.edit', ['id' => $row->id]);
            $row->saleskit = route('module.saleskit', ['cate_id' => $row->id]);
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function form(Request $request) {
        $model = SalesKitCategory::select(['id','parent_id','name','bg_mobile'])->where('id', $request->id)->first();
        $categories = SalesKitCategory::where('id', '!=', $model->id)->get();
        json_result([
            'model' => $model,
            'categories' => $categories,
        ]);
    }
    public function save(Request $request) {
        $this->validateRequest([
            'name' => 'required',
        ], $request, SalesKitCategory::getAttributeName());

        $parent = SalesKitCategory::find($request->parent_id);

        $model = SalesKitCategory::firstOrNew(['id' => $request->id]);
        $model->fill($request->all());
        $model->parent_id = $parent ? $parent->id : null;

        if ($parent){
            if($parent->name==$request->name){
                json_message('Tên không được trùng', 'error');
            }
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
        $check = SalesKit::whereIn('category_id', $ids)->exists();
        if ($check) {
            json_message('Danh mục được sử dụng. Không thể xóa', 'error');
        }
        SaleskitCategory::destroy($ids);
        json_result([
            'status' => 'success',
            'message' => trans('laother.delete_success'),
        ]);
    }
}
