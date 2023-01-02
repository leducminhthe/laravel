<?php

namespace App\Http\Controllers\React;

use App\Models\Profile;
use App\Models\ProfileView;
use App\Scopes\CompanyScope;
use FFMpeg\FFMpeg;
use FFMpeg\FFProbe;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\InteractionHistory;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Jenssegers\Agent\Agent;
use Modules\DailyTraining\Entities\DailyTrainingCategory;
use Modules\DailyTraining\Entities\DailyTrainingUserCommentVideo;
use Modules\DailyTraining\Entities\DailyTrainingUserLikeCommentVideo;
use Modules\DailyTraining\Entities\DailyTrainingUserLikeVideo;
use Modules\DailyTraining\Entities\DailyTrainingUserViewVideo;
use Modules\DailyTraining\Entities\DailyTrainingVideo;
use Modules\DailyTraining\Entities\DailyTrainingUserSaveVideo;
use Pion\Laravel\ChunkUpload\Exceptions\UploadMissingFileException;
use Pion\Laravel\ChunkUpload\Handler\HandlerFactory;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;
use Carbon\Carbon;
use Modules\UserPoint\Entities\UserPointResult;
use Modules\Promotion\Entities\PromotionUserPoint;
use Modules\Promotion\Entities\PromotionLevel;
use Modules\UserPoint\Entities\UserPointSettings;
use Modules\Notify\Entities\Notify;
use Modules\AppNotification\Helpers\AppNotification;
use Modules\DailyTraining\Entities\DailyTrainingSettingScoreLike;
use Modules\DailyTraining\Entities\DailyTrainingSettingScoreViews;
use Modules\DailyTraining\Entities\DailyTrainingSettingScoreComment;

class DailyTrainingReactController extends Controller
{
    public function index()
    {
        return view('react.daily_training.index');
    }

    // LẤY DANH MỤC HỌC LIỆU VIDEO
    public function categoryDailyTraining(Type $var = null)
    {
        DailyTrainingCategory::addGlobalScope(new CompanyScope());
        $categories = DailyTrainingCategory::where('id', '!=', 1)->get(['id','name']);
        return response()->json([
            'categories' => $categories,
        ]);
    }

    // DỮ LIỆU HỌC LIỆU ĐÀO TẠO
    public function dataDailyTraining(Request $request) {
        DailyTrainingVideo::addGlobalScope(new CompanyScope());
        $type = $request->type;
        $search = $request->get('search');
        $category = $request->get('searchCate');
        $query = DailyTrainingVideo::query();
        $query->select([
            'el_daily_training_video.id',
            'el_daily_training_video.avatar',
            'el_daily_training_video.name',
            'el_daily_training_video.created_by',
            'el_daily_training_video.created_at',
            'el_daily_training_video.view',
            'el_daily_training_video.status',
            'el_daily_training_video.approve',
        ]);
        $query->from('el_daily_training_video');
        if ($type == 1) {
            $query->where('el_daily_training_video.created_by', '=', profile()->user_id);
        } else if ($type == 2) {
            $query->join('el_daily_user_save_video as b','b.video_id','=','el_daily_training_video.id');
            $query->where('b.user_id', profile()->user_id);
        }
        if ($search) {
            $query->where(function ($subquery) use ($search) {
                $subquery->orWhere('el_daily_training_video.hashtag', 'like', '%'. $search .'%');
                $subquery->orWhere('el_daily_training_video.name', 'like', '%'. $search .'%');
            });
        }
        if($category) {
            $query->where('el_daily_training_video.category_id',$category);
        }
        $query->where(function($sub_query) {
            $sub_query->orWhere('el_daily_training_video.created_by', '=', profile()->user_id);
            $sub_query->orWhere('el_daily_training_video.approve',1);
        });
        $query->where('status', '!=', 0);
        $query->orderByDesc('el_daily_training_video.id');

        $videos = $query->paginate(8);
        foreach ($videos as $key => $video) {
            $profile = ProfileView::where('user_id', $video->created_by)->first(['avatar', 'full_name']);
            $video->avatar = $video->avatar ? image_file($video->avatar) : asset('images/image_default.webp');
            $video->profileAvatar = image_user($profile->avatar);
            $video->checkCreatedBy = profile()->user_id == $video->created_by ? 1 : 0;
            $video->profileName = $profile->full_name;
            $video->created_at2 = Carbon::parse($video->created_at)->diffForHumans();
            $video->iconHeart = asset('themes/mobile/img/heart.png');
            if($video->status == 1 && $video->approve == 1) {
                $video->check_approve = asset('images/approve_daily.png');
            } else {
                $video->check_approve = asset('images/deny_daily.png');
            }
        }

        return response()->json([
            'videos' => $videos,
        ]);
    }

