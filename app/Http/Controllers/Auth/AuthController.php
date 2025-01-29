<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Shared\BaseController;
use App\Services\Keycloak\KeycloakService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(private KeycloakService $service)
    {
    }

    /**
     * Store a newly created resource in storage.
     */
    public function token(Request $request)
    {
        return $this->service->token($request->all());
    }
}
