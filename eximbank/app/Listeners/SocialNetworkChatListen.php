<?php

namespace App\Listeners;

use App\Events\SocialNetworkChat;
use Illuminate\Support\Facades\Auth;
use App\Models\ProfileView;
use App\Models\SocialNetworkUserChat;
use App\Models\SocialNetworkGroupChat;

class SocialNetworkChatListen
{
    public function __construct()
    {
        //
    }

    public function handle(SocialNetworkChat $event)
    {
        $user_1 = $event->user_id;
        $user_2 = $event->id_chat;
        $chat_content = $event->chat;
        $type = $event->type;
        
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

        if (!isset($group_chat)) {
            $save_group_chat = new SocialNetworkGroupChat();
            $save_group_chat->user_1 = $user_1;
            $save_group_chat->user_2 = $user_2;
            $save_group_chat->save();

            $save_chat = new SocialNetworkUserChat();
            $save_chat->chat = $chat_content;
            $save_chat->group_id = $save_group_chat->id;
            $save_chat->post_by_user_id = $user_1;
            $save_chat->type = $type;
            $save_chat->save();
        } else {
            $save_chat = new SocialNetworkUserChat();
            $save_chat->chat = $chat_content;
            $save_chat->group_id = $group_chat->id;
            $save_chat->post_by_user_id = $user_1;
            $save_chat->type = $type;
            $save_chat->save();
        }
    }
}
