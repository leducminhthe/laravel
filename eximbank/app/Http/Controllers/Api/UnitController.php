<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Unit\UnitRequest;
use App\Http\Resources\Unit\UnitCollection;
use App\Http\Resources\Unit\UnitResource;
use App\Models\Categories\Unit;
use App\Services\UnitService;
use Illuminate\Http\Request;
use phpDocumentor\Reflection\Types\Parent_;
use phpDocumentor\Reflection\Types\This;
use function PHPUnit\Framework\throwException;

class UnitController extends BaseController
{
    protected $service;

    public function __construct(UnitService $service)
    {
        $this->service = $service;
    }

    public function index()
    {

        return $this->responeSuccess('ok',$this->service->index()->toArray());
//        return new UnitCollection($this->service->index());
    }
    public function store(UnitRequest $request)
    {

            $attributes = $request->all();
            return $this->responeSuccess('Thêm mới thành công',$this->service->store($attributes));
//            return new UnitResource($this->service->store($attributes));
    }
    public function show($id)
    {
            $result =  $this->service->show($id);
//            return $this->responeSuccess('ok',$result);
            return (new UnitResource($result));
    }
    public function update(UnitRequest $request, $id)
    {
        return $this->responeSuccess('Cập nhật thành công', $this->service->update($request->all(),$id));
    }

    public function destroy($id)
    {
        $this->service->delete($id);
        return $this->responeSuccess(trans('laother.delete_success'));
    }
}
