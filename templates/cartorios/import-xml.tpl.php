<div class="row">
    <div class="col-md-12">
        <!-- general form elements -->
        <div class="box box-primary ">
            <div class="box-header with-border">
            <h3 class="box-title">Importação de XML</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form role="" action="/cartorios/importar-xml" method="POST" enctype="multipart/form-data">
            <div class="box-body">
                <!-- <div class="form-group has-success">
                    <label class="control-label" for="inputSuccess"> Titulo</label>
                    <input type="text" class="form-control" id="inputSuccess" placeholder="O Município" readonly>
                    <span class="help-block">Help block with success</span>
                </div>-->
               
                <div class="alert alert-warning alert-dismissible" style="display: <?php echo ($configAlert['isShowAlertWarning']) ? 'block' : 'none' ;?>">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h4><i class="icon fa fa-info"></i> Atenção!</h4>
                    <?php 
                        foreach($configAlert['msgs'] as $msg){
                            echo"<p> - {$msg}</p>";
                        }
                    ?>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label" for="xml"> Selecione o XML fornecido pelo CNJ</label>
                            <input type="file" name="xml" id="xml" accept="text/xml"required>
                        </div>
                    </div>
                </div>
               
                
            </div>
            <!-- /.box-body -->

            <div class="box-footer">
                <button type="submit" class="btn btn-primary">Importar</button>
                <a href="/cartorios" class="btn btn-default">Cancelar</a>
            </div>
            </form>

            
        </div>
        <!-- /.box -->
        <?php if($result): ?>
        <div class="box box-solid">
            <div class="box-header with-border">
              <h3 class="box-title">Resultado da importação: <?=$result['total']?> registros presentes no XML. Destes, <?=count($result['novos'])?> foram novos registros</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="box-group" id="accordion">
                <!-- we are adding the .panel class so bootstrap.js collapse plugin detects it -->
                <div class="panel box box-danger">
                  <div class="box-header with-border">
                    <h4 class="box-title">
                      <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" class="" aria-expanded="true" class="collapsed">
                        <?=count($result['atualizadosFalha'])?> registros falharam
                      </a>
                    </h4>
                  </div>
                  <div id="collapseTwo" class="panel-collapse collapse in" aria-expanded="true" style="">
                    <div class="box-body">
                        <table class="table table-hover">
                            <tr>
                                <th>#</th>
                                <th style="width: 60%;">Registro</th>
                                <th>Motivo</th>
                                <th>Detalhes</th>
                            </tr>
                            <?php $i = 1; foreach($result['atualizadosFalha'] as $registro): ?>
                            <tr>
                                <td><?=$i;?></td>
                                <td><?=json_encode($registro['cartorio']);?></td>
                                <td>
                                    <span class="label label-<?php echo ($registro['motivo'] == 'Não passou na validação') ?  'warning' : 'danger' ;?>">
                                        <?=$registro['motivo']?>
                                    </span>
                                </td>
                                <td><?=$registro['detalhes']?></td>
                            </tr>
                            <?php $i++; endforeach; ?>
                        </table>
                    </div>
                  </div>
                </div>
                <div class="panel box box-success">
                  <div class="box-header with-border">
                    <h4 class="box-title">
                      <a data-toggle="collapse" data-parent="#accordion" href="#collapseThree" class="collapsed" aria-expanded="false">
                      <?=$result['atualizadosSucesso']?> foram atualizados com sucesso
                      </a>
                    </h4>
                  </div>
                  <div id="collapseThree" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                    <div class="box-body">
                        <h3>Registros novos (não necessariamente com êxito na importação)</h3>
                        <table class="table table-hover">
                            <tr>
                                <th>#</th>
                                <th>Nome</th>
                                <th>Documento</th>
                                <th>Bairro</th>
                                <th>Cidade/UF</th>
                            </tr>
                            <?php $i = 1; foreach($result['novos'] as $registro): ?>
                            <tr>
                                <td><?=$i?></td>
                                <td><?=$registro['nome']?></td>
                                <td><?=$registro['documento']?></td>
                                <td><?=$registro['bairro']?></td>
                                <td><?=$registro['cidade']?>/<?=$registro['uf']?></td>
                            </tr>
                            <?php $i++; endforeach; ?>
                            
                        </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!-- /.box-body -->
          </div>
        <?php endif; ?>
    </div>
</div>
