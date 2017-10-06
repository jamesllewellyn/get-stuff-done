<?php

namespace Tests\Feaure\Section;

use App\UserTeam;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Passport\Passport;
use App\Team;
use App\User;
use App\Project;
use App\Section;
use Auth;

class DeleteTest extends TestCase
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
     * Tests Route section.delete
     *
     * @test
     */
    public function can_delete_project_section()
    {
        /** Arrange */
        /** create new project section*/
        $section = factory(Section::class)->create([
            'project_id' => $this->project->id
        ]);
        /** Act */
        $response = $this->json('DELETE', "/api/team/".$this->team->id."/project/".$this->project->id."/section/".$section->id);
        /** Assert response is correct */
        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Section '.$section->name.' has been successfully deleted'
            ]);
        /** assert project is soft deleted */
        $this->assertSoftDeleted('sections', [
            'id' => $section->id
        ]);
    }

    /**
     * Tests Route section.delete
     *
     * @test
     */
    public function cannot_delete_project_section_in_team_user_is_not_a_member_of()
    {
        /** Arrange */
        /** create new project section*/
        $section = factory(Section::class)->create([
            'project_id' => $this->project->id
        ]);
        /** create new user and don't add to team*/
        Passport::actingAs(
            factory(User::class)->create()
        );
        /** Act */
        $response = $this->json('DELETE', "/api/team/".$this->team->id."/project/".$this->project->id."/section/".$section->id);
        /** Assert response is correct */
        $response->assertStatus(403);
        /** assert section is not deleted */
        $this->assertDatabaseHas('sections', [
            'id' => $section->id
        ]);
    }

}
