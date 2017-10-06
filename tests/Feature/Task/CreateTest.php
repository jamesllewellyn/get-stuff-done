<?php

namespace Tests\Feaure\Task;

use App\UserTeam;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Notification;
use App\Notifications\UserAssignedToTask;
use Laravel\Passport\Passport;
use App\Team;
use App\User;
use App\Project;
use App\Section;
use Faker\Factory as Faker;
use Auth;

class CreateTest extends TestCase
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
        /** create new project section */
        $this->section = factory(Section::class)->create([
            'project_id' => $this->project->id
        ]);
    }

    /**
     * Tests Route task.store
     *
     * @test
     */
    public function can_create_task()
    {
        /** Arrange */
        $faker = Faker::create();
        $name = $faker->words(4,true);
        $priority_id = [  "id" => 2, "name" => "Medium" ];
        $due_date = $faker->date('Y-m-d');
        $note = $faker->paragraph;
        /** Act */
        $response = $this->json('POST', "/api/team/".$this->team->id."/project/".$this->project->id."/section/".$this->section->id."/task", [
            "name" => $name,
            "priority_id" => $priority_id,
            "due_date" => $due_date,
            "note" => $note,
        ]);
        /** Assert response is correct */
        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'New task has been added to '.$this->section->name
            ]);
        /** Assert project is correct in db */
        $this->assertDatabaseHas('tasks', [
            "name" => $name,
            "priority_id" => $priority_id['id'],
            "due_date" => $due_date,
            "note" => $note,
        ]);
    }

    /**
     * Tests Route task.store
     *
     * @test
     */
    public function cannot_create_task_without_task_name()
    {
        /** Arrange */
        $faker = Faker::create();
        $priority_id = [  "id" => 2, "name" => "Medium" ];
        $due_date = $faker->date('Y-m-d');
        $note = $faker->paragraph;
        /** Act */
        $response = $this->json('POST', "/api/team/".$this->team->id."/project/".$this->project->id."/section/".$this->section->id."/task", [
            "priority_id" => $priority_id,
            "due_date" => $due_date,
            "note" => $note,
        ]);
        /** Assert response is correct */
        $response->assertStatus(422)
            ->assertJson([
                'name' => ["Please provide a name for this task"]
            ]);
    }

    /**
     * Tests Route task.store
     *
     * @test
     */
    public function cannot_create_task_without_due_date()
    {
        /** Arrange */
        $faker = Faker::create();
        $name = $faker->words(4,true);
        $priority_id = ["id" => 2, "name" => "Medium"];
        $note = $faker->paragraph;
        /** Act */
        $response = $this->json('POST', "/api/team/".$this->team->id."/project/".$this->project->id."/section/".$this->section->id."/task", [
            "name" => $name,
            "priority_id" => $priority_id,
            "note" => $note,
        ]);
        /** Assert response is correct */
        $response->assertStatus(422)
            ->assertJson([
                'due_date' => ["Please provide a due date for this task"]
            ]);
    }

    /**
     * Tests Route task.store
     *
     * @test
     */
    public function cannot_create_task_without_priority_id()
    {
        /** Arrange */
        $faker = Faker::create();
        $name = $faker->words(4,true);
        $due_date = $faker->date('Y-m-d');
        $note = $faker->paragraph;
        /** Act */
        $response = $this->json('POST', "/api/team/".$this->team->id."/project/".$this->project->id."/section/".$this->section->id."/task", [
            "name" => $name,
            "due_date" => $due_date,
            "note" => $note,
        ]);
        /** Assert response is correct */
        $response->assertStatus(422)
            ->assertJson([
                'priority_id' => ["Please give a priority to this task"]
            ]);
    }

    /**
     * Tests Route task.store
     *
     * @test
     */
    public function can_create_task_and_assign_to_user()
    {
        /** Arrange */
        Notification::fake();
        $faker = Faker::create();
        $name = $faker->words(4,true);
        $priority_id = [  "id" => 2, "name" => "Medium" ];
        $due_date = $faker->date('Y-m-d');
        $note = $faker->paragraph;
        /** create user */
        $user = factory(User::class)->create();
        /** add user to team */
        factory(UserTeam::class)->create([
            'user_id' => $user->id,
            'team_id' => $this->team->id,
        ]);
        /** Act */
        $response = $this->json('POST', "/api/team/".$this->team->id."/project/".$this->project->id."/section/".$this->section->id."/task", [
            "name" => $name,
            "priority_id" => $priority_id,
            "due_date" => $due_date,
            "note" => $note,
            "users" => [0 => $user]
        ]);
        /** Assert response is correct */
        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'New task has been added to '.$this->section->name
            ]);
        /** Assert task is correct in db */
        $this->assertDatabaseHas('tasks', [
            "name" => $name,
            "priority_id" => $priority_id['id'],
            "due_date" => $due_date,
            "note" => $note,
        ]);
        $createdTask = $response->decodeResponseJson()['task'];
        /** Assert user is assigned to task */
        $this->assertDatabaseHas('user_tasks', [
            "task_id" => $createdTask['id'],
            "user_id" => $user->id,
        ]);
        /** assert user has been sent notification */
        Notification::assertSentTo(
            [$user], UserAssignedToTask::class
        );
    }

    /**
     * Tests Route task.store
     *
     * @test
     */
    public function user_not_in_team_cannot_create_task()
    {
        /** Arrange */
        /** create new user and don't add to team*/
        Passport::actingAs(
            factory(User::class)->create()
        );
        $faker = Faker::create();
        $name = $faker->words(4,true);
        $priority_id = [  "id" => 2, "name" => "Medium" ];
        $due_date = $faker->date('Y-m-d');
        $note = $faker->paragraph;
        /** Act */
        $response = $this->json('POST', "/api/team/".$this->team->id."/project/".$this->project->id."/section/".$this->section->id."/task", [
            "name" => $name,
            "priority_id" => $priority_id,
            "due_date" => $due_date,
            "note" => $note,
        ]);
        /** Assert response is correct */
        $response->assertStatus(403);
        /** Assert project is correct in db */
        $this->assertDatabaseMissing('tasks', [
            "name" => $name,
            "priority_id" => $priority_id['id'],
            "due_date" => $due_date,
            "note" => $note,
        ]);
    }
}
