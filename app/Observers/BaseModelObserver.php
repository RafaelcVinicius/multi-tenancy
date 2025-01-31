<?php

namespace App\Observers;

use App\Models\Shared\BaseModel;

class BaseModelObserver
{
    /**
     * Handle the BaseModel "creating" event.
     */
    public function creating(BaseModel $baseModel): void
    {
        //
    }

    /**
     * Handle the BaseModel "created" event.
     */
    public function created(BaseModel $baseModel): void
    {
        //
    }

    /**
     * Handle the BaseModel "updated" event.
     */
    public function updated(BaseModel $baseModel): void
    {
        //
    }

    /**
     * Handle the BaseModel "deleted" event.
     */
    public function deleted(BaseModel $baseModel): void
    {
        //
    }

    /**
     * Handle the BaseModel "restored" event.
     */
    public function restored(BaseModel $baseModel): void
    {
        //
    }

    /**
     * Handle the BaseModel "force deleted" event.
     */
    public function forceDeleted(BaseModel $baseModel): void
    {
        //
    }
}
