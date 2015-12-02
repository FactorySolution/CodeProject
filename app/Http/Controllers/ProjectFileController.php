<?php

namespace CodeProject\Http\Controllers;
use CodeProject\Services\ProjectService;
use CodeProject\Repositories\ProjectRepository;
use Illuminate\Http\Request;


class ProjectFileController extends Controller
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
     * @param ProjectService $service
     */
    public function __construct(ProjectRepository $repository,
                                ProjectService $service)
    {
        $this->repository = $repository;
        $this->service = $service;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        if (!$request->has('file')){
            return [
                'error' => true,
                'message' => 'Por favor, insira um arquivo'
            ];
        }


        $file = $request->file('file');
        $extension  = $file->getClientOriginalExtension();
        $data = [
            'file' => $file,
            'extension' => $extension,
            'name' => $request->name,
            'description' => $request->description,
            'project_id' => $request->project_id
        ];


        return $this->service->createFile($data);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        if(!$this->checkProjectOwner($id))
            return ['error' => 'Access Forbidden'];


        return $this->service->destroy($id);
    }


}