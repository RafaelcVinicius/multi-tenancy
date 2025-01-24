<?php

namespace App\Services\Shared;

use App\Repositories\Shared\BaseRepository;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;

abstract class BaseService implements BaseServiceInterface
{

    /**
     * BaseService constructor.
     *
     * @param BaseRepository $repository
     * @param string $resource
     * @param string $collection
     */
    public function __construct(protected BaseRepository $repository, protected string $resource, protected string $collection) {}

    /**
     * Retorna todos os registros
     *
     * @param Request $request
     * @param string|int $parentId
     * @return \Illuminate\Http\Resources\Json\ResourceCollection
     */
    public function getAll(Request $request, string|int $parentId = null)
    {
        try {
            return new $this->collection($this->repository->getAll($request, $parentId));
        } catch (Exception $e) {
            Log::error('BaseService@getAll - error: ' . $e->getMessage());
            return new $this->collection((new LengthAwarePaginator([], 0, $request->get('perPage', config()->get('constants.pagination.perPage'))))->appends($request->query()));
        }
    }

    /**
     * Armazena um novo registro
     *
     * @param array $data
     * @param string|int $parentId
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(array $data, string|int $parentId = null)
    {
        try {
            $this->repository->store($data, $parentId);

            return response()->json(['statusCode' => Response::HTTP_CREATED, 'message' => 'Registro criado'], Response::HTTP_CREATED);
        } catch (Exception $e) {
            Log::error('BaseService@store - error: ' . $e->getMessage());
            return response()->json(['statusCode' => Response::HTTP_INTERNAL_SERVER_ERROR, 'message' => 'Não foi possível criar o registro'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Retorna um registro
     *
     * @param string|int $id
     * @param string|int $parentId
     * @return \Illuminate\Http\Resources\Json\JsonResource|\Illuminate\Http\JsonResponse
     */
    public function get(string|int $id, string|int $parentId = null)
    {
        try {
            return new $this->resource($this->repository->get($id, $parentId));
        } catch (ModelNotFoundException $e) {
            return response()->json(['statusCode' => Response::HTTP_NOT_FOUND, 'message' => 'Registro não encontrado'], Response::HTTP_NOT_FOUND);
        } catch (Exception $e) {
            Log::error('BaseService@get - error: ' . $e->getMessage());
            return response()->json(['statusCode' => Response::HTTP_INTERNAL_SERVER_ERROR, 'message' => 'Não foi possível encontrar o registro'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Atualiza um registro
     *
     * @param array $data
     * @param string|int $parentId
     * @param string|int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(array $data, string|int $id, string|int $parentId = null)
    {
        try {
            $this->repository->update($data, $id, $parentId);

            return response()->json(['statusCode' => Response::HTTP_OK, 'message' => 'Registro atualizado'], Response::HTTP_OK);
        } catch (ModelNotFoundException $e) {
            return response()->json(['statusCode' => Response::HTTP_NOT_FOUND, 'message' => 'Registro não encontrado'], Response::HTTP_NOT_FOUND);
        } catch (Exception $e) {
            Log::error('BaseService@update - error: ' . $e->getMessage());
            return response()->json(['statusCode' => Response::HTTP_INTERNAL_SERVER_ERROR, 'message' => 'Não foi possível atualizar o registro'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Deleta um registro
     *
     * @param string|int $id
     * @param string|int $parentId
     * @return void|\Illuminate\Http\JsonResponse
     */
    public function delete(string|int $id, string|int $parentId = null)
    {
        try {
            $this->repository->delete($id, $parentId);
        } catch (ModelNotFoundException $e) {
            return response()->json(['statusCode' => Response::HTTP_NOT_FOUND, 'message' => 'Registro não encontrado'], Response::HTTP_NOT_FOUND);
        } catch (Exception $e) {
            Log::error('BaseService@delete - error: ' . $e->getMessage());
            return response()->json(['statusCode' => Response::HTTP_INTERNAL_SERVER_ERROR, 'message' => 'Não foi possível deletar o registro'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
