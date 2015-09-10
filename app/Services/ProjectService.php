<?php
/**
 * Created by PhpStorm.
 * User: Andre
 * Date: 08/09/15
 * Time: 21:12
 */

namespace CodeProject\Services;


use CodeProject\Entities\Project;
use CodeProject\Repositories\ProjectRepository;
use CodeProject\Validators\ProjectValidator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Prettus\Validator\Exceptions\ValidatorException;

class ProjectService
{
    /**
     * @var ProjectValidator
     */
    protected $repository;
    /**
     * @var ProjectRepository
     */
    private $validator;

    public function __construct(ProjectRepository $repository, ProjectValidator $validator)
    {
        $this->repository = $repository;
        $this->validator = $validator;
    }

    public function create(array $data)
    {
        try{
            $this->validator->with($data)->passesOrFail();
            return $this->repository->create($data);
        }
        catch(ValidatorException $e)
        {
            return [
                'error' => true,
                'message' => $e->getMessageBag()
            ];
        }
    }

    public function update(array $data, $id)
    {
        try {
            if (Project::findOrFail($id)){
                try {
                    $this->validator->with($data)->passesOrFail();
                    return $this->repository->update($data, $id);
                } catch (ValidatorException $e) {
                    return [
                        'error' => true,
                        'message' => $e->getMessageBag()
                    ];
                }
            }
        }catch (ModelNotFoundException $model)
        {
            return [
                'error' => true,
                'message' => $model->getMessage()
            ];
        }
    }

    public function show($id)
    {
        try
        {
            return $this->repository->with('client','user')->find($id);
        }catch (ModelNotFoundException $model)
        {
            return [
                'error' => true,
                'message' => $model->getMessage()
            ];
        }
    }

    public function destroy($id)
    {
        try {
            if (Project::findOrFail($id))
            {
                return ['success' => $this->repository->delete($id)];
            }
        } catch (ModelNotFoundException $e) {
            return [
                'error' => true,
                'message'=> $e->getMessage()
            ];
        }
    }

    public function getAll()
    {
        return $this->repository->with(['client','user'])->all();
    }
}