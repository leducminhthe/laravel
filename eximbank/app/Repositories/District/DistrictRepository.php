<?php
namespace App\Repositories\District;

use App\Repositories\BaseRepository;
use App\Models\Categories\District;
use App\Repositories\District\DistrictRepositoryInterface;
use App\Scopes\DraftScope;

class DistrictRepository extends BaseRepository implements DistrictRepositoryInterface
{
    //lấy model tương ứng
    public function getModel()
    {
        return District::class;
    }

    public function getDistrict()
    {
        return $this->model->select('name')->take(5)->get();
    }

    public function getData($request)
    {
        $search = $request->input('search');
        $sort = $request->input('sort', 'name');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);
        $this->model::addGlobalScope(new DraftScope());
        $query = $this->model::query();
        $query->select(['el_district.*','b.name as province']);
        $query->join('el_province as b','el_district.province_id','=','b.code');

        if ($search) {
            $query->orWhere('el_district.id', 'like', '%'. $search .'%');
            $query->orWhere('el_district.name', 'like', '%'. $search .'%');
        }

        $count = $query->count();
        $query->orderBy('el_district.'.$sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();
        foreach ($rows as $row) {
            $created_by = $row->created_by ? $row->created_by : 2;
            $updated_by = $row->updated_by ? $row->updated_by : 2;
            $row->info = route('backend.get_user_info',['created' => $created_by, 'updated' => $updated_by]);
        }

        return [$count, $rows];
    }
}