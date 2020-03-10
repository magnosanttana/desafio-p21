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
                        <h3 class="box-title">Pesquisar</h3>
                       <!-- <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                            </button>
                        </div>-->
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form role="form">
                    <div class="box-body">
                        <div class="form-group col-xs-4">
                            <label for="exampleInputEmail1">Nome</label>
                            <input type="text" name="filter[nome]" class="form-control" id="exampleInputEmail1" placeholder="Nome" value="<?=old('filter.nome')?>">
                        </div>
                        <div class="form-group col-xs-2">
                            <label for="exampleInputEmail1">Cidade</label>
                            <select class="form-control" name="filter[cidade]">
                                <option value="">Todos</option>
                                <?php 
                                foreach ($cidades as $cidade): ?>
                                    <option 
                                    <?php if(old('filter.cidade') == $cidade->cidade): ?>
                                    selected 
                                <?php endif;?>
                                    value="<?=$cidade->cidade?>"><?=$cidade->cidade?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group col-xs-2">
                            <label>UF</label>
                            <select class="form-control" name="filter[uf]">
                                <option value="">Todos</option>
                                <?php 
                                foreach ($ufs as $uf): ?>
                                    <option 
                                    <?php if(old('filter.uf') == $uf->uf): ?>
                                    selected 
                                <?php endif;?>
                                    value="<?=$uf->uf?>"><?=$uf->uf?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group col-xs-2">
                            <label>Cadastro completo</label>
                            <select class="form-control" name="filter[completo]">
                                <option value="">Todos</option>
                               
                                <option 
                                <?php if(old('filter.completo') === "0"): ?>
                                    selected 
                                <?php endif;?>
                                value="0">NÃO</option>
                                <option 
                                <?php if(old('filter.completo') == 1): ?>
                                    selected 
                                <?php endif;?>
                                value="1">SIM</option>
                            </select>
                        </div>
                        <div class="form-group col-xs-2">
                            <label>Status</label>
                            <select class="form-control" name="filter[ativo]">
                                <option value="">Todos</option>
                               
                                <option 
                                <?php if(old('filter.ativo') === "0"): ?>
                                    selected 
                                <?php endif;?>
                                value="0">INATIVO</option>
                                <option 
                                <?php if(old('filter.ativo') == 1): ?>
                                    selected 
                                <?php endif;?>
                                value="1">ATIVO</option>
                            </select>
                        </div>
                    </div>
                    <!-- /.box-body -->

                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary pull-right"><i class="fa fa-search"></i> Pesquisar</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>


        <div class="box box-success">
        
        <div class="box-header">
            <h3 class="box-title">Cartórios cadastrados</h3>

                <div class="box-tools">
                    <div class="input-group input-group-sm" style="width: 150px;">

                        <div class="input-group-btn">
                        <a href="/cartorios/novo" class="btn btn-success"><i class="fa fa-plus-circle"></i> Cadastrar Manual</a>
                        <a href="/cartorios/importar-xml" class="btn btn-success"><i class="fa fa-upload"></i> Importar XML</a>
                        <a href="#" class="btn btn-info btn-agendamento" data-toggle="modal" data-target="#modal-agendamento-email"><i class="fa fa-envelope"></i> Enviar Email</a>
                        
                        </div>
                    </div>
                </div> 
        </div>
        <!-- /.box-header -->

       

       
        <div class="box-body table-responsive no-padding">
            <?php if(count($result['registros'])): ?>
            <table class="table table-hover">
            <tr>
                <th>#</th>
                <th style="width: 35%">Nome</th>
                <th style="width: 15%">Bairro/Cidade</th>
                <th>Telefone</th>
                <th>Completo?</th>
                <th>Ativo</th>
                <th style="width: 15%">Ações</th>
            </tr>
            <?php $i = 1; foreach($result['registros'] as $registro): ?>
            <tr>
                <td><?= $i;?></td>
                <td><?= $registro->nome?></td>
                <td><?= $registro->bairro?><br/><?= $registro->cidade?>-<?= $registro->uf?></td>
                <td><?= $registro->telefone?></td>
                <td>
                    <?php
                        $isComplet = true;
                        if(!trim($registro->telefone) || !trim($registro->email))
                            $isComplet = false;
                    ?>
                    <span class="label label-<?= ($isComplet) ? 'info' : 'warning' ?>"><?= ($isComplet) ? 'SIM' : 'NAO' ?></span>
                </td>
                <td>
                    <span class="label label-<?= ($registro->ativo) ? 'success' : 'danger' ?>"><?= ($registro->ativo) ? 'SIM' : 'NAO' ?></span>
                </td>
                <td>
                    <a href="/cartorios/editar?id=<?= $registro->id?>" class="btn btn-default"> <i class="fa fa-edit"></i> Editar</a>
                    <a href="#" class="btn btn-danger btn-excluir" data-toggle="modal" data-id="<?= $registro->id;?>" data-entidade="cartorios" data-target="#modal-default"> <i class="fa fa-trash"></i> Excluir</a>
                </td>
            </tr>
            <?php $i++; endforeach; ?>
           
            </table>
            <?php endif; ?>
                <div class="col-xs-12">
                    <a href="/cartorios/exportar-xls?<?php echo $_SERVER['QUERY_STRING'];?>" target="_blank" class="btn btn-primary pull-right"><i class="fa fa-download"></i> Exportar resultado (XLS)</a><br/><br/>
                </div>
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

<?php include_once(__DIR__."/modal-agendamento.php"); ?>