<?php

namespace App\Observers;


use Modules\Offline\Entities\OfflineCourseClass;

class OfflineCourseClassObserver extends BaseObserver
{
    /**
     * Handle the OfflineCourseClass "created" event.
     *
     * @param  \App\Models\OfflineCourseClass  $offlineCourseClass
     * @return void
     */
    public function created(OfflineCourseClass $offlineCourseClass)
    {
        //
    }

    /**
     * Handle the OfflineCourseClass "updated" event.
     *
     * @param  \App\Models\OfflineCourseClass  $offlineCourseClass
     * @return void
     */
    public function updated(OfflineCourseClass $offlineCourseClass)
    {
        if ($offlineCourseClass->isDirty(['code','name']))
            $this->updateHasChange($offlineCourseClass,1);
    }

    /**
     * Handle the OfflineCourseClass "deleted" event.
     *
     * @param  \App\Models\OfflineCourseClass  $offlineCourseClass
     * @return void
     */
    public function deleted(OfflineCourseClass $offlineCourseClass)
    {
        //
    }

    /**
     * Handle the OfflineCourseClass "restored" event.
     *
     * @param  \App\Models\OfflineCourseClass  $offlineCourseClass
     * @return void
     */
    public function restored(OfflineCourseClass $offlineCourseClass)
    {
        //
    }

    /**
     * Handle the OfflineCourseClass "force deleted" event.
     *
     * @param  \App\Models\OfflineCourseClass  $offlineCourseClass
     * @return void
     */
    public function forceDeleted(OfflineCourseClass $offlineCourseClass)
    {
        //
    }
}
