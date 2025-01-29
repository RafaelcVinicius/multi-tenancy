<?php

namespace App\Services\Keycloak;

use App\Repositories\Keycloak\KeycloakRepository;
use App\Services\Shared\BaseService;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class KeycloakService
{
    public function __construct(
        private KeycloakRepository $repository
    ) {
    }

    public function token(array $data){
        try {
            return $this->repository->token($data);
        } catch (Exception $e) {
            Log::error('KeycloakService@token - error: '. $e->getMessage());
            return response()->json(['statusCode' => Response::HTTP_INTERNAL_SERVER_ERROR,'message' => 'Não foi possível gerar o token'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
