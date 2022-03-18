<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->integer('type')->nullable()->comment('1: Added, 2: Withdrawn');
            $table->boolean('is_pixel_purchased')->nullable()->comment('0: No, 1: Yes');
            $table->boolean('is_license_purchased')->nullable()->comment('0: No, 1: Yes');
            $table->boolean('is_withdrawal_amount_paid')->nullable()->comment('0: No, 1: Yes');
            $table->boolean('is_reward_claimed')->nullable()->comment('0: No, 1: Yes');
            $table->boolean('is_nolu_plus_purchased')->nullable()->comment('0: No, 1: Yes');
            $table->double('nolu_plus_subscription_id')->nullable();
            $table->double('pixel_id')->nullable();
            $table->double('pixel_amount')->nullable();
            $table->double('nolu_plus_amount')->nullable();
            $table->double('license_id')->nullable();
            $table->double('license_amount')->nullable();
            $table->double('withdrawal_fee_amount')->nullable();
            $table->double('reward_claimed_amount')->nullable();
            $table->integer('user_id')->nullable();
            $table->string('date')->nullable();
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
        Schema::dropIfExists('transactions');
    }
}
