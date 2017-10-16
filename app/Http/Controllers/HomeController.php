<?php

namespace App\Http\Controllers;

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
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        /** check user is in a team */
        if(!auth()->user()->teams->first()){
            /** if user not in a team redirect to create team page */
            return redirect()->to('/create/#/team');
        }
        /** set users current_team_id to first team if not set */
        if (!auth()->user()->current_team_id) {
            auth()->user()->update(['current_team_id' => auth()->user()->teams->first()->id]);
        }
        return view('home');
    }
}
