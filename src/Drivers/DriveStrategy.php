<?php

namespace MSS\Drivers;

use MSS\Model;

interface DriveStrategy
{
    public function save($model);
    public function insert($model);
    public function update($model);
    public function select(string $fileds, string $filedOrder, string $sort = 'DESC', array $conditions = []);
    public function delete(array $conditions);
    public function exec(string $query = null);
    public function first();
    public function all();
}
