<?php

namespace Tests\Feaure\User;

use App\UserTeam;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Passport\Passport;
use App\Team;
use App\User;
use Auth;

class UserIndexTest extends TestCase
{
    use DatabaseTransactions;
    protected $user;
    protected $team;

    protected function setUp()
    {
        parent::setUp();
        /** create and act as user */
        $this->user = factory(User::class)->create();
        Passport::actingAs(
            $this->user
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
     * Tests Route user.update
     *
     * @test
     */
    public function can_get_user_index()
    {
        /** Act */
        $response = $this->json('GET', "/api/user/");
        /** Assert response is correct */
        $response->assertStatus(200)
            ->assertJson(
                $this->user->toArray()
            );
    }
}
