<?php

namespace Modules\TableManager\Entities;

use App\Models\CacheModel;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

/**
 * Modules\TableManager\Entities\Table
 *
 * @property int $id
 * @property string $code
 * @property string $name tên table
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Table newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Table newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Table query()
 * @method static \Illuminate\Database\Eloquent\Builder|Table whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Table whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Table whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Table whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Table whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Table extends Model
{
    use Cachable;
    protected $table = 'el_table';
    protected $fillable = [
        'code',
        'name',
    ];

    public static function getAllModel()
    {
        // Model Module
        $modules = \Module::all();
        foreach ($modules as $index => $item) {
            $module = \Module::find($item->name);
            $class = \Barryvdh\LaravelIdeHelper\ClassMapGenerator::createMap($module->getPath().'/Entities');
            foreach ($class as $index => $item) {
                $_class = new $index();

                $obj = new \ReflectionClass($_class);
                if ($obj->hasProperty('table_name')) {
                    $pros = new \ReflectionProperty($_class, 'table');
                    $pros->setAccessible(true);
                    $code = $pros->getValue($_class);

                    $pros = new \ReflectionProperty($index, 'table_name');
                    $pros->setAccessible(true);
                    $name = $pros->getValue($_class);

                    $commands_arr[] = (object)['code'=>$code,'name'=>$name];
                }
            }
        };

        //Model app
        $class =\Barryvdh\LaravelIdeHelper\ClassMapGenerator::createMap(app_path('/Models'));
        foreach ($class as $index => $item) {
            $_class = new $index();

            $obj = new \ReflectionClass($_class);
            if ($obj->hasProperty('table_name')) {
                $pros = new \ReflectionProperty($_class, 'table');
                $pros->setAccessible(true);
                $code = $pros->getValue($_class);

                $pros = new \ReflectionProperty($index, 'table_name');
                $pros->setAccessible(true);
                $name = $pros->getValue($_class);

                $commands_arr[] = (object)['code'=>$code,'name'=>$name];
            }
        }
        return collect($commands_arr);
    }
}
