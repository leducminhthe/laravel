<?php

namespace Modules\ReportNew\Entities;

use App\Models\CacheModel;
use App\Models\ProfileView;
use App\Scopes\CompanyScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\CourseView;

class BC38 extends Model
{
    public static function sql($unit_id, $title_id)
    {
        $date = date('Y-m-d');

        $query = ProfileView::query();
        $query->select([
            'profile.user_id',
            'profile.code',
            'profile.full_name',
            'profile.title_name',
            'profile.unit_name',
        ]);
        $query->from('el_profile_view as profile');
        $query->where('profile.user_id', '>', 2);

        if($unit_id) {
            $query->where('profile.unit_id', $unit_id);
        }

        if($title_id) {
            $query->where('profile.title_id', $title_id);
        }
       
        return $query;
    }
}
