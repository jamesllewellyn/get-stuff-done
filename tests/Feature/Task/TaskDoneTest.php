<?php

namespace Tests\Feaure\Task;

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

class TaskDoneTest extends TestCase
{
    use DatabaseTransactions;
    protected $team;
    protected $project;
    protected $section;
    protected $task;

    protected function setUp(){
        parent::setUp();
        /** create and act as user */
        Passport::actingAs(
            factory(User::class)->create()
        );
        /** set up new team */
        $this->team = factory(Team::class)->create();
        /** add user to team */
        factory(UserTeam::class)->create([
            'user_id' => Auth::user()->id,
            'team_id' => $this->team->id,
        ]);
        /** create new project */
        $this->project = factory(Project::class)->create([
            'team_id' => $this->team->id
        ]);
        /** create new project section */
        $this->section = factory(Section::class)->create([
            'project_id' => $this->project->id
        ]);
        /** create new task and add to section */
        $this->task = factory(Task::class)->create([
            'section_id' => $this->section->id,
            'sort_order' => 1
        ]);
    }

    /**
     * Tests Route task.done
     *
     * @test
     */
    public function can_flag_task_as_done()
    {
        /** Act */
        $response = $this->json('PUT', "/api/team/".$this->team->id."/project/".$this->project->id."/section/".$this->section->id."/task/".$this->task->id."/done");
        /** Assert response is correct */
        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Task '.$this->task->name.' has been flagged as done',
                'task' => [
                    "id" => $this->task->id,
                    "priority_id" => $this->task->priority_id,
                    "section_id" => $this->task->section_id,
                    "name" => $this->task->name,
                    "note" => $this->task->note,
                    "sort_order" => $this->task->sort_order,
                    "status_id" => 1,
                    "due_date" => $this->task->due_date,
                    "due_time" => $this->task->due_time,
                    "created_by_id" => $this->task->created_by_id,
                    "created_at" => $this->task->created_at->format('Y-m-d H:i:s'),
                    "deleted_at" => null
                ]
            ]);
        /** Assert task is correct in db */
        $this->assertDatabaseHas('tasks', [
            "id" => $this->task->id,
            "priority_id" => $this->task->priority_id,
            "section_id" => $this->task->section_id,
            "name" => $this->task->name,
            "note" => $this->task->note,
            "sort_order" => $this->task->sort_order,
            "status_id" => 1,
            "due_date" => $this->task->due_date,
            "due_time" => $this->task->due_time,
            "created_by_id" => $this->task->created_by_id,
            "created_at" => $this->task->created_at->format('Y-m-d H:i:s'),
            "deleted_at" => null
        ]);
    }

    /**
     * Tests Route task.done
     *
     * @test
     */
    public function cannot_flag_task_as_done_in_team_user_not_a_member_of()
    {
        /** Arrange */
        /** create new user and don't add to team*/
        Passport::actingAs(
            factory(User::class)->create()
        );
        /** Act */
        $response = $this->json('PUT', "/api/team/".$this->team->id."/project/".$this->project->id."/section/".$this->section->id."/task/".$this->task->id."/done");
        /** Assert response is correct */
        $response->assertStatus(403);
        /** Assert task is correct in db */
        $this->assertDatabaseHas('tasks', [
            "id" => $this->task->id,
            "priority_id" => $this->task->priority_id,
            "section_id" => $this->task->section_id,
            "name" => $this->task->name,
            "note" => $this->task->note,
            "sort_order" => $this->task->sort_order,
            "status_id" => $this->task->status_id,
            "due_date" => $this->task->due_date,
            "due_time" => $this->task->due_time,
            "created_by_id" => $this->task->created_by_id,
            "created_at" => $this->task->created_at->format('Y-m-d H:i:s'),
            "deleted_at" => null
        ]);
    }
}
