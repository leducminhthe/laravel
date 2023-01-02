<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SaveTrainingProcessRegister
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $course;
    public $subject;
    public $id;
    public $class_id;
    public $type;

    public function __construct($course, $subject, $id, $class_id, $type)
    {
        $this->course = $course;
        $this->subject = $subject;
        $this->id = $id;
        $this->class_id = $class_id;
        $this->type = $type;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
