<?php

namespace App\Http\Controllers\React;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Modules\TopicSituations\Entities\Topic;
use Modules\TopicSituations\Entities\Situation;
use Modules\TopicSituations\Entities\CommentSituation;
use Modules\TopicSituations\Entities\ReplyCommentSituation;
use Modules\TopicSituations\Entities\LikeSituation;
use Modules\TopicSituations\Entities\LikeCommentSituation;
use Carbon\Carbon;
use App\Scopes\CompanyScope;
use App\Models\ProfileView;

class TopicSituationsReactController extends Controller
{
    public function index()
    {
        return view('react.topic_situations.index');
    }

    public function getDataTopic(Request $request)
    {
        $search = $request->input('search');
        $fromdate = $request->dateFrom;
        $todate = $request->dateTo;
        Topic::addGlobalScope(new CompanyScope());
        $query = Topic::query();
        if($search){
            $query->where(function($sub_query) use ($search){
                $sub_query->orWhere('name','like','%' . $search . '%');
                $sub_query->orWhere('code','like','%' . $search . '%');
            });
        }
        if ($fromdate) {
            $query->where('created_at', '>=', date_convert($fromdate, '00:00:00'));
        }

        if ($todate) {
            $query->where('created_at', '<=', date_convert($todate, '23:59:59'));
        }
        $query->where('isopen',1);
        $topics = $query->paginate(4);
        foreach ($topics as $key => $topic) {
            $topic->image = image_file($topic->image);
            $topic->created_at2 = Carbon::parse($topic->created_at)->format('d/m/Y');
        }
        return response()->json([
            'topics' => $topics
        ]);
    }

    public function getDataSituation($topic_id, Request $request)
    {
        $profile = LikeSituation::where('user_id',profile()->user_id)->first();
        if ($profile !== null) {
            $get_profile_like_situation = json_decode($profile->situation_id);
        }

        $search = $request->search;
        $date_created = $request->dateFrom;
        $topic = Topic::find($topic_id,['id','name']);
        
        $query = Situation::query();
        $query->where('topic_id',$topic_id);

        if($search){
            $query->where(function($sub_query) use ($search){
                $sub_query->orWhere('name','like','%' . $search . '%');
                $sub_query->orWhere('code','like','%' . $search . '%');
                $sub_query->orWhere('description','like','%' . $search . '%');
            });
        }
        
        if ($date_created){
            $query->where('created_at', '>=', date_convert($date_created));
        }
        $situations = $query->get();

        foreach ($situations as $key => $situation) {
            $situation->description = \Str::words(html_entity_decode(strip_tags($situation->description)));
            $count_comment_situation = CommentSituation::where('topic_id',$topic->id)->where('situation_id',$situation->id)->count();
            $situation->count_comment_situation = $count_comment_situation;
            $situation->created_at2 = Carbon::parse($situation->created_at)->format('d/m/Y');
            if (!empty($get_profile_like_situation) && in_array($situation->id, $get_profile_like_situation)) {
                $situation->check_like = 1;
            } else {
                $situation->check_like = 0;
            }
        }

        return response()->json([
            'situations' => $situations,
            'topic' => $topic
        ]);
    }

    public function dataSituationDetail($topic_id, $situation_id)
    {
        $profile = profile();
        $profile->profile_avatar = image_user($profile->avatar);
        
        $situation = Situation::find($situation_id);
        $situation->view = $situation->view + 1;
        $situation->save();
        $topic = Topic::find($topic_id,['id','name']);
        
        $created_at = Carbon::parse($situation->created_at)->format('H:s d/m/Y');
        $situation->created_at2 = $created_at;
        return response()->json([
            'situation' => $situation,
            'topic' => $topic,
            'profile' => $profile
        ]);
    }

