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
        $transactions = Transactions::where('user_id', $user_id)->get();


        return $this->sendResponse($transactions, 'User Transaction retrieved successfully.');
    }
}
