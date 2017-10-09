<?php

namespace Tests\Feaure\Section;

use App\UserTeam;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Passport\Passport;
use App\Team;
use App\User;
use App\Project;
use Faker\Factory as Faker;
use Auth;

class SectionCreateTest extends TestCase
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
     * Tests Route section.store
     *
     * @test
     */
    public function can_create_project_section()
    {
        /** Arrange */
        $faker = Faker::create();
        $sectionName = $faker->words(4,true);
        /** Act */
        $response = $this->json('POST', "/api/team/".$this->team->id."/project/".$this->project->id."/section", [
            "name" => $sectionName
        ]);
        /** Assert response is correct */
        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'section has been created'
            ]);
        /** Assert project is correct in db */
        $this->assertDatabaseHas('sections', [
            'name' => $sectionName,
            'project_id' => $this->project->id,
            "deleted_at" => null
        ]);
    }

    /**
     * Tests Route section.store
     *
     * @test
     */
    public function cannot_create_project_section_without_section_name()
    {
        /** Act */
        $response = $this->json('POST', "/api/team/".$this->team->id."/project/".$this->project->id."/section", []);
        /** Assert */
        $response->assertStatus(422)
            ->assertJson([
                'name' => ["Please provide a name for this section"]
            ]);
    }

    /**
     * Tests Route section.store
     *
     * @test
     */
    public function user_not_in_team_cannot_create_project_section()
    {
        /** Arrange */
        $faker = Faker::create();
        $sectionName = $faker->words(4,true);
        /** create new user and don't add to team*/
        Passport::actingAs(
            factory(User::class)->create()
        );
        /** Act */
        $response = $this->json('POST', "/api/team/".$this->team->id."/project/".$this->project->id."/section", [
            "name" => $sectionName
        ]);
        /** Assert 403 status code */
        $response->assertStatus(403);
        /** Assert project has not been created */
        $this->assertDatabaseMissing('sections', [
            'name' => $sectionName,
            'project_id' => $this->project->id,
            "deleted_at" => null
        ]);
    }
}
