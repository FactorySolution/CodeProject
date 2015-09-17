<?php
/**
 * Created by PhpStorm.
 * User: Andre
 * Date: 08/09/15
 * Time: 21:12
 */

namespace CodeProject\Services;


use CodeProject\Entities\Project;
use CodeProject\Entities\ProjectNote;
use CodeProject\Repositories\ProjectNoteRepository;
use CodeProject\Validators\ProjectNoteValidator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Prettus\Validator\Exceptions\ValidatorException;

class ProjectNoteService
{
    /**
     * @var ProjectValidator
     */
    protected $repository;
    /**
     * @var ProjectRepository
     */
    private $validator;

    public function __construct(ProjectNoteRepository $repository, ProjectNoteValidator $validator)
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
            if (ProjectNote::findOrFail($id)){
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
                'message' => 'Nao foi possivel atualizar a anotacao do projeto'
            ];
        }
    }

    public function show($id, $noteId)
    {
        try
        {
            return $this->repository->findWhere(['project_id' => $id, 'id' => $noteId]);
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
            if (ProjectNote::findOrFail($id))
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

    public function getAll($id)
    {
        return $this->repository->findWhere(['project_id' => $id]);
    }
}