<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Eloquent\SoftDeletes;

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
            $table->string('image')->nullable();
            $table->unsignedDecimal('price')->default(0);
            $table->string('currency')->default('USD');
            $table->string('duration_in_days')->default(0);
            $table->unsignedDecimal('reward_amount')->default(0);
            $table->unsignedDecimal('withdrawal_fee')->default(0);
            $table->integer('pixel_id')->nullable();
            $table->boolean('is_active')->default(1)->comment('1 = active, 0 = inactive  (this filed is to control to deactivate license from the system)');
            $table->softDeletes();
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
