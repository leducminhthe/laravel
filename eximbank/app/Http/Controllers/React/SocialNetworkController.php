<?php

namespace App\Http\Controllers\React;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Models\ProfileView;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;
use Pion\Laravel\ChunkUpload\Handler\HandlerFactory;
use Pion\Laravel\ChunkUpload\Exceptions\UploadMissingFileException;
use App\Events\SocialNetWork;
use App\Events\SocialNetWorkComment;
use App\Events\SocialNetworkAddFriend;
use App\Events\SocialNetworkChat;
use App\Models\SocialNetworkItem;
use App\Models\SocialNetworkNew;
use App\Models\SocialNetworkUserLikeNew;
use App\Models\SocialNetworkUserComment;
use App\Models\SocialNetworkUserAddFriend;
use App\Models\SocialNetworkUserLikeComment;
use App\Models\SocialNetworkUserReplyComment;
use App\Models\SocialNetworkUserLikeReplyComment;
use App\Models\SocialNetworkUserChat;
use App\Models\SocialNetworkGroupChat;
use App\Models\SocialNetwotkImageCover;
use App\Models\SocialNetworkUserStory;
use App\Models\SocialNetWorkUserWorkPlace;
use App\Models\SocialNetworkUserStudy;
use App\Models\SocialNetworkUserCity;
use App\Models\SocialNetworkUserCountry;
use App\Models\SocialNetworkUserChooseFriendSeeNew;
use App\Models\SocialNetworkNoty;

class SocialNetworkController extends Controller
{
    public function index()
    {
        return view('react.social_network.index');
    }

    // DỮ LIỆU NGƯỜI DÙNG
    public function dataAuth(Request $request)
    {
        if ($request->userId){
            $profile = ProfileView::where('user_id', $request->userId)->first(['user_id','firstname','avatar']);
            $profile->avatar = image_user($profile->avatar);
        } else {
            $profile = profile();
            $profile->avatar = image_user($profile->avatar);
        }
        return response()->json([
            'profile' => $profile
        ]);
    }

    // DANH SÁCH NHỮNG NGƯỜI CÓ THỂ BIẾT
    public function dataListFriendUserKnow(Request $request)
    {
        $user_id = profile()->user_id;
        $not_accept = [1, 2, profile()->user_id];

        $model = SocialNetworkUserAddFriend::query();
        $model->where(function ($sub) use ($user_id){
            $sub->orWhere('user_id', $user_id);
            $sub->orWhere('friend_id', $user_id);
        });
        $model->where('status', 1);
        $get_list_friends = $model->get();
        $list_friends = [];
        foreach ($get_list_friends as $key => $item) {
            if($item->user_accept == profile()->user_id) {
                $list_friends[] = $item->user_id;
            } else {
                $list_friends[] = $item->friend_id;
            }
        }
        $list_friends_user_know = ProfileView::inRandomOrder()->whereNotIn('user_id', $not_accept)->whereNotIn('user_id', $list_friends)->take(3)->get(['id','firstname','avatar']);
        foreach ($list_friends_user_know as $key => $list_friend) {
            $list_friend->avatar = image_user($list_friend->avatar);
        }
        return response()->json([
            'list_friends_user_know' => $list_friends_user_know
        ]);
    }

    //LIST FRIEND USER
    public function dataListFriend($userId)
    {
        $model = SocialNetworkUserAddFriend::query();
        $model->select([
            'user_id',
            'friend_id',
            'user_accept',
        ]);
        $model->where(function ($sub) use ($userId){
            $sub->orWhere('user_id', $userId);
            $sub->orWhere('friend_id', $userId);
        });
        $model->where('status', 1);
        $list_friends = $model->paginate(20);
        foreach ($list_friends as $key => $list_friend) {
            if ($list_friend->user_accept == $userId) {
                $profile_user = ProfileView::where('user_id', $list_friend->user_id)->first(['firstname','avatar']);
                $id_chat = $list_friend->user_id;
            } else {
                $profile_user = ProfileView::where('user_id', $list_friend->friend_id)->first(['firstname','avatar']);
                $id_chat = $list_friend->friend_id;
            }
            $list_friend->avatar = image_user($profile_user->avatar);
            $list_friend->user_name = $profile_user->firstname;
            $list_friend->id_chat = $id_chat;
        }
        return response()->json([
            'list_friends' => $list_friends
        ]);
    }

