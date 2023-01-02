<?php

namespace Modules\Forum\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Modules\Forum\Entities\ForumCategory;
use Modules\Forum\Entities\Forum;
use Modules\Forum\Entities\ForumThread;
use Illuminate\Support\Facades\Auth;


class CreateNewsController extends Controller
{
    public function index($cate_id, $forum_id, $id = 0)
    {
        $cate = ForumCategory::find($cate_id);
        $forum = Forum::where('id', '=', $forum_id)->where('category_id', '=', $cate_id)->first();

        if ($id) {
            $model = ForumThread::find($id);
            $page_title = $model->title;
            return view('forum::backend.forum_thread.form', [
                'model' => $model,
                'forum' => $forum,
                'page_title' => $page_title,
                'cate' => $cate,
            ]);
        }

        $model = new ForumThread();
        $page_title = trans('labutton.add_new');

        return view('forum::backend.forum_thread.form', [
            'model' => $model,
            'forum' => $forum,
            'page_title' => $page_title,
            'cate' => $cate,
        ]);
    }

    public function save($cate_id, $forum_id, Request $request) {
        $this->validateRequest([
            'title' => 'required',
            'content' => 'required',
            'forum_id' => 'required',
        ], $request, ForumThread::getAttributeName());

        $model = ForumThread::firstOrNew(['id' => $request->id]);
        $model->fill($request->all());
        $model->created_by = profile()->user_id;
        $model->updated_by = profile()->user_id;

        if ($model->save()) {
            json_result([
                'status' => 'success',
                'message' => trans('laother.successful_save'),
                'redirect' => route('module.forum.thread',[
                    'cate_id' => $cate_id,
                    'forum_id' => $forum_id
                ])
            ]);
        }

        json_message(trans('laother.save_error'), 'error');
    }

    public function remove(Request $request) {
        $ids = $request->input('ids', null);
        ForumThread::destroy($ids);
        json_result([
            'status' => 'success',
            'message' => trans('laother.delete_success'),
        ]);
    }

}
