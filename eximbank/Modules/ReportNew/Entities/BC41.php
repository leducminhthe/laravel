<?php

namespace Modules\ReportNew\Entities;

use App\Models\CacheModel;
use App\Models\Categories\Titles;
use App\Scopes\CompanyScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\CourseRegisterView;
use App\Models\CourseView;
use Modules\Quiz\Entities\QuizResult;

class BC41 extends Model
{
    public static function sql($title_id)
    {
        $query = Titles::query();

        if (isset($title_id)) {
            $titles = explode(',', $title_id);
            $query->whereIn('id', $titles);
        }

        return $query;
    }
}
