$( document ).ready(function() {

    $(".btn-excluir").click(function(){
        let id       = $(this).attr('data-id');
        let entidade = $(this).attr('data-entidade');
        $("#btn-confirma-exclusao").attr('data-id', id);
        $("#btn-confirma-exclusao").attr('data-entidade', entidade);
    })

    $("#btn-confirma-exclusao").click(function(){
        let id       = $(this).attr('data-id');
        let entidade = $(this).attr('data-entidade');

        window.location = '/'+entidade+'/excluir?&id='+id;
    })

    $(".btn-agendamento").click(function(){
        CKEDITOR.replace('editor1');
    })

    $(".btn-view-agendamento").click(function(event){
        event.preventDefault();
        let url = $(this).attr('data-url');
        $.get( url, function( data ) {
            $("#modal-agendamento-email" ).html( data );
            $('#modal-agendamento-email').modal('show')

          });
        
    })

    $("#chk_all").click(function(){

        if($(this).is(":checked")){
            
            $( ".chk" ).each(function() {
                $(this).prop('checked', true);
                id = $(this).val();
                $("#container_hidden_id").append('<input type="hidden" id="chk_'+id+'" name="id[]" value="'+id+'"/>');
               
            });
            
        }else{
            $(".chk").prop('checked', false);
            $("#container_hidden_id").html('');
            
        }

    });

    $(".chk").click(function(){
        let id = $(this).val();
        if($(this).is(":checked")){
            $("#container_hidden_id").append('<input type="hidden" id="chk_'+id+'" name="id[]" value="'+id+'"/>');
        }else{
            $("#chk_"+id).remove();
            
        }
    })

    $(".btn-delete-all").click(function(e){

        if(confirm("Deseja realmente excluir todos os registros selecionados?")){
            $("#form-exclusao").submit();
        }
    })
    
    
});