<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Categories\Unit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class UnitTableSeeder extends Seeder
{
    public function run()
    {
        $model = Unit::firstOrNew(['code' => 'company']);
        $model->code = 'company';
        $model->name = 'Công ty';
        $model->level = 0;
        $model->parent_code = null;
        $model->status = 1;
        $model->email = null;
        $model->type = null;
        $model->note1 = null;
        $model->note2 = null;
        $model->created_by = 2;
        $model->updated_by = 2;
        $model->area_id = null;
        $model->save();
    }
}
