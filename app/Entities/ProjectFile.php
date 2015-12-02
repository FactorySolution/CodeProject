<?php

namespace CodeProject\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Traits\TransformableTrait;

class ProjectFile extends Model
{
    use TransformableTrait;

    protected $fillable = [
        'name',
        'description',
        'extension',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

}