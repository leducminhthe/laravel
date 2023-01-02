<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElOfflineTeachingOrganizationUserCategoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_offline_teaching_organization_user_category', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('teaching_organization_user_id');
            $table->bigInteger('category_id');
            $table->longText('category_name')->nullable();
            $table->integer('rating_teacher')->default(0)->nullable();
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
        Schema::dropIfExists('el_offline_teaching_organization_user_category');
    }
}
