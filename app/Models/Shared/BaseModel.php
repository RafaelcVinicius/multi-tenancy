<?php

namespace App\Models\Shared;

use App\Observers\BaseModelObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

#[ObservedBy(BaseModelObserver::class)]
class BaseModel extends Model {}
