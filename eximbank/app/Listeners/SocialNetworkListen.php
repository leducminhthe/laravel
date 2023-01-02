<?php

namespace App\Listeners;

use App\Events\SocialNetWork;
use Illuminate\Support\Facades\Auth;
use App\Models\SocialNetworkItem;
use App\Models\SocialNetworkNew;
use App\Models\ProfileView;

class SocialNetworkListen
{
    public function __construct()
    {
        //
    }

    public function handle(SocialNetWork $event)
    {
        $status = $event->status;
        $type = $event->type;
        $user_id = $event->user_id;
        $video = $event->video;
        $listImage = $event->listImage;
        $titleNew = $event->titleNew;

        $profile = ProfileView::where('user_id', $user_id)->first(['avatar','firstname']);

        $model = new SocialNetworkNew();
        $model->type = $type;
        $model->user_id = $user_id;
        $model->status = $status;
        $model->title_new = $titleNew;
        $model->avatar = $profile->avatar ? $profile->avatar : asset('/images/design/user_50_50.png');
        $model->user_name = $profile->firstname;
        $model->save();

        if (!empty($listImage) && $type == 1) {
            foreach ($listImage as $key => $image) {
                $item = new SocialNetworkItem();
                $item->image = $image;
                $item->social_network_new_id = $model->id;
                $item->save();
            }
        } else if (!empty($video) && $type == 2) {
            $item = new SocialNetworkItem();
            $item->video = $video;
            $item->social_network_new_id = $model->id;
            $item->save();
        }

        if($status == 2) {
            foreach ($request->chooseFriend as $key => $item) {
                $save_choose_friend = new SocialNetworkUserChooseFriendSeeNew();
                $save_choose_friend->friend_id = $item;
                $save_choose_friend->social_network_id = $model->id;
                $save_choose_friend->save();
            }
        }
    }
}
