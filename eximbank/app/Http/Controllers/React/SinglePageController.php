<?php

namespace App\Http\Controllers\React;

use App\Models\ProfileView;
use App\Models\User;
use App\Models\UserPermissionType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Libraries\Entities\LibrariesCategory;
use Modules\Libraries\Entities\Libraries;
use App\Scopes\CompanyScope;
use Carbon\Carbon;
use Modules\Libraries\Entities\LibrariesRatting;
use Modules\Libraries\Entities\LibrariesObject;
use App\Models\Categories\Unit;
use App\Models\InteractionHistory;
use App\Models\LibrariesStatistic;
use Illuminate\Support\Str;
use Modules\Libraries\Entities\RegisterBook;
use Modules\Notify\Entities\Notify;
use Modules\AppNotification\Helpers\AppNotification;
use App\Models\Profile;
use Modules\Libraries\Entities\LibrariesFileZip;
use Modules\Libraries\Entities\LibrariesMoreAudiobook;
use Modules\Libraries\Entities\LibrariesMoreVideo;
use Modules\UserPoint\Entities\UserPointResult;
use Modules\Promotion\Entities\PromotionUserPoint;
use Modules\Promotion\Entities\PromotionLevel;
use Modules\UserPoint\Entities\UserPointSettings;

class SinglePageController extends Controller
{
    public function index($type)
    {
        return view('react.library.index');
    }

    public function getLibraryBooks(Request $request)
    {
        $search_cate = $request->input('searchCate');
        $search_author = $request->input('searchAuthor');
        $search = $request->input('search');
        $status = $request->input('status');
        $type = $request->type;

        Libraries::addGlobalScope(new CompanyScope());
        $query = Libraries::query();
        $query->select([
            'el_libraries.id',
            'el_libraries.name',
            'el_libraries.name_author',
            'el_libraries.views',
            'el_libraries.created_at',
            'el_libraries.type',
            'el_libraries.image',
        ]);
        $query->where('el_libraries.type', '=', $type);
        $query->where('el_libraries.status', '=', 1);
        $query->orderByDesc('el_libraries.id');

        if($search){
            $query->where(function($sub_query) use ($search){
                $sub_query->orWhere('name','like','%' . $search . '%');
            });
        }

        if($search_author){
            $query->where(function($sub_query) use ($search_author){
                $sub_query->orWhere('name_author','like','%' . $search_author . '%');
            });
        }

        if ($status) {
            $query->Join('el_register_book as b','b.book_id','=','el_libraries.id');
            $query->where('b.user_id', profile()->user_id);
            if ($status == 1) {
                $query->where('b.approved','=',2);
            } else if ($status == 2) {
                $query->where('b.approved',1);
                $query->where('b.status',1);
            } else {
                $query->where('b.approved',1);
                $query->where('b.status',2);
            }
        }

        $all_name_cate_book = [];

        if ($search_cate){
            $cate_with_id = LibrariesCategory::find($search_cate,['name']);
            $query->where('category_parent','like', '%' . $cate_with_id->name . '%');
            $get_category_books = LibrariesCategory::where('type', $type)->where('parent_id',$search_cate)->get(['id','name']);
            $all_name_cate_book = LibrariesCategory::getTreeParentUnit($search_cate);
        } else {
            $get_category_books = LibrariesCategory::whereNull('parent_id')->where('type', $type)->get(['id','name']);
        }

        $get_books = $query->paginate(12);
        foreach ($get_books as $key => $get_book) {
            $get_book->image = image_library($get_book->image);
            $get_book->time = Carbon::parse($get_book->created_at)->diffForHumans();
            $isRating = LibrariesRatting::where('libraries_id', $get_book->id)->where('user_id', profile()->user_id)->first(['ratting']);
            $get_book->isRating = $isRating;
        }
        return response()->json([
            'get_books' => $get_books,
            'get_category_books' => $get_category_books,
            'all_name_cate_book' => $all_name_cate_book
        ]);
    }

