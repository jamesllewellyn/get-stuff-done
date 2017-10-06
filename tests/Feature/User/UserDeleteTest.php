<?php

namespace Tests\Feaure\User;

use App\UserTeam;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Passport\Passport;
use App\Team;
use App\User;
use Auth;

class UserDeleteTest extends TestCase
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
    public function can_delete_user()
    {
        /** Act */
        $response = $this->json('DELETE', "/api/user/".$this->user->id);
        /** Assert response is correct */
        $response->assertStatus(200)
            ->assertJson([
                'success' => true, 'message' => 'User '.$this->user->full_name.' has been successfully deleted'
            ]);
        /** assert user is not deleted */
        $this->assertDatabaseMissing('users', [
            'id' => $this->user->id
        ]);
    }
}
