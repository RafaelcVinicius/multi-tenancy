<?php

namespace App\Repositories\Keycloak\Contracts;

interface KeycloakRepositoryInterface {
    public function token(array $data);
    public function storeUser(array $data);
    public function findUser(string $data);
    public function showUser(array $data);

}
