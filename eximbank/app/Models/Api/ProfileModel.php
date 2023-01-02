<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfileModel extends Model
{
    use HasFactory;
    protected $table = 'el_profile';
    protected $fillable = [
        'id',
        'code',
        'user_id',
        'firstname',
        'lastname',
        'email',
        'phone',
        'dob',
        'title_code',
        'unit_code',
        'area_code',
        'address',
        'gender',
        'identity_card',
        'date_range',
        'issued_by',
        'contract_signing_date',
        'effective_date',
        'expiration_date',
        'join_company',
        'status',
        'expbank',
        'avatar',
        'certificate_code',
        'level',
        'id_code',
        'date_off',
        'referer',
        'unit_id',
        'position_id',
        'title_id',
        'date_title_appointment',
        'end_date_title_appointment',
        'marriage'
    ];
}
