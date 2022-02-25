<?php

namespace Modules\License\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\GenericResponseController;

class LicenseController extends GenericResponseController
{
    /**
     * *Create License Package
     */
    public function createLicensePackage(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:license_packages,name',
            'short_name' => 'required|unique:license_packages,short_name',
            'code' => 'required|unique:license_packages,code',
            'image' => 'required',
            'price' => 'required',
            'currency' => 'required',
            'expiration_date' => 'required',
            'is_active' => 'required',
            'pixel_id' => 'exists:pixel_packages,id'
        ]);

        //if validator fails return error
        if ($validator->fails()) {
            return $this->sendError('Validation Error', $validator->errors());
        }
        $input = $request->all();

        if (isset($input['image'])) {
            $imageName = time() . '.' . $input['image']->extension();  //creates the image name with extension
            //save image name to user table
            $input['image']->move(public_path('images/license_packages/'), $imageName); //moves the image to the public folder
            $input['image'] = $imageName;
        }

        $licensePackage = LicensePackages::create($input);

        return $this->sendResponse($licensePackage, 'License package created successfully.');
    }


    /**
     * *Update License Package
     */
    public function updateLicensePackage(Request $request, $id)
    {
        $licensePackage = LicensePackages::find($id);

        if (is_null($licensePackage)) {
            return $this->sendError('License Package not found.');
        }

        $input = $request->all();

        if (isset($input['image'])) {
            $imageName = time() . '.' . $input['image']->extension();  //creates the image name with extension
            //save image name to user table
            $input['image']->move(public_path('images/license_packages/'), $imageName); //moves the image to the public folder
            $input['image'] = $imageName;
        }

        $licensePackage->update($input);

        return $this->sendResponse($licensePackage, 'License package updated successfully.');
    }


    /**
     * *Get License Package
     */
    public function getLicensePackages()
    {
        $licensePackages = LicensePackages::all();
        return $this->sendResponse($licensePackages, 'License Packages retrieved successfully.');
    }
}
