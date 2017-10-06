<?php

namespace Tests\Feaure\User;

use App\UserTeam;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Passport\Passport;
use App\Team;
use App\User;
use Auth;
use Faker\Factory as Faker;

class UserUpdateTest extends TestCase
{
    use DatabaseTransactions;
    protected $user;
    protected $team;

    protected function setUp(){
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
    public function can_update_user()
    {
        /** Act */
        $faker = Faker::create();
        $first_name = $faker->firstName();
        $last_name = $faker->lastName;
        $handle =  $faker->word;
        /** Act */
        $response = $this->json('PUT', "/api/user/".$this->user->id, [
            "first_name" => $first_name,
            "last_name" => $last_name,
            "handle" => $handle
        ]);
        /** Assert response is correct */
        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'user has been updated',
                "user" => [
                    "id" => $this->user->id,
                    "first_name" => $first_name,
                    "last_name" => $last_name,
                    "full_name" => $first_name.' '.$last_name,
                    "email" => $this->user->email,
                    "handle" => $handle,
                    "created_at" => $this->user->created_at->format('Y-m-d H:i:s')
                ],
            ]);
        /** Assert team is correct in db */
        $this->assertDatabaseHas('users', [
            'id' => $this->user->id,
            'first_name' => $first_name,
            'last_name' => $last_name,
            'handle' => $handle,
        ]);
    }

    /**
     * Tests Route user.update
     *
     * @test
     */
    public function cannot_update_task_without_first_name()
    {
        /** Act */
        $faker = Faker::create();
        $last_name = $faker->lastName;
        $handle =  $faker->word;
        /** Act */
        $response = $this->json('PUT', "/api/user/".$this->user->id, [
            "last_name" => $last_name,
            "handle" => $handle
        ]);
        /** Assert response is correct */
        $response->assertStatus(422)
                 ->assertJson([
                     'first_name' => ['Please provide a first name']
                 ]);  ;
        /** Assert team is correct in db */
        $this->assertDatabaseHas('users', [
            'id' => $this->user->id,
            'first_name' => $this->user->first_name,
            'last_name' => $this->user->last_name,
            'handle' => $this->user->handle,
        ]);
    }

    /**
     * Tests Route user.update
     *
     * @test
     */
    public function cannot_update_task_without_last_name()
    {
        /** Act */
        $faker = Faker::create();
        $first_name = $faker->firstName;
        $handle =  $faker->word;
        /** Act */
        $response = $this->json('PUT', "/api/user/".$this->user->id, [
            "first_name" => $first_name,
            "handle" => $handle
        ]);
        /** Assert response is correct */
        $response->assertStatus(422)
            ->assertJson([
                'last_name' => ['Please provide a last name']
            ]);
        /** Assert team is correct in db */
        $this->assertDatabaseHas('users', [
            'id' => $this->user->id,
            'first_name' => $this->user->first_name,
            'last_name' => $this->user->last_name,
            'handle' => $this->user->handle,
        ]);
    }

    /**
     * Tests Route user.update
     *
     * @test
     */
    public function cannot_update_task_without_handle()
    {
        /** Act */
        $faker = Faker::create();
        $last_name = $faker->lastName;
        $first_name = $faker->firstName;
        /** Act */
        $response = $this->json('PUT', "/api/user/".$this->user->id, [
            "first_name" => $first_name,
            "last_name" => $last_name
        ]);
        /** Assert response is correct */
        $response->assertStatus(422)
            ->assertJson([
                'handle' => ['Please provide a handle']
            ]);
        /** Assert team is correct in db */
        $this->assertDatabaseHas('users', [
            'id' => $this->user->id,
            'first_name' => $this->user->first_name,
            'last_name' => $this->user->last_name,
            'handle' => $this->user->handle,
        ]);
    }

    /**
     * Tests Route user.update
     *
     * @test
     */
    public function cannot_update_another_user()
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
