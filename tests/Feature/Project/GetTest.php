<?php

namespace Tests\Feaure\Project;

use App\Section;
use App\UserTeam;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Passport\Passport;
use App\Team;
use App\User;
use App\Project;
use App\Task;
use Auth;

class GetTest extends TestCase
{
    use DatabaseTransactions;
    protected $team;
    protected $project;
    protected $sections;
    protected $tasks;

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
        /** create new project sections */
        $this->sections = factory(Section::class,3)->create([
            'project_id' => $this->project->id
        ]);
        /** create new project sections tasks */
        $this->tasks = factory(Task::class,3)->create([
            'section_id' => $this->sections->first()->id,
            'sort_order' => 1
        ]);
    }
    /**
     * Tests ProjectController::show
     *
     * @test
     */
    public function can_get_project()
    {
        /** Act */
        $response = $this->json('GET', "/api/team/".$this->team->id."/project/".$this->project->id);
        /** Assert response is correct */
        $response->assertStatus(200)
            ->assertJson([
                "success" => true,
                "message" => "project has been found",
                "project" => []
            ]);
        /** assert project returned to correct */
        $project = $response->decodeResponseJson()['project'];
        $this->assertEquals($this->project->name, $project['name']);
        $this->assertEquals($this->project->team_id, $project['team_id']);
    }

    /**
     * Tests ProjectController::show
     *
     * @test
     */
    public function can_get_project_with_sections()
    {
        /** Act */
        $response = $this->json('GET', "/api/team/".$this->team->id."/project/".$this->project->id);
        /** Assert response is correct */
        $response->assertStatus(200)
            ->assertJson([
                "success" => true,
                "message" => "project has been found",
                "project" => []
            ]);
        /** assert project returned to correct */
        $project = $response->decodeResponseJson()['project'];
        /** assert project returned has correct sections */
        /** loop each added section */
        foreach ($this->sections->toArray() as $key => $section){
            /** compare section matches section returned without tasks and deleted_at fields */
            $this->assertEquals($section, array_diff_key($project['sections'][$key],["deleted_at" => null, "tasks" => null]));
        }
    }

    /**
     * Tests ProjectController::show
     *
     * @test
     */
    public function can_get_project_with_sections_and_tasks()
    {
        /** Act */
        $response = $this->json('GET', "/api/team/".$this->team->id."/project/".$this->project->id);
        /** Assert response is correct */
        $response->assertStatus(200)
            ->assertJson([
                "success" => true,
                "message" => "project has been found",
                "project" => []
            ]);
        /** assert project returned to correct */
        $project = $response->decodeResponseJson()['project'];
        /** assert project returned has correct tasks */
        /** loop each added task */
        foreach ($project['sections'][0]['tasks'] as $key => $task){
            /** compare task matches task returned without assigned_users , due_time and deleted_at fields */
            $this->assertEquals($this->tasks[$key]->toArray(), array_diff_key($task, ["deleted_at" => null, "assigned_users" => null, "due_time" => null]) );
        }
    }
}
