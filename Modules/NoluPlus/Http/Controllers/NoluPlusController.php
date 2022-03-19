<?php

namespace Modules\NoluPlus\Http\Controllers;

use App\Http\Controllers\GenericResponseController;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\NoluPlus\Entities\NoluPlusPackage;
use Modules\Subscriptions\Entities\NoluPlusSubscriptoin;

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

    public function getNoluPlusPackages(Request $request)
    {
        $packages = NoluPlusPackage::where('id', $request->id)->get();

        return $this->sendResponse($packages, 'Nolu plus packages retrieved successfully.');
    }


    public function deleteNoluPlusPackageById(Request $request)
    {
        $noluPlusPackage = NoluPlusPackage::find($request->id);
        $getAllNoluPlusSubscriptions = NoluPlusSubscriptoin::where('nolu_plus_package_id', $request->id)->get();


        if (is_null($noluPlusPackage)) {
            return $this->sendError('Nolu plus package not found.');
        }

        if (count($getAllNoluPlusSubscriptions) == 0) {

            $noluPlusPackage->delete();
        } else {
            return $this->sendError('There are Ongoing subscriptions under this package.');
        }

        return $this->sendResponse($noluPlusPackage, 'Nolu plus package deleted successfully.');
    }
}
