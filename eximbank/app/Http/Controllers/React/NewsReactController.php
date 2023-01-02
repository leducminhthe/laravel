<?php

namespace App\Http\Controllers\React;

use App\Scopes\CompanyScope;
use App\Models\Slider;
use App\Models\AdvertisingPhoto;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\InteractionHistory;
use Modules\News\Entities\News;
use App\Models\User;
use Modules\News\Entities\NewsCategory;
use Modules\News\Entities\NewsLink;
use Modules\News\Entities\NewsObject;
use App\Models\Profile;
use App\Models\ProfileView;
use Carbon\Carbon;
use Modules\UserPoint\Entities\UserPointResult;
use Modules\Promotion\Entities\PromotionUserPoint;
use Modules\Promotion\Entities\PromotionLevel;
use Modules\UserPoint\Entities\UserPointSettings;
use Modules\Notify\Entities\Notify;
use Modules\AppNotification\Helpers\AppNotification;

class NewsReactController extends Controller
{
    public function index()
    {
        return view('react.news.index');
    }

    // MENU TIN TỨC
    public function dataMenuNews() {
        NewsCategory::addGlobalScope(new CompanyScope());
        $news_category_parent = NewsCategory::query()->orderBy('stt_sort_parent')->whereNull('parent_id')->get(['id','name']);
        foreach ($news_category_parent as $key => $new_category_parent) {
            $new_category_parent->child;
        }
        return response()->json([
            'news_category_parent' => $news_category_parent
        ]);
    }

    // ẢNH QUẢNG CÁO
    public function dataAdvertisement() {
        AdvertisingPhoto::addGlobalScope(new CompanyScope());
        $advertisingPhotos = AdvertisingPhoto::where('status',1)->where('type',1)->get(['id','url','image']);
        foreach ($advertisingPhotos as $key => $advertisingPhoto) {
            $advertisingPhoto->image = image_file($advertisingPhoto->image);
        }
        return response()->json([
            'advertisingPhotos' => $advertisingPhotos,
        ]);
    }