    // DỮ LIỆU TẤT CẢ BÀI VIẾT
    public function dataNews(Request $request)
    {
        if($request->type == 0) { //type == 0 => trong chi tiết người dùng
            $authUser = $request->authUser;
            $news = SocialNetworkNew::orderBy('id','DESC')->where('user_id', ($authUser ? $authUser : profile()->user_id))->paginate(15);
            foreach ($news as $new) {
                $check_like = SocialNetworkUserLikeNew::where(['social_network_new_id' => $new->id, 'user_id' => profile()->user_id])->first();
                if(isset($check_like)) {
                    $new->check_like = $check_like->type;
                    $new->id_like_new = $check_like->social_network_new_id;
                } else {
                    $new->check_like = '';
                    $new->id_like_new = '';
                }
                $new->avatar = image_user($new->avatar);
                $new->created_at2 = Carbon::parse($new->created_at)->diffForHumans();
                if($new->type == 1) {
                    $images = SocialNetworkItem::where('social_network_new_id', $new->id)->get(['id','image']);
                    if(!empty($images)){
                        $new->images = $images;
                        foreach ($images as $key => $item) {
                            $item->image = image_file($item->image);
                        }
                    }
                } else if ($new->type == 2) {
                    $video = SocialNetworkItem::where('social_network_new_id', $new->id)->first();
                    if(isset($video)){
                        $new->video = $video->getLinkPlay();
                    }
                }
            }
        } else { // type == 1 => trang chủ 
            $news = SocialNetworkNew::orderBy('id','DESC')->paginate(15);
            foreach ($news as $new) {
                if ($new->status == 3 && $new->user_id != profile()->user_id) {
                    $new->show = 0;
                } else if ($new->status == 2 && $new->user_id != profile()->user_id) {
                    $get_choose_friend = SocialNetworkUserChooseFriendSeeNew::where('social_network_id', $new->id)->pluck('friend_id')->toArray();
                    if(!in_array(profile()->user_id, $get_choose_friend)) {
                        $new->show = 0;
                    }
                } else if ($new->status == 1 && $new->user_id != profile()->user_id) {
                    $user_new_id = $new->user_id;
                    $user_id = profile()->user_id;
                    $model = SocialNetworkUserAddFriend::query();
                    $model->where(function ($sub) use ($user_new_id){
                        $sub->orWhere('user_id', $user_new_id);
                        $sub->orWhere('friend_id', $user_new_id);
                    });
                    $model->where(function ($sub) use ($user_id){
                        $sub->where('user_id', $user_id);
                        $sub->orWhere('friend_id', $user_id);
                    });
                    $model->where('status', 1);
                    $friend = $model->exists();

                    if(!$friend) {
                        $new->show = 0;
                    }
                } 
                $check_like = SocialNetworkUserLikeNew::where(['social_network_new_id' => $new->id, 'user_id' => profile()->user_id])->first();
                if(isset($check_like)) {
                    $new->check_like = $check_like->type;
                    $new->id_like_new = $check_like->social_network_new_id;
                } else {
                    $new->check_like = '';
                    $new->id_like_new = '';
                }
                $new->avatar = image_user($new->avatar);
                $new->created_at2 = Carbon::parse($new->created_at)->diffForHumans();
                if($new->type == 1) {
                    $images = SocialNetworkItem::where('social_network_new_id', $new->id)->get(['id','image']);
                    if(!empty($images)){
                        $new->images = $images;
                        foreach ($images as $key => $item) {
                            $item->image = image_file($item->image);
                        }
                    }
                } else if ($new->type == 2) {
                    $video = SocialNetworkItem::where('social_network_new_id', $new->id)->first();
                    if(isset($video)){
                        $new->video = $video->getLinkPlay();
                    }
                }
            }
        }
        return response()->json([
            'news' => $news
        ]);
    }

