<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $table = 'projects';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name'
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
        'name.required' => 'Please provide a name for this project'
    ];
}
