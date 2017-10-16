<?php

namespace Tests\Feaure\Team;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Passport\Passport;
use App\User;
use Faker\Factory as Faker;
use Auth;

class TeamCreateTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(){
        parent::setUp();
        /** create and act as user */
        Passport::actingAs(
            factory(User::class)->create()
        );
    }
    /**
     * Tests TeamController::store
     *
     * @test
     */
    public function can_create_team()
    {
        /** Arrange */
        $faker = Faker::create();
        $teamName = $faker->words(4,true);
        /** Act */
        $response = $this->json('POST', "/api/team", [
            "name" => $teamName
        ]);
        /** Assert response is correct */
        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'team' => [
                    'name' => $teamName
                ]
            ]);
        /** decode response and get team*/
        $team = $response->decodeResponseJson()['team'];
        /** Assert team is correct in db */
        $this->assertDatabaseHas('teams', [
            'name' => $teamName,
            "deleted_at" => null
        ]);
        /** Assert user is member of team */
        $this->assertDatabaseHas('user_teams', [
            'user_id' => Auth::user()->id,
            "team_id" => $team['id']
        ]);
    }

    /**
     * Tests ProjectController::store
     *
     * @test
     */
    public function cannot_create_team_without_team_name()
    {
        /** Act */
        $response = $this->json('POST', "/api/team",[]);
        /** Assert */
        $response->assertStatus(422)
            ->assertJson([
                'name' => ["Please provide a name for your team"]
            ]);
    }

}
