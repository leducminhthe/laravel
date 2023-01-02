<?php

namespace Modules\BotConfig\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\BotConfig\Entities\BotConfigAnswer;
use Modules\BotConfig\Entities\BotConfigQuestion;
use Modules\BotConfig\Entities\BotConfigSuggest;

class BotConfigSuggestController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request  )
    {
        $suggests = BotConfigSuggest::all();
        if ($request->ajax()){
            $search = $request -> input('search');
            $sort = $request ->input('sort','id');
            $order = $request ->input('order','desc');
            $offset =$request ->input('offset',0);
            $limit = $request ->input('limit',20);
            $query = BotConfigSuggest::select('id','name','url','parent_id','updated_at');
            $count = $query->count();
            $query->offset($offset);
            $query->limit($limit);

            $rows = $query ->get();
            foreach ($rows as $index => $row) {
                $row->updated_formatter = get_datetime($row->updated_at,'d/m/Y H:i');
            }
            json_result(['total' => $count, 'rows' => $rows]);
        }
        return view('botconfig::backend.suggest_index',[
            'suggests'=>$suggests
        ]);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('botconfig::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $id = $request->input('id');
        $parent_id = $request->input('parent');
        $answer = $request->input('answer');
        $suggests = $request->input('suggest');
        $urls = $request->input('link');
        $level = BotConfigSuggest::find($parent_id);
        $level = $level?($level->level+1):1;
        if ($id){
            foreach ($suggests as $index => $suggest) {
                $id = $index==0?$id:0;
                $model = BotConfigSuggest::firstOrNew(['id'=>$id]);
                $model->name = $suggest;
                $model->answer = $answer[$index];
                $model->url = $urls[$index];
                $model->level = $level ;
                $model->parent_id = (int)$parent_id;
                $model->save();
            }

        }else {
            foreach ($suggests as $index => $suggest) {
                $model = new BotConfigSuggest(['name' => $suggest, 'answer' => $answer[$index], 'url' => $urls[$index], 'parent_id' => $parent_id, 'level' => $level ]);
                $model->save();
            }
        }
        json_success();
    }

    public function show($id)
    {
        return view('botconfig::show');
    }

    public function edit($id)
    {
//        $data = BotConfigSuggest::where(['id'=>$id])->first()->childs;
        $data = BotConfigSuggest::with('parent')->where(['id'=>$id])->first();
        json_result($data);
    }

    public function destroy(Request $request)
    {
        $ids = $request->input('ids', null);
        $idArr = BotConfigSuggest::whereIn('id',$ids)->where('type','<>',1)->select('id')->pluck('id');
        BotConfigSuggest::destroy($idArr);

        json_result([
            'status' => 'success',
            'message' => trans('laother.delete_success'),
        ]);
    }
}
