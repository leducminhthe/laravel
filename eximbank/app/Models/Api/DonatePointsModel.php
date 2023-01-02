<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DonatePointsModel extends Model
{
    use HasFactory;
    protected $table = 'el_donate_points';
    protected $primaryKey = 'id';
    protected $fillable = [
        'user_id',
        'score',
        'note',
        'created_by',
        'updated_by',
        'unit_by',
    ];
}
