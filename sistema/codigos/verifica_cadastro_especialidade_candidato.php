
<script type="text/javascript">
function verifica_cadastro_especialidade_candidato()
{
    // LIMPA VERMELHO DOS CAMPOS
    $('#div_ott_stt').attr('class','form-group col-lg-6');
    $('#div_especialidade').attr('class','form-group col-lg-6');
    
    $("#mensagem_erro").text("");
    $("#div_mensagem_erro").hide();
    
    if($('#ott_stt').val() == '')
    {
        $('#div_ott_stt').attr('class','form-group has-error col-lg-6');
        $('#ott_stt').focus();
        $("#mensagem_erro").text("O campo Oficial ou Sargento é obrigatório!");
        $("#div_mensagem_erro").show();
        return false;
    }
    if($('#especialidade').val() == '')
    {
        $('#div_especialidade').attr('class','form-group has-error col-lg-6');
        $('#especialidade').focus();
        $("#mensagem_erro").text("O campo ESPECIALIDADE é obrigatório!");
        $("#div_mensagem_erro").show();
        return false;
    }
    
    
    return true;
}

</script>