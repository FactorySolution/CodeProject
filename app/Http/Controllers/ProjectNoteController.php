<?php

namespace CodeProject\Http\Controllers;

use CodeProject\Repositories\ProjectNoteRepository;
use CodeProject\Services\ProjectNoteService;
use CodeProject\Services\ProjectService;
use Illuminate\Http\Request;


class ProjectNoteController extends Controller
{
    /**
     * @var ProjectNoteRepository
     */

    private $repository;
    /**
     * @var ProjectNoteService
     */
    private $service;
    /**
     * @var ProjectService
     */
    private $projectService;


    /**
     * @param  ProjectNoteRepository $repository
     * @param ProjectNoteService $service
     */
    public function __construct(ProjectNoteRepository $repository,
                                ProjectNoteService $service,
                                ProjectService $projectService)
    {
        $this->repository = $repository;
        $this->service = $service;
        $this->projectService = $projectService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index($id)
    {
        if(!$this->checkProjectNotePermissions($id))
            return ['error' => 'Access Forbidden'];

        return $this->service->getAll($id);
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
    public function show($id, $noteId)
    {
        if(!$this->checkProjectNotePermissions($id))
            return ['error' => 'Access Forbidden'];

        return $this->service->show($id, $noteId);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id, $noteId)
    {
        $projectNote = $this->repository->skipPresenter()->find($id);
        $projectId = $projectNote->project_id;

        if($this->checkProjectNotePermissions($projectId) == false){
            return ['error' => 'Access Forbidden'];
        }

        return $this->service->update($request->all(), $noteId);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id, $noteId)
    {
       return $this->service->destroy($noteId);
    }

    private function checkProjectNotePermissions($projectId){
        if($this->projectService->checkProjectOwner($projectId) or $this->projectService->checkProjectMember($projectId)){
            return true;
        }
        return false;
    }
}