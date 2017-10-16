<?php

namespace Tests\Feaure\User;

use App\UserTeam;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Notifications\InviteUser;
use Illuminate\Support\Facades\Notification;
use App\Notifications\welcome;
use Laravel\Passport\Passport;
use App\Invitation;
use App\Team;
use App\User;
use Auth;
use Faker\Factory as Faker;

class UserInviteTest extends TestCase
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
     * Tests Route team.user
     *
     * @test
     */
    public function can_invite_new_user()
    {
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
                     'message' => $email.' has been invited to team '.$this->team->name
                 ]);
        /** assert user is in team */
        $this->assertDatabaseHas('invitations', [
            'email' => $email,
            "team_id" => $this->team->id
        ]);
        /** get invitation user */
        $invitation = Invitation::where('email',$email)->first();
        /** assert user has been sent notification */
        Notification::assertSentTo(
            [$invitation], InviteUser::class
        );
    }

    /**
     * Tests Route user.invite
     *
     * @test
     */
    public function can_create_user_from_invite()
    {
        /** Arrange */
        /** set up notification fake */
        Notification::fake();
        /** create new pending user */
        $invitation= factory(Invitation::class)->create([
            'created_by_id' => $this->user->id,
            'team_id' => $this->team->id,
        ]);
        /** Act */
        /** got to invite link with token  */
        $response = $this->json('GET', "/invitation?token=".urlencode(base64_encode('email='.$invitation->email.'&token='.$invitation->token)));
        /** Asset  */
        $response->assertViewIs('invitation.show');
        /** Arrange */
        $faker = Faker::create();
        $firstName = $faker->firstName;
        $lastName = $faker->lastName;
        $handle = $faker->word;
        $password = $faker->password(7);
        /** Act */
        /** create new user from pending user */
        $response = $this->json('POST', "/invitation",[
            'first_name' => $firstName,
            'last_name' =>  $lastName,
            'handle' => $handle,
            'password' => $password,
            'email' => $invitation->email,
            'invitation_id' => $invitation->id,
            '_token' => csrf_token()
        ]);
        /** Asset  */
        /** user has been created in db */
        $this->assertDatabaseHas('users', [
            'first_name' => $firstName,
            'last_name' =>  $lastName,
            'handle' => $handle,
            'email' => $invitation->email,
        ]);
        $newUser = User::where('email',$invitation->email)->first();
        /** user has been added to team */
        $this->assertDatabaseHas('user_teams', [
            'user_id' => $newUser->id,
            'team_id' =>  $this->team->id
        ]);
        /** user has been removed from pending users table */
        $this->assertDatabaseMissing('invitations', [
            'email' => $invitation->email,
            'id' => $invitation->id
        ]);
        /** assert user has been welcome email */
//        Notification::assertSentTo(
//            [$newUser], Welcome::class
//        );
        /** todo: test user is logged in and sent into application */
    }

    /**
     * Tests Route user.invite
     *
     * @test
     */
    public function invalid_tokens_with_unknown_email_shown_invite_error_in_view()
    {
        /** Arrange */
        /** create new pending user */
        $invitation = factory(Invitation::class)->create([
            'created_by_id' => $this->user->id,
            'team_id' => $this->team->id,
        ]);
        $faker = Faker::create();
        /** create new email */
        $email = $faker->unique()->safeEmail;
        /** Act */
        /** got to invite link with token made using new email */
        $response = $this->json('GET', "/invitation?invitation_code=".urlencode(base64_encode('email='.$email.'&token='.$invitation->token)));
        /** assert user to taken to home page */
        $response->assertViewHas('inviteError');

    }

    /**
     * Tests Route user.invite
     *
     * @test
     */
    public function invalid_tokens_redirected_shown_invite_error()
    {
        /** Act */
        /** got to invite link with token not containing email or token of pending user */
        $response = $this->json('GET', "/invitation?invitation_code=".urlencode(base64_encode(str_random(24))));
        /** assert user to taken to home page */
        $response->assertViewHas('inviteError');
    }
    /**
     * Tests Route user.invite
     *
     * @test
     */
    public function cannot_view_invited_user_form_without_token()
    {
        /** Act */
        /** got to invite link with out token */
        $response = $this->json('GET', "/invitation");
        /** assert user to taken to home page */
        $response->assertViewHas('inviteError');
    }

    /**
     * Tests Route user.invite
     *
     * @test
     */
    public function cannot_create_user_from_invite_without_first_name()
    {
        /** Arrange*/
        /** create new pending user */
        $invitation = factory(Invitation::class)->create([
            'created_by_id' => $this->user->id,
            'team_id' => $this->team->id,
        ]);
        $faker = Faker::create();
        $lastName = $faker->lastName;
        $handle = $faker->word;
        $password = $faker->password(7);
        /** Act */
        /** create new user from pending user */
        $response = $this->json('POST', "/invitation",[
            'last_name' =>  $lastName,
            'handle' => $handle,
            'password' => $password,
            '_token' => csrf_token()
        ]);
        /** Assert user was not created */
        $this->assertDatabaseMissing('users', [
            'email' => $invitation->email,
        ]);
        /** Assert pending user was not deleted */
        $this->assertDatabaseHas('invitations', [
            'id' => $invitation->id,
            'email' => $invitation->email,
        ]);
    }

    /**
     * Tests Route user.invite
     *
     * @test
     */
    public function cannot_create_user_from_invite_without_first_last()
    {
        /** Arrange*/
        /** create new pending user */
        $invitation = factory(Invitation::class)->create([
            'created_by_id' => $this->user->id,
            'team_id' => $this->team->id,
        ]);
        $faker = Faker::create();
        $firstName = $faker->firstName;
        $handle = $faker->word;
        $password = $faker->password(7);
        /** Act */
        /** create new user from pending user */
        $response = $this->json('POST', "/invitation",[
            'first_name' =>  $firstName,
            'handle' => $handle,
            'password' => $password,
            '_token' => csrf_token()
        ]);
        /** Assert user was not created */
        $this->assertDatabaseMissing('users', [
            'email' => $invitation->email,
        ]);
        /** Assert pending user was not deleted */
        $this->assertDatabaseHas('invitations', [
            'id' => $invitation->id,
            'email' => $invitation->email,
        ]);
    }

    /**
     * Tests Route user.invite
     *
     * @test
     */
    public function cannot_create_user_from_invite_without_handle()
    {
        /** Arrange*/
        /** create new pending user */
        $invitation = factory(Invitation::class)->create([
            'created_by_id' => $this->user->id,
            'team_id' => $this->team->id,
        ]);
        $faker = Faker::create();
        $firstName = $faker->firstName;
        $lastName = $faker->lastName;
        $password = $faker->password(7);
        /** Act */
        /** create new user from pending user */
        $response = $this->json('POST', "/invitation",[
            'first_name' =>  $firstName,
            'last_name' =>  $lastName,
            'password' => $password,
            '_token' => csrf_token()
        ]);
        /** Assert user was not created */
        $this->assertDatabaseMissing('users', [
            'email' => $invitation->email,
        ]);
        /** Assert pending user was not deleted */
        $this->assertDatabaseHas('invitations', [
            'id' => $invitation->id,
            'email' => $invitation->email,
        ]);
    }

    /**
     * Tests Route user.invite
     *
     * @test
     */
    public function cannot_create_user_from_invite_without_password_greater_then_7_chars()
    {
        /** Arrange*/
        /** create new pending user */
        $invitation = factory(Invitation::class)->create([
            'created_by_id' => $this->user->id,
            'team_id' => $this->team->id,
        ]);
        $faker = Faker::create();
        $firstName = $faker->firstName;
        $lastName = $faker->lastName;
        $handle = $faker->word;
        $password = $faker->password(1,6);
        /** Act */
        /** create new user from pending user */
        $response = $this->json('POST', "/invitation",[
            'first_name' =>  $firstName,
            'last_name' =>  $lastName,
            'handle' =>  $handle,
            'password' => $password,
            '_token' => csrf_token()
        ]);
        /** Assert user was not created */
        $this->assertDatabaseMissing('users', [
            'email' => $invitation->email,
        ]);
        /** Assert pending user was not deleted */
        $this->assertDatabaseHas('invitations', [
            'id' => $invitation->id,
            'email' => $invitation->email,
        ]);
    }

    /**
     * Tests Route user.invite
     *
     * @test
     */
    public function cannot_create_user_from_invite_without_password()
    {
        /** Arrange*/
        /** create new pending user */
        $invitation = factory(Invitation::class)->create([
            'created_by_id' => $this->user->id,
            'team_id' => $this->team->id,
        ]);
        $faker = Faker::create();
        $firstName = $faker->firstName;
        $lastName = $faker->lastName;
        $handle = $faker->word;
        /** Act */
        /** create new user from pending user */
        $response = $this->json('POST', "/invitation",[
            'first_name' =>  $firstName,
            'last_name' =>  $lastName,
            'handle' =>  $handle,
            '_token' => csrf_token()
        ]);
        /** Assert user was not created */
        $this->assertDatabaseMissing('users', [
            'email' => $invitation->email,
        ]);
        /** Assert pending user was not deleted */
        $this->assertDatabaseHas('invitations', [
            'id' => $invitation->id,
            'email' => $invitation->email,
        ]);
    }

    /**
     * Tests Route user.invite
     *
     * @test
     */
    public function cannot_create_user_from_invite_without_session_data()
    {
        /** Arrange*/
        /** create new pending user */
        $invitation = factory(Invitation::class)->create([
            'created_by_id' => $this->user->id,
            'team_id' => $this->team->id,
        ]);
        $faker = Faker::create();
        $firstName = $faker->firstName;
        $lastName = $faker->lastName;
        $handle = $faker->word;
        $password = $faker->password(7);
        /** Act */
        /** create new user from pending user */
        $response = $this->json('POST', "/invitation",[
            'first_name' =>  $firstName,
            'last_name' =>  $lastName,
            'handle' =>  $handle,
            'password' =>  $password,
            '_token' => csrf_token()
        ]);
        /** Assert user was not created */
        $this->assertDatabaseMissing('users', [
            'email' => $invitation->email,
        ]);
        /** Assert pending user was not deleted */
        $this->assertDatabaseHas('invitations', [
            'id' => $invitation->id,
            'email' => $invitation->email,
        ]);
    }
}
