<?php

namespace App\Models\Users;

use App\Models\Users\User;
use App\Traits\SensitiveDataTrait;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class UserDetail extends Model
{
    use SensitiveDataTrait;

    protected $table = 'user_details';

    protected $fillable = [
        'user_id',
        'cpf',
        'nickname',
        'position'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected function cpf(): Attribute
    {
        return Attribute::make(
            get: function (string $value) {
                return $this->hideSensitiveData($value, 3, 2);
            },
        );
    }
}
