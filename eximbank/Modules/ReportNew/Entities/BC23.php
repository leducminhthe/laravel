<?php

namespace Modules\ReportNew\Entities;

use App\Models\CacheModel;
use App\Models\Categories\LevelSubject;
use App\Models\Categories\Titles;
use Illuminate\Database\Eloquent\Model;

class BC23 extends Model
{
    public static function sql($title_id)
    {
        $query = Titles::query();
        $query->where(['id'=>$title_id])->select('id','code','name','employees')->orderBy('name');
        return $query;
    }
    public static function getRateComplete($title_id){
        $data = LevelSubject::query()
            ->from('el_level_subject as a')
            ->join('el_training_roadmap_finish as b','a.id','=','b.level_subject_id')
            ->where('b.title_id',$title_id)
            ->select('a.id','a.code','a.name','b.user_finish')
            ->orderBy('a.name')
            ->get();

        return $data;
    }
}
