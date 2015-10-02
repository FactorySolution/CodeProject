<?php

namespace CodeProject\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Traits\TransformableTrait;

class Project extends Model
{
    use TransformableTrait;

    protected $fillable = [
        'owner_id',
        'client_id',
        'name',
        'description',
        'progress',
        'status',
        'due_date'
    ];


    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function note()
    {
        return $this->hasMany(ProjectNote::class);
    }

    public function members()
     {
         return $this->belongsToMany(User::class, 'project_members', 'project_id', 'user_id');
     }


}