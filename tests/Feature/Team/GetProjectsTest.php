<?php

namespace Tests\Feaure\Team;

use App\UserTeam;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Passport\Passport;
use App\Team;
use App\User;
use App\Project;
use Auth;
use Faker\Factory as Faker;

class GetProjectsTest extends TestCase
{
    use DatabaseTransactions;
    protected $team;
    protected $projects;

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
        /** create new projects and add to team */
        $this->projects = factory(Project::class, 3)->create([
            'team_id' => $this->team->id
        ]);
    }

    /**
     * Tests TeamController::projects
     *
     * @test
     */
    public function can_get_team_project()
    {
        /** Act */
        $response = $this->json('GET', "/api/team/".$this->team->id."/projects/");
        /** Assert response is correct */
        $response->assertStatus(200)
                ->assertJson([
                    0 => [
                        "id" => $this->projects[0]->id,
                        "team_id" => $this->projects[0]->team_id,
                        "name" => $this->projects[0]->name,
                        "created_at" => $this->projects[0]->created_at->format('Y-m-d H:i:s'),
                        "updated_at" => $this->projects[0]->updated_at->format('Y-m-d H:i:s'),
                        "deleted_at" => null,
                    ],
                    1 => [
                        "id" => $this->projects[1]->id,
                        "team_id" => $this->projects[1]->team_id,
                        "name" => $this->projects[1]->name,
                        "created_at" => $this->projects[1]->created_at->format('Y-m-d H:i:s'),
                        "updated_at" => $this->projects[1]->updated_at->format('Y-m-d H:i:s'),
                        "deleted_at" => null,
                    ],
                    2 => [
                        "id" => $this->projects[2]->id,
                        "team_id" => $this->projects[2]->team_id,
                        "name" => $this->projects[2]->name,
                        "created_at" => $this->projects[2]->created_at->format('Y-m-d H:i:s'),
                        "updated_at" => $this->projects[2]->updated_at->format('Y-m-d H:i:s'),
                        "deleted_at" => null,
                    ]
                ]);
    }

    /**
     * Tests TeamController::projects
     *
     * @test
     */
    public function cannot_get_project_from_team_user_is_not_a_member_of(){
        /** Arrange */
        /** create new user and don't add to team */
        Passport::actingAs(
            factory(User::class)->create()
        );
        /** Act */
        $response = $this->json('GET', "/api/team/".$this->team->id."/projects");
        /** Assert response is correct */
        $response->assertStatus(403);
    }



}
