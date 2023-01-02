<?php

namespace App\Models;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Model;
use Modules\User\Entities\ProfileLevel;

/**
 * App\Models\Certificate
 *
 * @property int $id
 * @property string $certificate_code
 * @property string $certificate_name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Certificate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Certificate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Certificate query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Certificate whereCertificateCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Certificate whereCertificateName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Certificate whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Certificate whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Certificate whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Certificate extends BaseModel
{
    use Cachable;
    protected $table = 'el_cert';
    protected $table_name = "Trình độ";
    protected $fillable = [
        'certificate_code',
        'certificate_name',
        'status'
    ];
    protected $primaryKey = 'id';

    public static function getAttributeName() {
        return [
            'certificate_code' => 'Mã trình độ',
            'certificate_name' => 'Tên trình độ',
        ];
    }
    public static function syncAPICertificate($url,$code)
    {
        $client = new Client();
        $data = $client->request('get',$url, ['verify' => false])->getBody()->getContents();
        \Storage::disk('public')->put("api/{$code}.json",$data);
        $file = \storage_path('app/public/api/'). "{$code}.json";
        $dataJson = json_decode(file_get_contents($file));
        foreach ($dataJson as $index => $item) {
            Certificate::updateOrCreate(['certificate_code'=>$item->td_ma],[
                'certificate_code'=>$item->td_ma,
                'certificate_name'=>$item->td_ten,
                'status'=>(int)$item->tinhtrangsd,
            ]);
        }
    }
}
