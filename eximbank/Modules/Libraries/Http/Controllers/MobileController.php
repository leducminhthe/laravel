<?php

namespace Modules\Libraries\Http\Controllers;

use App\Models\UserPermissionType;
use App\Scopes\CompanyScope;
use App\Scopes\DraftScope;
use App\Models\Slider;
use App\Models\Warehouse;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Libraries\Entities\Libraries;
use Modules\Libraries\Entities\LikeLibraries;
use Modules\Libraries\Entities\LibrariesRatting;
use App\Models\User;
use Modules\Libraries\Entities\LibrariesBookmark;
use Modules\Libraries\Entities\LibrariesCategory;
use Modules\Libraries\Entities\LibrariesObject;
use Modules\Libraries\Entities\RegisterBook;
use Modules\Libraries\Entities\LibrariesMoreVideo;
use Modules\Libraries\Entities\LibrariesMoreAudiobook;
use App\Models\Notifications;
use Modules\AppNotification\Helpers\AppNotification;
use Illuminate\Support\Str;
use App\Models\Permission;
use App\Models\Profile;
use App\Models\ProfileView;
use Modules\Notify\Entities\Notify;
use App\Models\Categories\Unit;
use App\Models\Categories\Titles;
use App\Models\LibrariesStatistic;

class MobileController extends Controller
{
    public function index()
    {
        if(!userThird()){
            Libraries::addGlobalScope(new CompanyScope());
        }

        $get_news_libraries = Libraries::select(['id','image','type'])->where('status',1)->orderByDesc('created_at')->take(6)->get();
        $get_news_libraries_array = Libraries::where('status',1)->orderByDesc('created_at')->take(6)->pluck('id')->toArray();

        $get_news_book = Libraries::select(['id','image'])->where('type',1)->whereNotIn('id',$get_news_libraries_array)->orderByDesc('created_at')->take(6)->get();
        $get_news_ebook = Libraries::select(['id','image'])->where('type',2)->whereNotIn('id',$get_news_libraries_array)->orderByDesc('created_at')->take(6)->get();
        $get_news_document = Libraries::select(['id','image'])->where('type',3)->whereNotIn('id',$get_news_libraries_array)->orderByDesc('created_at')->take(6)->get();
        $get_news_video = Libraries::select(['id','image'])->where('type',4)->whereNotIn('id',$get_news_libraries_array)->orderByDesc('created_at')->take(6)->get();
        $get_news_audiobook = Libraries::select(['id','image'])->where('type',5)->whereNotIn('id',$get_news_libraries_array)->orderByDesc('created_at')->take(6)->get();

        return view('themes.mobile.frontend.libraries.index',[
            'get_news_libraries' => $get_news_libraries,
            'get_news_book' => $get_news_book,
            'get_news_document' => $get_news_document,
            'get_news_ebook' => $get_news_ebook,
            'get_news_video' => $get_news_video,
            'get_news_audiobook' => $get_news_audiobook,
        ]);
    }

    public function book($id, Request $request)
    {
        $search_cate = $request->input('search_cate');
        $search_author = $request->input('search_author');
        $search = $request->input('search');
        $type = $request->input('type');

        if(!userThird()){
            Libraries::addGlobalScope(new CompanyScope());
        }
        $query = Libraries::query();
        $query->select([
            'el_libraries.id',
            'el_libraries.name',
            'el_libraries.name_author',
            'el_libraries.views',
            'el_libraries.created_at',
            'el_libraries.type',
        ]);
        $query->where('el_libraries.type', '=', 1);
        $query->where('el_libraries.status', '=', 1);
        $query->orderByDesc('el_libraries.id');

        if($search){
            $query->where('name','like','%' . $search . '%');
        }

        if($search_author){
            $query->where('name_author','like','%' . $search_author . '%');
        }

        if ($search_cate){
            return redirect()->route('themes.mobile.frontend.libraries.book',['id' => $search_cate]);
        }

        if($id > 0){
            $cate_with_id = LibrariesCategory::find($id);
            $query->where('category_parent','like', '%' . $cate_with_id->name . '%');
        }

        if ($type) {
            $query->Join('el_register_book as b','b.book_id','=','el_libraries.id');
            $query->where('b.user_id', profile()->user_id);
            if ($type == 1) {
                $query->where('b.approved','=',2);
            } else if ($type == 2) {
                $query->where('b.approved',1);
                $query->where('b.status',1);
            } else {
                $query->where('b.approved',1);
                $query->where('b.status',2);
            }
        }

        if($search_author || $search || $type) {
            $books = $query->get();
        } else {
            $books = $query->paginate(8);
        }

        return view('themes.mobile.frontend.libraries.book.index', [
            'books' => $books,
        ]);
    }

