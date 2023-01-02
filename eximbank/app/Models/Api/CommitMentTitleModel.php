<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommitMentTitleModel extends Model
{
    use HasFactory;
    protected $table='el_commitment_title';
    protected $primaryKey = 'id';
    protected $fillable = [
        'commitment_id',
        'commit_group_id',
        'title_id',
    ];
}
