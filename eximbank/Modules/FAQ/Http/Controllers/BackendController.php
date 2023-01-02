<?php

namespace Modules\FAQ\Http\Controllers;

use App\Scopes\DraftScope;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Modules\FAQ\Entities\FAQs;

class BackendController extends Controller
{
    public function index()
    {
        return view('faq::backend.index',[
        ]);
    }

    public function getData(Request $request)
    {
        $search = $request -> input('search');
        $sort = $request ->input('sort','id');
        $order = $request ->input('order','desc');
        $offset =$request ->input('offset',0);
        $limit = $request ->input('limit',20);
        FAQs::addGlobalScope(new DraftScope());
        $query = FAQs::query();

        if($search){
            $query->where(function($sub_query) use ($search){
                $sub_query->orWhere('name', 'like', '%' . $search . '%');
            });
        }

        $count = $query ->count();
        $query -> orderBy($sort,$order);
        $query ->offset($offset);
        $query->limit($limit);

        $rows = $query ->get();
        foreach ($rows as $row) {
            $row->edit_url = route('module.faq.edit', ['id' => $row->id]);
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function form(Request $request) {
        $model = FAQs::select(['id','content','name'])->where('id', $request->id)->first();
        json_result($model);
    }

    public function save(Request $request) {
        $this->validateRequest([
            'name' => 'required',
            'content' => 'required',
        ], $request, FAQs::getAttributeName());

        $model = FAQs::firstOrNew(['id' => $request->id]);
        $model->fill($request->all());
        $model->content = html_entity_decode($request->get('content'));
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
        FAQs::destroy($ids);
        json_result([
            'status' => 'success',
            'message' => trans('laother.delete_success'),
        ]);
    }

}
