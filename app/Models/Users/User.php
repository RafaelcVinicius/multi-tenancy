<?php

namespace App\Models\Users;

use App\Models\Users\UserDetail;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory, SoftDeletes, Filterable;

    protected $table = 'users';

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */

    protected $fillable = [
        'public_id',
        'keycloak_id',
        'email',
        'name',
    ];

    private static $whiteListFilter = [
        'email',
        'nome',
        'interno',
        'created_at'
    ];

    public function detail()
    {
        return $this->hasOne(UserDetail::class);
    }

    public function contacts()
    {
        return $this->hasMany(UserContact::class);
    }
}