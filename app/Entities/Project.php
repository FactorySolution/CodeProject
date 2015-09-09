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
        return $this->belongsTo('CodeProject\Entities\Client');
    }
    public function user()
    {
        return $this->belongsTo('CodeProject\Entities\User', 'owner_id');
    }

}