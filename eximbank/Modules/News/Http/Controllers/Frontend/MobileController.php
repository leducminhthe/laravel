<?php

namespace Modules\News\Http\Controllers\Frontend;

use App\Models\AdvertisingPhoto;
use App\Models\Profile;
use App\Models\ProfileView;
use App\Models\User;
use App\Scopes\CompanyScope;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\News\Entities\News;
use Modules\News\Entities\NewsCategory;
use Modules\News\Entities\NewsLink;
use Modules\News\Entities\NewsObject;

class MobileController extends Controller
{
    public function index(Request $request)
    {
        News::addGlobalScope(new CompanyScope());
        NewsCategory::addGlobalScope(new CompanyScope());

        $cate_id = '';
        $get_unit =  ProfileView::select(['unit_id'])->where('user_id', profile()->user_id)->first();
        $get_object_news_parent_cate_id = NewsObject::get();
        $object_news_parent_cate_id = [];
        if( !$get_object_news_parent_cate_id->isEmpty() ) {
            foreach($get_object_news_parent_cate_id as $get_object_new_parent_cate_id) {
                $check_unit = NewsObject::checkUnitNewCate($get_object_new_parent_cate_id->unit_id, $get_unit->unit_id);
                if($check_unit == 0) {
                    $object_news_parent_cate_id[] = $get_object_new_parent_cate_id->new_id;
                } else {
                    if (($key = array_search($get_object_new_parent_cate_id->new_id, $object_news_parent_cate_id)) !== false) {
                        unset($object_news_parent_cate_id[$key]);
                        $object_news_parent_cate_id = array_values($object_news_parent_cate_id);
                    }
                }
            }
        }

        $get_main_new_hot = News::select(['id','title','date_setup_icon','description','image','created_at'])->where('hot_public',1)->orderByDesc('created_at')->whereNotIn('id',$object_news_parent_cate_id)->where('status',1)->first();
        $get_related_main_hot_news = [];
        if(!empty($get_main_new_hot)) {
            $get_related_main_hot_news = News::select('image','title','id','date_setup_icon','description')->where('status',1)->where('hot_public',1)->where('id','!=',$get_main_new_hot->id)->whereNotIn('id',$object_news_parent_cate_id)->get();
        }

        $get_news_parent_cate_left = NewsCategory::select(['id','name','parent_id'])->whereNull('parent_id')->where('status',1)->orderBy('stt_sort_parent','asc')->get();

        $news = '';
        if($request->cate_id) {
            $cate_id = $request->cate_id;
            $get_news_parent_cate_left = NewsCategory::select(['id','name'])->whereNull('parent_id')->where('status',1)->where('id',$request->cate_id)->get();
        }
        if($request->search) {
            $news = News::where('title','like','%'. $request->search .'%')->get();
        }
        $parent_cates = NewsCategory::whereNull('parent_id')->where('status',1)->orderBy('stt_sort_parent','asc')->get();
        return view('themes.mobile.frontend.news.index', [
            'parent_cates' => $parent_cates,
            'get_main_new_hot' => $get_main_new_hot,
            'get_hot_news' => $get_related_main_hot_news,
            'get_news_parent_cate_left' => $get_news_parent_cate_left,
            'object_cate_parent' => $object_news_parent_cate_id,
            'cate_id' => $cate_id,
            'news' => $news,
        ]);
    }

    public function detail($id, Request $request)
    {
        $news_links = NewsLink::where('news_id', $id)->get();
        News::updateItemViews($id);
        $item = News::findOrFail($id);
        $categories = News::getNewsCategory($item->category_id, $item->id);
        $user = User::getProfileById($item->created_by)->profile;
        $author = $user->lastname." ".$user->firstname;
        $next_post = News::where('id','>',$item->id)->where('status', '=', 1)->where('category_id', '=', $item->category_id)->orderBy('id')->first();
        $prev_post = News::where('id','<',$item->id)->where('status', '=', 1)->where('category_id', '=', $item->category_id)->orderBy('id','DESC')->first();
        return view('themes.mobile.frontend.news.detail', [
            'item' => $item,
            'author' => $author,
            'categories' => $categories,
            'next_post' => $next_post,
            'prev_post' => $prev_post,
            'news_links' => $news_links,
        ]);
    }

    // chức năng like bài viết
    public function likeNew(Request $request) {
        $check_like = 0;
        $id_new = $request->id;
        $new = News::where('id',$id_new)->first();
        $profile = Profile::find(profile()->user_id);
        if ($profile->like_new == null || empty($profile->like_new)) {
            $check_like = 1;
            $set_profile_like_new[] = $id_new;
            $profile->like_new = json_encode($set_profile_like_new);
            $profile->save();
            $like_new = $new->like_new + 1;
            $new->like_new = $like_new;
            $new->save();
            return json_result([
                'view_like'=>$new->like_new,
                'check_like'=>$check_like,
            ]);
        }
        $get_profile_like_new = json_decode($profile->like_new);
        if (($key = array_search($id_new, $get_profile_like_new)) !== false) {
            unset($get_profile_like_new[$key]);
            $newarray = array_values($get_profile_like_new);
            $profile->like_new = json_encode($newarray);
            $like_new = $new->like_new - 1;
        } else {
            array_push($get_profile_like_new, $id_new);
            $profile->like_new = json_encode($get_profile_like_new);
            $like_new = $new->like_new + 1;
            $check_like = 1;
        }
        $profile->save();
        $new->like_new = $like_new;
        $new->save();
        return json_result([
            'view_like'=>$new->like_new,
            'check_like'=>$check_like,
        ]);
    }

    public function cateNew($parent_id, $cate_id ,$type){
        NewsCategory::addGlobalScope(new CompanyScope());

        $cate_news = NewsCategory::where('parent_id',$parent_id)->orderBy('id','asc')->get();
        return view('themes.mobile.frontend.news.cate_child', [
            'cate_news' => $cate_news,
        ]);
    }

    public function ajaxGetRelatedNews(Request $request) {
        $category_id = $request->category_id;
        $date_search = date("Y-m-d", strtotime($request->date_search));
        $new_id = $request->new_id;
        $get_related_news = News::where('category_id',$category_id)
            ->where('status',1)
            ->where('id','!=',$new_id)
            ->whereDate('created_at', '=', $date_search)
            ->get();
        // dd($get_related_news);
        $image_related_new = '';
        if(!$get_related_news->isEmpty()){
            foreach($get_related_news as $item) {
                $image_related_new[] = array('image' => image_file($item->image), 'id' => $item->id, 'title' => $item->title, 'description' => $item->description);
            }
        }
        return json_result([
            'get_related_news'=>$image_related_new,
        ]);
    }

    public function viewPDF(Request $request){
        $path = $request->path;
        $path = str_replace(config('app.url'), config('app.mobile_url'), $path);

        return view('themes.mobile.frontend.news.view_pdf', [
            'path' => $path,
        ]);
    }
}
