<?php

require __DIR__ . '/vendor/autoload.php';
require __DIR__. '/src/helpers.php';

use Pimple\Container;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$router = require __DIR__. '/router.php';

$infoCtrl = $router->handler();

$di = new Container();

$di['pdo'] = function($c){
    $pdo = new \PDO('mysql:host='.getenv('DB_HOST').';port='.getenv('DB_PORT').';dbname='.getenv('DB_DATABASE'), getenv('DB_USER'), getenv('DB_PASS'), array(\PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
    $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

    return $pdo;
};

$di['driverPdo'] = function($c){
    return new MSS\Drivers\MysqlPdo($c['pdo']);
};

$ctrl = new $infoCtrl['class']($di);
$action = $infoCtrl['action'];

echo $ctrl->$action();

