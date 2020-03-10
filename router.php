<?php

$router = new MSS\Router;

$router['/'] = [
    'class'     => App\Controllers\HomeController::class,
    'action'    => 'index'
];

$router['/cartorios'] = [
    'class'     => App\Controllers\CartoriosController::class,
    'action'    => 'index'
];

$router['/cartorios/novo'] = [
    'class'     => App\Controllers\CartoriosController::class,
    'action'    => 'new'
];

$router['/cartorios/save'] = [
    'class'     => App\Controllers\CartoriosController::class,
    'action'    => 'save'
];

$router['/cartorios/editar'] = [
    'class'     => App\Controllers\CartoriosController::class,
    'action'    => 'edit'
];

$router['/cartorios/excluir'] = [
    'class'     => App\Controllers\CartoriosController::class,
    'action'    => 'delete'
];

$router['/cartorios/importar-xml'] = [
    'class'     => App\Controllers\CartoriosController::class,
    'action'    => 'importXml'
];

$router['/cartorios/exportar-xls'] = [
    'class'     => App\Controllers\CartoriosController::class,
    'action'    => 'exportXls'
];

$router['/agendamentos/agendar'] = [
    'class'     => App\Controllers\AgendamentosController::class,
    'action'    => 'save'
];

$router['/agendamentos'] = [
    'class'     => App\Controllers\AgendamentosController::class,
    'action'    => 'index'
];

$router['/agendamentos/editar'] = [
    'class'     => App\Controllers\AgendamentosController::class,
    'action'    => 'edit'
];

$router['/agendamentos/excluir'] = [
    'class'     => App\Controllers\AgendamentosController::class,
    'action'    => 'delete'
];

$router['/disparar-envio'] = [
    'class'     => App\Controllers\AgendamentosController::class,
    'action'    => 'sendMails'
];

return $router;