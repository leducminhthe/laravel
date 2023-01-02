<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CareerRoadmapUserModel extends Model
{
    use HasFactory;
    protected $table = 'career_roadmap_user';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'primary',
        'user_id',
        'title_id',
        'created_by',
        'updated_by',
        'unit_by',
    ];

    public function career_roadmap_titles_user() {
        return $this->hasMany(CareerRoadmapTitleUserModel::class, 'career_roadmap_user_id', 'id');
    }
}
