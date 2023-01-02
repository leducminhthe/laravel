<?php

namespace Modules\Libraries\Entities;

use App\Models\CacheModel;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

/**
 * Modules\Libraries\Entities\LibrariesMoreAudiobook
 *
 * @property int $id
 * @property int $libraries_id
 * @property string $attachment
 * @property string $name_audiobook
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Libraries\Entities\LibrariesBookmark newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Libraries\Entities\LibrariesBookmark newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Libraries\Entities\LibrariesBookmark query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Libraries\Entities\LibrariesBookmark whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Libraries\Entities\LibrariesBookmark whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Libraries\Entities\LibrariesBookmark whereLibrariesId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Libraries\Entities\LibrariesBookmark whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Libraries\Entities\LibrariesBookmark whereUserId($value)
 * @mixin \Eloquent
 */
class LibrariesMoreAudiobook extends Model
{
    use Cachable;
    protected $table = 'el_more_libraries_audiobook';
    protected $primaryKey = 'id';
    protected $fillable = [
        'libraries_id',
        'attachment',
        'name_audiobook',
    ];
    public static function getAttributeName() {
        return [
            'libraries_id' => 'id',
            'attachment' => 'File',
            'name_audiobook' => 'Tên sách nói',
        ];
    }
    public function getLinkPlay() {
        $storage = \Storage::disk('local');
        $file = encrypt_array([
            'path' => $storage->path('uploads/' . $this->attachment),
        ]);

        return route('stream.video', [$file]);
    }
}
