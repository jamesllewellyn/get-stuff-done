<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Section extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $table = 'sections';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'project_id', 'name'
    ];

    /**
     * Modal validation.
     * @var array
     */
    public $validation = [
        'name' => 'required'
    ];

    /**
     * Custom validation messages
     * @var array
     */
    public $messages = [
        'name.required' => 'Please provide a name for this section'
    ];

    /**
     * Get all section tasks.
     */
    public function tasks(){
        return $this->hasManyThrough('App\Task', 'App\SectionTask', 'task_id' ,'id' );
    }
}
