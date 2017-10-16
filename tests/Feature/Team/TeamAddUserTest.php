<?php

namespace Tests\Feaure\Team;

use App\Invitation;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Passport\Passport;
use App\User;
use App\Notifications\AddedToTeam;
use Illuminate\Support\Facades\Notification;
use Auth;
use App\Team;
use App\UserTeam;
use App\Notifications\InviteUser;
use Faker\Factory as Faker;

class TeamAddUserTest extends TestCase
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
     * Tests Route team.user
     *
     * @test
     */
    public function cannot_add_user_to_team_without_email_address()
    {
        /** Act */
        $response = $this->json('POST', "/api/team/".$this->team->id."/user",[]);
        /** Assert response is correct*/
        $response->assertStatus(422)
            ->assertJson([
                'email' => ["The email field is required."]
            ]);
    }

    /**
     * Tests Route team.user
     *
     * @test
     */
    public function cannot_add_user_to_team_with_invalid_email_address()
    {
        /** Arrange */
        $faker = Faker::create();
        $email = $faker->words(2);
        /** Act */
        $response = $this->json('POST', "/api/team/".$this->team->id."/user",[
            'email' => $email
        ]);
        /** Assert response is correct*/
        $response->assertStatus(422)
            ->assertJson([
                'email' => ["That email address doesn't look quiet right"]
            ]);
    }

    /**
     * Tests Route team.user
     *
     * @test
     */
    public function can_add_existing_app_user_to_team()
    {
        /** Arrange */
        Notification::fake();
        /** create user */
        $newUser = factory(User::class)->create();
        /** Act */
        $response = $this->json('POST', "/api/team/".$this->team->id."/user",[
            'email' => $newUser->email
        ]);
        /** Assert response is correct */
        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => $newUser->email.' has been invited to team '.$this->team->name
            ]);
        /** assert user has been added to team */
        $this->assertDatabaseHas('invitations', [
            'user_id' => $newUser->id,
            "team_id" => $this->team->id
        ]);
        $invitation = Invitation::where(['user_id' => $newUser->id, "team_id" => $this->team->id])->first();
        /** assert user has been sent notification */
        Notification::assertSentTo(
            [$invitation], InviteUser::class
        );
    }

    /**
     * Tests Route team.user
     *
     * @test
     */
    public function cannot_add_existing_team_member_user_to_team()
    {
        /** create user */
        Notification::fake();
        $newUser = factory(User::class)->create();
        /** added to team */
        factory(UserTeam::class)->create([
            'user_id' => $newUser->id,
            'team_id' => $this->team->id,
        ]);
        /** Act */
        $response = $this->json('POST', "/api/team/".$this->team->id."/user",[
            'email' => $newUser->email
        ]);
        /** Assert response is correct */
        $response->assertStatus(200)
            ->assertJson([
                'success' => false,
                'message' => $newUser->email.' is already a member of team '.$this->team->name
            ]);
        /** assert user is in team */
        $this->assertDatabaseHas('user_teams', [
            'user_id' => $newUser->id,
            "team_id" => $this->team->id
        ]);
        /** assert user has not been sent notification */
        Notification::assertNotSentTo(
            [$newUser], AddedToTeam::class
        );
    }

    /**
     * Tests Route team.user
     *
     * @test
     */
    public function can_add_new_app_user_to_team()
    {
        /** create user */
        Notification::fake();
        $faker = Faker::create();
        $email = $faker->unique()->safeEmail;
        /** Act */
        $response = $this->json('POST', "/api/team/".$this->team->id."/user",[
            'email' => $email
        ]);
        /** Assert response is correct */
        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => "{$email} has been invited to team {$this->team->name}"
            ]);
        /** assert user is in team */
        $this->assertDatabaseHas('invitations', [
            'email' => $email,
            "team_id" => $this->team->id
        ]);
        /** get pending user */
        $invitation = Invitation::where('email',$email)->first();
        /** assert user has  been sent notification */
        Notification::assertSentTo(
            [$invitation], InviteUser::class
        );
    }
}
