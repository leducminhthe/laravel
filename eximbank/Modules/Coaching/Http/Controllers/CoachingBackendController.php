<?php

namespace Modules\Coaching\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Scopes\DraftScope;
use Illuminate\Http\Request;
use Modules\Coaching\Entities\CoachingGroup;
use Modules\Coaching\Entities\CoachingTeacher;

class CoachingBackendController extends Controller
{
    public function index()
    {
        return view('coaching::backend.coaching_teacher.index');
    }

    public function getData(Request $request)
    {
        $search = $request->input('search');
        $sort = $request->input('sort','id');
        $order = $request->input('order','desc');
        $offset = $request->input('offset',0);
        $limit = $request->input('limit',20);

        $query = CoachingTeacher::query()
            ->select([
                'el_coaching_teacher.id',
                'el_coaching_teacher.user_id',
                'el_coaching_teacher.image',
                'el_coaching_teacher.technique',
                'el_coaching_teacher.start_date',
                'el_coaching_teacher.end_date',
                'el_coaching_teacher.coaching_group_id',
                'el_coaching_teacher.number_coaching',
                'el_coaching_teacher.status',
            ])
            ->with([
                'user' => function ($q){
                    $q->select('id','code','firstname','lastname');
                },
                'coaching_group' => function ($e){
                    $e->select('id','code','name');
                },
            ])
            ->whereHas('user', function($q) use($search){
                $q->when($search, function($q) use($search){
                    $q->where(function ($q2) use($search){
                        $q2->where('code', 'like', '%' . $search . '%');
                        $q2->orWhereRaw("concat(lastname,' ',firstname) like '%". $search . "%'");
                    });
                });
            });

        $count = $query->count();
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row) {
            $row->start_date = get_date($row->start_date);
            $row->end_date = get_date($row->end_date);
            $row->image = image_file($row->image);
        }
        json_result(['total' => $count, 'rows' => $rows]);

    }

    public function updateStatus(Request $request){
        $ids = $request->ids;
        $status = $request->status;

        CoachingTeacher::whereIn('id', $ids)
        ->update([
            'status' => $status,
        ]);

        json_result([
            'status' => 'success',
            'message' => trans('laother.successful_save'),
        ]);

    }
    
}
