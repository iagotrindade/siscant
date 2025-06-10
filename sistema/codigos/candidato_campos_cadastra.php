<script>
    
    function mostra_nome_social()
    {
        if($('#check_nome_social').is(':checked'))
        {
            $('#div_nome_social').show();
        }
        else
        {
            $('#div_nome_social').hide();
            $('#nome_social').val('');
        }
            
    }
    
    function mostra_companheiro()
    {
        if($('#estado_civil').val() == 'casado' || $('#estado_civil').val() == 'uniao_estavel')
        {
            $('#div_companheiro').show();
        }
        else
        {
            $('#div_companheiro').hide();
        }
            
    }
    
    function tempo_servico_publico()
    {
        if($('#tempo_sv_pub').val() == '1')
        {
            $('#div_tempo_sv_pub_anos').show();
            $('#div_tempo_sv_pub_meses').show();
            $('#div_tempo_sv_pub_dias').show();
        }
        else
        {
            $('#div_tempo_sv_pub_anos').hide();
            $('#div_tempo_sv_pub_meses').hide();
            $('#div_tempo_sv_pub_dias').hide();
            $('#tempo_sv_pub_anos').val("");
            $('#tempo_sv_pub_meses').val("");
            $('#tempo_sv_pub_dias').val("");
        }
    }

    function tempo_servico_militar()
    {
        if($('#tempo_sv_mil').val() == '1')
        {
            $('#div_tempo_sv_mil_anos').show();
            $('#div_tempo_sv_mil_meses').show();
            $('#div_tempo_sv_mil_dias').show();
        }
        else
        {
            $('#div_tempo_sv_mil_anos').hide();
            $('#div_tempo_sv_mil_meses').hide();
            $('#div_tempo_sv_mil_dias').hide();
            $('#tempo_sv_mil_anos').val("");
            $('#tempo_sv_mil_meses').val("");
            $('#tempo_sv_mil_dias').val("");
        }
    }
    
    function select_sexo()
    {
        $('#certificado').val("");
        $('#div_certificado').hide();

        $('#div_forca').hide();
        $('#forca').val("");

        $('#div_posto_grad').hide();
        $('#posto_grad').val("");

        $('#div_arma').hide();
        $('#arma').val("");

        $('#div_licenciamento').hide();
        $('#licenciamento').val("");

        $('#div_data_expedicao').hide();
        $('#data_expedicao').val("");

        $('#div_documento').hide();
        $('#documento').val("");

        $('#div_incorporacao').hide();
        $('#incorporacao').val("");

        $('#div_ja_foi_militar').hide();
        $('#ja_foi_militar').val("");
        
        $('#civil_militar').val("");
        
        
    }

    function select_civil_militar()
    {
        if($('#sexo').val() == '')
        {
            alert('Antes de continuar, você deve selecionar o campo sexo onde diz "Dados Pessoais"!')
            return;
        }
        
        ////////////////////////////
        // CAMPO POSTO GRAD
        ///////////////////////////
        $('#posto_grad').empty();

        $('#posto_grad').append($('<option>', 
        {
            value: "",
            text: "Selecione a opção"
        }));

        if($('#sexo').val() == 'masculino')
        {

            $('#posto_grad').append($('<option>', 
            {
                value: "sd",
                text: "Soldado"
            }));
        }

        $('#posto_grad').append($('<option>', 
        {
            value: "cd",
            text: "Cabo"
        }));

        $('#posto_grad').append($('<option>', 
        {
            value: "3_sgt",
            text: "3º Sargento"
        }));

        $('#posto_grad').append($('<option>', 
        {
            value: "asp",
            text: "Aspirante"
        }));

        $('#posto_grad').append($('<option>', 
        {
            value: "2_ten",
            text: "2º Tenente"
        }));

        $('#posto_grad').append($('<option>', 
        {
            value: "1_ten",
            text: "1º Tenente"
        }));
        
        if($('#civil_militar').val() == '')
        {
            $('#certificado').val("");
            $('#div_certificado').hide();

            $('#div_forca').hide();
            $('#forca').val("");
            
            $('#div_posto_grad').hide();
            $('#posto_grad').val("");
            
            $('#div_arma').hide();
            $('#arma').val("");
            
            $('#div_licenciamento').hide();
            $('#licenciamento').val("");
            
            $('#div_data_expedicao').hide();
            $('#data_expedicao').val("");
            
            $('#div_documento').hide();
            $('#documento').val("");
            
            $('#div_incorporacao').hide();
            $('#incorporacao').val("");
            
            $('#div_ja_foi_militar').hide();
            $('#ja_foi_militar').val("");
            
        }
        
        if($('#civil_militar').val() == 'militar')
        {
            $('#div_ja_foi_militar').hide();
            
            $('#certificado').val("");
            $('#div_certificado').hide();

            $('#div_data_expedicao').hide();
            $('#data_expedicao').val("");
            
            $('#div_documento').hide();
            $('#documento').val("");
            
            $('#div_posto_grad').hide();
            $('#posto_grad').val("");
            
            $('#forca').val("");
            $('#div_forca').show();
            
            $('#incorporacao').val("");
            $('#div_incorporacao').show();
            
            $('#posto_grad').val("");
            $('#div_posto_grad').show();
            
            $('#arma').val("");
            $('#div_arma').show();
            
            $('#div_licenciamento').hide();
            $('#licenciamento').val("");
            
            $('#data_expedicao').val("");
            $('#div_data_expedicao').show();

            $('#documento').val("");
            $('#div_documento').show();
            
            
        }
        
        if($('#civil_militar').val() == 'civil')
        {
            $('#div_ja_foi_militar').show();
            $('#ja_foi_militar').val("");
            
            $('#arma').val("");
            $('#div_arma').hide();
            
            $('#div_forca').hide();
            $('#forca').val("");
            
            $('#div_incorporacao').hide();
            $('#incorporacao').val("");
            
            $('#div_posto_grad').hide();
            $('#posto_grad').val("");
            
            $('#div_licenciamento').hide();
            $('#licenciamento').val("");
            
            $('#data_expedicao').val("");
            $('#div_data_expedicao').hide();

            $('#documento').val("");
            $('#div_documento').hide();
        }
    }
    
    function select_ja_foi_militar()
    {
        if($('#ja_foi_militar').val() == 'sim' || $('#ja_foi_militar').val() == 'sim_eas')
        {
            $('#forca').val("");
            $('#div_forca').show();
            
            $('#incorporacao').val("");
            $('#div_incorporacao').show();
            
            $('#posto_grad').val("");
            $('#div_posto_grad').show();
            
            $('#arma').val("");
            $('#div_arma').show();
            
            $('#div_licenciamento').show();
            $('#licenciamento').val("");
            
            $('#data_expedicao').val("");
            $('#div_data_expedicao').show();

            $('#documento').val("");
            $('#div_documento').show();
            
            if($('#sexo').val() == 'masculino')
            {
                $('#certificado').val("");
                $('#div_certificado').show();
            }
            
            $('#certificado').empty();

            $('#certificado').append($('<option>', 
            {
                value: "",
                text: "Selecione a opção"
            }));
            
            $('#certificado').append($('<option>', 
            {
                value: "csm",
                text: "Certidão de Situação Militar (CSM)"
            }));
            $('#certificado').append($('<option>', 
            {
                value: "1crm",
                text: "Certificado de Reservista Militar 1ª Categoria (CRM)"
            }));
            $('#certificado').append($('<option>', 
            {
                value: "2crm",
                text: "Certificado de Reservista Militar 2ª Categoria (CRM)"
            }));
        }
        
        if($('#ja_foi_militar').val() == 'nao')
        {
            $('#div_forca').hide();
            $('#forca').val("");
            
            $('#div_incorporacao').hide();
            $('#incorporacao').val("");
            
            $('#div_posto_grad').hide();
            $('#posto_grad').val("");
            
            $('#div_arma').hide();
            $('#arma').val("");
            
            $('#div_licenciamento').hide();
            $('#licenciamento').val("");
            
            $('#data_expedicao').val("");
            $('#div_data_expedicao').hide();

            $('#documento').val("");
            $('#div_documento').hide();
            
            if($('#sexo').val() == 'masculino')
            {
                $('#certificado').empty();

                $('#certificado').append($('<option>', 
                {
                    value: "",
                    text: "Selecione a opção"
                }));

                $('#certificado').append($('<option>', 
                {
                    value: "cdi",
                    text: "Certificado de Dispensa de Incorporação (CDI)"
                }));
                $('#certificado').append($('<option>', 
                {
                    value: "ci",
                    text: "Certificado de Isenção (CI)"
                }));
                
                $('#div_certificado').show();
                
            }
        }
    }

    
    function select_certificado()
    {
        $('#div_data_expedicao').show();
        $('#data_expedicao').val("");
        
        $('#div_documento').show();
        $('#documento').val("");
        
        if($('#certificado').val() == 'csm')
        {
            $('#posto_grad').empty();
            
            $('#posto_grad').append($('<option>', 
            {
                value: "",
                text: "Selecione a opção"
            }));
            
            $('#posto_grad').append($('<option>', 
            {
                value: "asp",
                text: "Aspirante"
            }));
            
            $('#posto_grad').append($('<option>', 
            {
                value: "2_ten",
                text: "2º Tenente"
            }));
            
            $('#posto_grad').append($('<option>', 
            {
                value: "1_ten",
                text: "1º Tenente"
            }));
        }
        
        if($('#certificado').val() == '1crm')
        {
            $('#posto_grad').empty();
            
            $('#posto_grad').append($('<option>', 
            {
                value: "",
                text: "Selecione a opção"
            }));
            
            $('#posto_grad').append($('<option>', 
            {
                value: "sd",
                text: "Soldado"
            }));
            $('#posto_grad').append($('<option>', 
            {
                value: "cb",
                text: "Cabo"
            }));
            $('#posto_grad').append($('<option>', 
            {
                value: "3_sgt",
                text: "3º Sargento"
            }));
        }
        
        if($('#certificado').val() == '2crm')
        {
            $('#posto_grad').empty();
            
            $('#posto_grad').append($('<option>', 
            {
                value: "",
                text: "Selecione a opção"
            }));
            
            $('#posto_grad').append($('<option>', 
            {
                value: "sd",
                text: "Soldado"
            }));
            $('#posto_grad').append($('<option>', 
            {
                value: "cb",
                text: "Cabo"
            }));
            
            $('#posto_grad').val('sd').change();
        }
    }
    
    
</script>