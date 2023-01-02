<?php

namespace Modules\UserPoint\Http\Controllers\Backend;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\UserPoint\Entities\UserPointItem;
use App\Http\Controllers\Controller;

class UserPointController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index($type)
    {
        if($type=="2") $title = trans('lacategory.onl_course');
        else if($type=="3") $title = trans('lacategory.off_course');
        else if($type=="4") $title = trans('lacategory.quiz');
        else if($type=="5") $title = trans('lacategory.point_online_activitive_course');
        else if($type=="6") $title = trans('lacategory.library');
        else if($type=="7") $title = trans('lacategory.forum');
        else if($type=="8") $title = trans('lamenu.training_video');
        else if($type=="9") $title = trans('lamenu.news');
        else if($type=="10") $title = trans('latraining.other');
        else if($type=="11") $title = 'Điểm thưởng Coaching';

        return view('userpoint::backend.index',["type"=>$type, "title"=>$title]);
    }

    public function getData(Request $request, $type) {
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $search = $request->input('search');
        $query = UserPointItem::where('type', $type);

        if ($search) {
            $query->where('name', 'like', '%'. $search .'%');
            $query->orWhere('ikey', 'like', '%'. $search .'%');
        }

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();
        
        foreach ($rows as $row) {

        }

        return response()->json(['total' => $count, 'rows' => $rows]);
    }


    public function form(Request $request) {
        $model = UserPointItem::where('id', $request->id)->first();
        json_result($model);
    }

    public function save(Request $request, $type)
    {
        $this->validateRequest([
            'name' => 'required',
            'default_value' => 'required',
        ], $request, [
            'name' => trans('backend.name')
        ]);

        $model = UserPointItem::firstOrNew(['id' => $request->id]);
        $model->fill($request->all());
        $model->type=$type;
        $model->save();

        return \response()->json([
            'status' => 'success',
            'message' => trans('laother.successful_save'),
        ]);
    }

    public function remove(Request $request) {
        $ids = $request->input('ids', null);
        UserPointItem::destroy($ids);
        return response()->json([
            'status' => 'success',
            'message' => trans('laother.delete_success'),
        ]);
    }
}
