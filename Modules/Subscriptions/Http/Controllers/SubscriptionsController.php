<?php

namespace Modules\Subscriptions\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\GenericResponseController;
use Modules\Subscriptions\Entities\SubscriptionType;

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


    function getSubscriptionByUser($id)
    {
        dd($id);
    }

    /**
     * create user subscription
     */
    function createSubscription(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'pixel_id' => 'required|unique:pixel_packages,name',
            'user_id' => 'required|unique:pixel_packages,short_name',
            'license_id' => 'unique:license_packages,code',
            'pixel_purchase_date' => 'required',
            'withdrawal_amount_is_paid' => 'required',
            'has_expired' => 'required'
        ]);
    }

    /**
     * get all subscriptions
     */
    function getAllSubscriptions()
    {
        dd("all");
    }
}
