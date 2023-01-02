<?php

namespace Modules\Coaching\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Scopes\DraftScope;
use Illuminate\Http\Request;
use Modules\Coaching\Entities\CoachingGroup;

class CoachingGroupController extends Controller
{
    public function index()
    {
        return view('coaching::backend.coaching_group.index');
    }
    public function getData(Request $request)
    {
        $search = $request -> input('search');
        $sort = $request ->input('sort','id');
        $order = $request ->input('order','desc');
        $offset =$request ->input('offset',0);
        $limit = $request ->input('limit',20);

        CoachingGroup::addGlobalScope(new DraftScope());
        $query = CoachingGroup::query();

        if($search){
            $query->where(function($sub_query) use ($search){
                $sub_query->orWhere('code','like','%' . $search . '%');
                $sub_query->orWhere('name','like','%' . $search . '%');
            });
        }

        $count = $query->count();
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query ->get();
        foreach ($rows as $row) {
            $created_by = $row->created_by ? $row->created_by : 2;
            $updated_by = $row->updated_by ? $row->updated_by : 2;

            $row->info = route('backend.get_user_info',['created' => $created_by, 'updated' => $updated_by]);
        }
        json_result(['total' => $count, 'rows' => $rows]);

    }

    public function form(Request $request) {
        $model = CoachingGroup::where('id', $request->id)->first();
       
        json_result([
            'model' => $model,
        ]);
    }

    public function save(Request $request) {
        $this->validateRequest ([
            'code' => 'required',
            'name' => 'required',
            'status' => 'required',
        ], $request, CoachingGroup::getAttributeName());

        $model = CoachingGroup::firstOrNew(['id' => $request->id]);
        $model->fill($request->all());

        if ($model->save()) {
            json_result([
                'status' => 'success',
                'message' => trans('laother.successful_save'),
            ]);
        }
        json_message(trans('laother.save_error'), 'error');
    }

    public function remove(Request $request){
        $ids = $request->ids;

        CoachingGroup::destroy($ids);

        json_result([
            'status' => 'success',
            'message' => trans('laother.delete_success'),
        ]);
    }

    public function updateStatus(Request $request){
        $ids = $request->ids;
        $status = $request->status;

        CoachingGroup::whereIn('id', $ids)
        ->update([
            'status' => $status,
        ]);

        json_result([
            'status' => 'success',
            'message' => trans('laother.successful_save'),
        ]);

    }
    
}
