<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVisitsTable extends Migration
{
    public function up()
    {
        Schema::create('visits', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('method')->nullable();
            $table->mediumText('request')->nullable();
            $table->mediumText('url')->nullable();
            $table->mediumText('referer')->nullable();
            $table->text('languages')->nullable();
            $table->text('useragent')->nullable();
            $table->text('headers')->nullable();
            $table->text('device')->nullable();
            $table->text('platform')->nullable();
            $table->text('browser')->nullable();
            $table->ipAddress('ip')->nullable();
    
            $table->string("visitable_type", 150)->nullable();
            $table->unsignedBigInteger("visitable_id")->nullable();
            $table->index(["visitable_type", "visitable_id"], 'visits_visitable');
    
            $table->string("visitor_type", 150)->nullable();
            $table->unsignedBigInteger("visitor_id")->nullable();
            $table->index(["visitor_type", "visitor_id"], 'visits_visitor');
            $table->timestamps();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('visits');
    }
}
