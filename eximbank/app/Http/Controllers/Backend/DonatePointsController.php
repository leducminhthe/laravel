<?php

namespace App\Http\Controllers\Backend;

use App\Exports\DonateExport;
use App\Jobs\NotifyUserOfCompletedImportUser;
use App\Models\DonatePoints;
use App\Imports\DonateImport;
use App\Models\DonatePointsHistory;
use App\Models\Categories\Titles;
use App\Models\Categories\Unit;
use App\Models\Profile;
use App\Scopes\DraftScope;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\Notify\Entities\NotifySend;
use Modules\Notify\Entities\NotifySendObject;
use Modules\Promotion\Entities\PromotionLevel;
use Modules\Promotion\Entities\PromotionUserPoint;
use Illuminate\Support\Str;
use App\Models\Notifications;
use Modules\Notify\Entities\Notify;
use Modules\AppNotification\Helpers\AppNotification;
use Modules\Promotion\Entities\PromotionUserHistory;

class DonatePointsController extends Controller
{
    public function index() {
        $notifications = Notifications::where('notifiable_id', '=', profile()->user_id)
            ->where('notifiable_type', '=', 'App\Models\User')
            ->whereNull('read_at')
            ->get();
        $users = Profile::select(['id','code','lastname','firstname'])->where('status',1)->get();
        return view('backend.donate_points.index',[
            'notifications' => $notifications,
            'users' => $users
        ]);
    }

    public function form(Request $request) {
        $model = DonatePoints::select(['id','user_id','score','note'])->where('id', $request->id)->first();
        $profile = Profile::select(['user_id','title_code','unit_code','code','firstname','lastname'])->where('user_id',$model->user_id)->first();
        $title = Titles::select('name')->where('code', '=', $profile->title_code)->first();
        $unit = Unit::select('name')->where('code', '=', $profile->unit_code)->first();
        json_result([
            'model' => $model,
            'title' => $title,
            'unit' => $unit,
            'profile' => $profile,
        ]);
    }

