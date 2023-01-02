<?php

namespace App\Http\Controllers\Backend;

use App\Scopes\DraftScope;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Categories\TitleRank;
use App\Models\Profile;
use App\Models\Categories\Titles;

class TitleRankController extends Controller
{
    public function index() {
        return view('backend.category.title_rank.index');
    }

    public function getData(Request $request) {
        $search = $request->input('search');

        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);
        
        TitleRank::addGlobalScope(new DraftScope());
        $query = TitleRank::query();
        $query->select('*');

        if ($search) {
            $query->where(function ($subquery) use ($search){
                $subquery->orWhere('el_title_rank.name', 'like', '%'. $search .'%');
                $subquery->orWhere('el_title_rank.code', 'like', '%'. $search .'%');
            });
        }

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();

        foreach ($rows as $row) {
            $created_by = $row->created_by ? $row->created_by : 2;
            $updated_by = $row->updated_by ? $row->updated_by : 2;
            $row->info = route('backend.get_user_info',['created' => $created_by, 'updated' => $updated_by]);
        }

        return response()->json([
            'total' => $count,
            'rows' => $rows
        ]);
    }

    public function form(Request $request) {
        $model = TitleRank::select(['id','status','code','name'])->where('id', $request->id)->first();
        json_result($model);
    }

    public function save(Request $request) {
        $this->validateRequest([
            'code' => 'required|unique:el_title_rank,code,'. $request->id,
            'name' => 'required',
            'status' => 'required|in:0,1',
        ], $request, TitleRank::getAttributeName());

        $model = TitleRank::firstOrNew(['id' => $request->id]);
        $model->code = $request->code;
        $model->name = $request->name;
        $model->status = $request->status;
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

        json_message(trans('laother.can_not_save'), 'error');
    }

    public function remove(Request $request) {
        $ids = $request->input('ids', null);
        foreach ($ids as $id) {
            $checkTitle = Titles::select(['id','name','code'])->where('group','=',$id)->exists();
            if ($checkTitle) {
                json_result([
                    'status' => 'error',
                    'message' => 'Không thể xóa vì có liên quan đến chức danh',
                    'redirect' => route('backend.category.title_rank')
                ]);
            } else {
                TitleRank::where('id','=',$id)->delete();
            }
        }
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
                $model = TitleRank::findOrFail($id);
                $model->status = $status;
                $model->save();
            }
        } else {
            $model = TitleRank::findOrFail($ids);
            $model->status = $status;
            $model->save();
        }

        json_result([
            'status' => 'success',
            'message' => trans('laother.successful_save'),
        ]);
    }
}
