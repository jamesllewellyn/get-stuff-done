<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

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
        /** get all sections in project */
        $sections = Section::where('project_id',$this->id)->get();
        /** pluck section ids from project sections */
        $sectionIds = $sections->pluck('id');
        /** get and return all tasks which belong to array of sectionIds */
        return Task::whereIn('section_id',$sectionIds)->get();
    }
    /** format tasks into overview */
    public function getOverview(){
        /** create new standard class for overview object*/
        $overview = new \stdClass();
        /** get all this projects tasks */
        $tasks = $this->getTasks();
        /** add project id to overview */
        $overview->project_id = $this->id;
        /** count number of tasks that are complete */
        $overview->complete = $tasks->where('status_id', 1)->count();
        /** count number of tasks that are being worked on */
        $overview->working_on = $tasks->where('status_id', 2)->count();
        /** count number of tasks that are over due */
        $overview->over_due = $tasks->where('status_id', '!=', 1)->where('due_date', '<', Carbon::now())->count();
        /** count number of tasks that have bot been started */
        $overview->not_started = $tasks->where('status_id', null)->count();
        /** return overview collection */
        return $overview;
    }
}