    public function ebook($id, Request $request)
    {
        $search_cate = $request->input('search_cate');
        $search = $request -> input('search');
        $search_author = $request->input('search_author');

        if(!userThird()){
            Libraries::addGlobalScope(new CompanyScope());
        }
        $ebooks = Libraries::getListEbook($id, $search_cate, $search, $search_author);

        if ($search_cate){
            return redirect()->route('themes.mobile.frontend.libraries.ebook',['id' => $search_cate]);
        }

        return view('themes.mobile.frontend.libraries.ebook.index', [
            'ebooks' => $ebooks,
        ]);
    }

    public function document($id, Request $request)
    {
        $search_cate = $request->input('search_cate');
        $search = $request -> input('search');
        $search_author = $request->input('search_author');

        if(!userThird()){
            Libraries::addGlobalScope(new CompanyScope());
        }
        $documents = Libraries::getListDocument($id, $search_cate, $search, $search_author);

        if ($search_cate){
            return redirect()->route('themes.mobile.frontend.libraries.document',['id' => $search_cate]);
        }

        return view('themes.mobile.frontend.libraries.doc.index', [
            'documents' => $documents,
        ]);
    }

    public function video($id, Request $request)
    {
        $search_cate = $request->input('search_cate');
        $search = $request -> input('search');
        $search_author = $request->input('search_author');

        if(!userThird()){
            Libraries::addGlobalScope(new CompanyScope());
        }
        $video = Libraries::getListVideo($id, $search_cate, $search, $search_author);

        if ($search_cate){
            return redirect()->route('themes.mobile.frontend.libraries.video',['id' => $search_cate]);
        }

        return view('themes.mobile.frontend.libraries.video.index', [
            'video' => $video,
        ]);
    }
    // sách nói
    public function audiobook($id, Request $request)
    {
        $search_cate = $request->input('search_cate');
        $search = $request -> input('search');
        $search_author = $request->input('search_author');

        if(!userThird()){
            Libraries::addGlobalScope(new CompanyScope());
        }
        $audiobook = Libraries::getListAudiobook($id, $search_cate, $search, $search_author);

        if ($search_cate){
            return redirect()->route('themes.mobile.frontend.libraries.audiobook',['id' => $search_cate]);
        }

        return view('themes.mobile.frontend.libraries.audiobook.index', [
            'audiobook' => $audiobook,
        ]);
    }
    // end sách nói

    public function bookDetail($id)
    {
        $item = Libraries::findOrFail($id);
        $item->views = $item->views + 1;
        $item->save();

        LibrariesStatistic::update_libraries_insert_statistic(0);

        return view('themes.mobile.frontend.libraries.book.detail', [
            'item' => $item,
        ]);
    }

    public function ebookDetail($id)
    {
        $item = Libraries::findOrFail($id);
        $item->views = $item->views + 1;
        $item->save();

        LibrariesStatistic::update_libraries_insert_statistic(0);

        return view('themes.mobile.frontend.libraries.ebook.detail', [
            'item' => $item,
        ]);
    }

    public function documentDetail($id)
    {
        $item = Libraries::findOrFail($id);
        $item->views = $item->views + 1;
        $item->save();

        LibrariesStatistic::update_libraries_insert_statistic(0);

        return view('themes.mobile.frontend.libraries.doc.detail', [
            'item' => $item,
        ]);
    }

    public function videoDetail($id)
    {
        $item = Libraries::findOrFail($id);
        $item->views = $item->views + 1;
        $item->save();

        LibrariesStatistic::update_libraries_insert_statistic(0);

        return view('themes.mobile.frontend.libraries.video.detail', [
            'item' => $item,
        ]);
    }

    public function audiobookDetail($id)
    {
        $item = Libraries::findOrFail($id);
        $item->views = $item->views + 1;
        $item->save();

        LibrariesStatistic::update_libraries_insert_statistic(0);

        return view('themes.mobile.frontend.libraries.audiobook.detail', [
            'item' => $item,
        ]);
    }

