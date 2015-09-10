<?php
/**
 * Created by PhpStorm.
 * User: Andre
 * Date: 08/09/15
 * Time: 20:13
 */

namespace CodeProject\Services;


use CodeProject\Entities\Client;
use CodeProject\Repositories\ClientRepository;
use CodeProject\Validators\ClientValidator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Prettus\Validator\Exceptions\ValidatorException;

class ClientService
{
    /**
     * @var ClientRepository
     */
    protected $repository;
    /**
     * @var ClientValidator
     */
    protected $validator;

    public function __construct(ClientRepository $repository, ClientValidator $validator) {
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

    public function getAll()
    {
        return $this->repository->all();
    }

    public function show($id)
    {
        try
        {
            return $this->repository->find($id);
        }catch (ModelNotFoundException $model)
        {
            return [
                'error' => true,
                'message' => 'Nao foi possivel encontrar o usuario'
            ];
        }
    }

    public function update(array $data, $id)
    {
        try {
            if (Client::findOrFail($id)){
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
                'message' => 'Nao foi possivel atualizar o usuario'
            ];
        }
    }

    public function destroy($id)
    {
        try {
            if (Client::findOrFail($id))
            {
                try {
                    $this->repository->find($id)->projects()->delete();
                    $this->repository->delete($id);

                    return ['sucess' => true];
                }catch (\Exception $e){
                    return [
                        'error' => true,
                        'message' => 'Erro ao tentar deletar um usuario!'
                    ];
                }
            }
        } catch (ModelNotFoundException $e) {
            return [
                'error' => true,
                'message'=> $e->getMessage()
            ];
        }
    }
}