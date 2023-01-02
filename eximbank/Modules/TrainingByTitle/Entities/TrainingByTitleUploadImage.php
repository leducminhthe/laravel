<?php

namespace Modules\TrainingByTitle\Entities;

use App\Models\CacheModel;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class TrainingByTitleUploadImage extends Model
{
    use Cachable;
    protected $table = 'el_training_by_title_upload_image';
    protected $primaryKey = 'id';
    protected $fillable = [
        'image',
        'type',
    ];
}
