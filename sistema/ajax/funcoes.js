
// <editor-fold defaultstate="collapsed" desc="Cadastro de usuario - Altera Senha">

function altera_senha()
{
    var senha1 = $('#senha1').val();
    var senha2 = $('#senha2').val();
    var id_usuario = $('#id_usuario').val();
    var nome_usuario = $('#nome_usuario').val();
    
    var ajax = AjaxF();
    ajax.onreadystatechange = function()
    {
        if(ajax.readyState == 4)
        {
            document.getElementById('mensagem').innerHTML = ajax.responseText;
        }   
    }
    
    url = "ajax/usuario_altera_senha.php?usuario="+id_usuario+"&nome_usuario="+nome_usuario+"&senha1="+senha1+"&senha2="+senha2;
    
    ajax.open("GET",url, false);
    ajax.setRequestHeader("Content-Type", "text/html");
    ajax.send();
}
// </editor-fold>

// <editor-fold defaultstate="collapsed" desc="Cadastro/Edita Usuário - Verifica usuário">

function verifica_usuario()
{
    var user = $('#nome_usuario').val();
    
    if($('#nome_usuario').val() == "")
    {$('#div_nome_usuario').attr('class','form-group');}
    else
    {
        var ajax = AjaxF();
        ajax.onreadystatechange = function()
        {
            if(ajax.readyState == 4)
            {
                document.getElementById('usuario_mensagem').innerHTML = ajax.responseText;
            }   
        }
        url = "ajax/verifica_usuario.php?usuario="+user;
        ajax.open("GET",url, false);
        ajax.setRequestHeader("Content-Type", "text/html");
        ajax.send();
    }
}

// </editor-fold>

// <editor-fold defaultstate="collapsed" desc="Cadastro/Edita Usuário - Verifica Novo Usuário">
function limpa_usuario()
{
    $('#div_nome_usuario').attr('class','form-group');
    $('#usuario_mensagem').text('');
}

function verifica_novo_usuario()
{
    var user = $('#nome_usuario').val();
    
    if($('#nome_usuario').val() == "")
    {$('#div_nome_usuario').attr('class','form-group');}
    else
    {
        var ajax = AjaxF();
        ajax.onreadystatechange = function()
        {
            if(ajax.readyState == 4)
            {
                document.getElementById('usuario_mensagem').innerHTML = ajax.responseText;
            }   
        }
        url = "ajax/verifica_novo_usuario.php?usuario="+user;
        ajax.open("GET",url, false);
        ajax.setRequestHeader("Content-Type", "text/html");
        ajax.send();
    }
}
// </editor-fold>

// <editor-fold defaultstate="collapsed" desc="Cadastro/Edita Usuário - Verifica CPF">

function limpa_cpf()
{
    $('#div_cpf').attr('class','form-group');
    $('#cpf_mensagem').text('');
}

function verifica_cpf()
{
    var cpf = $('#cpf').val();
    
    if($('#cpf').val() == "___.___.___-__")
    {$('#div_cpf').attr('class','form-group');}
    else
    {
        var ajax = AjaxF();
        ajax.onreadystatechange = function()
        {
            if(ajax.readyState == 4)
            {
                document.getElementById('cpf_mensagem').innerHTML = ajax.responseText;
            }   
        }
        url = "ajax/verifica_cpf.php?cpf="+cpf;
        ajax.open("GET",url, false);
        ajax.setRequestHeader("Content-Type", "text/html");
        ajax.send();
    }
}
// </editor-fold>

// <editor-fold defaultstate="collapsed" desc="Busca Cidade">
function busca_cidades()
{
    var valor_variavel = $('#uf').val();
    var ajax = AjaxF();
    ajax.onreadystatechange = function()
    {
        if(ajax.readyState == 4)
        {
            document.getElementById('cidade').innerHTML = ajax.responseText;
        }   
    }
    url = "ajax/busca_cidade_uf.php?variavel="+valor_variavel;
    ajax.open("GET",url, false);
    ajax.setRequestHeader("Content-Type", "text/html");
    ajax.send();
}
// </editor-fold>

