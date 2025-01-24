<?php

namespace App\Http\Controllers\Usuarios;

use App\Http\Controllers\Shared\BaseController;
use App\Http\Requests\Usuarios\UsuarioRequest;
use App\Services\Usuarios\UsuarioService;

class UsuarioController extends BaseController
{
    public function __construct(UsuarioService $service)
    {
        parent::__construct($service);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UsuarioRequest $request)
    {
        return $this->service->store($request->validated());
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UsuarioRequest $request, string $id)
    {
        return $this->service->update($request->validated(), $id);
    }
}
