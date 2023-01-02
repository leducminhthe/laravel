<?php

namespace Modules\Rating\Entities;

use Illuminate\Database\Eloquent\Model;

class RatingStatistical extends Model
{
    protected $table = 'el_rating_statistical';
    protected $fillable = [
        'template_id',
        'title_lesson',
        'title_organization',
        'title_teacher',
    ];
    protected $primaryKey = 'id';
}
