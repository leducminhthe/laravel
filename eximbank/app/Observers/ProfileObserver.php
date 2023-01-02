<?php

namespace App\Observers;

use App\Models\Categories\Area;
use App\Models\Categories\Position;
use App\Models\Categories\Titles;
use App\Models\Categories\Unit;
use App\Models\Profile;
use App\Models\ProfileStatus;
use App\Models\ProfileView;
use Modules\Rating\Entities\RatingLevelsRegister;
use App\Models\Certificate;
use Modules\Offline\Entities\OfflineRegisterView;
use Modules\Online\Entities\OnlineRegisterView;

class ProfileObserver extends BaseObserver
{
    /**
     * Handle the profile "created" event.
     *
     * @param  \App\Models\Profile  $profile
     * @return void
     */
    public function created(Profile $profile)
    {
        $this->syncProfileView($profile);
        $user = ProfileView::find($profile->id)->full_name;
        $action = "Thêm nhân viên";
        parent::saveHistory($profile,'Insert',$action,$user);
    }

    /**
     * Handle the profile "updated" event.
     *
     * @param  \App\Models\Profile  $profile
     * @return void
     */
    public function updated(Profile $profile)
    {
        $this->syncProfileView($profile);
        if ($profile->isDirty(['code','firstname','lastname','title_id','position_id','unit_id','status']))
            $this->updateHasChange($profile,1);
        $user = ProfileView::find($profile->id)->full_name;
        $action = "Cập nhật nhân viên";
        parent::saveHistory($profile,'Update',$action,$user);
    }

    /**
     * Handle the profile "deleted" event.
     *
     * @param  \App\Models\Profile  $profile
     * @return void
     */
    public function deleted(Profile $profile)
    {
        ProfileView::destroy($profile->id);
        $this->updateHasChange($profile,2);
        $user = ProfileView::find($profile->id)->full_name;
        $action = "Xóa nhân viên";
        parent::saveHistory($profile,'Delete',$action,$user);
    }

    /**
     * Handle the profile "restored" event.
     *
     * @param  \App\Models\Profile  $profile
     * @return void
     */
    public function restored(Profile $profile)
    {
        //
    }

    /**
     * Handle the profile "force deleted" event.
     *
     * @param  \App\Models\Profile  $profile
     * @return void
     */
    public function forceDeleted(Profile $profile)
    {
        //
    }
    private function syncProfileView(Profile $profile){
        $model = ProfileView::firstOrNew(['user_id'=>$profile->user_id]);
        $model->status_id = $profile->status;
         unset($profile->status);
        $model->fill($profile->toArray());
        $model->id = $profile->user_id;
        $model->full_name = $profile->lastname.' '.$profile->firstname;

        $position = Position::find(@$profile->position_id);
        $model->position_code = $profile->position_id > 0 ? $position->code : null;
        $model->position_name = $profile->position_id > 0 ? $position->name : null;

        $model->title_name = $profile->title_id>0? Titles::find($profile->title_id)->name:null;

        $unit = Unit::find(@$profile->unit_id);
        $model->unit_name = $unit ? $unit->name : null;
        $unit_parent = Unit::where(['code' => @$unit->parent_code])->first();
        $model->parent_unit_code = $unit_parent ? $unit_parent->code : null;
        $model->parent_unit_id = $unit_parent ? $unit_parent->id : null;
        $model->parent_unit_name = $unit_parent ? $unit_parent->name : null;

        $certificate = Certificate::where('certificate_code', @$profile->certificate_code)->first();
        $model->certificate_id = $certificate ? $certificate->id : null;
        $model->certificate_name = $certificate ? $certificate->certificate_name : null;

        $area = Area::where(['code' => @$profile->area_code])->first();
        $model->area_id = $profile->area_code ? $area->id : null;
        $model->area_name = $profile->area_code ? $area->name : null;

        if ($profile->status==1)
            $status_name = trans('backend.doing');
        elseif ($profile->status==2)
            $status_name = trans('backend.probationary');
        elseif ($profile->status==3)
            $status_name = trans('backend.pause');
        else
            $status_name = trans('backend.inactivity');
        $model->status_name = $status_name;
        $model->save();

        $offline_register = OfflineRegisterView::where('user_id', $model->user_id);
        if($offline_register->exists()){
            $offline_register->update([
                'code' => $model->code,
                'full_name' => $model->full_name,
                'email' => $model->email,
                'title_id' => $model->title_id,
                'title_code' => $model->title_code,
                'title_name' => $model->title_name,
                'position_id' => $model->position_id,
                'position_code' => $model->position_code,
                'position_name' => $model->position_name,
                'unit_id' => $model->unit_id,
                'unit_code' => $model->unit_code,
                'unit_name' => $model->unit_name,
                'parent_unit_id' => $model->parent_unit_id,
                'parent_unit_code' => $model->parent_unit_code,
                'parent_unit_name' => $model->parent_unit_name,
            ]);
        }

        $online_register = OnlineRegisterView::where('user_id', $model->user_id);
        if($online_register->exists()){
            $online_register->update([
                'code' => $model->code,
                'full_name' => $model->full_name,
                'email' => $model->email,
                'title_id' => $model->title_id,
                'title_code' => $model->title_code,
                'title_name' => $model->title_name,
                'position_id' => $model->position_id,
                'position_code' => $model->position_code,
                'position_name' => $model->position_name,
                'unit_id' => $model->unit_id,
                'unit_code' => $model->unit_code,
                'unit_name' => $model->unit_name,
                'parent_unit_id' => $model->parent_unit_id,
                'parent_unit_code' => $model->parent_unit_code,
                'parent_unit_name' => $model->parent_unit_name,
                'area_id' => $model->area_id,
                'area_code' => $model->area_code,
                'area_name' => $model->area_name,
            ]);
        }

        $rating_levels_register = RatingLevelsRegister::query()
            ->where('user_id', '=', $profile->user_id);
        if ($rating_levels_register->exists()){
            $rating_levels_register->update([
                'unit_id' => @$unit->id,
                'unit_code' => @$unit->code
            ]);
        }
    }
}
