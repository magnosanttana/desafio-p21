<?php

namespace App\Controllers;

use MSS\Controller;
use App\Services\CartorioService;
use App\Exceptions\BDException;
use App\Exceptions\ValidationException;
use App\Models\Cartorio;

class CartoriosController extends Controller
{
    protected $service;

    public function __construct($di)
    {
        $this->service = new CartorioService(new Cartorio($di['driverPdo']));
    }
    public function index()
    {
        $data = [];
        $data['page_titulo']    = 'Cartórios';
        $data['page_descricao'] = '';
        $data['result']         = $this->service->getPaginateByFilter($_GET['filter'] ?? []);
        $data['cidades']        = $this->service->getCidades();
        $data['ufs']            = $this->service->getUfs();

        $this->render($data, 'cartorios/index');
    }

    public function new(array $configAlert = [])
    {
        $data = [];
        $data['page_titulo']    = 'Cartórios - Novo';
        $data['page_descricao'] = '';
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

        $this->render($data, 'cartorios/edit');
    }

    public function save()
    {
        $data = $_POST['cartorio'];
        try {

            $this->service->save($data);

            $configAlert = ['isShowAlertSuccess' => true, 'msgs' => ['Registro salvo com sucesso!']];
            $configAlert = base64_encode(json_encode($configAlert));

        } catch(ValidationException $e) {

            $msgsError = explode('#', $e->getMessage());
            if(!$data['id']){
                $this->new(['isShowAlertWarning' => true, 'msgs' => $msgsError]);
            }else{
                $this->edit($data['id'], ['isShowAlertWarning' => true, 'msgs' => $msgsError]);
            }
            
            
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
            $this->redirect('/cartorios');
        }
        $registro = $this->service->getById($id); //TODO SANITIZAR

        if(!$registro){
            $this->redirect('/cartorios');
        }
        try {

            $this->service->delete($id);

        }catch(BDException $exception) {
            $configAlert = ['isShowAlertDanger' => true, 'msgs' => ['Falha ao tentar excluir o registro.']];
            $configAlert = base64_encode(json_encode($configAlert));
            
        }
        
        $configAlert = ['isShowAlertSuccess' => true, 'msgs' => ['Registro excluído com sucesso!']];
        $configAlert = base64_encode(json_encode($configAlert));
        
        $this->redirect('/cartorios?ca='.$configAlert);
    }

    public function importXml($configAlert = [])
    {
        $data = [];
        $result = null;
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            try {

                $result = $this->service->importXml($_FILES['xml']);
    
            } catch(ValidationException $e) {

                $msgsError = explode('#', $e->getMessage());
                $configAlert = ['isShowAlertWarning' => true, 'msgs' => $msgsError];
                
            } catch(\Exception $e) {
                $configAlert = ['isShowAlertDanger' => true, 'msgs' => ['Falha ao tentar salvar o registro.']];
                
            }
        }
        $data['configAlert']    = $configAlert;
        $data['page_titulo']    = 'Cartórios - Importação XML';
        $data['result']         = $result;

        $this->render($data, 'cartorios/import-xml');
        
    }

    public function exportXls(){
        $this->service->exportXls($_GET['filter'] ?? []);
    }
}