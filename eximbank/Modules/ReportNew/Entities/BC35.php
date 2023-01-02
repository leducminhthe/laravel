<?php

namespace Modules\ReportNew\Entities;

use App\Models\CacheModel;
use App\Scopes\CompanyScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\CourseView;

class BC35 extends Model
{
    public static function sql($course_type, $subject_id, $from_date, $to_date, $status)
    {
        $date = date('Y-m-d');

        $query = CourseView::query()
        ->select([
            'a.id',
            'a.name',
            'a.code',
            'a.course_id',
            'a.course_type',
            'a.start_date',
            'a.end_date',
            'a.training_form_id',
            'a.status',
            'a.isopen',
            'a.lock_course',
            'b.name as training_form_name'
        ])
        ->from('el_course_view as a')
        ->leftjoin('el_training_form as b', 'b.id', '=', 'a.training_form_id')
        ->where('a.offline', 0);
        $query->where('a.start_date', '>=', date_convert($from_date, '00:00:00'));
        $query->where(function ($sub) use ($to_date){
            $sub->orWhereNull('a.end_date');
            $sub->orWhere('a.end_date', '<=', date_convert($to_date, '23:59:59'));
        });
        $query->orderBy('a.id', 'ASC');

        if($course_type){
            $query->where('a.course_type', $course_type);
        }

        if ($subject_id){
            $query->whereIn('a.subject_id', explode(',', $subject_id));
        }

        if ($status){
            switch ($status) {
                case '1':
                    $query->where('a.status', 0);
                    break;
                case '2':
                    $query->where('a.status', 1);
                    $query->where(function ($sub) use ($date){
                        $sub->orWhereNull('a.end_date');
                        $sub->orWhere('a.end_date', '>=', date_convert($date, '23:59:59'));
                    });
                    $query->where('a.lock_course', 0);
                    break;
                case '3':
                    $query->where('a.status', 2);
                    break;
                case '4':
                    $query->where('a.status', 1);
                    $query->where('a.start_date', '<=', $date);
                    $query->where(function ($sub) use ($date){
                        $sub->orWhereNull('a.end_date');
                        $sub->orWhere('a.end_date', '>=', date_convert($date, '23:59:59'));
                    });
                    break;
                case '5':
                    $query->where('a.lock_course', '!=', 0);
                    $query->where('a.end_date', '<=', $date);
                    break;
                case '6':
                    $query->where('a.lock_course', '!=', 1);
                    $query->where('a.end_date', '<=', $date);
                    break;
            }
        }
        return $query;
    }
}
