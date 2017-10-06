<?php

namespace Tests\Feaure\Project;

use App\UserTeam;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Passport\Passport;
use App\Team;
use App\User;
use Faker\Factory as Faker;
use Auth;

class ProjectCreateTest extends TestCase
{
    use DatabaseTransactions;
    protected $team;

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
    }
    /**
     * Tests ProjectController::store
     *
     * @test
     */
    public function can_create_project()
    {
        /** Arrange */
        $faker = Faker::create();
        $projectName = $faker->words(4,true);
        /** Act */
        $response = $this->json('POST', "/api/team/".$this->team->id."/project", [
            "name" => $projectName
        ]);
        /** Assert response is correct */
        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'project has been created'
            ]);
        /** Assert project is correct in db */
        $this->assertDatabaseHas('projects', [
            'name' => $projectName,
            'team_id' => $this->team->id,
            "deleted_at" => null
        ]);
    }
    /**
     * Tests ProjectController::store
     *
     * @test
     */
    public function cannot_create_project_without_project_name()
    {
        /** Act */
        $response = $this->json('POST', "/api/team/".$this->team->id."/project",[]);
        /** Assert */
        $response->assertStatus(422)
                 ->assertJson([
                    'name' => ["Please provide a name for this project"]
                 ]);
    }
    /**
     * Tests ProjectController::store
     *
     * @test
     */
    public function user_not_in_team_cannot_create_project()
    {
        /** Arrange */
        $faker = Faker::create();
        $projectName = $faker->words(4,true);
        /** create new user and don't add to team*/
        Passport::actingAs(
            factory(User::class)->create()
        );
        /** Act */
        $response = $this->json('POST', "/api/team/".$this->team->id."/project", [
            "name" => $projectName
        ]);
        /** Assert 403 status code */
        $response->assertStatus(403);
        /** Assert project has not been created */
        $this->assertDatabaseMissing('projects', [
            'name' => $projectName,
            'team_id' => $this->team->id
        ]);
    }
}
