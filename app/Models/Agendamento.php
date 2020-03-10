<?php

namespace App\Models;

use MSS\Model;
use MSS\Drivers\MysqlPdo;

class Agendamento extends Model
{
    protected $table = 'agendamentos';

    public function __construct()
    {
        $pdo = new \PDO('mysql:host='.getenv('DB_HOST').';port='.getenv('DB_PORT').';dbname='.getenv('DB_DATABASE'), getenv('DB_USER'), getenv('DB_PASS'), array(\PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $this->setDriver(new MysqlPdo($pdo));
    }

}