<?php

namespace App\Observers;

use App\Models\AreaView;
use App\Models\Categories\Area;

class AreaObserver extends BaseObserver
{
    /**
     * Handle the area "created" event.
     *
     * @param  Area  $area
     * @return void
     */
    public function created(Area $area)
    {
        $this->syncArea($area);
    }

    /**
     * Handle the area "updated" event.
     *
     * @param  Area  $area
     * @return void
     */
    public function updated(Area $area)
    {
        $this->syncArea($area);
        if ($area->isDirty(['code','name']))
            $this->updateHasChange($area,1);
    }

    /**
     * Handle the area "deleted" event.
     *
     * @param  Area  $area
     * @return void
     */
    public function deleted(Area $area)
    {
        AreaView::destroy($area->id);
        $this->updateHasChange($area,2);
    }

    /**
     * Handle the area "restored" event.
     *
     * @param  Area  $area
     * @return void
     */
    public function restored(Area $area)
    {
        //
    }

    /**
     * Handle the area "force deleted" event.
     *
     * @param  Area  $area
     * @return void
     */
    public function forceDeleted(Area $area)
    {
        //
    }
    private function syncArea(Area $area){
        try {
            $model = AreaView::firstOrNew(['id' => $area->id]);
            $model->id = $area->id;
            $model->status = $area->status;
            $model->area_level = $area->level;
            $model->area_code = $area->code;
            $dataField = AreaView::mapField($area->code);
            $model->fill($dataField);
            $model->save();
        }catch (\Exception $e){
            dd($e);
        }
    }
}
