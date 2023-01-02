<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\MailSignature;
use App\Models\Categories\Unit;
use App\Scopes\DraftScope;
use Illuminate\Http\Request;

class MailSignatureController extends Controller
{
    public function index() {
        return view('backend.mail_signature.index');
    }

    public function form($id = 0, Request $request) {
        Unit::addGlobalScope(new DraftScope());
        $units = Unit::select(['id','name','code'])->where('level',0)->whereStatus(1)->get();

        if ($id){
            $model = MailSignature::find($id);
        }else{
            $model = new MailSignature();
        }

        $page_title = $id ? trans('lasetting.edit') : trans('lasetting.add_new');

        return view('backend.mail_signature.form', [
            'model' => $model,
            'page_title' => $page_title,
            'units' => $units,
        ]);
    }

    public function save(Request $request) {
        $this->validateRequest([
            'unit_id' => 'required|unique:el_mail_signature,unit_id,'. $request->id,
            'content' => 'required',
        ], $request, ['unit_id' => 'Công ty', 'content' => trans("latraining.content")]);

        $model = MailSignature::firstOrNew(['id' => $request->id]);
        $model->fill($request->all());
        $model->content = $request->input('content');

        if ($model->save()) {
            json_message(trans('laother.successful_save'));
        }

        json_message(trans('laother.can_not_save'), 'error');
    }

    public function getData(Request $request) {
        $search = $request->input('search');
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        //MailSignature::addGlobalScope(new DraftScope());
        $query = MailSignature::query();

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row) {
            $unit = Unit::find($row->unit_id);

            $row->unit_name = $unit->name;
            $row->edit_url = route('backend.mail_signature.edit', ['id' => $row->id]);
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function remove(Request $request) {
        $ids = $request->input('ids', null);
        MailSignature::destroy($ids);
        json_message(trans('laother.delete_success'));
    }
}
