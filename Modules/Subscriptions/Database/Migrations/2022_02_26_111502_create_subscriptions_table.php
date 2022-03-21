<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;


class CreateSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->string('pixel_id');
            $table->string('user_id');
            $table->string('subscription_type');
            $table->string('license_id')->default(0);
            $table->string('pixel_purchase_date')->nullable();
            $table->string('license_purchase_date')->nullable();
            $table->integer('license_duration')->default(0);
            $table->string('license_expiration_date')->nullable();
            $table->string('last_reward_withdrawalDate')->nullable();
            $table->boolean('withdrawal_amount_is_paid')->nullable();
            $table->string('withdrawal_fee_payment_date')->nullable();
            $table->boolean('has_expired')->default(0);
            $table->unsignedDecimal('nolu_reward_amount')->default(0);
            $table->unsignedDecimal('usdt_reward_amount')->default(0);
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
        Schema::dropIfExists('subscriptions');
    }
}
