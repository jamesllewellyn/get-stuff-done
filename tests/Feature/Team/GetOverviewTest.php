<?php

namespace Tests\Feaure\Team;

use App\UserTeam;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Passport\Passport;
use App\Team;
use App\User;
use App\Project;
use App\Task;
use App\Section;
use Auth;
use Carbon\Carbon;

class GetOverviewTest extends TestCase
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
        $this->projects = factory(Project::class, 2)->create([
            'team_id' => $this->team->id
        ]);
        /** create new project sections for fist project*/
        $this->projects[0]->section = factory(Section::class)->create([
            'project_id' => $this->projects->first()->id
        ]);
        /** create new project sections for second project*/
        $this->projects[1]->section = factory(Section::class)->create([
            'project_id' => $this->projects[1]->id
        ]);

        /** create new add tasks to sections */
        $this->projects->first()->section->tasks = factory(Task::class,3)->create([
            'section_id' => $this->projects[0]->section->id,
            /** todo: increment sort order */
            'sort_order' => 1
        ]);
        /** create new add tasks to sections */
        $this->projects[1]->section->tasks = factory(Task::class,3)->create([
            'section_id' => $this->projects[1]->section->id,
            /** todo: increment sort order */
            'sort_order' => 1
        ]);
    }

    /**
     * Tests route team.overview
     *
     * @test
     */
    public function can_get_team_overview()
    {
        /** Act */
        $response = $this->json('GET', "/api/team/".$this->team->id."/overview");
        /** Assert response is correct */
        $response->assertStatus(200);
        $responseOverview = $response->decodeResponseJson()['overview'];
        /** assert project id returned match ids added */
        $this->assertEquals($this->projects[0]->id, $responseOverview[0]['project_id']);
        $this->assertEquals($this->projects[1]->id, $responseOverview[1]['project_id']);
        /** assert number of completed task match number of completed tasks added to project */
        $this->assertEquals($this->projects[0]->section->tasks->where('status_id', 1)->count(), $responseOverview[0]['complete']);
        $this->assertEquals($this->projects[1]->section->tasks->where('status_id', 1)->count(), $responseOverview[1]['complete']);
        /** assert number of tasks set to working on it match number of working on it tasks added to project */
        $this->assertEquals($this->projects[0]->section->tasks->where('status_id', 2)->count(), $responseOverview[0]['working_on']);
        $this->assertEquals($this->projects[1]->section->tasks->where('status_id', 2)->count(), $responseOverview[1]['working_on']);
        /** assert number of over due tasks match number of over due tasks added to project */
        $this->assertEquals($this->projects[0]->section->tasks->where('status_id', '!=', 1)->where('due_date', '<', Carbon::now())->count(), $responseOverview[0]['over_due']);
        $this->assertEquals($this->projects[1]->section->tasks->where('status_id', '!=', 1)->where('due_date', '<', Carbon::now())->count(), $responseOverview[1]['over_due']);
        /** assert number of tasks set to not started match number of not started tasks added to project */
        $this->assertEquals($this->projects[0]->section->tasks->where('status_id', null)->count(), $responseOverview[0]['not_started']);
        $this->assertEquals($this->projects[1]->section->tasks->where('status_id', null)->count(), $responseOverview[1]['not_started']);
    }
}
