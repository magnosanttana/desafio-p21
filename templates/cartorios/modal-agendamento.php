<div class="modal fade" id="modal-agendamento-email" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <form role="" action="/agendamentos/agendar" method="POST">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                    <h4 class="modal-title">Agendamento de envio de mensagem para os cadastrados</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="callout callout-info">
                                <h4>Atenção!</h4>

                                <p>A mensagem será enviada para os Cartórios listados na tela anterior, será respeitado eventuais filtros aplicado.</p>
                            </div>
                        </div>
                    </div>
                    
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label" for="nome"> Assunto do email</label>
                                    <input type="text" class="form-control" id="nome" name="agendamento[assunto]" placeholder="Nome" value="">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label" for="razao"> Conteúdo</label>
                                    <textarea id="editor1" name="agendamento[conteudo]" rows="5" cols="40">
                                    
                                    </textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label" for="nome"> Data para envio</label>
                                    <input type="date" class="form-control" id="data" name="agendamento[data_envio]" placeholder="Nome" value="">
                                </div>
                            </div>
                        
                        </div>
                        <input type="hidden" name="filter" value="<?=$_SERVER['QUERY_STRING'];?>"/>
                   
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary" data-id="" ><i class="fa fa-clock-o"></i> Agendar envio</button>
                </div>
            </div>
         </form>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>