<?php

namespace MSS\Drivers;

use MSS\Model;

class MysqlPdo implements DriveStrategy
{
    protected $pdo;
    protected $table;
    protected $num_rows_affected = null;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function setTable(string $table)
    {
        $this->table = $table;
        return $this;
    }

    public function save($data)
    {
        if (!empty($data->id) || !empty($data['id'])) {
            $this->update($data);
            return $this;
        }
        
        $this->insert($data);
        
        return $this;
    }

    public function insert($data)
    {
        $query = 'INSERT INTO %s (%s) VALUES (%s)';

        $fields = [];
        $fields_to_bind = [];

        foreach ($data as $field => $value) {
            $fields[] = $field;
            $fields_to_bind[] = ':'.$field;
        }

        $fields = implode(',', $fields);
        $fields_to_bind = implode(',', $fields_to_bind);

        $query = sprintf($query, $this->table, $fields, $fields_to_bind);

        $this->query = $this->pdo->prepare($query);

        $this->bind($data);
        
        return $this;
    }

    public function update($data)
    {
        if (empty($data->id) && empty($data['id'])) {
            throw new \Exception("Id is required");
        }

        $query          = 'UPDATE %s SET %s';
        $data_to_update = $this->params($data, ',');

        $query  = sprintf($query, $this->table, $data_to_update);
        $query .= ' WHERE id=:id';
        
        $this->query = $this->pdo->prepare($query);
        $this->bind($data);

        return $this;
    }

    public function select(string $fileds = '*', string $filedOrder = 'id', string $sort = 'DESC', array $conditions = [], $limite = ['init' => 0, 'end' => 0])
    {
        $query = 'SELECT '.$fileds.' from '. $this->table;
        
        $data = $this->params($conditions);

        if ($data) {
            $query .= ' WHERE '. $data;
        }

        $query .= ' ORDER BY '. $filedOrder.' '.$sort;

        if ($limite['end']) {
            $query .= ' LIMIT '.$limite['init'].','.$limite['end'];
        }

        $this->query = $this->pdo->prepare($query);

        $this->bind($conditions);

        return $this;
    }

    public function delete(array $conditions)
    {
        if (empty($conditions['id'])) {
            throw new \Exception("Id is required");
        }

        $query = 'DELETE from '. $this->table;
        $data = $this->params($conditions);
        $query .= ' WHERE '. $data;

        $this->query = $this->pdo->prepare($query);
        $this->bind($conditions);

        return $this;
    }

    public function exec(string $query = null)
    {
        if ($query) {
            $this->query = $this->pdo->prepare($query);
        }
        $this->num_rows_affected = $this->query->execute();
        //$this->query->debugDumpParams();
        return $this;
    }

    public function first()
    {
        return $this->query->fetch(\PDO::FETCH_OBJ);
    }

    public function all()
    {
        return $this->query->fetchAll(\PDO::FETCH_OBJ);
    }

    public function limit(int $init = 1, int $end = 50)
    {
        $query = ' LIMIT '.$init.','.$end;
        $this->query->queryString .=  $query;
        
        return $this;
    }

    protected function params($conditions, $concatenator = ' AND ')
    {
        $fields = [];
        foreach ($conditions as $field => $value) {
            $fields[] = $field . '=:'. rtrim($field, '!');
        }
        return implode($concatenator, $fields);
    }

    protected function bind($data)
    {
        foreach ($data as $field => $value) {
            $this->query->bindValue(rtrim($field, '!'), $value);
        }
    }

    public function getRowsAffected()
    {
        return $this->num_rows_affected;
    }

    public function getPDO()
    {
        return $this->pdo;
    }
}
