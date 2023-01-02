<?php

namespace App\Observers;

use App\Models\HasChange;
use App\Models\ProfileView;
use Illuminate\Database\Eloquent\Model;
use Modules\ModelHistory\Entities\ModelHistory;
use Modules\SyncTable\Entities\SyncTable;
use Modules\SyncTable\Entities\SyncTableSetting;

class BaseObserver
{
    protected function updateHasChange(Model $model,int $type){

        $table = $model->getTable();
        $modelChange = HasChange::firstOrNew(['table_name'=>$model->getTable(),'record_id'=>$model->id,'type'=>$type]);
        $modelChange->table_name = $model->getTable();
        $modelChange->record_id = $model->id;
        $modelChange->type = $type;
        $modelChange->save();
        $dirtyColumn = $model->getDirty();
        foreach ($dirtyColumn as $key => $item) {
            $syncTables = SyncTableSetting::where(['from_table'=>$table,'from_column'=>$key])->get();
            foreach ($syncTables as  $syncTable) {
                $record = SyncTable::firstOrCreate(['sync_table_setting_id' => $syncTable->id, 'record_change' => $model->id]);
                \App\Jobs\SyncTable::dispatch($table,$key,$syncTable->id, $model->id, $record->id);
            }
        }
    }

    protected function saveHistory(Model $model, $code, $action, $note=null,$parent_id=null, $parent_model=null){
        $user_id = profile()->user_id??2;
        $profile = ProfileView::find($user_id);
        $fullName = $profile?$profile->full_name:'Admin';
        $hist = new ModelHistory();
        $hist->model_id= isset($model->id)?$model->id:0;
        $hist->model=$model->getTable();
        $hist->code=$code;
        $hist->action=$action;
        $hist->note=$note??strip_tags($model->name);
        $hist->parent_id=$parent_id;
        $hist->parent_model=$parent_model;
        $hist->created_name= $fullName;
        $hist->save();
    }
}
