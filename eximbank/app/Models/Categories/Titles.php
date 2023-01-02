<?php

namespace App\Models\Categories;

use App\Models\BaseModel;
use App\Console\Commands\Title;
use App\Models\Profile;
use App\Traits\ChangeLogs;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Model;
use Modules\Online\Entities\OnlineObject;
use Spatie\Permission\Traits\HasRoles;

/**
 * App\Models\Categories\Titles
 *
 * @property int $id
 * @property string $code
 * @property string $name
 * @property string|null $group
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\Titles newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\Titles newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\Titles query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\Titles whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\Titles whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\Titles whereGroup($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\Titles whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\Titles whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\Titles whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\Titles whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string|null $units
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\Titles whereUnits($value)
 * @property int|null $unit_id
 * @property int|null $unit_level
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\Titles whereUnitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\Titles whereUnitLevel($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|Profile[] $profiles
 * @property-read int|null $profiles_count
 */
class Titles extends BaseModel
{
    use ChangeLogs, HasRoles, Cachable;

    protected $table = 'el_titles';
    protected $table_name = "Chức danh";
    protected $fillable = [
        'code',
        'name',
        'unit',
        'group',
        'unit_id',
        'unit_level',
        'status',
        'position_id',
        'employees',
        'unit_type',
        'title_time_kpi',
        'user_time_kpi',
    ];
    protected $primaryKey = 'id';
    protected $guard_name = 'web';

    public static function getAttributeName() {
        return [
            'code' => trans('lacategory.title_code'),
            'name' => trans('lacareer_path.title_name'),
            'unit' => trans('lamenu.unit'),
            'group' => 'Cấp bậc chức danh',
            'status' => trans("latraining.status"),
            'unit_type' => 'Loại đơn vị',
        ];
    }

//    public function objectsOnline()
//    {
//        return $this->hasMany(OnlineObject::class);
//    }
    public function profiles()
    {
        return $this->hasMany(Profile::class,'title_code','code');
    }
    public static function syncAPITitle($url,$code)
    {
        $client = new Client();
        $data = $client->request('get',$url, ['verify' => false])->getBody()->getContents();
        \Storage::disk('public')->put("api/{$code}.json",$data);
        $file = \storage_path('app/public/api/'). "{$code}.json";;
        $dataJson = json_decode(file_get_contents($file));
        foreach ($dataJson as $index => $item) {
            $group = TitleRank::where('code',$item->cbac_ma)->value('id');
            Titles::updateOrCreate(['code'=>$item->cd_ma],[
                'code'=>$item->cd_ma,
                'name'=>$item->cd_ten,
                'group'=>$group,
                'status'=>$item->tinhtrangsd,
            ]);
        }
    }
}
