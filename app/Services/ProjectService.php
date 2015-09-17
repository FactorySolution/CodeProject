<?php
/**
 * Created by PhpStorm.
 * User: Andre
 * Date: 08/09/15
 * Time: 21:12
 */

namespace CodeProject\Services;


use CodeProject\Entities\Project;
use CodeProject\Repositories\ProjectMemberRepository;
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
    /**
     * @var ProjectMemberRepository
     */
    private $repositoryMember;

    public function __construct(ProjectRepository $repository, ProjectValidator $validator, ProjectMemberRepository $repositoryMember)
    {
        $this->repository = $repository;
        $this->validator = $validator;
        $this->repositoryMember = $repositoryMember;
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
                'message' => 'Nao foi possivel atualizar o projeto'
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
                'message' => 'Nao foi possivel localizar o projeto'
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
                'message'=> 'Nao foi possivel excluir o projeto'
            ];
        }
    }

    public function getAll()
    {
        return $this->repository->with(['client','user'])->all();
    }


    public function addMember(array $data)
    {
        return $this->repositoryMember->create($data);
    }

    public function removeMember($project_id, $user_id)
    {
        return $this->repositoryMember->findWhere(['project_id'=> $project_id, 'user_id' => $user_id])->delete();
    }

    public function isMember($project_id, $user_id)
    {
        $result =  $this->repositoryMember->findWhere(['project_id' => $project_id, 'user_id' => $user_id]);

        if (count($result) > 0)
            return true;

        return false;
    }
}