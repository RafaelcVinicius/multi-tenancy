<?php

namespace App\Services\Usuarios;

use App\Http\Resources\Usuarios\UsuarioContatoCollection;
use App\Http\Resources\Usuarios\UsuarioContatoResource;
use App\Repositories\Usuarios\UsuarioContatoRepository;
use App\Repositories\Usuarios\UsuarioRepository;
use App\Services\Shared\BaseService;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class UsuarioContatoService extends BaseService
{
    public function __construct(
        UsuarioContatoRepository $repository,
        protected UsuarioRepository $usuarioRepository,
    ) {
        parent::__construct($repository, UsuarioContatoResource::class, UsuarioContatoCollection::class);
    }

    public function store(array $data, string|int $parentId = null)
    {
        try {
            $usuario = $this->usuarioRepository->get($parentId);
            $this->repository->store([...$data, 'usuarioId' => $usuario->id, 'publicId' => (string) Str::uuid()]);
            return response()->json(['statusCode' => Response::HTTP_CREATED, 'message' => 'Registro criado'], Response::HTTP_CREATED);
        } catch (ModelNotFoundException $e) {
            return response()->json(['statusCode' => Response::HTTP_NOT_FOUND, 'message' => 'Usuário não encontrado'], Response::HTTP_NOT_FOUND);
        } catch (Exception $e) {
            Log::error('UsuarioContatoService@store - error: ' . $e->getMessage());
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
            Log::error('UsuarioContatoService@update - error: ' . $e->getMessage());
            return response()->json(['statusCode' => Response::HTTP_INTERNAL_SERVER_ERROR, 'message' => 'Não foi possível atualizar o registro'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
