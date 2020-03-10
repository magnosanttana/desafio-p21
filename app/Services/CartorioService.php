<?php

namespace App\Services;

use MSS\Service;
use Respect\Validation\Validator as v;
use Respect\Validation\Exceptions\NestedValidationException;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use App\Exceptions\ValidationException;
use App\Exceptions\BDException;
use App\Models\Cartorio;

class CartorioService extends Service
{
    protected $model;

    public function __construct(Cartorio $model)
    {
        $this->model = $model;
    }

    public function save(array $dados)
    {
        $dados = $this->sanitize($dados);
        
        if(!$dados['ativo']){
            $dados['ativo'] = 0;
        }

        try{
            $this->validate($dados);
            $dados['data_ultima_alteracao'] = date("Y-m-d H:i:s");
            $this->model->save($dados);
        }catch(NestedValidationException $e) {
            $errors = $e->findMessages([
                'email' => 'O campo {{name}} deve ser um email válido',
                'length' => 'O campo {{name}} deve ter um comprimento entre {{minValue}} e {{maxValue}}',
            ]);

            $msgsError = $e->getMessages();

            throw new ValidationException(implode('#',$msgsError));

        }catch(\Exception $e){
            if($e->getCode() == "23000"){
                throw new ValidationException('O número de documento informado já encontra-se vinculado a um registro no banco de dados.');
            }else{
                dd($e);
                throw new \Exception('Falha ao tentar salvar no banco.');
            }
            
        }

    }

    public function importXml($fileXml){
        
        try{
            $this->validateXml($fileXml);

        }catch(NestedValidationException $e){

            $errors = $exception->findMessages([
                'type' => 'O arquivo precisa ser do tipo XML',
                'length' => 'O campo {{name}} deve ter um comprimento entre {{minValue}} e {{maxValue}}',
            ]);

            $msgsError = $e->getMessages();

            throw new ValidationException(implode('#',$msgsError));

        }

        $xml = simplexml_load_file($fileXml['tmp_name']);

        if(!isset($xml->cartorio)){
            throw new ValidationException('O XML não possui o formato experado, verifique se selecionou o arquivo corretamente.');
        }

        $cartorios = $xml->cartorio;

        $dados = [];
        foreach($cartorios as $cartorio){
            $dados[(string)$cartorio->documento]['nome']       = (string) $cartorio->nome;
            $dados[(string)$cartorio->documento]['razao']      = (string) $cartorio->razao;
            $dados[(string)$cartorio->documento]['documento']  = (string) $cartorio->documento;
            $dados[(string)$cartorio->documento]['cep']        = (string) $cartorio->cep;
            $dados[(string)$cartorio->documento]['endereco']   = (string) $cartorio->endereco;
            $dados[(string)$cartorio->documento]['bairro']     = (string) $cartorio->bairro;
            $dados[(string)$cartorio->documento]['cidade']     = (string) $cartorio->cidade;
            $dados[(string)$cartorio->documento]['uf']         = (string) $cartorio->uf;
            $dados[(string)$cartorio->documento]['tabeliao']   = (string) $cartorio->tabeliao;
            $dados[(string)$cartorio->documento]['ativo']      = (int) $cartorio->ativo;
        }

        return $this->createAll($dados);

    }

    protected function createAll(array $arrCartorios)
    {
        $arrCartoriosSucesso = [];
        $arrCartoriosFalha = [];
        $arrCartoriosNovos = [];
        $countNovos = 0;

        $this->model->getDriver()->getPDO()->beginTransaction();
        foreach($arrCartorios as $doc => $dados){
            $data = [];

            try{
                
                $data = $this->sanitize($dados);
                $this->validate($dados);

                $verificaRegistro = $this->model->findAll('*', 'id', 'asc', ['documento' => $data['documento']]);

                if(count($verificaRegistro)){
                    $data['id'] = $verificaRegistro[0]->id;
                }else{
                    $countNovos++;
                    $arrCartoriosNovos[] = $data;
                }

                $this->model->save($data);
                $arrCartoriosSucesso[] = $data;
            }catch(NestedValidationException $e){
                $cartorioFalha['motivo']    = 'Não passou na validação';
                $cartorioFalha['detalhes']  = $e->getFullMessage();
                $cartorioFalha['cartorio']  = $data;
                $arrCartoriosFalha[] = $cartorioFalha;
            }catch(\Exception $e){
                $cartorioFalha['motivo']    = 'Falha no banco de dados';
                $cartorioFalha['detalhes']  = $e->getMessage();
                $cartorioFalha['cartorio']  = $data;
                $arrCartoriosFalha[] = $cartorioFalha;
            }

        }

        $this->model->getDriver()->getPDO()->commit();

        return [
            'atualizadosSucesso' => count($arrCartoriosSucesso),
            'atualizadosFalha' => $arrCartoriosFalha,
            'novos' => $arrCartoriosNovos,
            'total' => count($arrCartorios)
        ];
    }