    // CHI TIẾT HỌC LIỆU VIDEO
    public function detailDailyTraining($id){
        DailyTrainingVideo::updateView($id);

        $agent = new Agent();
        $user_view = new DailyTrainingUserViewVideo();
        $user_view->video_id = $id;
        $user_view->user_id = profile()->user_id;
        $user_view->time_view = date('Y-m-d H:i:s');
        $user_view->device = $agent->deviceType();
        $user_view->save();

        $count_like = DailyTrainingUserLikeVideo::query()
            ->where('video_id', '=', $id)
            ->where('like', '=', 1)
            ->count();

        $count_dislike = DailyTrainingUserLikeVideo::query()
            ->where('video_id', '=', $id)
            ->where('dislike', '=', 1)
            ->count();

        $video = DailyTrainingVideo::find($id);
        $video->linkPlay = $video->getLinkPlay();
        $video->countLike = $count_like;
        $video->countDislike = $count_dislike;

        $profile = ProfileView::where('user_id', $video->created_by)->first(['avatar', 'full_name']);
        $video->profileAvatar = image_user($profile->avatar);
        $video->profileName = $profile->full_name;
        $video->created_at2 = Carbon::parse($video->created_at)->diffForHumans();

        $like = DailyTrainingVideo::checkLike($video->id, 1);
        $dislike = DailyTrainingVideo::checkLike($video->id, 2);
        $check_save = DailyTrainingUserSaveVideo::where('video_id', $id)->where('user_id', profile()->user_id)->exists();

        $video->like = $like;
        $video->dislike = $dislike;
        $video->check_save = $check_save ? 1 : 0;

        /*Lưu lịch sử tương tác của HV*/
        $interaction_history = InteractionHistory::where(['user_id' => profile()->user_id, 'code' => 'training_video'])->first();
        if($interaction_history){
            $interaction_history->number = ($interaction_history->number + 1);
            $interaction_history->save();
        }else{
            $interaction_history = new InteractionHistory();
            $interaction_history->user_id = profile()->user_id;
            $interaction_history->code = 'training_video';
            $interaction_history->name = 'Đào tạo video';
            $interaction_history->number = 1;
            $interaction_history->save();
        }

        $setting_views = DailyTrainingSettingScoreViews::where('category_id', $video->category_id)->get();
        foreach ($setting_views as $key => $setting_view) {
            $userpoint_reward_views = UserPointResult::where('ref', $setting_view->id)->where('user_id', $video->created_by)->where('type', 8)->where('item_id', $video->id)->where('setting_id', 1)->exists();
            if (($setting_view->from < $video->view && $setting_view->to > $video->view) && !$userpoint_reward_views) {
                $content_reward = 'Nhận điểm thưởng khi đạt đủ lượt xem video học liệu đào tạo: '. $video->name;
                $save_point_reward_view = new UserPointResult();
                $save_point_reward_view->user_id = $video->created_by;
                $save_point_reward_view->content = $content_reward;
                $save_point_reward_view->setting_id = 1;
                $save_point_reward_view->ref = $setting_view->id;
                $save_point_reward_view->point = $setting_view->score;
                $save_point_reward_view->item_id = $video->id;
                $save_point_reward_view->type = 8;
                $save_point_reward_view->type_promotion = 1;
                $save_point_reward_view->save();

                $user_point_reward_view = PromotionUserPoint::firstOrNew(['user_id' => $video->created_by]);
                $user_point_reward_view->point = (int)$user_point_reward_view->point + (int)$setting_view->score;
                $user_point_reward_view->level_id = PromotionLevel::levelUp($user_point_reward_view->point, $video->created_by);
                $user_point_reward_view->save();
            }
        }

        return response()->json([
            'video' => $video,
        ]);
    }

