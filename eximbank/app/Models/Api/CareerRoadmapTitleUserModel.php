<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CareerRoadmapTitleUserModel extends Model
{
    use HasFactory;
    protected $table = 'career_roadmap_titles_user';
    protected $primaryKey = 'id';
    protected $fillable = [
        'career_roadmap_user_id',
        'title_id',
        'parent_id',
        'level',
        'seniority',
    ];

    public function career_roadmap_user(){
        return $this->hasOne(CareerRoadmapUserModel::class, 'id', 'career_roadmap_user_id');
    }
}