    // DỮ LIỆU TIN TỨC
    public function dataNews(Request $request)
    {
        $date_now = date('Y-m-d H:s');
        News::addGlobalScope(new CompanyScope());
        NewsCategory::addGlobalScope(new CompanyScope());

        $get_new_user_like = Profile::where('user_id', profile()->user_id)->first(['like_new']);
        $new_user_like = !empty($get_new_user_like) ? json_decode($get_new_user_like->like_new) : [];

        $get_object_news_parent_cate_id = NewsObject::get(['new_id','unit_id']);
        $object_news_parent_cate_id = [];
        if( !$get_object_news_parent_cate_id->isEmpty() ) {
            $check_unit_array = [];
            $get_unit =  ProfileView::select(['unit_id'])->where('user_id', profile()->user_id)->first();
            foreach($get_object_news_parent_cate_id as $get_object_new_parent_cate_id) {
                $check_unit = NewsObject::checkUnitNewCate($get_object_new_parent_cate_id->unit_id, $get_unit->unit_id);
                if($check_unit == 1) {
                    $check_unit_array[] = $get_object_new_parent_cate_id->new_id;
                }
            }
            $object_news_parent_cate_id = NewsObject::whereNotIn('new_id', $check_unit_array)->pluck('new_id')->toArray();
        }

        $query = News::query();
        $query->select(['id','image','title','created_at','hot_public_sort']);
        $query->whereNotIn('id', $object_news_parent_cate_id);
        $query->where('hot_public', 1);
        $query->where('status', 1);
        $hot_public_sort_1 = '';
        $hot_public_sort_2 = '';
        $hot_public_sort_3 = '';
        $hot_public_sort_4 = '';
        $hot_public = $query->get();
        foreach ($hot_public as $key => $value) {
            $value->image = image_file($value->image);
            $value->created_at2 = Carbon::parse($value->created_at)->format('d/m/Y');
            if($value->hot_public_sort == 1) {
                $hot_public_sort_1 = $value;
                continue;
            } else if ($value->hot_public_sort == 2) {
                $hot_public_sort_2 = $value;
                continue;
            }   else if ($value->hot_public_sort == 3) {
                $hot_public_sort_3 = $value;
                continue;
            } else if ($value->hot_public_sort == 4) {
                $hot_public_sort_4 = $value;
                continue;
            }
        }

        $parent_cate_left = NewsCategory::select(['id','name'])->whereNull('parent_id')->where('status',1)->orderBy('stt_sort_parent','asc')->get();
        foreach ($parent_cate_left as $key => $cate_left) {
            $cate_left->cate_child = NewsCategory::where('parent_id', $cate_left->id)->where('sort',0)->orderBy('stt_sort','asc')->get(['name','id']);

            $cate_child_array = NewsCategory::where('parent_id', $cate_left->id)->where('sort',0)->orderBy('stt_sort','asc')->pluck('id')->toArray();

            $hot_news_cate_first = News::select(['id','image','title','date_setup_icon','views','created_at','description','like_new'])->whereNotIn('id', $object_news_parent_cate_id)->where('category_parent_id', $cate_left->id)->whereIn('category_id',$cate_child_array)->where('hot_public',0)->orderByDesc('hot')->orderByDesc('created_at')->where('status',1)->first();
            if (!empty($hot_news_cate_first)) {
                $hot_news_cate_first->image = image_file($hot_news_cate_first->image);
                $hot_news_cate_first->checkDate = $date_now < $hot_news_cate_first->date_setup_icon ? asset('images/new1.gif') : '';
                $hot_news_cate_first->created_at2 = Carbon::parse($hot_news_cate_first->created_at)->format('H:s d/m/Y');
                $hot_news_cate_first->check_user_like = in_array($hot_news_cate_first->id, $new_user_like) ? asset('images/check_like.png') : '';
                $cate_left->hot_news_cate_first = $hot_news_cate_first;
            } else {
                $cate_left->hot_news_cate_first = '';
            }

            $cate_left->hot_news_of_cate_child = News::select(['id','image','title','date_setup_icon','views','created_at','like_new'])->whereNotIn('id', $object_news_parent_cate_id)->where('id', '!=' , $hot_news_cate_first->id)->where('category_parent_id', $cate_left->id)->where('hot_public',0)->whereIn('category_id',$cate_child_array)->orderByDesc('hot')->orderByDesc('created_at')->where('status',1)->get()->take(4);

            foreach($cate_left->hot_news_of_cate_child as $new_cate_child) {
                $new_cate_child->image = image_file($new_cate_child->image);
                $new_cate_child->checkDate = $date_now < $new_cate_child->date_setup_icon ? asset('images/new1.gif') : '';
                $new_cate_child->created_at2 = Carbon::parse($new_cate_child->created_at)->format('d/m/Y');
                $new_cate_child->check_user_like = in_array($new_cate_child->id, $new_user_like) ? asset('images/check_like.png') : '';
            }
        }

        return response()->json([
            'parent_cate_left' => $parent_cate_left,
            'hot_public_sort_1' => $hot_public_sort_1,
            'hot_public_sort_2' => $hot_public_sort_2,
            'hot_public_sort_3' => $hot_public_sort_3,
            'hot_public_sort_4' => $hot_public_sort_4,
        ]);
    }

