<?php

namespace App\Services\Tenants;

use App\Http\Resources\Users\TenantResource;
use App\Repositories\Domains\DomainRepository;
use App\Repositories\Tenants\TenantRepository;
use App\Services\Shared\BaseService;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Stancl\Tenancy\Database\TenantCollection;

class TenantService extends BaseService
{
    public function __construct(
        TenantRepository $repository,
        protected DomainRepository $domainRepository
    ) {

        parent::__construct($repository, TenantResource::class, TenantCollection::class);
    }

    public function store(array $data, string|int $parentId = null)
    {
        try {
            DB::beginTransaction();

            $existDomain = $this->domainRepository->exist($data['domain']);

            if ($existDomain)
                return response()->json(['statusCode' => Response::HTTP_CONFLICT, 'message' => 'Já existe o dominio cadastrado.'], Response::HTTP_CONFLICT);
            
                $this->repository->store($data);


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

            $tenant = $this->repository->get($id);

            $this->repository->update($data, $tenant->id);

            DB::commit();

            return response()->json(['statusCode' => Response::HTTP_OK, 'message' => 'Registro atualizado'], Response::HTTP_OK);
        } catch (Exception $e) {
            Log::error('UsuarioService@update - error: ' . $e->getMessage());
            return response()->json(['statusCode' => Response::HTTP_INTERNAL_SERVER_ERROR, 'message' => 'Não foi possível atualizar o registro'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
