<?php

namespace App\Models;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class RelatedSubject extends Model
{
    use Cachable;
    protected $table = 'el_related_subject';
    protected $table_name = "Chuyên đề liên quan";
    protected $primaryKey = 'id';
    protected $fillable = [
        'subject_id',
        'compel',
        'finish_5day',
        'finish_soon_end',
        'score_5',
        'score_8',
        'number_lesson',
        'new_subject',
    ];
}
