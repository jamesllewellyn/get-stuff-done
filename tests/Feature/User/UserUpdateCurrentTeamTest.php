<?php

namespace Tests\Feaure\User;

use App\UserTeam;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Passport\Passport;
use App\Team;
use App\User;
use Auth;

class UserUpdateCurrentTeamTest extends TestCase
{
    use DatabaseTransactions;
    protected $user;
    protected $teams;

    protected function setUp(){
        parent::setUp();
        /** create and act as user */
        $this->user = factory(User::class)->create();
        Passport::actingAs(
            $this->user
        );
        /** set up new teams */
        $this->teams = factory(Team::class, 2)->create();
        /** add user to each team */
        foreach ($this->teams->toArray() as $team){
            factory(UserTeam::class)->create([
                'user_id' => Auth::user()->id,
                'team_id' => $team['id'],
            ]);
        }
    }

    /**
     * Tests Route user.current_team
     *
     * @test
     */
    public function can_update_users_current_team()
    {
        /** Act */
        /** update users current team to first team in $this->teams */
        $response = $this->json('PUT', "/api/user/".$this->user->id."/team", [
            "teamId" => $this->teams->first()->id,
        ]);
        /** Assert response is correct */
        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Team has been switched',
                'user' => $this->user->toArray()
            ]);
        /** Assert users current team has been updated in db */
        $this->assertDatabaseHas('users', [
            'id' => $this->user->id,
            'current_team_id' => $this->teams->first()->id,
        ]);
    }

    /**
     * Tests Route user.current_team
     *
     * @test
     */
    public function cannot_update_current_team_with_teamId()
    {
        /** Act */
        /** update users current team with teamId field */
        $response = $this->json('PUT', "/api/user/".$this->user->id."/team", []);
        /** Assert response is correct */
        $response->assertStatus(422)
            ->assertJson([
                'teamId' => ["The team id field is required."]
            ]);
        /** Assert users current team has not been updated in db */
        $this->assertDatabaseHas('users', [
            'id' => $this->user->id,
            'current_team_id' => null,
        ]);
    }

    /**
     * Tests Route user.current_team
     *
     * @test
     */
    public function cannot_update_current_team_to_team_user_in_not_a_member_of()
    {
        /** Arrange */
        /** create new team and don't add user to it */
        $team = factory(Team::class)->create();
        /** Act */
        /** try to update users current team to new team */
        $response = $this->json('PUT', "/api/user/".$this->user->id."/team", [
            'teamId' => $team->id
        ]);
        /** Assert response is Forbidden */
        $response->assertStatus(403);
        /** Assert users current team has not been updated in db */
        $this->assertDatabaseHas('users', [
            'id' => $this->user->id,
            'current_team_id' => null,
        ]);
    }

    /**
     * Tests Route user.current_team
     *
     * @test
     */
    public function cannot_update_another_users_current_team()
    {
        /** Arrange */
        /** create a new user */
        $user = factory(User::class)->create();
        /** add user to team */
        factory(UserTeam::class)->create([
            'user_id' => $user->id,
            'team_id' => $this->teams->first()->id,
        ]);
        /** Act */
        /** try to update new users current team */
        $response = $this->json('PUT', "/api/user/".$user->id."/team", [
            'teamId' => $this->teams->first()->id
        ]);
        /** Assert response is Forbidden */
        $response->assertStatus(403);
        /** Assert users current team has not been updated in db */
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'current_team_id' => null,
        ]);
    }
}
