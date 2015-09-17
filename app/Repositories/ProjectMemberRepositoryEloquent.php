<?php
/**
 * Created by PhpStorm.
 * User: Andre
 * Date: 17/09/15
 * Time: 19:04
 */

namespace CodeProject\Repositories;


use CodeProject\Entities\ProjectMember;
use Prettus\Repository\Eloquent\BaseRepository;

class ProjectMemberRepositoryEloquent extends BaseRepository implements ProjectMemberRepository
{
    public function model(){
        return ProjectMember::class;
    }

}