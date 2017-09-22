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
        'name', 'team_id'
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

    public function team(){
        return $this->belongsTo(Team::class);
    }
    /**
     * Get all project sections.
     */
    public function sections(){
        return $this->hasMany(Section::class, 'project_id', 'id' );
    }
    /**
     * Get all project tasks.
     */
    public function getTasks(){
        $sections = Section::where('project_id',$this->id)->get();
        $sectionIds = $sections->pluck('id');
        return Task::whereIn('section_id',$sectionIds)->get();
    }
    /** format tasks into overview */
    public function getOverview(){
        $tasks = $this->getTasks();
        foreach ($tasks as $task){
            
        }
    }
}
