<?php

namespace App\Traits;

use App\Models\Profile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;

trait CacheModel
{
    public static function getKeyCache()
    {
        $part['model'] = self::class;
        $part['uri'] = request()->route()->uri;
        $part['user_id'] = profile()->user_id;
        $request =  request()->all();
        array_push($part,$request);
        $key = Arr::query($part);
        return $key;
    }
    public static function removeCache()
    {
        $prefixCache = Cache::getPrefix();
        $part['model'] = self::class;
        $key = Arr::query($part);;
        $redis = Redis::connection('cache');
        $cacheKey = $redis->keys("{$prefixCache}{$key}*");
        foreach ($cacheKey as $index => $item) {
            $redis->del($item);
//            Cache::forget($item);
        }
    }
}
