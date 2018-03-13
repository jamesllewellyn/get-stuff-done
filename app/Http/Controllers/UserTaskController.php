<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class UserTaskController extends Controller
{
    public function __construct()
    {
        /** define controller middleware */
        $this->middleware('auth:api');
    }

    /**
     * Get all users tasks
     * @param  \Illuminate\Http\Request $request
     * @param User $user
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, User $user)
    {
        /** authorize user */
        $this->authorize('access-user', $user);

        if ($request->filter) {
            return $this->filterTasks($user, $request->filter);
        }

        /** get users tasks */
        $tasks = $user->tasks()->with('section', 'section.project', 'section.project.team');

        /** return success message */
        return response()->json($tasks->get());
    }

    /**
     * Filter users tasks
     *
     * @param  User $user
     * @param  $filter
     * @return \Illuminate\Http\Response
     */
    private function filterTasks(User $user, $filter)
    {
        if (!$filter) {
            return null;
        }

        if ($filter === 'working-on-it') {
            return response()->json($user->workingOnIt()->with('section', 'section.project', 'section.project.team')->get());
        }

        if ($filter === 'over-due') {
            return response()->json($user->overDue()->with('section', 'section.project', 'section.project.team')->get());
        }

        /** return all tasks unfiltered */
        return response()->json($user->tasks()->with('section', 'section.project', 'section.project.team'));
    }
}
