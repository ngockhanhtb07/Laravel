<?php

namespace App\Repositories;


abstract class EloquentRepository implements RepositoryInterface
{
    protected $_model;

    public function __construct()
    {
        $this->setModel();
    }

    abstract public function getModel();

    public function all($columns = array('*'))
    {
        return $this->_model::select($columns)->get();
    }

    public function setModel()
    {
        $this->_model = app()->make(
            $this->getModel()
        );
    }

    public function find($id)
    {
        $result = $this->_model->findOrFail($id);
        return $result;
    }

    public function create(array $attributes)
    {
        return $this->_model->create($attributes);
    }

    public function update(array $attributes, $id)
    {
        $result = $this->find($id);
        if ($result) {
            $result->update($attributes);
            return $result;
        }
        return false;
    }

    public function delete($id)
    {
        $result = $this->find($id);
        if ($result) {
            $result->delete();
            return true;
        }
        return false;
    }

    public function paginate($perPage = 15, $columns = array('*'))
    {
        return $this->_model->select($columns)->paginate($perPage);
    }

    public function findBy($field, $value, $columns = array('*'))
    {
        return $this->_model->select($columns)->where($field, $value)->get();
    }
    public function findByListCondition(array $list_filed, $columns = array('*')){
        return $this->_model->select($columns)->where($list_filed);
    }

    public function firstOrCreate(array $value, array $attribute = null)
    {
        return $this->_model->firstOrCreate($value,$attribute);
    }

    public function createMany(array $value)
    {
        return $this->_model->insert($value);
    }

    public function findDelete(array $data)
    {
        if (count($data))
        $this->_model->where($data)->delete();
    }


}