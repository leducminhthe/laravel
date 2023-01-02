<?php

namespace Modules\ReportNew\Entities;

use App\Models\CacheModel;
use App\Scopes\CompanyScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\Categories\Titles;
use App\Models\ProfileView;
use Modules\TrainingByTitle\Entities\TrainingByTitleCategory;

class BC36 extends Model
{
    public static function sql($training_title_category, $users, $title)
    {
        $query = ProfileView::query();
        $query->select([
            'profile.user_id',
            'profile.full_name',
            'profile.unit_name',
            'profile.title_id',
            'profile.title_name',
            'profile.email',
            'profile.code',
            'profile.full_name',
            'profile.join_company',
            'training_category.name as training_category_name',
            'training_category.id as training_category_id',
        ])->disableCache();
        $query->from('el_profile_view as profile');
        $query->leftjoin('el_training_by_title_category as training_category', function($join) use ($training_title_category, $title) {
            $join->on('training_category.title_id', '=', 'profile.title_id');
            $join->where('training_category.id', $training_title_category);
            $join->where('training_category.title_id', $title);
        });
        $query->where('profile.title_id', $title);

        if(!empty($users)) {
            $user_id = explode(',', $users);
            $query->whereIn('profile.user_id', $user_id);
        }

        $query->orderBy('profile.id');
        return $query;
    }
}
