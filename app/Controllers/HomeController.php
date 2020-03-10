<?php

namespace App\Controllers;

use MSS\Controller;

class HomeController extends Controller
{
    public function index()
    {
        $data = [];
        $data['page_titulo']    = 'Home';
        $data['page_descricao'] = 'Informações do projeto';

        $this->render($data, 'home/index');
    }
}