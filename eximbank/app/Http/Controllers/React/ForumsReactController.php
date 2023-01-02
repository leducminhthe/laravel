<?php

namespace App\Http\Controllers\React;

use App\Models\Categories\Unit;
use App\Scopes\DraftScope;
use App\Scopes\CompanyScope;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Modules\Forum\Entities\Forum;
use Modules\Forum\Entities\FilterWord;
use Modules\Forum\Entities\ForumCategory;
use Modules\Forum\Entities\ForumThread;
use Modules\Forum\Entities\ForumComment;
use Modules\Forum\Entities\ForumCategoryPermission;
use App\Models\Profile;
use App\Models\Permission;
use App\Models\ForumsStatistic;
use App\Models\InteractionHistory;
use Illuminate\Support\Facades\Auth;
use Modules\Forum\Entities\ForumUserLikeComment;
use App\Models\ProfileView;
use Carbon\Carbon;
use Modules\Forum\Entities\ForumUserLikeThread;
use Modules\UserPoint\Entities\UserPointItem;
use Modules\UserPoint\Entities\UserPointResult;
use Modules\Promotion\Entities\PromotionUserPoint;
use Modules\Promotion\Entities\PromotionLevel;
use Modules\UserPoint\Entities\UserPointSettings;
use Modules\Notify\Entities\Notify;
use Modules\AppNotification\Helpers\AppNotification;
use Modules\Forum\Entities\ForumSettingScoreComment;

class ForumsReactController extends Controller
{
    public function index()
    {
        return view('react.forum.index');
    }

