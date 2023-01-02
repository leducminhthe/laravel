<?php

namespace Modules\ReportNew\Entities;

use App\Models\Categories\TrainingTeacher;
use App\Scopes\CompanyScope;
use App\Scopes\DraftScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class BC16 extends Model
{
    public static function sql($type)
    {
        $query = TrainingTeacher::query();

        if ($type){
            $query->where('type', '=', $type);
        }

        return $query;
    }

}
