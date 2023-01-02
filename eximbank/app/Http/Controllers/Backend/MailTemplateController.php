<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Config;
use App\Models\MailTemplate;
use App\Scopes\DraftScope;
use Illuminate\Http\Request;
use LDAP\Result;

class MailTemplateController extends Controller
{
    public function index() {
        return view('backend.mailtemplate.index');
    }

    public function form($id) {
        $model = MailTemplate::findOrFail($id);
        return view('backend.mailtemplate.form', [
            'model' => $model
        ]);
    }

    public function save(Request $request) {
        $this->validateRequest([
            'name' => 'required|max:255',
            'title' => 'required|max:255',
            'content' => 'required',
        ], $request, ['name' => 'Tên', 'title' => 'Tiêu đề', 'content' => trans("latraining.content")]);
        $model = MailTemplate::findOrFail($request->id);
        $model->fill($request->all());
        $model->content = html_entity_decode($request->get('content'));

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
        //MailTemplate::addGlobalScope(new DraftScope());
        $query = MailTemplate::query();
        if ($search) {
            $query->where(function ($subquery) use ($search){
                $subquery->orWhere('name', 'like', '%'. $search .'%');
                $subquery->orWhere('code', 'like', '%'. $search .'%');
                $subquery->orWhere('title', 'like', '%'. $search .'%');
            });
        }

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row) {
            $row->update_day = in_array($row->code, ['notify_course_deadline_1', 'notify_course_deadline_2', 'notify_offline_schedule']);
            $row->num_day =  Config::getConfig($row->code);
            $row->edit_url = route('backend.mailtemplate.edit', ['id' => $row->id]);
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function updateTimeSend(Request $request) {
        $mail_code = $request->mail_code;
        $num_day = $request->num_day;

        Config::setConfig($mail_code, $num_day);

        json_message(trans('laother.successful_save'));
    }
}
