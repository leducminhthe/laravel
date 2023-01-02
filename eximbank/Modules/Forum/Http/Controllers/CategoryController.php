<?php

namespace Modules\Forum\Http\Controllers;

use App\Models\Categories\Unit;
use App\Models\Profile;
use App\Scopes\DraftScope;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Intervention\Image\ImageManagerStatic;
use Modules\Forum\Entities\Forum;
use Illuminate\Support\Facades\Auth;
use Modules\Forum\Entities\ForumCategory;
use Modules\Forum\Entities\FilterWord;
use Modules\Forum\Entities\ForumCategoryPermission;
use Modules\Forum\Entities\ForumThread;
use Modules\Forum\Entities\ForumSettingScoreComment;
use Modules\UserPoint\Entities\UserPointItem;
use Modules\UserPoint\Entities\UserPointResult;
use Modules\Promotion\Entities\PromotionUserPoint;
use Modules\Promotion\Entities\PromotionLevel;
use Modules\UserPoint\Entities\UserPointSettings;
use Modules\Notify\Entities\Notify;
use Modules\AppNotification\Helpers\AppNotification;

class CategoryController extends Controller
{
    //category
    public function index()
    {
        return view('forum::backend.forum_category.index',[
        ]);
    }

    public function getData(Request $request)
    {
        $search = $request -> input('search');
        $sort = $request ->input('sort','id');
        $order = $request ->input('order','desc');
        $offset =$request ->input('offset',0);
        $limit = $request ->input('limit',20);
        ForumCategory::addGlobalScope(new DraftScope());
        $query = ForumCategory::query();

        if($search){
            $query->where(function($sub_query) use ($search){
                $sub_query->orWhere('name','like','%' . $search . '%');
            });
        }

        $count = $query->count();
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query ->get();
        foreach ($rows as $row) {
            $forums = Forum::where('category_id', '=', $row->id)->pluck('id')->toArray();
            $row->total_thread = ForumThread::whereIn('forum_id', $forums)->count();
            $row->total_thread_approved = ForumThread::whereIn('forum_id', $forums)->where('status', 1)->count();

            $row->permission_url = route('module.permission', ['cate_id' => $row->id]);
            $row->forum_url = route('module.forum', ['cate_id' => $row->id]);
            $row->edit_url = route('module.forum.category.edit', ['id' => $row->id]);
        }
        json_result(['total' => $count, 'rows' => $rows]);

    }

    public function form(Request $request) {
        $model = ForumCategory::select(['id','status','icon','name'])->where('id', $request->id)->first();
        $image = image_forum_1($model->icon);
        json_result([
            'model' => $model,
            'image' => $image
        ]);
    }

    public function save(Request $request) {
        $this->validateRequest([
            'status'=> 'required',
            'name' => 'required',
        ], $request, ForumCategory::getAttributeName());

        $model = ForumCategory::firstOrNew(['id' => $request->id]);
        $model->fill($request->all());
        if ($request->id) {
            $model->created_by = $model->created_by;
        }
        $model->updated_by = profile()->user_id;
        // $model->unit_id = is_array($request->unit_id) ? json_encode($request->unit_id) : '';

        if ($request->icon){
            $uploadPath = data_file(path_upload($request->icon), true, 'upload');

            $new_folder = date('Y/m/d') . '/forum/';

            $storage = \Storage::disk('upload');
            if (!$storage->exists($new_folder)) {
                \File::makeDirectory($storage->path($new_folder), 0777, true);
            }

            $file_path = $new_folder . basename($request->icon);

            $resize_image = ImageManagerStatic::make($uploadPath);
            $resize_image->resize(50, 50);
            $resize_image->save($storage->path($file_path));

            $model->icon = $file_path;
        }

        if ($model->save()) {
            json_result([
                'status' => 'success',
                'message' => trans('laother.successful_save'),
                'redirect' => route('module.forum.category')
            ]);
        }
        json_message(trans('laother.save_error'), 'error');
    }

