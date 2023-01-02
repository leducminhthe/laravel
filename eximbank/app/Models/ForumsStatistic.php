<?php

namespace App\Models;

use Carbon\Carbon;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;
use Modules\Forum\Entities\Forum;
use Modules\Forum\Entities\ForumComment;
use Modules\Forum\Entities\ForumThread;

class ForumsStatistic extends Model
{
    use Cachable;
    protected $table = 'el_forums_statistic';
    protected $fillable = [
        'type','t1','t2','t3','t4','t5','t6','t7','t8','t9','t10','t11','t12','year',
    ];
    public $timestamps= false;

    public static function update_forums_insert_statistic()
    {
        $year = (int) date('Y');
        $month = "t".(int) date('m');
        $model = self::where("year",$year)->pluck($month)->toArray();
        $errors = array_filter($model);

        if (empty($errors)) {
            self::updateOrCreate([
                'year'=> $year
            ],[
                'year'=> $year,
                $month => 1
            ]);
        } else {
            $model = self::where("year",$year)->first();
            self::updateOrCreate([
                'year'=> $year
            ], [
                'year'=> $year,
                $month => $model->$month + 1
            ]);
        }
    }
}
