<?php

namespace MSS;

use MSS\Drivers\DriveStrategy;

abstract class Model
{
    protected $driver;

    public function setDriver(DriveStrategy $driver)
    {
        $this->driver = $driver;
        $this->driver->setTable($this->table);
        return $this;
    }

    public function getDriver()
    {
        return $this->driver;
    }

    public function save(array $data = null)
    {
        return $this->getDriver()
            ->save($data ?? $this)
            ->exec();
    }

    public function findAll(string $fileds = '*', string $filedOrder = 'id', string $sort = 'DESC', array $conditions = [], $limite = ['init' => 0, 'end' => 0])
    {
        return $this->getDriver()
            ->select($fileds, $filedOrder, $sort, $conditions, $limite)
            ->exec()
            ->all();
    }

    public function paginate(string $fileds = '*', string $filedOrder = 'id', string $sort = 'DESC', array $conditions = [])
    {
        return $this->getDriver()
            ->select($fileds, $filedOrder, $sort, $conditions)
            ->exec()
            ->all();
    }

    public function find(int $id)
    {
        return $this->getDriver()
            ->select('*', 'id', 'DESC', ['id' => $id])
            ->exec()
            ->first();
    }

    public function delete(int $id)
    {
        return $this->getDriver()
            ->delete(['id' => $id])
            ->exec();
    }

    public function __get($variable)
    {
        if ($variable == 'table') {
            $table = get_class($this);
            $table = explode('\\', $table);

            return strtolower(array_pop($table));
        }
    }

    public function getRowsAffected()
    {
        return $this->getDriver()->getRowsAffected();
    }
}
