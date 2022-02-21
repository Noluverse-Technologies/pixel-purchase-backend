<?php

namespace Modules\Users\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Users\Entities\User;
use Validator;
use App\Http\Controllers\GenericResponseController;
use Modules\Users\Entities\Roles;

class UsersController extends GenericResponseController
{

    /**
     * Get all the user roles
     */

    public function getUserRoles()
    {
        $roles = Roles::all();
        return $this->sendResponse($roles, 'User roles retrieved successfully.');
    }

    /**
     * Create user roles
     */
    public function createUserRoles(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required'
        ]);


        if ($validator->fails()) {
            return $this->sendError('Validation Error', $validator->errors());
        }

        $input = $request->all();

        $role = Roles::create($input);

        return $this->sendResponse($role, 'Role Created successfully.');
    }

    /**
     * Update user roles
     */
    public function updateUserRoles(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:roles,name,' . $request->id
        ]);

        //if validator failes return error
        if ($validator->fails()) {
            return $this->sendError('Validation Error', $validator->errors());
        }

        //write an update functionality for the roles field
        $role = Roles::find($request->id);

        if (!$role) {
            return $this->sendError('Role not found.');
        }

        $role->name = $request->name;
        $update = $role->save();
        return $this->sendResponse('', 'Role updated successfully.');
    }


    /**
     * Delete user roles
     */

    public function deleteUserRoles(Request $request)
    {
        $role = Roles::find($request->id);

        if (!$role) {
            return $this->sendError('Role not found.');
        }

        $role->delete();

        return $this->sendResponse('', 'Role deleted successfully.');
    }



    /**
     * Update user info
     */
    public function updateCurrentUser(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:users',
            'wallet_address' => 'unique:users',
            'image' => 'image|mimes:jpg,png,jpeg,gif,svg|max:2048'
        ]);


        if ($validator->fails()) {
            return $this->sendError('Validation Error', $validator->errors());
        }

        $input = $request->all();

        //if validator failes return error
        if ($validator->fails()) {
            return $this->sendError('Validation Error', $validator->errors());
        }

        //write an update functionality for the roles field
        $user = User::find($request->id);

        if (!$user) {
            return $this->sendError('User not found.');
        }

        if (isset($input['firstname'])) {
            $user->firstname = $input['firstname'];
        }

        if (isset($input['lastname'])) {
            $user->lastname = $input['lastname'];
        }

        if (isset($input['wallet_address'])) {
            $user->wallet_address = $input['wallet_address'];
        }

        if (isset($input['password'])) {
            $user->password = bcrypt($input['password']);
        }

        if (isset($input['image'])) {
            $imageName = time() . '.' . $input['image']->extension();  //creates the image name with extension
            //save image name to user table
            $input['image']->move(public_path('images/' . $input['firstname']), $imageName); //moves the image to the public folder
            $input['image'] = $imageName;
        }


        $update = $user->update();

        if ($update) {
            return $this->sendResponse('', 'User updated successfully.');
        } else {
            return $this->sendError('User not updated.');
        }
    }
}
