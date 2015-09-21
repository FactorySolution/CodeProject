<?php

namespace CodeProject\Http\Controllers;

use CodeProject\Repositories\ProjectTaskRepository;
use CodeProject\Services\ProjectTaskService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

use CodeProject\Http\Requests;


class ProjectTaskController extends Controller
{
    /**
     * @var ProjectTaskRepository
     */
    private $repository;
    /**
     * @var ProjectTaskService
     */
    private $service;

    /**
     * ProjectTaskController constructor.
     */
    public function __construct(ProjectTaskRepository $repository, ProjectTaskService $service)
    {
        $this->repository = $repository;
        $this->service = $service;
    }

    public function index()
    {
        return $this->repository->all();
    }

    public function show($id)
    {
        try {
            return $this->repository->find($id);
        }catch (ModelNotFoundException $e)
        {
            return [
                'error' => true,
                'message' => 'Nao foi possivel encontrar o registro'
            ];
        }
    }

    public function store(Request $request)
    {
        return $this->repository->create($request->all());
    }

    public function update(Request $request, $id)
    {
        return $this->service->update($request->all(), $id);
    }

    public function destroy($id)
    {
        $result = $this->repository->find($id)->delete();

        if ($result)
            return ['error' => false];

        return ['error' => true,
        'message' => 'erro ao deletar uma task'];
    }

}