    public function remove(Request $request) {
        $ids = $request->input('ids', null);
        ForumThread::query()
            ->leftJoin('el_forum as forum', 'forum.id', '=', 'el_forum_thread.forum_id')
            ->whereIn('forum.category_id', $ids)
            ->delete();
        Forum::query()->whereIn('category_id', $ids)->delete();
        ForumCategory::destroy($ids);
        json_result([
            'status' => 'success',
            'message' => trans('laother.delete_success'),
        ]);
    }

    public function ajaxIsopenPublish(Request $request) {
        $this->validateRequest([
            'ids' => 'required',
            'status' => 'required|in:0,1'
        ], $request, [
            'ids' => trans('laprofile.rank'),
        ]);

        $ids = $request->input('ids', null);
        $status = $request->input('status', 0);
        if(is_array($ids)) {
            foreach ($ids as $id) {
                $model = ForumCategory::findOrFail($id);
                $model->status = $status;
                $model->save();
            }
        } else {
            $model = ForumCategory::findOrFail($ids);
            $model->status = $status;
            $model->save();
        }

        json_result([
            'status' => 'success',
            'message' => trans('laother.successful_save'),
        ]);
    }

    //forum
    public function forum($cate_id)
    {
        $cate = ForumCategory::find($cate_id);
        return view('forum::backend.forum.index', [
            'cate_id' => $cate_id,
            'cate' => $cate,
        ]);
    }
    public function getDataForum($cate_id, Request $request)
    {
        $search = $request -> input('search');
        $sort = $request ->input('sort','id');
        $order = $request ->input('order','desc');
        $offset =$request ->input('offset',0);
        $limit = $request ->input('limit',20);

        $query = Forum::query();
        $query->where('category_id', '=', $cate_id);

        if($search){
            $query->where(function($sub_query) use ($search){
                $sub_query->orWhere('name','like','%' . $search . '%');
            });
        }

        $count = $query->count();
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query ->get();
        foreach ($rows as $row) {
            $thread = ForumThread::where('forum_id', '=', $row->id)->whereNotNull('main_article')->first();
            $row->total_thread = ForumThread::where('forum_id', '=', $row->id)->count();
            $row->total_thread_approved = ForumThread::where('forum_id', '=', $row->id)->where('status', 1)->count();

            $row->action_url = route('module.forum.thread', ['cate_id' => $cate_id, 'forum_id' => $row->id]);
            $row->edit_url = route('module.forum.edit', ['cate_id' => $cate_id, 'id' => $row->id]);
            $row->reward_point = route('module.forum.reward_point', ['cate_id' => $cate_id, 'id' => $row->id]);

            if (empty($thread)) {
                $row->add_forum = route('module.forum.thread.create', ['cate_id' => $cate_id, 'forum_id' => $row->id]);
            } else {
                $row->add_forum = route('module.forum.thread.edit', [
                    'cate_id' => $cate_id,
                    'forum_id' => $row->id,
                    'id' => $thread->id
                ]);
            }
        }
        json_result(['total' => $count, 'rows' => $rows]);

    }
    public function formForum(Request $request) {
        $model = Forum::select(['id','status','icon','name'])->where('id', $request->id)->first();
        $image = image_forum_2($model->icon);
        json_result([
            'model' => $model,
            'image' => $image
        ]);
    }

