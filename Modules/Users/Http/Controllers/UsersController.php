<?php

namespace Modules\Users\Http\Controllers;

use Illuminate\Http\Request;

use Modules\Users\Entities\User;
use Auth;
use Validator;
use Illuminate\Routing\Controller;
use Modules\Users\Http\Handlers\UsersHandler;

class UsersController extends Controller
{
    

    /**
     * User login functionality.
     * 
     */
    public function login(Request $request)
    {
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){ 
            
            $user = Auth::user(); 
            $success['token'] =  $user->createToken('Nolu')-> accessToken; 
            $success['name'] =  $user->name;
   
            return $success['token'];
        } 
        else{ 
            return "failure";
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
            'email' => 'required|email',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);

        
        if($validator->fails()){
            // return $this->sendError('Validation Error.', $validator->errors());
            dd("validation error");       
        }
   
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $success['token'] =  $user->createToken('MyApp')->accessToken;
        $success['name'] =  $user->name;
   
        dd("usr registered");     
    }

    
}
