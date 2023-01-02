<?php

namespace App\Http\Controllers\Mobile;

use App\Scopes\CompanyScope;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Models\Categories\TrainingTeacher;
use App\Models\Profile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Modules\Promotion\Entities\Promotion;
use Modules\Promotion\Entities\PromotionGroup;
use Modules\Promotion\Entities\PromotionOrders;
use Modules\Promotion\Entities\PromotionUserPoint;
use Modules\Promotion\Entities\PromotionLevel;
use Modules\Notify\Entities\Notify;
use Modules\AppNotification\Helpers\AppNotification;
use Modules\Promotion\Entities\PromotionUserHistory;
use Modules\Promotion\Entities\PromotionInfo;

class PromotionController extends Controller
{
    public function index(Request $request)
    {
        $date = date('Y-m-d H:i:s');
        PromotionGroup::addGlobalScope(new CompanyScope());
        $promotion_group = PromotionGroup::all();

        Promotion::addGlobalScope(new CompanyScope());
        $promotions = Promotion::where('el_promotion.status',1)
            ->select('el_promotion.*','el_promotion_group.name as group_name')
            ->join('el_promotion_group', 'el_promotion_group.id','promotion_group')
            ->where('el_promotion.period', '>', $date)
            ->orderBy('period')
            ->paginate(8);

        $promotion_user = PromotionUserPoint::whereUserId(auth()->id())->first();

        $profile = profile();

        $training_teacher = TrainingTeacher::whereNotNull('user_id')->pluck('user_id')->toArray();
        $query = DB::query();
        $query->select(['profile.user_id', 'profile.full_name', 'profile.avatar', 'profile.gender', 'profile.firstname', 'user_point.point']);
        $query->from('el_promotion_user_point as user_point');
        $query->leftJoin('el_profile_view as profile', 'user_point.user_id', '=', 'profile.user_id');
        $query->where('profile.status_id', '=', 1);
        $query->where('profile.user_id', '>', 2);
        $query->where('user_point.point', '>', 0);
        $query->whereNotIn('profile.user_id', $training_teacher);
        $query->orderBy('user_point.point', 'DESC');
        $query->limit(3);
        $max_point = $query->get();

        return view('themes.mobile.frontend.promotion.index', compact('promotion_group', 'promotions', 'promotion_user', 'profile', 'max_point'));
    }

    public function get(Request $request){
        $this->validateRequest([
            'location'=>'required',
            'time'=>'required',
            'phoneNumber'=>'required',
            'dateFrom'=>'required',
        ], $request, [
            'location' => 'Địa điểm nhận quà',
            'time' => 'Thời gian nhận quà',
            'phoneNumber' => 'Số điện thoại',
            'dateFrom' => 'Ngày nhận quà',
        ]);
        $userPointInfo = PromotionUserPoint::where('user_id', profile()->user_id)->first();
        if (!$userPointInfo){
            return response()->json([
                'message' => 'Điểm của bạn không đủ',
                'status' => 'warning'
            ]);
        }
        $promotionInfo = Promotion::findOrFail($request->id);
        if ($userPointInfo->point < $promotionInfo->point) {
            return response()->json([
                'message' => 'Điểm của bạn không đủ',
                'status' => 'warning'
            ]);
        } else {
            $orders = new PromotionOrders();
            $latestOrder = PromotionOrders::orderBy('created_at','DESC')->first();
            $latestId = $latestOrder ? $latestOrder->id : 0;
            $orders->user_id = profile()->user_id;
            $orders->orders_id = '#'.str_pad($latestId + 1, 8, "0", STR_PAD_LEFT);
            $orders->point = $promotionInfo->point;
            $orders->promotion_id = $promotionInfo->id;
            $orders->quantity = 1;
            $orders->status = @trans('app.pending');
            if ($orders->save()) {
                $saveInfo = new PromotionInfo();
                $saveInfo->location = $request->location;
                $saveInfo->phone_number = $request->phoneNumber;
                $saveInfo->time = $request->time;
                $saveInfo->date_from = $request->dateFrom;
                $saveInfo->date_to = $request->dateTo;
                $saveInfo->note = $request->note;
                $saveInfo->order_id = $orders->id;
                $saveInfo->save();

                $userPointInfo->point -= $promotionInfo->point;
                $userPointInfo->save();
                $promotionInfo->amount -= 1;
                $promotionInfo->save();

                $donate_points_user_history = new PromotionUserHistory();
                $donate_points_user_history->user_id = profile()->user_id;
                $donate_points_user_history->point = $promotionInfo->point;
                $donate_points_user_history->promotion = $promotionInfo->id;
                $donate_points_user_history->save();

                $query = new Notify();
                $query->user_id = profile()->user_id;
                $query->subject = 'Quy đổi quà tặng';
                $query->content = 'Quà tặng bạn quy đổi mã '. $promotionInfo->code .' - '. $promotionInfo->name .'. đang xử lý';
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
                $notification->add(profile()->user_id);
                $notification->save();

                $redirect = route('themes.mobile.front.promotion');
                return response()->json([
                    'status' => 'success',
                    'message' => 'Đổi thành công',
                    'redirect' => $redirect,
                ]);
            }
        }
    }

    // CHI TIẾT QÙA tẶNG
    public function detail($id, Request $request)
    {
        $query = Promotion::query();
        $query->select([
            'promotion.*',
            'group.name as group_name',
        ]);
        $query->from('el_promotion as promotion');
        $query->leftJoin('el_promotion_group as group', 'group.id', '=', 'promotion.promotion_group');
        $query->where('promotion.id', $id);
        $promotion = $query->first();

        return view('themes.mobile.frontend.promotion.detail', [
            'promotion' => $promotion
        ]);
    }
}
