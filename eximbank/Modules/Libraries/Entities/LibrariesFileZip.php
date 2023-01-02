<?php

namespace Modules\Libraries\Entities;

use App\Models\Warehouse;
use Illuminate\Database\Eloquent\Model;

class LibrariesFileZip extends Model
{
    protected $table = 'el_libraries_file_zip';
    protected $fillable = [
        'libraries_id',
        'origin_path',
        'unzip_path',
        'index_file',
        'status',
        'error',
    ];

    public function warehouse() {
        return $this->hasOne(Warehouse::class, 'file_path', 'origin_path');
    }
}
