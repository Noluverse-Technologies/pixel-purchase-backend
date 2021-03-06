<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNoluPlusPackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nolu_plus_packages', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->integer('duration_in_days')->nullable();
            $table->float('discount_percentage')->nullable();
            $table->float('price')->nullable();
            $table->float('withdrawal_fee')->nullable();
            $table->float('discount_on_stores')->nullable();
            $table->string('currency')->nullable();
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
        Schema::dropIfExists('nolu_plus_packages');
    }
}
