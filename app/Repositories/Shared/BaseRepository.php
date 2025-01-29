<?php

namespace App\Repositories\Shared;

use App\Repositories\Shared\Contracts\BaseRepositoryInterface;
use App\Traits\HelperTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

abstract class BaseRepository implements BaseRepositoryInterface
{
    use HelperTrait;

    /**
     * BaseRepository constructor.
     *
     * @param Model $model
     */
    public function __construct(protected Model $model) {}

    /**
     * Retorna todos os registros
     *
     * @param string|int $parentId
     * @param Request $request
     */
    public function getAll(Request $request, string|int $parentId = null)
    {
        $query = $this->model->query();

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

    /**
     * Armazena um novo registro
     *
     * @param array $data
     * @param string|int $parentId
     * @return Model
     */
    public function store(array $data, string|int $parentId = null)
    {
        return $this->model->create($this->arrayChangeKeyCase($data));
    }

    /**
     * Retorna um registro específico
     *
     * @param string|int $id
     * @param string|int $parentId
     * @return Model
     */
    public function get(string|int $id, string|int $parentId = null)
    {
        $query = $this->model->query();

        if (request()->has('with')) {
            $query->with(request()->get('with'));
        }

        if (is_string($id) && Str::isUuid($id)) {
            return $query->where('public_id', $id)->firstOrFail();
        }

        return $query->findOrFail($id);
    }

    /**
     * Atualiza um registro
     *
     * @param array $data
     * @param string|int $id
     * @param string|int $parentId
     */
    public function update(array $data, string|int $id, string|int $parentId = null)
    {
        $model = $this->get($id);
        $model->update($this->arrayChangeKeyCase($data));
        return $model;
    }

    /**
     * Deleta um registro
     *
     * @param string|int $id
     * @param string|int $parentId
     * @return void
     */
    public function delete(string|int $id, string|int $parentId = null)
    {
        $model = $this->get($id);
        $model->delete();
    }
}
