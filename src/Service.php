<?php

namespace MSS;

abstract class Service
{
    protected $model;

    public function getAll()
    {
        return $this->model->findAll();
    }

    public function getById(int $id)
    {
        return $this->model->find($id);
    }

    public function delete(int $id)
    {
        return $this->model->delete($id);
    }

    public function sanitize(array $data)
    {
        $newData = [];

        foreach ($data as $key => $value) {
            $newData[$key] = htmlentities(trim($value));
        }

        return $newData;
    }
}
