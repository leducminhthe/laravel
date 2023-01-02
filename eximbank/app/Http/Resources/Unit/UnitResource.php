<?php

namespace App\Http\Resources\Unit;

use App\Http\Resources\Area\AreaResource;
use App\Models\Categories\Area;
use Illuminate\Http\Resources\Json\JsonResource;

class UnitResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        try {
            return [
                'id'=>$this->id,
                'code'=>'aaa',
                'area_id'=>$this->area_id,
                'area'=> Area::where(['id'=>$this->area_id])->with('parentHierarchy', function ($query){
                    $query->select('id','code','name','level','parent_code','status');
                })->get()
//                'area'=> new AreaResource($this->area)
            ];
        } catch (Exception $e) {
            dd($e->getMessage());
        }

//        return parent::toArray($request);
    }
}
