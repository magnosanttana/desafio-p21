<?php

namespace App\Controllers;

use MSS\Controller;
use App\Services\CartorioService;
use App\Services\AgendamentoService;
use App\Exceptions\BDException;
use App\Exceptions\ValidationException;
use App\Models\Cartorio;
use App\Models\Agendamento;

class AgendamentosController extends Controller
{
    protected $service;

    public function __construct($di)
    {
        //$this->service = new AgendamentoService;
        $this->service = new AgendamentoService(new Agendamento($di['driverPdo']), new CartorioService(new Cartorio($di['driverPdo'])));
    }
    public function index()
    {
        $data = [];
        $data['page_titulo']    = 'Envios Agendado';
        $data['result']         = $this->service->getPaginateByFilter($_GET['filter'] ?? []);

        $this->render($data, 'agendamentos/index');
    }

    public function new(array $configAlert = [])
    {
        $data = [];
        $data['page_titulo']    = 'Cartórios - Novo';
        $data['configAlert']    = $configAlert;

        $this->render($data, 'cartorios/create');
    }

    public function edit($id = null, $configAlert = [])
    {   
        
        if(!$id && !$_REQUEST['id']){
            $this->redirect('/cartorios');
        }
        $registro = $this->service->getById($id ?? $_REQUEST['id']); //TODO SANITIZAR

        if(!$registro){
            $this->redirect('/cartorios');
        }
        $data = [];
        $data['page_titulo']    = 'Cartórios - Editar';
        $data['page_descricao'] = 'Cartório: '.$registro->nome;
        $data['registro']       = $registro;
        $data['configAlert']    = $configAlert;

        $this->render($data, 'agendamentos/modal-agendamento', false);
    }

    public function save()
    {
        $data = $_POST['agendamento'];
        parse_str(urldecode($_POST['filter']), $filter);

        try {

            $this->service->save($data, $filter['filter']);

            $configAlert = ['isShowAlertSuccess' => true, 'msgs' => ['Agendamento realizado com sucesso!']];
            $configAlert = base64_encode(json_encode($configAlert));

        } catch(ValidationException $e) {
            $msgsError = explode('#', $e->getMessage());

            $configAlert = ['isShowAlertDanger' => true, 'msgs' => $msgsError];
            $configAlert = base64_encode(json_encode($configAlert));
            
            
        } catch(\Exception $e) {
            $configAlert = ['isShowAlertDanger' => true, 'msgs' => [$e->getMessage()]];
            $configAlert = base64_encode(json_encode($configAlert));
            
        }
        $this->redirect('/cartorios?ca='.$configAlert);
    }

    public function delete()
    {
        $id = $_REQUEST['id'] ?? null;

        
        if(!$id){
            $this->redirect('/agendamentos');
        }
        /*
        $registro = $this->service->getById($id); //TODO SANITIZAR

        if(!$registro){
            $this->redirect('/agendamentos');
        }
        */
        try {

            if(!is_array($id)){
                $this->service->delete($id);
            }else{
                foreach($id as $i){
                    $this->service->delete($i);
                }
            }

        }catch(BDException $exception) {
            $configAlert = ['isShowAlertDanger' => true, 'msgs' => ['Falha ao tentar excluir o registro.']];
            $configAlert = base64_encode(json_encode($configAlert));
            
        }
        
        $configAlert = ['isShowAlertSuccess' => true, 'msgs' => ['Registro(s) excluído com sucesso!']];
        $configAlert = base64_encode(json_encode($configAlert));
        
        $this->redirect('/agendamentos?ca='.$configAlert);
    }

    public function sendMails(){
        $this->service->sendMails();

        dd("Envios realizados");
    }

}