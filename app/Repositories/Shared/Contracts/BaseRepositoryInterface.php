<?php

namespace App\Repositories\Shared\Contracts;

use Illuminate\Http\Request;

interface BaseRepositoryInterface
{
    public function getAll(Request $request, string|int $parentId = null);
    public function store(array $data, string|int $parentId = null);
    public function get(string|int $id, string|int $parentId = null);
    public function update(array $data, string|int $id, string|int $parentId = null);
    public function delete(string|int $id, string|int $parentId = null);
}
