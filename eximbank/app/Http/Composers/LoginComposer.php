<?php

namespace App\Http\Composers;

use App\Models\AppMobile;
use App\Models\LoginImage;
use App\Models\LogoModel;
use App\Models\Config;
use Illuminate\Contracts\View\View;

class LoginComposer{
    protected $logo;
    protected $favicon;
    protected $img_login;
    protected $app_android;
    protected $app_apple;
    protected $url;

    public function __construct() {
        $this->getLogo();
        $this->getFavicon();
        $this->getImgLogin();
        $this->getAppAndroid();
        $this->getAppApple();
        $this->getUrl();
    }

    public function compose(View $view) {
        $view->with('logo',$this->logo)
            ->with('favicon',$this->favicon)
            ->with('img',$this->img_login)
            ->with('app_android',$this->app_android)
            ->with('app_apple',$this->app_apple)
            ->with('url',$this->url);
    }

    public function getLogo()
    {
        $this->logo = LogoModel::where('status', 1)->first();
    }

    public function getFavicon()
    {
        $this->favicon = Config::whereName('favicon')->first(['value']);
    }

    public function getImgLogin() {
        $this->img_login = LoginImage::where('status', '=', 1)->where('type',1)->get();
    }

    public function getAppAndroid() {
        $this->app_android = AppMobile::where('type', '=', 1)->first();
    }

    public function getAppApple() {
        $this->app_apple = AppMobile::where('type', '=', 2)->first();
    }

    public function getUrl() {
        $this->url = session()->get('url_previous') ? session()->get('url_previous') : '';
    }
}
