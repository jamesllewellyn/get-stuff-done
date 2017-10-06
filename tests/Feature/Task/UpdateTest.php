<?php

namespace Tests\Feaure\Task;

use App\UserTeam;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Passport\Passport;
use App\Team;
use App\User;
use App\Project;
use App\Task;
use App\Section;
use Faker\Factory as Faker;
use Auth;

class UpdateTest extends TestCase
{
    use DatabaseTransactions;
    protected $team;
    protected $project;
    protected $section;
    protected $task;

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
        /** create new task and add to section */
        $this->task = factory(Task::class)->create([
            'section_id' => $this->section->id,
            'sort_order' => 1
        ]);
    }

    /**
     * Tests Route task.store
     *
     * @test
     */
    public function can_update_task()
    {
        /** Arrange */
        $faker = Faker::create();
        $name = $faker->words(4,true);
        $priority_id = $faker->numberBetween(1,3);
        $status_id = $faker->numberBetween(1,2);
        $due_date = $faker->date('Y-m-d');
        $note = $faker->paragraph;
        /** Act */
        $response = $this->json('PUT', "/api/team/".$this->team->id."/project/".$this->project->id."/section/".$this->section->id."/task/".$this->task->id, [
            "name" => $name,
            "priority_id" => $priority_id,
            "due_date" => $due_date,
            "status_id" => $status_id,
            "note" => $note,
            "sort_order" => 1,
            "users" => []
        ]);
        /** Assert response is correct */
        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'task has been updated',
                'task' => [
                    "id"=> $this->task->id,
                    "priority_id"=> $priority_id,
                    "section_id"=> $this->task->section_id,
                    "name"=>  $name,
                    "note"=>  $note,
                    "sort_order"=>  $this->task->sort_order,
                    "status_id"=>  $status_id,
                    "due_date"=>  $due_date,
                    "due_time"=>  $this->task->due_time,
                    "created_by_id"=>  $this->task->created_by_id,
                    "created_at"=>  $this->task->created_at->format('Y-m-d H:i:s'),
                    "deleted_at"=>   null
                ]
            ]);
        /** Assert task is correct in db */
        $this->assertDatabaseHas('tasks', [
            "id"=> $this->task->id,
            "priority_id"=> $priority_id,
            "section_id"=> $this->task->section_id,
            "name"=>  $name,
            "note"=>  $note,
            "sort_order"=>  $this->task->sort_order,
            "status_id"=>  $status_id,
            "due_date"=>  $due_date,
            "due_time"=>  $this->task->due_time,
            "created_by_id"=>  $this->task->created_by_id,
            "created_at"=>  $this->task->created_at->format('Y-m-d H:i:s'),
            "deleted_at"=>   null
        ]);
    }

    /**
     * Tests Route task.store
     *
     * @test
     */
    public function cannot_update_task_without_name()
    {
        /** Arrange */
        $faker = Faker::create();
        $priority_id = $faker->numberBetween(1,3);
        $status_id = $faker->numberBetween(1,2);
        $due_date = $faker->date('Y-m-d');
        $note = $faker->paragraph;
        /** Act */
        $response = $this->json('PUT', "/api/team/".$this->team->id."/project/".$this->project->id."/section/".$this->section->id."/task/".$this->task->id, [
            "priority_id" => $priority_id,
            "due_date" => $due_date,
            "status_id" => $status_id,
            "note" => $note,
            "sort_order" => 1,
            "users" => []
        ]);
        /** Assert response is correct */
        $response->assertStatus(422)
                 ->assertJson([
                     'name' => ['Please provide a name for this task']
                 ]);
        /** Assert task is  db hasn't been updated */
        $this->assertDatabaseHas('tasks', [
            "id"=> $this->task->id,
            "priority_id"=> $this->task->priority_id,
            "section_id"=> $this->task->section_id,
            "name"=>  $this->task->name,
            "note"=>  $this->task->note,
            "sort_order"=>  $this->task->sort_order,
            "status_id"=>  $this->task->status_id,
            "due_date"=>  $this->task->due_date,
            "due_time"=>  $this->task->due_time,
            "created_by_id"=>  $this->task->created_by_id,
            "created_at"=>  $this->task->created_at->format('Y-m-d H:i:s'),
            "deleted_at"=>   null
        ]);
    }

    /**
     * Tests Route task.store
     *
     * @test
     */
    public function cannot_update_task_without_due_date()
    {
        /** Arrange */
        $faker = Faker::create();
        $name = $faker->words(4,true);
        $priority_id = $faker->numberBetween(1,3);
        $status_id = $faker->numberBetween(1,2);
        $note = $faker->paragraph;
        /** Act */
        $response = $this->json('PUT', "/api/team/".$this->team->id."/project/".$this->project->id."/section/".$this->section->id."/task/".$this->task->id, [
            "priority_id" => $priority_id,
            "status_id" => $status_id,
            "note" => $note,
            "name" => $name,
            "sort_order" => 1,
            "users" => []
        ]);
        /** Assert response is correct */
        $response->assertStatus(422)
            ->assertJson([
                'due_date' => ['Please provide a due date for this task']
            ]);
        /** Assert task is  db hasn't been updated */
        $this->assertDatabaseHas('tasks', [
            "id"=> $this->task->id,
            "priority_id"=> $this->task->priority_id,
            "section_id"=> $this->task->section_id,
            "name"=> $this->task->name,
            "note"=> $this->task->note,
            "sort_order"=> $this->task->sort_order,
            "status_id"=> $this->task->status_id,
            "due_date"=> $this->task->due_date,
            "due_time"=> $this->task->due_time,
            "created_by_id"=> $this->task->created_by_id,
            "created_at"=> $this->task->created_at->format('Y-m-d H:i:s'),
            "deleted_at"=> null
        ]);
    }

    /**
     * Tests Route task.store
     *
     * @test
     */
    public function cannot_update_task_without_priority_id()
    {
        /** Arrange */
        $faker = Faker::create();
        $name = $faker->words(4,true);
        $status_id = $faker->numberBetween(1,2);
        $due_date = $faker->date('Y-m-d');
        $note = $faker->paragraph;
        /** Act */
        $response = $this->json('PUT', "/api/team/".$this->team->id."/project/".$this->project->id."/section/".$this->section->id."/task/".$this->task->id, [
            "due_date" => $due_date,
            "status_id" => $status_id,
            "note" => $note,
            "name" => $name,
            "sort_order" => 1,
            "users" => []
        ]);
        /** Assert response is correct */
        $response->assertStatus(422)
            ->assertJson([
                'priority_id' => ['Please give a priority to this task']
            ]);
        /** Assert task is  db hasn't been updated */
        $this->assertDatabaseHas('tasks', [
            "id"=> $this->task->id,
            "priority_id"=> $this->task->priority_id,
            "section_id"=> $this->task->section_id,
            "name"=> $this->task->name,
            "note"=> $this->task->note,
            "sort_order"=> $this->task->sort_order,
            "status_id"=> $this->task->status_id,
            "due_date"=> $this->task->due_date,
            "due_time"=> $this->task->due_time,
            "created_by_id"=> $this->task->created_by_id,
            "created_at"=> $this->task->created_at->format('Y-m-d H:i:s'),
            "deleted_at"=> null
        ]);
    }

    /**
     * Tests Route task.store
     *
     * @test
     */
    public function cannot_update_task_in_team_user_is_not_a_member_of()
    {
        /** Arrange */
        $faker = Faker::create();
        $name = $faker->words(4,true);
        $priority_id = $faker->numberBetween(1,3);
        $status_id = $faker->numberBetween(1,2);
        $due_date = $faker->date('Y-m-d');
        $note = $faker->paragraph;
        /** create new user and don't add to team*/
        Passport::actingAs(
            factory(User::class)->create()
        );
        /** Act */
        $response = $this->json('PUT', "/api/team/".$this->team->id."/project/".$this->project->id."/section/".$this->section->id."/task/".$this->task->id, [
            "name" => $name,
            "priority_id" => $priority_id,
            "due_date" => $due_date,
            "status_id" => $status_id,
            "note" => $note,
            "sort_order" => 1,
            "users" => []
        ]);
        /** Assert response is correct */
        $response->assertStatus(403);
        /** Assert task is correct in db */
        $this->assertDatabaseHas('tasks', [
            "id"=> $this->task->id,
            "priority_id"=> $this->task->priority_id,
            "section_id"=> $this->task->section_id,
            "name"=> $this->task->name,
            "note"=> $this->task->note,
            "sort_order"=> $this->task->sort_order,
            "status_id"=> $this->task->status_id,
            "due_date"=> $this->task->due_date,
            "due_time"=> $this->task->due_time,
            "created_by_id"=> $this->task->created_by_id,
            "created_at"=> $this->task->created_at->format('Y-m-d H:i:s'),
            "deleted_at"=> null
        ]);
    }
}