    public function saveForum($cate_id, Request $request) {
        $this->validateRequest([
            'status'=> 'required',
            'name' => 'required',
        ], $request, Forum::getAttributeName());

        $model = Forum::firstOrNew(['id' => $request->id]);
        $model->fill($request->all());
        $model->category_id = $cate_id;
        $model->updated_by = profile()->user_id;
        if ($request->id) {
            $model->created_by = $model->created_by;
        } else {
            $model->created_by = profile()->user_id;
        }

        if ($request->icon){
            $uploadPath = data_file(path_upload($request->icon), true, 'upload');

            $new_folder = date('Y/m/d') . '/forum/';

            $storage = \Storage::disk('upload');
            if (!$storage->exists($new_folder)) {
                \File::makeDirectory($storage->path($new_folder), 0777, true);
            }

            $file_path = $new_folder . basename($request->icon);

            $resize_image = ImageManagerStatic::make($uploadPath);
            $resize_image->resize(40, 40);
            $resize_image->save($storage->path($file_path));

            $model->icon = $file_path;
        }

        if ($model->save()) {
            json_result([
                'status' => 'success',
                'message' => trans('laother.successful_save'),
                'redirect' => route('module.forum', ['cate_id' => $cate_id])
            ]);
        }
        json_message(trans('laother.save_error'), 'error');
    }
    public function removeForum($cate_id, Request $request) {
        $ids = $request->input('ids', null);
        ForumThread::query()->whereIn('forum_id', $ids)->delete();
        Forum::destroy($ids);
        json_result([
            'status' => 'success',
            'message' => trans('laother.delete_success'),
        ]);
    }
    public function ajaxIsopenPublishForum(Request $request) {
        $this->validateRequest([
            'ids' => 'required',
            'status' => 'required|in:0,1'
        ], $request, [
            'ids' => trans('laprofile.rank'),
        ]);

        $ids = $request->input('ids', null);
        $status = $request->input('status', 0);
        if(is_array($ids)) {
            foreach ($ids as $id) {
                $model = Forum::findOrFail($id);
                $model->status = $status;
                $model->save();
            }
        } else {
            $model = Forum::findOrFail($ids);
            $model->status = $status;
            $model->save();
        }

        json_result([
            'status' => 'success',
            'message' => trans('laother.successful_save'),
        ]);
    }

    //thread
    public function forumThread($cate_id, $forum_id) {
        $cate = ForumCategory::find($cate_id);
        $forum = Forum::where('id', '=', $forum_id)->where('category_id', '=', $cate_id)->first();

        return view('forum::backend.forum_thread.index', [
            'forum' => $forum,
            'cate' => $cate,
        ]);
    }
    public function getDataThread($cate_id, $forum_id, Request $request)
    {
        $search = $request -> input('search');
        $sort = $request ->input('sort','id');
        $order = $request ->input('order','desc');
        $offset = $request ->input('offset',0);
        $limit = $request ->input('limit',20);

        $query = ForumThread::query();
        $query->where('forum_id', '=', $forum_id);

        if($search){
            $query->where(function($sub_query) use ($search){
                $sub_query->orWhere('name','like','%' . $search . '%');
            });
        }

        $count = $query ->count();
        $query ->offset($offset);
        $query->limit($limit);

        $rows = $query ->get();
        foreach ($rows as $row) {
            $row->created_at2 = get_date($row->created_at, 'H:i d/m/Y');
            $row->edit_thread = route('module.forum.thread.edit', [
                'cate_id' => $cate_id,
                'forum_id' => $forum_id,
                'id' => $row->id
            ]);
        }
        json_result(['total' => $count, 'rows' => $rows]);
    }
    public function saveStatus($cate_id, $forum_id, Request $request)
    {
        $ids = $request->ids;
        $userpoint_setting = UserPointSettings::where('pvalue', '>', 0)->where('pkey', 'forum_create')->where('item_id', $forum_id)->where('item_type', 7)->first(['id','pvalue']);

        foreach($ids as $id){
            $forum = ForumThread::find($id);
            $forum->status = 1;
            $forum->save();

            $check_user_point = UserPointResult::where('setting_id', @$userpoint_setting->id)->where('item_id', $forum->id)->where('user_id', $forum->created_by)->where('type', 7)->exists();
            if ($userpoint_setting && !$check_user_point) {
                $subject = 'Điểm thưởng khi đăng và được duyệt bài viết';
                $content = 'Nhận điểm thưởng khi đăng và được duyệt bài viết: '. $forum->title;

                $save_point = new UserPointResult();
                $save_point->user_id = $forum->created_by;
                $save_point->content = $content;
                $save_point->setting_id = $userpoint_setting->id;
                $save_point->point = $userpoint_setting->pvalue;
                $save_point->item_id = $forum->id;
                $save_point->type = 7;
                $save_point->type_promotion = 1;
                $save_point->save();

                $user_point = PromotionUserPoint::firstOrNew(['user_id' => $forum->created_by]);
                $user_point->point = (int)$user_point->point + (int)$userpoint_setting->pvalue;
                $user_point->level_id = PromotionLevel::levelUp($user_point->point, $forum->created_by);
                $user_point->save();

                $query = new Notify();
                $query->user_id = $forum->created_by;
                $query->subject = $subject;
                $query->content = $content;
                $query->url = '';
                $query->created_by = 0;
                $query->save();

                $content = \Str::words(html_entity_decode(strip_tags($query->content)), 10);
                $redirect_url = route('module.notify.view', [
                    'id' => $query->id,
                    'type' => 1
                ]);

                $notification = new AppNotification();
                $notification->setTitle($query->subject);
                $notification->setMessage($content);
                $notification->setUrl($redirect_url);
                $notification->add($forum->created_by);
                $notification->save();
            }
        }
        json_result(['ids' => $ids]);
    }

