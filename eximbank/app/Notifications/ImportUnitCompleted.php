<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ImportUnitCompleted extends Notification
{
    use Queueable;
    
    public function __construct()
    {
        //
    }
    
    public function via($notifiable)
    {
        return ['database'];
    }
    
    public function toArray($notifiable)
    {
        return [
            'title' => 'Import tập đoàn đã hoàn thành',
            'status' => 'success',
        ];
    }
}
