<?php

namespace App\Models;

use App\Helpers\LdapLogin;
use App\Traits\ChangeLogs;
use Carbon\Carbon;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Support\Facades\Cache;

/**
 * App\Models\User
 *
 * @property int $id
 * @property string $auth
 * @property int $confirmed
 * @property int $policyagreed
 * @property int $deleted
 * @property int $suspended
 * @property int $mnethostid
 * @property string $username
 * @property string $password
 * @property string $idnumber
 * @property string $firstname
 * @property string $lastname
 * @property string $email
 * @property int $emailstop
 * @property string $icq
 * @property string $skype
 * @property string $yahoo
 * @property string $aim
 * @property string $msn
 * @property string $phone1
 * @property string $phone2
 * @property string $institution
 * @property string $department
 * @property string $address
 * @property string $city
 * @property string $country
 * @property string $lang
 * @property string $calendartype
 * @property string $theme
 * @property string $timezone
 * @property int $firstaccess
 * @property int $lastaccess
 * @property int $lastlogin
 * @property int $currentlogin
 * @property string $lastip
 * @property string $secret
 * @property int $picture
 * @property string $url
 * @property string|null $description
 * @property int $descriptionformat
 * @property int $mailformat
 * @property int $maildigest
 * @property int $maildisplay
 * @property int $autosubscribe
 * @property int $trackforums
 * @property int $timecreated
 * @property int $timemodified
 * @property int $trustbitmask
 * @property string|null $imagealt
 * @property string|null $lastnamephonetic
 * @property string|null $firstnamephonetic
 * @property string|null $middlename
 * @property string|null $alternatename
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereAim($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereAlternatename($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereAuth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereAutosubscribe($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCalendartype($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereConfirmed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCurrentlogin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereDeleted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereDepartment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereDescriptionformat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailstop($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereFirstaccess($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereFirstname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereFirstnamephonetic($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereIcq($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereIdnumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereImagealt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereInstitution($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLang($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLastaccess($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLastip($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLastlogin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLastname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLastnamephonetic($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereMaildigest($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereMaildisplay($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereMailformat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereMiddlename($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereMnethostid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereMsn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePhone1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePhone2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePicture($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePolicyagreed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereSecret($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereSkype($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereSuspended($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTheme($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTimecreated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTimemodified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTimezone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTrackforums($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTrustbitmask($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUsername($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereYahoo($value)
 * @mixin \Eloquent
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Permission\Models\Permission[] $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Permission\Models\Role[] $roles
 * @property-read int|null $roles_count
 * @method static \Illuminate\Database\Eloquent\Builder|User permission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder|User role($roles, $guard = null)
 * @property-read \App\Models\Profile|null $profile
 * @property string|null $last_online
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Survey\Entities\Survey[] $surveys
 * @property-read int|null $surveys_count
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLastOnline($value)
 * @property-read \App\Models\Categories\TrainingTeacher|null $teacher
 * @property string|null $created_at
 * @property string|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 */
class User extends Authenticatable implements JWTSubject
{
    use Notifiable, HasRoles, ChangeLogs, Cachable;

    protected $table = 'user';
    protected $fillable = [
        'username', 'email', 'confirmed', 'mnethostid', 'last_online', 'code'
    ];
    protected $hidden = [
        'password',
    ];
    protected $appends  = ['avatar'];
    public $timestamps = false;
    private static $user = null;
    public function notifications()
    {
        return $this->morphMany(Notifications::class, 'notifiable')->orderBy('created_at', 'desc');
    }

    public function getAvatarAttribute()
    {
        return Profile::avatar($this->id);
    }
    public function profile()
    {
        return $this->hasOne(Profile::class, 'user_id');
    }

    public function surveys()
    {
        return $this->belongsToMany('Modules\Survey\Entities\Survey', 'el_survey_user', 'user_id', 'survey_user');
    }

    public function teacher()
    {
        return $this->hasOne('App\Models\Categories\TrainingTeacher', 'user_id', 'id');
    }