    //permission category
    public function permission($cate_id)
    {
        $corporations = Unit::select(['id','name','code'])->where('level', '=', 1)->where('status', '=', 1)->get();
        $cate = ForumCategory::find($cate_id);
        return view('forum::backend.permission.index', [
            'cate_id' => $cate_id,
            'cate' => $cate,
            'corporations' => $corporations,
        ]);
    }
    public function getDataPermission($cate_id, Request $request)
    {
        $search = $request -> input('search');
        $sort = $request ->input('sort','id');
        $order = $request ->input('order','desc');
        $offset =$request ->input('offset',0);
        $limit = $request ->input('limit',20);

        $query = ForumCategoryPermission::query();
        $query->select([
            'a.*',
            'b.name AS unit_name',
            'c.code AS profile_code',
            'c.lastname',
            'c.firstname',
            'c.email',
            'd.name as unit_manager',
        ]);
        $query->from('el_forum_category_permission AS a');
        $query->leftJoin('el_unit AS b', 'b.id', '=', 'a.unit_id');
        $query->leftJoin('el_unit AS d', 'd.code', '=', 'b.parent_code');
        $query->leftJoin('el_profile AS c', 'c.user_id', '=', 'a.user_id');
        $query->where('a.forum_cate_id', '=', $cate_id);

        $count = $query->count();
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query ->get();
        foreach ($rows as $row) {
            $row->profile_name = $row->lastname . ' ' . $row->firstname;
        }
        json_result(['total' => $count, 'rows' => $rows]);

    }
    public function savePermission($cate_id, Request $request) {
        $this->validateRequest([
            'unit_id' => 'nullable|exists:el_unit,id',
        ], $request, Forum::getAttributeName());

        $unit_id = $request->input('unit_id');
        $user_id = $request->input('user_id');
        if ($unit_id) {
            foreach ($unit_id as $item) {
                if (ForumCategoryPermission::checkObjectUnit($cate_id, $item)){
                    continue;
                }
                $model = new ForumCategoryPermission();
                $model->forum_cate_id = $cate_id;
                $model->unit_id = $item;
                $model->save();
            }
        }
        if ($user_id){
            foreach ($user_id as $item) {
                if (ForumCategoryPermission::checkObjectUser($cate_id, $item)){
                    continue;
                }
                $model = new ForumCategoryPermission();
                $model->forum_cate_id = $cate_id;
                $model->user_id = $item;
                $model->save();
            }
        }

        json_result([
            'status' => 'success',
            'message' => trans('laother.successful_save'),
            'redirect' => route('module.permission', ['cate_id' => $cate_id])
        ]);

    }
    public function removePermission($cate_id, Request $request) {
        $ids = $request->input('ids', null);
        ForumCategoryPermission::destroy($ids);
        json_result([
            'status' => 'success',
            'message' => trans('laother.delete_success'),
        ]);
    }
    public function getChild($cate_id, Request $request){
        $unit_id = $request->id;
        $unit = Unit::find($unit_id);

        $childs = Unit::where('parent_code', '=', $unit->code)->get(['id', 'name', 'code']);

        $count_child = [];
        $page_child = [];
        foreach ($childs as $item){
            $count_child[$item->id] = Unit::countChild($item->code);
            $page_child[$item->id] = route('module.permission.get_tree_child', ['cate_id' => $cate_id, 'parent_code' => $unit->code]);
        }

        $data = ['childs' => $childs, 'count_child' => $count_child, 'page_child' => $page_child];
        return \response()->json($data);
    }
    public function getTreeChild($cate_id, Request $request){
        $parent_code = $request->parent_code;
        return view('forum::backend.permission.tree_unit_child', [
            'parent_code' => $parent_code
        ]);
    }