    // DỮ LIỆU TIN TỨC PHẢI
    public function dataNewsRight()
    {
        $date_now = date('Y-m-d H:s');
        News::addGlobalScope(new CompanyScope());
        NewsCategory::addGlobalScope(new CompanyScope());

        $get_new_user_like = Profile::where('user_id', profile()->user_id)->first(['like_new']);
        $new_user_like = !empty($get_new_user_like) ? json_decode($get_new_user_like->like_new) : [];

        $get_object_news_parent_cate_id = NewsObject::get(['new_id','unit_id']);
        $object_news_parent_cate_id = [];
        if( !$get_object_news_parent_cate_id->isEmpty() ) {
            $check_unit_array = [];
            $get_unit =  ProfileView::select(['unit_id'])->where('user_id', profile()->user_id)->first();
            foreach($get_object_news_parent_cate_id as $get_object_new_parent_cate_id) {
                $check_unit = NewsObject::checkUnitNewCate($get_object_new_parent_cate_id->unit_id, $get_unit->unit_id);
                if($check_unit == 1) {
                    $check_unit_array[] = $get_object_new_parent_cate_id->new_id;
                }
            }
            $object_news_parent_cate_id = NewsObject::whereNotIn('new_id', $check_unit_array)->pluck('new_id')->toArray();
        }

        $get_news_category_sort_right = NewsCategory::query()
        ->select([
            'el_news_category.id',
            'el_news_category.name',
            'el_news_category.parent_id',
        ])
        ->leftJoin('el_news_category as b','b.id','=','el_news_category.parent_id')
        ->where('el_news_category.sort',2)
        ->orderBy('b.stt_sort_parent', 'asc')
        ->orderBy('el_news_category.stt_sort', 'asc')->get();

        foreach($get_news_category_sort_right as $cate_right) {
            $cate_right->news_right = News::select(['id', 'image', 'title', 'date_setup_icon', 'created_at', 'views', 'like_new'])
            ->where('category_id',$cate_right->id)
            ->whereNotIn('id',$object_news_parent_cate_id)
            ->where('hot_public', 0)
            ->where('status',1)
            ->orderBy('hot','DESC')
            ->orderBy('created_at','DESC')
            ->take(3)->get();
            foreach($cate_right->news_right as $new_right) {
                $new_right->image = image_file($new_right->image);
                $new_right->checkDate = $date_now < $new_right->date_setup_icon ? asset('images/new1.gif') : '';
                $new_right->created_at2 = Carbon::parse($new_right->created_at)->format('d/m/Y');
                $new_right->check_user_like = in_array($new_right->id, $new_user_like) ? asset('images/check_like.png') : '';
            }
        }

        return response()->json([
            'get_news_category_sort_right' => $get_news_category_sort_right,
        ]);
    }

