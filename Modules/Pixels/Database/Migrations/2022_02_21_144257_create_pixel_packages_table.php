<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePixelPackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pixel_packages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('short_name');
            $table->string('code')->unique()->comment('this is the pixel unique id');;
            $table->string('type')->nullable();
            $table->string('image')->nullable();
            $table->unsignedDecimal('price')->default(0);
            $table->string('currency')->default('USD');
            $table->integer('duration_in_days')->default(-1)->comment('-1 means unlimited');
            $table->integer('license_id')->nullable();
            $table->boolean('is_active')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pixel_packages');
    }
}