    //filter_word
    public function filter()
    {
        return view('forum::backend.forum_filter_word.index',[
        ]);
    }
    public function filter_word(Request $request) {
        $model = FilterWord::select(['id','status','name'])->where('id', $request->id)->first();
        json_result($model);
    }
    public function filter_save(Request $request){
        $this->validateRequest([
            'status'=> 'required',
            'name' => 'required',
        ], $request, FilterWord::getAttributeName());

        $model = FilterWord::firstOrNew(['id' => $request->id]);
        $model->fill($request->all());

        if ($model->save()) {
            json_result([
                'status' => 'success',
                'message' =>trans('laother.successful_save'),
            ]);
        }
        json_message(trans('laother.save_error'), 'error');
    }
    public function filter_remove(Request $request) {
        $ids = $request->input('ids', null);
        FilterWord::destroy($ids);
        json_result([
            'status' => 'success',
            'message' => trans('laother.delete_success'),
        ]);
    }
    public function getword(Request $request)
    {
        $search = $request -> input('search');
        $sort = $request ->input('sort','id');
        $order = $request ->input('order','desc');
        $offset =$request ->input('offset',0);
        $limit = $request ->input('limit',20);

        $query = FilterWord::query();

        if($search){
            $query->where(function($sub_query) use ($search){
                $sub_query->orWhere('name','like','%' . $search . '%');
            });
        }

        $count = $query->count();
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query ->get();
        json_result(['total' => $count, 'rows' => $rows]);
    }
    public function filterAjaxIsopenPublish(Request $request) {
        $this->validateRequest([
            'ids' => 'required',
            'status' => 'required|in:0,1'
        ], $request, [
            'ids' => trans('laprofile.rank'),
        ]);

        $ids = $request->input('ids', null);
        $status = $request->input('status', 0);
        if(is_array($ids)) {
            foreach ($ids as $id) {
                $model = FilterWord::findOrFail($id);
                $model->status = $status;
                $model->save();
            }
        } else {
            $model = FilterWord::findOrFail($ids);
            $model->status = $status;
            $model->save();
        }

        json_result([
            'status' => 'success',
            'message' => trans('laother.successful_save'),
        ]);
    }

    // ĐIỂM THƯỞNG
    public function rewardPoint($cate_id, $id)
    {
        $category = Forum::select(['id','name'])->where('id', $id)->first();
        return view('forum::backend.reward_point.form',[
            'category' => $category,
            'cate_id' => $cate_id
        ]);
    }

