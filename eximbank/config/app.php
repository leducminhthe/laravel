<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Application Name
    |--------------------------------------------------------------------------
    |
    | This value is the name of your application. This value is used when the
    | framework needs to place the application's name in a notification or
    | any other location as required by the application or its packages.
    |
    */

    'name' => env('APP_NAME', 'Laravel'),

    /*
    |--------------------------------------------------------------------------
    | Application Environment
    |--------------------------------------------------------------------------
    |
    | This value determines the "environment" your application is currently
    | running in. This may determine how you prefer to configure various
    | services the application utilizes. Set this in your ".env" file.
    |
    */

    'env' => env('APP_ENV', 'production'),

    /*
    |--------------------------------------------------------------------------
    | Application Debug Mode
    |--------------------------------------------------------------------------
    |
    | When your application is in debug mode, detailed error messages with
    | stack traces will be shown on every error that occurs within your
    | application. If disabled, a simple generic error page is shown.
    |
    */

    'debug' => env('APP_DEBUG', true),

    /*
    |--------------------------------------------------------------------------
    | Application URL
    |--------------------------------------------------------------------------
    |
    | This URL is used by the console to properly generate URLs when using
    | the Artisan command line tool. You should set this to the root of
    | your application so that it is used when running Artisan tasks.
    |
    */

    'url' => env('APP_URL', 'http://localhost'),

    'asset_url' => env('ASSET_URL', null),

    /*
    |--------------------------------------------------------------------------
    | Application Timezone
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default timezone for your application, which
    | will be used by the PHP date and date-time functions. We have gone
    | ahead and set this to a sensible default for you out of the box.
    |
    */

    'timezone' => 'Asia/Ho_Chi_Minh',

    /*
    |--------------------------------------------------------------------------
    | Application Locale Configuration
    |--------------------------------------------------------------------------
    |
    | The application locale determines the default locale that will be used
    | by the translation service provider. You are free to set this value
    | to any of the locales which will be supported by the application.
    |
    */

    'locale' => 'vi',

    /*
    |--------------------------------------------------------------------------
    | Application Fallback Locale
    |--------------------------------------------------------------------------
    |
    | The fallback locale determines the locale to use when the current one
    | is not available. You may change the value to correspond to any of
    | the language folders that are provided through your application.
    |
    */

    'fallback_locale' => 'vi',

    /*
    |--------------------------------------------------------------------------
    | Faker Locale
    |--------------------------------------------------------------------------
    |
    | This locale will be used by the Faker PHP library when generating fake
    | data for your database seeds. For example, this will be used to get
    | localized telephone numbers, street address information and more.
    |
    */

    'faker_locale' => 'vi_VN',

    /*
    |--------------------------------------------------------------------------
    | Encryption Key
    |--------------------------------------------------------------------------
    |
    | This key is used by the Illuminate encrypter service and should be set
    | to a random, 32 character string, otherwise these encrypted strings
    | will not be safe. Please do this before deploying an application!
    |
    */

    'key' => env('APP_KEY'),

    'cipher' => 'AES-256-CBC',

    /*
    |--------------------------------------------------------------------------
    | Autoloaded Service Providers
    |--------------------------------------------------------------------------
    |
    | The service providers listed here will be automatically loaded on the
    | request to your application. Feel free to add your own services to
    | this array to grant expanded functionality to your applications.
    |
    */

    'providers' => [

        /*
         * Laravel Framework Service Providers...
         */
        Illuminate\Auth\AuthServiceProvider::class,
        Illuminate\Broadcasting\BroadcastServiceProvider::class,
        Illuminate\Bus\BusServiceProvider::class,
        Illuminate\Cache\CacheServiceProvider::class,
        Illuminate\Foundation\Providers\ConsoleSupportServiceProvider::class,
        Illuminate\Cookie\CookieServiceProvider::class,
        Illuminate\Database\DatabaseServiceProvider::class,
        Illuminate\Encryption\EncryptionServiceProvider::class,
        Illuminate\Filesystem\FilesystemServiceProvider::class,
        Illuminate\Foundation\Providers\FoundationServiceProvider::class,
        Illuminate\Hashing\HashServiceProvider::class,
        Illuminate\Mail\MailServiceProvider::class,
        Illuminate\Notifications\NotificationServiceProvider::class,
        Illuminate\Pagination\PaginationServiceProvider::class,
        Illuminate\Pipeline\PipelineServiceProvider::class,
        Illuminate\Queue\QueueServiceProvider::class,
        Illuminate\Redis\RedisServiceProvider::class,
        Illuminate\Auth\Passwords\PasswordResetServiceProvider::class,
        Illuminate\Session\SessionServiceProvider::class,
        Illuminate\Translation\TranslationServiceProvider::class,
        Illuminate\Validation\ValidationServiceProvider::class,
        Illuminate\View\ViewServiceProvider::class,

        /*
         * Package Service Providers...
         */
        \SocialiteProviders\Manager\ServiceProvider::class, // add
        /*
         * Application Service Providers...
         */
        App\Providers\AppServiceProvider::class,
        App\Providers\AuthServiceProvider::class,
        App\Providers\BroadcastServiceProvider::class,
        App\Providers\EventServiceProvider::class,
        App\Providers\RouteServiceProvider::class,
        // App\Providers\TelescopeServiceProvider::class,
		App\Providers\RepositoryServiceProvider::class,
        Intervention\Image\ImageServiceProvider::class,
        Arcanedev\LogViewer\LogViewerServiceProvider::class,
        Spatie\Permission\PermissionServiceProvider::class,
//        App\Providers\MailConfigServiceProvider::class,
        Tymon\JWTAuth\Providers\LaravelServiceProvider::class,
        Spatie\JsonApiPaginate\JsonApiPaginateServiceProvider::class,
        Yajra\DataTables\DataTablesServiceProvider::class,
        Barryvdh\Debugbar\ServiceProvider::class,
        Stevebauman\Location\LocationServiceProvider::class,
//        App\Providers\ComposerServiceProvider::class,
        Onecentlin\Adminer\ServiceProvider::class,
        App\Providers\ObserverServiceProvider::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Class Aliases
    |--------------------------------------------------------------------------
    |
    | This array of class aliases will be registered when this application
    | is started. However, feel free to register as many as you wish as
    | the aliases are "lazy" loaded so they don't hinder performance.
    |
    */

    'aliases' => [

        'App' => Illuminate\Support\Facades\App::class,
        'Arr' => Illuminate\Support\Arr::class,
        'Artisan' => Illuminate\Support\Facades\Artisan::class,
        'Auth' => Illuminate\Support\Facades\Auth::class,
        'Blade' => Illuminate\Support\Facades\Blade::class,
        'Broadcast' => Illuminate\Support\Facades\Broadcast::class,
        'Bus' => Illuminate\Support\Facades\Bus::class,
        'Cache' => Illuminate\Support\Facades\Cache::class,
        'Config' => Illuminate\Support\Facades\Config::class,
        'Cookie' => Illuminate\Support\Facades\Cookie::class,
        'Crypt' => Illuminate\Support\Facades\Crypt::class,
        'DB' => Illuminate\Support\Facades\DB::class,
        'Eloquent' => Illuminate\Database\Eloquent\Model::class,
        'Event' => Illuminate\Support\Facades\Event::class,
        'File' => Illuminate\Support\Facades\File::class,
        'Gate' => Illuminate\Support\Facades\Gate::class,
        'Hash' => Illuminate\Support\Facades\Hash::class,
        'Lang' => Illuminate\Support\Facades\Lang::class,
        'Log' => Illuminate\Support\Facades\Log::class,
        'Mail' => Illuminate\Support\Facades\Mail::class,
        'Notification' => Illuminate\Support\Facades\Notification::class,
        'Password' => Illuminate\Support\Facades\Password::class,
        'Queue' => Illuminate\Support\Facades\Queue::class,
        'Redirect' => Illuminate\Support\Facades\Redirect::class,
        'Redis' => Illuminate\Support\Facades\Redis::class,
        'Request' => Illuminate\Support\Facades\Request::class,
        'Response' => Illuminate\Support\Facades\Response::class,
        'Route' => Illuminate\Support\Facades\Route::class,
        'Schema' => Illuminate\Support\Facades\Schema::class,
        'Session' => Illuminate\Support\Facades\Session::class,
        'Storage' => Illuminate\Support\Facades\Storage::class,
        'Str' => Illuminate\Support\Str::class,
        'URL' => Illuminate\Support\Facades\URL::class,
        'Validator' => Illuminate\Support\Facades\Validator::class,
        'View' => Illuminate\Support\Facades\View::class,
        'Image' => Intervention\Image\Facades\Image::class,
        'Visitor' => Shetabit\Visitor\Facade\Visitor::class,
//        'Socialite' => SocialiteProviders\Manager\ServiceProvider::class,
        'JWTAuth' => Tymon\JWTAuth\Facades\JWTAuth::class,
        'JWTFactory' => Tymon\JWTAuth\Facades\JWTFactory::class,
        'DataTables' => Yajra\DataTables\Facades\DataTables::class,
        'Debugbar' => Barryvdh\Debugbar\Facades\Debugbar::class,
        'Pusher' => Pusher\Pusher::class,
        'Location' => Stevebauman\Location\Facades\Location::class,
    ],

    'datafile' => [
        'path' => env('APP_STORAGE'),
        'public_url' => env('APP_STORAGE_PUBLIC', '/uploads'),
        'upload_disk' => env('APP_UPLOAD_DISK', 'upload'),
    ],

    'debug_blacklist' => [
        '_COOKIE' => array_keys($_COOKIE),
        '_SERVER' => array_keys($_SERVER),
        '_ENV' => array_keys($_ENV),
        '_POST' => array_keys($_POST),
    ],

    'mobile_url' => env('MOBILE_DOMAIN', ''),
    'app1_path' => env('APP1_PATH', ''),
    'app2_path' => env('APP2_PATH', ''),
    'social_url' => env('SOCIAL_DOMAIN', ''),

    'ffmpeg' => [
        'binaries' => env('FFMPEG_BINARIES', ''),
        'threads'  => 12,
    ],

    'ffprobe' => [
        'binaries' => env('FFPROBE_BINARIES', ''),
    ],
    'azure'=>[
        'tenant_id'             =>env('AZURE_TENANT_ID',''),
        'client_id'             =>env('AZURE_CLIENT_ID',''),
        'client_secret'         =>env('AZURE_CLIENT_SECRET',''),
        'redirect'              =>env('AZURE_REDIRECT',''),
        'user_id'               =>env('AZURE_USER_ID',''),
        'api_url'               =>env('AZURE_API_URL',''),

        'client_id_mobi'        =>env('AZURE_CLIENT_ID_MOBI',''),
        'client_secret_mobi'    =>env('AZURE_CLIENT_SECRET_MOBI',''),
        'redirect_mobi'         =>env('AZURE_REDIRECT_MOBI',''),
    ],
    'zoom' => [
        'url' => env('ZOOM_API_URL', ''),
        'key' => env('ZOOM_API_KEY', ''),
        'secret' => env('ZOOM_API_SECRET', ''),
    ],
    'lrs' =>[
        'user' => env('LRS_USER',''),
        'pass' => env('LRS_PASS',''),
        'end_point' => env('LRS_END_POINT',''),
    ],
    'timeout' => 3600,
    'enable_logging' => env('ENABLE_LOGGING', false),
    'enable_subtance_caching' => env('CACHE_SUBSTANCE_ENABLE', true),
    'cache'=>[
        'expire'=>now()->hours(24*7)
    ]
];
