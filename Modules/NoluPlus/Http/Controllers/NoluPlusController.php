<?php

namespace Modules\NoluPlus\Http\Controllers;

use App\Http\Controllers\GenericResponseController;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\NoluPlus\Entities\NoluPlusPackage;

class NoluPlusController extends GenericResponseController
{
    public function createPackage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'duration_in_days' => 'required',
            'discount_percentage' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $input = $request->all();

        $noluPlusPackage = NoluPlusPackage::create($input);

        return $this->sendResponse($noluPlusPackage, 'Nolu plus package created successfully.');
    }
}
