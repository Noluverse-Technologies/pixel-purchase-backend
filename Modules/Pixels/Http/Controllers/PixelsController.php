<?php

namespace Modules\Pixels\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Pixels\Entities\PixelPackages;

use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\GenericResponseController;

class PixelsController extends GenericResponseController
{

    /**
     * Create a new pixel package
     */
    public function createPixelPackage(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:pixel_packages,name',
            'short_name' => 'required|unique:pixel_packages,short_name',
            'code' => 'required|unique:pixel_packages,code',
            'image' => 'required',
            'price' => 'required',
            'currency' => 'required',
            'expiration_date' => 'required',
            'is_active' => 'required'
        ]);

        //if validator failes return error
        if ($validator->fails()) {
            return $this->sendError('Validation Error', $validator->errors());
        }
        $input = $request->all();

        if (isset($input['image'])) {
            $imageName = time() . '.' . $input['image']->extension();  //creates the image name with extension
            //save image name to user table
            $input['image']->move(public_path('images/pixel_packages/'), $imageName); //moves the image to the public folder
            $input['image'] = $imageName;
        }
        //write an update functionality for the pixel packages
        $pixelPackage = PixelPackages::create($input);

        return $this->sendResponse($pixelPackage, 'Pixel package created successfully.');
    }

    /**
     * Update a pixel package
     */
    public function updatePixelPackage(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:pixel_packages',
            'short_name' => 'unique:pixel_packages,short_name,' . $request->id,
            'code' => 'unique:pixel_packages,code,' . $request->id,
            'image' => 'image|mimes:jpg,png,jpeg,gif,svg|max:2048'
        ]);

        //if validator failes return error
        if ($validator->fails()) {

            return $this->sendError('Validation Error', $validator->errors());
        }
        $input = $request->all();

        if (isset($input['image'])) {
            $imageName = time() . '.' . $input['image']->extension();  //creates the image name with extension
            //save image name to user table
            $input['image']->move(public_path('images/pixel_packages/'), $imageName); //moves the image to the public folder
            $input['image'] = $imageName;
        }
        //write an update functionality for the pixel packages
        $pixelPackage = PixelPackages::find($input['id']);
        $pixelPackage->update($input);

        return $this->sendResponse($pixelPackage, 'Pixel package updated successfully.');
    }
}
