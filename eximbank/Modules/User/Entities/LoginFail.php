<?php

namespace Modules\User\Entities;

use App\Models\CacheModel;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class LoginFail extends Model
{
    use Cachable;
    protected $table = 'el_login_fail';
    protected $primaryKey = 'id';
    protected $fillable = [
        'user_id',
        'username',
        'user_type',
        'num_fail',
    ];
}
