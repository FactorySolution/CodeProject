<?php
/**
 * Created by PhpStorm.
 * User: Andre
 * Date: 17/09/15
 * Time: 19:16
 */

namespace CodeProject\Services;


use CodeProject\Repositories\ProjectTaskRepository;
use CodeProject\Validators\ProjectTaskValidator;
use Prettus\Validator\Exceptions\ValidatorException;

class ProjectTaskService
{
    /**
     * @var
     */
    private $repository;
    /**
     * @var
     */
    private $validator;


    /**
     * ProjectTaskService constructor.
     */
    public function __construct(ProjectTaskRepository $repository, ProjectTaskValidator $validator)
    {
        $this->repository = $repository;
        $this->validator = $validator;
    }

    public function create(array $data)
    {
        try{

            $this->validator->with($data)->passesOrFail();
            return $this->repository->create($data);

        }catch (ValidatorException $e)
        {
            return[
                'error' => true,
                'message' => $e->getMessageBag()
            ];
        }
    }

    public function update(array $data, $id)
    {
        try{
            $this->validator->with($data)->passesOrFail();
            return $this->repository->update($data, $id);
        }catch (ValidatorException $e)
        {
            return[
                'error' => true,
                'message' => $e->getMessageBag()
            ];
        }
    }
}