<?php

namespace Modules\DailyTraining\Http\Controllers\Backend;

use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Modules\DailyTraining\Entities\DailyTrainingCategory;
use Modules\DailyTraining\Entities\DailyTrainingUserCommentVideo;
use Modules\DailyTraining\Entities\DailyTrainingUserViewVideo;
use Modules\DailyTraining\Entities\DailyTrainingVideo;
use Modules\UserPoint\Entities\UserPointResult;
use Modules\Promotion\Entities\PromotionUserPoint;
use Modules\Promotion\Entities\PromotionLevel;
use Modules\UserPoint\Entities\UserPointSettings;
use Modules\Notify\Entities\Notify;
use Modules\AppNotification\Helpers\AppNotification;

class DailyTrainingVideoController extends Controller
{
    public function index($cate_id)
    {
        return view('dailytraining::backend.video.index', [
            'cate_id' => $cate_id,
        ]);
    }

    public function getData($cate_id, Request $request) {
        $search = $request->input('search');
        $sort = $request->input('sort', 'view');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = DailyTrainingVideo::query()
            ->where('category_id', '=', $cate_id);

        if ($search) {
            $query->where('name', 'like', '%'. $search .'%');
        }

        $count = $query->count();
        $query->orderBy('view', $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();

        foreach ($rows as $row) {
            $row->video = $row->getLinkPlay();

            $row->view_comment = route('module.daily_training.video.view_comment', ['cate_id' => $cate_id, 'video_id' => $row->id]);
            $row->view_report = route('module.daily_training.video.view_report', ['cate_id' => $cate_id, 'video_id' => $row->id]);

            $row->info_url = route('module.daily_training.video.modal_info', ['cate_id' => $cate_id, 'video_id' => $row->id]);
        }
        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function remove(Request $request) {
        $ids = $request->input('ids', null);
        foreach ($ids as $id){
            $cate = DailyTrainingVideo::find($id);

            DailyTrainingUserCommentVideo::query()->where('video_id', '=', $id)->delete();

            $cate->delete();
        }

        json_result([
            'status' => 'success',
            'message' => trans('laother.delete_success'),
        ]);
    }

    public function approve($cate_id, Request $request) {
        $ids = $request->input('ids', null);
        $approve = $request->input('status');
        $userpoint_setting = UserPointSettings::where('pvalue', '>', 0)->where('pkey', 'daily_create')->where('item_id', $cate_id)->where('item_type', 8)->first(['id','pvalue']);
        foreach ($ids as $id){
            $video = DailyTrainingVideo::find($id);
            $video->approve = $approve;
            $video->status = 1;
            $video->user_approve = profile()->user_id;
            $video->time_approve = date('Y-m-d H:i:s');
            $video->save();

            $check_user_point = UserPointResult::where('setting_id', @$userpoint_setting->id)->where('item_id', $video->id)->where('user_id', $video->created_by)->where('type', 8)->exists();
            if ($approve == 1 && $userpoint_setting && !$check_user_point) {
                $subject = 'Điểm thưởng khi đăng và được duyệt video';
                $content = 'Nhận điểm thưởng khi đăng và được duyệt video: '. $video->name;

                $save_point = new UserPointResult();
                $save_point->user_id = $video->created_by;
                $save_point->content = $content;
                $save_point->setting_id = $userpoint_setting->id;
                $save_point->point = $userpoint_setting->pvalue;
                $save_point->item_id = $video->id;
                $save_point->type = 8;
                $save_point->type_promotion = 1;
                $save_point->save();

                $user_point = PromotionUserPoint::firstOrNew(['user_id' => $video->created_by]);
                $user_point->point = (int)$user_point->point + (int)$userpoint_setting->pvalue;
                $user_point->level_id = PromotionLevel::levelUp($user_point->point, $video->created_by);
                $user_point->save();

                $query = new Notify();
                $query->user_id = $video->created_by;
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
                $notification->add($video->created_by);
                $notification->save();
            }
        }

        json_message('Trạng thái đã thay đổi');
    }

    public function viewComment($cate_id, $video_id, Request $request){
        $comments = DailyTrainingUserCommentVideo::where('video_id', '=', $video_id)->orderBy('created_at', 'DESC')->get();
        $video = DailyTrainingVideo::find($video_id);

        return view('dailytraining::backend.video.comment', [
            'comments' => $comments,
            'video' => $video,
            'cate_id' => $cate_id
        ]);
    }

    public function checkFailedComment($cate_id, $video_id, Request $request){
        $comment = DailyTrainingUserCommentVideo::where('id', '=', $request->comment_id)
            ->where('video_id', '=', $video_id)->first();
        $comment->failed = ($comment->failed == 0 ? 1 : 0);
        $comment->save();

        json_message('Đánh dấu xong');
    }

    public function viewReport($cate_id, $video_id, Request $request){
        $video = DailyTrainingVideo::find($video_id);

        return view('dailytraining::backend.video.report', [
            'video' => $video,
            'cate_id' => $cate_id
        ]);
    }

    public function getDataReport($cate_id, $video_id, Request $request) {
        $search = $request->input('search');

        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = DailyTrainingUserViewVideo::query()
            ->select([
                'user_view.*',
                'profile.dob',
                'title.name as title_name',
                'unit.name as unit_name',
                'unit_manager.name as unit_manager',
            ])
            ->from('el_daily_training_user_view_video as user_view')
            ->leftJoin('el_profile as profile', 'profile.user_id', '=', 'user_view.user_id')
            ->leftJoin('el_titles as title', 'title.code', 'profile.title_code')
            ->leftJoin('el_unit as unit', 'unit.code', '=', 'profile.unit_code')
            ->leftJoin('el_unit as unit_manager', 'unit_manager', '=', 'unit.parent_code')
            ->where('user_view.video_id', '=', $video_id);

        if ($search) {
            $query->where(function ($sub_query) use ($search) {
                $sub_query->orWhere('profile.code', 'like', '%'. $search .'%');
                $sub_query->orWhere('profile.lastname', 'like', '%'. $search .'%');
                $sub_query->orWhere('profile.firstname', 'like', '%'. $search .'%');
                $sub_query->orWhere('profile.email', 'like', '%'. $search .'%');
            });
        }

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();

        foreach ($rows as $row) {
            $row->fullname = Profile::fullname($row->user_id);
            $row->time_view = get_date($row->time_view, 'H:i d/m/Y');
            $row->dob = get_date($row->dob, 'd/m/Y');
        }
        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function modalInfo($cate_id, $video_id, Request $request){
        $daily_training_video = DailyTrainingVideo::find($video_id);

        $profile = Profile::find($daily_training_video->created_by);
        $title_name = @$profile->titles->name;

        $user_apporve = Profile::find($daily_training_video->user_approve);

        $created_by = $profile->lastname .' '. $profile->firstname .' ('. $profile->code .')';
        $created_time = get_date($daily_training_video->created_at, 'H:i d/m/Y');

        $user_approve = $user_apporve ? ($user_apporve->lastname .' '. $user_apporve->firstname .' ('. $user_apporve->code .')') : '';
        $time_approve = get_date($daily_training_video->time_approve, 'H:i d/m/Y');

        return view('dailytraining::backend.video.modal_info', [
            'created_by' => $created_by,
            'title_name' => $title_name,
            'created_time' => $created_time,
            'user_approve' => $user_approve,
            'time_approve' => $time_approve,
        ]);
    }
}
