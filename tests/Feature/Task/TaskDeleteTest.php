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

class TaskDeleteTest extends TestCase
{
    use DatabaseTransactions;
    protected $team;
    protected $project;
    protected $section;

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
    }

    /**
     * Tests Route section.delete
     *
     * @test
     */
    public function can_delete_task()
    {
        /** Arrange */
        /** create new task and add to section */
        $task = factory(Task::class)->create([
            'section_id' => $this->section->id,
            'sort_order' => 1
        ]);
        /** Act */
        $response = $this->json('DELETE', "/api/team/".$this->team->id."/project/".$this->project->id."/section/".$this->section->id."/task/".$task->id);

        /** Assert response is correct */
        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Task '.$task->name.' has been successfully deleted'
            ]);
        /** assert project is soft deleted */
        $this->assertSoftDeleted('tasks', [
            'id' => $task->id
        ]);
    }

    /**
     * Tests Route section.delete
     *
     * @test
     */
    public function cannot_delete_task_in_team_user_is_not_a_member_of()
    {
        /** Arrange */
        /** create new task and add to section */
        $task = factory(Task::class)->create([
            'section_id' => $this->section->id,
            'sort_order' => 1
        ]);
        /** create new user and don't add to team*/
        Passport::actingAs(
            factory(User::class)->create()
        );
        /** Act */
        $response = $this->json('DELETE', "/api/team/".$this->team->id."/project/".$this->project->id."/section/".$this->section->id."/task/".$task->id);
        /** Assert response is correct */
        $response->assertStatus(403);
        /** assert section is not deleted */
        $this->assertDatabaseHas('tasks', [
            'id' => $task->id
        ]);
    }

}
