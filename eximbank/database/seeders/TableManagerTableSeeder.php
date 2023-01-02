<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\TableManager\Entities\Table;

class TableManagerTableSeeder extends Seeder
{
    public function run()
    {
        $models = Table::getAllModel();
        //dd($models);
        foreach($models as $model){
            $exists = Table::whereCode($model->code)->exists();
            if(!$exists){
                $table = new Table();
                $table->code = $model->code;
                $table->name = $model->name;
                $table->save();
            }
        }
    }
}
