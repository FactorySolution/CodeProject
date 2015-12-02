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

    public function __construct(ClientRepository $repository,
                                ClientValidator $validator) {
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

    public function all()
    {
        try
        {
            return $this->repository->all();
        }
        catch (\Exception $e)
        {
            return [
                "error" => true,
                "message" => $e->getMessage()
            ];
        }
    }

    public function find($id)
    {
        try
        {
            return $this->repository->find($id);
        }
        catch (\Exception $e)
        {
            return [
                "error" => true,
                "message" => $e->getMessage()
            ];
        }
    }



    public function update(array $data, $id)
    {
        try
        {
            $this->validator->with($data)->passesOrFail();
            return $this->repository->update($data, $id);
        }
        catch(ValidatorException $e)
        {
            return [
                'error'     => true,
                'message'   => $e->getMessageBag()
            ];
        }
    }

    public function destroy($id)
    {
        try
        {
            $this->repository->find($id)->projects()->delete();
            $this->repository->delete($id);
            return ['success' => true];
        }
        catch (\Exception $e) {
            return [
                "error" => true,
                "message" => $e->getMessage()
            ];
        }
    }
}