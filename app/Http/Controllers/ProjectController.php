<?php

namespace CodeProject\Http\Controllers;

use CodeProject\Repositories\ProjectMemberRepository;
use CodeProject\Services\ProjectService;
use CodeProject\Repositories\ProjectRepository;
use Illuminate\Http\Request;


class ProjectController extends Controller
{
    /**
     * @var ProjectRepository
     */

    private $repository;
    /**
     * @var ProjectService
     */
    private $service;
    /**
     * @var ProjectMemberRepository
     */
    private $repositoryMember;


    /**
     * @param  ProjectRepository $repository
     * @param ClientService $service
     */
    public function __construct(ProjectRepository $repository, ProjectService $service, ProjectMemberRepository $repositoryMember)
    {
        $this->repository = $repository;
        $this->service = $service;
        $this->repositoryMember = $repositoryMember;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return $this->service->getAll();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        return $this->service->create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        return $this->service->show($id);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        return $this->service->update($request->all(), $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
       return $this->service->destroy($id);
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

    public function members($id)
    {
        return $this->repositoryMember->with(['user'])->findWhere(['project_id' => $id]);
    }
}