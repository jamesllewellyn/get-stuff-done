<?php

namespace Tests\Feaure\User;

use App\UserTeam;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Notifications\InviteUser;
use Illuminate\Support\Facades\Notification;
use App\Notifications\welcome;
use Laravel\Passport\Passport;
use App\PendingUser;
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
                     'message' => $email.' has been invited to team'
                 ]);
        /** assert user is in team */
        $this->assertDatabaseHas('users_pending', [
            'email' => $email,
            "team_id" => $this->team->id
        ]);
        /** get pending user */
        $pendingUser = PendingUser::where('email',$email)->first();
        /** assert user has been sent notification */
        Notification::assertSentTo(
            [$pendingUser], InviteUser::class
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
        $pendingUser = factory(PendingUser::class)->create([
            'created_by_id' => $this->user->id,
            'team_id' => $this->team->id,
        ]);
        /** Act */
        /** got to invite link with token  */
        $response = $this->json('GET', "/invite?token=".urlencode(base64_encode('email='.$pendingUser->email.'&token='.$pendingUser->token)));
        /** Asset  */
        $response->assertViewIs('auth.invite');
        /** asset pending users data is set in session var */
        $response->assertSessionHas('pending',$pendingUser->toArray());
        /** Arrange */
        $faker = Faker::create();
        $firstName = $faker->firstName;
        $lastName = $faker->lastName;
        $handle = $faker->word;
        $password = $faker->password(7);
        /** Act */
        /** create new user from pending user */
        $response = $this->json('POST', "/invite",[
            'first_name' => $firstName,
            'last_name' =>  $lastName,
            'handle' => $handle,
            'password' => $password,
            '_token' => csrf_token()
        ]);
        /** Asset  */
        /** user has been created in db */
        $this->assertDatabaseHas('users', [
            'first_name' => $firstName,
            'last_name' =>  $lastName,
            'handle' => $handle,
            'email' => $pendingUser->email,
        ]);
        $newUser = User::where('email',$pendingUser->email)->first();
        /** user has been added to team */
        $this->assertDatabaseHas('user_teams', [
            'user_id' => $newUser->id,
            'team_id' =>  $this->team->id
        ]);
        /** user has been removed from pending users table */
        $this->assertDatabaseMissing('users_pending', [
            'email' => $pendingUser->email,
            'id' => $pendingUser->id
        ]);
        /** assert user has been welcome email */
        Notification::assertSentTo(
            [$newUser], welcome::class
        );
        /** todo: test user is logged in and sent into application */
    }

    /**
     * Tests Route user.invite
     *
     * @test
     */
    public function invalid_tokens_with_unknown_email_redirected_to_welcome_page()
    {
        /** Arrange */
        /** create new pending user */
        $pendingUser = factory(PendingUser::class)->create([
            'created_by_id' => $this->user->id,
            'team_id' => $this->team->id,
        ]);
        $faker = Faker::create();
        /** create new email */
        $email = $faker->unique()->safeEmail;
        /** Act */
        /** got to invite link with token made using new email */
        $response = $this->json('GET', "/invite?token=".urlencode(base64_encode('email='.$email.'&token='.$pendingUser->token)));
        /** assert user to taken to home page */
        $response->assertRedirect('/');
        /** assert session is missing pending user data */
        $response->assertSessionMissing('pending');
    }

    /**
     * Tests Route user.invite
     *
     * @test
     */
    public function invalid_tokens_redirected_to_welcome_page()
    {
        /** Act */
        /** got to invite link with token not containing email or token of pending user */
        $response = $this->json('GET', "/invite?token=".urlencode(base64_encode(str_random(24))));
        /** assert user to taken to home page */
        $response->assertRedirect('/');
        /** assert session is missing pending user data */
        $response->assertSessionMissing('pending');
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
        $response = $this->json('GET', "/invite");
        /** assert user to taken to home page */
        $response->assertRedirect('/');
        /** assert session is missing pending user data */
        $response->assertSessionMissing('pending');
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
        $pendingUser = factory(PendingUser::class)->create([
            'created_by_id' => $this->user->id,
            'team_id' => $this->team->id,
        ]);
        /** add pending user data to session */
        $this->session([
            'pending' => $pendingUser->toArray()
        ]);
        $faker = Faker::create();
        $lastName = $faker->lastName;
        $handle = $faker->word;
        $password = $faker->password(7);
        /** Act */
        /** create new user from pending user */
        $response = $this->json('POST', "/invite",[
            'last_name' =>  $lastName,
            'handle' => $handle,
            'password' => $password,
            '_token' => csrf_token()
        ]);
        /** Assert user was not created */
        $this->assertDatabaseMissing('users', [
            'email' => $pendingUser->email,
        ]);
        /** Assert pending user was not deleted */
        $this->assertDatabaseHas('users_pending', [
            'id' => $pendingUser->id,
            'email' => $pendingUser->email,
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
        $pendingUser = factory(PendingUser::class)->create([
            'created_by_id' => $this->user->id,
            'team_id' => $this->team->id,
        ]);
        /** add pending user data to session */
        $this->session([
            'pending' => $pendingUser->toArray()
        ]);
        $faker = Faker::create();
        $firstName = $faker->firstName;
        $handle = $faker->word;
        $password = $faker->password(7);
        /** Act */
        /** create new user from pending user */
        $response = $this->json('POST', "/invite",[
            'first_name' =>  $firstName,
            'handle' => $handle,
            'password' => $password,
            '_token' => csrf_token()
        ]);
        /** Assert user was not created */
        $this->assertDatabaseMissing('users', [
            'email' => $pendingUser->email,
        ]);
        /** Assert pending user was not deleted */
        $this->assertDatabaseHas('users_pending', [
            'id' => $pendingUser->id,
            'email' => $pendingUser->email,
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
        $pendingUser = factory(PendingUser::class)->create([
            'created_by_id' => $this->user->id,
            'team_id' => $this->team->id,
        ]);
        /** add pending user data to session */
        $this->session([
            'pending' => $pendingUser->toArray()
        ]);
        $faker = Faker::create();
        $firstName = $faker->firstName;
        $lastName = $faker->lastName;
        $password = $faker->password(7);
        /** Act */
        /** create new user from pending user */
        $response = $this->json('POST', "/invite",[
            'first_name' =>  $firstName,
            'last_name' =>  $lastName,
            'password' => $password,
            '_token' => csrf_token()
        ]);
        /** Assert user was not created */
        $this->assertDatabaseMissing('users', [
            'email' => $pendingUser->email,
        ]);
        /** Assert pending user was not deleted */
        $this->assertDatabaseHas('users_pending', [
            'id' => $pendingUser->id,
            'email' => $pendingUser->email,
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
        $pendingUser = factory(PendingUser::class)->create([
            'created_by_id' => $this->user->id,
            'team_id' => $this->team->id,
        ]);
        /** add pending user data to session */
        $this->session([
            'pending' => $pendingUser->toArray()
        ]);
        $faker = Faker::create();
        $firstName = $faker->firstName;
        $lastName = $faker->lastName;
        $handle = $faker->word;
        $password = $faker->password(1,6);
        /** Act */
        /** create new user from pending user */
        $response = $this->json('POST', "/invite",[
            'first_name' =>  $firstName,
            'last_name' =>  $lastName,
            'handle' =>  $handle,
            'password' => $password,
            '_token' => csrf_token()
        ]);
        /** Assert user was not created */
        $this->assertDatabaseMissing('users', [
            'email' => $pendingUser->email,
        ]);
        /** Assert pending user was not deleted */
        $this->assertDatabaseHas('users_pending', [
            'id' => $pendingUser->id,
            'email' => $pendingUser->email,
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
        $pendingUser = factory(PendingUser::class)->create([
            'created_by_id' => $this->user->id,
            'team_id' => $this->team->id,
        ]);
        /** add pending user data to session */
        $this->session([
            'pending' => $pendingUser->toArray()
        ]);
        $faker = Faker::create();
        $firstName = $faker->firstName;
        $lastName = $faker->lastName;
        $handle = $faker->word;
        /** Act */
        /** create new user from pending user */
        $response = $this->json('POST', "/invite",[
            'first_name' =>  $firstName,
            'last_name' =>  $lastName,
            'handle' =>  $handle,
            '_token' => csrf_token()
        ]);
        /** Assert user was not created */
        $this->assertDatabaseMissing('users', [
            'email' => $pendingUser->email,
        ]);
        /** Assert pending user was not deleted */
        $this->assertDatabaseHas('users_pending', [
            'id' => $pendingUser->id,
            'email' => $pendingUser->email,
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
        $pendingUser = factory(PendingUser::class)->create([
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
        $response = $this->json('POST', "/invite",[
            'first_name' =>  $firstName,
            'last_name' =>  $lastName,
            'handle' =>  $handle,
            'password' =>  $password,
            '_token' => csrf_token()
        ]);
        /** Assert user was not created */
        $this->assertDatabaseMissing('users', [
            'email' => $pendingUser->email,
        ]);
        /** Assert pending user was not deleted */
        $this->assertDatabaseHas('users_pending', [
            'id' => $pendingUser->id,
            'email' => $pendingUser->email,
        ]);
    }
}