    public function validate($data)
    {
        $validator = v::key('nome', v::stringType()->length(3, 255))
                        ->key('razao', v::stringType()->length(5, 255))
                        ->key('documento', v::stringType()->length(11, 15))
                        ->key('cep', v::stringType()->length(8, 8))
                        ->key('endereco', v::stringType()->length(4, 255))
                        ->key('bairro', v::stringType()->length(2, 70))
                        ->key('cidade', v::stringType()->length(2, 70))
                        ->key('uf', v::stringType()->length(2, 2))
                        ->key('tabeliao', v::stringType()->length(4, 150));
                        //->validate($data);
        $validator->assert($data);

    }

    public function validateXml($fileXml)
    {
        $validator = v::key('tmp_name', v::file())
                        ->key('type', v::equals('text/xml'));
                       // ->validate($fileXml);

        $validator->assert($fileXml);

    }

    public function getCidades()
    {
        return $this->model->getCidades();
    }

    public function getUfs()
    {
        return $this->model->getUfs();
    }

    public function getAllByFilter($filtros = [])
    {
        $filtros = $this->sanitize($filtros);
        $cond = $this->getConditionsByFilter($filtros);

        return $this->model->findAll('*', 'id', 'ASC', $cond);
    }

    public function getPaginateByFilter($filtros = [])
    {
        $filtros = $this->sanitize($filtros);
        $cond = $this->getConditionsByFilter($filtros);
        
        $total = $this->model->findAll('count(*) as total', 'id', 'ASC', $cond);
        $totalPerPage = $paginator['per_page'] ?? 50;
        $page = $_GET['page'] ?? 1;
        $totalPages = $total[0]->total / $totalPerPage;

        $init = ($page - 1) * $totalPerPage;

        $result = $this->model->findAll('*', 'id', 'ASC', $cond, ['init' => $init, 'end' => 50]);

        return ['registros' => $result, 'total_pages' => ceil($totalPages)];
    }

    protected function getConditionsByFilter($filtros){
        $cond = [];
        
        if(!empty($filtros['nome'])){
            $cond['nome'] = $filtros['nome'];
        }

        if(!empty($filtros['cidade'])){
            $cond['cidade'] = $filtros['cidade'];
        }

        if(!empty($filtros['uf'])){
            $cond['uf'] = $filtros['uf'];
        }

        if(isset($filtros['ativo']) && $filtros['ativo'] !== ''){
            $cond['ativo'] = $filtros['ativo'];
        }

        if(isset($filtros['completo']) && $filtros['completo'] !== ''){
            if($filtros['completo'] == 1){
                $cond['telefone!'] = '';
                $cond['email!'] = '';
            }else{
                $cond['telefone'] = '';
                $cond['email'] = '';
            }
        }

        return $cond;
    }

    public function exportXls($filtros = [])
    {
        $registros = $this->getAllByFilter($filtros);
        
        $spreadsheet = new Spreadsheet(); 
        $sheet = $spreadsheet->getActiveSheet(); 
        $sheet->setTitle('Lista Atualizada de Cartórios');

        $sheet->setCellValue('A1', 'NOME'); 
        $sheet->setCellValue('B1', 'RAZAO'); 
        $sheet->setCellValue('C1', 'DOCUMENTO');
        $sheet->setCellValue('D1', 'CEP');
        $sheet->setCellValue('E1', 'ENDEREÇO');
        $sheet->setCellValue('F1', 'BAIRRO');
        $sheet->setCellValue('G1', 'CIDADE');
        $sheet->setCellValue('H1', 'UF');
        $sheet->setCellValue('I1', 'TELEFONE');
        $sheet->setCellValue('J1', 'EMAIL');
        $sheet->setCellValue('K1', 'TABELIÃO');
        $sheet->setCellValue('L1', 'ATIVO');

        for($i = "A"; $i <= "L"; $i++){
            $sheet->getColumnDimension($i)->setAutoSize(true);
            $sheet->getStyle($i.'1')->getFont()->setBold(true);
        }

        $linha = 2;
        
        foreach($registros as $registro){
            $sheet->setCellValue('A'.$linha, html_entity_decode($registro->nome));
            $sheet->setCellValue('B'.$linha, html_entity_decode($registro->razao));
            $sheet->setCellValue('C'.$linha, $registro->documento);
            $sheet->setCellValue('D'.$linha, $registro->cep);
            $sheet->setCellValue('E'.$linha, html_entity_decode($registro->endereco));
            $sheet->setCellValue('F'.$linha, html_entity_decode($registro->cidade));
            $sheet->setCellValue('G'.$linha, html_entity_decode($registro->bairro));
            $sheet->setCellValue('H'.$linha, $registro->uf);
            $sheet->setCellValue('I'.$linha, $registro->telefone);
            $sheet->setCellValue('J'.$linha, $registro->email);
            $sheet->setCellValue('K'.$linha, html_entity_decode($registro->tabeliao));
            $sheet->setCellValue('L'.$linha, ($registro->ativo) ? 'SIM' : 'NAO');

            $linha++;
        }
	
        header('Content-Type: application/vnd.ms-excel'); 
        header('Content-Disposition: attachment;filename="CARTORIOS-'.date('d-m-Y H:i:s').'.xls"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output'); 
    }
}