<?php

namespace App\Services\Users;

use App\Http\Resources\Users\UserContactCollection;
use App\Http\Resources\Users\UserContactResource;
use App\Repositories\Users\UserContactRepository;
use App\Repositories\Users\UserRepository;
use App\Services\Shared\BaseService;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class UserContactService extends BaseService
{
    public function __construct(
        UserContactRepository $repository,
        protected UserRepository $UserRepository,
    ) {
        parent::__construct($repository, UserContactResource::class, UserContactCollection::class);
    }

    public function store(array $data, string|int $parentId = null)
    {
        try {
            $User = $this->UserRepository->get($parentId);
            $this->repository->store([...$data, 'UserId' => $User->id, 'publicId' => (string) Str::uuid()]);
            return response()->json(['statusCode' => Response::HTTP_CREATED, 'message' => 'Registro criado'], Response::HTTP_CREATED);
        } catch (ModelNotFoundException $e) {
            return response()->json(['statusCode' => Response::HTTP_NOT_FOUND, 'message' => 'Usuário não encontrado'], Response::HTTP_NOT_FOUND);
        } catch (Exception $e) {
            Log::error('UserContactService@store - error: ' . $e->getMessage());
            return response()->json(['statusCode' => Response::HTTP_INTERNAL_SERVER_ERROR, 'message' => 'Não foi possível criar o registro'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(array $data, string|int $id, null|string|int $parentId = null)
    {
        try {
            $this->repository->update($data, $id, $parentId);
            return response()->json(['statusCode' => Response::HTTP_OK, 'message' => 'Registro atualizado'], Response::HTTP_OK);
        } catch (ModelNotFoundException $e) {
            return response()->json(['statusCode' => Response::HTTP_NOT_FOUND, 'message' => 'Usuário não encontrado'], Response::HTTP_NOT_FOUND);
        } catch (Exception $e) {
            Log::error('UserContactService@update - error: ' . $e->getMessage());
            return response()->json(['statusCode' => Response::HTTP_INTERNAL_SERVER_ERROR, 'message' => 'Não foi possível atualizar o registro'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
