<?php

namespace App\Http\Controllers\React;

use App\Models\Profile;
use App\Scopes\CompanyScope;
use App\Scopes\DraftScope;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Suggest\Entities\Suggest;
use Modules\Suggest\Entities\SuggestComment;
use App\Models\ProfileView;

class SuggestReactController extends Controller
{
    public function index()
    {
        return view('react.suggest.index');
    }

    public function getSuggest(Request $request) {
        Suggest::addGlobalScope(new CompanyScope());
        $query = Suggest::query();
        if($request->search) {
            $query->where('name','like','%'.$request->search.'%');
        }
        if($request->dateFrom) {
            $query->where('created_at','>=', date_convert($request->dateFrom));
        }
        if($request->dateTo) {
            $query->where('created_at','<=', date_convert($request->dateTo));
        }
        $get_suggests = $query->get(['id','name','created_at','checked_reply']);
        foreach ($get_suggests as $key => $get_suggest) {
            $get_suggest->created_at2 = get_date($get_suggest->created_at);
        }
        return response()->json([
            'get_suggests' => $get_suggests
        ]);
    }

    public function save(Request $request) {
        $this->validateRequest([
            'name' => 'required',
            'content' => 'required',
        ], $request, Suggest::getAttributeName());

        $name = $request->input('name');
        $content = $request->input('content');

        $model = new Suggest();
        $model->name = $name;
        $model->content = $content;
        $model->user_id = profile()->user_id;

        if ($model->save()) {
            json_result([
                'status' => 'success',
                'message' => trans('laother.successful_save'),
                'redirect' => route('module.suggest.index')
            ]);
        }

        json_message(trans('laother.save_error'), 'error');
    }

    public function commentSugget($suggest_id){
        $suggest = Suggest::find($suggest_id,['id','name','content']);
        $comments = SuggestComment::where('suggest_id', '=', $suggest->id)->where('user_id',profile()->user_id)->get();
        foreach($comments as $comment) {
            $comment->created_at2 = get_date($comment->created_at, 'H:i:s d/m/Y');
            $profile_comment = ProfileView::find($comment->user_id,['full_name','code']);
            $comment->profile_full_name = $profile_comment->full_name;
            $comment->profile_code = $profile_comment->code;
        }
        return response()->json([
            'suggest' => $suggest,
            'comments' => $comments,
        ]);
    }

    public function saveUserComment($suggest_id, Request $request) {
        $this->validateRequest([
            'content' => 'required',
        ], $request, SuggestComment::getAttributeName());

        $content = $request->input('content');

        $model = new SuggestComment();
        $model->content = $content;
        $model->suggest_id = $suggest_id;
        $model->user_id = profile()->user_id;

        if ($model->save()) {
            $profile = ProfileView::find($model->user_id,['full_name','code']);
            json_result([
                'profile_code' => $profile->code,
                'profile_full_name' => $profile->full_name,
                'created_at2' => get_date($model->created_at, 'H:i:s d/m/Y'),
                'content' => $content,
            ]);
        }

        json_message(trans('laother.save_error'), 'error');
    }

    // LƯU KTRA ĐÃ HỒI ÂM
    public function saveCheckReplySuggest(Request $request)
    {
        $suggest = Suggest::find($request->id);
        $suggest->checked_reply = $request->checked == true ? 1 : 0;
        $suggest->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Lưu thành công'
        ]);
    }
}
