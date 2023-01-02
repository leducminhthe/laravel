<?php

namespace Modules\CareerRoadmap\Http\Controllers\Backend;

use App\Scopes\DraftScope;
use Modules\CareerRoadmap\Entities\CareerRoadmapTitle;
use Modules\CareerRoadmap\Entities\CareerRoadmap;
use App\Http\Controllers\Controller;
use App\Models\Categories\Titles;
use Illuminate\Http\Request;
use Modules\CareerRoadmap\Imports\RoadmapImport;

class CareerRoadmapTitleController extends Controller
{
    public function index($title_id) {
        $title = Titles::findOrFail($title_id);
        CareerRoadmap::addGlobalScope(new DraftScope());
        $roadmaps = CareerRoadmap::where('title_id', '=', $title->id)
            ->get(['id', 'name','primary']);

        return view('careerroadmap::backend.title.index', [
            'title' => $title,
            'roadmaps' => $roadmaps,
        ]);
    }

    public function save($title_id, Request $request) {
        $title = Titles::findOrFail($title_id);

        $this->validateRequest([
            'name' => 'required|max:500',
        ], $request, [
            'name' => trans('career.roadmap_name')
        ]);

        $primary = $request->post('primary');
        $model_id = $request->post('id');

        $model = CareerRoadmap::firstOrNew(['id' => $model_id]);
        $model->fill($request->all());
        $model->setAttribute('title_id', $title->id);

        if ($primary) {
            $model->setAttribute('primary', $primary);
        }

        $model->save();

        if (empty($model_id)) {
            $career_roadmap_titles = new CareerRoadmapTitle();
            $career_roadmap_titles->career_roadmap_id = $model->id;
            $career_roadmap_titles->title_id = $title->id;
            $career_roadmap_titles->level = 0;
            $career_roadmap_titles->save();
        }

        return response()->json([
            'status' => 'success',
            'message' => trans('backend.save_success'),
            'redirect' => route('module.career_roadmap.title', [$title->id]),
        ]);
    }

    public function addTitle($title_id, Request $request) {
        $title = Titles::findOrFail($title_id);
        $roadmap = CareerRoadmap::findOrFail($request->post('id'));

        $this->validateRequest([
            'title_id' => 'required|exists:el_titles,id',
            'parent_title' => 'required',
            'seniority' => 'required',
        ], $request, [
            'title_id' => trans('app.title'),
            'parent_title' => trans('career.parent_title'),
            'seniority' => 'Thâm niên (năm)',
        ]);

        $title_id = $request->post('title_id');
        $parent_title = $request->post('parent_title');
        $parent = CareerRoadmapTitle::findOrFail($parent_title);

        $model = new CareerRoadmapTitle();
        $model->career_roadmap_id = $roadmap->id;
        $model->title_id = $title_id;
        $model->parent_id = $parent->id;
        $model->level = $parent->level + 1;
        $model->seniority = $request->seniority;
        $model->save();

        return response()->json([
            'status' => 'success',
            'message' => trans('backend.save_success'),
            'redirect' => route('module.career_roadmap.title', [$title->id]),
        ]);
    }

    public function getParents($title_id, Request $request) {
        $titles_list = Titles::query()
            ->select(['id', 'code', 'name'])
            ->where('status', '=', 1)
            ->whereNotIn('id', function ($sub) use ($request){
                $sub->select(['title_id'])
                    ->from('career_roadmap_titles')
                    ->where('career_roadmap_id', '=', $request->roadmap_id)
                    ->pluck('title_id')
                    ->toArray();
            })
            ->get();

        Titles::findOrFail($title_id);
        $roadmap = CareerRoadmap::find($request->post('roadmap_id'));
        $titles = $roadmap->titles()
            ->with('title')
            ->orderBy('level', 'ASC')
            ->get(['id', 'title_id', 'level']);

        $result = [];
        $parent_titles = [];
        foreach ($titles as $title) {
            $parent_titles[] = [
                'id' => $title->id,
                'name' => str_repeat('-- ', $title->level) . $title->title->name,
            ];
        }

        $result['titles_list'] = $titles_list;
        $result['parent_titles'] = $parent_titles;

        return response()->json($result);
    }

    public function remove($title_id, Request $request) {
        $this->validateRequest([
            'roadmap_id' => 'required',
        ], $request, [
            'roadmap_id' => trans('career.roadmap_name')
        ]);

        CareerRoadmapTitle::where('id', '=', $request->post('roadmap_id'))
            ->delete();

        return response()->json([
            'status' => 'success',
        ]);
    }

    public function edit($title_id,Request $request) {
        $roadmap = CareerRoadmapTitle::query();
        $roadmap->select([
            'a.*',
            'b.name',
            'b.id AS titles_id '
        ]);
        $roadmap->from('career_roadmap_titles AS a')
        ->join('el_titles AS b', 'b.id', '=', 'a.title_id')
        ->where('a.id','=', $request->post('roadmap_id'));
        $type = $request->post('roadmap_type');
        $rows = $roadmap->get();
        // dd($rows);
        return response()->json([
            'rows' => $rows,
            'type' => $type,
        ]);
    }

    public function saveEditTitle($title_id, Request $request){
        $title = Titles::findOrFail($title_id);
        $name = $request->post('title_id');
        $seniority = $request->post('seniority');
        $roadmap = CareerRoadmapTitle::find( $request->post('id'));
        $roadmap->update(['title_id'=>$name,'seniority'=>$seniority]);

        if($roadmap) {
            return response()->json([
                'status' => 'success',
                'message' => trans('backend.save_success'),
                'redirect' => route('module.career_roadmap.title', [$title->id]),
            ]);
        }
        else {
            return response()->json([
                'status' => 'unsuccessful',
                'message' => trans('backend.unsuccessful'),
                'redirect' => route('module.career_roadmap.title', [$title->id]),
            ]);
        }

    }

    public function removeRoadmap($title_id, Request $request) {
        $this->validateRequest([
            'roadmap_id' => 'required',
        ], $request, [
            'roadmap_id' => trans('career.roadmap_name')
        ]);

        CareerRoadmap::where('id', '=', $request->post('roadmap_id'))
            ->delete();

        return response()->json([
            'status' => 'success',
        ]);
    }

    public function import(Request $request)
    {
        $this->validateRequest([
            'import_file' => 'required|file'
        ], $request, ['import_file' => 'File import']);

        $title_id = $request->title_id;
        $file = $request->file('import_file');
        $import = new RoadmapImport($title_id);
        \Excel::import($import, $file);
        if ($import->errors) {
            session()->put('errors', $import->errors);
            session()->save();
        }
        json_result([
            'status' => 'success',
            'message' => 'Import ' . trans('laapi.success'),
            'redirect' => route('module.career_roadmap.title',['title_id'=>$title_id])
        ]);
    }
}
