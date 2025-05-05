<?php 

$datetime = date('d/m/Y H:i:s');

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

$tipo_relatorio = htmlspecialchars(trim($_POST['tipo_relatorio']));
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
//var_dump($mostrar_especialidade); exit;

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
        <img src='../imagens/brasao.png' width='60px'><br>
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

if($tipo_relatorio == 'concorrendo_esp' || $tipo_relatorio == 'nao_concorrendo_esp')
    include_once 'por_especialidade.php';

if($etapa == 1 && ($tipo_relatorio == 'concorrendo_lista' || $tipo_relatorio == 'nao_concorrendo_lista'))
    include_once 'lista_etapa_1.php';

if($etapa > 1 && ($tipo_relatorio == 'concorrendo_lista' || $tipo_relatorio == 'nao_concorrendo_lista'))
{
    erro_gerar_relatorio_cadastro_candidato("Erro 25325! Este relatório serve apenas para a Etapa I pois nas outras etapas o candidato já tem especialidade!");
    exit();
}

if($tipo_relatorio == 'classificacao')
    include_once 'classificacao.php';

$mpdf=new mPDF(); 
$mpdf->SetDisplayMode('fullpage');
$css = file_get_contents("css/estilo.css");

if($orientacao == 'paisagem') $mpdf->AddPage('L');

$mpdf->WriteHTML($css,1);
$mpdf->WriteHTML($html);

$alteracoes_detalhadas = " Data da geração do relatório $datetime |
    Cabeçalho = $cabecalho | Tipo do Relatório = $tipo_relatorio |
    Etapa = $etapa | Orientação = $orientacao | 
    Título Principal = $titulo_1 | Título Secundário = $titulo_2 |
    Cidade e Data = $cidade_dt | Parágrafo 1 = $paragrafo_1 | 
    Parágrafo 2 = $paragrafo_2 | Parágrafo 3 = $paragrafo_3 | 
    Tipo de especialidade = $tp_especialidade |
    Mostrar especialidades vazias = $mostrar_especialidade";

$insere_log = $conexao->insere_log($_SESSION['id_usuario'], $_SESSION['cpf'], null, "20101", "relatorio", "create", "Gerou um relatório Personalizado de Candidatos em $datetime", $alteracoes_detalhadas);

if($insere_log)
    $mpdf->Output("candidatos.pdf",'D');

exit();