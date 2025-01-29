<?php

namespace App\Services\Users;

use App\Http\Resources\Users\UserCollection;
use App\Http\Resources\Users\UserResource;
use App\Repositories\Keycloak\KeycloakRepository;
use App\Repositories\Users\UserContactRepository;
use App\Repositories\Users\UserDetailRepository;
use App\Repositories\Users\UserRepository;
use App\Services\Shared\BaseService;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class UserService extends BaseService
{
    public function __construct(
        UserRepository $repository,
        protected UserDetailRepository $userDetailRepository,
        protected UserContactRepository $userContactRepository,
        protected KeycloakRepository $keycloakRepository
    ) {
        parent::__construct($repository, UserResource::class, UserCollection::class);
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

             $userKeycloak = $this->keycloakRepository->storeUser([
                "email" =>  $data["email"],
                "emailVerified" =>  true,
                "enabled" =>  true,
                "firstName" =>  $data["name"],
                "lastName" =>  $data["name"],
                "userName" =>  $data['email'],
                "credentials" =>  [
                    "temporary" => false,
                    "type" => 'password',
                    "value" => $data['password'],
                ]
            ]);

            $usuario = $this->repository->store([...$data, 'keycloakId' => (string) $userKeycloak['id'], 'publicId' => (string) Str::uuid()]);

            if (array_key_exists('detail', $data) && isset($data['detail'])) {
                $cpfExists = $this->userDetailRepository->findByCpf($data['detail']['cpf']);

                if ($cpfExists) {
                    return response()->json(['statusCode' => Response::HTTP_CONFLICT, 'message' => 'Já existe um usuário cadastrado para o CPF informado.'], Response::HTTP_CONFLICT);
                }

                $data['detail']['userId'] = $usuario->id;
                $this->userDetailRepository->store($data['detail']);
            }

            if (array_key_exists('contacts', $data) && isset($data['contacts'])) {
                foreach ($data['contacts'] as $contato) {
                    $contato['userId'] = $usuario->id;
                    $contato['publicId'] = (string) Str::uuid();
                    $this->userContactRepository->store($contato);
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

            if (array_key_exists('detail', $data) && isset($data['detail'])) {
                $this->userDetailRepository->update($data['detail'], $usuario->detalhes->id);
            }

            if (array_key_exists('contacts', $data) && isset($data['contacts'])) {
                foreach ($usuario->contatos as $contato) {
                    if (!in_array($contato->public_id, array_column($data['contacts'], 'id'))) {
                        $this->userContactRepository->delete($contato->id);
                    }
                }

                foreach ($data['contacts'] as $contato) {
                    if (array_key_exists('id', $contato) && isset($contato['id'])) {
                        $this->userContactRepository->update($contato, $contato['id']);
                        continue;
                    }

                    $contato['userId'] = $usuario->id;
                    $contato['publicId'] = (string) Str::uuid();
                    $this->userContactRepository->store($contato);
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
