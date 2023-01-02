<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CareerRoadmapTitleModel extends Model
{
    use HasFactory;
    protected $table = 'career_roadmap_titles';
    protected $primaryKey = 'id';
    protected $fillable = [
        'career_roadmap_id',
        'title_id',
        'parent_id',
        'level',
        'seniority',
    ];

    public function career_roadmap(){
        return $this->hasOne(CareerRoadmapModel::class, 'id', 'career_roadmap_id');
    }
}
