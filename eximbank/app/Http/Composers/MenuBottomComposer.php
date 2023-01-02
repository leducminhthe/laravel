<?php


namespace App\Http\Composers;
use Composer\Config;
use Illuminate\Contracts\View\View;
use Modules\User\Entities\User;

class MenuBottomComposer
{
    protected  $menuBottom;
    public function __construct()
    {
        $this->getMenuBottom();
    }

    public function compose(View $view)
    {

            $view->with('menuBottom',$this->menuBottom) ;

    }
    public function getMenuBottom()
    {
        $this->menuBottom = \App\Models\Note::where('type',0)->where('user_id',profile()->user_id)->get();
    }
}








