<?php

namespace App\Repositories\Users\Contracts;

interface UserRepositoryInterface {
    public function findByEmail(string $email);
}
