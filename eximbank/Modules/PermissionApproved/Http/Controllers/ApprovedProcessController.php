<?php

namespace Modules\PermissionApproved\Http\Controllers;

use App\Console\Commands\Title;
use App\Models\Categories\Titles;
use App\Models\Categories\Unit;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\PermissionApproved\Entities\ApprovedProcess;
use Modules\PermissionApproved\Entities\ModelApproved;
use Modules\PermissionApproved\Entities\PermissionApproved;
use Modules\PermissionApproved\Entities\PermissionApprovedObject;
use Modules\PermissionApproved\Entities\PermissionApprovedTitle;
use Modules\PermissionApproved\Entities\PermissionApprovedUser;
use Modules\PermissionApproved\Http\Requests\ApprovedProcessRequest;
use Modules\PermissionApproved\Http\Requests\PermissionApprovedRequest;
use Illuminate\Support\Facades\DB;

class ApprovedProcessController extends Controller
{

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $unit = $request->unit_id;
            $sort = $request->input('sort', 'level');
            $order = $request->input('order', 'desc');
            $offset = $request->input('offset', 0);
            $limit = $request->input('limit', 20);
            $query = DB::query();
            $query->select([
                'a.*',
                'b.name'
            ]);
            $query->from('el_approved_process as a');
            $query->join('el_unit as b', 'b.id', '=', 'a.unit_id');

            if ($unit) {
                $unit = Unit::whereIn('id', explode(';', $unit))->latest('id')->first();
                $unit_id = Unit::getArrayChild($unit->code);

                $query->where(function ($sub_query) use ($unit_id, $unit) {
                    $sub_query->WhereIn('a.unit_id', $unit_id);
                    $sub_query->orWhere('a.unit_id', '=', $unit->id);
                });
            }

            $count = $query->count();
            $query->orderBy($sort, $order);
            $query->offset($offset);
            $query->limit($limit);
            $rows = $query->get();
            foreach ($rows as $index => $row) {
                $arr = explode('/', $row->hierarchy);
                $branch_name = [];
                foreach ($arr as $index => $item) {
                    if ($item) {
                        $branch_name[]=Unit::find($item)->name;
                    }
                }
                $row->branch_name = implode(' <i class="fas fa-long-arrow-alt-right" aria-hidden="true"></i> ', $branch_name);
            }
            json_result(['total' => $count, 'rows' => $rows]);
        }
        $modelApproved = ModelApproved::active()->get();
        $company = Unit::getUnitByLevel(0);

        return view('permissionapproved::approved_process',[
            'modelApproved'=>$modelApproved,
            'company'=>$company,
        ]);
    }
    public function store(ApprovedProcessRequest $request)
    {
        $unit_id = $request->unit_id;
        $unit_name = Unit::findOrFail($unit_id)->name;
        $hierarchy= Unit::getHierarchyByUnit($unit_id);
        $model = new ApprovedProcess();
        $model->fill($request->all());
        $model->hierarchy = $hierarchy;
        $model->unit_name = $unit_name;
        $model->save();

        json_result([
            'status' => 'success',
            'messgae' => 'Lưu thành công'
        ]);
    }
    public function destroy(  Request $request)
    {
        $unit_id = ApprovedProcess::findOrFail($request->ids[0])->unit_id;
        ApprovedProcess::destroy($request->ids);
        PermissionApproved::where(['unit_id'=>$unit_id])->delete();
        PermissionApprovedObject::where(['unit_id'=>$unit_id])->delete();
        PermissionApprovedTitle::where(['unit_id'=>$unit_id])->delete();
        PermissionApprovedUser::where(['unit_id'=>$unit_id])->delete();
        json_success();
    }
}
