<script type="text/javascript">
function funcao_ok(arquivo){
    swal({
        type: "success",
        title: " Restaurado com Sucesso!",
        confirmButtonText: "OK!",
    }, function(isConfirm) 
    {
        if (isConfirm) {window.location.replace(arquivo+"_visualiza.php");} 
    });
  }; 
    
function funcao_restaura(id,arquivo){
    swal({
            title: "Você tem certeza?",
            text: "Este item será restaurado!",
            type: "warning",
            showCancelButton: true,
            confirmButtonText: "Sim, restaurar!",
            cancelButtonText: "Não, Cancelar!",
            closeOnConfirm: false,
            closeOnCancel: false
    }, function(isConfirm) {
        if (isConfirm) 
        {
            window.location.replace("../banco_dados/"+arquivo+"_restaura.php?id="+id);
            //var ajax = AjaxF();
            //url = "../banco_dados/"+arquivo+"_apaga.php?id="+id;
            //ajax.open("GET",url, false);
            //ajax.setRequestHeader("Content-Type", "text/html");
            //ajax.send();
            //funcao_ok(arquivo);
        } 
        else 
        { swal("Cancelado", "Item não restuarado", "error");}
    });
  };
</script>