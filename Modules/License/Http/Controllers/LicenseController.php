<?php

namespace Modules\License\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\GenericResponseController;
use Modules\License\Entities\LicensePackages;

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
     * Update License Package
     */
    public function updateLicensePackage(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:license_packages',
            'short_name' => 'unique:license_packages,short_name,' . $request->id,
            'code' => 'unique:license_packages,code,' . $request->id,
            'image' => 'image|mimes:jpg,png,jpeg,gif,svg|max:2048',
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

        $licensePackage = LicensePackages::find($request->id);
        $licensePackage->update($input);

        return $this->sendResponse($licensePackage, 'License package updated successfully.');
    }


    /**
     * *Get License Package
     */
    public function getLicensePackages()
    {
        $licensePackages = LicensePackages::with('hasPixel')->get();
        return $this->sendResponse($licensePackages, 'License Packages retrieved successfully.');
    }
}
