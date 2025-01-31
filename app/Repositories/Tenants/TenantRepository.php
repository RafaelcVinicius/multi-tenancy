<?php

namespace App\Repositories\Tenants;

use App\Models\Tenant;
use App\Repositories\Shared\BaseRepository;
use App\Repositories\Tenants\Contracts\TenantRepositoryInterface;

class TenantRepository extends BaseRepository implements TenantRepositoryInterface
{
    public function __construct(Tenant $model)
    {
        parent::__construct($model);
    }
}
