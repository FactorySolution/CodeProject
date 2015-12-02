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
use CodeProject\Repositories\ProjectRepository;
use CodeProject\Validators\ProjectFileValidator;
use CodeProject\Validators\ProjectValidator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

use Prettus\Validator\Exceptions\ValidatorException;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Contracts\Filesystem\Factory as Storage;

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
     * @var Filesystem
     */
    private $filesystem;
    /**
     * @var Storage
     */
    private $storage;
    /**
     * @var ProjectFileValidator
     */
    private $projectFileValidator;


    public function __construct(ProjectRepository $repository,
                                ProjectValidator $validator,
                                Filesystem $filesystem,
                                Storage $storage,
                                 ProjectFileValidator $projectFileValidator
    ){
        $this->repository = $repository;
        $this->validator = $validator;
        $this->filesystem = $filesystem;
        $this->storage = $storage;
        $this->projectFileValidator = $projectFileValidator;
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


    public function addMember($id, $userId)
    {
        try
        {
            $this->repository->find($id)->members()->attach($userId);
            return ['success' => true];
        }
        catch (\Exception $e)
        {
            return [
                "error" => true,
                "message" => $e->getMessage()
            ];
        }
    }

    public function removeMember($id, $userId)
    {
        try
        {
            $this->repository->find($id)->members()->detach($userId);
            return ['success' => true];
        }
        catch (\Exception $e)
        {
            return [
                "error" => true,
                "message" => $e->getMessage()
            ];
        }
    }

    public function isMember($id, $userId)
    {
        $check = ProjectMember::where("project_id", $id)->where("user_id", $userId)->count();
        if($check < 1){
            return ['success' => false];
        }
        return ['success' => true];

    }

    /**
     * Verifica se o usuário logado é o dono do project
         * @param  integer $projectId id do projeto
         * @return boolean            se o usuário é ou não dono do projecto
         */
        public function checkProjectOwner($projectId)
    {
        $userId = \Authorizer::getResourceOwnerId();
        return $this->repository->isOwner($projectId, $userId);
    }
        /**
         * Verifica se o usuário logado é o membro do projecto
         * @param  integer $projectId id do projeto
         * @return boolean
         */

    /**
     * Get all members of a project
     * @param  integer $id projectId
     * @return json
     */
    public function members($id)
    {
        try
        {
            $members = $this->repository->skipPresenter()->find($id)->members;
            if(count($members))
                return $members;
            return [
                'error' => false,
                'message' => 'Não existem membros neste projeto'
            ];
        }
        catch (\Exception $e)
        {
            return [
                "error" => true,
                "message" => $e->getMessage()
            ];
        }
    }

    public function checkProjectMember($projectId)
    {

        $userId = \Authorizer::getResourceOwnerId();
        return $this->repository->hasMember($projectId, $userId);
    }
        /**
         * Verifica se o usuário possui permissão de acesso a um projeto
         * @param  integer $projectId id do projeto
         * @return boolean
         */
    public function checkProjectPermissions($projectId)
    {
        if($this->checkProjectOwner($projectId) || $this->checkProjectMember($projectId))
            return true;
        return false;
    }

    /**
     * Inserir arquivo a um projeto
     * @param  array  $data dados enviados
     * @return [type]       [description]
     */
    public function createFile(array $data)
    {
        try
        {
            $this->projectFileValidator->with($data)->passesOrFail();
            $project = $this->repository->skipPresenter()->find($data['project_id']);
            $projectFile = $project->files()->create($data);
            $this->storage->put($projectFile->id.'.'.$data['extension'], $this->filesystem->get($data['file']));
            return ['success' => true];
        }
        catch (\Exception $e)
        {
            return [
                "error" => true,
                "message" => $e->getMessage()
            ];
        }
    }
    public function deleteFile($projectId)
    {
        $files = $this->repository->skipPresenter()->find($projectId)->files;
        $deletar = [];
        foreach ($files as $file) {
            $path = $file->id . '.' . $file->extension;
            if($file->delete($file->id))
                $deletar[] = $path;
        }
        $return = $this->storage->delete($deletar);
        if($return)
            return ['error' => false];
        else
            return ['error' => true];
    }


}