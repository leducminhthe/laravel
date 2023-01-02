<?php

namespace App\Models;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class SocialNetwotkImageCover extends Model
{
    use Cachable;
    protected $table = 'el_social_network_image_cover';
    protected $primaryKey = 'id';
    protected $fillable = [
        'user_id',
        'image_cover',
    ];
}
