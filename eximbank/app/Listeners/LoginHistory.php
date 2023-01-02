<?php

namespace App\Listeners;

use App\Models\Analytics;
use App\Events\LoginSuccess;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\LoginHistory as AppLoginHistory;
use Modules\User\Entities\ProfileChangedPass;

class LoginHistory
{
    public function __construct()
    {
        //
    }

    public function handle(LoginSuccess $event)
    {
        AppLoginHistory::setLoginHistory($event->user->id);
        /* analytics  */
        $analytic = new Analytics();
        $analytic->user_id = $event->user->id;
        $analytic->ip_address = request()->ip();
        $analytic->start_date = date('Y-m-d H:i:s');
        $analytic->day = date('Y-m-d');
        $analytic->save();
    }
}
