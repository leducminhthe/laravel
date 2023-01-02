<?php

namespace App\Http\Controllers\Backend;

use App\Models\Categories\District;
use App\Models\Categories\Province;
use App\Models\Categories\TrainingLocation;
use App\Models\Categories\Unit;
use App\Models\Categories\UnitType;
use App\Models\Categories\UnitManager;
use App\Models\Profile;
use App\Scopes\DraftScope;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use User\Acl\Rule;
use App\Repositories\District\DistrictRepositoryInterface;
use App\Rules\DistrictRules;

class DistrictController extends Controller
{
    protected $districtRepo;

    public function __construct(DistrictRepositoryInterface $districtRepo)
    {
        $this->districtRepo = $districtRepo;
    }

    public function index( ) {
        $province = Province::all();
        return view('backend.category.district.index',[
            'province' => $province,
        ]);
    }

    public function getData(Request $request) {
        $products = $this->districtRepo->getData($request);
        json_result(['total' => $products[0], 'rows' => $products[1]]);
    }

    public function form(Request $request) {
        $model = District::select(['id','province_id','name'])->where('id', $request->id)->first();
        json_result($model);
    }

    public function save(Request $request) {
        $request->validate([
            'name' => ['required', new DistrictRules],
        ]);
        // $this->validateRequest([
        //     'id' => 'required|integer|min:1|max:999999',
        //     'name' => 'required|max:250',
        // ], $request, District::getAttributeName());
        // $validator = \Validator::make($request->all(),
        //     ['province_id'=> 'required|integer|min:1',],
        //     ['province_id.required'=> 'Chưa chọn thành phố']);
        // if($validator->fails()){
        //     json_message($validator->errors()->all()[0], 'error');
        // }
        // $model = District::firstOrNew(['id' => $request->id]);
        // $model->fill($request->all());
        // if ($request->id) {
        //     $model->created_by = $model->created_by;
        // }
        // $model->updated_by = profile()->user_id;
        // if ($model->save()) {
            json_result([
                'status' => 'success',
                'message' => trans('laother.successful_save'),
            ]);
        // }

        // json_message(trans('laother.can_not_save'), 'error');
    }

    public function filter(Request $request)
    {
        $district = District::query()->where('province_id','=', $request->province_id)->get();
        json_result($district);
    }

    public function remove(Request $request) {
        $ids = $request->input('ids', null);

        $related = TrainingLocation::whereIn('district_id', $ids)->first();
        if ($related){
            json_message(trans('laother.related_data'). '. ' .trans('laother.can_not_delete'), 'error');
        }

        District::destroy($ids);
        json_result([
            'status' => 'success',
            'message' => trans('laother.delete_success'),
        ]);
    }
}
