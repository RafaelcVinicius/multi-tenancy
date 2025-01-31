<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Shared\BaseController;
use App\Services\Users\UserService;
use App\Http\Requests\Users\UserRequest;
use Illuminate\Support\Facades\Auth;

class UserController extends BaseController
{
    public function __construct(UserService $service)
    {
        parent::__construct($service);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request)
    {
        return $this->service->store($request->validated());
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, string $id)
    {
        return $this->service->update($request->validated(), $id);
    }

    /**
     * Get user logged.
     */
    public function logged()
    {
        return Auth::user();
    }
}
