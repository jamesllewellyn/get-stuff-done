<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $table = 'tasks';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'due_date',  'due_time', 'priority_id', 'sort_order', 'note', 'status_id'
    ];

    /**
     * Modal validation.
     * @var array
     */
    public $validation = [
        'name' => 'required',
        'due_date' => 'required',
        'priority_id' => 'required'
    ];

    /**
     * Custom validation messages
     * @var array
     */
    public $messages = [
        'name.required' => 'Please provide a name for this task',
        'due_date.required' => 'Please provide a due date for this task',
        'priority_id.required' => 'Please give a priority to the task',
    ];

    /**
     * Get all section tasks.
     */
    public function tasks(){
        return $this->hasManyThrough('App\Task', 'App\SectionTask', 'section_id' ,'id' )->orderBy('tasks.sort_order');
    }
    public function section(){
        return $this->sectionTask->belongsTo(Section::class);
    }
    public function sectionTask(){
        return $this->belongsTo(SectionTask::class, 'id','task_id');
    }

    public function project(){
        return $this->section->belongsTo(Project::class);
    }
}
