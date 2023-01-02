<?php

namespace Modules\Notify\Entities;

use App\Models\BaseModel;
use App\Models\Permission;
use App\Models\Profile;
use App\Models\ProfileView;
use App\Models\Categories\Titles;
use App\Models\Categories\Unit;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class NotifySend extends BaseModel
{
    protected $table = 'el_notify_send';
    protected $table_name = 'Thông báo';
    protected $fillable = [
        'subject',
        'content',
        'url',
        'type',
        'popup',
        'popup_type',
        'popup_image',
        'created_by',
        'status',
        'type',
        'important',
    ];
    protected $primaryKey = 'id';

    public static function getAttributeName(){
        return [
            'subject' => 'Tiêu đề thông báo',
            'url' => 'Liên kết',
            'created_by' => trans('laother.creator'),
            'status' => trans("latraining.status"),
        ];
    }

    public static function countMessage(){
        $count = 0;
        $profile = profile();
        if ($profile){
            $query2 = Notify::query();
            $query2->select([
                'id',
                'subject',
                'content',
                'created_at',
                'url'
            ]);
            $query2 = $query2->where('viewed', '=', 0);
            $query2 = $query2->where('user_id', '=', $profile->user_id);

            $query = NotifySend::query();
            $query->select([
                'a.id',
                'a.subject',
                'a.content',
                'a.created_at',
                'a.url'
            ]);
            $query->from('el_notify_send AS a');
            $query->where('a.status', '=', 1);
            $query->where('a.viewed', '=', 0);
            if (!Permission::isAdmin()){
                $query->whereNotIn('a.id', function ($sub) use ($profile){
                    $sub->select('notify_send_id')
                        ->from('el_remove_notify_send')
                        ->where('user_id', '=', $profile->user_id)
                        ->whereNotNull('id');
                });
                $query->whereIn('a.id', function ($subquery) use ($profile) {
                    $subquery->select(['notify_send_id'])
                        ->from('el_notify_send_object')
                        ->where('status', '=', 1)
                        ->where(function ($sub) use ($profile) {
                            $sub->orWhere('user_id', '=', $profile->user_id)
                                ->orWhere('title_id', '=', @$profile->title_id)
                                ->orWhere('unit_id', '=', @$profile->unit_id);
                        });
                });
                $query->orWhere(function ($sub) use ($profile){
                    $sub->whereNotNull('a.time_send')
                        ->where('a.time_send', '<=', date('Y-m-d H:i:s'))
                        ->whereIn('a.id', function ($subquery) use ($profile) {
                            $subquery->select(['notify_send_id'])
                                ->from('el_notify_send_object')
                                ->where('status', '=', 0)
                                ->where(function ($sub) use ($profile) {
                                    $sub->orWhere('user_id', '=', $profile->user_id)
                                        ->orWhere('title_id', '=', @$profile->title_id)
                                        ->orWhere('unit_id', '=', @$profile->unit->id);
                                });
                        });
                });
            }
            $query = $query->union($query2);
//            $query_sql = $query->toSql();
//            $query = \DB::table(\DB::raw("($query_sql) AS q"))->mergeBindings($query->getQuery());

            $count += $query->count();
        }

        return  $count;
    }

    public static function getNotifyNew($limit = null,$search = null,$start_date = null,$end_date = null, $type = null){
        $profile = profile();
        $query2 = Notify::query();
        $query2->select([
            'id',
            'subject',
            'created_at',
            'viewed',
            'important',
            \DB::raw('1 AS type')
        ]);
        $query2 = $query2->where('user_id', '=', $profile->user_id);
        if ($search){
            $query2->where('subject', 'like', '%'. $search .'%');
        }
        if ($start_date) {
            $query2->where('created_at', '>=', date_convert($start_date, '00:00:00'));
        }

        if ($end_date) {
            $query2->where('created_at', '<=', date_convert($end_date, '23:59:59'));
        }

        $query = NotifySend::query();
        $query->select([
            'a.id',
            'a.subject',
            'a.created_at',
            'a.viewed',
            'a.important',
            \DB::raw('2 AS type')
        ]);
        $query->from('el_notify_send AS a');
        $query->where('a.status', '=', 1);

        if ($search){
            $query->where('subject', 'like', '%'. $search .'%');
        }
        if ($start_date) {
            $query->where('created_at', '>=', date_convert($start_date, '00:00:00'));
        }

        if ($end_date) {
            $query->where('created_at', '<=', date_convert($end_date, '23:59:59'));
        }

        if (!Permission::isAdmin()){
            $query->whereNotIn('a.id', function ($sub) use ($profile){
                $sub->select('notify_send_id')
                    ->from('el_remove_notify_send')
                    ->where('user_id', '=', $profile->user_id)
                    ->whereNotNull('id');
            });

            $query->whereIn('a.id', function ($subquery) use ($profile) {
                $subquery->select(['notify_send_id'])
                    ->from('el_notify_send_object')
                    ->where('status', '=', 1)
                    ->where(function ($sub) use ($profile) {
                        $sub->orWhere('user_id', '=', $profile->user_id)
                            ->orWhere('title_id', '=', @$profile->title_id)
                            ->orWhere('unit_id', '=', @$profile->unit_id);
                    });
            });

            $query->orWhere(function ($sub) use ($profile){
                $sub->whereNotNull('a.time_send')
                    ->where('a.time_send', '<=', date('Y-m-d H:i:s'))
                    ->whereIn('a.id', function ($subquery) use ($profile) {
                        $subquery->select(['notify_send_id'])
                            ->from('el_notify_send_object')
                            ->where('status', '=', 0)
                            ->where(function ($sub) use ($profile) {
                                $sub->orWhere('user_id', '=', $profile->user_id)
                                    ->orWhere('title_id', '=', @$profile->title_id)
                                    ->orWhere('unit_id', '=', @$profile->unit_id);
                            });
                    });
            });
        }

        $query = $query->union($query2);
//        $query_sql = $query->toSql();
//        $query = \DB::table(\DB::raw("($query_sql) AS q"))->mergeBindings($query->getQuery());
        $query->orderByDesc('created_at');

        if ($limit && $type != 1){
            $query->limit($limit);
            return $query->get();
        } else {
            return $query->paginate(20);
        }
    }
}
