<?php

namespace Modules\Users\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Users\Entities\User;

use Auth;
use Validator;
use App\Http\Controllers\GenericResponseController;
use Illuminate\Routing\Controller;



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
            'name' => 'required',
            'email' => 'required|unique:users',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);


        if ($validator->fails()) {
            return $this->sendError('Validation Error', $validator->errors());
        }

        $input = $request->all();

        //hashes the password
        $input['password'] = bcrypt($input['password']);

        $user = User::create($input);


        $success['token'] =  $user->createToken('MyApp')->accessToken;
        $success['name'] =  $user->name;

        return $this->sendResponse($success, 'User registered successfully.');
    }
}
