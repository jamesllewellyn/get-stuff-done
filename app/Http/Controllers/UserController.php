<?php

namespace App\Http\Controllers;

use App\Team;
use Illuminate\Http\Request;
use App\User;
use Hash;
use Auth;

class UserController extends Controller
{
    public function __construct() {
        /** define controller middleware */
        $this->middleware('auth:api');
    }

    /**
     * get logged in user data
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        /** return current logged in user */
        return response()->json($request->user());
    }

    /**
     * Update project
     * @param \Illuminate\Http\Request $request
     * @param User $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user) {
        /** authorize user */
        $this->authorize('access-user', $user);
       /** validate the request data */
        $this->validate(Request(),$user->updateValidation, $user->messages);
        /** update record */
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->handle = $request->handle;
        $user->save();
        /** return success and updated project */
        return response()->json(['success' => true, 'message' => 'user has been updated', 'user' => $user]);
    }

    /**
     * Delete user
     * @param User $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user) {
        /** authorize user */
        $this->authorize('access-user', $user);
        /** delete user */
        $user->delete();
        /** return success message */
        return response()->json(['success' => true, 'message' => 'User '.$user->getFullName().' has been successfully deleted']);
    }
}
