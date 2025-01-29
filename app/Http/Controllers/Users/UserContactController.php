<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Shared\BaseController;
use App\Http\Requests\Users\UserContactRequest;
use App\Services\Users\UserContactService;

class UserContactController extends BaseController
{
    public function __construct(UserContactService $service)
    {
        parent::__construct($service);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserContactRequest $request)
    {
        return $this->service->store($request->validated(), ...array_values($request->route()->parameters));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserContactRequest $request)
    {
        return $this->service->update($request->validated(), ...array_values(array_reverse(request()->route()->parameters)));
    }
}
