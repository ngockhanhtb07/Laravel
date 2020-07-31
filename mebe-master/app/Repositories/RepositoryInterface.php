<?php

namespace App\Repositories;

interface RepositoryInterface
{
    public function all($columns = array('*'));

    public function paginate($perPage = 15, $columns = array('*'));

    public function create(array $data);

    public function update(array $data, $id);

    public function delete($id);

    public function find($id);

    public function findBy($field, $value, $columns = array('*'));

    public function findByListCondition(array $list_filed, $columns = array('*'));

    public function firstOrCreate(array $value,array $attribute = null);

    public function createMany(array $value);

    public function findDelete(array $data);
}