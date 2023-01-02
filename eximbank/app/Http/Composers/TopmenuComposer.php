<?php


namespace App\Http\Composers;
use App\Models\Categories\UnitManager;
use Composer\Config;
use Illuminate\Contracts\View\View;
use Modules\User\Entities\User;
use Modules\News\Entities\News;
use Modules\News\Entities\NewsObject;
use App\Models\ProfileView;
use App\Scopes\CompanyScope;

class TopmenuComposer
{
    protected  $logo;
    protected  $news_created_at;
    public function __construct()
    {
        $this->getLogo();
        $this->getNews();
    }

    public function compose(View $view)
    {
        $view->with('logo',$this->logo)
            ->with('news_created_at',$this->news_created_at) ;
    }

    public function getLogo()
    {
        \App\Models\LogoModel::addGlobalScope(new \App\Scopes\CompanyScope());
        $this->logo = \App\Models\LogoModel::where('status', 1)->first();
    }

    public function getNews()
    {
        News::addGlobalScope(new CompanyScope());

        $get_object_news_parent_cate_id = NewsObject::get(['new_id','unit_id']);
        $object_news_parent_cate_id = [];
        if( !$get_object_news_parent_cate_id->isEmpty() ) {
            $check_unit_array = [];
            $get_unit =  profile();
            foreach($get_object_news_parent_cate_id as $get_object_new_parent_cate_id) {
                $check_unit = NewsObject::checkUnitNewCate($get_object_new_parent_cate_id->unit_id, $get_unit->unit_id);
                if($check_unit == 1) {
                    $check_unit_array[] = $get_object_new_parent_cate_id->new_id;
                }
            }
            $object_news_parent_cate_id = NewsObject::whereNotIn('new_id', $check_unit_array)->pluck('new_id')->toArray();
        }

        $get_news = News::select(['id','title','created_at'])->where('status', 1)->orderByDesc('created_at')->whereNotIn('id',$object_news_parent_cate_id)->take(5)->get();
        foreach($get_news as $item) {
            $item->created_at2 = \Carbon\Carbon::parse($item->created_at)->format('d/m/Y');
        }
        $this->news_created_at = $get_news;
    }
}








