<?php

namespace Modules\Libraries\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Models\ProfileView;
use Modules\Libraries\Entities\Libraries;
use Modules\Libraries\Entities\LibrariesCategory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Modules\Notify\Entities\Notify;
use App\Models\Categories\Unit;
use Carbon\Carbon;
use Modules\UserPoint\Entities\UserPointItem;
use Modules\UserPoint\Entities\UserPointSettings;

class BackendController extends Controller
{
    // LẤY DỮ LIỆU ĐIỂM THƯỞNG
    public function getDataRewardPoint($id, $type, Request $request)
    {
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);
        $query = UserPointItem::query();
        $query->select([
            'a.*',
        ]);
        $query->from('el_userpoint_item as a');
        $query->where('a.type', 6);
        if ($type == 1) {
            $query->where('ikey', 'user_rating_libraries');
        } 
        if ($type != 2 && $type != 3 && $type != 1) {
            $query->where(function($sub) {
                $sub->where('ikey', 'user_rating_libraries');
                $sub->orwhere('ikey', 'user_view_libraries');
            });
        }

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row) {
            $userpoint_setting = UserPointSettings::where('pkey', $row->ikey)->where('item_id', $id)->where('item_type', 6)->first();
            $row->setting_updated_at2 = isset($userpoint_setting) ? get_date($userpoint_setting->updated_at, 'H:i:s d/m/Y') : '';
            $row->pvalue = isset($userpoint_setting) ? round($userpoint_setting->pvalue) : 0;
            $row->setting_id = isset($userpoint_setting) ? round($userpoint_setting->id) : '';
        }
        json_result(['total' => $count, 'rows' => $rows]);
    }

    // LƯU ĐIỂM THƯỞNG
    public function saveRewardPoint($id, $type, Request $request)
    {
        foreach ($request->userpoint_id as $key => $userpoint_id) {
            if ($userpoint_id == null && $request->promotion_status[$key] == 1) {
                $complete = new UserPointSettings();
                $complete->pkey = $request->ikey[$key];
                $complete->item_id = $id;
                $complete->item_type = 6;
                $complete->pvalue = $request->userpoint_others[$key] ? $request->userpoint_others[$key] : 0;
                $complete->save();
            } else if ($userpoint_id != null && $request->promotion_status[$key] == 1) {
                $complete = UserPointSettings::firstOrNew(['id' => $userpoint_id]);
                $complete->pkey = $request->ikey[$key];
                $complete->item_id = $id;
                $complete->item_type = 6;
                $complete->pvalue = $request->userpoint_others[$key] ? $request->userpoint_others[$key] : 0;
                $complete->save();
            } else if ($userpoint_id != null && $request->promotion_status[$key] == 0) {
                $complete = UserPointSettings::firstOrNew(['id' => $userpoint_id]);
                $complete->pkey = $request->ikey[$key];
                $complete->item_id = $id;
                $complete->item_type = 6;
                $complete->pvalue = 0;
                $complete->save();
            }
        }

        json_result([
            'status' => 'success',
            'message' => trans('laother.successful_save'),
        ]);
    }
}
