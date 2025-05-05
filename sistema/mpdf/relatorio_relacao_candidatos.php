<?php 
include_once '../../banco_dados/conexao.php';
include_once '../../sistema/funcoes.php';
include("mpdf60/mpdf.php");

$old = ini_set('memory_limit', '512M'); 

set_time_limit(300);

session_start();

if(!isset($_SESSION['perfil']))
{
    erro_relatorio("Erro 823494! A sua sessão expirou! Faça o login no sistema para gerar o relatório");
    exit();
}
if($_SESSION['perfil'] != 'admin')
{
    erro_relatorio("Erro 824! Somente o administrador pode gerar este relatório");
    exit();
}
if($_SESSION['candidato'] == '1')
{
    erro_gerar_relatorio_cadastro_candidato("Erro 8145345346 ao gerar relatório!");
    exit();
}

$cabecalho = htmlspecialchars(trim($_POST['cabecalho']));
$concorrendo = htmlspecialchars(trim($_POST['concorrendo']));
$etapa = htmlspecialchars(trim((int)$_POST['etapa']));
$orientacao = htmlspecialchars(trim($_POST['orientacao']));
$titulo_1 = htmlspecialchars(trim($_POST['titulo_1']));
$titulo_2 = htmlspecialchars(trim($_POST['titulo_2']));
$cidade_dt = htmlspecialchars(trim($_POST['cidade_dt']));
$paragrafo_1 = htmlspecialchars(trim($_POST['paragrafo_1']));
$paragrafo_2 = htmlspecialchars(trim($_POST['paragrafo_2']));
$paragrafo_3 = htmlspecialchars(trim($_POST['paragrafo_3']));
$tp_especialidade = htmlspecialchars(trim($_POST['tipo_especialdiade']));
$mostrar_especialidade = htmlspecialchars(trim($_POST['mostrar_especialidade']));

if($etapa < 0 || $etapa > 7)
{
    erro_gerar_relatorio_cadastro_candidato("Erro 3252353 ETAPA inválida!");
    exit();
}

$conexao = new Conexao();

$html = "";

// <editor-fold defaultstate="collapsed" desc="CABEÇALHO">

if($cabecalho == 'sim')
{
    $html = "
    <p class='center' style='font-size: 10px;'>

    <img src='../imagens/brasao.png' width='70px'><br>
         " .$_SESSION['cabecalho_relatorio']. " 
    
</p>";
}

$html = $html. "
 <table border='0' style='width:100%'>
    <tr>
          <th align='center'><strong>".$titulo_1."</strong></th>
    </tr>
</table> 
<br>

<table border='0' style='width:100%'>
  <tr>
    <th align='center'><strong>".$titulo_2."</strong>
  </tr>
  <tr>
    <th align='right'><strong> <p style='font-size: 12px; font-family: Times New Roman;'> ".$cidade_dt."   </p></strong></th>
  </tr>
</table>

<p style='font-size: 12px; font-family: Times New Roman; text-align: justify;'>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
$paragrafo_1
</p>
<p style='font-size: 12px; font-family: Times New Roman; text-align: justify;'>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
$paragrafo_2
</p>
<p style='font-size: 12px; font-family: Times New Roman; text-align: justify;'>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
$paragrafo_3
</p>
" ;

// </editor-fold>

/////////////////////////////////////////////////////////////////
// CANDIDATOS
////////////////////////////////////////////////////////////////

