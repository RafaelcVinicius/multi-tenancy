<?php

namespace App\Models;

use App\Models\Shared\BaseModel;
use Stancl\Tenancy\Database\Concerns\HasDatabase;
use Stancl\Tenancy\Database\Concerns\HasDomains;

class Domain extends BaseModel
{
    use HasDatabase, HasDomains;
}