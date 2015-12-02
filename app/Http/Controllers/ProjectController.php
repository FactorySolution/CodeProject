<?php

namespace CodeProject\Http\Controllers;

use CodeProject\Repositories\ProjectMemberRepository;
use CodeProject\Services\ProjectService;
use CodeProject\Repositories\ProjectRepository;
use Illuminate\Http\Request;
use LucaDegasperi\OAuth2Server\Facades\Authorizer;


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
     * @param  ProjectRepository $repository
     * @param ClientService $service
     */
    public function __construct(ProjectRepository $repository, ProjectService $service)
    {
        $this->repository = $repository;
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {


        $ownerId = \Authorizer::getResourceOwnerId();

        if($this->checkProjectPermissions($ownerId) == false){
            return ['error' => 'Access Forbidden'];
        }


        return $this->repository->findWhere(['owner_id' => $ownerId]);
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
        if(!$this->service->checkProjectPermissions($id)){
            return ['error' => 'Access Forbidden'];
        }

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
        if(!$this->service->checkProjectPermissions($id))
                return ['error' => 'Access Forbidden'];


        return $this->service->update($request->all(), $id);
    }

    /**
     * Check project members
     * @param  integer $id id of the project
     * @return Response
     */
    public function members($id)
    {
        if(!$this->service->checkProjectPermissions($id))
            return ['error' => 'Access Forbidden'];

        return $this->service->members($id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        if(!$this->service->checkProjectOwner($id))
            return ['error' => 'Access Forbidden'];

        return $this->service->destroy($id);
    }

    public function isMember($id, $userId){

        return $this->service->isMember($id, $userId);
    }

    public function addMember($id, $memberId)
    {
        if(!$this->service->checkProjectPermissions($id))
            return ['error' => 'Access Forbidden'];

        return $this->service->addMember($id, $memberId);
    }

    public function removeMember($id, $userId)
    {
        if(!$this->service->checkProjectOwner($id))
            return ['error' => 'Access Forbidden'];

        return $this->service->removeMember($id, $userId);
    }

}