    // UPLOAD VIDEO 
    public function uploadVideo(Request $request)
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
                    $storage = \Storage::disk('local');
                    $file = encrypt_array([
                        'path' => $storage->path('uploads/' . $save_file),
                    ]);
                    return response()->json([
                        'path' => $save_file,
                        'src_video' => route('stream.video', [$file])
                    ]);
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
    // END UPLOAD VIDEO

    // UPLOAD HÌNH  ẢNH 
    public function uploadImage(Request $request)
    {
        $file = $request->file;
        $filename = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        $new_filename = Str::slug(basename(substr($filename, 0, 50), "." . $extension)) .'-'. time() .'-'. Str::random(10) .'.' . $extension;
        $storage = \Storage::disk('upload');
        $new_path = $storage->putFileAs(date('Y/m/d'), $file, $new_filename);
        return response()->json([
            'new_path' => $new_path
        ]);
    }

    // THÊM BÀI VIẾT
    public function addNew(Request $request)
    {
        $this->validateRequest([
            'titleNew' => 'required',
        ], $request, [
            'titleNew' => trans("latraining.content")
        ]);
        
        broadcast(new SocialNetWork(profile()->user_id, $request));
        return response()->json([
            'status' => 'success'
        ]);
    }

    // LIKE BÀI VIẾT
    public function likeNew(Request $request)
    {
        $check_like = SocialNetworkUserLikeNew::where(['social_network_new_id' => $request->id, 'user_id' => profile()->user_id])->exists();
        $model = SocialNetworkNew::find($request->id);
        if (!$check_like) {
            $model->total_like = $model->total_like + 1;
            $model->save();
        }
        
        $like = SocialNetworkUserLikeNew::firstOrNew(['social_network_new_id' => $request->id, 'user_id' => profile()->user_id]);
        $like->social_network_new_id = $request->id;
        $like->type = $request->type;
        $like->user_id = profile()->user_id;
        $like->save();

        return response()->json([
            'status' => 'success',
            'total_like' => $model->total_like
        ]);
    }

    // HIỆN DỮ LIỆU BÌNH LUẬN
    public function showComment($id)
    {
        $comments = SocialNetworkUserComment::where('social_network_new_id', $id)->orderBy('id','DESC')->paginate(7);
        foreach ($comments as $key => $comment) {
            $check_like = SocialNetworkUserLikeComment::where(['comment_id'=> $comment->id, 'user_id' => profile()->user_id])->first();
            $comment->check_like = isset($check_like) ? $check_like->type : '';
            $comment->avatar = image_user($comment->avatar);
            $comment->id_like_comment = isset($check_like) ? $check_like->comment_id : '';
        }
        return response()->json([
            'comments' => $comments,
        ]);
    }

    // USER BÌNH LUẬN
    public function userComment(Request $request)
    {
        $profile = ProfileView::where('user_id', profile()->user_id)->first(['avatar','firstname']);

        $save_comment = new SocialNetworkUserComment();
        $save_comment->user_id = profile()->user_id;
        $save_comment->social_network_new_id = $request->id;
        $save_comment->comment = $request->comment;
        $save_comment->avatar = $profile->avatar ? $profile->avatar : asset('/images/design/user_50_50.png');
        $save_comment->user_name = $profile->firstname;
        $save_comment->save();

        $model = SocialNetworkNew::find($request->id);
        $model->total_comment = $model->total_comment + 1;
        $model->save();

        $data = new \stdClass();
        $data->id_comment = $save_comment->id;
        $data->avatar = $profile->avatar ? image_user($profile->avatar) : asset('/images/design/user_50_50.png');
        $data->user_name = $profile->firstname;
        $data->comment = $request->comment;

        event(new SocialNetWorkComment(profile()->user_id, $data));
        return response()->json([
            'status' => 'success',
            'total_comment' => $model->total_comment
        ]);
    }

