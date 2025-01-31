<?php

namespace App\Http\Controllers\Tenants;

use App\Http\Controllers\Shared\BaseController;
use App\Http\Requests\Tenants\TenantRequest;
use App\Services\Tenants\TenantService;

class TenantController extends BaseController
{
    public function __construct(TenantService $service)
    {
        parent::__construct($service);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TenantRequest $request)
    {
        return $this->service->store($request->validated());
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TenantRequest $request, string $id)
    {
        return $this->service->update($request->validated(), $id);
    }
}
