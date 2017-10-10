<?php

namespace Tests\Feaure\Auth;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Passport\Passport;
use App\Team;
use App\UserTeam;
use App\User;
use Auth;

class LoginTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Tests Route home
     *
     * @test
     */
    public function user_without_team_gets_redirected_to_create_team_page_on_log_in()
    {
        /** Arrange */
        /** create and act as user */
        $user = factory(User::class)->create();
        Passport::actingAs(
            $user
        );
        /** Act */
        /** enter app */
        $response = $this->json('GET', "/home");
        /** Assert redirect status code */
        $response->assertStatus(302);
        /** Assert user is take to create team form */
        $response->assertRedirect('/create/#/team');

    }

    /**
     * Tests Route home
     *
     * @test
     */
    public function user_with_team_is_taken_into_app()
    {
        /** Arrange */
        /** create and act as user */
        $user = factory(User::class)->create([
            'current_team_id' => null
        ]);
        Passport::actingAs(
            $user
        );
        /** Act */
        /** set up new team */
        $team = factory(Team::class)->create();
        /** add user to team */
        factory(UserTeam::class)->create([
            'user_id' => $user->id,
            'team_id' => $team->id,
        ]);
        /** enter app */
        $response = $this->json('GET', "/home");
        /** Assert */
        /** status code 200 */
        $response->assertStatus(200);
        /** home view */
        $response->assertViewIs('home');
        /** user has current team_id updated to team */
        $this->assertDatabaseHas('users',[
            'id' => $user->id,
            'current_team_id' => $team->id
        ]);
    }
    /**
     * Tests Route home
     *
     * @test
     */
    public function user_not_logged_in_cant_get_into_application()
    {
        /** Act */
        /** enter app */
        $response = $this->json('GET', "/home");
        /** Assert status code Unauthorized */
        $response->assertStatus(401);
    }
}
