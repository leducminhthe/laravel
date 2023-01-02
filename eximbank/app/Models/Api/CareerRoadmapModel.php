<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CareerRoadmapModel extends Model
{
    use HasFactory;
    protected $table = 'career_roadmap';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'primary',
        'title_id',
        'created_by',
        'updated_by',
        'unit_by',
    ];

    public function career_roadmap_titles() {
        return $this->hasMany(CareerRoadmapTitleModel::class, 'career_roadmap_id', 'id');
    }
}
