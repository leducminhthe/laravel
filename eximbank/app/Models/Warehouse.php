<?php

namespace App\Models;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Warehouse
 *
 * @property int $id
 * @property string $file_name
 * @property string $file_type
 * @property string $file_path
 * @property int $file_size
 * @property string $extension
 * @property string $source
 * @property int|null $folder_id
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Warehouse newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Warehouse newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Warehouse query()
 * @method static \Illuminate\Database\Eloquent\Builder|Warehouse whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Warehouse whereExtension($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Warehouse whereFileName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Warehouse whereFilePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Warehouse whereFileSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Warehouse whereFileType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Warehouse whereFolderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Warehouse whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Warehouse whereSource($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Warehouse whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Warehouse whereUserId($value)
 * @mixin \Eloquent
 * @property string $type
 * @method static \Illuminate\Database\Eloquent\Builder|Warehouse whereType($value)
 */
class Warehouse extends BaseModel
{
    use Cachable;
    protected $table = 'el_warehouse';
    protected $table_name = "Quản lý tệp tin";
    protected $primaryKey = 'id';
    protected $fillable = [
        'file_name',
        'file_type',
        'file_path',
        'file_size',
        'extension',
        'source',
        'type',
        'folder_id',
        'check_role',
    ];

    public function getFileUrl() {
        return upload_file($this->file_path);
    }

    public static function getLinkPlay($file_path) {
        $storage = \Storage::disk(config('app.datafile.upload_disk'));
        $file = encrypt_array([
            'file_path' => $file_path,
            'path' => $storage->path($file_path),
        ]);

        return route('stream.video', [$file]);
    }
}
