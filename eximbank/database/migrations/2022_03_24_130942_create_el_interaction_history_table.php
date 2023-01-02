<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateElInteractionHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //Lịch sử tương tác của HV
        Schema::create('el_interaction_history', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('code')->comment('Mã loại tương tác. Tự quy định');
            $table->string('name')->nullable()->comment('Tên loại tương tác');
            $table->integer('number')->default(0)->comment('số lần tương tác');
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
        Schema::dropIfExists('el_interaction_history');
    }
}
