
<script type="text/javascript">
function suporte_inicial_valida()
{
    // LIMPA VERMELHO DOS CAMPOS
    $('#div_nome_completo').attr('class','form-group');
    $('#div_cpf').attr('class','form-group');
    $('#div_telefone').attr('class','form-group');
    $('#div_mail').attr('class','form-group');
    $('#div_mail2').attr('class','form-group');
    $('#div_motivo').attr('class','form-group');
    $('#div_mensagem').attr('class','form-group');
    
    $("#mensagem_erro").text("");
    $("#div_mensagem_erro").hide();
    
    
    if($('#motivo').val() == '')
    {
        $('#div_motivo').attr('class','form-group has-error');
        $('#motivo').focus();
        $("#mensagem_erro").text("O campo MOTIVO é obrigatório!");
        $("#div_mensagem_erro").show();
        return false;
    }
    if($('#nome_completo').val() == '')
    {
        $('#div_nome_completo').attr('class','form-group has-error');
        $('#nome_completo').focus();
        $("#mensagem_erro").text("O campo NOME COMPLETO é obrigatório!");
        $("#div_mensagem_erro").show();
        return false;
    }
    if($('#cpf').val() == '')
    {
        $('#div_cpf').attr('class','form-group has-error');
        $('#cpf').focus();
        $("#mensagem_erro").text("O campo CPF é obrigatório!");
        $("#div_mensagem_erro").show();
        return false;
    }
    
    if($('#telefone').val() == '')
    {
        $('#div_telefone').attr('class','form-group has-error');
        $('#telefone').focus();
        $("#mensagem_erro").text("O campo TELEFONE é obrigatório!");
        $("#div_mensagem_erro").show();
        return false;
    }
    if($('#mail').val() == '')
    {
        $('#div_mail').attr('class','form-group has-error');
        $('#mail').focus();
        $("#mensagem_erro").text("O campo E-MAIL é obrigatório!");
        $("#div_mensagem_erro").show();
        return false;
    }
    if($('#mail2').val() == '')
    {
        $('#div_mail2').attr('class','form-group has-error');
        $('#mail2').focus();
        $("#mensagem_erro").text("Repita o sei E-MAIL!");
        $("#div_mensagem_erro").show();
        return false;
    }
    if($('#mensagem').val() == '')
    {
        $('#div_mensagem').attr('class','form-group has-error');
        $('#mensagem').focus();
        $("#mensagem_erro").text("O campo MENSAGEM é obrigatório!");
        $("#div_mensagem_erro").show();
        return false;
    }
  
    
    return true;
}

</script>