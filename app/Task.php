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
        'name', 'due_date',  'due_time', 'priority_id', 'sort_order', 'note'
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
}
