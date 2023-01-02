<?php

namespace App\Models;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\MailTemplate
 *
 * @property int $id
 * @property string $code
 * @property string $name
 * @property string $title
 * @property string $content
 * @property string|null $note
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MailTemplate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MailTemplate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MailTemplate query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MailTemplate whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MailTemplate whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MailTemplate whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MailTemplate whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MailTemplate whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MailTemplate whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MailTemplate whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MailTemplate whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MailTemplate whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class MailTemplate extends BaseModel
{
    protected $table = 'el_mail_template';
    protected $table_name = "Mẫu mail";
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'title',
        'content',
        'note',
        'status'
    ];
}
