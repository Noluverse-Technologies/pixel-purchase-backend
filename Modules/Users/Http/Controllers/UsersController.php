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
}
