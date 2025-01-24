<?php

namespace App\Services\Shared;

use Illuminate\Http\Request;

interface BaseServiceInterface
{
    public function getAll(Request $request, string|int $parentId = null);
    public function store(array $data, string|int $parentId = null);
    public function get(string|int $id, string|int $parentId = null);
    public function update(array $data, string|int $id, string|int $parentId = null);
    public function delete(string|int $id, string|int $parentId = null);
}
