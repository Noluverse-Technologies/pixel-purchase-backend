<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLicensePackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('license_packages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('short_name');
            $table->string('code')->unique()->comment('this is the license unique id');
            $table->string('type')->nullable();
            $table->integer('duration')->default(0);
            $table->string('image')->nullable();
            $table->unsignedDecimal('price')->default(0);
            $table->string('currency')->default('USD');
            $table->string('expiration_date');
            $table->integer('pixel_id')->nullable();
            $table->boolean('is_active')->default(1);
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
        Schema::dropIfExists('license_packages');
    }
}
