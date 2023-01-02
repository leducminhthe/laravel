<?php

namespace Modules\Promotion\Http\Controllers;

use App\Scopes\DraftScope;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Promotion\Entities\PromotionGroup;
use Modules\Promotion\Entities\Promotion;

class PromotionGroupController extends Controller
{

    public function index()
    {
        return view('promotion::backend.promotion_group.index',[
        ]);
    }

    public function getData(Request $request) {
        $search = $request->input('search');

        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);
        PromotionGroup::addGlobalScope(new DraftScope());
        $query = PromotionGroup::query()
            ->select(['*'])
            ->from('el_promotion_group');

        if ($search) {
            $query->where('name', 'like', '%'. $search .'%');
        }

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach($rows as $row){
            $row->created_at2 = get_date($row->created_at, 'H:i d/m/Y');
            $row->updated_at2 = get_date($row->updated_at, 'H:i d/m/Y');
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function save(Request $request)
    {
        $this->validateRequest([
            'code' => 'required',
            'name' => 'required',
            'status' => 'in:0,1',
            // 'icon' => 'required',
        ],$request,PromotionGroup::getAttributeName());

        $model = PromotionGroup::firstOrNew(['id' => $request->id]);
        $model->fill($request->all());

        $sizes = config('image.sizes.medium');
        // $model->icon = upload_image($sizes, $request->icon);

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

    public function form(Request $request) {
        $model = PromotionGroup::select(['id','icon','status','code','name'])->where('id', $request->id)->first();
        $path_image = image_file($model->icon);
        json_result([
            'model' => $model,
            'image' => $path_image
        ]);
    }

    public function remove(Request $request) {
        $ids = $request->input('ids', null);
        $check = Promotion::whereIn('promotion_group', $ids)->first(['name']);
        if ($check) {
            json_message('Không thể xóa. Nhóm quà tặng đang được quà tặng: '. $check->name .' sử dụng', 'error');
        }
        PromotionGroup::destroy($ids);
        json_result([
            'status' => 'success',
            'message' => trans('laother.delete_success'),
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
                $model = PromotionGroup::findOrFail($id);
                $model->status = $status;
                $model->save();
            }
        } else {
            $model = PromotionGroup::findOrFail($ids);
            $model->status = $status;
            $model->save();
        }

        json_result([
            'status' => 'success',
            'message' => trans('laother.successful_save')
        ]);
    }
}
