<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SocialNetworkAddFriend implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */

    public $user_id;
    public $noty;
    public $avatar;
    public $user_name;
    public $created_at;
    public $friend_id;
    public $type;
    public $status;
    public $id;

    public function __construct($user_id, $request)
    {
        $this->user_id = $user_id;
        $this->noty = $request->noty;
        $this->avatar = $request->avatar;
        $this->user_name = $request->user_name;
        $this->created_at = $request->created_at;
        $this->friend_id = $request->friend_id;
        $this->type = $request->type;
        $this->status = $request->status;
        $this->id = $request->id;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PresenceChannel('social');
    }
}
