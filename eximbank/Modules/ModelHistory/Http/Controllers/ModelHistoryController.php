<?php

namespace Modules\ModelHistory\Http\Controllers;

use App\Scopes\DraftScope;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\ModelHistory\Entities\ModelHistory;
use Modules\TableManager\Entities\Table;

class ModelHistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        // return view('modelhistory::backend.index');
        return view('backend.history.index',[
        ]);
    }

    public function getData(Request $request)
    {
        if (!$request->from_date && !$request->to_date)
            json_result([]);

        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');
        $sort = $request->input('sort','id');
        $order = $request->input('order','desc');
        $offset = $request->input('offset',0);
        $limit = $request->input('limit',20);
        $user = $request->input('user',0);
        $model_id = $request->input('model','');

        ModelHistory::addGlobalScope(new DraftScope());
        $query = ModelHistory::query();
        $query->select([
            'id',
            'action',
            'note',
            'created_name',
            'created_at',
        ]);
        if($model_id){
            $model = Table::find($model_id)->code;
            $query->where(function ( $subquery) use ($model){
                $subquery->orWhere('model','=',$model);
                $subquery->orWhere('parent_model','=',$model);
            });
        }
        if($fromDate) {
            $query->where('created_at', '>=', get_date($fromDate, 'Y-m-d H:i:s'));
        }
        if ($toDate){
            $query->where('created_at', '<=', get_date($toDate, 'Y-m-d H:i:s'));
        }
        if ($user){
            $query->where('created_by',$user);
        }
        $count = $query->count();
        $query->orderBy( $sort,$order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();
        foreach ($rows as $row) {
            $row->created_date = get_datetime($row->created_at);
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }
}
