<?php

namespace Modules\Promotion\Http\Controllers;

use App\Scopes\DraftScope;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Promotion\Entities\PromotionGroup;
use Modules\Promotion\Entities\Promotion;
use App\Models\ProfileView;
use App\Models\Permission;
use App\Models\Categories\Unit;
use App\Models\Categories\Area;
use Modules\UserPoint\Entities\UserPointResult;
use Modules\UserPoint\Entities\UserPointItem;
use Modules\Promotion\Export\PromotionHistoryExport;

class PromotionHistoryController extends Controller
{

    public function index()
    {
        return view('promotion::backend.history.index',[
        ]);
    }

    public function getData(Request $request) {
        $search = $request->input('search');
        $status = $request->input('status');
        $unit = $request->unit_id;
        $title = $request->input('title');
        $area = $request->input('area');

        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $unit_manager = Permission::isUnitManager();
        if(!$unit_manager){
            ProfileView::addGlobalScope(new DraftScope('user_id'));
        }

        $query = ProfileView::query();
        $query->select([
            'el_profile_view.id',
            'el_profile_view.user_id',
            'el_profile_view.code',
            'el_profile_view.full_name',
            'el_profile_view.email',
            'el_profile_view.title_name',
            'el_profile_view.unit_name',
            'el_profile_view.parent_unit_name',
        ]);
        $query->from('el_profile_view');
        $query->leftjoin('user as u','u.id','=','el_profile_view.user_id');
        $query->where('el_profile_view.user_id', '>', 2);
        $query->where('el_profile_view.type_user', '=', 1);
        $query->where('el_profile_view.status_id', '=', 1);

        if ($unit_manager){
            $unit_user = Unit::find(session('user_unit'));
            $child_arr = Unit::getArrayChild(@$unit_user->code);

            $query->where(function ($sub) use ($unit_user, $child_arr){
                $sub->orWhere('el_profile_view.unit_id', '=', @$unit_user->id);
                $sub->orWhereIn('el_profile_view.unit_id', $child_arr);
            });
        }

        if ($search) {
            $query->where(function ($sub_query) use ($search) {
                $sub_query->orWhere('el_profile_view.full_name', 'like', '%' . $search . '%');
                $sub_query->orWhere('el_profile_view.code', 'like', '%'. $search .'%');
                $sub_query->orWhere('el_profile_view.email', 'like', '%'. $search .'%');
                $sub_query->orWhere('u.username', 'like', '%'. $search .'%');
            });
        }
        if ($request->area) {
            $query->leftJoin('el_unit AS c', 'c.code', '=', 'el_profile_view.unit_code');
            $query->leftJoin('el_area AS area', 'area.id', '=', 'c.area_id');
            $area = Area::find($request->area);
            $area_id = Area::getArrayChild($area->code);
            $query->where(function ($sub_query) use ($area, $area_id) {
                $sub_query->whereIn('area.id', $area_id);
                $sub_query->orWhere('area.id', '=', $area->id);
            });
        }
        if (!is_null($status)) {
            $query->where('el_profile_view.status_id', '=', $status);
        }

        if ($unit) {
            $unit = Unit::whereIn('id', explode(';', $unit))->latest('id')->first();
            $unit_id = Unit::getArrayChild($unit->code);

            $query->where(function ($sub_query) use ($unit_id, $unit) {
                $sub_query->orWhereIn('el_profile_view.unit_id', $unit_id);
                $sub_query->orWhere('el_profile_view.unit_id', '=', $unit->id);
            });
        }

        if ($title) {
            $title = Titles::where('id', '=', $title)->first();
            $query->where('el_profile_view.title_code', '=', $title->code);
        }

        $data['total'] = $query->count();

        $query->orderBy('el_profile_view.status_id', 'desc');
        $query->orderBy('el_profile_view.code', 'desc');
        $query->offset($offset);
        $query->limit($limit);

        $data['rows'] = $query->get();
        foreach ($data['rows'] as $row) {
            $row->user_detail = route('module.promotion.history.detail', ['userId' => $row->user_id]);
        }

        json_result(['total' => $data['total'], 'rows' => $data['rows']]);
    }

    public function getDetail($userId)
    {
        $user = ProfileView::find($userId, ['user_id','full_name']);
        return view('promotion::backend.history.detail',[
            'user' => $user
        ]);
    }

    public function getDataDetail($userId, Request $request) {
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $search = $request->input('search');
        $query = UserPointResult::where("user_id","=", $userId);
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

    public function export(Request $request){
        $area = $request->area;
        $unit = $request->export_unit;
        $title = $request->title;
        $status = $request->status;
        $search = $request->search;

        return (new PromotionHistoryExport($area, $unit, $title, $status, $search))->download('thong_ke_lich_su_diem'. date('d_m_Y') .'.xlsx');
    }
}
