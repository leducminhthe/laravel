<?php

namespace Modules\Quiz\Http\Controllers\Backend;

use App\Scopes\CompanyScope;
use App\Scopes\DraftScope;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Modules\Quiz\Entities\QuizType;
use Modules\Quiz\Entities\Quiz;

class QuizTypeController extends Controller
{
    public function index() {
        return view('quiz::backend.type.index',[
        ]);
    }

    public function form(Request $request) {
        $model = QuizType::select(['id','name'])->where('id', $request->id)->first();
        json_result($model);
    }

    public function save(Request $request) {
        $this->validateRequest([
            'name' => 'required'
        ], $request, [
            'name' => trans('backend.name')
        ]);

        $model = QuizType::firstOrNew(['id' => $request->id]);
        $model->fill($request->all());
        $model->save();
        return \response()->json([
            'status' => 'success',
            'message' => trans('laother.successful_save'),
        ]);
    }

    public function getData(Request $request) {
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $search = $request->input('search');
        QuizType::addGlobalScope(new CompanyScope());
        $query = QuizType::query();

        if ($search) {
            $query->where('name', 'like', '%'. $search .'%');
        }

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();

        foreach ($rows as $row) {
            $created_by = $row->created_by ? $row->created_by : 2;
            $updated_by = $row->updated_by ? $row->updated_by : 2;
            $row->info = route('backend.get_user_info',['created' => $created_by, 'updated' => $updated_by]);
        }

        return response()->json(['total' => $count, 'rows' => $rows]);
    }

    public function remove(Request $request) {
        $ids = $request->input('ids', null);
        $check = Quiz::whereIn('type_id', $ids)->first(['name']);
        if(!empty($check)) {
            json_message('Không thể xoá. Có dữ liệu liên quan - kỳ thi: '. $check->name, 'error');
        } 
        QuizType::destroy($ids);
        return response()->json([
            'status' => 'success',
            'message' => trans('laother.delete_success'),
        ]);
    }
}