    // DATA BÌNH LUẬN TÌNH HUỐNG
    public function dataCommentSituation($topic_id, $situation_id)
    {
        $comments = CommentSituation::select([
            'cs.*',
            \DB::raw("CONCAT_WS(' ',lastname, firstname) AS fullname"),
            'p.avatar',
        ])
            ->from('el_comment_situation AS cs')
            ->join('el_profile AS p', 'p.user_id', '=', 'cs.user_id')
            ->where('cs.situation_id', '=', $situation_id)
            ->orderBy('cs.id', 'desc')
            ->get();
        foreach ($comments as $key => $comment) {
            $profile = LikeCommentSituation::where('user_id',profile()->user_id)->whereNull('reply_comment_id')->first();
            $reply_comments = ReplyCommentSituation::where('comment_id',$comment->id)->get();
            if ($profile !== null && !empty(json_decode($profile->comment_id)) && in_array($comment->id, json_decode($profile->comment_id))) {
                $comment->check_like = 1;
            } else {
                $comment->check_like = 0;
            }
            if ($comment->user_id == profile()->user_id) {
                $comment->user_comment = 1;
            } else {
                $comment->user_comment = 0;
            }
            $comment->created_at2 = Carbon::parse($comment->created_at)->diffForHumans();
            $comment->profile_avatar = image_user($comment->avatar);
            if (!empty($reply_comments)) {
                $comment->reply_comments = $reply_comments;
                foreach ($comment->reply_comments as $key => $reply_comment) {
                    $profile_reply = ProfileView::where('user_id',$reply_comment->user_id)->first(['full_name','avatar']);
                    $profile_reply_like = LikeCommentSituation::where('user_id',profile()->user_id)->whereNull('comment_id')->first();
                    if ($profile_reply_like !== null && !empty(json_decode($profile_reply_like->reply_comment_id)) && in_array($reply_comment->id, json_decode($profile_reply_like->reply_comment_id))) {
                        $reply_comment->check_like = 1;
                    } else {
                        $reply_comment->check_like = 0;
                    }
                    if ($reply_comment->user_id == profile()->user_id) {
                        $comment->user_reply = 1;
                    } else {
                        $comment->user_reply = 0;
                    }
                    $reply_comment->created_at2 = Carbon::parse($reply_comment->created_at)->diffForHumans();
                    $reply_comment->profile_avatar = image_user($profile_reply->avatar);
                    $reply_comment->profile_full_name = $profile_reply->full_name;
                }
            }
        }
        // dd($comments);
        return response()->json([
            'comments' => $comments
        ]);
    }

    // like tình huống
    public function userLikeSituation($id_situation) 
    {
        $check_like = 0;
        $situation = Situation::where('id',$id_situation)->first(); 
        $profile = LikeSituation::where('user_id',profile()->user_id)->first();
        if ($profile == null) {
            $check_like = 1;
            $profile = new LikeSituation;
            $set_profile_like_situation = [$id_situation];
            $profile->situation_id = json_encode($set_profile_like_situation);
            $profile->user_id = profile()->user_id;
            $profile->save();
            $like = $situation->like + 1;
            $situation->like = $like;
            $situation->save();
            return json_result([
                'view_like'=>$situation->like,
                'check_like'=>$check_like,
            ]);
        }
        $get_profile_like_situation = json_decode($profile->situation_id);
        if (($key = array_search($id_situation, $get_profile_like_situation)) !== false) {
            unset($get_profile_like_situation[$key]);
            $newarray = array_values($get_profile_like_situation);
            $profile->situation_id = json_encode($newarray);
            $like = $situation->like - 1;
        } else {
            array_push($get_profile_like_situation, $id_situation);
            $profile->situation_id = json_encode($get_profile_like_situation);
            $like = $situation->like + 1;
            $check_like = 1;
        }     
        $profile->save();   
        $situation->like = $like;
        $situation->save();
        return json_result([
            'view_like' => $situation->like,
            'check_like'=> $check_like,
        ]);
    }

    // like bình luận tình huống
    public function userLikeComment(Request $request) 
    {
        $check_like = 0;
        $id_comment_situation = $request->comment_id;
        $comment_situation = CommentSituation::where('id',$id_comment_situation)->first(); 
        $profile = LikeCommentSituation::where('user_id',profile()->user_id)->whereNull('reply_comment_id')->first();
        if ($profile == null) {
            $check_like = 1;
            $profile = new LikeCommentSituation;
            $set_profile_like_comment_situation = [$id_comment_situation];
            $profile->comment_id = json_encode($set_profile_like_comment_situation);
            $profile->user_id = profile()->user_id;
            $profile->save();

            $like_comment = $comment_situation->like_comment + 1;
            $comment_situation->like_comment = $like_comment;
            $comment_situation->save();
            return json_result([
                'view_like'=>$comment_situation->like_comment,
                'check_like'=>$check_like,
            ]);
        }
        $get_profile_like_comment_situation = json_decode($profile->comment_id);
        if (($key = array_search($id_comment_situation, $get_profile_like_comment_situation)) !== false) {
            unset($get_profile_like_comment_situation[$key]);
            $newarray = array_values($get_profile_like_comment_situation);
            $profile->comment_id = json_encode($newarray);
            $like_comment = $comment_situation->like_comment - 1;
        } else {
            array_push($get_profile_like_comment_situation, $id_comment_situation);
            $profile->comment_id = json_encode($get_profile_like_comment_situation);
            $like_comment = $comment_situation->like_comment + 1;
            $check_like = 1;
        }     
        $profile->save();   
        $comment_situation->like_comment = $like_comment;
        $comment_situation->save();
        return json_result([
            'view_like' => $comment_situation->like_comment,
            'check_like'=> $check_like,
        ]);
    }

