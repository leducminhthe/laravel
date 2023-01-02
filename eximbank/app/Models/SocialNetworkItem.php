<?php

namespace App\Models;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class SocialNetworkItem extends Model
{
    use Cachable;
    protected $table = 'el_social_network_item';
    protected $primaryKey = 'id';
    protected $fillable = [
        'social_network_new_id',
        'image',
        'video',
    ];

    public function getLinkPlay() {
        $storage = \Storage::disk('local');
        $file = encrypt_array([
            'path' => $storage->path('uploads/' . $this->video),
        ]);

        return route('stream.video', [$file]);
    }
}
