<?php

namespace Tests\Feaure\User;

use App\UserTask;
use App\UserTeam;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Passport\Passport;
use App\Team;
use App\User;
use App\Project;
use App\Task;
use App\Section;
use Auth;

class UserGetOverDueTasksTest extends TestCase
{
    use DatabaseTransactions;
    protected $team;
    protected $project;
    protected $user;
    protected $tasks;

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
        /** create new section for project */
        $this->project->section = factory(Section::class)->create([
            'project_id' => $this->project->id
        ]);
        /** add 3 tasks with due_date in the future */
        $tasks = factory(Task::class, 3)->create([
            'section_id' => $this->project->section->id,
            'sort_order' => 1,
            'due_date' => Carbon::tomorrow('America/Chicago')->format('Y-m-d')
        ]);
        /** assign tasks to user */
        foreach ($tasks as $task){
          factory(UserTask::class)->create([
              'task_id' => $task->id,
              'user_id' => $this->user->id
          ]);
        }
    }

    /**
     * Tests route user.tasks
     *
     * @test
     */
    public function can_get_users_over_due_tasks()
    {
        /** Arrange **/
        /** create 3 tasks with due_date in the past **/
         $overDateTasks= factory(Task::class, 3)->create([
            'section_id' => $this->project->section->id,
            'sort_order' => 1,
            'status_id' => null,
            'due_date' => Carbon::yesterday('America/Chicago')->format('Y-m-d')
        ]);
        /** assign tasks to logged in user */
        foreach ($overDateTasks as $task){
            factory(UserTask::class)->create([
                'task_id' => $task->id,
                'user_id' => $this->user->id
            ]);
        }
        /** Act */
        $response = $this->json('GET', "/api/user/".$this->user->id."/over-due");
        /** Assert response is correct */
        $response->assertStatus(200);
        /** decode returned tasks */
        $tasks = $response->decodeResponseJson();
        /** assert tasks were returned */
        $this->assertNotEmpty($tasks);
        /** loop each task and assert they match added over due tasks */
        foreach ($tasks as $key => $task){
            $this->assertEquals($this->project->section->id, $task['section']['id']);
            $this->assertEquals($this->project->id, $task['section']['project']['id']);
            $this->assertEquals($overDateTasks[$key]->name, $task['name']);
            $this->assertEquals($overDateTasks[$key]->note, $task['note']);
            $this->assertEquals($overDateTasks[$key]->due_date, $task['due_date']);
            $this->assertEquals($overDateTasks[$key]->priority_id, $task['priority_id']);
            $this->assertEquals($overDateTasks[$key]->status_id, $task['status_id']);
            $this->assertEquals($overDateTasks[$key]->created_by_id, $task['created_by_id']);
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
        $response = $this->json('GET', "/api/user/".$this->user->id."/over-due");
        /** Assert response is Forbidden */
        $response->assertStatus(403);
    }
}
