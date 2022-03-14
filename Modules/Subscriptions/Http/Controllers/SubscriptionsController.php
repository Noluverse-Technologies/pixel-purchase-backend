<?php

namespace Modules\Subscriptions\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\GenericResponseController;
use Modules\Subscriptions\Entities\Subscriptions;
use Modules\Subscriptions\Entities\SubscriptionType;
use Illuminate\Support\Carbon;
use Modules\License\Entities\LicensePackages;
use Modules\Payment\Entities\Transactions;
use Modules\Pixels\Entities\PixelPackages;

class SubscriptionsController extends GenericResponseController
{


    //create subscription type 
    public function createSubscriptionType(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:subscription_types,name',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $subscriptionType = SubscriptionType::create($request->all());

        return $this->sendResponse($subscriptionType, 'Subscription type created successfully.');
    }


    //*update subscription type
    public function updateSubscriptionType(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:subscription_types',
            'name' => 'required|unique:subscription_types,name,' . $request->id,
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $subscriptionType = SubscriptionType::find($request->id);
        $subscriptionType->update($request->all());

        return $this->sendResponse($subscriptionType, 'Subscription type updated successfully.');
    }


    //*get all subscription types
    public function getAllSubscriptionType()
    {
        $subscriptionTypes = SubscriptionType::all();

        return $this->sendResponse($subscriptionTypes, 'Subscription types retrieved successfully.');
    }


    function getSubscriptionByUser($id)
    {
        $subscription = Subscriptions::with('hasPixel')->where('user_id', $id)->paginate(10);

        return $this->sendResponse($subscription, 'Subscription retrieved successfully.');
    }

    function getAllUserSubscriptionById($id)
    {
        $subscription = Subscriptions::with('hasPixel')->where('user_id', $id)->get();

        return $this->sendResponse($subscription, 'Subscription retrieved successfully.');
    }

