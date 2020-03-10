    <div class="modal-dialog">
        
        <div class="modal-content">
            <form role="" action="/agendamentos/agendar" method="POST">
                <div class="modal-header">
                    <h4 class="modal-title">Detalhe</h4>
                </div>
                <div class="modal-body">
                    
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label" for="nome"> Cartório: </label> <?=$registro->nome?><br/>
                                    <label class="control-label" for="nome"> Email: </label><?=$registro->email?>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label" for="nome"> Assunto do email</label><br/>
                                    <?=$registro->assunto?>
                                </div>
                            </div>
                            
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label" for="razao"> Conteúdo</label><hr/><br/>
                                    <?=html_entity_decode($registro->conteudo)?>
                                    <br/><hr/>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label" for="nome"> Data para envio: </label>
                                    <?= date('d/m/Y', strtotime($registro->data_envio)) ?>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                <?php
                                    switch($registro->status){
                                        case 0:
                                            $status['class'] = 'info';
                                            $status['txt']   = 'Agendado';
                                        break;
                                        case 1:
                                            $status['class'] = 'success';
                                            $status['txt']   = 'Enviado';
                                        break;
                                        case 2:
                                            $status['class'] = 'danger';
                                            $status['txt']   = 'Falha no envio';
                                        break;
                                    }
                                ?>
                                <strong>Status:</strong> <span class="label label-<?= $status['class']?>"><?= $status['txt']?></span><br/>
                                <?=$registro->erro?>
                                </div>
                            </div>
                        
                        </div>
                   
                </div>
                <div class="modal-footer">
                    <!-- <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancelar</button> -->
                    <button type="submit" class="btn btn-primary" data-dismiss="modal" data-id="" >Fechar</button>
                </div>
            </div>
         </form>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
 