    public function rattingStartLibrary(Request $request)
    {
        $id = $request->id;
        $check_ratting = LibrariesRatting::where('user_id', '=', profile()->user_id)->where('libraries_id', $request->id)->first();
        $setting_rating = UserPointSettings::where('pkey', 'user_rating_libraries')->where('item_id', $id)->where('item_type', 6)->first();
        $userpoint_reward_rating = UserPointResult::where('user_id', profile()->user_id)->where('type', 6)->where('item_id', $id)->where('setting_id', @$setting_rating->id)->exists();
        if ($check_ratting === null) {
            if (isset($setting_rating) && !$userpoint_reward_rating) {
                $libraries_name = Libraries::find($request->id, ['name','type']);
                if ($libraries_name->type == 1) {
                    $subject = 'Điểm thưởng đánh giá sao sách giấy';
                    $content = 'Nhận điểm thưởng đánh giá sao sách giấy: '. $libraries_name->name;
                } elseif($libraries_name->type == 2) {
                    $subject = 'Điểm thưởng đánh giá sao sách diện tử';
                    $content = 'Nhận điểm thưởng đánh giá sao sách điện tử: '. $libraries_name->name;
                } elseif ($libraries_name->type == 3) {
                    $subject = 'Điểm thưởng đánh giá sao tài liệu';
                    $content = 'Nhận điểm thưởng đánh giá sao tài liệu: '. $libraries_name->name;
                } elseif($libraries_name->type == 4) {
                    $subject = 'Điểm thưởng đánh giá sao video';
                    $content = 'Nhận điểm thưởng đánh giá sao video: '. $libraries_name->name;
                } else {
                    $subject = 'Điểm thưởng đánh giá sao sách nói';
                    $content = 'Nhận điểm thưởng đánh giá sao sách nói: '. $libraries_name->name;
                }

                $save_point_reward_view = new UserPointResult();
                $save_point_reward_view->user_id = profile()->user_id;
                $save_point_reward_view->content = $content;
                $save_point_reward_view->setting_id = $setting_rating->id;
                $save_point_reward_view->point = $setting_rating->pvalue;
                $save_point_reward_view->item_id = $id;
                $save_point_reward_view->type = 6;
                $save_point_reward_view->type_promotion = 1;
                $save_point_reward_view->save();

                $user_point = PromotionUserPoint::firstOrNew(['user_id' => profile()->user_id]);
                $user_point->point = (int)$user_point->point + (int)$setting_rating->pvalue;
                $user_point->level_id = PromotionLevel::levelUp($user_point->point, profile()->user_id);
                $user_point->save();

                $query = new Notify();
                $query->user_id = profile()->user_id;
                $query->subject = $subject;
                $query->content = $content;
                $query->url = '';
                $query->created_by = 0;
                $query->save();

                $content = \Str::words(html_entity_decode(strip_tags($query->content)), 10);
                $redirect_url = route('module.notify.view', [
                    'id' => $query->id,
                    'type' => 1
                ]);

                $notification = new AppNotification();
                $notification->setTitle($query->subject);
                $notification->setMessage($content);
                $notification->setUrl($redirect_url);
                $notification->add(profile()->user_id);
                $notification->save();
            }

            $model = new LibrariesRatting();
            $model->user_id = profile()->user_id;
            $model->ratting = $request->i;
            $model->libraries_id = $request->id;
            $model->save();
            return response()->json([
                'status' => 'success',
                'message' => 'Cảm ơn bạn đã đánh giá',
            ]);
        } else {
            return response()->json([
                'status' => 'warning',
                'message' => trans('laother.you_rated'),
            ]);
        }
    }