    // LẤY DỮ LIỆU ĐIỂM THƯỞNG
    public function getDataRewardPoint($cate_id, $id, Request $request)
    {
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);
        $query = UserPointItem::query();
        $query->select([
            'a.*',
        ]);
        $query->from('el_userpoint_item as a');
        $query->where('a.type', 7);

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row) {
            $userpoint_setting = UserPointSettings::where('pkey', $row->ikey)->where('item_id', $id)->where('item_type', 7)->first();
            $row->setting_updated_at2 = isset($userpoint_setting) ? get_date($userpoint_setting->updated_at, 'H:i:s d/m/Y') : '';
            $row->pvalue = isset($userpoint_setting) ? round($userpoint_setting->pvalue) : 0;
            $row->setting_id = isset($userpoint_setting) ? round($userpoint_setting->id) : '';
        }
        json_result(['total' => $count, 'rows' => $rows]);
    }

    // LƯU ĐIỂM THƯỞNG
    public function saveRewardPoint($cate_id, $id, Request $request)
    {
        foreach ($request->userpoint_id as $key => $userpoint_id) {
            if ($userpoint_id == null && $request->promotion_status[$key] == 1) {
                $complete = new UserPointSettings();
                $complete->pkey = $request->ikey[$key];
                $complete->item_id = $id;
                $complete->item_type = 7;
                $complete->pvalue = $request->userpoint_others[$key] ? $request->userpoint_others[$key] : 0;
                $complete->save();
            } else if ($userpoint_id != null && $request->promotion_status[$key] == 1) {
                $complete = UserPointSettings::firstOrNew(['id' => $userpoint_id]);
                $complete->pkey = $request->ikey[$key];
                $complete->item_id = $id;
                $complete->item_type = 7;
                $complete->pvalue = $request->userpoint_others[$key] ? $request->userpoint_others[$key] : 0;
                $complete->save();
            } else if ($userpoint_id != null && $request->promotion_status[$key] == 0) {
                $complete = UserPointSettings::firstOrNew(['id' => $userpoint_id]);
                $complete->pkey = $request->ikey[$key];
                $complete->item_id = $id;
                $complete->item_type = 7;
                $complete->pvalue = 0;
                $complete->save();
            }
        }

        json_result([
            'status' => 'success',
            'message' => trans('laother.successful_save'),
        ]);
    }

    //DỮ LIỆU MỐC ĐIỂM BÌNH LUẬN
    public function getDataRewardComment($cate_id, $id, Request $request) {
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);
        $query = ForumSettingScoreComment::query();
        $query->where('forum_id', $id);
        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function saveRewardComment($cate_id, $id, Request $request) {
        $this->validateRequest([
            'score' => 'required',
            'reward_comment' => 'required',
        ], $request, ForumSettingScoreComment::getAttributeName());

        $score = $request->input('score');
        $reward_comment = $request->input('reward_comment');
        $comment_id = $request->id;

        $check1 = ForumSettingScoreComment::query()
            ->where('forum_id', $id)
            ->where('id','!=',$comment_id)
            ->where('reward_comment', $reward_comment);
        if ($check1->exists()) {
            json_result([
                'status' => 'error',
                'message' => 'Mốc điểm đã tồn tại',
            ]);
        }

        $model = ForumSettingScoreComment::firstOrNew(['id' => $request->id]);
        $model->forum_id = $id;
        $model->fill($request->all());
        if ($model->save()) {
            json_result([
                'status' => 'success',
                'message' => trans('laother.successful_save'),
            ]);
        }
        json_message(trans('laother.save_error'), 'error');
    }

    public function formRewardComment($cate_id, $id, Request $request) {
        $model = ForumSettingScoreComment::where('id', $request->id)->where('forum_id', $id)->first();
        json_result($model);
    }

    public function removeRewardComment($cate_id, $id, Request $request) {
        $ids = $request->input('ids', null);
        ForumSettingScoreComment::destroy($ids);
        json_result([
            'status' => 'success',
            'message' => trans('laother.delete_success'),
        ]);
    }
}
