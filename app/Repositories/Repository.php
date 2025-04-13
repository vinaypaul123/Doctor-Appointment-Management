<?php

namespace App\Repositories;

use App\DataTransferObjects\Dto;
use App\Traits\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Pipeline;
use Illuminate\Database\Eloquent\Builder;

abstract class Repository
{
    use Filterable;

    public function __construct(
        protected Model $model
    ) {}

    public function list($filters = []): Builder
    {
        return Pipeline::send($this->model::query())
            ->through([...$filters, ...$this->filter()])
            ->thenReturn();
    }

    public function find($id, $relations = []): Model
    {
        return $this->model = $this->model::with($relations)->findOrFail($id);
    }

    public function findBy($column, $value, $operator = '=')
    {
        return $this->model::where($column, $operator, $value)->first();
    }


    public function findWithTrashed($id, $relations = []): Model
    {
        return $this->model = $this->model::withTrashed()->with($relations)->findOrFail($id);
    }

    public function findOrFail($id, $relations = []): Model|null
    {
        return $this->model = $this->model::with($relations)->find($id);
    }

    public function forceDelete(int $id): bool
    {
        return $this->findWithTrashed($id)?->forceDelete();
    }

    public function delete(int $id): bool
    {
        return $this->find($id)?->delete();
    }

    public function create(Dto $data)
    {
        return $this->model::create($data->toArray())->refresh();
    }

    public function update($id, Dto $data)
    {
        $model = $this->model::find($id);
        $model->update($data->toArray());
        return $model;
    }
}