    public function detailLibraryBook($id)
    {
        $get_unit =  ProfileView::select('unit_id')->where('user_id', profile()->user_id)->first();
        $check_register = RegisterBook::where('user_id', profile()->user_id)->where('book_id', $id)->exists();

        $item = Libraries::findOrFail($id);
        $item->views = $item->views + 1;
        $item->save();
        $item->image = image_library($item->image);
        $item->check_register = !$check_register ? 1 : 0;

        $get_related_libraries = Libraries::where('category_id',$item->category_id)->where('id','!=',$item->id)->where('type',1)->where('status', 1)->get(['id','type','image','name']);
        $related_libraries = [];
        foreach($get_related_libraries as $related_library) {
            $related_library->image = image_library($related_library->image);
            $get_object_libraries = LibrariesObject::where('libraries_id',$related_library->id)->whereNotNull('unit_id')->where('type', $related_library->type)->get();
            $check_unit = 0;
            if ( !$get_object_libraries->isEmpty() ) {
                foreach ($get_object_libraries as $get_object_librarie) {
                    $unit_code = Unit::select('code')->find($get_object_librarie->unit_id);
                    $get_array_childs = Unit::getArrayChild($unit_code->code);
                    if( in_array($get_unit->unit_id, $get_array_childs) || $get_unit->unit_id == $get_object_librarie->unit_id) {
                        $check_unit = 1;
                    }
                }
            }
            if((!$get_object_libraries->isEmpty() && $check_unit == 1) || $get_object_libraries->isEmpty()) {
                $related_libraries[] = $related_library;
            }
        }


        LibrariesStatistic::update_libraries_insert_statistic(0);

        /*Lưu lịch sử tương tác của HV*/
        $interaction_history = InteractionHistory::where(['user_id' => profile()->user_id, 'code' => 'libraries'])->first();
        if($interaction_history){
            $interaction_history->number = ($interaction_history->number + 1);
            $interaction_history->save();
        }else{
            $interaction_history = new InteractionHistory();
            $interaction_history->user_id = profile()->user_id;
            $interaction_history->code = 'libraries';
            $interaction_history->name = 'Thư viện';
            $interaction_history->number = 1;
            $interaction_history->save();
        }

        return response()->json([
            'item' => $item,
            'related_libraries' => $related_libraries
        ]);
    }

