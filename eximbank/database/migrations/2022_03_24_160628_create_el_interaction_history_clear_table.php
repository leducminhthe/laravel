<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateElInteractionHistoryClearTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //Lịch sử ng thiết lập xóa lịch sử tương tác
        Schema::create('el_interaction_history_clear', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->comment('Ng thiết lập');
            $table->date('date_clear')->comment('Thời gian clear');
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
        Schema::dropIfExists('el_interaction_history_clear');
    }
}
