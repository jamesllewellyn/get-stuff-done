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
        'section_id', 'name', 'due_date',  'due_time', 'priority_id', 'sort_order', 'note', 'status_id'
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
     * Get user assigned to task.
     */
    public function assignedUsers(){
        return $this->belongsToMany(User::class, 'user_tasks')->withTimestamps();
    }
    /**
     * Get section that the task is in.
     */
    public function section(){
        return $this->belongsTo(Section::class, 'section_id', 'id');
    }
    /**
     * Get task status.
     */
    public function status(){
        return $this->hasOne(Status::class);
    }
    /**
     * Get task priority.
     */
    public function priority(){
        return $this->hasOne(Priority::class);
    }
    /** Todo: fix this relationship */
//    public function project(){
//        return $this->section->first()->belongsTo(Project::class, 'project_id','id');
//    }
}