    // CHI TIẾT
    public function dataDetailNew($id)
    {
        $date_now = date('Y-m-d H:s');
        $news_links = NewsLink::where('news_id', $id)->get(['id','title','link','type']);
        foreach($news_links as $news_link) {
            if($news_link->type == 'file' && isFilePdf($news_link->link)) {
                $news_link->checkLink = route('module.news.view_pdf').'?path='.upload_file($news_link->link);
                $news_link->checkFilePdf = 1;
            } else {
                $news_link->checkLink = link_download('uploads/'.$news_link->link);
                $news_link->checkFilePdf = 0;
            }
            $news_link->titleName = $news_link->title ? $news_link->title : basename($news_link->link);
        }
        $user_like_new = Profile::select('like_new')->where('user_id',profile()->user_id)->first();
        $dt = Carbon::now('Asia/Ho_Chi_Minh');
        News::updateItemViews($id);
        $get_new = News::with('categoryNew:id,name,parent_id')->find($id);
        $get_new->dt = $dt->format('d/m/Y h:i A');
        $get_new->checkDate = $date_now < $get_new->date_setup_icon ? asset('images/new1.gif') : '';
        $get_new->created_at2 = Carbon::parse($get_new->created_at)->format('H:s d/m/Y');

        if(!empty($user_like_new->like_new) && in_array($get_new->id, json_decode($user_like_new->like_new))) {
            $get_new->checkLike = 1;
        } else {
            $get_new->checkLike = 0;
        }

        if($get_new->type == 2) {
            $get_new->content = image_file($get_new->content);
        } else if ($get_new->type == 3) {
            $pictures = json_decode($get_new->content);
            foreach($pictures as $picture) {
                $picture_format[] = image_file($picture);
            }
            $get_new->content = $picture_format;
        }
        $get_new->categoryParnetNew = NewsCategory::where('id', $get_new->categoryNew->parent_id)->first(['id','name']);

        /*Lưu lịch sử tương tác của HV*/
        $interaction_history = InteractionHistory::where(['user_id' => profile()->user_id, 'code' => 'news'])->first();
        if(isset($interaction_history)){
            $interaction_history->number = ($interaction_history->number + 1);
            $interaction_history->save();
        }else{
            $interaction_history = new InteractionHistory();
            $interaction_history->user_id = profile()->user_id;
            $interaction_history->code = 'news';
            $interaction_history->name = 'Tin tức';
            $interaction_history->number = 1;
            $interaction_history->save();
        }

        $setting_view = UserPointSettings::where('pkey', 'user_view_new')->where('item_id', $id)->where('item_type',9)->first();
        $userpoint_reward_views = UserPointResult::where('user_id', profile()->user_id)->where('type', 9)->where('item_id', $id)->where('setting_id', @$setting_view->id)->exists();
        if (isset($setting_view) && !$userpoint_reward_views) {
            $content_reward = 'Nhận điểm thưởng khi xem tin tức: '. $get_new->title;
            $save_point_reward_view = new UserPointResult();
            $save_point_reward_view->user_id = profile()->user_id;
            $save_point_reward_view->content = $content_reward;
            $save_point_reward_view->setting_id = $setting_view->id;
            $save_point_reward_view->point = $setting_view->pvalue;
            $save_point_reward_view->item_id = $id;
            $save_point_reward_view->type = 9;
            $save_point_reward_view->type_promotion = 1;
            $save_point_reward_view->save();

            $user_point_reward_view = PromotionUserPoint::firstOrNew(['user_id' => profile()->user_id]);
            $user_point_reward_view->point = (int)$user_point_reward_view->point + (int)$setting_view->pvalue;
            $user_point_reward_view->level_id = PromotionLevel::levelUp($user_point_reward_view->point, profile()->user_id);
            $user_point_reward_view->save();
        }

        return response()->json([
            'get_new' => $get_new,
            'news_links' => $news_links,
        ]);
    }

    // TIN TỨC LIÊN QUAN
    public function relatedNew($cate_id, $id, Request $request)
    {
        $date_now = date('Y-m-d H:i:s');
        News::addGlobalScope(new CompanyScope());
        NewsCategory::addGlobalScope(new CompanyScope());

        $get_new_user_like = Profile::where('user_id', profile()->user_id)->first(['like_new']);
        $new_user_like = !empty($get_new_user_like) ? json_decode($get_new_user_like->like_new) : [];

        $get_object_news_parent_cate_id = NewsObject::get(['new_id','unit_id']);
        $object_news_parent_cate_id = [];
        if( !$get_object_news_parent_cate_id->isEmpty() ) {
            $check_unit_array = [];
            $get_unit =  ProfileView::select(['unit_id'])->where('user_id', profile()->user_id)->first();
            foreach($get_object_news_parent_cate_id as $get_object_new_parent_cate_id) {
                $check_unit = NewsObject::checkUnitNewCate($get_object_new_parent_cate_id->unit_id, $get_unit->unit_id);
                if($check_unit == 1) {
                    $check_unit_array[] = $get_object_new_parent_cate_id->new_id;
                }
            }
            $object_news_parent_cate_id = NewsObject::whereNotIn('new_id', $check_unit_array)->pluck('new_id')->toArray();
        }

        $query = News::query();
        $query->select([
            'el_news.id',
            'el_news.title',
            'el_news.description',
            'el_news.date_setup_icon',
            'el_news.image',
            'el_news.created_at',
            'el_news.views',
            'el_news.like_new'
        ]);
        $checkCate = NewsCategory::where('id',$cate_id)->first(['parent_id']);
        if(isset($checkCate->parent_id)) {
            $query->where('el_news.category_id', '=', $cate_id);
        } else {
            $query->where('el_news.category_parent_id', '=', $cate_id);
        }

        $query->where('el_news.status', '=', 1);
        $query->where('el_news.id','!=',$id);
        $query->whereNotIn('el_news.id', $object_news_parent_cate_id);
        $query->orderByDesc('el_news.created_at');

        if(!empty($request->search) && $request->type == 1) {
            $query->whereDate('el_news.created_at', '=', $request->search);
        } else if (!empty($request->search) && $request->type == 0) {
            $query->whereNotIn('el_news.id', $request->search);
        }

        $get_related_news = $query->paginate(15);
        foreach($get_related_news as $get_related_new) {
            $get_related_new->image = image_file($get_related_new->image);
            $get_related_new->checkDate = $date_now < $get_related_new->date_setup_icon ? asset('images/new1.gif') : '';
            $get_related_new->created_at2 = Carbon::parse($get_related_new->created_at)->format('d/m/Y');
            $get_related_new->check_user_like = in_array($get_related_new->id, $new_user_like) ? asset('images/check_like.png') : '';
        }
        return response()->json([
            'get_related_news' => $get_related_news,
        ]);
    }

