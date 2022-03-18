<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('firstname');
            $table->string('lastname');
            $table->string('email')->unique();
            $table->string('wallet_address')->nullable();
            $table->string('image')->nullable();
            $table->string('user_type')->default("nolu");
            $table->boolean('is_nolu_plus')->default(0);
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password', 60)->nullable()->default(null);
            $table->enum('role',  [1, 2, 3])->default(3)->comment("admin->1, subscribed->2, nonsubscribed->3");
            $table->boolean('is_licensed')->default(0)->comment('0->not licensed, 1->licensed');
            $table->boolean('pixel_purchased')->default(0)->comment('0->not purchased, 1->purchased');
            $table->boolean('is_active')->default(1)->comment('0->inactive, 1->active');
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