    // LẤY DỮ LIỆU DIỄN ĐÀN
    public function dataForums(Request $request)
    {
        ForumsStatistic::update_forums_insert_statistic();
        $search = $request->search; //hashtag
        $profile_view = ProfileView::select(['unit_id','user_id'])->find(profile()->user_id);
        $is_admin = Permission::isAdmin();

        if (!empty($search)){
            $forum_thread_search = ForumThread::query()
                ->from('el_forum_thread as a')
                ->leftJoin('el_forum as b', 'b.id', '=', 'a.forum_id')
                ->where('a.status', '=', 1)
                ->where('a.hashtag', 'like', '%'.$search.'%')
                ->orderBy('a.id', 'DESC');
            $data_forums = $forum_thread_search->get();
            foreach ($data_forums as $key => $thread) {
                $thread->checked = 0;
                $check_unit = 0;
                $get_unit_id_forums_cate = ForumCategoryPermission::where('forum_cate_id',$thread->category_id)->get();
                foreach ($get_unit_id_forums_cate as $get_unit_id_forum_cate) {
                    if( $profile_view->unit_id == $get_unit_id_forum_cate->unit_id) {
                        $check_unit = 1;
                    } else if ($profile_view->user_id == $get_unit_id_forum_cate->user_id) {
                        $check_unit = 1;
                    }
                }
                if ( (!$get_unit_id_forums_cate->isEmpty() && $check_unit == 1) || $get_unit_id_forums_cate->isEmpty() || $is_admin) {
                    $user = ProfileView::whereUserId($thread->updated_by ? $thread->updated_by : $thread->created_by)->first(['full_name','avatar']);
                    $thread->count_comment = ForumComment::CountComment($thread->id);
                    $thread->checkUpdatedBy = $thread->updated_by == auth()->id() ? 1 : 0;
                    $thread->title = \Str::limit($thread->title);
                    $thread->created_at2 = Carbon::parse($thread->created_at)->diffForHumans();
                    $thread->profileName = $user->full_name;
                    $thread->content = \Str::words(html_entity_decode(strip_tags($thread->content)), 10);
                    $thread->dateCreated = get_date($thread->created_at, 'H:i d/m/Y');
                    $thread->profileAvatar = image_user($user->avatar);
                    $thread->lastComment = ForumThread::getLastestComment($thread->id);
                    if ($thread->lastComment) {
                        $thread->lastCommentAvatar = image_file(Profile::avatar($thread->lastComment->created_by));
                        $thread->lastCommentCreated = get_date($thread->lastComment->created_at, 'H:i d/m/Y');
                        $thread->lastCommentContent = \Str::words(html_entity_decode(strip_tags($thread->lastComment->comment)), 10);
                    }
                } else {
                    $thread->checked = 1;
                    continue;
                }
            }
        } else {
            ForumCategory::addGlobalScope(new CompanyScope());
            $forum_categories = ForumCategory::query()
            ->select('id','name','icon')
            ->where('status', '=', 1);
            $data_forums = $forum_categories->get(['id','icon','name']);
            foreach ($data_forums as $key => $forum_category) {
                $forum_category->checked = 0;
                $check_unit = 0;
                $get_unit_id_forums_cate = ForumCategoryPermission::where('forum_cate_id',$forum_category->id)->get(['unit_id','user_id']);
                foreach ($get_unit_id_forums_cate as $get_unit_id_forum_cate) {
                    if( $profile_view->unit_id == $get_unit_id_forum_cate->unit_id) {
                        $check_unit = 1;
                    } else if ($profile_view->user_id == $get_unit_id_forum_cate->user_id) {
                        $check_unit = 1;
                    }
                }
                if ( (!$get_unit_id_forums_cate->isEmpty() && $check_unit == 1) || $get_unit_id_forums_cate->isEmpty() || $is_admin) {
                    $forum_category->icon = $forum_category->icon ? image_file($forum_category->icon, 'forum1') : asset('images/design/icon_forum_1.png');
                    $forum_category->forums = Forum::where('category_id', $forum_category->id)->orderBy('num_topic', 'DESC')->orderBy('num_comment', 'DESC')->where('status',1)->get();
                    foreach ($forum_category->forums as $key => $item) {
                        $item->icon2 = $item->icon ? image_file($item->icon, 'forum2') : asset('images/design/icon_forum_2.png');
                        $item->getTotalView = ForumThread::where('forum_id', $item->id)->where('status',1)->sum('views');
                        $item->threadCount = $item->thread->count() ? $item->thread->count() : 0;
                        $item->getTotalComment = $item->getTotalComment() ? $item->getTotalComment() : 0;
                        foreach ($item->thread as $thread) {
                            $user = ProfileView::whereUserId($thread->updated_by ? $thread->updated_by : $thread->created_by)->first(['full_name','avatar']);
                            $thread->count_comment = ForumComment::CountComment($thread->id);
                            $thread->lastComment = ForumThread::getLastestComment($thread->id);
                            if ($thread->lastComment) {
                                $thread->lastCommentAvatar = image_file(Profile::avatar($thread->lastComment->created_by));
                                $thread->lastCommentCreated = get_date($thread->lastComment->created_at, 'H:i d/m/Y');
                                $thread->lastCommentContent = \Str::words(html_entity_decode(strip_tags($thread->lastComment->comment)), 10);
                            }
                            $thread->checkUpdatedBy = $thread->updated_by == auth()->id() ? 1 : 0;
                            $thread->title = \Str::limit($thread->title);
                            $thread->created_at2 = Carbon::parse($thread->created_at)->diffForHumans();
                            $thread->dateCreated = get_date($thread->created_at, 'H:i d/m/Y');
                            $thread->profileName = $user->full_name;
                            $thread->profileAvatar = image_user($user->avatar);
                            $thread->content = \Str::words(html_entity_decode(strip_tags($thread->content)), 10);
                        }
                    }
                } else {
                    $forum_category->checked = 1;
                    continue;
                }
            }
        }

        return response()->json([
            'data_forums' => $data_forums,
            'is_admin' => $is_admin,
        ]);
    }

    // LẤY DỮ LIỆU DANH MỤC CON THEO ID
    public function dataTopic($id, Request $request)
    {
        $topic = Forum::select('id','name')->findOrFail($id);
        $is_admin = Permission::isAdmin();

        $forum_thread = ForumThread::where('forum_id',$id)->where('status',1);
        $forum_thread = $forum_thread->get();
        foreach ($forum_thread as $key => $item) {
            $user = ProfileView::whereUserId($item->updated_by ? $item->updated_by : $item->created_by)->first(['full_name','avatar']);
            $item->lastComment = ForumThread::getLastestComment($item->id);
            if ($item->lastComment) {
                $item->lastCommentAvatar = image_file(Profile::avatar($item->lastComment->created_by));
                $item->lastCommentCreated = get_date($item->lastComment->created_at, 'H:i d/m/Y');
                $item->lastCommentContent = \Str::words(html_entity_decode(strip_tags($item->lastComment->comment)), 10);
            }
            $item->checkUpdatedBy = $item->updated_by == auth()->id() ? 1 : 0;
            $item->title = \Str::limit($item->title);
            $item->created_at2 = Carbon::parse($item->created_at)->diffForHumans();
            $item->profileName = $user->full_name;
            $item->profileAvatar = image_user($user->avatar);
            $item->content = \Str::words(html_entity_decode(strip_tags($item->content)), 10);
            $item->countThreadComment = ForumComment::CountComment($item->id);
            $item->dateCreated = get_date($item->created_at, 'H:i d/m/Y');
        }
        return response()->json([
            'topic' => $topic,
            'forum_thread' => $forum_thread,
            'is_admin' => $is_admin,
        ]);
    }

