<?php

namespace App\Listeners;

use App\Events\SocialNetworkAddFriend;
use Illuminate\Support\Facades\Auth;
use App\Models\SocialNetworkItem;
use App\Models\SocialNetworkNew;
use App\Models\ProfileView;
use App\Models\SocialNetworkUserAddFriend;

class SocialNetWorkAddFriendListen
{
    public function __construct()
    {
        //
    }

    public function handle(SocialNetworkAddFriend $event)
    {
        $friend_id = $event->friend_id;
        $user_id = $event->user_id;
    }
}
