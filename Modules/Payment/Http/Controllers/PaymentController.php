<?php

namespace Modules\Payment\Http\Controllers;

use App\Http\Controllers\GenericResponseController;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Payment\Entities\Transactions;
use Illuminate\Support\Facades\Validator;

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
    }
}