    // LẤY DỮ LIỆU BÀI VIẾT THEO ID
    public function dataThread($id)
    {
        ForumThread::updateItemViews($id);

        $is_admin = profile();
        $is_admin->avatar = image_user($is_admin->avatar);

        $thread = ForumThread::findOrFail($id);
        $user = ProfileView::whereUserId($thread->created_by)->first(['full_name','avatar']);
        $thread->forum_category = $thread->category->name;
        $thread->checkUpdatedBy = $thread->created_by == auth()->id() ? 1 : 0;
        $thread->profileAvatar = image_user($user->avatar);
        $thread->profileName = $user->full_name;
        $thread->created_at2 = Carbon::parse($thread->created_at)->diffForHumans();

        $thread->count_like_thread = $thread->like->where('like', 1)->count();
        $thread->count_dislike_thread = $thread->like->where('dislike', 1)->count();

        /*Lưu lịch sử tương tác của HV*/
        $interaction_history = InteractionHistory::where(['user_id' => profile()->user_id, 'code' => 'forum'])->first();
        if($interaction_history){
            $interaction_history->number = ($interaction_history->number + 1);
            $interaction_history->save();
        }else{
            $interaction_history = new InteractionHistory();
            $interaction_history->user_id = profile()->user_id;
            $interaction_history->code = 'forum';
            $interaction_history->name = 'Diễn đàn';
            $interaction_history->number = 1;
            $interaction_history->save();
        }

        return response()->json([
            'thread' => $thread,
            'is_admin' => $is_admin,
        ]);
    }

    // CHỈNH SỬA BÀI VIẾT
    public function editThread($id){
        $thread = ForumThread::findOrFail($id);
        $thread->forum_category = $thread->category->name;
        return response()->json([
            'thread' => $thread,
        ]);
    }

    // LẤY BÌNH LUẬN THEO ID BÀI VIẾT
    public function dataThreadComment($thread_id)
    {
        $comments = ForumComment::where('thread_id','=',$thread_id)->get();
        $countComments = $comments->count();
        foreach ($comments as $key => $item) {
            $user = $user = ProfileView::whereUserId($item->created_by)->first(['full_name','avatar']);
            $item->checkUpdatedBy = $item->created_by == auth()->id() ? 1 : 0;
            $item->profileAvatar = image_user($user->avatar);
            $item->profileName = $user->full_name;
            $item->created_at2 = Carbon::parse($item->created_at)->diffForHumans();
        }
        return response()->json([
            'comments' => $comments,
            'countComments' => $countComments
        ]);
    }

    // XÓA BÀI VIẾT
    public function removeThread($thread_id)
    {
        $del = ForumThread::find($thread_id);
        $del->comments()->delete();

        $userpoint_setting = UserPointSettings::where('pkey', 'forum_create')->where('item_id', $del->forum_id)->where('pvalue','>',0)->where('item_type', 7)->first(['id','pvalue']);
        $check_userpoint_result = UserPointResult::where('setting_id', @$userpoint_setting->id)->where('user_id', profile()->user_id)->where('type', 7)->where('item_id', $del->id)->first();
        if (isset($check_userpoint_result)) {
            $user_point = PromotionUserPoint::firstOrNew(['user_id' => $check_userpoint_result->user_id]);
            $user_point->point = (int)$user_point->point - (int)$check_userpoint_result->point;
            $user_point->level_id = PromotionLevel::levelUp($user_point->point, $check_userpoint_result->user_id);
            $user_point->save();
            UserPointResult::where('user_id', profile()->user_id)->where('type', 7)->where('item_id', $del->id)->delete();
        }

        if($del->delete()){
            json_result([
                'status' => 'success',
            ]);
        }
    }

