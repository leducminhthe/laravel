<?php

namespace App\Listeners;

use App\Events\SocialNetWorkComment;
use Illuminate\Support\Facades\Auth;
use App\Models\SocialNetworkItem;
use App\Models\SocialNetworkNew;
use App\Models\ProfileView;
use App\Models\SocialNetworkUserComment;

class SocialNetworkUserCommentListen
{
    public function __construct()
    {
        //
    }

    public function handle(SocialNetWorkComment $event)
    {
        $social_network_new_id = $event->social_network_new_id;
        $user_id = $event->user_id;
        $comment = $event->comment;

        // $profile = ProfileView::where('user_id', $user_id)->first(['avatar','firstname']);

        // $save_comment = new SocialNetworkUserComment();
        // $save_comment->user_id = $user_id;
        // $save_comment->social_network_new_id = $social_network_new_id;
        // $save_comment->comment = $comment;
        // $save_comment->avatar = $profile->avatar ? $profile->avatar : asset('/images/design/user_50_50.png');
        // $save_comment->user_name = $profile->firstname;
        // $save_comment->save();

        // $model = SocialNetworkNew::find($event->social_network_new_id);
        // $model->total_comment = $model->total_comment + 1;
        // $model->save();
    }
}