    // USER THÊM BẠN
    public function userAddFriend(Request $request)
    {
        $profile = ProfileView::where('user_id', profile()->user_id)->first(['avatar','firstname']);

        $save_friend = new SocialNetworkUserAddFriend();
        $save_friend->user_id = profile()->user_id;
        $save_friend->friend_id = $request->user_id;
        $save_friend->save();

        $save_noty = new SocialNetworkNoty();
        $save_noty->user_1 = profile()->user_id;
        $save_noty->user_2 = $request->user_id;
        $save_noty->noty = 'gửi cho bạn lời mời kết bạn';
        $save_noty->type = 0;
        $save_noty->status = 1;
        $save_noty->save();

        $data = new \stdClass();
        $data->created_at = Carbon::parse($save_friend->created_at)->diffForHumans();
        $data->avatar = $profile->avatar ? image_user($profile->avatar) : asset('/images/design/user_50_50.png');
        $data->user_name = $profile->firstname;
        $data->friend_id = $request->user_id;
        $data->type = 0;
        $data->status = 1;
        $data->id = $save_noty->id;
        $data->noty = 'gửi cho bạn lời mời kết bạn';

        broadcast(new SocialNetworkAddFriend(profile()->user_id, $data));
        return response()->json([
            'status' => 'success',
        ]);
    }

    // USER ĐỒNG Ý KẾT BẠN
    public function acceptAddFriend(Request $request)
    {
        if($request->type == 1) {
            $model = SocialNetworkUserAddFriend::where('friend_id', profile()->user_id)->where('user_id', $request->user_id)->first();
            $model->status = $request->type;
            $model->user_accept = profile()->user_id;
            $model->save();

            $model = SocialNetworkNoty::find($request->id);
            $model->status = 1;
            $model->save();
        } else {
            $model = SocialNetworkUserAddFriend::where('friend_id', profile()->user_id)->where('user_id', $request->user_id)->delete();
        }
        
        return response()->json([
            'status' => 'success',
        ]);
    }

    // DỮ LIỆU THÔNG BÁO
    public function dataNoty(Request $request)
    {
        $model = SocialNetworkNoty::where('user_2', profile()->user_id)->get();
        foreach ($model as $key => $item) {
            $profile = ProfileView::where('user_id', $item->user_1)->first(['avatar','firstname']);
            $item->user_name = $profile->firstname;
            $item->avatar_ = image_user($profile->avatar);
            $item->created_at2 = Carbon::parse($item->created_at)->diffForHumans();
            $item->user_id = $item->user_1;
        }
        return response()->json([
            'noties' => $model,
        ]);
    }

    // USER THÍCH BÌNH LUẬN
    public function likeComment(Request $request)
    {
        $save = SocialNetworkUserLikeComment::firstOrNew(['user_id' => profile()->user_id, 'comment_id' => $request->id]);
        $save->user_id = profile()->user_id;
        $save->comment_id = $request->id;
        $save->type = $request->typeLike;
        $save->save();

        return response()->json([
            'status' => 'success',
        ]);
    }

    // PHẢN HỒI BÌNH LUẬN 
    public function replyComment(Request $request)
    {
        $profile = ProfileView::where('user_id', profile()->user_id)->first(['avatar','firstname']);

        $save = new SocialNetworkUserReplyComment();
        $save->user_id = profile()->user_id;
        $save->comment_id = $request->comment_id;
        $save->reply = $request->reply;
        $save->avatar = $profile->avatar ? $profile->avatar : asset('/images/design/user_50_50.png');
        $save->user_name = $profile->firstname;
        $save->save();

        $comment = SocialNetworkUserComment::where('social_network_new_id', $request->data_id)->first();
        $comment->total_reply =  $comment->total_reply + 1;
        $comment->save();

        return response()->json([
            'status' => 'success',
        ]);
    }

    // DỮ LIỆU PHẢN HỒI BÌNH LUẬN
    public function dataReplyComment(Request $request)
    {
        $reply = SocialNetworkUserReplyComment::where('comment_id', $request->comment_id)->get();
        foreach ($reply as $key => $item) {
            $check_like = SocialNetworkUserLikeReplyComment::where(['reply_id'=> $item->id, 'user_id' => profile()->user_id])->first();
            $item->check_like = isset($check_like) ? $check_like->type : '';
            $item->avatar = image_user($item->avatar);
            $comment->id_like_reply = isset($check_like) ? $check_like->reply_id: '';
        }

        return response()->json([
            'reply' => $reply,
        ]);
    }

