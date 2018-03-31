<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model {

    use SoftDeletes;
    protected $dates = ['deleted_at'];
    public $table = "comments";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'comment', 'user_id', 'task_id'
    ];

    /**
     * Modal validation.
     *
     * @var array
     */
    public $validation = [
        'comment' => 'required'
    ];

    /**
     * Custom validation messages
     *
     * @var array
     */
    public $messages = [
        'comment.required' => 'Please provide a comment',
    ];

    /***********************
     * Eloquent Relationships
     **********************/
    /**
     * Get section that the task is in.
     */
    public function task(){
        return $this->belongsTo(Task::class, 'task_id', 'id');
    }

    /**
     * Get task status.
     */
    public function author(){
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}