<?php

namespace App\Repositories\Domains;

use App\Models\Domain;
use App\Repositories\Domains\Contracts\DomainRepositoryInterface;
use App\Repositories\Shared\BaseRepository;

class DomainRepository extends BaseRepository implements DomainRepositoryInterface
{
    public function __construct(Domain $model)
    {
        parent::__construct($model);
    }

    public function exist(string $name): bool{
        return $this->model->where('domain', $name)->exists();
    }
}