    public function registerBook($book_id, Request $request){
        $this->validateRequest([
            'quantity'=>'required'
        ], $request);

        $book = Libraries::find($book_id);
        $quantily = $request->input('quantity');

        $model = new RegisterBook();
        $model->user_id = profile()->user_id;
        $model->book_id = $book_id;
        $model->quantity = $quantily;
        $model->register_date = Carbon::now();
        $model->approved = 2;
        $model->status = 1;

        if ($quantily > $book->current_number){
            json_message('Số lượng còn lại không đủ', 'error');
        }

        $exists = RegisterBook::where('user_id', profile()->user_id)
            ->where('book_id', '=', $book_id)
            ->where('approved', '=', 1)
            //->orWhere('approved', '=', 0)
            ->where(function ($sub) {
                $sub->where('status',1);
                $sub->orWhere('status',2);
            })
            ->exists();
        if ($exists){
            json_message('Sách còn đang mượn', 'error');
        }
        $save = $model->save();
        $library = Libraries::find($book_id);
        $library->current_number -= $quantily;
        $library->save();
        if ($save) {
            $unit_id = [];
            $unit = Unit::getTreeParentUnit(Profile::getUnitCode());
            foreach ($unit as $item){
                $unit_id[] = $item->id;
            }
            $query = UserPermissionType::query()
                ->from('el_user_permission_type as a')
                ->leftJoin('el_permission_type_unit as b', 'b.permission_type_id', '=', 'a.permission_type_id')
                ->leftJoin('el_permissions as c', 'c.id', '=', 'a.permission_id')
                ->where(function ($sub) use ($unit_id){
                    $sub->orWhere(function ($sub1) use ($unit_id){
                        $sub1->where('b.type', '=', 'group-child')
                            ->whereIn('b.unit_id', $unit_id);
                    });
                    $sub->orWhere(function ($sub2){
                        $sub2->where('b.type', '=', 'owner')
                            ->where('b.unit_id', '=', Profile::getUnitId());
                    });
                })
                ->whereIn('c.name', function ($sub2){
                    $sub2->select(['per.parent'])
                        ->from('el_model_has_permissions as model')
                        ->leftJoin('el_permissions as per', 'per.id', '=', 'model.permission_id')
                        ->whereColumn('model.model_id', '=', 'a.user_id')
                        ->where('per.name', '=', 'libraries-book-register-approve')
                        ->orWhere('per.name', '=', 'libraries-book-register');
                })
                ->where('c.name', '=', 'user')
                ->pluck('a.user_id')->toArray();

            $user_managers = $query;
            if (count($user_managers) > 0){
                foreach ($user_managers as $user) {
                    $model = new Notify();
                    $model->user_id = $user;
                    $model->subject = 'Duyệt đăng ký mượn sách';
                    $model->content = 'Nhân viên '. Profile::fullname(profile()->user_id) .' vừa đăng ký mượn sách. Vui lòng vào quản trị để duyệt thay đổi';
                    $model->url = '';
                    $model->created_by = 0;
                    $model->save();

                    $content = \Str::words(html_entity_decode(strip_tags($model->content)), 10);
                    $redirect_url = route('module.notify.view', [
                        'id' => $model->id,
                        'type' => 1
                    ]);

                    $notification = new AppNotification();
                    $notification->setTitle($model->subject);
                    $notification->setMessage($content);
                    $notification->setUrl($redirect_url);
                    $notification->add($user);
                }
                $notification->save();
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => trans('app.register_success'),
            'redirect' => route('themes.mobile.libraries.book.detail', ['id' => $book_id])
        ]);
    }

    public function updateItemViews(Request $request){
        $model = Libraries::find($request->id);
        $model->views = $model->views + 1;
        $model->save();
    }

    public function saveLibrariesBookmark($id, $type){
        $model = new LibrariesBookmark();
        $model->libraries_id = $id;
        $model->type = $type;
        $model->user_id = profile()->user_id;
        $model->save();

        if ($type == 1){
            json_result([
                'status' => 'success',
                'message' => 'Đã đánh dấu',
                'redirect' => route('themes.mobile.frontend.libraries.book',['id' => 0])
            ]);
        }
        if ($type == 2){
            json_result([
                'status' => 'success',
                'message' => 'Đã đánh dấu',
                'redirect' => route('themes.mobile.frontend.libraries.ebook',['id' => 0])
            ]);
        }
        if ($type == 3){
            json_result([
                'status' => 'success',
                'message' => 'Đã đánh dấu',
                'redirect' => route('themes.mobile.frontend.libraries.document',['id' => 0])
            ]);
        }
    }

