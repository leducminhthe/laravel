<?php

namespace Modules\TrainingUnit\Entities;

use App\Models\CacheModel;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class ProposedQuestionCategoryLib extends Model
{
    use Cachable;
    protected $table = 'el_proposed_question_category_lib';
    public $timestamps = null;
}
