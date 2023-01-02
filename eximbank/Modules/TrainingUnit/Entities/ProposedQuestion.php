<?php

namespace Modules\TrainingUnit\Entities;

use App\Models\CacheModel;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class ProposedQuestion extends Model
{
    use Cachable;
    protected $table = 'el_proposed_question';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'type',
        'category_id',
        'multiple',
        'created_by',
        'updated_by',
    ];

    public static function getAttributeName() {
        return [
            'name' => 'Tên câu hỏi',
            'type' => 'Loại',
            'category_id' => trans('lamenu.category'),
            'multiple' => 'Chọn nhiều',
            'created_by' => trans('laother.creator'),
            'updated_by' => trans('laother.editor'),
        ];
    }
}
