<?php

namespace App\Models;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class RattingCourse extends Model
{
    use Cachable;
    protected $table = 'el_ratting_course';
    protected $primaryKey = 'id';
    protected $fillable = [
        'course_id',
        'program_content',
        'teacher',
        'organization',
        'quality_course',
        'type',
    ];

    public static function getAttributeName() {
        return [
            'program_content' => trans("latraining.content"),
            'teacher' => 'Giảng viên',
            'organization' => 'Tổ chức',
        ];
    }
}