    public function cateNewsName($cate_id ,$type)
    {
        NewsCategory::addGlobalScope(new CompanyScope());
        $cate = NewsCategory::select('id','name','parent_id')->find($cate_id);
        if($type == 0) {
            $cate_new = $cate;
            $all_cate_name = NewsCategory::select(['id','name'])->where('parent_id',$cate_new->id)->get();
        } else {
            $cate_new = NewsCategory::select('id','name')->where('id',$cate->parent_id)->first();
            $all_cate_name = NewsCategory::select(['id','name'])->where('parent_id',$cate->parent_id)->get();
        }
        return response()->json([
            'all_cate_name' => $all_cate_name,
            'cate_new' => $cate_new
        ]);
    }

    // DỮ LIỆU TIN TỨC THEO DANH MỤC
    public function dataCateNews($cate_id ,$type)
    {
        $date_now = date('Y-m-d H:s');
        News::addGlobalScope(new CompanyScope());
        NewsCategory::addGlobalScope(new CompanyScope());

        $get_new_user_like = Profile::where('user_id', profile()->user_id)->first(['like_new']);
        $new_user_like = !empty($get_new_user_like) ? json_decode($get_new_user_like->like_new) : [];

        $get_object_news_parent_cate_id = NewsObject::get(['new_id','unit_id']);
        $object_news_parent_cate_id = [];
        if( !$get_object_news_parent_cate_id->isEmpty() ) {
            $check_unit_array = [];
            $get_unit =  ProfileView::select(['unit_id'])->where('user_id', profile()->user_id)->first();
            foreach($get_object_news_parent_cate_id as $get_object_new_parent_cate_id) {
                $check_unit = NewsObject::checkUnitNewCate($get_object_new_parent_cate_id->unit_id, $get_unit->unit_id);
                if($check_unit == 1) {
                    $check_unit_array[] = $get_object_new_parent_cate_id->new_id;
                }
            }
            $object_news_parent_cate_id = NewsObject::whereNotIn('new_id', $check_unit_array)->pluck('new_id')->toArray();
        }

        $get_related_news_hot = [];
        $news_id = [];
        if($type == 1){
            $get_hot_new_of_category = News::select(['id','title','date_setup_icon','description','image','created_at','like_new'])->where('hot',1)->where('status',1)->whereNotIn('id',$object_news_parent_cate_id)->where('category_id',$cate_id)->orderByDesc('created_at')->first();

            if (!empty($get_hot_new_of_category)) {
                $news_id[] = $get_hot_new_of_category->id;
                $get_hot_new_of_category->image = image_file($get_hot_new_of_category->image);
                $get_hot_new_of_category->checkDate = $date_now < $get_hot_new_of_category->date_setup_icon ? asset('images/new1.gif') : '';
                $get_hot_new_of_category->created_at2 = Carbon::parse($get_hot_new_of_category->created_at)->diffForHumans();

                $get_related_news_hot = News::select('title','id','description')->where('category_id',$cate_id)->whereNotIn('id',$object_news_parent_cate_id)->orderByDesc('created_at')->where('status',1)->where('hot',1)->where('id','!=',$get_hot_new_of_category->id)->get();
                foreach($get_related_news_hot as $item) {
                    $item->checkDate = $date_now < $item->date_setup_icon ? asset('images/new1.gif') : '';
                    $item->check_user_like = in_array($item->id, $new_user_like) ? asset('images/check_like.png') : '';
                    $news_id[] = $item->id;
                }
            }
        } else {
            $get_hot_new_of_category = News::where('hot',1)->where('category_parent_id', $cate_id)->whereNotIn('id',$object_news_parent_cate_id)->where('status',1)->orderByDesc('created_at')->first();

            if (!empty($get_hot_new_of_category)) {
                $news_id[] = $get_hot_new_of_category->id;
                $get_hot_new_of_category->image = image_file($get_hot_new_of_category->image);
                $get_hot_new_of_category->checkDate = $date_now < $get_hot_new_of_category->date_setup_icon ? asset('images/new1.gif') : '';
                $get_hot_new_of_category->created_at2 = Carbon::parse($get_hot_new_of_category->created_at)->diffForHumans();

                $get_related_news_hot = News::select('title','id','description')->where('hot',1)->where('category_parent_id', $cate_id)->whereNotIn('id',$object_news_parent_cate_id)->orderByDesc('created_at')->where('status',1)->where('id','!=',$get_hot_new_of_category->id)->take(3)->get();
                foreach($get_related_news_hot as $item) {
                    $item->checkDate = $date_now < $item->date_setup_icon ? asset('images/new1.gif') : '';
                    $item->check_user_like = in_array($item->id, $new_user_like) ? asset('images/check_like.png') : '';
                    $news_id[] = $item->id;
                }
            }
        }

        return response()->json([
            'get_hot_new_of_category' => $get_hot_new_of_category,
            'get_related_news_hot' => $get_related_news_hot,
            'news_id' => $news_id
        ]);
    }