    // USER THÍCH PHẢN HỒI
    public function likeReplyComment(Request $request)
    {
        $save = SocialNetworkUserLikeReplyComment::firstOrNew(['user_id' => profile()->user_id, 'reply_id' => $request->id]);
        $save->user_id = profile()->user_id;
        $save->reply_id = $request->id;
        $save->type = $request->typeLike;
        $save->save();

        return response()->json([
            'status' => 'success',
        ]);
    }

    // CHAT
    public function chat(Request $request)
    {
        if($request->hasFile('chat') && $request->type == 1){ 
            $file = $request->file('chat');
            $filename = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $new_filename = Str::slug(basename(substr($filename, 0, 50), "." . $extension)) .'-'. time() .'-'. Str::random(10) .'.' . $extension;
            $storage = \Storage::disk('upload');
            $new_path = $storage->putFileAs(date('Y/m/d'), $file, $new_filename);

            $data = new \stdClass();
            $data->id_chat = $request->id_chat;
            $data->chat = $new_path;
            $data->send = $request->send;
            $data->type = $request->type;
            broadcast(new SocialNetworkChat(profile()->user_id, $data))->toOthers();
            return response()->json([
                'new_path' => image_file($new_path),
            ]);
        } else if ($request->type == 2) {
            $data = new \stdClass();
            $data->id_chat = $request->id_chat;
            $data->chat = asset('images/like.png');
            $data->send = $request->send;
            $data->type = $request->type;
            broadcast(new SocialNetworkChat(profile()->user_id, $data))->toOthers();
            return response()->json([
                'like' => asset('images/like.png'),
            ]);
        } else {
            broadcast(new SocialNetworkChat(profile()->user_id, $request))->toOthers();
        }
        return response()->json([
            'status' => 'success',
        ]);
    }

    // DỮ LIỆU CHAT
    public function dataChat(Request $request) {
        // $date_now = date('Y-m-d');
        $user_1 = $request->auth;
        $user_2 = $request->userChat;

        $model = SocialNetworkGroupChat::query();
        $model->where(function ($sub) use ($user_1){
            $sub->orWhere('user_1', $user_1);
            $sub->orWhere('user_2', $user_1);
        });
        $model->where(function ($sub) use ($user_2){
            $sub->where('user_2', $user_2);
            $sub->orWhere('user_1', $user_2);
        });
        $group_chat = $model->first();

        if(isset($group_chat)) {
            $get_messages = SocialNetworkUserChat::where('group_id', $group_chat->id)->get();
            foreach($get_messages as $get_message) {
                if($get_message->type == 1) {
                    $get_message->chat = image_file($get_message->chat);
                }
            }
        } else {
            $get_messages = [];
        }
        
        return response()->json([
            'messages' => $get_messages,
            'group_chat' => isset($group_chat) ? $group_chat->id : '',
        ]);
    }

    // XÓA CHAT
    public function deleteChat(Request $request) {
        $model = SocialNetworkUserChat::find($request->idMessage);
        $get_messages = json_decode($model->chat);
        $messages = array_except($get_messages, [$request->key]);
        $messages = array_values($messages);
        $model->update([
            'chat' => json_encode($messages),
        ]);
        return response()->json([
            'status' => 'success',
        ]);
    }

    //LƯU ẢNH BÌA TRANG CÁ NHÂN
    public function saveImageCover(Request $request)
    {
        
        $file = $request->image_cover;
        $filename = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        $new_filename = Str::slug(basename(substr($filename, 0, 50), "." . $extension)) .'-'. time() .'-'. Str::random(10) .'.' . $extension;
        $storage = \Storage::disk('upload');
        $new_path = $storage->putFileAs(date('Y/m/d'), $file, $new_filename);

        $save = SocialNetwotkImageCover::firstOrNew(['user_id' => $request->userId]);
        $save->user_id = $request->userId;
        $save->image_cover = $new_path;
        $save->save();

        return response()->json([
            'status' => 'success',
        ]);
    }

