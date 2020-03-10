<?php

namespace App\Services;

use MSS\Service;
use Respect\Validation\Validator as v;
use Respect\Validation\Exceptions\NestedValidationException;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception as MailException;

use App\Exceptions\ValidationException;
use App\Exceptions\BDException;
use App\Models\Agendamento;
use App\Models\EnvioAgendado;
use App\Services\CartorioService;

class AgendamentoService extends Service
{
    protected $model;
    protected $serviceCartorio;

    public function __construct(Agendamento $model, CartorioService $cartorioService)
    {
        $this->model = $model;
        $this->serviceCartorio = $cartorioService;
    }

    public function save(array $dados, $filtro = [])
    {
        //$agendamento = $this->sanitize($dados);
        $agendamento = $dados;
        $this->model->getDriver()->getPDO()->beginTransaction();
        try{
            $this->validate($agendamento);
            
            //$serviceCartorio = new CartorioService();
            $cartorios = $this->serviceCartorio->getAllByFilter($filtro);
            $countAgendado = 0;
            foreach($cartorios as $cartorio){
                $dataAgendamento = $agendamento;
                if($cartorio->email){
                    $dataAgendamento['nome']              = $cartorio->nome;
                    $dataAgendamento['email']             = $cartorio->email;

                    $this->model->save($dataAgendamento);
                    $countAgendado++;
                }
                
            }

            if(!$countAgendado){
                throw new ValidationException('#Nenhum dos cartórios selecionados possui email no cadastro.');
            }
            

        }catch(NestedValidationException $e) {
            $errors = $e->findMessages([
                'date' => 'O campo {{name}} deve ser uma data válida',
                'length' => 'O campo {{name}} deve ter um comprimento entre {{minValue}} e {{maxValue}}',
            ]);
            $msgsError = $e->getMessages();
            $this->model->getDriver()->getPDO()->rollBack();

            throw new ValidationException(implode('#',$msgsError));

        }catch(ValidationException $e) {
            $this->model->getDriver()->getPDO()->rollBack();
            throw new ValidationException($e->getMessage());

        }catch(\Exception $e) {
            $this->model->getDriver()->getPDO()->rollBack();
            throw new \Exception('Falha ao tentar realizar agendamento.');

        }

        $this->model->getDriver()->getPDO()->commit();

    }

    public function validate($data)
    {
        $validator = v::key('assunto', v::stringType()->length(3, 255))
                        ->key('conteudo', v::stringType()->length(5, null))
                        ->key('data_envio', v::date());
                        //->validate($data);
        $validator->assert($data);

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
        $totalPerPage = $paginator['per_page'] ?? 5;
        $page = $_GET['page'] ?? 1;
        $totalPages = $total[0]->total / $totalPerPage;

        $init = ($page - 1) * $totalPerPage;

        $result = $this->model->findAll('*', 'data_envio', 'DESC', $cond, ['init' => $init, 'end' => 5]);

        return ['registros' => $result, 'total_pages' => ceil($totalPages)];
    }

    public function sendMails(){
        $countAgendadosTotal = $this->model->findAll('count(*) as total', 'id', 'ASC', ['status' => 0]);
        $countAgendadosTotal = $countAgendadosTotal[0]->total;

        dp('Total de emails agendado: '.$countAgendadosTotal);

        $agendadosHoje = $this->model->findAll('*', 'id', 'ASC', ['status' => 0, 'data_envio' => date("Y-m-d")], ['init' => 0, 'end' => getenv('MAX_SEND_MAIL_EXEC')]);

        dp('Total de emails agendado para hoje ('.date("Y-m-d").') e ainda não enviado: '.count($agendadosHoje));

        if(count($agendadosHoje)){
            
            $countFail = 0;
            foreach($agendadosHoje as $info){
                $mail = $this->getObjMail();

                try {
                    $mail->addAddress($info->email, $info->nome); 
                    $assunto = utf8_decode($info->assunto);
                    $mail->Subject = $assunto;
                    $mail->Body    = html_entity_decode($info->conteudo);

                    //$mail->send(); COMENTADO PARA NAO DISPARAR DE VERDADE

                    $this->model->save(['id' => $info->id, 'status' => 1]);
                    sleep(1);
                } catch (MailException $e) {
                    $this->model->save(['id' => $info->id, 'status' => 2, 'erro' => $e->getMessage()]);
                    $countFail++;

                }
            }

            dp('Total de envios com falha: '.$countFail);
        }

    }

    protected function getObjMail(){
        $mail = new PHPMailer(true);

        $mail->SMTPDebug = SMTP::DEBUG_OFF;
        $mail->isSMTP();
        $mail->Host       = getenv('SMTP_HOST'); 
        $mail->SMTPAuth   = true; 
        $mail->Username   = getenv('SMTP_USER');
        $mail->Password   = getenv('SMTP_PASS'); 
        $mail->SMTPSecure = getenv('SMTP_PROTC_SECURITY');
        $mail->Port       = getenv('SMTP_PORT'); 

        $mail->setFrom(getenv('SMTP_FROM_DEFAULT_EMAIL'), getenv('SMTP_FROM_DEFAULT_NAME'));
        $mail->isHTML(true);

        return $mail;
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

}