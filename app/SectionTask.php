<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SectionTask extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $table = 'section_tasks';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'section_id', 'task_id'
    ];
}
