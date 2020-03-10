<?php

namespace App\Models;

use MSS\Model;
use MSS\Drivers\MysqlPdo;

class Cartorio extends Model
{
    protected $table = 'cartorios';

    public function __construct(MysqlPdo $driver)
    {
        //$pdo = new \PDO('mysql:host='.getenv('DB_HOST').';port='.getenv('DB_PORT').';dbname='.getenv('DB_DATABASE'), getenv('DB_USER'), getenv('DB_PASS'), array(\PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
        //$pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        //$this->setDriver(new MysqlPdo($pdo));
        $this->setDriver($driver);
    }

    public function getCidades()
    {
        return $this->findAll('distinct(cidade)', 'cidade', 'ASC');
    }

    public function getUfs()
    {
        return $this->findAll('distinct(uf)', 'uf', 'ASC');
    }
}