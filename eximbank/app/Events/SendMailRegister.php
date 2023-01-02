<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SendMailRegister
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $users;
    public $course;
    public $type;
    public $type_send_mail;
    public $status;

    public function __construct($users, $course, $type, $type_send_mail = null, $status = null)
    {
        $this->users = $users;
        $this->course = $course;
        $this->type = $type;
        $this->type_send_mail = $type_send_mail;
        $this->status = $status;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
