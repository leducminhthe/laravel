<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Log\Events\MessageLogged;
use Illuminate\Support\Facades\Event;
use App\Helpers\Tracking;
use Illuminate\Support\Facades\Artisan;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use PHPUnit\TextUI\XmlConfiguration\Logging\Logging;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],

        \App\Events\LoginSuccess::class => [
            \App\Listeners\LoginHistory::class
        ],
        \App\Events\SurveyRealTime::class => [
            \App\Listeners\SurveyUserAnswerListen::class
        ],
        \App\Events\SocialNetWork::class => [
            \App\Listeners\SocialNetworkListen::class
        ],
        \App\Events\SocialNetWorkComment::class => [
            \App\Listeners\SocialNetworkUserCommentListen::class
        ],
        \App\Events\SocialNetworkAddFriend::class => [
            \App\Listeners\SocialNetWorkAddFriendListen::class
        ],
        \App\Events\SocialNetworkChat::class => [
            \App\Listeners\SocialNetworkChatListen::class
        ],
        \App\Events\SaveOfflineScore::class => [
            \App\Listeners\UserPromotionPoint::class,
        ],
        \App\Events\Online\GoActivity::class => [
            \App\Listeners\Online\ActivityHistory::class,
        ],
        \App\Events\Offline\GoActivity::class => [
            \App\Listeners\Offline\ActivityHistory::class,
        ],
        \SocialiteProviders\Manager\SocialiteWasCalled::class => [
            // add your listeners (aka providers) here
            'SocialiteProviders\\Google\\GoogleExtendSocialite@handle',
            'SocialiteProviders\\Azure\\AzureExtendSocialite@handle',
        ],
        \App\Events\SaveTrainingProcessRegister::class => [
            \App\Listeners\SaveTrainingProcessRegisterListen::class
        ],
        \App\Events\SendMailRegister::class => [
            \App\Listeners\SendMailRegisterListen::class
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        if (
            config('app.debug', false)
            && config('app.enable_logging', false)
        ) {
            $this->trackingEvents();
        }

        // listen to flushAll caching
        Event::listen('cache:clear', function () {
            Artisan::call('cache:clear');
        });
        Event::listen(MessageLogged::class, function (MessageLogged $messageLogged ) {
            list($levelStr, $message, $context) = [$messageLogged->level, $messageLogged->message, $messageLogged->context];
            $loger = new Logger(config('app.env'));
            $level = $loger::toMonologLevel($messageLogged->level?: 'debug');
            $logPath = rtrim(config('app.datafile.path'), '/') .'/logs/laravel-'. Carbon::now()->toDateString() . '.log';
            $formatter = new LineFormatter(
                null, // Format of message in log, default [%datetime%] %channel%.%level_name%: %message% %context% %extra%\n
                Carbon::now()->format('Y-m-d H:i:s'), // Datetime format
                true, // allowInlineLineBreaks option, default false
                true,  // discard empty Square brackets in the end, default false
                true
            );
            $debugHandler  = new StreamHandler($logPath, $level, false);
            $debugHandler->setFormatter($formatter);
            $loger->pushHandler($debugHandler);

            $loger->{$levelStr}($message,$context);
        });
    }

    private function trackingEvents()
    {
        // app events depend on the eloquent trigger
        Event::listen('eloquent.*', function ($eventName) {
            Tracking::put((object) ['event' => $eventName], 'app');
        });

        // layout init events
        Event::listen('creating:*', function ($eventName) {
            Tracking::put((object) ['event' => $eventName], 'lay', false);
        });

        // layout compose events
        Event::listen('composing:*', function ($eventName) {
            Tracking::put((object) ['event' => $eventName], 'lay', false);
        });

        // all events tracking trail
        Event::listen([
            '*.*',
            'creating:*',
            'composing:*',
            'bootstrapped:*'
        ], function ($eventName) {
            Tracking::put((object) ['event' => $eventName], 'event', false);
        });
    }
}
