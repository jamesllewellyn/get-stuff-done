<?php

namespace App\Http\Controllers;

use App\Team;
use Auth;

class TeamController extends Controller
{
    public function __construct() {
        /** define controller middleware */
        $this->middleware('auth:api');
    }

    /**
     * Store a new Team
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $team =  new Team();
        /** validate the request data */
        $this->validate(request(),$team->validation, $team->messages);
        /** create team */
        $team = auth()->user()->teams()->create(['name' => request('name')]);
        /** return response */
        return response()->json(['success' => true, 'team' => $team]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Team  $team
     * @return \Illuminate\Http\Response
     */
    public function update(Team $team)
    {
        /** authorize user has access to project */
        $this->authorize('access-team', $team);
        /** validate the request data */
        $this->validate(request(), $team->validation, $team->messages);
        /** update team */
        $team->update(['name' => request('name')]);
        /** return response */
        return response()->json(['success' => true, 'team' => $team]);
    }

    /**
     * Get all team projects.
     *
     * @param  \App\Team $team
     * @return \Illuminate\Http\Response
     */
    public function getProjects(Team $team)
    {
        /** authorize user has access to project */
        $this->authorize('access-team', $team);
        /** get all teams projects */
        $projects = $team->projects()->get();
        /** return response */
        return response()->json($projects);
    }

    /**
     * Get overview of projects data
     *
     * @param  Team  $team
     * @return \Illuminate\Http\Response
     */
    public function getOverview(Team $team)
    {
        /** return response */
        return response()->json(['success' => true, 'overview' => $team->getOverview()]);
    }
}
