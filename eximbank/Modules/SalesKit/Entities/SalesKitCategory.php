<?php

namespace Modules\SalesKit\Entities;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;

class SalesKitCategory extends BaseModel
{
    protected $table = 'el_sales_kit_category';
    protected $fillable = [
        'name',
        'parent_id',
        'created_by',
        'updated_by',
        'bg_mobile',
    ];
    protected $primaryKey = 'id';
    protected $casts = ['parent_id' => 'integer'];

    public static function getAttributeName() {
        return [
            'name' => trans('laother.category_name'),
            'created_by' => trans('laother.creator'),
            'updated_by' => trans('laother.editor'),
        ];
    }

    public static function getTreeParentUnit($id, &$result = []) {
        $records = self::select('id','name','parent_id')->where('id',$id)->get();
        foreach ($records as $key => $record) {
            $result[] = $record;
            if ($record->parent_id) {
                self::getTreeParentUnit($record->parent_id, $result);
            }
        }

        return $result;
    }
}
