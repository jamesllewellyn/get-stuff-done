<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Auth;
use Hash;
use App\PendingUser;
use App\UserTeam;
use App\Team;


class SignUpController extends Controller
{
    public function __construct() {
        /** define controller middleware */
//        $this->middleware('auth:api', ['except' => ['isUser', 'store', 'validation', 'pendingUser', 'invite']]);
    }

    /**
     * Check if user exists via email
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function isUser(Request $request) {
        /** validate email*/
        $this->validate(Request(),['email' => 'required|email'], ['email.required' => 'Please provide an email address', 'email.email' => 'That doest email address doesn\'t look quiet right ']);
        /** try and find user  */
        $user = User::where('email', $request->get('email'))->first();
        /** is no user return error  */
        if(!$user){
            return response()->json(['success' => false, 'message' => 'User could not be found']);
        }
        /** return success message */
        return response()->json(['success' => true, 'user' => $user]);
    }

    /**
     * Validate new user
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function validation(Request $request) {
        $user =  new User();
        /** validate the request data */
        $this->validate(Request(),$user->validation, $user->messages);
        /** return new user */
        return response()->json(['success' => true]);
    }

    /**
     * Create user and Team
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function signUp(Request $request) {
//        dd($request->all());
        $user =  new User();
        /** validate the request data */
//        $this->validate($request,$user->validation, $user->messages);
        /** create new user */
        $user->first_name = $request->user['first_name'];
        $user->last_name = $request->user['last_name'];
        $user->email = $request->user['email'];
        $user->handle = $request->user['handle'];
        $user->password = Hash::make($request->user['password']);
        $user->save();
        /** login user */
        Auth::login($user, true);
        $team =  new Team();
        /** validate the request data */
//        $this->validate($request->team,$team->validation, $team->messages);
        /** create team */
        $team->name = $request->team['name'];
        $team->save();
        /** add user to team */
        $userTeam = UserTeam::create(['user_id' => $user->id, 'team_id' => $team->id]);
        /** check team has been created */
        if(!$userTeam){
            return response()->json(['success' => false, 'message' => 'User could not be added to team']);
        }
        return response()->json(['success' => true, 'team' => $team]);
    }

    public function invite(Request $request){
        $user = new User();
        /** validate the request data */
        $validator = Validator::make($request->all(),[
            'first_name' => 'required',
            'last_name' => 'required',
            'handle' => 'required',
            'password' => 'required|min:7'
        ],$user->messages);
        /** if validation errors return customer to login page with error */
        if($validator->fails()) {
            return redirect()->back()->withErrors($validator)
                ->withInput();
        }
        /** get pending user session data */
        $pending = $request->session()->get('pending');
        /** delete session */
        $request->session()->forget('pending');

        if(!$pending){
            return redirect()->route('home')->with('inviteError','Sorry we couldn\'t find your invitation');
        }
        /** create new user */
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $pending['email'];
        $user->handle = $request->handle;
        $user->password = Hash::make($request->password);
        $user->save();
        /** log user in */
        Auth::login($user, true);
        /** add user to team */
        UserTeam::create(['user_id' => $user->id, 'team_id' => $pending['team_id']]);
        /** remove pending user record */
        PendingUser::where('id', $pending['id'])->delete();
        /** redirect to app home page */
        return redirect()->route('home');
    }
}
