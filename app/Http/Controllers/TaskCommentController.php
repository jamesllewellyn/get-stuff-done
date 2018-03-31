<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Project;
use App\Section;
use App\Task;
use App\Team;
use Illuminate\Http\Request;

class TaskCommentController extends Controller
{

    public function __construct() {
        /** define controller middleware */
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Add new comment to task.
     *
     * @param \Illuminate\Http\Request $request
     * @param Team $team
     * @param Project $project
     * @param Section $section
     * @param Task $task
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Team $team, Project $project, Section $section, Task $task)
    {
        /** authorize user has access to task */
        $this->authorize('access-task', [$team, $project, $section, $task]);
        $comment = new Comment();
        /** validate the request data */
        $this->validate(Request(), $comment->validation, $comment->messages);

        $comment = $task->comments()->create([
            'user_id' => auth()->user()->id,
            'comment' => $request->get('comment')
        ]);

        /** return success and requested task */
        return response()->json(['success' => true, 'message' => 'comment has been added to task', 'comment' => $comment->where('comments.id', $comment->id)->with('author')->first()]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
