<?php

namespace Modules\User\Entities;

use App\Models\CacheModel;
use App\Models\Categories\TitleRank;
use App\Models\Categories\Titles;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Model;

/**
 * Modules\User\Entities\ProfileLevel
 *
 * @method static \Illuminate\Database\Eloquent\Builder|ProfileLevel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProfileLevel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProfileLevel query()
 * @mixin \Eloquent
 */
class ProfileLevel extends Model
{
    use Cachable;
    protected $table = 'el_profile_level';
    protected $table_name = 'Cấp bậc nhân viên';
    protected $fillable = [
        'code',
        'name',
        'status',
    ];
    public static function syncAPIProfileLevel($url,$code)
    {
        $client = new Client();
        $data = $client->request('get',$url, ['verify' => false])->getBody()->getContents();
        \Storage::disk('public')->put("api/{$code}.json",$data);
        $file = \storage_path('app/public/api/'). "{$code}.json";
        $dataJson = json_decode(file_get_contents($file));
        foreach ($dataJson as $index => $item) {
            ProfileLevel::updateOrCreate(['code'=>$item->td_ma],[
                'code'=>$item->td_ma,
                'name'=>$item->td_ten,
                'status'=>$item->tinhtrangsd,
            ]);
        }
    }
}
