<?php

namespace Squashjedi\Basecamp\App\Http\Repositories;

abstract class DbRepository {

    private $model;
    protected $paginateAfter = 20;

    function __construct($model)
    {
        $this->model = $model;
    }

    public function getById($id)
    {
        return $this->model->find($id);
    }

    public function getAll()
    {
        return $this->model->all();
    }

    public function destroy($ids)
    {
        $ids = explode(",", $ids);
        $this->model->destroy($ids);
        return $ids;
    }

}