    // DỮ LIỆU ẢNH BÌA
    public function dataImageCover($userId)
    {
        $get_image_cover = SocialNetwotkImageCover::where('user_id', $userId)->first();
        if (isset($get_image_cover)) {
            $image_cover = image_file($get_image_cover->image_cover);
        } else {
            $image_cover = asset('images/image_default.jpg');
        }
        return response()->json([
            'image_cover' => $image_cover,
        ]);
    }

    // LƯU TIỂU SỬ
    public function saveStory(Request $request)
    {
        $save = SocialNetworkUserStory::firstOrNew(['user_id' => $request->userId]);
        $save->user_id = $request->userId;
        $save->story = $request->story;
        $save->save();

        return response()->json([
            'status' => 'success',
        ]);
    }

    // DỮ LIỆU TIỂU SỬ
    public function dataStory($userId)
    {
        $get_story = SocialNetworkUserStory::where('user_id', $userId)->first();
        $story = $get_story->story;
        return response()->json([
            'story' => $story,
        ]);
    }

    // LƯU ĐỊA ĐIỂM LÀM VIỆC
    public function saveWorkPlace(Request $request)
    {
        $save = SocialNetWorkUserWorkPlace::firstOrNew(['user_id' => profile()->user_id, 'id' => $request->idWorkPlace]);
        $save->user_id = profile()->user_id;
        $save->company = $request->company;
        $save->position = $request->position;
        $save->city = $request->cityWork;
        $save->description = $request->description;
        $save->status = $request->workNow == true ? 1 : 0;
        $save->year_start = $request->yearStartWork;
        $save->year_end = $request->yearEndWork;
        $save->type = $request->typeWorkPlace;
        $save->save();

        return response()->json([
            'status' => 'success',
        ]);
    }

    // XÓA ĐỊA ĐIỂM LÀM VIỆC
    public function deleteWorkPlace(Request $request)
    {
        $model = SocialNetWorkUserWorkPlace::find($request->idWorkPlace)->delete();

        return response()->json([
            'status' => 'success',
        ]);
    }

    // DỮ LIỆU NƠI LÀM VIỆC
    public function dataWorkPlace($userId, $type)
    {
        if($type == 0) {
            $get_work_place = SocialNetWorkUserWorkPlace::where('user_id', $userId)->first();
            if(isset($get_work_place)) {
                $user_new_id = $get_work_place->user_id;
                $user_id = profile()->user_id;
                if ($userId != profile()->user_id && $get_work_place->type == 2) {
                    $model = SocialNetworkUserAddFriend::query();
                    $model->where(function ($sub) use ($user_new_id){
                        $sub->orWhere('user_id', $user_new_id);
                        $sub->orWhere('friend_id', $user_new_id);
                    });
                    $model->where(function ($sub) use ($user_id){
                        $sub->where('user_id', $user_id);
                        $sub->orWhere('friend_id', $user_id);
                    });
                    $model->where('status', 1);
                    $friend = $model->exists();
                    if(!$friend) {
                        $get_work_place->show = 0;
                    } 
                } else if ($userId != profile()->user_id && $get_work_place->type == 3) {
                    $get_work_place->show = 0;
                } else {
                    $get_work_place->show = 1;
                }
            }
            $work_place = $get_work_place;
        } else {
            $work_place = SocialNetWorkUserWorkPlace::where('user_id', $userId)->get();
        }

        return response()->json([
            'work_place' => $work_place,
        ]);
    }

    // LƯU TRƯỜNG TRUNG HỌC/ CAO ĐẲNG/ ĐẠI HỌC
    public function saveStudy(Request $request)
    {
        $save = SocialNetworkUserStudy::firstOrNew(['user_id' => profile()->user_id, 'id' => $request->id]);
        $save->user_id = profile()->user_id;
        $save->name = $request->name;
        $save->description = $request->description;
        $save->status = $request->graduate == 'true' ? 1 : 0;
        $save->year_start = $request->yearStart;
        $save->year_end = $request->yearEnd;
        $save->type = $request->type;
        $save->type_study = $request->type_study;
        $save->save();

        return response()->json([
            'status' => 'success',
        ]);
    }

