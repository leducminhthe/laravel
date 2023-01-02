<?php

namespace App\Models\Categories;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Categories\SubjectTypeSubject
 *
 * @property int $id
 * @property int $subject_type_id
 * @property int $subject_id
 * @method static \Illuminate\Database\Eloquent\Builder|SubjectTypeSubject newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SubjectTypeSubject newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SubjectTypeSubject query()
 * @method static \Illuminate\Database\Eloquent\Builder|SubjectTypeSubject whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubjectTypeSubject whereSubjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubjectTypeSubject whereSubjectTypeId($value)
 * @mixin \Eloquent
 */
class SubjectTypeSubject extends Model
{
   protected $table = 'el_subject_type_subject';
    protected $table_name = "Chương trình đào tạo join Subject";

    protected $primaryKey = 'id';
    protected $fillable = [
        'subject_type_id',
        'subject_id',
    ];
}
