<?php

namespace App\Repositories\Users;

use App\Models\Users\UserDetail;
use App\Repositories\Shared\BaseRepository;
use App\Repositories\Users\Contracts\UserDetailRepositoryInterface;

class UserDetailRepository extends BaseRepository implements UserDetailRepositoryInterface
{
    public function __construct(UserDetail $model)
    {
        parent::__construct($model);
    }

    public function findByCpf(string $cpf)
    {
        return $this->model->where('cpf', $cpf)->first();
    }
}