    // chức năng like bài viết
    public function likeNew(Request $request) {
        $check_like = 0;
        $id_new = $request->id;
        $new = News::where('id',$id_new)->first();
        $profile = profile();
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

        $setting_like = UserPointSettings::where('pkey', 'user_like_new')->where('item_id', $id_new)->where('item_type',9)->first();
        $userpoint_reward_like = UserPointResult::where('user_id', profile()->user_id)->where('type', 9)->where('item_id', $id_new)->where('setting_id', @$setting_like->id)->exists();
        if (isset($setting_like) && !$userpoint_reward_like && $check_like == 1) {
            $content_reward = 'Nhận điểm thưởng khi thích tin tức: '. $new->title;
            $save_point_reward_like = new UserPointResult();
            $save_point_reward_like->user_id = profile()->user_id;
            $save_point_reward_like->content = $content_reward;
            $save_point_reward_like->setting_id = $setting_like->id;
            $save_point_reward_like->point = $setting_like->pvalue;
            $save_point_reward_like->item_id = $id_new;
            $save_point_reward_like->type = 9;
            $save_point_reward_like->type_promotion = 1;
            $save_point_reward_like->save();

            $user_point_reward_like = PromotionUserPoint::firstOrNew(['user_id' => profile()->user_id]);
            $user_point_reward_like->point = (int)$user_point_reward_like->point + (int)$setting_like->pvalue;
            $user_point_reward_like->level_id = PromotionLevel::levelUp($user_point_reward_like->point, profile()->user_id);
            $user_point_reward_like->save();
        }

        return json_result([
            'view_like'=>$new->like_new,
            'check_like'=>$check_like,
        ]);
    }