    // USER like phản hồi bình luận tình huống
    public function userLikeReply(Request $request) 
    {
        $check_like = 0;
        $id_comment_situation = $request->reply_id;
        $comment_situation = ReplyCommentSituation::where('id',$id_comment_situation)->first(); 
        $profile = LikeCommentSituation::where('user_id',profile()->user_id)->whereNull('comment_id')->first();
        if ($profile == null) {
            $check_like = 1;
            $profile = new LikeCommentSituation;
            $set_profile_like_comment_situation = [$id_comment_situation];
            $profile->reply_comment_id = json_encode($set_profile_like_comment_situation);
            $profile->user_id = profile()->user_id;
            $profile->save();

            $comment_situation->like = $comment_situation->like + 1;
            $comment_situation->save();
            return json_result([
                'view_like'=>$comment_situation->like,
                'check_like'=>$check_like,
            ]);
        }
        $get_profile_like_comment_situation = json_decode($profile->reply_comment_id);
        if (($key = array_search($id_comment_situation, $get_profile_like_comment_situation)) !== false) {
            unset($get_profile_like_comment_situation[$key]);
            $newarray = array_values($get_profile_like_comment_situation);
            $profile->reply_comment_id = json_encode($newarray);
            $like = $comment_situation->like - 1;
        } else {
            array_push($get_profile_like_comment_situation, $id_comment_situation);
            $profile->reply_comment_id = json_encode($get_profile_like_comment_situation);
            $like = $comment_situation->like + 1;
            $check_like = 1;
        }     
        $profile->save();   
        $comment_situation->like = $like;
        $comment_situation->save();
        return json_result([
            'view_like' => $comment_situation->like,
            'check_like'=> $check_like,
        ]);
    }

    // USER BÌNH LUẬN TÌNH HUỐNG
    public function userCommentSituation(Request $request) 
    {
        $comment = $request->comment;
        // dd($request);
        if (strpos($comment, 'sex') !== false || strpos($comment, 'xxx') !== false || strpos($comment, 'địt') !== false){
            return json_result([
                'message' => 'Nội dung có từ nhạy cảm',
                'status' => 'error',
            ]);
        }else{
            if ($request->comment_id == null) {
                $model = new CommentSituation();
                $model->situation_id = $request->id;
                $model->topic_id = $request->topic_id;
                $model->user_id = profile()->user_id;
                $model->comment = $comment;
                $model->save();
            }else{
                $comment = CommentSituation::findOrFail($request->comment_id);
                $comment->comment = $this->comment;
                $comment->save();
            }
            return json_result([
                'status' => 'success',
            ]);
        }
    }

    //USER REPLY BÌNH LUẬN
    function userReplyCommentSituation(Request $request) 
    {
        $reply_comment = $request->replyComment;
        if (strpos($reply_comment, 'sex') !== false || strpos($reply_comment, 'xxx') !== false || strpos($reply_comment, 'địt') !== false){
            return json_result([
                'message' => 'Nội dung có từ nhạy cảm',
                'status' => 'error',
            ]);
        }else{
            if ($request->reply_comment_id == null) {
                $model = new ReplyCommentSituation();
                $model->comment_id = $request->comment_id;
                $model->user_id = profile()->user_id;
                $model->comment = $reply_comment;
                $model->save();
            }else{
                $reply_comment = ReplyCommentSituation::findOrFail($request->reply_comment_id);
                $reply_comment->comment = $reply_comment;
                $reply_comment->save();
            }
            return json_result([
                'status' => 'success',
            ]);
        }
    }

    // USER XÓA BÌNH LUẬN
    function userDeleteCommentSituation(Request $request)
    {
        if($request->type == 0) {
            CommentSituation::where('id',$request->id)->delete();
            ReplyCommentSituation::where('comment_id',$request->id)->delete();
        } else {
            ReplyCommentSituation::where('id',$request->id)->delete();
        }
        return json_result([
            'status' => 'success',
        ]);
    }
}
