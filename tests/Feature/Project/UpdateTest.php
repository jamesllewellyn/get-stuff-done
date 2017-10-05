<?php

namespace Tests\Feaure\Project;

use App\UserTeam;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Passport\Passport;
use App\Team;
use App\User;
use App\Project;
use Auth;
use Faker\Factory as Faker;

class UpdateTest extends TestCase
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
    }

    /**
     * Tests ProjectController::update
     *
     * @test
     */
    public function can_update_project()
    {
        /** Act */
        $faker = Faker::create();
        $projectName = $faker->words(4,true);
        /** Act */
        $response = $this->json('PUT', "/api/team/".$this->team->id."/project/".$this->project->id, [
            "name" => $projectName
        ]);
        /** Assert response is correct */
        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'project has been updated',
                "project" => [
                    "id" => $this->project->id,
                    "team_id" => $this->project->team_id,
                    "name" => $projectName,
                    "created_at" => $this->project->created_at->format('Y-m-d H:i:s'),
                    "updated_at" => $this->project->updated_at->format('Y-m-d H:i:s'),
                    "deleted_at" => null
                ],
            ]);
        /** Assert project is correct in db */
        $this->assertDatabaseHas('projects', [
            'name' => $projectName,
            'team_id' => $this->team->id
        ]);
    }

    /**
     * Tests ProjectController::update
     *
     * @test
     */
    public function cannot_update_project_without_project_name()
    {
        /** Act */
        $response = $this->json('PUT', "/api/team/".$this->team->id."/project/".$this->project->id, []);
        /** Assert response is correct */
        $response->assertStatus(422)
            ->assertJson([
                'name' => ['Please provide a name for this project']
            ]);
        /** Assert project has not been updated in db */
        $this->assertDatabaseHas('projects', [
            'id' => $this->project->id,
            'name' => $this->project->name,
            'team_id' => $this->team->id,
            'updated_at' => $this->project->updated_at->format('Y-m-d H:i:s')
        ]);
    }

    /**
     * Tests ProjectController::update
     *
     * @test
     */
    public function cannot_update_project_in_team_user_is_not_a_member_of()
    {
        /** Arrange */
        $faker = Faker::create();
        $projectName = $faker->words(4,true);
        /** create new user and don't add to team*/
        Passport::actingAs(
            factory(User::class)->create()
        );
        /** Act */
        $response = $this->json('PUT', "/api/team/".$this->team->id."/project/".$this->project->id, [
            "name" => $projectName
        ]);
        /** Assert response is correct */
        $response->assertStatus(403);
        /** Assert project has not been updated in db */
        $this->assertDatabaseHas('projects', [
            'id' => $this->project->id,
            'name' => $this->project->name,
            'team_id' => $this->team->id,
            'updated_at' => $this->project->updated_at->format('Y-m-d H:i:s')
        ]);
    }

}