    public function updateAnalytics()
    {
        if ($this->id) {
            $analytics = Analytics::where('user_id', '=', $this->id)->where('day', '=', date('Y-m-d'))->orderBy('id', 'desc')->first();

            if ($analytics) {
                Analytics::where('id', '=', $analytics->id)->update(['end_date' => date('Y-m-d H:i:s')]);
            } else {
                $analytic = new Analytics();
                $analytic->user_id = $this->id;
                $analytic->ip_address = request()->ip();
                $analytic->start_date = date('Y-m-d H:i:s');
                $analytic->end_date = date('Y-m-d H:i:s');
                $analytic->day = date('Y-m-d');
                $analytic->save();
            }
        }
    }

    public function isAdmin()
    {

        if (in_array(Auth::user()->username, ['admin', 'superadmin']))
            return true;
        return self::getInstance()->roles()->where('name', 'Admin')->count();
    }

    public function existsRole()
    {
        return self::getInstance()->roles()->count();
    }
    public function isTeacher()
    {
        return User::isRoleTeacher() && $this->teacher()->exists();
    }

    /**
     * Check and login user.
     * @param string $password
     * @param bool $remember
     * @return bool
     * */
    public function login($password, $remember = false)
    {
        if ($this->auth == 'ldap') {
            $ldap = new LdapLogin();
            if ($ldap->login($this->username, $password)) {
                Auth::loginUsingId($this->id);
                return true;
            }
        }

        if ($this->auth == 'manual') {
            $auth = Auth::attempt([
                'username' => $this->username,
                'password' => $password
            ], $remember);

            if ($auth) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get profile info by user id
     * @param int $id
     * @return \App\Models\Profile
     * */
    public static function getProfileById($id)
    {
        $user = self::find($id);
        return $user;
    }

    /**
     * Count user online.
     * @return int
     * */
    public static function countUsersOnline()
    {
        $users = Cache::get('online-users');
        if(!$users) return null;
        $onlineUsers = collect($users)->count();
        return $onlineUsers;
    }
    public static function canPermissionReport()
    {
        $user = self::getInstance();
        return User::isRoleManager() && Cache::rememberForever('User.canPermissionReport.' .  self::getUserRole(), function () use ($user) {
            return  $user->isAdmin() || \DB::table('el_model_has_permissions as a')->join('el_permissions as b', 'a.permission_id', '=', 'b.id')
                ->where(['a.model_id' => $user->id])->where('b.name', 'like', '%report%')->exists() || (profile()->user_id == 2);
        });
    }
    //Quyền danh mục
    public static function canPermissionCategory()
    {
        $user = self::getInstance();
        return  User::isRoleManager() && Cache::rememberForever('User.canPermissionCategory.' . self::getUserRole(), function () use ($user) {
                return  $user->isAdmin() || self::canPermissionCategoryUnit()
                    || self::canPermissionCategoryArea()
                    || self::canPermissionCategoryInfo()
                    || self::canPermissionCategorySubject()
                    || self::canPermissionCategoryDiscipline()
                    || self::canPermissionCategoryCost()
                    || self::canPermissionCategoryTeacher()
                    || self::canPermissionCategoryTrainingLocation()
                    || self::canPermissionCategoryUserPoint()
                    || self::canPermissionCategoryMedal() ;
            });
    }
    //Quyền Danh mục tổ chức
    public static function canPermissionCategoryUnit()
    {
        $user = self::getInstance();
        return User::isRoleManager() && Cache::rememberForever('User.canPermissionCategoryUnit.' .  self::getUserRole(), function () use ($user) {
            return $user->isAdmin() || $user->can('category-unit') ;
        });
    }

    public static function canPermissionCategoryInfo()
    {
        $user = self::getInstance();
        return  User::isRoleManager() && Cache::rememberForever('User.canPermissionCategoryInfo.' .  self::getUserRole(), function () use ($user) {
            return $user->isAdmin() || $user->can('category-unit-type') || $user->can('category-title-rank') || $user->can('category-titles')
                || $user->can('category-cert') || $user->can('category-position') ;
        });
    }
    //Quyền Chương trình học phần
    public static function canPermissionCategorySubject()
    {
        $user = self::getInstance();
        return User::isRoleManager() && Cache::rememberForever('User.canPermissionCategorySubject.' .  self::getUserRole(), function () use ($user) {
            return $user->isAdmin() || $user->can('category-subject-type') || $user->can('category-training-program') || $user->can('category-level-subject')
                || $user->can('category-subject') || $user->can('category-training-form') || $user->can('category-training-type') || $user->can('category-quiz-type')
                || $user->can('category-training-object');
        });
    }
    // Quyền kỷ luật
    public static function canPermissionCategoryDiscipline()
    {
        $user = self::getInstance();
        return  User::isRoleManager() && Cache::rememberForever('User.canPermissionCategoryDiscipline.' . self::getUserRole(), function () use ($user) {
            return $user->isAdmin() || $user->can('category-absent') || $user->can('category-discipline') || $user->can('category-absent-reason');
        });
    }
    // Quyền chi phí
    public static function canPermissionCategoryCost()
    {
        $user = self::getInstance();
        return  User::isRoleManager()  && Cache::rememberForever('User.canPermissionCategoryCost.' .  self::getUserRole(), function () use ($user) {
            return $user->isAdmin() || $user->can('category-type-cost') || $user->can('category-student-cost') || $user->can('commit-month');
        });
    }
    // Quyền giảng viên
    public static function canPermissionCategoryTeacher()
    {
        $user = self::getInstance();
        return User::isRoleManager() && Cache::rememberForever('User.canPermissionCategoryTeacher.' . self::getUserRole(), function () use ($user) {
                return $user->isAdmin() || $user->can('category-teacher') || $user->can('category-teacher-type')
                    || $user->can('category-partner') || userCan('coaching-group') || $user->can('coaching-mentor-method');
            });
    }
    // Quyền địa điểm đào tạo
    public static function canPermissionCategoryTrainingLocation()
    {
        $user = self::getInstance();
        return  User::isRoleManager() && Cache::rememberForever('User.canPermissionCategoryTrainingLocation.' .  self::getUserRole(), function () use ($user) {
                return $user->isAdmin() || $user->can('category-province') || $user->can('category-district')
                    || $user->can('category-training-location') ;
            });
    }
    //Quyền điểm thưởng
    public static function canPermissionCategoryUserPoint()
    {
        $user = self::getInstance();
        return  User::isRoleManager() && Cache::rememberForever('User.canPermissionCategoryUserPoint.' .  self::getUserRole(), function () use ($user) {
                return $user->isAdmin() || $user->can('category-userpoint-item')  ;
            });
    }
    //Quyền chương trình thi đua
    public static function canPermissionCategoryMedal()
    {
        $user = self::getInstance();
        return   User::isRoleManager() && Cache::rememberForever('User.canPermissionCategoryMedal.' .  self::getUserRole(), function () use ($user) {
                return $user->isAdmin() || $user->can('category-usermedal')  ;
            });
    }
    //Quyền vị trí địa lý
    public static function canPermissionCategoryArea()
    {
        $user = self::getInstance();
        return User::isRoleManager() && Cache::rememberForever('User.canPermissionCategoryArea.' .  self::getUserRole(), function () use ($user) {
                return $user->isAdmin() || $user->can('category-area')  ;
            });
    }
    //Quyền quản lý nhân viên
    public static function canPermissionGeneralEmployee()
    {
        $user = self::getInstance();
        return Cache::rememberForever('User.canPermissionGeneralEmployee.' .  self::getUserRole(), function () use ($user) {
                return $user->isAdmin() || User::isRoleLeader() ||
                    $user->can('user') || $user->can('quiz-user-secondary') || $user->can('user-take-leave') ;
            });
    }
    //Quyền quản lý lịch sử
    public static function canPermissionGeneralHistory()
    {
        $user = self::getInstance();
        return  User::isRoleManager() && Cache::rememberForever('User.canPermissionGeneralHistory.' .  self::getUserRole(), function () use ($user) {
                return $user->isAdmin() || $user->can('model-history') || $user->can('login-history') || $user->can('log-view-course')  ;
            });
    }
    //Quyền quản lý
    public static function canPermissionGeneral()
    {
        $user = self::getInstance();
        return  User::isRoleManager() && Cache::rememberForever('User.canPermissionGeneral.' .  self::getUserRole(), function () use ($user) {
                return  $user->isAdmin() || $user->can('topic') || User::canPermissionGeneralEmployee()
                    || $user->can('target-manager-parent') || $user->can('forum') || $user->can('suggest') || $user->can('note') || $user->can('survey')
                    || $user->can('career-roadmap') || User::isRoleLeader()
                    || $user->can('plan-suggest') || User::canPermissionGeneralHistory() || $user->can('FAQ') || $user->can('guide') || $user->can('coaching-teacher');
            });
    }
    public static function canPermissionCategoryCourse()
    {
        $user = self::getInstance();
        return Cache::rememberForever('User.canPermissionCategoryCourse.' .  self::getUserRole(), function () use ($user) {
            return $user->isAdmin() || $user->can('category-training-program') || $user->can('category-level-subject')
                || $user->can('category-subject') || $user->can('category-training-location') || $user->can('category-training-form');
        });
    }
    public static function canPermissionCategoryQuiz()
    {
        $user = self::getInstance();
        return Cache::rememberForever('User.canPermissionCategoryQuiz.' .  self::getUserRole(), function () use ($user) {
            return $user->isAdmin() || $user->can('category-quiz-type');
        });
    }

    public function role($permission = false)
    {
        $role = session()->get('user_role');
        // if ($permission) {
        //     return Auth::user()->roles()->first()->code;
        // }
        return $role ?? Auth::user()->roles()->first()->code;
    }
    public static function getUserRole()
    {
        $check_isset_role = session()->get('user_role');
        $role = $check_isset_role ?? Auth::user()->roles()->first()->code;

        if($role)
            return $role;
        else{
            return session()->get('user_role');
        }
//        return  session()->get('user_role_selected');
    }
    public static function canPermissionCategoryProvince()
    {
        $user = self::getInstance();
        return Cache::rememberForever('User.canPermissionCategoryProvince.' . self::getUserRole(), function () use ($user) {
            return $user->isAdmin() || $user->can('category-province') || $user->can('category-district');
        });
    }
    // quyền tin tức chung
    public static function canPermissionNewsGeneral()
    {
        $user = self::getInstance();
        return Cache::rememberForever('User.canPermissionNewsGeneral.' . self::getUserRole(), function () use ($user) {
            return $user->isAdmin() || $user->can('news-outside-category') || $user->can('news-outside-list');
        });
    }
    // quản lý đào tạo
    public static function canPermissionTrainingManager()
    {
        $user = self::getInstance();
        return  User::isRoleManager() && Cache::rememberForever('User.canPermissionTrainingManager.' .  self::getUserRole(), function () use ($user) {
            return  $user->isAdmin() || $user->can('training-roadmap') || $user->can('training-by-title')
                || $user->can('training-by-title-result') || $user->can('mergesubject') || $user->can('splitsubject')  || $user->can('subjectcomplete')
                || $user->can('movetrainingprocess') ;
        });
    }
    // Hoạt động đào tạo - chứng chỉ
    public static function canPermissionTrainingCert()
    {
        $user = self::getInstance();
        return User::isRoleManager() && Cache::rememberForever('User.canPermissionTrainingCert.' . self::getUserRole(), function () use ($user) {
            return $user->isAdmin() || $user->can('certificate-template') || $user->can('category-subject-type')
                || $user->can('certificate-template-kpi')  ;
        });
    }
    //Hoạt động dào tạo - đánh giá đào tạo
    public static function canPermissionTrainingRate()
    {
        $user = self::getInstance();
        return  User::isRoleManager() && Cache::rememberForever('User.canPermissionTrainingRate.' . self::getUserRole(), function () use ($user) {
            return $user->isAdmin() || $user->can('rating-template') || $user->can('plan-app-template') || $user->can('rating-levels');
        });
    }
    // Tổ chức đào tạo
    public static function canPermissionTrainingOrganization()
    {
        $user = self::getInstance();
        return  User::isRoleManager() && Cache::rememberForever('User.canPermissionTrainingOrganization.' .  self::getUserRole(), function () use ($user) {
            return $user->isAdmin() || $user->can('online-course') || $user->can('offline-course')
                || $user->can('training-plan') || $user->can('course-plan') || $user->can('training-teacher-register');
        });
    }

    // Quiz
    public static function canPermissionQuiz()
    {
        $user = self::getInstance();
        return  User::isRoleManager() && Cache::rememberForever('User.canPermissionQuiz.' . self::getUserRole(), function () use ($user) {
            return  $user->isAdmin() || $user->can('quiz-category-question') || $user->can('quiz') || $user->can('quiz-grading') || $user->can('quiz-template')
                || $user->can('quiz-dashboard') ||   $user->can('quiz-history') || $user->can('quiz-history-user-second');
        });
    }
    // Thư viện
    public static function canPermissionSalesKit()
    {
        $user = self::getInstance();
        return  User::isRoleManager() && Cache::rememberForever('User.canPermissionSalesKit.' .  self::getUserRole(), function () use ($user) {
             return $user->isAdmin() || $user->can('saleskit') || $user->can('saleskit-category');
        });
    }
    // Thư viện
    public static function canPermissionLibraries()
    {
        $user = self::getInstance();
        return  User::isRoleManager() && Cache::rememberForever('User.canPermissionLibraries.' .  self::getUserRole(), function () use ($user) {
             return $user->isAdmin() || $user->can('libraries-book') || $user->can('libraries-ebook')
                 || $user->can('libraries-document') || $user->can('libraries-video')
                || $user->can('libraries-category') || $user->can('libraries-book-register') || $user->can('libraries-book-register');
            });
    }
    //Tin tức
    public static function canPermissionNews()
    {
        $user = self::getInstance();
        return  User::isRoleManager() && Cache::rememberForever('User.canPermissionNews.' .  self::getUserRole(), function () use ($user) {
                return $user->isAdmin() || $user->can('news-category') || $user->can('news-list')
                    ;
            });
    }
    // Tích lũy điểm + quà tặng
    public static function canPermissionGiftPromotion()
    {
        $user = self::getInstance();
        return  User::isRoleManager() && Cache::rememberForever('User.canPermissionGiftPromotion.' .  self::getUserRole(), function () use ($user) {
                return $user->isAdmin() || $user->can('promotion') || $user->can('promotion-group')
                    || $user->can('promotion-level') || $user->can('promotion-purchase-history') || $user->can('donate-point')
                    || $user->can('promotion-history');
            });

    }
    // Chương trình thi dua
    public static function canPermissionMedalSetting()
    {
        $user = self::getInstance();
        return  User::isRoleManager() && Cache::rememberForever('User.canPermissionMedalSetting.' .  self::getUserRole(), function () use ($user) {
                return $user->isAdmin() || $user->can('usermedal-setting');
            });

    }
    // học liệu video
    public static function canPermissionTrainingVideo()
    {
        $user = self::getInstance();
        return  User::isRoleManager() && Cache::rememberForever('User.canPermissionTrainingVideo.' .  self::getUserRole(), function () use ($user) {
                return $user->isAdmin() || $user->can('daily-training');
            });
    }
    public static function canPermissionUnit()
    {
        $user = self::getInstance();
        return  Cache::rememberForever('User.canPermissionUnit.' .  self::getUserRole(), function () use ($user) {
                return $user->isAdmin() || User::isRoleLeader();
            });
    }
    // phân quyền
    public static function canPermissionRule()
    {
        $user = self::getInstance();
        return  User::isRoleManager() && Cache::rememberForever('User.canPermissionRule.' .  self::getUserRole(), function () use ($user) {
                return $user->isAdmin() || $user->can('permission-group') || $user->can('role') ||
                    $user->can('approved-process') || $user->can('unit-manager-setting');
        });
    }
    // setting - cài đặt
    public static function canPermissionSetting()
    {
        $user = self::getInstance();
        return   User::isRoleManager() && Cache::rememberForever('User.canPermissionSetting.' .  self::getUserRole(), function () use ($user) {
                return $user->isAdmin() || $user->can('config') || $user->can('config-email') ||
                    $user->can('config-notify-send') || $user->can('config-notify-template') || $user->can('config-app-mobile') || $user->can('config-favicon')
                    || $user->can('config-logo') || $user->can('config-login-image') || $user->can('config-point-refer') || $user->can('mail-template')
                    || $user->can('mail-template-history') || $user->can('guide') || $user->can('banner') || $user->can('donate-point') || $user->can('FAQ')
                    || $user->can('contact') || $user->can('google-map') || $user->can('banner') || $user->can('setting-color') || $user->can('languages')
                    || $user->can('setting-time') || $user->can('setting-experience-navigate') || $user->can('dashboard-by-user')
                    || $user->can('interaction-history-clear') || $user->can('interaction-history-clear')
                ;
        });
    }
    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
    private static function getInstance()
    {
        if (self::$user === null) self::$user = Auth::user();
        return self::$user;
    }
    private function isRole($role=''){
        $user_role = session('user_role');
        return ($role==$user_role) ? true: false;
    }

    public static function isRoleUnitManager()
    {
        return (new self())->isRole('unit_manager');
    }
    public static function isRoleManager()
    {
        return (new self())->isRole('manager') ;
    }
    public static function isRoleTeacher()
    {
        return (new self())->isRole('teacher');
    }
    public static function isRoleLeader(){
        return (new self())->isRole('unit_manager');
    }
}
