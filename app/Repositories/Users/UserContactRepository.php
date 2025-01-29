<?php

namespace App\Repositories\Users;

use App\Models\Users\UserContact;
use App\Repositories\Shared\BaseRepository;
use App\Repositories\Users\Contracts\UserContactRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UserContactRepository extends BaseRepository implements UserContactRepositoryInterface
{
    public function __construct(UserContact $model)
    {
        parent::__construct($model);
    }

    public function getAll(Request $request, null|string|int $parentId = null)
    {
        $query = $this->model->query();

        if ($parentId)
            $query->whereHas('User', fn($query) => is_string($parentId) && Str::isUuid($parentId) ? $query->where('public_id', $parentId) : $query->where('id', $parentId));
        /**
         * Utiliza o método filter() da lib medhi-fathi eloquent-filter, para isso
         * deve ignorar algumas informações da request e deve ser definido no model a propriedade whiteListFilter e a trait filterable
         */
        $query->ignoreRequest(['page', 'perPage', 'sort', 'sortDir', 'q', 'with'])
            ->filter();

        if ($request->has('with')) {
            $query->with($request->get('with'));
        }

        return $query->paginate($request->get('perPage', config()->get('constants.pagination.perPage')));
    }

    public function get(string|int $id, string|int $parentId = null)
    {
        $query = $this->model->query();

        if ($parentId)
            $query->whereHas('User', fn($query) => is_string($parentId) && Str::isUuid($parentId) ? $query->where('public_id', $parentId) : $query->where('id', $parentId));

        if (request()->has('with')) {
            $query->with(request()->get('with'));
        }

        if (is_string($id) && Str::isUuid($id)) {
            return $query->where('public_id', $id)->firstOrFail();
        }

        return $query->findOrFail($id);
    }
}
