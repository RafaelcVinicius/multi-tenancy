<?php

namespace App\Services\Usuarios;

use App\Http\Resources\Usuarios\UsuarioCollection;
use App\Http\Resources\Usuarios\UsuarioResource;
use App\Repositories\Usuarios\UsuarioContatoRepository;
use App\Repositories\Usuarios\UsuarioDetalheRepository;
use App\Repositories\Usuarios\UsuarioRepository;
use App\Services\Shared\BaseService;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class UsuarioService extends BaseService
{
    public function __construct(
        UsuarioRepository $repository,
        protected UsuarioDetalheRepository $usuarioDetalheRepository,
        protected UsuarioContatoRepository $usuarioContatoRepository
    ) {
        parent::__construct($repository, UsuarioResource::class, UsuarioCollection::class);
    }

    public function store(array $data, string|int $parentId = null)
    {
        try {
            DB::beginTransaction();

            $emailExists = $this->repository->findByEmail($data['email']);

            if ($emailExists) {
                return response()->json(['statusCode' => Response::HTTP_CONFLICT, 'message' => 'Já existe um usuário cadastrado para o e-mail informado.'], Response::HTTP_CONFLICT);
            }

            /**
             * TODO: Implementar a lógica de negócio para o keycloak
             */

            $usuario = $this->repository->store([...$data, 'keycloakId' => (string) Str::uuid(), 'publicId' => (string) Str::uuid()]);

            if (array_key_exists('detalhes', $data) && isset($data['detalhes'])) {
                $cpfExists = $this->usuarioDetalheRepository->findByCpf($data['detalhes']['cpf']);

                if ($cpfExists) {
                    return response()->json(['statusCode' => Response::HTTP_CONFLICT, 'message' => 'Já existe um usuário cadastrado para o CPF informado.'], Response::HTTP_CONFLICT);
                }

                $data['detalhes']['usuarioId'] = $usuario->id;
                $this->usuarioDetalheRepository->store($data['detalhes']);
            }

            if (array_key_exists('contatos', $data) && isset($data['contatos'])) {
                foreach ($data['contatos'] as $contato) {
                    $contato['usuarioId'] = $usuario->id;
                    $contato['publicId'] = (string) Str::uuid();
                    $this->usuarioContatoRepository->store($contato);
                }
            }

            DB::commit();

            return response()->json(['statusCode' => Response::HTTP_CREATED, 'message' => 'Registro criado'], Response::HTTP_CREATED);
        } catch (Exception $e) {
            Log::error('UsuarioService@store - error: ' . $e->getMessage());
            return response()->json(['statusCode' => Response::HTTP_INTERNAL_SERVER_ERROR, 'message' => 'Não foi possível criar o registro'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(array $data, string|int $id, string|int $parentId = null)
    {
        try {
            DB::beginTransaction();

            $usuario = $this->repository->get($id);

            $this->repository->update($data, $id);

            if (array_key_exists('detalhes', $data) && isset($data['detalhes'])) {
                $this->usuarioDetalheRepository->update($data['detalhes'], $usuario->detalhes->id);
            }

            if (array_key_exists('contatos', $data) && isset($data['contatos'])) {
                foreach ($usuario->contatos as $contato) {
                    if (!in_array($contato->public_id, array_column($data['contatos'], 'id'))) {
                        $this->usuarioContatoRepository->delete($contato->id);
                    }
                }

                foreach ($data['contatos'] as $contato) {
                    if (array_key_exists('id', $contato) && isset($contato['id'])) {
                        $this->usuarioContatoRepository->update($contato, $contato['id']);
                        continue;
                    }

                    $contato['usuarioId'] = $usuario->id;
                    $contato['publicId'] = (string) Str::uuid();
                    $this->usuarioContatoRepository->store($contato);
                }
            }

            DB::commit();

            return response()->json(['statusCode' => Response::HTTP_OK, 'message' => 'Registro atualizado'], Response::HTTP_OK);
        } catch (Exception $e) {
            Log::error('UsuarioService@update - error: ' . $e->getMessage());
            return response()->json(['statusCode' => Response::HTTP_INTERNAL_SERVER_ERROR, 'message' => 'Não foi possível atualizar o registro'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
