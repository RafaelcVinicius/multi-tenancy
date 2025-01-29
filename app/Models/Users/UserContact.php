<?php

namespace App\Models\Users;

use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserContact extends Model
{
    use SoftDeletes, Filterable;

    protected $table = 'user_contacts';

    protected $fillable = [
        'user_id',
        'public_id',
        'type',
        'contact'
    ];

    private static $whiteListFilter = [
        'type',
        'contact',
        'user.name',
        'user.email'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