$lista_especialidades = $conexao->get_especialidade(); 
foreach ($lista_especialidades as &$especialidade) 
{
    if($tp_especialidade == 'sem_medicos' && $especialidade['ott_stt'] == 'medico') continue;
    if($tp_especialidade == 'somente_medicos' && $especialidade['ott_stt'] != 'medico') continue;

    $tipo_especialidade = $especialidade['ott_stt'];
    if($tipo_especialidade == 'medico') $tipo_especialidade = "MÉDICO";
    if($tipo_especialidade == 'dentista') $tipo_especialidade = "DENTISTA";
    if($tipo_especialidade == 'veterinario') $tipo_especialidade = "VETERINÁRIO";
    if($tipo_especialidade == 'farmaceutico') $tipo_especialidade = "FARMACÊUTICO";
    if($tipo_especialidade == 'ott') $tipo_especialidade = "OTT";
    if($tipo_especialidade == 'stt') $tipo_especialidade = "STT";

    $lista_candidatos_especialidade = $conexao->get_candidatos_especialidade_concorrendo_nao_concorrendo($especialidade['id']);  

    $contador = 1;

    /////////////////////////////////////////////////////////////
    // VERIFICA SE POSSUI ALGUM CANDIDATO NAQUELA ESPECIALIDADE
    /////////////////////////////////////////////////////////////

    $possui_candidato = false;
    foreach($lista_candidatos_especialidade as &$candidato)
    {
        if($candidato['etapa'] == $etapa)
        {
            if($concorrendo == 'concorrendo_esp'         && ($candidato['concorrendo'] == '1' && $candidato['concorrendo_especialidade'] == '1'))
                $possui_candidato = true;
            if($concorrendo == 'nao_concorrendo_esp' && ($candidato['concorrendo'] == '0' || $candidato['concorrendo_especialidade'] == '0'))
                $possui_candidato = true;
        }
    }

    if($mostrar_especialidade == 'nao_mostrar_especialidade' && !$possui_candidato) continue;

    $html = $html . "
    <table border='0' style='font-size: 12px; font-family: Times New Roman; width:100%' >

    <tr style='background-color: #D8D8D8'>
        <td colspan=\"5\"> <center><b> $tipo_especialidade - ".mb_strtoupper($especialidade['nome'],"UTF-8")."</b></center></td>
    </tr>";

    if(!$possui_candidato)
    {
        $html = $html . "
        <tr>
            <td colspan='3'>
                Nenhum candidato nesta especialidade.
            </td>
        </tr>";
    }
    else
    {
        $html = $html . "
            <tr>";
        
                if($concorrendo != 'nao_concorrendo_esp')
                    $html = $html ."<td>
                                        <b>Nº</b>
                                    </td>";
                
                $html = $html . "
                <td>
                    <b>CPF</b>
                </td>
                <td>
                    <b>NOME</b>
                </td>";
        
                if($concorrendo == 'nao_concorrendo_esp')
                    $html = $html ."<td>
                                        <b>MOTIVO</b>
                                    </td>";
        
            $html = $html . "</tr>";

        foreach($lista_candidatos_especialidade as &$candidato)
        {
            if($candidato['etapa'] != $etapa) continue;

            if($concorrendo == 'concorrendo'         && ($candidato['concorrendo'] == '0' || $candidato['concorrendo_especialidade'] == '0')) continue;
            if($concorrendo == 'nao_concorrendo_esp' && ($candidato['concorrendo'] == '1' && $candidato['concorrendo_especialidade'] == '1')) continue;

            $cpf = substr($candidato['cpf'], 0, -6);
            $cpf = $cpf . "******";

            $justificativa = null;

            if($candidato['concorrendo'] == '0') $justificativa = $candidato['justificativa_concorrendo'];
            if($candidato['concorrendo_especialidade'] == '0') $justificativa = $candidato['justificativa_especialiade'];

            $html = $html . "<tr>";

                    if($concorrendo != 'nao_concorrendo_esp')
                    $html = $html ."<td>
                                        $contador
                                    </td>";
        
                    $html = $html . "
                    <td>
                        ".$cpf."
                    </td>
                    <td>
                        ".mb_strtoupper($candidato['nome_completo'],"UTF-8")."
                    </td>";

                    if($concorrendo == 'nao_concorrendo_esp')
                    $html = $html ."<td>
                                        $justificativa
                                    </td>";
        
                    $html = $html . "</tr>";

                $contador++;
        }
    }

    $html = $html . "</table> <br>";
}



$mpdf=new mPDF(); 
$mpdf->SetDisplayMode('fullpage');
$css = file_get_contents("css/estilo.css");

if($orientacao == 'paisagem') $mpdf->AddPage('L');

$mpdf->WriteHTML($css,1);
$mpdf->WriteHTML($html);

$datetime = date('d/m/Y H:i:s');

$alteracoes_detalhadas = " Data da geração do relatório $datetime |
    Cabeçalho = $cabecalho | Concorrendo = $concorrendo |
    Etapa = $etapa | Orientação = $orientacao | 
    Título Principal = $titulo_1 | Título Secundário = $titulo_2 |
    Cidade e Data = $cidade_dt | Parágrafo 1 = $paragrafo_1 | 
    Parágrafo 2 = $paragrafo_2 | Parágrafo 3 = $paragrafo_3 | 
    Tipo de especialidade = $tp_especialidade |
    Mostrar especialidades vazias = $mostrar_especialidade";

$insere_log = $conexao->insere_log($_SESSION['id_usuario'], $_SESSION['cpf'], null, "20101", "relatorio", "create", "Gerou um relatório de Candidatos em $datetime", $alteracoes_detalhadas);

if($insere_log)
    $mpdf->Output("candidatos.pdf",'D');

exit();