    public function getData(Request $request) {
        $search = $request->input('search');
        $sort = $request->get('sort', 'name');
        $order = $request->get('order', 'desc');
        $offset = $request->get('offset', 0);
        $limit = $request->get('limit', 20);
        DonatePoints::addGlobalScope(new DraftScope());
        $query = DonatePoints::query();
        $query->select([
            'el_donate_points.*'
        ]);
        $query->from('el_donate_points');
        $query->join('el_profile as profile', 'profile.user_id', '=', 'el_donate_points.user_id');
        $query->where('el_donate_points.user_id', '>', 2);
        if($search){
            $query->where(function($sub_query) use ($search){
                $sub_query->orWhere('profile.lastname','like','%' . $search . '%');
                $sub_query->orWhere('profile.firstname','like','%' . $search . '%');
            });
        }

        $count = $query->count();
        $query->orderBy('el_donate_points.'.$sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();

        foreach ($rows as $row) {
            $profile = Profile::find($row->user_id);
            $row->name = $profile->lastname . ' ' . $profile->firstname;
            $row->edit_url = route('backend.donate_points.edit', ['id' => $row->id]);
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function remove(Request $request) {
        $ids = $request->post('ids', []);
        DonatePoints::destroy($ids);
        json_message(trans('laother.delete_success'));
    }

    public function save(Request $request) {
        $this->validateRequest([
            'user_id' => 'required',
            'score' => 'required',
            'note' => 'required',
        ], $request, DonatePoints::getAttributeName());

        $model = DonatePoints::firstOrNew(['id' => $request->id]);
        $model->fill($request->all());
        if (empty($model->id)) $model->created_by = profile()->user_id;
        $model->updated_by = profile()->user_id;
        $point = DonatePoints::find($request->id);

        if($request->id && ($request->score < $point->score)) {
            json_result([
                'status' => 'warning',
                'message' => 'Điểm phải lớn hơn hoặc bằng điểm ban đầu',
            ]);
        }

        if ($model->save()) {
            if (!empty($point)){
                $user_point = PromotionUserPoint::firstOrNew(['user_id' => $request->user_id]);
                $user_point->point = $request->score > $point->score ?  $user_point->point + ($request->score - $point->score) : $user_point->point;
                $user_point->level_id = PromotionLevel::levelUp($user_point->point, $user_point->user_id);
                $user_point->save();

                if($request->score > $point->score) {
                    $donate_points_user_history = new PromotionUserHistory();
                    $donate_points_user_history->user_id = $request->user_id;
                    $donate_points_user_history->point = $request->score - $point->score;
                    $donate_points_user_history->donate_point = 1;
                    $donate_points_user_history->save();

                    $query = new Notify();
                    $query->user_id = $request->user_id;
                    $query->subject = 'Số lượng điểm của Bạn đã thay đổi.';
                    $query->content = 'Số lượng điểm bạn đã nhận được lúc '. get_date($point->created_at) .' thay đổi '. $point->score .' điểm thành '. $model->score .' điểm. <br> Lý do: '. $model->note .' <br><br> Bạn đừng quên hãy luôn tích cực tham gia học tập để nhận được nhiều quà tặng bất ngờ nhé.';
                    $query->url = '';
                    $query->created_by = profile()->user_id;
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
                    $notification->add($request->user_id);
                    $notification->save();
                }
            }else{
                $user_point = PromotionUserPoint::firstOrNew(['user_id' => $request->user_id]);
                $user_point->point = $user_point->point + $request->score;
                $user_point->level_id = PromotionLevel::levelUp($user_point->point, $user_point->user_id);
                $user_point->save();

                $donate_points_user_history = new PromotionUserHistory();
                $donate_points_user_history->user_id = $request->user_id;
                $donate_points_user_history->point = $model->score;
                $donate_points_user_history->donate_point = 1;
                $donate_points_user_history->save();

                $query = new Notify();
                $query->user_id = $request->user_id;
                $query->subject = 'Bạn được nhận '. $model->score .' điểm';
                $query->content = 'Bạn nhận được quà tặng là '. $model->score .' điểm. Hãy kiểm tra ngay để không bỏ lỡ. <br> Lý do: '. $model->note .' <br><br> Bạn đừng quên hãy luôn tích cực tham gia học tập để nhận được nhiều quà tặng bất ngờ nhé.';
                $query->url = '';
                $query->created_by = profile()->user_id;
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
                $notification->add($request->user_id);
                $notification->save();
            }

            $donate_points_history = new DonatePointsHistory();
            $donate_points_history->user_id = $request->user_id;
            $donate_points_history->score = $request->score;
            $donate_points_history->save();

            json_result([
                'status' => 'success',
                'message' => trans('laother.successful_save'),
            ]);
        }

        json_message(trans('laother.can_not_save'), 'error');

    }

    private function levelUp($point)
    {
        $level = PromotionLevel::query()->where('point','<=',$point);

        if($level->exists())
            return $level->max('level');
        else
            return 0;
    }

    public function getTitleUnit(Request $request)
    {
        $profile = Profile::find($request->user_id);
        $title = Titles::where('code', '=', $profile->title_code)->first();
        $unit = Unit::where('code', '=', $profile->unit_code)->first();

        json_result([
            'title' => $title ? $title->name : '',
            'unit' => $unit ? $unit->name : '',
        ]);
    }

    public function import_donate_points(Request $request){
        $this->validateRequest([
            'import_file' => 'required|file'
        ], $request, [
            'import_file' => 'File import'
        ]);

        $file = $request->file('import_file');
        $name = 'import_user_' . Str::random(10) . '.' . $file->extension();
        $newfile = $file->move(storage_path('import_files'), $name);
        $type_import = $request->type_import;
        if($newfile) {
            (new DonateImport(\Auth::user(), $type_import))->queue($newfile)->chain([
                new NotifyUserOfCompletedImportUser(\Auth::user()),
            ]);

            json_result([
                'status' => 'success',
                'message' => 'Đang import dữ liệu, bạn sẽ được thông báo khi hoàn thành...',
                'redirect' => route('backend.donate_points')
            ]);
        }

        json_result([
            'status' => 'error',
            'message' => trans('laother.unable_upload'),
            'redirect' => route('backend.donate_points')
        ]);
    }
    public function export_donate_points()
    {
        return (new DonateExport())->download('danh_sach_tang_diem_'. date('d_m_Y') .'.xlsx');
    }
}
