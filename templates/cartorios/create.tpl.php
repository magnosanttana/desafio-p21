<div class="row">
    <div class="col-md-12">
        <!-- general form elements -->
        <div class="box box-primary ">
            <div class="box-header with-border">
            <h3 class="box-title">Novo Cadastro</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form role="" action="/cartorios/save" method="POST">
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
                            <label class="control-label" for="nome"> Nome</label>
                            <input type="text" class="form-control" id="nome" name="cartorio[nome]" placeholder="Nome" value="<?= old('cartorio.nome');?>">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label" for="razao"> Razão</label>
                            <input type="text" class="form-control" id="razao" name="cartorio[razao]" placeholder="Razão Social" value="<?= old('cartorio.razao');?>">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label" for="documento"> Documento</label>
                            <input type="text" class="form-control" id="documento" name="cartorio[documento]" placeholder="Documento" value="<?= old('cartorio.documento');?>">
                        </div>
                    </div>
                </div>
               
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label" for="cep"> CEP</label>
                            <input type="text" class="form-control" id="cep" name="cartorio[cep]" placeholder="CEP" value="<?= old('cartorio.cep');?>">
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="form-group">
                            <label class="control-label" for="endereco"> Endereço</label>
                            <input type="text" class="form-control" id="endereco" name="cartorio[endereco]" placeholder="Endereço" value="<?= old('cartorio.endereco');?>">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-5">
                        <div class="form-group">
                            <label class="control-label" for="bairro"> Bairro</label>
                            <input type="text" class="form-control" id="bairro" name="cartorio[bairro]" placeholder="Bairro" value="<?= old('cartorio.bairro');?>">
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group">
                            <label class="control-label" for="cidade"> Cidade</label>
                            <input type="text" class="form-control" id="cidade" name="cartorio[cidade]" placeholder="Cidade" value="<?= old('cartorio.cidade');?>">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="control-label" for="uf"> UF <?= old('cartorio.uf');?></label>
                            <select class="form-control" id="uf" name="cartorio[uf]">
                                <option value="AC" <?php if(old('cartorio.uf') == 'AC') echo 'selected'; ?> >AC</option>
                                <option value="AL" <?php if(old('cartorio.uf') == 'AL') echo 'selected'; ?> >AL</option>
                                <option value="AP" <?php if(old('cartorio.uf') == 'AP') echo 'selected'; ?> >AP</option>
                                <option value="AM" <?php if(old('cartorio.uf') == 'AM') echo 'selected'; ?> >AM</option>
                                <option value="BA" <?php if(old('cartorio.uf') == 'BA') echo 'selected'; ?> >BA</option>
                                <option value="CE" <?php if(old('cartorio.uf') == 'CE') echo 'selected'; ?> >CE</option>
                                <option value="DF" <?php if(old('cartorio.uf') == 'DF') echo 'selected'; ?> >DF</option>
                                <option value="ES" <?php if(old('cartorio.uf') == 'ES') echo 'selected'; ?> >ES</option>
                                <option value="GO" <?php if(old('cartorio.uf') == 'GO') echo 'selected'; ?> >GO</option>
                                <option value="MA" <?php if(old('cartorio.uf') == 'MA') echo 'selected'; ?> >MA</option>
                                <option value="MT" <?php if(old('cartorio.uf') == 'MT') echo 'selected'; ?> >MT</option>
                                <option value="MS" <?php if(old('cartorio.uf') == 'MS') echo 'selected'; ?> >MS</option>
                                <option value="MG" <?php if(old('cartorio.uf') == 'MG') echo 'selected'; ?> >MG</option>
                                <option value="PA" <?php if(old('cartorio.uf') == 'PA') echo 'selected'; ?> >PA</option>
                                <option value="PB" <?php if(old('cartorio.uf') == 'PB') echo 'selected'; ?> >PB</option>
                                <option value="PR" <?php if(old('cartorio.uf') == 'PR') echo 'selected'; ?> >PR</option>
                                <option value="PE" <?php if(old('cartorio.uf') == 'PE') echo 'selected'; ?> >PE</option>
                                <option value="PI" <?php if(old('cartorio.uf') == 'PI') echo 'selected'; ?> >PI</option>
                                <option value="RJ" <?php if(old('cartorio.uf') == 'RJ') echo 'selected'; ?> >RJ</option>
                                <option value="RN" <?php if(old('cartorio.uf') == 'RN') echo 'selected'; ?> >RN</option>
                                <option value="RS" <?php if(old('cartorio.uf') == 'RS') echo 'selected'; ?> >RS</option>
                                <option value="RO" <?php if(old('cartorio.uf') == 'RO') echo 'selected'; ?> >RO</option>
                                <option value="RR" <?php if(old('cartorio.uf') == 'RR') echo 'selected'; ?> >RR</option>
                                <option value="SC" <?php if(old('cartorio.uf') == 'SC') echo 'selected'; ?> >SC</option>
                                <option value="SP" <?php if(old('cartorio.uf') == 'SP') echo 'selected'; ?> >SP</option>
                                <option value="SE" <?php if(old('cartorio.uf') == 'SE') echo 'selected'; ?> >SE</option>
                                <option value="TO" <?php if(old('cartorio.uf') == 'TO') echo 'selected'; ?> >TO</option>
                            </select>
                        </div>
                    </div>
                </div>
               
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label" for="razao"> Telefone</label>
                            <input type="text" class="form-control" id="telefone" name="cartorio[telefone]" placeholder="Telefone" value="<?= old('cartorio.telefone');?>">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label" for="email"> Email</label>
                            <input type="email" class="form-control" id="email" name="cartorio[email]" placeholder="Email" value="<?= old('cartorio.email');?>">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label" for="tabeliao"> Tabelião</label>
                            <input type="text" class="form-control" id="tabeliao" name="cartorio[tabeliao]" placeholder="Tabelião" value="<?= old('cartorio.tabeliao');?>">
                             <!-- <p class="help-block">Example block-level help text here.</p> -->
                        </div>
                    </div>
                </div>
                
                <div class="checkbox">
                <label>
                    <input type="checkbox" name="cartorio[ativo]" value="1"> Cartório Ativo
                </label>
                </div>
            </div>
            <!-- /.box-body -->

            <div class="box-footer">
                <button type="submit" class="btn btn-primary">Salvar</button>
                <a href="/cartorios" class="btn btn-default">Cancelar</a>
            </div>
            </form>
        </div>
        <!-- /.box -->
    </div>
</div>