    /**
     * create user subscription
     */
    function createSubscription(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'pixel_id' => 'required|exists:pixel_packages,id',
            'user_id' => 'required|exists:users,id',
            'subscription_type' => 'required|exists:users,user_type',
            'license_id' => 'exists:license_packages,id',
            'withdrawal_amount_is_paid' => 'required',
        ]);




        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $input = $request->all();

        if (isset($input["pixel_id"])) {
            $input["pixel_purchase_date"] = Carbon::now()->addSeconds(10);
        }

        //license expiration date calculation
        if (isset($input["license_id"])) {
            $getTheLicense = LicensePackages::where('id', $input["license_id"])->first();
            $licenseDuration = $getTheLicense->duration_in_days;
            $input["license_purchase_date"] = Carbon::now()->addSecond(10);
            $input["license_duration"] = $licenseDuration;

            $input["license_expiration_date"] = Carbon::parse($input["license_purchase_date"])->addDays($licenseDuration);
        }

        $subscription = Subscriptions::create($input);

        $getCreatedSubscription = Subscriptions::find($subscription->id);


        if ($getCreatedSubscription->pixel_id) {
            if ($getCreatedSubscription->license_id) {
                /**
                 * !THIS DATE COMPARISON IS NOT WORKING
                 */
                if ($getCreatedSubscription->pixel_purschase_date == $getCreatedSubscription->license_purcahse_date) {

                    //both pixel and license is purchased at the same time

                    $licenseAmount = LicensePackages::where('id', $getCreatedSubscription->license_id)->first();

                    $getPixelAmount = PixelPackages::where('id', $getCreatedSubscription->pixel_id)->first();

                    $transactionObject = [
                        'type' => 1,
                        'is_pixel_purchased' => 1,
                        'is_license_purchased' => 1,
                        'is_withdrawal_amount_paid' => 0,
                        'is_reward_claimed' => 0,
                        'pixel_amount' => $getPixelAmount->price,
                        'license_amount' => $licenseAmount->price,
                        'user_id' => $getCreatedSubscription->user_id,
                        'date' => Carbon::now()->addSecond(10)
                    ];

                    $saveTransaction = Transactions::create($transactionObject);
                } else {
                    //only license is purchased

                    //get the license amount
                    $licenseAmount = LicensePackages::where('id', $getCreatedSubscription->license_id)->first();


                    $transactionObject = [
                        'type' => 1,
                        'is_pixel_purchased' => 0,
                        'is_license_purchased' => 1,
                        'is_withdrawal_amount_paid' => 0,
                        'is_reward_claimed' => 0,
                        'license_amount' => $licenseAmount->price,
                        'user_id' => $getCreatedSubscription->user_id,
                        'date' => Carbon::now()->addSecond(10)
                    ];

                    $saveTransaction = Transactions::create($transactionObject);
                }
            } else {
                //only pixel purchased
                $getPixelAmount = PixelPackages::where('id', $getCreatedSubscription->pixel_id)->first();

                // dd($getPixelAmount->price);
                $transactionObject = [
                    'type' => 1,
                    'is_pixel_purchased' => 1,
                    'is_license_purchased' => 0,
                    'is_withdrawal_amount_paid' => 0,
                    'is_reward_claimed' => 0,
                    'pixel_amount' => $getPixelAmount->price,
                    'user_id' => $getCreatedSubscription->user_id,
                    'date' => Carbon::now()->addSecond(10)
                ];

                $saveTransaction = Transactions::create($transactionObject);
            }
        }

        return $this->sendResponse($subscription, 'Subscription type created successfully.');
    }


    /**
     * update user subscription
     */
    function updateSubscription(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:subscriptions',
            'license_id' => 'exists:license_packages,id',
        ]);


        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $input = $request->all();

        if (isset($input["license_id"])) {
            //this extra seconds added because it will take time to complete the subscribe process. So once the subscription process is finished then we want to start the license time
            $input["license_purchase_date"] = Carbon::now()->addSecond(10);
        }

        $subscription = Subscriptions::find($request->id);

        $subscription->update($input);




        $getCreatedSubscription = Subscriptions::find($subscription->id);


        if ($getCreatedSubscription->pixel_id) {
            if ($getCreatedSubscription->license_id) {
                /**
                 * !THIS DATE COMPARISON IS NOT WORKING
                 */
                if ($getCreatedSubscription->pixel_purschase_date->eq($getCreatedSubscription->license_purcahse_date)) {
                    dd("both sme");
                    //both pixel and license is purchased at the same time

                    $licenseAmount = LicensePackages::where('id', $getCreatedSubscription->license_id)->first();

                    $getPixelAmount = PixelPackages::where('id', $getCreatedSubscription->pixel_id)->first();

                    $transactionObject = [
                        'type' => 1,
                        'is_pixel_purchased' => 1,
                        'is_license_purchased' => 1,
                        'is_withdrawal_amount_paid' => 0,
                        'is_reward_claimed' => 0,
                        'pixel_amount' => $getPixelAmount->price,
                        'license_amount' => $licenseAmount->price,
                        'user_id' => $getCreatedSubscription->user_id,
                        'date' => Carbon::now()->addSecond(10)
                    ];

                    $saveTransaction = Transactions::create($transactionObject);
                } else {
                    //only license is purchased

                    //get the license amount
                    $licenseAmount = LicensePackages::where('id', $getCreatedSubscription->license_id)->first();


                    $transactionObject = [
                        'type' => 1,
                        'is_pixel_purchased' => 0,
                        'is_license_purchased' => 1,
                        'is_withdrawal_amount_paid' => 0,
                        'is_reward_claimed' => 0,
                        'license_amount' => $licenseAmount->price,
                        'user_id' => $getCreatedSubscription->user_id,
                        'date' => Carbon::now()->addSecond(10)
                    ];

                    $saveTransaction = Transactions::create($transactionObject);
                }
            } else {
                //only pixel purchased
                $getPixelAmount = PixelPackages::where('id', $getCreatedSubscription->pixel_id)->first();

                // dd($getPixelAmount->price);
                $transactionObject = [
                    'type' => 1,
                    'is_pixel_purchased' => 1,
                    'is_license_purchased' => 0,
                    'is_withdrawal_amount_paid' => 0,
                    'is_reward_claimed' => 0,
                    'pixel_amount' => $getPixelAmount->price,
                    'user_id' => $getCreatedSubscription->user_id,
                    'date' => Carbon::now()->addSecond(10)
                ];

                $saveTransaction = Transactions::create($transactionObject);
            }
        }


        return $this->sendResponse($subscription, 'Subscription type updated successfully.');
    }

    /**
     * get all subscriptions
     */
    function getAllSubscriptions()
    {
        $subscriptions = Subscriptions::paginate(10);

        return $this->sendResponse($subscriptions, 'Subscriptions retrieved successfully.');
    }


    /**
     * Expire all subscriptions where licence date is greater than or equal to license duration in days
     */
    function expireAllExpiredSubscriptions()
    {
        //get all subscriptions where license id is not null and license purchase date is greater than or equal to license duration in days
        $subscriptions = Subscriptions::where('license_id', '!=', null)->where('license_expiration_date', '>=', Carbon::now())->get();

        //expire all subscriptions
        foreach ($subscriptions as $subscription) {
            $subscription->has_expired = 1;
            $subscription->save();
        }
    }

    //expire all subscriptions by user id
    function expireAllExpiredSubscriptionsByUser(Request $request)
    {
        //get all subscriptions where license id is not null and license purchase date is greater than or equal to license duration in days
        $subscriptions = Subscriptions::where('license_id', '!=', null)->where('user_id', $request->user_id)->where('license_expiration_date', '>=', Carbon::now())->get();

        //expire all subscriptions
        foreach ($subscriptions as $subscription) {
            $subscription->has_expired = 1;
            $subscription->save();
        }
    }
}
