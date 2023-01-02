<?php

namespace App\Http\Controllers\Backend;

use App\Scopes\DraftScope;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Categories\TrainingPartner;
use App\Exports\TrainingPartnerExport;
use Modules\Offline\Entities\OfflineCourse;
use Modules\Offline\Entities\OfflineCourseCost;
use App\Models\TypeCost;
use App\Models\Categories\TrainingCost;

class TrainingPartnerController extends Controller
{
    public function index() {
        return view('backend.category.training_partner.index');
    }

    public function getData(Request $request) {
        $search = $request->input('search');
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);
        TrainingPartner::addGlobalScope(new DraftScope());
        $query = TrainingPartner::query();

        if ($search) {
            $query->where(function ($sub_query) use ($search) {
                $sub_query->orWhere('name', 'like', '%'. $search .'%');
                $sub_query->orWhere('code', 'like', '%'. $search .'%');
            });
        }

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row) {
            $row->cost = route('backend.training_partner_cost',['id' => $row->id]);
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function form(Request $request) {
        $model = TrainingPartner::select(['id','email','code','name','phone','address','people'])->where('id', $request->id)->first();
        json_result($model);
    }

    public function save(Request $request) {
        $this->validateRequest([
            'code' => 'required|unique:el_training_partner,code,'. $request->id,
            'name' => 'required',
            'email' => 'nullable|email',
            'phone' => 'nullable|numeric',
        ], $request, TrainingPartner::getAttributeName());

        $model = TrainingPartner::firstOrNew(['id' => $request->id]);
        $model->fill($request->all());
        if ($request->id) {
            $model->created_by = $model->created_by;
        }
        $model->updated_by = profile()->user_id;
        if ($model->save()) {
            json_result([
                'status' => 'success',
                'message' => trans('laother.successful_save'),
            ]);
        }

        json_message(trans('laother.can_not_save'), 'error');
    }

    public function remove(Request $request) {
        $ids = $request->input('ids', null);
        $check_offline = OfflineCourse::Where('training_partner_id', 'like', '%'. $id .'%')->first(['name']);
        if (!empty($check_offline)) {
            json_message('Không thể xoá. Có dữ liệu liên quan khóa học offline: '. $check_offline->name, 'error');
        }
        TrainingPartner::destroy($ids);
        json_result([
            'status' => 'success',
            'message' => trans('laother.delete_success'),
        ]);
    }

    public function exportTrainingPartner()
    {
        return (new TrainingPartnerExport())->download('danh_sach_doi_tac_'. date('d_m_Y') .'.xlsx');
    }

    public function trainingPartnerCost($id) 
    {
        $model = TrainingPartner::where('id', $id)->first();
        return view('backend.category.training_partner.form', [
            'model' => $model,
        ]);
    }

    public function trainingPartnerCostGetData($id, Request $request)
    {
        $search = $request->input('search');
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = OfflineCourse::query();
        $query->where('status', 1);
        $query->where('isopen', 1);
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $getQuery = $query->get(['id','name','code','training_unit','training_partner_id']);

        $typeCostOfPartner = TypeCost::where('type', 2)->pluck('id')->toArray();
        $costOfPartner = TrainingCost::whereIn('type', $typeCostOfPartner)->pluck('id')->toArray();
        
        $allCourses = [];
        foreach($getQuery as $item) {
            $trainingUnit = json_decode($item->training_unit);
            $trainingPartnerId = json_decode($item->training_partner_id);
            if(in_array($id, $trainingUnit)) {
                $allCourses[] = $item;
            }
            if(in_array($id, $trainingPartnerId)) {
                $allCourses[] = $item;
            }
        }

        $count = count($allCourses);
        $rows = $allCourses;
        foreach ($rows as $row) {
            $cost = OfflineCourseCost::where('course_id', $row->id)->whereIn('cost_id', $costOfPartner)->sum('actual_amount');
            $row->cost = number_format($cost);
            $row->costDetail = route('backend.training_partner_cost_detail',['id' => $id, 'courseId' => $row->id]);
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function trainingPartnerCostDetail($id, $courseId)
    {
        $model = TrainingPartner::where('id', $id)->first();
        $typeCostOfPartner = TypeCost::where('type', 2)->pluck('id')->toArray();
        $training_costs = TrainingCost::whereIn('type', $typeCostOfPartner)->orderBy('id')->get(['id','name','type']);
        $totalActualAmount = 0;
        foreach($training_costs as $training_cost) {
            $typeCostName = TypeCost::where('id', $training_cost->type)->first(['name']);
            $training_cost->typeCostName = $typeCostName->name;

            $course_cost = OfflineCourseCost::where('cost_id', $training_cost->id)->where('course_id',$courseId)->first(['actual_amount']);
            $training_cost->actual_amount = $course_cost->actual_amount;

            $totalActualAmount += $course_cost->actual_amount;
        }
        return view('backend.category.training_partner.cost_detail', [
            'model' => $model,
            'training_costs' => $training_costs,
            'totalActualAmount' => $totalActualAmount
        ]);
    }
}
