<?php

namespace Modules\UserPoint\Http\Controllers\Backend;

use App\Models\Profile;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\User\Entities\TrainingProgramLearned;
use Illuminate\Http\Response;
use Modules\UserPoint\Entities\UserPointRewardLogin;

class UserPointRewardLoginController extends Controller
{
    public function getData(Request $request) {
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = UserPointRewardLogin::query();
        $query->select([
            '*',
        ]);

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row) {
            $row->start_date = get_date($row->start_date, 'd-m-Y');
            $row->end_date = get_date($row->end_date, 'd-m-Y');
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function form(Request $request) {
        $model = UserPointRewardLogin::where('id', $request->id)->first();
        json_result($model);
    }

    public function save(Request $request) {
        $this->validateRequest([
            'start_date' => 'required',
            'end_date' => 'required',
            'number_login' => 'required',
            'reward_point' => 'required',
        ], $request, UserPointRewardLogin::getAttributeName());

        if (get_date($request->end_date, 'Y-m-d') <= get_date($request->start_date, 'Y-m-d')) {
            json_message('Ngày không hợp lệ', 'error');
        }
        $checks_date = UserPointRewardLogin::where('id', '!=', $request->id)->get();
        foreach ($checks_date as $key => $checks_date) {
            if ((get_date($checks_date->start_date, 'Y-m-d') <= get_date($request->start_date, 'Y-m-d')) && (get_date($checks_date->end_date, 'Y-m-d') >= get_date($request->start_date, 'Y-m-d'))) {
                json_message('Ngày đã tồn tại', 'error');
            } else if ((get_date($checks_date->start_date, 'Y-m-d') <= get_date($request->end_date, 'Y-m-d')) && (get_date($checks_date->end_date, 'Y-m-d') >= get_date($request->end_date, 'Y-m-d'))) {
                json_message('Ngày đã tồn tại', 'error');
            }
        }

        $model = UserPointRewardLogin::firstOrNew(['id' => $request->id]);
        $model->fill($request->all());
        $model->start_date = get_date($request->start_date, 'Y-m-d');
        $model->end_date = get_date($request->end_date, 'Y-m-d');
        $model->save();

        json_result([
            'status' => 'success',
            'message' => trans('laother.successful_save'),
        ]);
    }

    public function remove(Request $request) {
        $ids = $request->input('ids', null);
        UserPointRewardLogin::destroy($ids);

        json_result([
            'status' => 'success',
            'message' => trans('laother.delete_success'),
        ]);
    }
}