    public function registerBookLibrary($book_id, Request $request)
    {
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

        $exists = RegisterBook::where('user_id', profile()->user_id)
            ->where('book_id', '=', $book_id)
            ->where('approved', '=', 1)
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
            'current_number' => $library->current_number,
        ]);
    }

    public function detailLibraryEbook($id)
    {
        $profile = ProfileView::select(['unit_id','title_id'])->where('user_id',profile()->user_id)->first();
        $check_status_libraries_obj = 3;

        $item = Libraries::findOrFail($id);
        $item->views = $item->views + 1;
        $item->save();
        $item->image = image_library($item->image);
        $item->checkIsFilePdf = $item->isFilePdf();

        $item->link_view = '';
        if(!$item->isFileZip()){
            if(!$item->isFilePdf() && !$item->isFileImg()) {
                $link = 'https://view.officeapps.live.com/op/embed.aspx?src='. upload_file($item->attachment);
                $item->link_view = $link;
            } else if ($item->isFileImg()) {
                $link = upload_file($item->attachment);
                $item->link_view = $link;
            }
        }

        $item->getLinkDownload = $item->getLinkDownload();

        if ($profile->title_id !== null || $profile->unit_id !== null) {
            $libraries_object_title = LibrariesObject::where('title_id',$profile->title_id)->where('libraries_id',$id)->first();
            $libraries_object_unit = LibrariesObject::where('unit_id',$profile->unit_id)->where('libraries_id',$id)->first();
            if ($libraries_object_title !== null && $libraries_object_unit == null) {
                if ($libraries_object_title->status == 1) {
                    $check_status_libraries_obj = 1;
                } else if($libraries_object_title->status == 2) {
                    $check_status_libraries_obj = 2;
                }
            } else if ($libraries_object_title !== null && $libraries_object_unit !== null) {
                if($libraries_object_title->status == 1 && $libraries_object_unit->status == 1) {
                    $check_status_libraries_obj = 1;
                } else if($libraries_object_title->status == 2 && $libraries_object_unit->status == 2) {
                    $check_status_libraries_obj = 2;
                }
            } else if($libraries_object_unit !== null && $libraries_object_title == null) {
                if ($libraries_object_unit->status == 1) {
                    $check_status_libraries_obj = 1;
                } else if($libraries_object_unit->status == 2) {
                    $check_status_libraries_obj = 2;
                }
            }
        }

        $lib_zip = LibrariesFileZip::where('libraries_id', $id)->where('status', 1)->first();
        $item->link_file_zip = '';
        if($lib_zip){
            $storage = \Storage::disk(config('app.datafile.upload_disk'));
            $item->link_file_zip = $storage->url($lib_zip->unzip_path) . '/' . $lib_zip->index_file;
        }

        $get_related_libraries = Libraries::select(['id','image','type','name'])->where('category_id',$item->category_id)->where('type', $item->type)->where('id','!=',$item->id)->where('status', 1)->get();
        $related_libraries = [];
        foreach($get_related_libraries as $related_library) {
            $related_library->image = image_library($related_library->image);
            $get_object_libraries = LibrariesObject::where('libraries_id',$related_library->id)->whereNotNull('unit_id')->where('type', $related_library->type)->get();
            $check_unit = 0;
            if ( !$get_object_libraries->isEmpty() ) {
                foreach ($get_object_libraries as $get_object_librarie) {
                    $unit_code = Unit::select('code')->find($get_object_librarie->unit_id);
                    $get_array_childs = Unit::getArrayChild($unit_code->code);
                    if( in_array($profile->unit_id, $get_array_childs) || $profile->unit_id == $get_object_librarie->unit_id) {
                        $check_unit = 1;
                    }
                }
            }
            if((!$get_object_libraries->isEmpty() && $check_unit == 1) || $get_object_libraries->isEmpty()) {
                $related_libraries[] = $related_library;
            }
        }

        LibrariesStatistic::update_libraries_insert_statistic(0);
        return response()->json([
            'item' => $item,
            'check_status_libraries_obj' => $check_status_libraries_obj,
            'related_libraries' => $related_libraries,
        ]);
    }

    // tăng số lần tải
    public function download($id) {
        $setting_download = UserPointSettings::where('pkey', 'user_download_libraries')->where('item_id', $id)->where('item_type', 6)->first();
        $userpoint_reward_download = UserPointResult::where('user_id', profile()->user_id)->where('type', 6)->where('item_id', $id)->where('setting_id', @$setting_download->id)->exists();
        if (isset($setting_download) && !$userpoint_reward_download) {
            $libraries_name = Libraries::find($id, ['name','type']);
            if($libraries_name->type == 2) {
                $subject = 'Điểm thưởng tải về sách diện tử';
                $content = 'Nhận điểm thưởng tải về sách điện tử: '. $libraries_name->name;
            } elseif ($libraries_name->type == 3) {
                $subject = 'Điểm thưởng tải về tài liệu';
                $content = 'Nhận điểm thưởng tải về tài liệu: '. $libraries_name->name;
            }

            $save_point_reward_view = new UserPointResult();
            $save_point_reward_view->user_id = profile()->user_id;
            $save_point_reward_view->content = $content;
            $save_point_reward_view->setting_id = $setting_download->id;
            $save_point_reward_view->point = $setting_download->pvalue;
            $save_point_reward_view->item_id = $id;
            $save_point_reward_view->type = 6;
            $save_point_reward_view->type_promotion = 1;
            $save_point_reward_view->save();

            $user_point = PromotionUserPoint::firstOrNew(['user_id' => profile()->user_id]);
            $user_point->point = (int)$user_point->point + (int)$setting_download->pvalue;
            $user_point->level_id = PromotionLevel::levelUp($user_point->point, profile()->user_id);
            $user_point->save();

            $query = new Notify();
            $query->user_id = profile()->user_id;
            $query->subject = $subject;
            $query->content = $content;
            $query->url = '';
            $query->created_by = 0;
            $query->save();

            $content = \Str::words(html_entity_decode(strip_tags($query->content)), 10);
            $redirect_url = route('module.notify.view', [
                'id' => $query->id,
                'type' => 1
            ]);

            $notification = new AppNotification();
            $notification->setTitle($query->subject);
            $notification->setMessage($content);
            $notification->setUrl($redirect_url);
            $notification->add(profile()->user_id);
            $notification->save();
        }
        $item = Libraries::findOrFail($id);
        $item->download = $item->download + 1;
        $item->save();

        /*Lưu lịch sử tương tác của HV*/
        $interaction_history = InteractionHistory::where(['user_id' => profile()->user_id, 'code' => 'libraries'])->first();
        if($interaction_history){
            $interaction_history->number = ($interaction_history->number + 1);
            $interaction_history->save();
        }else{
            $interaction_history = new InteractionHistory();
            $interaction_history->user_id = profile()->user_id;
            $interaction_history->code = 'libraries';
            $interaction_history->name = 'Thư viện';
            $interaction_history->number = 1;
            $interaction_history->save();
        }

        return response()->json([
            'count_download' => $item->download
        ]);
    }

    // XEM THƯ VIỆN CỘNG ĐIỂM THƯỞNG
    public function viewFile($id) {
        $setting_view = UserPointSettings::where('pkey', 'user_view_libraries')->where('item_id', $id)->where('item_type',6)->first();
        $userpoint_reward_views = UserPointResult::where('user_id', profile()->user_id)->where('type', 6)->where('item_id', $id)->where('setting_id', @$setting_view->id)->exists();
        if (isset($setting_view) && !$userpoint_reward_views) {
            $libraries_name = Libraries::find($id, ['name','type']);
            if($libraries_name->type == 2) {
                $subject = 'Điểm thưởng Xem sách diện tử';
                $content = 'Nhận điểm thưởng Xem sách điện tử: '. $libraries_name->name;
            } elseif ($libraries_name->type == 3) {
                $subject = 'Điểm thưởng Xem tài liệu';
                $content = 'Nhận điểm thưởng Xem tài liệu: '. $libraries_name->name;
            }

            $save_point_reward_view = new UserPointResult();
            $save_point_reward_view->user_id = profile()->user_id;
            $save_point_reward_view->content = $content;
            $save_point_reward_view->setting_id = $setting_view->id;
            $save_point_reward_view->point = $setting_view->pvalue;
            $save_point_reward_view->item_id = $id;
            $save_point_reward_view->type = 6;
            $save_point_reward_view->type_promotion = 1;
            $save_point_reward_view->save();

            $user_point = PromotionUserPoint::firstOrNew(['user_id' => profile()->user_id]);
            $user_point->point = (int)$user_point->point + (int)$setting_view->pvalue;
            $user_point->level_id = PromotionLevel::levelUp($user_point->point, profile()->user_id);
            $user_point->save();

            $query = new Notify();
            $query->user_id = profile()->user_id;
            $query->subject = $subject;
            $query->content = $content;
            $query->url = '';
            $query->created_by = 0;
            $query->save();

            $content = \Str::words(html_entity_decode(strip_tags($query->content)), 10);
            $redirect_url = route('module.notify.view', [
                'id' => $query->id,
                'type' => 1
            ]);

            $notification = new AppNotification();
            $notification->setTitle($query->subject);
            $notification->setMessage($content);
            $notification->setUrl($redirect_url);
            $notification->add(profile()->user_id);
            $notification->save();
        }
        return response()->json([
            'status' => 'success'
        ]);
    }

    public function detailLibraryAudiobook($id)
    {
        $get_unit =  ProfileView::select('unit_id')->where('user_id', profile()->user_id)->first();
        $item = Libraries::findOrFail($id);
        $item->views = $item->views + 1;
        $item->save();
        $item->getLinkPlay = $item->getLinkPlay();
        $item->attachment = upload_file($item->attachment);

        $libraries_audiobooks = LibrariesMoreAudiobook::where('libraries_id',$id)->get();
        foreach($libraries_audiobooks as $libraries_audiobook) {
            $libraries_audiobook->attachment = upload_file($libraries_audiobook->attachment);
        }

        $get_related_libraries = Libraries::select(['id','image','type','name'])->where('category_id',$item->category_id)->where('type',5)->where('id','!=',$item->id)->where('status', 1)->get();
        $related_libraries = [];
        foreach($get_related_libraries as $related_library) {
            $related_library->image = image_library($related_library->image);
            $get_object_libraries = LibrariesObject::where('libraries_id',$related_library->id)->whereNotNull('unit_id')->where('type', $related_library->type)->get();
            $check_unit = 0;
            if ( !$get_object_libraries->isEmpty() ) {
                foreach ($get_object_libraries as $get_object_librarie) {
                    $unit_code = Unit::select('code')->find($get_object_librarie->unit_id);
                    $get_array_childs = Unit::getArrayChild($unit_code->code);
                    if( in_array($get_unit->unit_id, $get_array_childs) || $get_unit->unit_id == $get_object_librarie->unit_id) {
                        $check_unit = 1;
                    }
                }
            }
            if((!$get_object_libraries->isEmpty() && $check_unit == 1) || $get_object_libraries->isEmpty()) {
                $related_libraries[] = $related_library;
            }
        }

        LibrariesStatistic::update_libraries_insert_statistic(0);

        $setting_view = UserPointSettings::where('pkey', 'user_view_libraries')->where('item_id', $id)->where('item_type',6)->first();
        $userpoint_reward_views = UserPointResult::where('user_id', profile()->user_id)->where('type', 6)->where('item_id', $id)->where('setting_id', @$setting_view->id)->exists();
        if (isset($setting_view) && !$userpoint_reward_views) {
            $libraries_name = Libraries::find($id, ['name','type']);
            $subject = 'Điểm thưởng Xem Sách nói';
            $content = 'Nhận điểm thưởng Xem Sách nói: '. $item->name;

            $save_point_reward_view = new UserPointResult();
            $save_point_reward_view->user_id = profile()->user_id;
            $save_point_reward_view->content = $content;
            $save_point_reward_view->setting_id = $setting_view->id;
            $save_point_reward_view->point = $setting_view->pvalue;
            $save_point_reward_view->item_id = $id;
            $save_point_reward_view->type = 6;
            $save_point_reward_view->type_promotion = 1;
            $save_point_reward_view->save();

            $user_point = PromotionUserPoint::firstOrNew(['user_id' => profile()->user_id]);
            $user_point->point = (int)$user_point->point + (int)$setting_view->pvalue;
            $user_point->level_id = PromotionLevel::levelUp($user_point->point, profile()->user_id);
            $user_point->save();

            $query = new Notify();
            $query->user_id = profile()->user_id;
            $query->subject = $subject;
            $query->content = $content;
            $query->url = '';
            $query->created_by = 0;
            $query->save();

            $content = \Str::words(html_entity_decode(strip_tags($query->content)), 10);
            $redirect_url = route('module.notify.view', [
                'id' => $query->id,
                'type' => 1
            ]);

            $notification = new AppNotification();
            $notification->setTitle($query->subject);
            $notification->setMessage($content);
            $notification->setUrl($redirect_url);
            $notification->add(profile()->user_id);
            $notification->save();
        }

        /*Lưu lịch sử tương tác của HV*/
        $interaction_history = InteractionHistory::where(['user_id' => profile()->user_id, 'code' => 'libraries'])->first();
        if($interaction_history){
            $interaction_history->number = ($interaction_history->number + 1);
            $interaction_history->save();
        }else{
            $interaction_history = new InteractionHistory();
            $interaction_history->user_id = profile()->user_id;
            $interaction_history->code = 'libraries';
            $interaction_history->name = 'Thư viện';
            $interaction_history->number = 1;
            $interaction_history->save();
        }

        return response()->json([
            'item' => $item,
            'libraries_audiobooks' => $libraries_audiobooks,
            'related_libraries' => $related_libraries,
        ]);
    }

    public function detailLibraryVideo($id)
    {
        $get_unit =  ProfileView::select('unit_id')->where('user_id', profile()->user_id)->first();
        $item = Libraries::findOrFail($id);
        $item->views = $item->views + 1;
        $item->save();
        $item->getLinkPlay = $item->getLinkPlay();
        $item->attachment = upload_file($item->attachment);

        $libraries_videos = LibrariesMoreVideo::where('libraries_id',$id)->get();
        foreach($libraries_videos as $libraries_video) {
            $libraries_video->getLinkPlay = $libraries_video->getLinkPlay();
            $libraries_video->attachment = upload_file($libraries_video->attachment);
        }

        $get_related_libraries = Libraries::select(['id', 'image', 'type', 'name'])->where('category_id',$item->category_id)->where('type',4)->where('id','!=',$item->id)->where('status', 1)->get();
        $related_libraries = [];
        foreach($get_related_libraries as $related_library) {
            $related_library->image = image_library($related_library->image);
            $get_object_libraries = LibrariesObject::where('libraries_id',$related_library->id)->whereNotNull('unit_id')->where('type', $related_library->type)->get();
            $check_unit = 0;
            if ( !$get_object_libraries->isEmpty() ) {
                foreach ($get_object_libraries as $get_object_librarie) {
                    $unit_code = Unit::select('code')->find($get_object_librarie->unit_id);
                    $get_array_childs = Unit::getArrayChild($unit_code->code);
                    if( in_array($get_unit->unit_id, $get_array_childs) || $get_unit->unit_id == $get_object_librarie->unit_id) {
                        $check_unit = 1;
                    }
                }
            }
            if((!$get_object_libraries->isEmpty() && $check_unit == 1) || $get_object_libraries->isEmpty()) {
                $related_libraries[] = $related_library;
            }
        }

        LibrariesStatistic::update_libraries_insert_statistic(0);

        $setting_view = UserPointSettings::where('pkey', 'user_view_libraries')->where('item_id', $id)->where('item_type',6)->first();
        $userpoint_reward_views = UserPointResult::where('user_id', profile()->user_id)->where('type', 6)->where('item_id', $id)->where('setting_id', @$setting_view->id)->exists();
        if (isset($setting_view) && !$userpoint_reward_views) {
            $libraries_name = Libraries::find($id, ['name','type']);
            $subject = 'Điểm thưởng Xem Video';
            $content = 'Nhận điểm thưởng Xem Video: '. $item->name;

            $save_point_reward_view = new UserPointResult();
            $save_point_reward_view->user_id = profile()->user_id;
            $save_point_reward_view->content = $content_reward;
            $save_point_reward_view->setting_id = $setting_view->id;
            $save_point_reward_view->point = $setting_view->pvalue;
            $save_point_reward_view->item_id = $id;
            $save_point_reward_view->type = 6;
            $save_point_reward_view->type_promotion = 1;
            $save_point_reward_view->save();

            $user_point = PromotionUserPoint::firstOrNew(['user_id' => profile()->user_id]);
            $user_point->point = (int)$user_point->point + (int)$setting_view->pvalue;
            $user_point->level_id = PromotionLevel::levelUp($user_point->point, profile()->user_id);
            $user_point->save();

            $query = new Notify();
            $query->user_id = profile()->user_id;
            $query->subject = $subject;
            $query->content = $content;
            $query->url = '';
            $query->created_by = 0;
            $query->save();

            $content = \Str::words(html_entity_decode(strip_tags($query->content)), 10);
            $redirect_url = route('module.notify.view', [
                'id' => $query->id,
                'type' => 1
            ]);

            $notification = new AppNotification();
            $notification->setTitle($query->subject);
            $notification->setMessage($content);
            $notification->setUrl($redirect_url);
            $notification->add(profile()->user_id);
            $notification->save();
        }
        /*Lưu lịch sử tương tác của HV*/
        $interaction_history = InteractionHistory::where(['user_id' => profile()->user_id, 'code' => 'libraries'])->first();
        if($interaction_history){
            $interaction_history->number = ($interaction_history->number + 1);
            $interaction_history->save();
        }else{
            $interaction_history = new InteractionHistory();
            $interaction_history->user_id = profile()->user_id;
            $interaction_history->code = 'libraries';
            $interaction_history->name = 'Thư viện';
            $interaction_history->number = 1;
            $interaction_history->save();
        }

        return response()->json([
            'item' => $item,
            'libraries_videos' => $libraries_videos,
            'related_libraries' => $related_libraries,
        ]);
    }
}
