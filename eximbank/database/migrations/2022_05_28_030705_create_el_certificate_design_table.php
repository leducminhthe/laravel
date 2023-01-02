<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateElCertificateDesignTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_certificate_design', function (Blueprint $table) {
            // $table->id();
            $table->integer('certificate_id')->nullable();
            $table->string('name')->nullable();
            $table->string('type', 10);
            $table->string('align')->nullable();
            $table->integer('pleft')->nullable()->index('el_cert_unit_by_index');
            $table->integer('ptop')->nullable();
            $table->integer('font_size')->nullable();
            $table->string('color')->nullable();
            $table->string('value')->nullable();
            $table->integer('status')->nullable()->default(1)->index('el_cert_status_index');
            $table->integer('created_by')->nullable()->default(2)->index('el_cert_created_by_index');
            $table->integer('updated_by')->nullable()->default(2)->index('el_cert_updated_by_index');
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
        Schema::dropIfExists('el_certificate_design');
    }
}
