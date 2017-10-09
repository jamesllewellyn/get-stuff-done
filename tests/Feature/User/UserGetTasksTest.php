<?php

namespace Tests\Feaure\Team;

use App\UserTask;
use App\UserTeam;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Passport\Passport;
use App\Team;
use App\User;
use App\Project;
use App\Task;
use App\Section;
use Auth;
use Carbon\Carbon;

class UserGetTasksTest extends TestCase
{
    use DatabaseTransactions;
    protected $team;
    protected $project;
    protected $user;

    protected function setUp()
    {
        parent::setUp();
        /** create and act as user */
        $this->user = factory(User::class)->create();
        Passport::actingAs(
            $this->user
        );
        /** set up new team */
        $this->team = factory(Team::class)->create();
        /** add user to team */
        factory(UserTeam::class)->create([
            'user_id' => Auth::user()->id,
            'team_id' => $this->team->id,
        ]);
        /** create new project and add to team */
        $this->project = factory(Project::class)->create([
            'team_id' => $this->team->id
        ]);
        /** create new project sections for project */
        $this->project->section = factory(Section::class)->create([
            'project_id' => $this->project->id
        ]);
        /**  add tasks to section */
        $this->project->section->tasks = factory(Task::class, 3)->create([
            'section_id' => $this->project->section->id,
            /** todo: increment sort order */
            'sort_order' => 1
        ]);
        /** assign tasks in project to logged in user */
        foreach ($this->project->section->tasks->toArray() as $task){
            factory(UserTask::class)->create([
                'task_id' => $task['id'],
                'user_id' => $this->user->id
            ]);
        }
    }

    /**
     * Tests route user.tasks
     *
     * @test
     */
    public function can_get_users_tasks()
    {
        /** Act */
        $response = $this->json('GET', "/api/user/".$this->user->id."/tasks");
        /** Assert response is correct */
        $response->assertStatus(200);
        /** decode returned tasks */
        $tasks = $response->decodeResponseJson();
        /** loop each task and assert they match added tasks */
        foreach ($tasks as $key => $task){
            $this->assertEquals($this->project->section->id, $task['section']['id']);
            $this->assertEquals($this->project->id, $task['section']['project']['id']);
            $this->assertEquals($this->project->section->tasks[$key]->name, $task['name']);
            $this->assertEquals($this->project->section->tasks[$key]->note, $task['note']);
            $this->assertEquals($this->project->section->tasks[$key]->due_date, $task['due_date']);
            $this->assertEquals($this->project->section->tasks[$key]->priority_id, $task['priority_id']);
            $this->assertEquals($this->project->section->tasks[$key]->status_id, $task['status_id']);
            $this->assertEquals($this->project->section->tasks[$key]->created_by_id, $task['created_by_id']);
        }
    }

    /**
     * Tests route user.tasks
     *
     * @test
     */
    public function cannot_get_another_users_tasks(){
        /** Arrange */
        /** Create New User and act as them */
        $user = factory(User::class)->create();
        Passport::actingAs(
            $user
        );
        /** Act */
        /** Attempt to get first users tasks*/
        $response = $this->json('GET', "/api/user/".$this->user->id."/tasks");
        /** Assert response is Forbidden */
        $response->assertStatus(403);
    }
}
