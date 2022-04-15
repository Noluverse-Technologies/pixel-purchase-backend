<?php

namespace Modules\Overview\Http\Controllers;

use App\Http\Controllers\GenericResponseController;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Overview\Entities\Events;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Carbon;
use Modules\Payment\Entities\Transactions;
use Modules\Subscriptions\Entities\Subscriptions;

class OverviewController extends GenericResponseController
{
    public function createEvents(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'date_from' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $Events = Events::create($request->all());

        return $this->sendResponse($Events, 'Events created successfully.');
    }

    public function getEvents()
    {
        $events = collect(Events::all())->sortBy("id")->reverse()->values();

        return $this->sendResponse($events, 'Events retrieved successfully.');
    }

    public function deleteEvents(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $events = Events::find($request->id);

        if (is_null($events)) {
            return $this->sendError('Events not found.');
        }

        $events->delete();

        return $this->sendResponse($events, 'Events deleted successfully.');
    }


    //================== Overview API Calculations ========================//

    public function OverviewCalculations(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $getMonthWiseNoluReward = [];
        $getMonthWiseUSDTReward = [];


        $getTotalNoluRewards = Transactions::where('user_id', $request->user_id)->where('type', 2)->sum('reward_claimed_amount');

        $getTotalUSDTRewards = $getTotalNoluRewards * config('app.oneNoluToUSDT');

        $getTotalPixels = Subscriptions::where('user_id', $request->user_id)->where('pixel_id', '<>', '')->get()->count();

        $getTransactions = Transactions::where('user_id', $request->user_id)->where('is_reward_claimed', 1)->get()
            ->groupBy(function ($date) {

                return Carbon::parse($date->created_at)->format('m'); // 
            });



        //Total Monthly Rewards in Nolu
        foreach ($getTransactions->toArray() as $key => $value) {


            array_push($getMonthWiseNoluReward, [$key, $getTransactions[$key]->sum('reward_claimed_amount')]);
        }

        //Total Monthly Rewards in USDT
        foreach ($getTransactions->toArray() as $key => $value) {


            array_push($getMonthWiseUSDTReward, [$key, $getTransactions[$key]->sum('reward_claimed_amount') * config('app.oneNoluToUSDT')]);
        }

        $overviewData = ["totalRewardsInNolu" => $getTotalNoluRewards, "totalRewardsInUSDT" => $getTotalUSDTRewards, "totalPixels" => $getTotalPixels, "totalMonthlyRewardsInNolu" => $getMonthWiseNoluReward, "totalMonthlyRewardsInUSDT" => $getMonthWiseUSDTReward];

        return $overviewData;
    }
}
