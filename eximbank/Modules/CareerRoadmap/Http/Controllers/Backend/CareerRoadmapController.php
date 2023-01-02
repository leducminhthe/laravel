<?php

namespace Modules\CareerRoadmap\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Categories\Titles;
use Illuminate\Http\Request;

class CareerRoadmapController extends Controller
{
    public function index() {
        return view('careerroadmap::backend.index',[
        ]);
    }

    public function getData(Request $request) {
        $search = $request->input('search');
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = Titles::query();
        $query->select([
            'id',
            'name',
            'code'
        ]);
        if ($search) {
            $query->where(function ($sub_query) use ($search) {
                $sub_query->orWhere('code', 'like', '%'. $search .'%');
                $sub_query->orWhere('name', 'like', '%'. $search .'%');
            });
        }

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();

        foreach ($rows as $row) {
            $row->edit_url = route('module.career_roadmap.title', [$row->id]);
        }

        return response()->json([
            'total' => $count,
            'rows' => $rows
        ]);
    }
}