    // VIDEO CÙNG DANH MỤC
    public function relatedVideo($id)
    {
        $video = DailyTrainingVideo::find($id,['category_id','id']);
        $related_videos = DailyTrainingVideo::where('id','!=',$video->id)->where('category_id',$video->category_id)->get(['name','avatar','id','view','created_at']);
        foreach ($related_videos as $key => $related_video) {
            $related_video->avatar = $related_video->avatar ? image_file($related_video->avatar) : asset('images/image_default.jpg');
            $related_video->created_at2 = Carbon::parse($related_video->created_at)->diffForHumans();
        }
        return response()->json([
            'related_videos' => $related_videos,
        ]);
    }

    // LẤY TẤT CẢ BÌNH LUẬN CHI TIẾT VIDEO
    public function detailCommentDailyTraining($id)
    {
        $comments = DailyTrainingUserCommentVideo::where('video_id', '=', $id)->get();
        foreach($comments as $comment) {
            $like_comment = DailyTrainingUserCommentVideo::checkLikeComment($id, $comment->id, 1);
            $dislike_comment = DailyTrainingUserCommentVideo::checkLikeComment($id, $comment->id, 2);
            $count_like_comment = DailyTrainingUserLikeCommentVideo::countLikeOrDisLike($id, $comment->id, 1);
            $count_dislike_comment = DailyTrainingUserLikeCommentVideo::countLikeOrDisLike($id, $comment->id, 2);

            $profile = ProfileView::where('user_id', $comment->user_id)->first(['avatar', 'full_name']);
            $comment->profileAvatar = image_user($profile->avatar);
            $comment->profileName = $profile->full_name;
            $comment->created_at2 = Carbon::parse($comment->created_at)->diffForHumans();
            $comment->like_comment = $like_comment;
            $comment->dislike_comment = $dislike_comment;
            $comment->count_like_comment = $count_like_comment;
            $comment->count_dislike_comment = $count_dislike_comment;
        }
        $count_comment = $comments->count();
        return response()->json([
            'comments' => $comments,
            'count_comment' => $count_comment
        ]);
    }

    // USER BÌNH LUẬN VIDEO
    public function commentDailyTraining($id, Request $request){
        $this->validateRequest([
            'comment' => 'required',
        ], $request, ['comment' => trans("latraining.content")]);

        $content = strtolower($request->post('comment'));

        if (strpos($content, 'sex') !== false || strpos($content, 'xxx') !== false || strpos($content, 'địt') !== false){
            return json_result([
                'message' => 'Nội dung có từ phản cảm',
                'status' => 'warning',
            ]);
        }
        $user_comment = new DailyTrainingUserCommentVideo();
        $user_comment->video_id = $id;
        $user_comment->user_id = profile()->user_id;
        $user_comment->content = $request->post('comment');
        $user_comment->save();

        $video = DailyTrainingVideo::find($id, ['id','category_id','created_by','name']);
        $count_comment = DailyTrainingUserCommentVideo::where('video_id', $id)->count();
        $setting_comments = DailyTrainingSettingScoreComment::where('category_id', $video->category_id)->get();
        foreach ($setting_comments as $key => $setting_comment) {
            $userpoint_reward_comment = UserPointResult::where('ref', $setting_comment->id)->where('user_id', $video->created_by)->where('type', 8)->where('item_id', $video->id)->where('setting_id', 3)->exists();
            if (($setting_comment->from < $count_comment && $setting_comment->to > $count_comment) && !$userpoint_reward_comment) {
                $content_reward = 'Nhận điểm thưởng khi đạt đủ lượt bình luận video học liệu đào tạo: '. $video->name;
                $save_point_reward_comment = new UserPointResult();
                $save_point_reward_comment->user_id = $video->created_by;
                $save_point_reward_comment->content = $content_reward;
                $save_point_reward_comment->setting_id = 3;
                $save_point_reward_comment->ref = $setting_comment->id;
                $save_point_reward_comment->point = $setting_comment->score;
                $save_point_reward_comment->item_id = $video->id;
                $save_point_reward_comment->type = 8;
                $save_point_reward_comment->type_promotion = 1;
                $save_point_reward_comment->save();

                $user_point_reward_comment = PromotionUserPoint::firstOrNew(['user_id' => profile()->user_id]);
                $user_point_reward_comment->point = (int)$user_point_reward_comment->point + (int)$setting_comment->score;
                $user_point_reward_comment->level_id = PromotionLevel::levelUp($user_point_reward_comment->point, profile()->user_id);
                $user_point_reward_comment->save();
            }
        }

        json_result([
            'message' => 'Bình luận thành công',
            'status' => 'success',
        ]);
    }

