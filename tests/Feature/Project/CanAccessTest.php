<?php

namespace Tests\Feaure\Project;

use App\UserTeam;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Passport\Passport;
use App\Team;
use App\User;
use App\Project;
use Auth;

class CanAccessTest extends TestCase
{
    use DatabaseTransactions;
    protected $team;
    protected $project;

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
        /** create new project */
        $this->project = factory(Project::class)->create([
            'team_id' => $this->team->id
        ]);
    }

    /**
     * Tests ProjectController::canAccess
     *
     * @test
     */
    public function can_access_project_returns_true_for_project_in_team_user_is_a_member_of()
    {
        /** Act */
        $response = $this->json('GET', "/api/team/".$this->team->id."/project/".$this->project->id."/can-access");
        /** Assert response is correct */
        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'user can access project',
                "project" => [
                    "id" => $this->project->id,
                    "team_id" => $this->project->team_id,
                    "name" => $this->project->name,
                    "created_at" => $this->project->created_at->format('Y-m-d H:i:s'),
                    "updated_at" => $this->project->updated_at->format('Y-m-d H:i:s'),
                    "deleted_at" => null
                ]
            ]);
    }

    /**
     * Tests ProjectController::canAccess
     *
     * @test
     */
    public function can_access_project_returns_false_for_project_in_team_user_is_not_a_member_of()
    {
        /** Arrange */
        /** create and act as new user not a member of $this->team */
        Passport::actingAs(
            factory(User::class)->create()
        );
        /** Act */
        $response = $this->json('GET', "/api/team/".$this->team->id."/project/".$this->project->id."/can-access");
        /** Assert response is correct */
        $response->assertStatus(403);
    }

}
