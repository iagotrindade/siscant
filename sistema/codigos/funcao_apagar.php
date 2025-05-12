<script type="text/javascript">
    function funcao_ok(arquivo) {
        swal({
            type: "success",
            title: " Apagado com Sucesso!",
            confirmButtonText: "OK!",
        }, function(isConfirm) {
            if (isConfirm) {
                window.location.replace(arquivo + "_visualiza.php");
            }
        });
    };

    function funcao_apagar(id, arquivo, crip) {
        console.log(arquivo);
        swal({
            title: "Você tem certeza?",
            text: "Este item será apagado!",
            type: "warning",
            showCancelButton: true,
            confirmButtonText: "Sim, apagar!",
            cancelButtonText: "Não, Cancelar!",
            closeOnConfirm: false,
            closeOnCancel: false
        }, function(isConfirm) {
            if (isConfirm) {
                window.location.replace("../banco_dados/" + arquivo + "_apaga.php?crip=" + crip + "&id=" + id);
                //var ajax = AjaxF();
                //url = "../banco_dados/"+arquivo+"_apaga.php?id="+id;
                //ajax.open("GET",url, false);
                //ajax.setRequestHeader("Content-Type", "text/html");
                //ajax.send();
                //funcao_ok(arquivo);
            } else {
                swal("Cancelado", "Item salvo :)", "error");
            }
        });
    };

    function funcao_apagar_ata_is(id_usuario, tipo) {
        swal({
            title: "Você tem certeza?",
            text: "Este item será apagado!",
            type: "warning",
            showCancelButton: true,
            confirmButtonText: "Sim, apagar!",
            cancelButtonText: "Não, Cancelar!",
            closeOnConfirm: false,
            closeOnCancel: false
        }, function(isConfirm) {
            if (isConfirm) {
                window.location.replace("../banco_dados/apaga_ata_is_candidato.php?id=" + id_usuario + "&tipo=" + tipo);
                //var ajax = AjaxF();
                //url = "../banco_dados/"+arquivo+"_apaga.php?id="+id;
                //ajax.open("GET",url, false);
                //ajax.setRequestHeader("Content-Type", "text/html");
                //ajax.send();
                //funcao_ok(arquivo);
            } else {
                swal("Cancelado", "Item salvo :)", "error");
            }
        });
    };
</script>