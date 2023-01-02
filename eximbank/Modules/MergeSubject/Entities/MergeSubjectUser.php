<?php

namespace Modules\MergeSubject\Entities;

use App\Models\CacheModel;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

/**
 * Modules\MergeSubject\Entities\MergeSubjectUser
 *
 * @method static \Illuminate\Database\Eloquent\Builder|MergeSubjectUser newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MergeSubjectUser newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MergeSubjectUser query()
 * @mixin \Eloquent
 */
class MergeSubjectUser extends Model
{
    use Cachable;
    protected $table = 'el_merge_subject_user';
    protected $table_name = 'Gộp chuyên đề HV';
    protected $fillable = [
        'user_id',
        'merge_subject_id',
        'type',
        'processed',
    ];
}
