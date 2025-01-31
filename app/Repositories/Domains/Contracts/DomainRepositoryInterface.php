<?php

namespace App\Repositories\Domains\Contracts;

interface DomainRepositoryInterface {
    public function exist(string $name): bool;
}
