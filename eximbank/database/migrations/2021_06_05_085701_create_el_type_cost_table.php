<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateElTypeCostTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_type_cost', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code');
            $table->string('name');
            $table->integer('type')->default(1)->comment('1: Nội bộ; 2:Đối tác');
            $table->timestamps();
        });

        DB::table('el_type_cost')->insert([
            [
                'code' => 'CPTC',
                'name' => 'Chi Phí tổ chức',
                'type' => 1,
            ],
            [
                'code' => 'CPPĐT',
                'name' => 'Chi phí phòng đào tạo',
                'type' => 1,
            ],
            [
                'code' => 'CPĐTBN',
                'name' => 'Chi phí đào tạo bên ngoài',
                'type' => 1,
            ],
            [
                'code' => 'CPGV',
                'name' => 'Chi phí giảng viên',
                'type' => 1,
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('el_type_cost');
    }
}