    // XÓA TRƯỜNG TRUNG HỌC/ CAO ĐẲNG/ ĐẠI HỌC
    public function deleteStudy(Request $request)
    {
        $model = SocialNetworkUserStudy::find($request->id)->delete();

        return response()->json([
            'status' => 'success',
        ]);
    }

    // DỮ LIỆU TRƯỜNG TRUNG HỌC/ CAO ĐẲNG/ ĐẠI HỌC
    public function dataStudy($userId, $type_study, $type)
    {
        if($type == 0) {
            $get_study = SocialNetworkUserStudy::where('user_id', $userId)->where('type_study', $type_study)->first();
            if(isset($get_study)) {
                $user_new_id = $get_study->user_id;
                $user_id = profile()->user_id;
                if ($userId != profile()->user_id && $get_study->type == 2) {
                    $model = SocialNetworkUserAddFriend::query();
                    $model->where(function ($sub) use ($user_new_id){
                        $sub->orWhere('user_id', $user_new_id);
                        $sub->orWhere('friend_id', $user_new_id);
                    });
                    $model->where(function ($sub) use ($user_id){
                        $sub->where('user_id', $user_id);
                        $sub->orWhere('friend_id', $user_id);
                    });
                    $model->where('status', 1);
                    $friend = $model->exists();
                    if(!$friend) {
                        $get_study->show = 0;
                    } 
                } else if ($userId != profile()->user_id && $get_study->type == 3) {
                    $get_study->show = 0;
                } else {
                    $get_study->show = 1;
                }
            }
            $study = $get_study;
        } else {
            $study = SocialNetworkUserStudy::where('user_id', $userId)->where('type_study', $type_study)->get();
        }

        return response()->json([
            'study' => $study,
        ]);
    }

    // LƯU THÀNH PHỐ
    public function saveCity(Request $request)
    {
        $save = SocialNetworkUserCity::firstOrNew(['user_id' => profile()->user_id, 'id' => $request->idCity]);
        $save->user_id = profile()->user_id;
        $save->city = $request->city;
        $save->type = $request->typeCity;
        $save->save();

        return response()->json([
            'status' => 'success',
        ]);
    }

    // XÓA THÀNH PHỐ
    public function deleteCity(Request $request)
    {
        $model = SocialNetworkUserCity::find($request->idCity)->delete();

        return response()->json([
            'status' => 'success',
        ]);
    }

    // DỮ LIỆU THÀNH PHỐ
    public function dataCity($userId, $type)
    {
        if($type == 0) {
            $get_city = SocialNetworkUserCity::where('user_id', $userId)->first();
            if(isset($get_city)) {
                $user_new_id = $get_city->user_id;
                $user_id = profile()->user_id;
                if ($userId != profile()->user_id && $get_city->type == 2) {
                    $model = SocialNetworkUserAddFriend::query();
                    $model->where(function ($sub) use ($user_new_id){
                        $sub->orWhere('user_id', $user_new_id);
                        $sub->orWhere('friend_id', $user_new_id);
                    });
                    $model->where(function ($sub) use ($user_id){
                        $sub->where('user_id', $user_id);
                        $sub->orWhere('friend_id', $user_id);
                    });
                    $model->where('status', 1);
                    $friend = $model->exists();
                    if(!$friend) {
                        $get_city->show = 0;
                    } 
                } else if ($userId != profile()->user_id && $get_city->type == 3) {
                    $get_city->show = 0;
                } else {
                    $get_city->show = 1;
                }
            }
            $city = $get_city;
        } else {
            $city = SocialNetworkUserCity::where('user_id', $userId)->get();
        }

        return response()->json([
            'city' => $city,
        ]);
    }

