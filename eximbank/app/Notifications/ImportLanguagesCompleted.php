<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class ImportLanguagesCompleted extends Notification  implements ShouldQueue
{
    use Queueable;
    
    public function __construct()
    {
    
    }
    
    public function via($notifiable)
    {
        return ['database'];
    }
    
    public function toArray($notifiable)
    {
        return [
            'title' => 'Import ngôn ngữ đã hoàn thành',
            'status' => 'success',
        ];
    }
}
