<?php

namespace App\Repositories;

abstract class BaseRepository
{
    /**
     * @var object
     */
    protected $model;

    /**
     * The model class.
     *
     * @return string
     */
    abstract public function model(): string;

    /**
     * Creates a new class instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->model = app($this->model());
    }

    /**
     * Find a model by its primary key or throw an exception.
     *
     * @param  mixed  $id
     * @param  array  $columns
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection|static|static[]
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findOrFail($id, $columns = ['*'])
    {
        return $this->model->findOrFail($id, $columns);
    }

    /**
     * Paginate the given records.
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginate()
    {
        return $this->model->paginate();
    }
}
