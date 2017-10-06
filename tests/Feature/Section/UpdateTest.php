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
use Faker\Factory as Faker;
use Auth;

class UpdateTest extends TestCase
{
    use DatabaseTransactions;
    protected $team;
    protected $project;
    protected $section;

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
        /** create new project section*/
        $this->section = factory(Section::class)->create([
            'project_id' => $this->project->id
        ]);
    }

    /**
     * Tests Route section.update
     *
     * @test
     */
    public function can_update_project_section()
    {
        /** Act */
        $faker = Faker::create();
        $sectionName = $faker->words(4,true);
        /** Act */
        $response = $this->json('PUT', "/api/team/".$this->team->id."/project/".$this->project->id."/section/".$this->section->id, [
            "name" => $sectionName
        ]);
        /** Assert response is correct */
        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'section has been updated',
                "section" => [
                    "id" => $this->section->id,
                    "project_id" => $this->project->id,
                    "name" => $sectionName,
                    "created_at" => $this->section->created_at->format('Y-m-d H:i:s'),
                    "updated_at" => $this->section->updated_at->format('Y-m-d H:i:s'),
                    "deleted_at" => null
                ],
            ]);
        /** Assert section is correct in db */
        $this->assertDatabaseHas('sections', [
            'name' => $sectionName,
            'project_id' => $this->project->id
        ]);
    }

    /**
     * Tests Route section.update
     *
     * @test
     */
    public function cannot_update_project_section_without_section_name()
    {
        /** Act */
        $response = $this->json('PUT', "/api/team/".$this->team->id."/project/".$this->project->id."/section/".$this->section->id, []);
        /** Assert response is correct */
        $response->assertStatus(422)
            ->assertJson([
                'name' => ['Please provide a name for this section']
            ]);
        /** Assert section has not been updated in db */
        $this->assertDatabaseHas('sections', [
            'id' => $this->section->id,
            'name' => $this->section->name,
            'project_id' => $this->project->id,
            'updated_at' => $this->section->updated_at->format('Y-m-d H:i:s')
        ]);
    }

    /**
     * Tests Route section.update
     *
     * @test
     */
    public function cannot_update_project_section_in_team_user_is_not_a_member_of()
    {
        /** Arrange */
        $faker = Faker::create();
        $sectionName = $faker->words(4,true);
        /** create new user and don't add to team*/
        Passport::actingAs(
            factory(User::class)->create()
        );
        /** Act */
        $response = $this->json('PUT', "/api/team/".$this->team->id."/project/".$this->project->id."/section/".$this->section->id, [
            "name" => $sectionName
        ]);
        /** Assert response is correct */
        $response->assertStatus(403);
        /** Assert section has not been updated in db */
        $this->assertDatabaseHas('sections', [
            'id' => $this->section->id,
            'name' => $this->section->name,
            'project_id' => $this->project->id,
            'updated_at' => $this->section->updated_at->format('Y-m-d H:i:s')
        ]);
    }
}