    public function removeLibrariesBookmark($id, $type){
        LibrariesBookmark::query()
            ->where('libraries_id', '=', $id)
            ->where('type', '=', $type)
            ->where('user_id', '=', profile()->user_id)
            ->delete();

        if ($type == 1){
            json_result([
                'status' => 'success',
                'message' => 'Đã bỏ đánh dấu',
                'redirect' => route('themes.mobile.frontend.libraries.book',['id' => 0])
            ]);
        }
        if ($type == 2){
            json_result([
                'status' => 'success',
                'message' => 'Đã bỏ đánh dấu',
                'redirect' => route('themes.mobile.frontend.libraries.ebook',['id' => 0])
            ]);
        }
        if ($type == 3){
            json_result([
                'status' => 'success',
                'message' => 'Đã bỏ đánh dấu',
                'redirect' => route('themes.mobile.frontend.libraries.document',['id' => 0])
            ]);
        }
    }

    public function viewPDF($id){
        $item = Libraries::findOrFail($id);
        $item->views = $item->views + 1;
        $item->save();

        $path = $item->getLinkView();
        $path = convert_url_web_to_app($path);

        return view('themes.mobile.frontend.libraries.view_pdf', [
            'path' => $path,
        ]);
    }

    // tăng số lần tải
    public function download($id) {
        $item = Libraries::findOrFail($id);
        $item->download = $item->download + 1;
        $item->save();
        return $count_download = $item->download;
    }

    // chức năng like
    public function like(Request $request) {
        $check_like = 0;
        $id_new = $request->id;
        $libraries = Libraries::where('id',$id_new)->first();
        $profile = LikeLibraries::where('user_id',profile()->user_id)->first();
        if ($profile == null) {
            $check_like = 1;
            $profile = new LikeLibraries;
            $set_profile_like_libraries = [$id_new];
            $profile->libraries_id = json_encode($set_profile_like_libraries);
            $profile->user_id = profile()->user_id;
            $profile->save();
            $like_libraries = $libraries->like_libraries + 1;
            $libraries->like_libraries = $like_libraries;
            $libraries->save();
            return json_result([
                    'view_like'=>$libraries->like_libraries,
                    'check_like'=>$check_like,
                ]);
        }
        $get_profile_like_libraries = json_decode($profile->libraries_id);
        if (($key = array_search($id_new, $get_profile_like_libraries)) !== false) {
            unset($get_profile_like_libraries[$key]);
            $newarray = array_values($get_profile_like_libraries);
            $profile->libraries_id = json_encode($newarray);
            $like_libraries = $libraries->like_libraries - 1;
        } else {
            array_push($get_profile_like_libraries, $id_new);
            $profile->libraries_id = json_encode($get_profile_like_libraries);
            $like_libraries = $libraries->like_libraries + 1;
            $check_like = 1;
        }
        $profile->save();
        $libraries->like_libraries = $like_libraries;
        $libraries->save();
        return json_result([
                'view_like'=>$libraries->like_libraries,
                'check_like'=>$check_like,
            ]);
    }

    // AJAX LẤY TÊN TÁC GIẢ
    public function getAuthor(Request $request) {
		$search = $request->search_author;
        $type = $request->type;
        $get_authors = Libraries::select('name_author')->where('name_author','like','%' . $search . '%')->where('type', $type)->groupBy('name_author')->get();
        json_result($get_authors);
	}

    // AJAX LẤY TÊN THƯ VIỆN
    public function getNameLibraries(Request $request) {
		$search = $request->search_libraries;
        $type = $request->type;
        $get_name_libraries = Libraries::where('name','like','%' . $search . '%')->where('type', $type)->get();
        json_result($get_name_libraries);
	}

    // ĐÁNH GIÁ SAO
    public function ratting(Request $request) {
        $check_ratting = LibrariesRatting::where('user_id', '=', profile()->user_id)->where('libraries_id', $request->id)->first();
        if ($check_ratting === null) {
            $model = new LibrariesRatting();
            $model->user_id = profile()->user_id;
            $model->ratting = $request->star;
            $model->libraries_id = $request->id;
            $model->save();
            json_result([
                'status' => 'success',
                'message' => 'Cảm ơn bạn đã đánh giá',
            ]);
        } else {
            json_result([
                'status' => 'warning',
                'message' => trans('laother.you_rated'),
            ]);
        }
    }

