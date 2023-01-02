<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElRoleHasPermissionType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_role_has_permission_type', function (Blueprint $table) {
            $table->integer('role_id');
            $table->integer('permission_type_id');
            $table->primary(['role_id','permission_type_id'],'mdl_el_role_has_permission_type_id_primary');
        });

        \DB::table('el_role_has_permission_type')->insert([
            [
                'role_id'=>6,
                'permission_type_id'=>6,
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
        Schema::dropIfExists('el_role_has_permission_type');
    }
}