    // LƯU BÌNH LUẬN BÀI VIẾT
    public function sendThreadComment(Request $request, $id)
    {
        $this->validateRequest([
            'comment'=>'required',
        ], $request, ForumComment::getAttributeName());

        $check_comments = FilterWord::where('status',1)->pluck('name')->all();
        $content = mb_strtolower($request->comment);
        $content = html_entity_decode($content,ENT_HTML5, "UTF-8");
        foreach($check_comments as $check_comment){
            $text = strpos($content, $check_comment);
            if ($text > 0 ){
                return json_result([
                    'message' => 'Nội dung có từ phản cảm : ' . $check_comment . ', vui lòng liên hệ admin để biết thêm chi tiết',
                    'status' => 'warning',
                ]);
            }
        }

        $cmt = new ForumComment();
        $cmt->thread_id = $id;
        $cmt->created_by = profile()->user_id;
        $cmt->comment = $request->comment;
        if ($cmt->save()) {
            $post = ForumThread::find($id)->update(['updated_at'=> now()]);
            $total_comment = ForumComment::where('thread_id', '=', $id)->count();
            $thread = ForumThread::find($id);
            $thread->total_comment = $total_comment;
            $thread->save();

            $forum = Forum::find($thread->forum_id);
            $forum->num_comment = $forum->getTotalComment();
            $forum->save();

            $rewards_comment = ForumSettingScoreComment::where('forum_id', $thread->forum_id)->get();
            foreach ($rewards_comment as $key => $reward_comment) {
                $userpoint_reward_comment = UserPointResult::where('ref', $reward_comment->id)->where('user_id', $thread->created_by)->where('type', 7)->where('item_id', $thread->id)->where('setting_id', 1)->exists();
                if ($total_comment == $reward_comment->reward_comment && !$userpoint_reward_comment) {
                    $content_reward = 'Nhận điểm thưởng khi đạt đủ mốc bình luận bài viết diễn đàn: '. $thread->title;
                    $save_point_reward_comment = new UserPointResult();
                    $save_point_reward_comment->user_id = $thread->created_by;
                    $save_point_reward_comment->content = $content_reward;
                    $save_point_reward_comment->setting_id = 1;
                    $save_point_reward_comment->ref = $reward_comment->id;
                    $save_point_reward_comment->point = $reward_comment->score;
                    $save_point_reward_comment->item_id = $thread->id;
                    $save_point_reward_comment->type = 7;
                    $save_point_reward_comment->type_promotion = 1;
                    $save_point_reward_comment->save();

                    $user_point_reward_comment = PromotionUserPoint::firstOrNew(['user_id' => $thread->created_by]);
                    $user_point_reward_comment->point = (int)$user_point_reward_comment->point + (int)$reward_comment->score;
                    $user_point_reward_comment->level_id = PromotionLevel::levelUp($user_point_reward_comment->point, $thread->created_by);
                    $user_point_reward_comment->save();
                }
            }

            $userpoint_setting = UserPointSettings::where('pkey', 'user_comment_forum_thread')->where('item_id', $thread->forum_id)->where('pvalue','>',0)->where('item_type', 7)->first(['id','pvalue']);
            $check_user_point = UserPointResult::where('setting_id', @$userpoint_setting->id)->where('user_id', profile()->user_id)->where('type', 7)->where('item_id', $thread->id)->first();
            if (!empty($userpoint_setting) && !isset($check_user_point)) {
                $subject = 'Điểm thưởng khi bình luận bài viết diễn đàn';
                $content = 'Nhận điểm thưởng khi bình luận bài viết diễn đàn: '. $thread->title;

                $save_point = new UserPointResult();
                $save_point->user_id = profile()->user_id;
                $save_point->content = $content;
                $save_point->setting_id = $userpoint_setting->id;
                $save_point->point = $userpoint_setting->pvalue;
                $save_point->item_id = $thread->id;
                $save_point->type = 7;
                $save_point->type_promotion = 1;
                $save_point->save();

                $user_point = PromotionUserPoint::firstOrNew(['user_id' => profile()->user_id]);
                $user_point->point = (int)$user_point->point + (int)$userpoint_setting->pvalue;
                $user_point->level_id = PromotionLevel::levelUp($user_point->point, profile()->user_id);
                $user_point->save();

                $query = new Notify();
                $query->user_id = profile()->user_id;
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
                $notification->add(profile()->user_id);
                $notification->save();
            }

            json_result([
                'message' => 'Bình luận thành công',
                'status' => 'success',
            ]);
        }
    }

