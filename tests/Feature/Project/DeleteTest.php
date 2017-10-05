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

class DeleteTest extends TestCase
{
    use DatabaseTransactions;
    protected $team;
    protected $user;

    protected function setUp(){
        parent::setUp();
        /** create and act as user */
        Passport::actingAs(
            factory(User::class)->create()
        );
        /** add user to protected var */
        $this->user = Auth::user();
        /** set up new team */
        $this->team = factory(Team::class)->create();
        /** add user to team */
        factory(UserTeam::class)->create([
            'user_id' => $this->user->id,
            'team_id' => $this->team->id,
        ]);
    }

    /**
     * Tests ProjectController::destroy
     *
     * @test
     */
    public function can_delete_project()
    {
        /** Arrange */
        /** create new project */
        $project = factory(Project::class)->create([
            'team_id' => $this->team->id
        ]);
        /** Act */
        $response = $this->json('DELETE', "/api/team/".$this->team->id."/project/".$project->id);
        /** Assert response is correct */
        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Project '.$project->name.' has been successfully deleted'
            ]);
        /** assert project is soft deleted */
        $this->assertSoftDeleted('projects', [
            'id' => $project->id
        ]);
    }

    /**
     * Tests ProjectController::destroy
     *
     * @test
     */
    public function cannot_delete_project_in_team_user_is_not_a_member_of()
    {
        /** Arrange */
        /** create new project */
        $project = factory(Project::class)->create([
            'team_id' => $this->team->id
        ]);
        /** create new user and don't add to team*/
        Passport::actingAs(
            factory(User::class)->create()
        );
        /** Act */
        $response = $this->json('DELETE', "/api/team/".$this->team->id."/project/".$project->id);
        /** Assert response is correct */
        $response->assertStatus(403);
        /** assert project is not deleted */
        $this->assertDatabaseHas('projects', [
            'id' => $project->id
        ]);
    }

}
