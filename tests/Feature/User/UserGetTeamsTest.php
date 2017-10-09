<?php

namespace Tests\Feaure\User;

use App\Project;
use App\UserTeam;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Passport\Passport;
use App\Team;
use App\User;
use Auth;

class UserGetTeamsTest extends TestCase
{
    use DatabaseTransactions;
    protected $user;
    protected $teams;

    protected function setUp()
    {
        parent::setUp();
        /** create and act as user */
        $this->user = factory(User::class)->create();

        Passport::actingAs(
            $this->user
        );
        /** set up new teams */
        $this->teams = factory(Team::class, 3)->create();
        /** add user to teams */
        foreach ($this->teams->toArray() as $team){
            factory(UserTeam::class)->create([
                'user_id' => Auth::user()->id,
                'team_id' => $team['id'],
            ]);
        }
    }

    /**
     * Tests Route user.teams
     *
     * @test
     */
    public function can_get_user_teams()
    {
        /** Act */
        $response = $this->json('GET', "/api/user/".$this->user->id."/teams");
        /** Assert response is correct */
        $response->assertStatus(200)
                  ->assertJson(
                      $this->teams->toArray()
                  );
        /** Assert teams returned belong to user in db */
        foreach ($this->teams->toArray() as $team){
            $this->assertDatabaseHas('user_teams', [
                'user_id' => $this->user->id,
                'team_id' => $team['id']
            ]);
        }
    }

    /**
     * Tests Route user.teams
     *
     * @test
     */
    public function can_get_user_teams_with_projects()
    {
        /** Arrange Add projects */
        $projects = factory(Project::class, 3)->create([
            'team_id' => $this->teams->first()->id
        ]);
        /** Act */
        $response = $this->json('GET', "/api/user/".$this->user->id."/teams");
        /** Assert response is correct */
        $response->assertStatus(200)
                 ->assertJson(
                     $this->teams->toArray()
                 );
        /** decode returned projects */
        $returnedProjects = $response->decodeResponseJson()[0]['projects'];
        /** assert projects were returned */
        $this->assertNotEmpty($returnedProjects);
        /** loop returned projects and assert they within the add projects array*/
        foreach ($returnedProjects as $returnedProject){
            $this->assertContains(array_diff_key($returnedProject,["deleted_at" => null]),$projects->toArray());
            /** Assert projects returned belong to team in db */
            $this->assertDatabaseHas('projects', [
                'id' => $returnedProject['id'],
                'team_id' => $this->teams->first()->id
            ]);
        }
    }

    /**
     * Tests Route user.teams
     *
     * @test
     */
    public function can_get_user_teams_with_other_users()
    {
        /** Arrange Add user to first team */
        $users = factory(User::class, 3)->create();
        foreach ($users as $user){
            factory(UserTeam::class)->create([
                'user_id' => $user->id,
                'team_id' => $this->teams[0]->id,
            ]);
        }
        /** add current user to $user array as they will also be returned */
        $users->push($this->user);
        /** Act */
        $response = $this->json('GET', "/api/user/".$this->user->id."/teams");
        /** Assert response is correct */
        $response->assertStatus(200);
        /** decode returned first teams users */
        $returnedUsers = $response->decodeResponseJson()[0]['users'];
        /** assert users were returned */
        $this->assertNotEmpty($returnedUsers);
        /** loop returned user and assert they within the added users array*/
        foreach ($returnedUsers as $returnedUser){
            /** assert returned user matches a user in users array minus pivot and current_team_id indexes */
            $this->assertContains(array_diff_key($returnedUser,["pivot" => null, 'current_team_id' => null]),$users->toArray());
            /** Assert returned user belongs to team in db */
            $this->assertDatabaseHas('user_teams', [
                'user_id' => $returnedUser['id'],
                'team_id' => $this->teams->first()->id
            ]);
        }
    }

    /**
     * Tests Route user.teams
     *
     * @test
     */
    public function cannot_get_another_users_teams()
    {
        /** Arrange create new user */
        $user = factory(User::class)->create();
        /** Act */
        /** try and access new users teams */
        $response = $this->json('GET', "/api/user/".$user->id."/teams");
        /** Assert response is Forbidden */
        $response->assertStatus(403);
    }
}
