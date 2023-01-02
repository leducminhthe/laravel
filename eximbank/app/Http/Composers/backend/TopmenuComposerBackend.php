<?php

namespace App\Http\Composers\Backend;

use App\Models\Permission;
use Illuminate\Contracts\View\View;
use Modules\User\Entities\User;
use App\Models\Profile;
use App\Models\LogoModel;
use App\Scopes\CompanyScope;

class TopmenuComposerBackend
{
    protected  $logo;
    public function __construct()
    {
        $this->getLogo();
    }

    public function compose(View $view)
    { 
        if (Permission::isAdmin())
            $userUnits = Profile::getAllCompany();
        else
            $userUnits = auth()->check()? User::getAllUnitByRole() : [];

        $view->with('userUnits',$userUnits)
            ->with('logo',$this->logo);
    }

    public function getLogo()
    {
        LogoModel::addGlobalScope(new CompanyScope());
        $this->logo = LogoModel::where('status', 1)->first();
    }
}








