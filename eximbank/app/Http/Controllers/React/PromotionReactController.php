<?php

namespace App\Http\Controllers\React;

use App\Models\Categories\TrainingTeacher;
use App\Models\Profile;
use App\Scopes\CompanyScope;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Modules\Promotion\Entities\Promotion;
use Modules\Promotion\Entities\PromotionInfo;
use Modules\Promotion\Entities\PromotionGroup;
use Modules\Promotion\Entities\PromotionOrders;
use Modules\Promotion\Entities\PromotionUserPoint;
use Modules\Promotion\Entities\PromotionLevel;
use Modules\Notify\Entities\Notify;
use Modules\AppNotification\Helpers\AppNotification;
use Modules\Promotion\Entities\PromotionUserHistory;

class PromotionReactController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('react.promotion.index');
    }

    public function getData(Request $request) {
        $date = date('Y-m-d');
        $search = $request->input('search');
        $sort = $request->sort;

        Promotion::addGlobalScope(new CompanyScope());
        $query = Promotion::query()
            ->select([
                'el_promotion.*',
                'b.name as groupname'
            ])
            ->leftJoin('el_promotion_group as b','el_promotion.promotion_group','=','b.id');

        if ($search) {
            $query->where('el_promotion.name', 'like', '%'. $search .'%');
        }

        if ($sort == 1){
            $query->orderBy('point','asc');
        }
        if ($sort == 2){
            $query->orderBy('point','desc');
        }
        $query->where('el_promotion.status',1);
        $rows = $query->get();

        foreach ($rows as $row) {
            $row->images = image_promotion($row->images);
            if($row->period < $date) {
                $row->checkPeriod = 1;
            } else {
                $row->checkPeriod = 0;
            }
            if($row->amount == 0) {
                $row->checkAmount = 1;
            }
            $row->period = Carbon::parse($row->period)->format('d/m/Y');
        }
        $image_promotion = asset('images/level/point.png');
        return response()->json([
            'rows' => $rows,
            'image_promotion' => $image_promotion
        ]);
    }

    public function getPromotion($id, Request $request){
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
        if ($request->dateTo && date_convert($request->dateTo) < date_convert($request->dateFrom)){
            return response()->json([
                'message' => 'Ngày nhận cuối cùng phải lớn hơn ngày nhận lúc đầu',
                'status' => 'warning'
            ]);
        }
        $promotionInfo = Promotion::findOrFail($id);
        if ($userPointInfo->point < $promotionInfo->point) {
            return response()->json([
                'message' => 'Điểm của bạn không đủ',
                'status' => 'warning'
            ]);
        }else {
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
                $saveInfo->date_from = date_convert($request->dateFrom);
                $saveInfo->date_to = $request->dateTo ? date_convert($request->dateTo) : '';
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

                return response()->json([
                    'status' => 'success',
                    'message' => 'Đổi thành công',
                ]);
            }
        }
    }

    public function getUserMaxPoint(Request $request){
        $limit = $request->limit;

        $user = DB::query()
            ->select([
                'profile.user_id',
                'profile.lastname',
                'profile.firstname',
                'user_point.point'
            ])
            ->from('el_promotion_user_point as user_point')
            ->leftJoin('el_profile as profile', 'user_point.user_id', '=', 'profile.user_id')
            ->where('profile.status', '=', 1)
            ->where('profile.user_id', '>', 2)
            ->where('user_point.point', '>', 0)
            ->orderBy('user_point.point', 'DESC');
        if($limit){
            $user = $user->limit($limit);
        }
        $rows = $user->get();
        foreach ($rows as $row) {
            $row->full_name = $row->lastname .' '. $row->firstname;
            $row->image_avatar = Profile::avatar($row->user_id);
        }
        $image_promotion = asset('images/level/point.png');
        return response()->json([
            'rows' => $rows,
            'image_promotion' => $image_promotion
        ]);
    }


}
