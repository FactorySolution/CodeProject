<?php
/**
 * Created by PhpStorm.
 * User: Andre
 * Date: 08/09/15
 * Time: 21:12
 */

namespace CodeProject\Services;


use CodeProject\Entities\Project;
use CodeProject\Entities\ProjectMember;
use CodeProject\Entities\User;
use CodeProject\Repositories\ProjectMemberRepository;
use CodeProject\Repositories\ProjectRepository;
use CodeProject\Validators\ProjectValidator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
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


    public function addMember($id, $memberId)
    {
        try {
            if (Project::findOrFail($id) && User::findOrFail($memberId)) {
                if (DB::table('project_members')->where('project_id', $id)->where('user_id', $memberId)->count() > 0) {
                    return response()->json([
                        "error" => true,
                        "message" => "The User {$memberId} is already member of the project {$id}."
                    ]);
                }
                return ['sucess' => true];
            }
        } catch (ModelNotFoundException $e) {
            return response()->json([
                "error" => true,
                "message" => $e->getMessage()
            ]);
        }
    }

    public function removeMember($id, $memberId)
    {
        try {
            if (ProjectMember::where("project_id", $id)->where("user_id", $memberId)->firstOrFail()) {
                if (DB::table('project_members')->where('project_id', $id)->where('user_id', $memberId)->count() > 0) {
                    return ['success' => true];
                }
            }
        } catch (ModelNotFoundException $e) {
            return response()->json([
                "error" => true,
                "message" => $e->getMessage()
            ], 404);
        }
    }

    public function isMember($id, $memberId)
    {
        try {

            if (ProjectMember::where("project_id", $id)->where("user_id", $memberId)->firstOrFail()) {
                return ['member' => true];
            }
        } catch (ModelNotFoundException $e) {
            return response()->json([
                "error" => true,
                "message" => $e->getMessage()
            ]);
        }

    }
}