    // LƯU QUÊ QUÁN
    public function saveCountry(Request $request)
    {
        $save = SocialNetworkUserCountry::firstOrNew(['user_id' => profile()->user_id, 'id' => $request->idCountry]);
        $save->user_id = profile()->user_id;
        $save->country = $request->country;
        $save->type = $request->typeCountry;
        $save->save();

        return response()->json([
            'status' => 'success',
        ]);
    }

    // XÓA QUÊ QUÁN
    public function deleteCountry(Request $request)
    {
        $model = SocialNetworkUserCountry::find($request->idCountry)->delete();

        return response()->json([
            'status' => 'success',
        ]);
    }

    // DỮ LIỆU QUÊ QUÁN
    public function dataCountry($userId, $type)
    {
        if($type == 0) {
            $get_country = SocialNetworkUserCountry::where('user_id', $userId)->first();
            if(isset($get_country)) {
                $user_new_id = $get_country->user_id;
                $user_id = profile()->user_id;
                if ($userId != profile()->user_id && $get_country->type == 2) {
                    $model = SocialNetworkUserAddFriend::query();
                    $model->where(function ($sub) use ($user_new_id){
                        $sub->orWhere('user_id', $user_new_id);
                        $sub->orWhere('friend_id', $user_new_id);
                    });
                    $model->where(function ($sub) use ($user_id){
                        $sub->where('user_id', $user_id);
                        $sub->orWhere('friend_id', $user_id);
                    });
                    $model->where('status', 1);
                    $friend = $model->exists();
                    if(!$friend) {
                        $get_country->show = 0;
                    } 
                } else if ($userId != profile()->user_id && $get_country->type == 3) {
                    $get_country->show = 0;
                } else {
                    $get_country->show = 1;
                }
            }
            $country = $get_country;
        } else {
            $country = SocialNetworkUserCountry::where('user_id', $userId)->get();
        }

        return response()->json([
            'country' => $country,
        ]);
    }

    // DỮ LIỆU CHI TIẾT BÀI BIẾT THEO HÌNH ẢNH
    public function dataDetailPostPhoto($id, $idImage) 
    {
        $detailNew = SocialNetworkNew::find($id);
        $detailNew->avatar = image_user($detailNew->avatar);
        $detailNew->created_at2 = Carbon::parse($detailNew->created_at)->diffForHumans();

        $check_like = SocialNetworkUserLikeNew::where(['social_network_new_id' => $id, 'user_id' => profile()->user_id])->first();
        if(isset($check_like)) {
            $detailNew->check_like = $check_like->type;
            $detailNew->id_like_new = $check_like->social_network_new_id;
        } else {
            $detailNew->check_like = '';
            $detailNew->id_like_new = '';
        }

        return response()->json([
            'detailNew' => $detailNew,
        ]);
    }

    // DỮ LIỆU HÌNH ẢNH CHI TIẾT BÀI BIẾT
    public function dataImagePostPhoto($id, $idImage) 
    {
        $imageDetailNew = SocialNetworkItem::find($idImage);

        $listImageDetailNew = SocialNetworkItem::where('social_network_new_id', $id)->pluck('id')->toArray();
        $index = array_search($idImage ,$listImageDetailNew);
        $prev = $listImageDetailNew[$index - 1];
        $next = $listImageDetailNew[$index + 1];

        $imageDetailNew->image = image_file($imageDetailNew->image);
        $imageDetailNew->prev = $prev;
        $imageDetailNew->next = $next;

        return response()->json([
            'imageDetailNew' => $imageDetailNew,
        ]);
    }

    //DỮ LIỆU HÌNH ẢNH CỦA USER
    public function dataUserImage($userId) 
    {
        $getNewByUser = SocialNetworkNew::where(['user_id'=> $userId, 'type' => 1])->pluck('id')->toArray();
        if(!empty($getNewByUser)) {
            $getPhotoByNew = SocialNetworkItem::whereIn('social_network_new_id', $getNewByUser)->paginate(12);
            foreach($getPhotoByNew as $image) {
                $image->image = image_file($image->image);
            }
        } else {
            $getPhotoByNew = [];
        }
        return response()->json([
            'getPhotoByNew' => $getPhotoByNew,
        ]);
    }
}
