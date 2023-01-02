<?php

namespace Modules\TrainingRoadmap\Http\Controllers;

use App\Scopes\DraftScope;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Models\Categories\Titles;
use Modules\TrainingRoadmap\Entities\TrainingRoadmap;
use App\Models\Categories\TitleRank;

class BackendController extends Controller
{
    public function listRoadmap()
    {
        return view('trainingroadmap::index.list_roadmap');
        
    }

    public function index()
    {
        $errors = session()->get('errors');
        \Session::forget('errors');
        $titles_rank = TitleRank::get();
        // return view('trainingroadmap::index.index', [
        //     'errors' => $errors,
        //     'titles_rank' => $titles_rank,
        // ]);
        return view('backend.learning_manager.index',[
            'errors' => $errors,
            'titles_rank' => $titles_rank,
        ]);
    }

    public function getData(Request $request) {
        $title = $request->input('title');
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $title_rank = $request->title_rank;
        $unit_type = $request->unit_type;

        $query = Titles::query();
        $query->where('status',1);
        if ($title){
            $query->where('id', '=', $title);
        }

        if($request->unit_id) {
            $query->where('unit_id', $request->unit_id);
        }

        if($unit_type) {
            $query->where('unit_type',$unit_type);
        }

        if($title_rank) {
            $query->where('group',$title_rank);
        }

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();
        foreach ($rows as $row) {
            $row->num_subject = TrainingRoadmap::query()->where('title_id', '=', $row->id)->count();
            $row->title_url = route('module.trainingroadmap.detail', ['id' => $row->id]);
        }
        json_result(['total' => $count, 'rows' => $rows]);
    }
}
