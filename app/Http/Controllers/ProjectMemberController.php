<?php

namespace CodeProject\Http\Controllers;

use CodeProject\Repositories\ProjectMemberRepository;
use CodeProject\Http\Requests;


class ProjectMemberController extends Controller
{
    /**
     * @var ProjectMemberRepository
     */
    private $repository;

    /**
     * ProjectMemberController constructor.
     */
    public function __construct(ProjectMemberRepository $repository)
    {
        $this->repository = $repository;
    }

    public function members($id)
    {
        return $this->repository->with(['user'])->findWhere(['project_id' => $id]);
    }
}
