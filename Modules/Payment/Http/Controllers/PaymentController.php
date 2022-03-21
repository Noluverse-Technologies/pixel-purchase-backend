<?php

namespace Modules\Payment\Http\Controllers;

use App\Http\Controllers\GenericResponseController;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Payment\Entities\Transactions;
use Illuminate\Support\Facades\Validator;
use Modules\Subscriptions\Entities\NoluPlusSubscriptoin;
use Modules\Subscriptions\Entities\Subscriptions;

Carbon::setLocale('en');

class PaymentController extends GenericResponseController
{


    public function getAllTransactionByUser(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'user_id' => 'exists:users,id'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $user_id = $request->user_id;
        $transactions = Transactions::where('user_id', $user_id)->get();


        return $this->sendResponse($transactions, 'User Transaction retrieved successfully.');
    }


    public function getUserTransactionsByMonth(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'user_id' => 'exists:users,id'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $user_id = $request->user_id;
        $transactions = Transactions::with(['hasPixel', 'hasLicense', 'hasNoluPlusSubscription', 'hasNoluPlusSubscription.hasNoluPlusPackage'])->whereMonth('created_at', $request->month)->where('user_id', $user_id)->get();
        $getPixelAmountSum = Transactions::sum('pixel_amount');
        $getLicenseAmountSum = Transactions::sum('license_amount');
        $getMaintainanceFeeSum = Transactions::sum('withdrawal_fee_amount');
        $getRewardAmount = Transactions::sum('reward_claimed_amount');
        $getNoluPlus = Transactions::sum('nolu_plus_amount');

        $totalCredits = $getPixelAmountSum + $getLicenseAmountSum + $getMaintainanceFeeSum;

        $totalDebits = $getRewardAmount + $getNoluPlus;

        if (count($transactions) > 0) {
            return $this->sendResponse([$transactions, "total_credits" => $totalCredits, "total_debits" => $totalDebits], 'User Transaction retrieved successfully.');
        } else {
            return $this->sendResponse([$transactions, "total_credits" => 0, "total_debits" => 0], 'User Transaction retrieved successfully.');
        }
    }



    public function calculateReward(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'subscription_id' => 'exists:subscriptions,id'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $getAllSubscriptions = Subscriptions::with('hasUser', 'hasPixel', 'hasLicense')->get();

        foreach ($getAllSubscriptions as $subscription) {

            if ($subscription->license_id != null) {


                //check if the subscription has not expired
                if ($subscription->hasExpired == 1) {
                    return;
                } else {
                    $getUsdtToNoluConversionRate = config('app.oneNoluToUSDT');
                    $getTodaysDate = Carbon::now();
                    //get the license purchase date
                    $lastRewardEarnedDate = Carbon::parse($subscription->last_reward_withdrawalDate);

                    $rewardPercentage = $subscription->hasLicense->reward_amount;

                    $totalLicenseHoldMinutes = $lastRewardEarnedDate->diffInMinutes($getTodaysDate);

                    $totalHoldDays = $totalLicenseHoldMinutes / 1440;

                    $totalRewardInNolu = ($totalHoldDays * $subscription->hasPixel->price) * ($rewardPercentage / 100);

                    $totalRewardInUSDT = $totalRewardInNolu / $getUsdtToNoluConversionRate;

                    $subscription->nolu_reward_amount = $totalRewardInNolu;
                    $subscription->usdt_reward_amount = $totalRewardInUSDT;

                    $subscription->save();
                }
            }
        }
    }

    public function claimAllReward(Request $request)
    {

        //inputs 'user_id'
        $validator = Validator::make($request->all(), [
            'user_id' => 'exists:users,id',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }


        $getAllUserSubscriptions = Subscriptions::where('user_id', $request->user_id)->where('has_expired', 0)->get();
        $totalNoluReward = 0;
        $totalUsdtReward = 0;

        foreach ($getAllUserSubscriptions as $subscription) {
            if ($subscription->license_id != null) {
                if ($subscription->withdrawal_amount_is_paid == 1) {
                    $totalNoluReward += $subscription->nolu_reward_amount;
                    $totalUsdtReward += $subscription->usdt_reward_amount;
                    $subscription->nolu_reward_amount = 0;
                    $subscription->usdt_reward_amount = 0;
                    $subscription->last_reward_withdrawalDate = Carbon::now();
                    $subscription->save();
                } else {
                    continue;
                }
            }
        }

        if ($totalNoluReward != 0) {

            $getNoluPlusInfo = NoluPlusSubscriptoin::with('hasNoluPlusPackage')->where('user_id', $request->user_id)->where('has_expired', 0)->get();

            if (count($getNoluPlusInfo) > 0) {

                $transactionObject = [
                    'type' => 2,
                    'is_reward_claimed' => 1,
                    'reward_claimed_amount' => - ($totalNoluReward + ($totalNoluReward * ($getNoluPlusInfo->hasNoluPlusPackage->discount_percentage / 100))),
                    'nolu_plus_bonus_amount' => $totalNoluReward * ($getNoluPlusInfo->hasNoluPlusPackage->discount_percentage / 100),
                    'user_id' => $request->user_id,
                    'date' => Carbon::now()->addSecond(10)
                ];

                $transaction = Transactions::create($transactionObject);

                $transaction->save();

                if ($transaction) {
                    return $this->sendResponse($transaction, 'Reward Claimed successfully.');
                }
            }

            $transactionObject = [
                'type' => 2,
                'is_reward_claimed' => 1,
                'reward_claimed_amount' => -$totalNoluReward,
                'user_id' => $request->user_id,
                'date' => Carbon::now()->addSecond(10)
            ];

            $transaction = Transactions::create($transactionObject);

            $transaction->save();

            if ($transaction) {
                return $this->sendResponse($transaction, 'Reward Claimed successfully.');
            }
        } else {
            return $this->sendError('No rewards to be claimed.');
        }
    }

    //TODO: this function is yet to be done
    //!This withdraw fee is also called maintanence fee

    public function payWithdrawalFee()
    {
    }
}
