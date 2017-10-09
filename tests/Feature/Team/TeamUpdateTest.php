<?php

namespace Tests\Feaure\Team;

use App\UserTeam;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Passport\Passport;
use App\Team;
use App\User;
use Auth;
use Faker\Factory as Faker;

class TeamUpdateTest extends TestCase
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
     * Tests TeamController::update
     *
     * @test
     */
    public function can_update_team()
    {
        /** Act */
        $faker = Faker::create();
        $teamName = $faker->words(4,true);
        /** Act */
        $response = $this->json('PUT', "/api/team/".$this->team->id, [
            "name" => $teamName
        ]);
        /** Assert response is correct */
        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                "team" => [
                    "id" => $this->team->id,
                    "name" => $teamName,
                    "created_at" => $this->team->created_at->format('Y-m-d H:i:s'),
                    "updated_at" => $this->team->updated_at->format('Y-m-d H:i:s'),
                    "deleted_at" => null
                ],
            ]);
        /** Assert team is correct in db */
        $this->assertDatabaseHas('teams', [
            'id' => $this->team->id,
            'name' => $teamName
        ]);
    }

    /**
     * Tests TeamController::update
     *
     * @test
     */
    public function cannot_update_team_without_team_name()
    {
        /** Act */
        $response = $this->json('PUT', "/api/team/".$this->team->id, []);
        /** Assert response is correct */
        $response->assertStatus(422)
            ->assertJson([
                'name' => ['Please provide a name for your team']
            ]);
        /** Assert team has not been updated in db */
        $this->assertDatabaseHas('teams', [
            'id' => $this->team->id,
            'name' => $this->team->name,
            'updated_at' => $this->team->updated_at->format('Y-m-d H:i:s')
        ]);
    }

    /**
     * Tests TeamController::update
     *
     * @test
     */
    public function cannot_update_team_user_is_not_a_member_of()
    {
        /** Arrange */
        $faker = Faker::create();
        $teamName = $faker->words(4,true);
        /** create new user and don't add to team */
        Passport::actingAs(
            factory(User::class)->create()
        );
        /** Act */
        $response = $this->json('PUT', "/api/team/".$this->team->id, [
            "name" => $teamName
        ]);
        /** Assert response is correct */
        $response->assertStatus(403);
        /** Assert team has not been updated in db */
        $this->assertDatabaseHas('teams', [
            'id' => $this->team->id,
            'name' => $this->team->name,
            'updated_at' => $this->team->updated_at->format('Y-m-d H:i:s')
        ]);
    }

}
