<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElDashboardUnitQuizTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_dashboard_unit_quiz', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('unit_id')->index();
            $table->string('unit_code')->nullable();
            $table->integer('quiz_id')->index();
            $table->integer('total')->default(0);
            $table->integer('unlearned')->default(0)->comment('SL Chưa thi');
            $table->integer('completed')->default(0)->comment('SL Hoàn thành');
            $table->integer('uncompleted')->default(0)->comment('SL Chưa hoàn thành');
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();
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
        Schema::dropIfExists('el_dashboard_unit_quiz');
    }
}
