<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\PendingUser;
use Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['invite']]);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        /** get logged in user */
        $user = Auth::user();
        /** check user is in a team */
        if(!$user->teams->first()){
            /** if user not in a team redirect to create team page */
            return redirect()->to('/create/#/team');
        }
        /** set users current_team_id if not set */
        if (!$user->current_team_id) {
            $user->current_team_id = $user->teams->first()->id;
            $user->save();
        }
        return view('home');
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function invite(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'token' => 'required'
        ]);
        /** if validation errors return customer to login page with error */
        if($validator->fails()) {
            return redirect()->route('home')->with('inviteError','Sorry we couldn\'t find your invitation');
        }
        $params = [];
        $token = $request->get('token');
        parse_str(base64_decode($token), $params);
        $pending = PendingUser::where(['email' => $params['email'], 'token' => $params['token']])->first();
        /**  set a session variable with the pending data */
        $request->session()->put('pending',$pending->toArray());
        return view('auth.invite');
    }

}
