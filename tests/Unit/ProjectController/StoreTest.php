<?php

namespace Tests\Unit\ProjectController;

use App\UserTeam;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Passport\Passport;
use App\Team;
use App\User;
use Auth;
use Faker\Factory as Faker;

class StoreTest extends TestCase
{
    use DatabaseTransactions;
    protected $team;
    protected $user;

    protected function setUp(){
        parent::setUp();
        /** create and act as user */
        Passport::actingAs(
            factory(User::class)->create()
        );
        /** add user to protected var */
        $this->user = Auth::user();
        /** set up new team */
        $this->team = factory(Team::class)->create();
        /** add user to team */
        factory(UserTeam::class)->create([
            'user_id' => $this->user->id,
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
            'team_id' => $this->team->id
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
        $response->assertStatus(403);
    }
}