// <editor-fold defaultstate="collapsed" desc="Busca Cidades">
function busca_cidades()
{
    var valor_variavel = $('#uf').val();
    var ajax = AjaxF();
    ajax.onreadystatechange = function()
    {
        if(ajax.readyState == 4)
        {
            document.getElementById('cidade').innerHTML = ajax.responseText;
        }   
    }
    url = "ajax/busca_cidade_uf.php?variavel="+valor_variavel;
    ajax.open("GET",url, false);
    ajax.setRequestHeader("Content-Type", "text/html");
    ajax.send();
}
// </editor-fold>

// <editor-fold defaultstate="collapsed" desc="Busca Cidades2">
function busca_cidades2()
{
    var valor_variavel = $('#uf2').val();
    var ajax = AjaxF();
    ajax.onreadystatechange = function()
    {
        if(ajax.readyState == 4)
        {
            document.getElementById('cidade2').innerHTML = ajax.responseText;
        }   
    }
    url = "ajax/busca_cidade_uf_2.php?variavel="+valor_variavel;
    ajax.open("GET",url, false);
    ajax.setRequestHeader("Content-Type", "text/html");
    ajax.send();
}
// </editor-fold>

// <editor-fold defaultstate="collapsed" desc="Busca Cidade IE">
function busca_cidades_ie()
{
    var valor_variavel = $('#uf_ie').val();
    var ajax = AjaxF();
    ajax.onreadystatechange = function()
    {
        if(ajax.readyState == 4)
        {
            document.getElementById('cidade_ie').innerHTML = ajax.responseText;
        }   
    }
    url = "ajax/busca_cidade_uf.php?variavel="+valor_variavel;
    ajax.open("GET",url, false);
    ajax.setRequestHeader("Content-Type", "text/html");
    ajax.send();
}
// </editor-fold>

// <editor-fold defaultstate="collapsed" desc="Busca OTT e STT">
function busca_ott_stt()
{
    
    var valor_variavel = $('#ott_stt').val();
    var ajax = AjaxF();
    ajax.onreadystatechange = function()
    {
        if(ajax.readyState == 4)
        {
            document.getElementById('especialidade').innerHTML = ajax.responseText;
        }   
    }
    url = "ajax/busca_ott_stt.php?variavel="+valor_variavel;
    ajax.open("GET",url, false);
    ajax.setRequestHeader("Content-Type", "text/html");
    ajax.send();
}
// </editor-fold>

// <editor-fold defaultstate="collapsed" desc="Calcula Data">
function calcula_data()
{
    var data_inicio = $('#data_inicio_calcular').val();
    var data_fim = $('#data_fim_calcular').val();
    var ajax = AjaxF();
    ajax.onreadystatechange = function()
    {
        if(ajax.readyState == 4)
        {
            document.getElementById('diferenca_datas').innerHTML = ajax.responseText;
        }   
    }
    url = "ajax/calcula_data.php?data_inicio="+data_inicio+"&data_fim="+data_fim;
    ajax.open("GET",url, false);
    ajax.setRequestHeader("Content-Type", "text/html");
    ajax.send();
}
// </editor-fold>

// <editor-fold defaultstate="collapsed" desc="Calcula Data">
function calcula_data_dias()
{
    var data_inicio = $('#data_inicio_calcular_dias').val();
    var data_fim = $('#data_fim_calcular_dias').val();
    var ajax = AjaxF();
    ajax.onreadystatechange = function()
    {
        if(ajax.readyState == 4)
        {
            document.getElementById('diferenca_datas_dias').innerHTML = ajax.responseText;
        }   
    }
    url = "ajax/calcula_data_dias.php?data_inicio="+data_inicio+"&data_fim="+data_fim;
    ajax.open("GET",url, false);
    ajax.setRequestHeader("Content-Type", "text/html");
    ajax.send();
}
// </editor-fold>