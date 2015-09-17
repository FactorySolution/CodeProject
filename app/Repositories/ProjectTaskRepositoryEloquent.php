<?php
/**
 * Created by PhpStorm.
 * User: Andre
 * Date: 17/09/15
 * Time: 19:19
 */

namespace CodeProject\Repositories;


use CodeProject\Entities\ProjectTask;
use Prettus\Repository\Eloquent\BaseRepository;

class ProjectTaskRepositoryEloquent extends BaseRepository implements ProjectTaskRepository
{
    public function model()
    {
        return ProjectTask::class;
    }
}