
<script type="text/javascript">
function candidato_valida_cadastro()
{
    
    // LIMPA VERMELHO DOS CAMPOS
    $('#div_nome_completo').attr('class','form-group');
    $('#div_cpf').attr('class','form-group');
    $('#div_identidade').attr('class','form-group');
    
    $('#div_data_nascimento').attr('class','form-group');
    $('#div_nome_social').attr('class','form-group');
    $('#div_estado_civil').attr('class','form-group');
    $('#div_companheiro').attr('class','form-group');
    //$('#div_dependentes').attr('class','form-group');
    $('#div_sexo').attr('class','form-group');
    $('#div_nascionalidade').attr('class','form-group');
    $('#div_naturalidade').attr('class','form-group');
    $('#div_filiacao_pai').attr('class','form-group');
    $('#div_filiacao_mae').attr('class','form-group');
    $('#div_uf').attr('class','form-group');
    $('#div_rua').attr('class','form-group');
    $('#div_bairro').attr('class','form-group');
    $('#div_cep').attr('class','form-group');
    $('#div_cidade').attr('class','form-group');
    $('#div_telefone').attr('class','form-group');
    $('#div_mail').attr('class','form-group');
    $('#div_mail2').attr('class','form-group');
    $('#div_celular').attr('class','form-group');
    //$('#div_tempo_sv_pub_anos').attr('class','form-group');
    //$('#div_tempo_sv_pub_meses').attr('class','form-group');
    //$('#div_tempo_sv_pub_dias').attr('class','form-group');
    $('#div_tempo_sv_mil_anos').attr('class','form-group');
    $('#div_tempo_sv_mil_meses').attr('class','form-group');
    $('#div_tempo_sv_mil_dias').attr('class','form-group');
    //$('#div_tempo_sv_pub').attr('class','form-group');
    $('#div_tempo_sv_mil').attr('class','form-group');
    $('#div_civil_militar').attr('class','form-group');
    $('#div_certificado').attr('class','col-lg-3');
    $('#div_data_expedicao').attr('class','col-lg-3');
    $('#div_documento').attr('class','col-lg-3');
    $('#div_forca').attr('class','col-lg-3');
    $('#div_posto_grad').attr('class','col-lg-3');
    $('#div_arma').attr('class','col-lg-3');
    $('#div_incorporacao').attr('class','col-lg-3');
    $('#div_licenciamento').attr('class','col-lg-3');
    $('#div_ativa_reserva').attr('class','col-lg-3');
    $('#div_declaracao').attr('class','form-group');
    $('#div_ja_foi_militar').attr('class','col-lg-3');
    
    $("#erro_dados_pessoais").text("");
    $("#div_erro_dados_pessoais").hide();
    
    $("#erro_endereco").text("");
    $("#div_erro_endereco").hide();
    
    $("#erro_contato").text("");
    $("#div_erro_contato").hide();
    
    //$("#erro_tempo_sv_pub").text("");
    //$("#div_erro_tempo_sv_pub").hide();
    
    $("#erro_tempo_sv_mil").text("");
    $("#div_erro_tempo_sv_mil").hide();
    
    $("#erro_civil_militar").text("");
    $("#div_erro_civil_militar").hide();
    
    $("#erro_declaracao").text("");
    $("#div_erro_declaracao").hide();
    
    /////////////////////////////////////
    // VERIFICA DADOS PESSOAIS
    /////////////////////////////////////
    
    if($('#nome_completo').val() == '')
    {
        $('#div_nome_completo').attr('class','has-error form-group');
        $('#nome_completo').focus();
        $("#erro_dados_pessoais").text("O campo NOME COMPLETO é obrigatório!");
        $("#div_erro_dados_pessoais").show();
        return false;
    }
    if($('#cpf').val() == '')
    {
        $('#div_cpf').attr('class','has-error form-group');
        $('#cpf').focus();
        $("#erro_dados_pessoais").text("O campo CPF é obrigatório!");
        $("#div_erro_dados_pessoais").show();
        return false;
    }
    if($('#identidade').val() == '')
    {
        $('#div_identidade').attr('class','has-error form-group');
        $('#identidade').focus();
        $("#erro_dados_pessoais").text("O campo IDENTIDADE é obrigatório!");
        $("#div_erro_dados_pessoais").show();
        return false;
    }
    if($('#data_nascimento').val() == '')
    {
        $('#div_nascimento').attr('class','has-error form-group');
        $('#data_nascimento').focus();
        $("#erro_dados_pessoais").text("O campo DATA DE NASCIMENTO é obrigatório!");
        $("#div_erro_dados_pessoais").show();
        return false;
    }
    if($('#nome_social').val() == '' && $('#check_nome_social').is(':checked'))
    {
        $('#div_nome_social').attr('class','has-error form-group');
        $('#nome_social').focus();
        $("#erro_dados_pessoais").text("Você selecionou que possui um NOME SOCIAL! Logo, deve preencher!");
        $("#div_erro_dados_pessoais").show();
        return false;
    }
    if($('#estado_civil').val() == '')
    {
        $('#div_estado_civil').attr('class','has-error form-group');
        $('#estado_civil').focus();
        $("#erro_dados_pessoais").text("O campo ESTADO CIVIL é obrigatório!");
        $("#div_erro_dados_pessoais").show();
        return false;
    }
    if($('#estado_civil').val() == 'casado' && $('#companheiro').val() == '')
    {
        $('#companheiro').attr('class','has-error form-group');
        $('#companheiro').focus();
        $("#erro_dados_pessoais").text("O campo COMPANHEIRO(A) é obrigatório!");
        $("#div_erro_dados_pessoais").show();
        return false;
    }
    if($('#estado_civil').val() == 'uniao_estavel' && $('#companheiro').val() == '')
    {
        $('#companheiro').attr('class','has-error form-group');
        $('#companheiro').focus();
        $("#erro_dados_pessoais").text("O campo COMPANHEIRO(A) é obrigatório!");
        $("#div_erro_dados_pessoais").show();
        return false;
    }
    /*
    if($('#dependentes').val() == '')
    {
        $('#div_dependentes').attr('class','has-error form-group');
        $('#dependentes').focus();
        $("#erro_dados_pessoais").text("O campo NÚMERO DE DEPENDENTES é obrigatório!");
        $("#div_erro_dados_pessoais").show();
        return false;
    }
    */
    if($('#sexo').val() == '')
    {
        $('#div_sexo').attr('class','has-error form-group');
        $('#sexo').focus();
        $("#erro_dados_pessoais").text("O campo SEXO é obrigatório!");
        $("#div_erro_dados_pessoais").show();
        return false;
    }
    if($('#nascionalidade').val() == '')
    {
        $('#div_nascionalidade').attr('class','has-error form-group');
        $('#nascionalidade').focus();
        $("#erro_dados_pessoais").text("O campo NACIONALIDADE é obrigatório!");
        $("#div_erro_dados_pessoais").show();
        return false;
    }
    if($('#naturalidade').val() == '')
    {
        $('#div_naturalidade').attr('class','has-error form-group');
        $('#naturalidade').focus();
        $("#erro_dados_pessoais").text("O campo NATURALIDADE é obrigatório!");
        $("#div_erro_dados_pessoais").show();
        return false;
    }
    if($('#filiacao_pai').val() == '')
    {
        $('#div_filiacao_pai').attr('class','has-error form-group');
        $('#filiacao_pai').focus();
        $("#erro_dados_pessoais").text("O campo FILIAÇÃO PAI é obrigatório!");
        $("#div_erro_dados_pessoais").show();
        return false;
    }
    if($('#filiacao_mae').val() == '')
    {
        $('#div_filiacao_mae').attr('class','has-error form-group');
        $('#filiacao_mae').focus();
        $("#erro_dados_pessoais").text("O campo FILIAÇÃO MÃE é obrigatório!");
        $("#div_erro_dados_pessoais").show();
        return false;
    }
    
    /////////////////////////////////////
    // VERIFICA ENDEREÇO
    /////////////////////////////////////
    
    if($('#uf').val() == '')
    {
        $('#div_uf').attr('class','has-error form-group');
        $('#uf').focus();
        $("#erro_endereco").text("O campo UF é obrigatório!");
        $("#div_erro_endereco").show();
        return false;
    }
    if($('#cidade').val() == '')
    {
        $('#div_cidade').attr('class','has-error form-group');
        $('#cidade').focus();
        $("#erro_endereco").text("O campo CIDADE é obrigatório!");
        $("#div_erro_endereco").show();
        return false;
    }
    if($('#rua').val() == '')
    {
        $('#div_rua').attr('class','has-error form-group');
        $('#rua').focus();
        $("#erro_endereco").text("O campo RUA, NÚMERO E COMPLEMENTO é obrigatório!");
        $("#div_erro_endereco").show();
        return false;
    }
    if($('#bairro').val() == '')
    {
        $('#div_bairro').attr('class','has-error form-group');
        $('#bairro').focus();
        $("#erro_endereco").text("O campo BAIRRO é obrigatório!");
        $("#div_erro_endereco").show();
        return false;
    }
    if($('#cep').val() == '')
    {
        $('#div_cep').attr('class','has-error form-group');
        $('#cep').focus();
        $("#erro_endereco").text("O campo CEP é obrigatório!");
        $("#div_erro_endereco").show();
        return false;
    }
    
    /////////////////////////////////////
    // VERIFICA CONTATO
    /////////////////////////////////////
    
    if($('#telefone').val() == '')
    {
        $('#div_telefone').attr('class','has-error form-group');
        $('#telefone').focus();
        $("#erro_contato").text("O campo TELEFONE RESIDENCIAL é obrigatório!");
        $("#div_erro_contato").show();
        return false;
    }
    if($('#celular').val() == '')
    {
        $('#div_celular').attr('class','has-error form-group');
        $('#celular').focus();
        $("#erro_contato").text("O campo TELEFONE CELULAR é obrigatório!");
        $("#div_erro_contato").show();
        return false;
    }
    if($('#mail').val() == '')
    {
        $('#div_mail').attr('class','has-error form-group');
        $('#mail').focus();
        $("#erro_contato").text("O campo E-MAIL é obrigatório!");
        $("#div_erro_contato").show();
        return false;
    }
    if($('#mail2').val() == '')
    {
        $('#div_mail2').attr('class','has-error form-group');
        $('#mail2').focus();
        $("#erro_contato").text("Você deve repetir o seu E-MAIL!");
        $("#div_erro_contato").show();
        return false;
    }
    
    if($('#mail').val() != $('#mail2').val())
    {
        $('#div_mail2').attr('class','has-error form-group');
        $('#mail2').focus();
        $("#erro_contato").text("Você deve repetir o mesmo E-Mail!");
        $("#div_erro_contato").show();
        return false;
    }
    
    /////////////////////////////////////
    // VERIFICA SERVIÇO PÚBLICO
    /////////////////////////////////////
    /*
    if($('#tempo_sv_pub').val() == '')
    {
        $('#div_tempo_sv_pub').attr('class','has-error form-group');
        $('#tempo_sv_pub').focus();
        $("#erro_tempo_sv_pub").text("O campo POSSUI TEMPO DE SERVIÇO PÚBLICO é obrigatório!");
        $("#div_erro_tempo_sv_pub").show();
        return false;
    }
    
    if($('#tempo_sv_pub').val() == '1')
    {
        if($('#tempo_sv_pub_anos').val() == '')
        {
            $('#div_tempo_sv_pub_anos').attr('class','has-error form-group');
            $('#tempo_sv_pub_anos').focus();
            $("#erro_tempo_sv_pub").text("O campo QUANTIDADE DE ANOS DE SERVIÇO PÚBLICO é obrigatório!");
            $("#div_erro_tempo_sv_pub").show();
            return false;
        } 
        if($('#tempo_sv_pub_meses').val() == '')
        {
            $('#div_tempo_sv_pub_meses').attr('class','has-error form-group');
            $('#tempo_sv_pub_meses').focus();
            $("#erro_tempo_sv_pub").text("O campo QUANTIDADE DE MESES DE SERVIÇO PÚBLICO é obrigatório!");
            $("#div_erro_tempo_sv_pub").show();
            return false;
        } 
        if($('#tempo_sv_pub_dias').val() == '')
        {
            $('#div_tempo_sv_pub_dias').attr('class','has-error form-group');
            $('#tempo_sv_pub_dias').focus();
            $("#erro_tempo_sv_pub").text("O campo QUANTIDADE DE DIAS DE SERVIÇO PÚBLICO é obrigatório!");
            $("#div_erro_tempo_sv_pub").show();
            return false;
        } 
    }
    */
    /////////////////////////////////////
    // VERIFICA SERVIÇO MILITAR
    /////////////////////////////////////
    
    if($('#tempo_sv_mil').is(":visible") && $('#tempo_sv_mil').val() == '')
    {
        $('#div_tempo_sv_mil').attr('class','has-error form-group');
        $('#tempo_sv_mil').focus();
        $("#erro_tempo_sv_mil").text("O campo POSSUI TEMPO DE SERVIÇO MILITAR ANTERIOR é obrigatório!");
        $("#div_erro_tempo_sv_mil").show();
        return false;
    }
    
    if($('#tempo_sv_mil').is(":visible") && $('#tempo_sv_mil').val() == '1')
    {
        if($('#tempo_sv_mil_anos').val() == '')
        {
            $('#div_tempo_sv_mil_anos').attr('class','has-error form-group');
            $('#tempo_sv_mil_anos').focus();
            $("#erro_tempo_sv_mil").text("O campo QUANTIDADE DE ANOS DE SERVIÇO MILITAR é obrigatório!");
            $("#div_erro_tempo_sv_mil").show();
            return false;
        } 
        if($('#tempo_sv_mil_meses').val() == '')
        {
            $('#div_tempo_sv_mil_meses').attr('class','has-error form-group');
            $('#tempo_sv_mil_meses').focus();
            $("#erro_tempo_sv_mil").text("O campo QUANTIDADE DE MESES DE SERVIÇO MILITAR é obrigatório!");
            $("#div_erro_tempo_sv_mil").show();
            return false;
        } 
        if($('#tempo_sv_mil_dias').val() == '')
        {
            $('#div_tempo_sv_mil_dias').attr('class','has-error form-group');
            $('#tempo_sv_mil_dias').focus();
            $("#erro_tempo_sv_mil").text("O campo QUANTIDADE DE DIAS DE SERVIÇO MILITAR é obrigatório!");
            $("#div_erro_tempo_sv_mil").show();
            return false;
        } 
    }
    
    /////////////////////////////////////
    // VERIFICA CIVIL OU MILITAR
    /////////////////////////////////////

    /*
    if($('#civil_militar').val() == '')
    {
        $('#div_civil_militar').attr('class','has-error form-group');
        $('#civil_militar').focus();
        $("#erro_civil_militar").text("O campo VOCÊ É CIVIL OU MILITAR TEMPORÁRIO é obrigatório!");
        $("#div_erro_civil_militar").show();
        return false;
    }
    
    
    /////////////////////////
    // CIVIL
    /////////////////////////
    
    if($('#civil_militar').val() == 'civil')
    {
        if($('#ja_foi_militar').val() == '')
        {
            $('#div_ja_foi_militar').attr('class',' has-error col-lg-3');
            $('#ja_foi_militar').focus();
            $("#erro_civil_militar").text("O campo JÁ FOI MILITAR é obrigatório!");
            $("#div_erro_civil_militar").show();
            return false;
        }
        
        if($('#ja_foi_militar').val() == 'sim')
        {
            if($('#forca').val() == '')
            {
                $('#div_forca').attr('class','has-error col-lg-3');
                $('#forca').focus();
                $("#erro_civil_militar").text("O campo FORÇA é obrigatório!");
                $("#div_erro_civil_militar").show();
                return false;
            }

            if($('#posto_grad').val() == '')
            {
                $('#div_posto_grad').attr('class','has-error col-lg-3');
                $('#posto_grad').focus();
                $("#erro_civil_militar").text("O campo POSTO/GRADUAÇÃO é obrigatório!");
                $("#div_erro_civil_militar").show();
                return false;
            }

            if($('#arma').val() == '')
            {
                $('#div_arma').attr('class','has-error col-lg-3');
                $('#arma').focus();
                $("#erro_civil_militar").text("O campo ARMA/QUADRO/SERVIO é obrigatório!");
                $("#div_erro_civil_militar").show();
                return false;
            }

            if($('#incorporacao').val() == '')
            {
                $('#div_incorporacao').attr('class','has-error col-lg-3');
                $('#incorporacao').focus();
                $("#erro_civil_militar").text("O campo ANO DE INCORPORAÇÃO é obrigatório!");
                $("#div_erro_civil_militar").show();
                return false;
            }

            if($('#documento').val() == '')
            {
                $('#div_documento').attr('class','has-error col-lg-3');
                $('#documento').focus();
                $("#erro_civil_militar").text("O campo Nº DE DOCUMENTO é obrigatório!");
                $("#div_erro_civil_militar").show();
                return false;
            }

            if($('#data_expedicao').val() == '')
            {
                $('#div_data_expedicao').attr('class','has-error col-lg-3');
                $('#data_expedicao').focus();
                $("#erro_civil_militar").text("O campo DATA DE EXPEDIÇÃO é obrigatório!");
                $("#div_erro_civil_militar").show();
                return false;
            }
            
            if($('#licenciamento').val() == '')
            {
                $('#div_licenciamento').attr('class','has-error col-lg-3');
                $('#licenciamento').focus();
                $("#erro_civil_militar").text("O campo LICENCIAMENTO é obrigatório!");
                $("#div_erro_civil_militar").show();
                return false;
            }
        }
        
        if($('#sexo').val() == 'masculino')
        {
            if($('#certificado').val() == '')
            {
                $('#div_certificado').attr('class','has-error col-lg-3');
                $('#certificado').focus();
                $("#erro_civil_militar").text("O campo DOCUMENTO MILITAR é obrigatório!");
                $("#div_erro_civil_militar").show();
                return false;
            }
            
            if($('#documento').val() == '')
            {
                $('#div_documento').attr('class','has-error col-lg-3');
                $('#documento').focus();
                $("#erro_civil_militar").text("O campo Nº DE DOCUMENTO é obrigatório!");
                $("#div_erro_civil_militar").show();
                return false;
            }
            
            if($('#data_expedicao').val() == '')
            {
                $('#div_data_expedicao').attr('class','has-error col-lg-3');
                $('#data_expedicao').focus();
                $("#erro_civil_militar").text("O campo DATA DA EXPEDIÇÃO é obrigatório!");
                $("#div_erro_civil_militar").show();
                return false;
            }
        }
    }
    
    if($('#civil_militar').val() == 'militar')
    {
        if($('#forca').val() == '')
        {
            $('#div_forca').attr('class','has-error col-lg-3');
            $('#forca').focus();
            $("#erro_civil_militar").text("O campo FORÇA é obrigatório!");
            $("#div_erro_civil_militar").show();
            return false;
        }
        
        if($('#posto_grad').val() == '')
        {
            $('#div_posto_grad').attr('class','has-error col-lg-3');
            $('#posto_grad').focus();
            $("#erro_civil_militar").text("O campo POSTO/GRADUAÇÃO é obrigatório!");
            $("#div_erro_civil_militar").show();
            return false;
        }
        
        if($('#arma').val() == '')
        {
            $('#div_arma').attr('class','has-error col-lg-3');
            $('#arma').focus();
            $("#erro_civil_militar").text("O campo ARMA/QUADRO/SERVIO é obrigatório!");
            $("#div_erro_civil_militar").show();
            return false;
        }
        
        if($('#incorporacao').val() == '')
        {
            $('#div_incorporacao').attr('class','has-error col-lg-3');
            $('#incorporacao').focus();
            $("#erro_civil_militar").text("O campo ANO DE INCORPORAÇÃO é obrigatório!");
            $("#div_erro_civil_militar").show();
            return false;
        }
        
        if($('#documento').val() == '')
        {
            $('#div_documento').attr('class','has-error col-lg-3');
            $('#documento').focus();
            $("#erro_civil_militar").text("O campo Nº DE DOCUMENTO é obrigatório!");
            $("#div_erro_civil_militar").show();
            return false;
        }
        
        if($('#data_expedicao').val() == '')
        {
            $('#div_data_expedicao').attr('class','has-error col-lg-3');
            $('#data_expedicao').focus();
            $("#erro_civil_militar").text("O campo DATA DE EXPEDIÇÃO é obrigatório!");
            $("#div_erro_civil_militar").show();
            return false;
        }
    }
        */
    
    
    if(!$('#declaracao').is(':checked'))
    {
        $('#div_erro_declaracao').attr('class','alert alert-dismissible alert-danger');
        $('#declaracao').focus();
        $("#erro_declaracao").text("O campo de DECLARAÇÃO DE VERACIDADE DAS INFORMAÇÕES é obrigatório!");
        $("#div_erro_declaracao").show();
        return false;
    }
    
    return true;
}

</script>