<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Models\Categories\Titles;
use App\Models\Categories\Unit;
use App\Models\Permission;
use App\Models\Profile;
use Illuminate\Support\Facades\Auth;
use Modules\Notify\Entities\Notify;
use Modules\Notify\Entities\NotifySend;


class NotifyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        return view('themes.mobile.frontend.notify.index', [
            'notify_new' => $this->getNotifyNew(),
        ]);
    }
    public function notSeen(){
        return view('themes.mobile.frontend.notify.index', [
            'notify_not_seen' => $this->getNotifyNotSeen(),
        ]);
    }
    public function viewed(){
        return view('themes.mobile.frontend.notify.index', [
            'notify_viewed' => $this->getNotifyViewed(),
        ]);
    }

    public function detail($id, $type){
        if ($type == 2){
            $notify = NotifySend::find($id);
            $notify->viewed = 1;
            $notify->save();
        }else{
            $notify = Notify::find($id);
            $notify->viewed = 1;
            $notify->save();
        }

        return view('themes.mobile.frontend.notify.detail', [
           'notify' => $notify
        ]);
    }

    public function getNotifyNew(){
        $profile = profile();
        $title = Titles::where('code', '=', $profile->title_code)->first();
        $unit = Unit::where('code', '=', $profile->unit_code)->first();

        $query2 = Notify::select([
            'id',
            'subject',
            'created_at',
            'url',
            'viewed',
            \DB::raw('1 AS type')
        ])->where('user_id', '=', $profile->user_id);

        $query = NotifySend::select([
            'a.id',
            'a.subject',
            'a.created_at',
            'a.url',
            'a.viewed',
            \DB::raw('2 AS type')
        ])
            ->from('el_notify_send AS a')
            ->where('a.status', '=', 1);
        if (!Permission::isAdmin()){
            $query->whereNotIn('a.id', function ($sub) use ($profile){
                $sub->select('notify_send_id')
                    ->from('el_remove_notify_send')
                    ->where('user_id', '=', $profile->user_id)
                    ->whereNotNull('id');
            });
            $query->whereIn('a.id', function ($subquery) use ($profile, $unit, $title) {
                $subquery->select(['notify_send_id'])
                    ->from('el_notify_send_object')
                    ->where('status', '=', 1)
                    ->where(function ($sub) use ($profile, $unit, $title) {
                        $sub->orWhere('user_id', '=', $profile->user_id)
                            ->orWhere('title_id', '=', @$title->id)
                            ->orWhere('unit_id', '=', $unit ? @$unit->id : '');
                    });
            });
            $query->orWhere(function ($sub) use ($profile, $unit, $title){
                $sub->whereNotNull('a.time_send')
                    ->where('a.time_send', '<=', date('Y-m-d H:i:s'))
                    ->whereIn('a.id', function ($subquery) use ($profile, $unit, $title) {
                        $subquery->select(['notify_send_id'])
                            ->from('el_notify_send_object')
                            ->where('status', '=', 0)
                            ->where(function ($sub) use ($profile, $unit, $title) {
                                $sub->orWhere('user_id', '=', $profile->user_id)
                                    ->orWhere('title_id', '=', @$title->id)
                                    ->orWhere('unit_id', '=', $unit ? @$unit->id : '');
                            });
                    });
            });
        }

        $query = $query->union($query2);
        $query_sql = $query->toSql();
        $query = \DB::table(\DB::raw("($query_sql) AS q"))->mergeBindings($query->getQuery());
        $query->orderBy('created_at', 'DESC');
        $rows = $query->paginate(10);

        return $rows;
    }

    public function getNotifyNotSeen(){
        $profile = profile();
        $title = Titles::where('code', '=', @$profile->title_code)->first();
        $unit = Unit::where('code', '=', @$profile->unit_code)->first();

        $query2 = Notify::query();
        $query2->select([
            'id',
            'subject',
            'content',
            'created_at',
            'url',
            'viewed',
            \DB::raw('1 AS type')
        ]);
        $query2 = $query2->where('viewed', '=', 0)
            ->whereYear('created_at', '=', date('Y'))
            ->where('user_id', '=', $profile->user_id);

        $query = NotifySend::query();
        $query->select([
            'a.id',
            'a.subject',
            'a.content',
            'a.created_at',
            'a.url',
            'a.viewed',
            \DB::raw('2 AS type')
        ]);
        $query->from('el_notify_send AS a');
        $query->where('a.status', '=', 1);
        $query->where('a.viewed', '=', 0);
        $query->whereYear('a.created_at', '=', date('Y'));
        if (!Permission::isAdmin()){
            $query->whereNotIn('a.id', function ($sub) use ($profile){
                $sub->select('notify_send_id')
                    ->from('el_remove_notify_send')
                    ->where('user_id', '=', $profile->user_id)
                    ->whereNotNull('id');
            });
            $query->whereIn('a.id', function ($subquery) use ($profile, $unit, $title) {
                $subquery->select(['notify_send_id'])
                    ->from('el_notify_send_object')
                    ->orWhere('user_id', '=', $profile->user_id)
                    ->orWhere('title_id', '=', @$title->id)
                    ->orWhere('unit_id', '=', $unit ? @$unit->id : '');
            });
        }

        $query = $query->union($query2);
        $query_sql = $query->toSql();
        $query = \DB::table(\DB::raw("($query_sql) AS q"))->mergeBindings($query->getQuery());
        $rows = $query->paginate(10);

        return $rows;
    }

    public function getNotifyViewed(){
        $profile = profile();
        $title = Titles::where('code', '=', @$profile->title_code)->first();
        $unit = Unit::where('code', '=', @$profile->unit_code)->first();

        $query2 = Notify::query();
        $query2->select([
            'id',
            'subject',
            'content',
            'created_at',
            'url',
            'viewed',
            \DB::raw('1 AS type')
        ]);
        $query2 = $query2->where('viewed', '=', 1)
            ->where('user_id', '=', $profile->user_id)
            ->where(\DB::raw('year(created_at)'), '=', date('Y'));

        $query = NotifySend::query();
        $query->select([
            'a.id',
            'a.subject',
            'a.content',
            'a.created_at',
            'a.url',
            'a.viewed',
            \DB::raw('2 AS type')
        ]);
        $query->from('el_notify_send AS a');
        $query->where('a.status', '=', 1);
        $query->where('a.viewed', '=', 1);
        $query->where(\DB::raw('year(created_at)'), '=', date('Y'));
        if (!Permission::isAdmin()){
            $query->whereNotIn('a.id', function ($sub) use ($profile){
                $sub->select('notify_send_id')
                    ->from('el_remove_notify_send')
                    ->where('user_id', '=', $profile->user_id)
                    ->whereNotNull('id');
            });
            $query->whereIn('a.id', function ($subquery) use ($profile, $unit, $title) {
                $subquery->select(['notify_send_id'])
                    ->from('el_notify_send_object')
                    ->orWhere('user_id', '=', $profile->user_id)
                    ->orWhere('title_id', '=', @$title->id)
                    ->orWhere('unit_id', '=', $unit ? @$unit->id : '');
            });
        }

        $query = $query->union($query2);
        $query_sql = $query->toSql();
        $query = \DB::table(\DB::raw("($query_sql) AS q"))->mergeBindings($query->getQuery());

        $rows = $query->paginate(10);

        return $rows;
    }

    
}
