<?php

namespace App\Models\Categories;

use App\Models\CacheModel;
use App\Traits\ChangeLogs;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Categories\SubjectTypeResult
 *
 * @property int $id
 * @property int $user_id
 * @property int $subject_type_id
 * @property int $course_finished_total
 * @property string|null $certificate_file
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|SubjectTypeResult newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SubjectTypeResult newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SubjectTypeResult query()
 * @method static \Illuminate\Database\Eloquent\Builder|SubjectTypeResult whereCertificateFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubjectTypeResult whereCourseFinishedTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubjectTypeResult whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubjectTypeResult whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubjectTypeResult whereSubjectTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubjectTypeResult whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubjectTypeResult whereUserId($value)
 * @mixin \Eloquent
 */
class SubjectTypeResult extends Model
{
    public $timestamps = true;
    protected $table = 'el_subject_type_result';
    protected $table_name = "Chương trình đào tạo kết quả";
    protected $fillable = [
        'subject_type_id',
        'user_id',
        'course_finished_total',
        'certificate_file',
        'created_at',
        'updated_at',
        
    ];
    protected $primaryKey = 'id';

}
