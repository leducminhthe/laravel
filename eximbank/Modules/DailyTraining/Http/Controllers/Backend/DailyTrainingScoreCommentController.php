<?php

namespace Modules\DailyTraining\Http\Controllers\Backend;

use App\Scopes\DraftScope;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Modules\DailyTraining\Entities\DailyTrainingSettingScoreComment;

class DailyTrainingScoreCommentController extends Controller
{
    public function getData($category_id, Request $request) {
        $search = $request->input('search');
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);
        $query = DailyTrainingSettingScoreComment::query();
        $query->where('category_id', $category_id);
        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function save($category_id, Request $request) {
        $this->validateRequest([
            'from' => 'required',
            'score' => 'required',
        ], $request, DailyTrainingSettingScoreComment::getAttributeName());

        $from = $request->input('from');
        $to = $request->input('to');
        $id = $request->id;

        $check1 = DailyTrainingSettingScoreComment::query()
            ->where('category_id', $category_id)
            ->where('id','!=',$id)
            ->where('from', '<=', $from)
            ->where('to', '>=', $from);
        if ($check1->exists()) {
            json_result([
                'status' => 'error',
                'message' => 'Điểm nhập không hợp lệ',
            ]);
        }

        if ($to){
            $check2 = DailyTrainingSettingScoreComment::query()
                ->where('category_id', $category_id)
                ->where('id','!=',$id)
                ->where('from', '<=', $to)
                ->where('to', '>=', $to);
            if ($check2->exists()) {
                json_result([
                    'status' => 'error',
                    'message' => 'Điểm nhập không hợp lệ',
                ]);
            }

            if ($from >= $to){
                json_result([
                    'status' => 'error',
                    'message' => 'Khoảng bình luận không hợp lệ',
                ]);
            }
        }

        $model = DailyTrainingSettingScoreComment::firstOrNew(['id' => $request->id]);
        $model->category_id = $category_id;
        $model->fill($request->all());
        if ($model->save()) {
            json_result([
                'status' => 'success',
                'message' => trans('laother.successful_save'),
            ]);
        }
        json_message(trans('laother.save_error'), 'error');
    }

    public function form($category_id, Request $request) {
        $model = DailyTrainingSettingScoreComment::select(['id','from','to','score'])->where('id', $request->id)->first();
        json_result($model);
    }

    public function remove($category_id, Request $request) {
        $ids = $request->input('ids', null);

        DailyTrainingSettingScoreComment::destroy($ids);

        json_result([
            'status' => 'success',
            'message' => trans('laother.delete_success'),
        ]);
    }
}
