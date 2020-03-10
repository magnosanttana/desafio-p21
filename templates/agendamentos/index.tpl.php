<?php 
    $configAlert = json_decode(base64_decode($_GET['ca']), true);
?>
<div class="row">
                
    <div class="col-xs-12">
         <div class="alert alert-danger alert-dismissible" style="display: <?php echo ($configAlert['isShowAlertDanger']) ? 'block' : 'none' ;?>">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="icon fa fa-info"></i> Atenção!</h4>
            <?php 
                foreach($configAlert['msgs'] as $msg){
                    echo"<p> {$msg}</p>";
                }
            ?>
        </div>
        <div class="alert alert-success alert-dismissible" style="display: <?php echo ($configAlert['isShowAlertSuccess']) ? 'block' : 'none' ;?>">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="icon fa fa-info"></i> Atenção!</h4>
            <?php 
                foreach($configAlert['msgs'] as $msg){
                    echo"<p> {$msg}</p>";
                }
            ?>
        </div>

        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Filtrar</h3>
                       <!-- <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                            </button>
                        </div>-->
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form role="form">
                    <div class="box-body">
                        <div class="form-group col-xs-2">
                            <label for="periodo_inicio">Envio De</label>
                            <input type="date" name="filter[periodo_inicio]" class="form-control" id="periodo_inicio" value="<?=old('filter.periodo_inicio')?>">
                        </div>
                        <div class="form-group col-xs-2">
                            <label for="periodo_fim">Até</label>
                            <input type="date" name="filter[periodo_fim]" class="form-control" id="periodo_fim" value="<?=old('filter.periodo_fim')?>">
                        </div>
                        <div class="form-group col-xs-4">
                            <label for="assunto">Assunto</label>
                            <input type="text" name="filter[assunto]" class="form-control" id="assunto" value="<?=old('filter.assunto')?>">
                        </div>
                        
                        <div class="form-group col-xs-2">
                            <label>Status de envio</label>
                            <select class="form-control" name="filter[ativo]">
                                <option value="">Todos</option>
                               
                                <option 
                                <?php if(old('filter.ativo') === "0"): ?>
                                    selected 
                                <?php endif;?>
                                value="0">Aguardando</option>
                                <option 
                                <?php if(old('filter.ativo') == 1): ?>
                                    selected 
                                <?php endif;?>
                                value="1">Enviado</option>
                                <option 
                                <?php if(old('filter.ativo') == 2): ?>
                                    selected 
                                <?php endif;?>
                                value="2">Falha no envio</option>
                            </select>
                        </div>
                    </div>
                    <!-- /.box-body -->

                    <div class="box-footer">
                        <button type="button" class="btn btn-primary pull-right"><i class="fa fa-search"></i> Filtrar</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>


        <div class="box box-success">
        
        <div class="box-header">
            <h3 class="box-title">Envios agendados</h3>

                <div class="box-tools">
                    <form action="/agendamentos/excluir" id="form-exclusao" method="POST"> 
                    <div class="input-group input-group-sm" style="width: 150px;">

                        <div class="input-group-btn">
                        <button type="button" class="btn btn-danger btn-delete-all"><i class="fa fa-trash"></i> Excluir selecionados</button>
                            <div id="container_hidden_id">
                            
                            </div>
                        </div>
                    </div>
                </form>
                </div> 
        </div>
        <!-- /.box-header -->

       

       
        <div class="box-body table-responsive no-padding">
            <?php if(count($result['registros'])): ?>
            <table class="table table-hover">
            <tr>
                <th><input id="chk_all" type="checkbox" value=""></th>
                <th>#</th>
                <th style="width: 35%">Cartório</th>
                <th style="width: 15%">Email</th>
                <th>Assunto do email</th>
                <th>Data para envio</th>
                <th>Status</th>
                <th style="width: 15%">Ações</th>
            </tr>
            <?php $i = 1; foreach($result['registros'] as $registro): ?>
            <tr>
                <th><input class="chk" type="checkbox" value="<?= $registro->id?>"></th>
                <td><?= $i;?></td>
                <td><?= $registro->nome?></td>
                <td><?= $registro->email?></td>
                <td><?= $registro->assunto?></td>
                <td><?= date('d/m/Y', strtotime($registro->data_envio)) ?></td>
                <td>
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
                    <span class="label label-<?= $status['class']?>"><?= $status['txt']?></span>
                </td>
                <td>
                    <a data-url="/agendamentos/editar?id=<?= $registro->id?>" class="btn btn-primary btn-view-agendamento"> <i class="fa fa-eye"></i> Visualizar</a>
                    <a href="#" class="btn btn-danger btn-excluir" data-toggle="modal" data-id="<?= $registro->id;?>" data-entidade="agendamentos" data-target="#modal-default"> <i class="fa fa-trash"></i> Excluir</a>
                </td>
            </tr>
            <?php $i++; endforeach; ?>
           
            </table>
            <?php endif; ?>
               
        </div>
        <!-- /.box-body -->
            <div class="box-footer clearfix">
              <?php echo paginator($_GET['page'] ?? 1, $result['total_pages']); ?>
              
            </div>
        </div>
        <!-- /.box -->
    </div>
</div>


<div class="modal fade" id="modal-default" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span></button>
            <h4 class="modal-title">Confirmação de Exclusão</h4>
        </div>
        <div class="modal-body">
            <p>Deseja realmente excluir este registro? Esta ação não poderá ser desfeita.…</p>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancelar</button>
            <button type="button" class="btn btn-danger" id="btn-confirma-exclusao" data-entidade="" data-id="" data-dismiss="modal">Sim, Excluir</button>
        </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>


<div class="modal fade" id="modal-agendamento-email" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span></button>
            <h4 class="modal-title">xx</h4>
        </div>
       
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<?php //include_once(__DIR__."/modal-agendamento.php"); ?>