    // XÓA BÌNH LUẬN BÀI VIẾT
    public function removeThreadComment($comment_id){
        $del = ForumComment::find($comment_id);
        if($del->delete()){
            json_result([
                'status' => 'success',
            ]);
        }
    }

    // LƯU CHỈNH SỬA, THÊM MỚI BÀI VIẾT
    public function saveThread($id, Request $request){
        $this->validateRequest([
            'title'=>'required',
            'content' => 'required',
            'hashtag' => 'required'
        ], $request, [
            'title' => 'Tiêu đề bài viết',
            'content' => 'Nội dung bài viết',
            'hashtag' => 'hashtag',
        ]);

        $check_comments = FilterWord::where('status',1)->pluck('name')->all();
        $content = mb_strtolower($request->input('content'));
        $content = html_entity_decode($content,ENT_HTML5, "UTF-8");
        foreach($check_comments as $check_comment){
            $text = strpos($content, $check_comment);
            if ($text > 0 ){
                return json_result([
                    'message' => 'Nội dung có từ phản cảm: ' . $check_comment . ', vui lòng liên hệ admin để biết thêm chi tiết',
                    'status' => 'warning',
                ]);
            }
        }

        if($request->type == 0) {
            $model = new ForumThread;
            $topic = $id;
            $model->status = 2;
        } else {
            $model = ForumThread::findOrFail($id);
            $topic = $model->category->id;
            $model->status = 1;
        }
        $model->fill($request->all());
        $model->forum_id = $topic;
        $model->hashtag = str_replace(' ', '', $request->hashtag);
        $model->created_by = profile()->user_id;
        $model->updated_by = profile()->user_id;

        if ($model->save()) {

            if($request->type == 0){
                /*Lưu lịch sử tương tác của HV*/
                $interaction_history = InteractionHistory::where(['user_id' => profile()->user_id, 'code' => 'forum'])->first();
                if($interaction_history){
                    $interaction_history->number = ($interaction_history->number + 1);
                    $interaction_history->save();
                }else{
                    $interaction_history = new InteractionHistory();
                    $interaction_history->user_id = profile()->user_id;
                    $interaction_history->code = 'forum';
                    $interaction_history->name = 'Diễn đàn';
                    $interaction_history->number = 1;
                    $interaction_history->save();
                }
            }

            json_result([
                'status' => 'success',
                'message' => trans('laother.successful_save'),
            ]);
        }
    }

    // THÊM BÀI VIẾT MỚI
    public function createThread($topic_id){
        $topic = Forum::findOrFail($topic_id);
        return response()->json([
            'topic' => $topic,
        ]);
    }

    //Like DisLike Bài viết
    public function likeThread($thread_id, $type){
        $user_like = ForumUserLikeThread::firstOrNew([
            'thread_id' => $thread_id,
            'user_id' => profile()->user_id,
        ]);
        $user_like->thread_id = $thread_id;
        $user_like->user_id = profile()->user_id;
        if ($type == 'like'){
            $user_like->like = 1;
            $user_like->dislike = null;
        }elseif($type == 'dislike'){
            $user_like->like = null;
            $user_like->dislike = 1;
        }else{
            $user_like->like = null;
            $user_like->dislike = null;
        }
        $user_like->save();

        $count_like_thread = ForumUserLikeThread::query()
            ->where('thread_id', '=', $thread_id)
            ->where('like', '=', 1)
            ->count();

        $count_dislike_thread = ForumUserLikeThread::query()
            ->where('thread_id', '=', $thread_id)
            ->where('dislike', '=', 1)
            ->count();

        $thread = ForumThread::find($thread_id);
        $thread->total_like = $count_like_thread;
        $thread->total_dislike = $count_dislike_thread;
        $thread->save();

        json_result([
            'count_like_thread' => $count_like_thread,
            'count_dislike_thread' => $count_dislike_thread
        ]);
    }
}