    public function cateLibraries($cate_id, $type) {
        $check_cate_parent = LibrariesCategory::where('id',$cate_id)->where('type',$type)->first();

        if(empty($check_cate_parent->parent_id)) {
            $cate_libraries = LibrariesCategory::where('type', $type)->whereNull('parent_id')->get();
        } else {
            $cate_libraries = LibrariesCategory::where('type', $type)->where('parent_id',$check_cate_parent->parent_id)->get();
        }
        return view('themes.mobile.frontend.libraries.cate_libraries',[
            'cate_libraries' => $cate_libraries,
            'cate_id' => $cate_id,
            'type' => $type,
        ]);
    }

    public function search(Request $request) {
        $type = $request->type;
        $search = $request->search;
        $cate_id = $request->cate_id;
        $model = LibrariesCategory::query();
        $model->where('type',$type);

        $cates_item = '';
        $get_cate_with_id = '';
        $get_libraries = '';
        if($cate_id > 0) {
            $model->where('parent_id',$cate_id);
            $get_cate_with_id = LibrariesCategory::find($cate_id);
            $cates_search = $model->get();

            $query = Libraries::query();
            $query->where('type',$type);
            $query->where(function($sub) use ($get_cate_with_id){
                $sub->where('category_id',$get_cate_with_id->id);
                $sub->orWhere('category_parent','like','%'. $get_cate_with_id->name .'%');
            });
            $query->where('status',1);
            if($search) {
                $query->where(function($sub) use ($search) {
                    $sub->where('name','like','%'. $search .'%');
                    $sub->orwhere('name_author','like','%'. $search .'%');
                });
            }
            $cates_item = $query->get();
        } else {
            $model->whereNull('parent_id');
            $cates_search = $model->get();
            $query = Libraries::query();
            $query->where('type',$type);
            $query->where(function($sub) use ($search) {
                $sub->where('name','like','%'. $search .'%');
                $sub->orwhere('name_author','like','%'. $search .'%');
            });
            $get_libraries = $query->get();
        }

        if($search && !$type) {
            $query = Libraries::query();
            $query->where(function($sub) use ($search) {
                $sub->where('name','like','%'. $search .'%');
                $sub->orwhere('name_author','like','%'. $search .'%');
            });
            $get_libraries = $query->get();
        }

        $get_news_libraries = Libraries::where('status',1)->where('type',$type)->orderByDesc('created_at')->get()->take(6);

        return view('themes.mobile.frontend.libraries.search',[
            'type' => $type,
            'search' => $search,
            'get_libraries' => $get_libraries,
            'cates_search' => $cates_search,
            'cates_item' => $cates_item,
            'cate_id' => $cate_id,
            'get_cate_with_id' => $get_cate_with_id,
            'get_news_libraries' => $get_news_libraries
        ]);
    }

    public function salekit(){
        $category = LibrariesCategory::whereType(6)->get();

        return view('themes.mobile.frontend.libraries.salekit.salekit', [
            'category' => $category
        ]);
    }

    public function salekitDetail($cate_id){
        $category = LibrariesCategory::find($cate_id);
        $libraries = Libraries::whereCategoryId($cate_id)->where('type', 6)->get();

        $libraries_obj = function ($lib_id){
            $profile = profile();
            $object = LibrariesObject::where('libraries_id', '=', $lib_id);

            if(!$object->exists()){
                return true;
            }else{
                $status = LibrariesObject::where(function($sub) use ($profile){
                    $sub->orWhere('user_id', '=', $profile->user_id)
                    ->orWhere('title_id', '=', $profile->title_id)
                    ->orWhere('unit_id', '=', $profile->unit_id);
                })
                ->where('libraries_id', '=', $lib_id)
                ->where('type', '=', 6)
                ->whereIn('status', [1,3]);

                return $status->exists();
            }
        };

        return view('themes.mobile.frontend.libraries.salekit.salekit_detail', [
            'category' => $category,
            'libraries' => $libraries,
            'libraries_obj' => $libraries_obj,
        ]);
    }
}
