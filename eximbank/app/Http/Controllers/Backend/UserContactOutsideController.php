<?php

namespace App\Http\Controllers\Backend;

use App\Models\UserContactOutside;
use App\Scopes\DraftScope;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Categories\Unit;
use App\Models\Categories\Titles;
use Carbon\Carbon;

class UserContactOutsideController extends Controller
{
    public function index() {
        // return view('backend.user_contact.index');
        return view('user::backend.user.index2');
    }

    public function getData(Request $request) {
        $search = $request->get('search');
        $unit = $request->unit;
        $title = $request->input('title');

        $sort = $request->get('sort', 'name');
        $order = $request->get('order', 'desc');
        $offset = $request->get('offset', 0);
        $limit = $request->get('limit', 20);

        $query = UserContactOutside::query();
        $query->select(['a.*']);
        $query->from('el_user_contact as a');

        if ($search) {
            $date = str_replace('/', '-', $search);
            $search =  date('Y-m-d', strtotime($date));
            $query->whereDate('created_at', '=', $search);
        }

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();
        foreach ($rows as $row) {
            if ($row->created_at == '1970-01-01 08:00:00') {
                $row->created_at = '-';
            }
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function remove(Request $request)
    {
        $ids = $request->input('ids', null);

        UserContactOutside::destroy($ids);

        json_result([
            'status' => 'success',
            'message' => trans('laother.delete_success'),
        ]);
    }
}
