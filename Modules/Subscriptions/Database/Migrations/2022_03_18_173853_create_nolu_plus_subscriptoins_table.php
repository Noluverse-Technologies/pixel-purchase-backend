<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNoluPlusSubscriptoinsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nolu_plus_subscriptoins', function (Blueprint $table) {
            $table->id();
            $table->string('purchase_date')->nullable();
            $table->string('expiration_date')->nullable();
            $table->string('user_id')->nullable();
            $table->boolean('has_expired')->default(0);
            $table->boolean('nolu_plus_package_id')->nullable();
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
        Schema::dropIfExists('nolu_plus_subscriptoins');
    }
}
