<?php

namespace Modules\UserPoint\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Modules\UserPoint\Entities\UserPointResult;
use Modules\UserPoint\Entities\UserPointItem;
use App\Models\Profile;

class UserPointController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {

    }

    public function history(Request $request)
    {
        return view('userpoint::frontend.history',[]);
    }

    public function getDataHistory(Request $request) {
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $search = $request->input('search');
        $query = UserPointResult::where("user_id","=",profile()->user_id);
        $query->select('el_userpoint_result.*', 'el_userpoint_settings.pkey');
        $query->leftJoin('el_userpoint_settings', 'el_userpoint_settings.id', 'el_userpoint_result.setting_id');
		$query->where('el_userpoint_result.point', '>', 0);
		
        if ($search) {
            $query->where('name', 'like', '%'. $search .'%');
        }

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();

        foreach ($rows as $row) {
            if ($row->type == 6) {
                if ($row->setting_id == 1) {
                    $row->name = 'Đánh giá sao thư viện';
                } else if ($row->setting_id == 2) {
                    $row->name = 'Nhận điểm thưởng xem thư viện';
                } else {
                    $row->name = 'Nhận điểm thưởng tải về thư viện';
                }
            } elseif($row->type == 7 && !empty($row->ref)) {
                $row->name = 'Nhận điểm thưởng khi đạt đủ mốc bình luận bài viết diễn đàn';
            } elseif ($row->type == 8 && !empty($row->ref)) {
                if ($row->setting_id == 1) {
                    $row->name = 'Nhận điểm thưởng khi đạt đủ lượt xem video học liệu đào tạo';
                } else if ($row->setting_id == 2) {
                    $row->name = 'Nhận điểm thưởng khi đạt đủ lượt thích video học liệu đào tạo';
                } else {
                    $row->name = 'Nhận điểm thưởng khi đạt đủ lượt bình luận video học liệu đào tạo';
                }
            } elseif ($row->type == 10) {
                if (!empty($row->item_id)) {
                    $row->name = 'Nhận điểm thưởng tạo góp ý';
                } else {
                    $row->name = 'Nhận điểm thưởng khi đăng nhập';
                }
            } else {
                if($row->pkey == 'quiz_complete'){
                    $row->name = 'Hoàn thành kỳ thi';
                }elseif($row->pkey == 'online_activity_complete'){
                    $row->name = 'Hoàn thành hoạt động';
                }else{
                    $item= UserPointItem::where("ikey","=",$row->pkey)->first();
                    $row->name = $item->name;
                }
            }

            $row->datecreated = get_date($row->created_at, 'd/m/Y');
        }

        return response()->json(['total' => $count, 'rows' => $rows]);
    }

}
