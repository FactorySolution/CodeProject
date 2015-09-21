<?php

namespace CodeProject\Http\Controllers;

use CodeProject\Repositories\ProjectMemberRepository;
use CodeProject\Http\Requests;
use CodeProject\Services\ProjectService;
use Illuminate\Support\Facades\Request;


class ProjectMemberController extends Controller
{
    /**
     * @var ProjectMemberRepository
     */
    private $repository;
    /**
     * @var ProjectService
     */
    private $service;

    /**
     * ProjectMemberController constructor.
     */
    public function __construct(ProjectMemberRepository $repository, ProjectService $service)
    {
        $this->repository = $repository;
        $this->service = $service;
    }

    public function members($id)
    {
        return $this->repository->with(['user'])->findWhere(['project_id' => $id]);
    }

    public function isMember($id, $userId){
        return $this->service->isMember($id, $userId);
    }

    public function addMember($id, $memberId)
    {
        return $this->service->addMember($id, $memberId);
    }

    public function removeMember($id, $userId)
    {
        return $this->service->removeMember($id, $userId);
    }
}