    // USER LIKE / DISLIKE VIDEO
    public function likeDislikeVideoDailyTraining($id, Request $request)
    {
        $category_id = $request->category_id;
        $type = $request->type;
        $check = '';
        $user_like = DailyTrainingUserLikeVideo::firstOrNew(['video_id' => $id, 'user_id' => profile()->user_id]);
        $user_like->video_id = $id;
        $user_like->user_id = profile()->user_id;
        if($type == 1) {
            $like = DailyTrainingVideo::checkLike($id, $type);
            if($like) {
                $user_like->like = null;
                $user_like->dislike = null;
            } else {
                $user_like->like = 1;
                $user_like->dislike = null;
                $check = 'like-video';
            }
        } else {
            $dislike = DailyTrainingVideo::checkLike($id, $type);
            if($dislike) {
                $user_like->dislike = null;
                $user_like->like = null;
            } else {
                $user_like->dislike = 1;
                $user_like->like = null;
                $check = 'dislike-video';
            }
        }
        $user_like->save();
        $count_like = DailyTrainingUserLikeVideo::where('video_id', '=', $id)->where('like', '=', 1)->count();
        $count_dislike = DailyTrainingUserLikeVideo::where('video_id', '=', $id)->where('dislike', '=', 1)->count();

        $name_video = DailyTrainingVideo::find($id, ['name']);
        $userpoint_setting = UserPointSettings::where('pkey', 'user_like_daily_training')->where('item_id', $category_id)->where('item_type', 8)->where('pvalue','>',0)->first(['id','pvalue']);
        $userpoint_result = UserPointResult::where('setting_id', @$userpoint_setting->id)->where('user_id', profile()->user_id)->where('type', 8)->where('item_id', $id)->exists();
        if (!$userpoint_result && !empty($userpoint_setting)) {
            $subject = 'Điểm thưởng khi thích video';
            $content = 'Nhận điểm thưởng khi thích video: '. $name_video->name;

            $save_point = new UserPointResult();
            $save_point->user_id = profile()->user_id;
            $save_point->content = $content;
            $save_point->setting_id = $userpoint_setting->id;
            $save_point->point = $userpoint_setting->pvalue;
            $save_point->item_id = $id;
            $save_point->type = 8;
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
            'count_like' => $count_like,
            'count_dislike' => $count_dislike,
            'check' => $check
        ]);
    }

    // USER LIKE/ DISLIKE BÌNH LUẬN VIDEO
    public function likeDislikeCommentDailyTraining($id, Request $request)
    {
        $type = $request->type;
        $comment_id = $request->comment_id;
        $check = '';
        $user_like = DailyTrainingUserLikeCommentVideo::query()->firstOrNew(['video_id' => $id, 'comment_id' => $comment_id, 'user_id' => profile()->user_id]);
        if($type == 1) {
            $like_comment = DailyTrainingUserCommentVideo::checkLikeComment($id, $comment_id, $type);
            if($like_comment) {
                $user_like->like = null;
                $user_like->dislike = null;
            } else {
                $user_like->like = 1;
                $user_like->dislike = null;
                $check = 'user-like-comment-'.$comment_id;
            }
        } else {
            $dislike_comment = DailyTrainingUserCommentVideo::checkLikeComment($id, $comment_id, $type);
            if($dislike_comment) {
                $user_like->dislike = null;
                $user_like->like = null;
            } else {
                $user_like->dislike = 1;
                $user_like->like = null;
                $check = 'user-dislike-comment-'.$comment_id;
            }
        }
        $user_like->save();

        $count_like_comment = DailyTrainingUserLikeCommentVideo::where('comment_id', '=', $comment_id)->where('video_id', '=', $id)->where('like', '=', 1)->count();
        $count_dislike_comment = DailyTrainingUserLikeCommentVideo::where('comment_id', '=', $comment_id)->where('video_id', '=', $id)->where('dislike', '=', 1)->count();
        json_result([
            'check' => $check,
            'count_like_comment' => $count_like_comment,
            'count_dislike_comment' => $count_dislike_comment
        ]);
    }

    // LƯU VIDEO
    public function createVideoDailyTraining(Request $request)
    {
        $this->validateRequest([
            'name' => 'required',
            'hashtag'=>'required',
            'video' => 'required',
            'category' => 'required',
        ], $request, DailyTrainingVideo::getAttributeName());

        $video = $request->video;

        $cate_id = $request->category;
        $category = DailyTrainingCategory::find($cate_id);

        $model = new DailyTrainingVideo();
        $model->fill($request->all());
        $model->hashtag = '#'.str_replace(' ', '', $request->hashtag);
        $model->category_id = $category->id;
        $model->created_by = profile()->user_id;
        $model->updated_by = profile()->user_id;
        if ($cate_id == 1) {
            $model->status = 1;
        } else {
            $model->status = 0;
        }
        $model->approve = 2;
        $model->video = $video;

        $ffmpeg = FFMpeg::create([
            'ffmpeg.binaries'  => config('app.ffmpeg.binaries'),
            'ffprobe.binaries' => config('app.ffprobe.binaries'),
            'timeout'          => 3600,
            'ffmpeg.threads'   => 12,
        ]);
        $path_img = 'video_'.time().'.png';

        $storage = \Storage::disk('upload');
        $uploadPath = $storage->path($video);
        $ffmpeg->open($uploadPath)
            ->frame(\FFMpeg\Coordinate\TimeCode::fromSeconds(15))
            ->save($storage->path(date('Y/m/d').'/'.$path_img));

        $model->avatar = date('Y/m/d').'/'.$path_img;
        $model->save();

        /*Lưu lịch sử tương tác của HV*/
        $interaction_history = InteractionHistory::where(['user_id' => profile()->user_id, 'code' => 'training_video'])->first();
        if($interaction_history){
            $interaction_history->number = ($interaction_history->number + 1);
            $interaction_history->save();
        }else{
            $interaction_history = new InteractionHistory();
            $interaction_history->user_id = profile()->user_id;
            $interaction_history->code = 'training_video';
            $interaction_history->name = 'Đào tạo video';
            $interaction_history->number = 1;
            $interaction_history->save();
        }

        if($cate_id == 1) {
            return response()->json([
                'status' => 'success',
                'message' => 'Thành công',
            ]);
        } else {
            return response()->json([
                'status' => 'success',
                'message' => 'Thành công. Xin chờ duyệt',
            ]);
        }
    }

    // UPLOAD VIDEO VÀO STORAGE
    public function uploadVideoDailyTraining(Request $request)
    {
        $error_bag = [];
        try {

            $receiver = new FileReceiver('file', $request, HandlerFactory::classFromRequest($request));
            if ($receiver->isUploaded() === false) {
                throw new UploadMissingFileException();
            }

            $save = $receiver->receive();
            if ($save->isFinished()) {
                $save_file = $this->saveFile($save->getFile());
                if ($save_file) {
                    return response()->json(['path' => $save_file]);
                }
                return $this->response($this->errors);
            }

            $handler = $save->handler();

            return response()->json([
                "done" => $handler->getPercentageDone(),
                'status' => true
            ]);

        } catch (\Exception $e) {
            \Log::error($e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            array_push($error_bag, $e->getMessage());
            return $this->response($error_bag);
        }
    }

    protected function saveFile(UploadedFile $file) {
        $filename = $this->createFilename($file);
        $storage = \Storage::disk('upload');
        $new_path = $storage->putFileAs(date('Y/m/d'), $file, $filename);

        if (!$this->fileIsValid($storage->path($new_path))) {
            unlink($storage->path($new_path));
            return false;
        }

        return $new_path;
    }

    protected function createFilename(UploadedFile $file) {
        $filename = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        $new_filename = Str::slug(basename(substr($filename, 0, 50), "." . $extension)) .'-'. time() .'-'. Str::random(10) .'.' . $extension;
        return $new_filename;
    }

    protected function fileIsValid($file_path)
    {
        $file = new UploadedFile($file_path, basename($file_path));

        if (empty($file)) {
            array_push($this->errors, $this->error('file-empty'));
            return false;
        }

        if (! $file instanceof UploadedFile) {
            array_push($this->errors, $this->error('instance'));
            return false;
        }

        if ($file->getError() != UPLOAD_ERR_OK) {
            $msg = 'File failed to upload. Error code: ' . $file->getError();
            array_push($this->errors, $msg);
            return false;
        }

        // Bytes to MB
        $max_size = 2048;
        $file_size = $file->getSize();

        if ($max_size > 0) {
            if ($file_size > ($max_size * 1024 * 1024)) {
                array_push($this->errors, $this->error('size'));
                return false;
            }
        }

        return true;
    }

    protected function response($error_bag) {
        $response = count($error_bag) > 0 ? $error_bag : $this->success_response;
        return response()->json($response);
    }

    protected function error($error_type, $variables = [])
    {
        return trans('lfm.error-' . $error_type, $variables);
    }

    // USER XÓA VIDEO
    public function disableVideo(Request $request){
        $video = DailyTrainingVideo::find($request->id);
        $video->status = 0;
        $video->save();

        $userpoint_setting = UserPointSettings::where('pkey', 'daily_create')->where('item_id', $video->category_id)->where('pvalue','>',0)->where('item_type', 8)->first(['id']);
        $check_userpoint_result = UserPointResult::where('setting_id', @$userpoint_setting->id)->where('user_id', profile()->user_id)->where('type', 8)->where('item_id', $request->id)->first();
        if (isset($check_userpoint_result)) {
            $user_point = PromotionUserPoint::firstOrNew(['user_id' => $check_userpoint_result->user_id]);
            $user_point->point = (int)$user_point->point - (int)$check_userpoint_result->point;
            $user_point->level_id = PromotionLevel::levelUp($user_point->point, $check_userpoint_result->user_id);
            $user_point->save();
            UserPointResult::where('user_id', profile()->user_id)->where('type', 8)->where('item_id', $request->id)->delete();
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Xoá thành công',
        ]);
    }

    //DATA VIDEO LƯỢT XEM VÀ MỚI NHẤT
    public function dataDailyTrainingViewNew()
    {
        DailyTrainingVideo::addGlobalScope(new CompanyScope());
        $query = DailyTrainingVideo::query();
        $query->select([
            'id',
            'avatar',
            'name',
            'created_at',
            'view'
        ]);
        $query->where(function($sub_query) {
            $sub_query->orWhere('el_daily_training_video.status',1);
            $sub_query->orWhere('el_daily_training_video.approve',1);
        });

        $videos_view = $query->orderByDesc('view')->get()->take(2);
        $videos_view_array = $query->orderByDesc('view')->take(2)->pluck('id')->toArray();
        $videos_new = $query->whereNotIn('id', $videos_view_array)->orderByDesc('created_at')->get()->take(3);
        foreach ($videos_view as $key => $video_view) {
            $video_view->avatar = $video_view->avatar ? image_file($video_view->avatar) : asset('images/image_default.jpg');
            $video_view->created_at2 = Carbon::parse($video_view->created_at)->diffForHumans();
        }
        foreach ($videos_new as $key => $video_new) {
            $video_new->avatar = $video_new->avatar ? image_file($video_new->avatar) : asset('images/image_default.jpg');
            $video_new->created_at2 = Carbon::parse($video_new->created_at)->diffForHumans();
        }

        return response()->json([
            'videos_view' => $videos_view,
            'videos_new' => $videos_new
        ]);
    }

    // USER LƯU VIDEO
    public function userSaveVideo(Request $request)
    {
        $type = $request->type;
        if ($type == 1) {
            $save = new DailyTrainingUserSaveVideo();
            $save->user_id = profile()->user_id;
            $save->video_id = $request->id;
            $save->save();
        } else {
            DailyTrainingUserSaveVideo::where('user_id',profile()->user_id)->where('video_id', $request->id)->delete();
        }
        return response()->json([
            'status' => 'success',
            'message' => trans('laother.successful_save'),
        ]);
    }
}
