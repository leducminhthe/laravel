<?php

namespace Modules\BotConfig\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\BotConfig\Entities\BotConfigAnswer;
use Modules\BotConfig\Entities\BotConfigQuestion;

class BotConfigController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request  )
    {
        if ($request->ajax()){
            $search = $request -> input('search');
            $sort = $request ->input('sort','id');
            $order = $request ->input('order','desc');
            $offset =$request ->input('offset',0);
            $limit = $request ->input('limit',20);
            $sub = BotConfigQuestion::select('answer_id', \DB::raw("GROUP_CONCAT(question) as question"))->groupBy('answer_id');
            $query = BotConfigAnswer::joinSub($sub,'b',function ($join){
                $join->on('bot_config_answer.id','=','b.answer_id');
            })->selectRaw("bot_config_answer.id , bot_config_answer.answer , b.question, bot_config_answer.updated_at");
            if($search){
                $query->where(function($sub_query) use ($search){
                    $sub_query->orWhere('b.question','like','%' . $search . '%');
                });
            }

            $count = $query->count();
            $query->offset($offset);
            $query->limit($limit);

            $rows = $query ->get();
            foreach ($rows as $index => $row) {
                $row->updated_formatter = get_datetime($row->updated_at,'d/m/Y H:i');
            }
            json_result(['total' => $count, 'rows' => $rows]);
        }
        return view('botconfig::backend.index');
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
        if ($request->suggest==1)
            $this->saveSuggest($request);
        else
            $this->saveKeyword($request);
        json_success();
    }

    private function saveKeyword(Request $request)
    {
        $questions = explode(',',$request->question);
        $botConfigAnswer = BotConfigAnswer::firstOrNew(['id'=>$request->id]);
        $botConfigAnswer->fill($request->all());
        if($botConfigAnswer->save())
            BotConfigQuestion::where(['answer_id'=>$botConfigAnswer->id])->delete();
        foreach ($questions as $index => $item) {
            $botConfigQuestion = new BotConfigQuestion();
            $botConfigQuestion->question = $item;
            $botConfigQuestion->answer_id = $botConfigAnswer->id;
            $botConfigQuestion->save();
        }
    }
    private function saveSuggest(Request $request)
    {
        $botConfigQuestion = BotConfigQuestion::firstOrNew(['id'=>$request->id]);
        $botConfigQuestion->question = $request->input('question-suggest');
        $botConfigQuestion->suggest = $request->input('suggest');
        $answers = $request->input('answer-suggest');
        if($botConfigQuestion->save()) {
            $dataAnswer =[];
            foreach ($answers as $index => $answer) {
                $dataAnswer[] = new BotConfigAnswer(['answer'=>$answer]);
            }
            $botConfigQuestion->answers()->saveMany($dataAnswer);
        }
    }
    public function show($id)
    {
        return view('botconfig::show');
    }

    public function edit($id)
    {
        $data = BotConfigAnswer::with('questions')->where(['id'=>$id])->first();
        json_result($data);
    }

    public function destroy(Request $request)
    {
        $ids = $request->input('ids', null);
        foreach ($ids as $index => $id) {
            BotConfigQuestion::where(['answer_id'=>$id])->delete();
        }
        BotConfigAnswer::destroy($ids);

        json_result([
            'status' => 'success',
            'message' => trans('laother.delete_success'),
        ]);
    }
}
