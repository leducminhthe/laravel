<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElUnitNameTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //if (!Schema::hasTable('el_unit_name')) {
            Schema::create('el_unit_name', function (Blueprint $table) {
                $table->integerIncrements('id');
                $table->integer('level')->index();
                $table->string('name');
                $table->string('description')->nullable();
                $table->string('name_en')->nullable();
                $table->timestamps();
            });

            DB::table('el_unit_name')->insert(
                [
                    [
                        'level' => 0,
                        'name' => 'Danh mục công ty',
                        'name_en' => 'Unit 0'
                    ],
                    [
                        'level' => 1,
                        'name' => 'Đơn vị 1',
                        'name_en' => 'Unit 1'
                    ],
                    [
                        'level' => 2,
                        'name' => 'Đơn vị 2',
                        'name_en' => 'Unit 2'
                    ],
                    [
                        'level' => 3,
                        'name' => 'Đơn vị 3',
                        'name_en' => 'Unit 3'
                    ],
                    [
                        'level' => 4,
                        'name' => 'Đơn vị 4',
                        'name_en' => 'Unit 4'
                    ],
                    [
                        'level' => 5,
                        'name' => 'Đơn vị 5',
                        'name_en' => 'Unit 5'
                    ],
                    [
                        'level' => 6,
                        'name' => 'Đơn vị 6',
                        'name_en' => 'Unit 6'
                    ],
                ]
            );
        //}
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('el_unit_name');
    }
}
