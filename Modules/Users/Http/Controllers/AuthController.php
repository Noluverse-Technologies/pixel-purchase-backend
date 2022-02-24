<?php

namespace Modules\Users\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Users\Entities\User;

use Illuminate\Support\Facades\Auth;

//use validator facade
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\GenericResponseController;


class AuthController extends GenericResponseController
{

    /**
     * User login functionality.
     * 
     */
    public function login(Request $request)
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {

            $user = Auth::user();

            $success['token'] =  $user->createToken('Nolu')->accessToken;
            $success['name'] =  $user->name;

            return $this->sendResponse($success, 'User logged in successfully.');
        } else {
            return $this->sendError('Unauthorised.', ['error' => 'Unauthorised']);
        }
    }

    /**
     * User register functionality.
     * 
     */
    public function register(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'firstname' => 'required',
            'lastname' => 'required',
            'email' => 'required|email|unique:users',
            'wallet_address' => 'unique:users',
            'password' => 'required',
            'image' => 'image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            'role' => 'required',
            'c_password' => 'required|same:password',
        ]);


        if ($validator->fails()) {
            return $this->sendError('Validation Error', $validator->errors());
        }

        $input = $request->all();


        //hashes the password
        $input['password'] = bcrypt($input['password']);

        if (isset($input['image'])) {
            $imageName = time() . '.' . $input['image']->extension();  //creates the image name with extension
            //save image name to user table
            $input['image']->move(public_path('images/user_profile_pic/'), $imageName); //moves the image to the public folder
            $input['image'] = $imageName;
        }

        $user = User::create($input);

        // $success['token'] =  $user->createToken('Nolu')->accessToken;
        $success['name'] =  $user->name;

        return $this->sendResponse($success, 'User registered successfully.');
    }


    /**
     * logout functionaity
     */

    public function logout(Request $request)
    {
        dd($request->user()->token());
        $request->user()->token()->revoke();
        return $this->sendResponse('', 'User logged out successfully.');
    }
}
