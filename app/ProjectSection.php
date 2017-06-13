<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectSection extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $table = 'project_sections';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'project_id', 'section_id'
    ];
}