    // LẤY DANH SÁCH BÀI VIẾT XEM NHIỀU, THÍCH NHIỀU NHẤT
    public function dataNewViewLike($type)
    {
        $date_now = date('Y-m-d H:s');
        News::addGlobalScope(new CompanyScope());
        NewsCategory::addGlobalScope(new CompanyScope());

        $get_new_user_like = Profile::where('user_id', profile()->user_id)->first(['like_new']);
        $new_user_like = !empty($get_new_user_like) ? json_decode($get_new_user_like->like_new) : [];

        $get_object_news_parent_cate_id = NewsObject::get(['new_id','unit_id']);
        $object_news_parent_cate_id = [];
        if( !$get_object_news_parent_cate_id->isEmpty() ) {
            $check_unit_array = [];
            $get_unit =  ProfileView::select(['unit_id'])->where('user_id', profile()->user_id)->first();
            foreach($get_object_news_parent_cate_id as $get_object_new_parent_cate_id) {
                $check_unit = NewsObject::checkUnitNewCate($get_object_new_parent_cate_id->unit_id, $get_unit->unit_id);
                if($check_unit == 1) {
                    $check_unit_array[] = $get_object_new_parent_cate_id->new_id;
                }
            }
            $object_news_parent_cate_id = NewsObject::whereNotIn('new_id', $check_unit_array)->pluck('new_id')->toArray();
        }

        if($type == 0 ) {
            $news_view = News::select('title','id','views','created_at','image','date_setup_icon','like_new')->whereNotIn('id', $object_news_parent_cate_id)->orderByDesc('views')->where('status',1)->take(3)->get();
            foreach($news_view as $new_view) {
                $new_view->image = image_file($new_view->image);
                $new_view->checkDate = $date_now < $new_view->date_setup_icon ? asset('images/new1.gif') : '';
                $new_view->created_at2 = Carbon::parse($new_view->created_at)->format('d/m/Y');
                $new_view->check_user_like = in_array($new_view->id, $new_user_like) ? asset('images/check_like.png') : '';
            }

            $news_like = News::select('title','id','views','created_at','image','date_setup_icon','like_new')->whereNotIn('id', $object_news_parent_cate_id)->orderByDesc('like_new')->where('status',1)->take(3)->get();
            foreach($news_like as $new_like) {
                $new_like->image = image_file($new_like->image);
                $new_like->checkDate = $date_now < $new_like->date_setup_icon ? asset('images/new1.gif') : '';
                $new_like->created_at2 = Carbon::parse($new_like->created_at)->format('d/m/Y');
                $new_like->check_user_like = in_array($new_like->id, $new_user_like) ? asset('images/check_like.png') : '';
            }
            return response()->json([
                'news_view' => $news_view,
                'news_like' => $news_like,
            ]);
        } else if ($type == 1) {
            $news = News::select('title','id','views','created_at','image','date_setup_icon','like_new','description')->whereNotIn('id', $object_news_parent_cate_id)->orderByDesc('views')->where('status',1)->paginate(15);
            foreach($news as $new) {
                $new->image = image_file($new->image);
                $new->checkDate = $date_now < $new->date_setup_icon ? asset('images/new1.gif') : '';
                $new->created_at2 = Carbon::parse($new->created_at)->format('d/m/Y');
                $new->check_user_like = in_array($new->id, $new_user_like) ? asset('images/check_like.png') : '';
            }
            return response()->json([
                'news' => $news,
            ]);
        } else {
            $news = News::select('title','id','views','created_at','image','date_setup_icon','like_new','description')->whereNotIn('id', $object_news_parent_cate_id)->orderByDesc('like_new')->where('status',1)->paginate(10);
            foreach($news as $new) {
                $new->image = image_file($new->image);
                $new->checkDate = $date_now < $new->date_setup_icon ? asset('images/new1.gif') : '';
                $new->created_at2 = Carbon::parse($new->created_at)->format('d/m/Y');
                $new->check_user_like = in_array($new->id, $new_user_like) ? asset('images/check_like.png') : '';
            }
            return response()->json([
                'news' => $news,
            ]);
        }


    }
}
