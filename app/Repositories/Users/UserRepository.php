<?php

namespace App\Repositories\Users;

use App\Models\Users\User;
use App\Repositories\Shared\BaseRepository;
use App\Repositories\Users\Contracts\UserRepositoryInterface;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function findByEmail(string $email)
    {
        return $this->model->where('email', $email)->first();
    }
}
