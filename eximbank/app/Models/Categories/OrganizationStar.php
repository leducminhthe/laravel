<?php

namespace App\Models\Categories;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrganizationStar extends Model
{
    protected $table = 'el_organization_star';
    protected $fillable = [
        'user_id',
        'num_star',
        'course_id',
        'course_type',
    ];
    protected $primaryKey = 'id';

    public static function getAttributeName() {
        return [
            'user_id' => 'Học viên',
            'num_star' => 'Số sao',
            'course_id' => 'Khoá học',
            'course_type' => 'Loại khoá học',
        ];
    }
}
