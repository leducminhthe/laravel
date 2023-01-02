<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SocialNetworkChat implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $user_id;
    public $chat;
    public $id_chat;
    public $send;
    public $type;
    public $format_image;

    public function __construct($user_id, $request)
    {
        $this->user_id = $user_id;
        $this->chat = $request->chat;
        $this->id_chat = $request->id_chat;
        $this->send = $request->send;
        $this->type = $request->type;
        if($request->type == 1) {
            $this->format_image = image_file($request->chat);
        }
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
