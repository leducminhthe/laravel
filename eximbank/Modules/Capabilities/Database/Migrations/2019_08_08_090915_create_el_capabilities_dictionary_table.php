<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElCapabilitiesDictionaryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_capabilities_dictionary', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('capabilities_id')->unsigned()->comment('el_capabilities id');

            $table->longText('basic_apply')->nullable()->comment('mức độ áp dụng cấp 1');
            $table->longText('medium_apply')->nullable()->comment('mức độ áp dụng cấp 2');
            $table->longText('advanced_apply')->nullable()->comment('mức độ áp dụng cấp 3');
            $table->longText('profession_apply')->nullable()->comment('mức độ áp dụng cấp 4');

            $table->longText('basic_complex')->nullable()->comment('mức độ phức tạp cấp 1');
            $table->longText('medium_complex')->nullable()->comment('mức độ phức tạp cấp 2');
            $table->longText('advanced_complex')->nullable()->comment('mức độ phức tạp cấp 3');
            $table->longText('profession_complex')->nullable()->comment('mức độ phức tạp cấp 4');

            $table->longText('basic_affect')->nullable()->comment('phạm vi ảnh hưởng cấp 1');
            $table->longText('medium_affect')->nullable()->comment('phạm vi ảnh hưởng cấp 2');
            $table->longText('advanced_affect')->nullable()->comment('phạm vi ảnh hưởng cấp 3');
            $table->longText('profession_affect')->nullable()->comment('phạm vi ảnh hưởng cấp 4');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('el_capabilities_dictionary');
    }
}
