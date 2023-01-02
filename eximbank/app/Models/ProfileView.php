<?php

namespace App\Models;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use App\Traits\CacheModel;
use Modules\Quiz\Entities\QuizRegister;

/**
 * App\Models\ProfileView
 *
 * @property int $id
 * @property string $code
 * @property int $user_id
 * @property string|null $firstname Họ
 * @property string|null $lastname Tên
 * @property string $full_name Họ Tên nhân viên
 * @property string|null $dob ngày sinh
 * @property string|null $address
 * @property string|null $email
 * @property string|null $identity_card Số CMND
 * @property string|null $date_range Ngày cấp
 * @property string|null $issued_by Nơi cấp
 * @property int $gender 1:Nam, 0:Nữ
 * @property string|null $phone
 * @property string|null $contract_signing_date Ngày kí hợp đồng lao động
 * @property string|null $effective_date Ngày hiệu lực
 * @property string|null $expiration_date Ngày kết thúc
 * @property string|null $date_off Ngày nghỉ việc
 * @property string|null $join_company Ngày vào ngân hàng
 * @property string|null $expbank Thâm niên trong lĩnh vực ngân hàng
 * @property int|null $position_id id chức vụ
 * @property string|null $position_code Mã chức vụ
 * @property string|null $position_name tên chức vụ
 * @property int|null $title_id id chức danh
 * @property string|null $title_code mã chức danh
 * @property string|null $title_name chức danh
 * @property int|null $unit_id id đơn vị
 * @property string|null $unit_code mã đơn vị
 * @property string|null $unit_name tên đơn vị
 * @property int|null $parent_unit_id id đơn vị cha
 * @property string|null $parent_unit_code mã đơn vị cha
 * @property string|null $parent_unit_name tên đơn vị cha
 * @property int|null $area_id id khu vực
 * @property string|null $area_code mã khu vực
 * @property string|null $area_name Tên khu vực
 * @property string|null $level
 * @property int|null $certificate_id Mã trình độ
 * @property string|null $certificate_name trình độ
 * @property int|null $status_id 0: Nghỉ việc, 1: Đang làm, 2: Thử việc, 3: Tạm hoãn
 * @property string|null $status_name Tên trạng thái
 * @property string|null $avatar
 * @property string|null $id_code Mã định danh
 * @property string|null $referer Mã người giới thiệu
 * @property int $type_user
 * @property string|null $date_title_appointment
 * @property string|null $end_date_title_appointment
 * @property int|null $marriage tình trạng hôn nhân
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $unit_by
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ProfileView all($columns = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ProfileView avg($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ProfileView cache(array $tags = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ProfileView cachedValue(array $arguments, string $cacheKey)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ProfileView count($columns = '*')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ProfileView disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ProfileView disableModelCaching()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ProfileView flushCache(array $tags = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ProfileView getModelCacheCooldown(\Illuminate\Database\Eloquent\Model $instance)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ProfileView inRandomOrder($seed = '')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ProfileView insert(array $values)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ProfileView isCachable()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ProfileView max($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ProfileView min($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ProfileView newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ProfileView newQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ProfileView query()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ProfileView sum($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ProfileView truncate()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ProfileView userExternal()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ProfileView whereAddress($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ProfileView whereAreaCode($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ProfileView whereAreaId($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ProfileView whereAreaName($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ProfileView whereAvatar($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ProfileView whereCertificateId($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ProfileView whereCertificateName($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ProfileView whereCode($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ProfileView whereContractSigningDate($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ProfileView whereCreatedAt($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ProfileView whereCreatedBy($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ProfileView whereDateOff($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ProfileView whereDateRange($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ProfileView whereDateTitleAppointment($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ProfileView whereDob($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ProfileView whereEffectiveDate($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ProfileView whereEmail($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ProfileView whereEndDateTitleAppointment($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ProfileView whereExpbank($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ProfileView whereExpirationDate($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ProfileView whereFirstname($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ProfileView whereFullName($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ProfileView whereGender($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ProfileView whereId($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ProfileView whereIdCode($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ProfileView whereIdentityCard($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ProfileView whereIssuedBy($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ProfileView whereJoinCompany($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ProfileView whereLastname($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ProfileView whereLevel($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ProfileView whereMarriage($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ProfileView whereParentUnitCode($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ProfileView whereParentUnitId($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ProfileView whereParentUnitName($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ProfileView wherePhone($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ProfileView wherePositionCode($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ProfileView wherePositionId($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ProfileView wherePositionName($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ProfileView whereReferer($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ProfileView whereStatusId($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ProfileView whereStatusName($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ProfileView whereTitleCode($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ProfileView whereTitleId($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ProfileView whereTitleName($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ProfileView whereTypeUser($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ProfileView whereUnitBy($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ProfileView whereUnitCode($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ProfileView whereUnitId($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ProfileView whereUnitName($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ProfileView whereUpdatedAt($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ProfileView whereUpdatedBy($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ProfileView whereUserId($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ProfileView withCacheCooldownSeconds(?int $seconds = null)
 * @mixin \Eloquent
 */
class ProfileView extends Model
{
    use Cachable;
    protected $table ='el_profile_view';
    public $incrementing = false;
    protected $fillable=[
        'id',
        'code',
        'user_id',
        'firstname',
        'lastname',
        'full_name',
        'dob',
        'address',
        'email',
        'identity_card',
        'date_range',
        'issued_by',
        'gender',
        'phone',
        'contract_signing_date',
        'effective_date',
        'expiration_date',
        'date_off',
        'join_company',
        'expbank',
        'position_id',
        'position_code',
        'position_name',
        'title_id',
        'title_code',
        'title_name',
        'unit_id',
        'unit_code',
        'unit_name',
        'parent_unit_id',
        'parent_unit_code',
        'parent_unit_name',
        'area_id',
        'area_code',
        'area_name',
        'level',
        'certificate_id',
        'certificate_name',
        'status_id',
        'status_name',
        'avatar',
        'id_code',
        'referer',
        'type_user',
        'date_title_appointment',
        'end_date_title_appointment',
        'marriage'
    ];
    public function scopeUserExternal(Builder $query){
        return $query->where(['type_user'=>2]);
    }

    public function quizRegisters()
    {
        return $this->hasMany(QuizRegister::class,'user_id');
    }

    public function getAvatar() {
        if (empty($this->avatar)) {
            return asset('/images/design/user_150_150.png');
        }else{
            $fileExists =  upload_file('profile/' . $this->avatar);
            if ($fileExists)
                return $fileExists;
            else
                return image_file($this->avatar, 'avatar');
        }
    }
}
