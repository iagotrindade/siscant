<?php 

include_once '../../banco_dados/conexao.php';
include_once '../../sistema/funcoes.php';
include("mpdf60/mpdf.php");

$old = ini_set('memory_limit', '512M'); 

$mpdf=new mPDF(); 
$mpdf->SetDisplayMode('fullpage');
$css = file_get_contents("css/estilo.css");
$mpdf->WriteHTML($css,1);

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

if($_POST['data_ata_saude'] == '' || $_POST['data_ata_saude'] == null)
{
    erro_gerar_relatorio_cadastro_candidato("Você deve inserir a data da inspeção de saúde!");
    exit();
}

$data_ata = reverte_data($_POST['data_ata_saude']);

$data_hoje = date('d/m/Y');


$assinante_1 = null;
$assinante_2 = null;
$assinante_3 = null;

$cidade = null;
$sessao = null;

$conexao = new Conexao();

$get_exames_saude = $conexao->get_exames_medico();  
foreach($get_exames_saude as $linha)
{
    if($linha['dia_exame'] == $data_ata)
    {
        $sessao = $linha['sessao'];
        $cidade = $linha['cidade'];
        $assinante_1 = $linha['presidente'];
        $assinante_2 = $linha['membro_1'];
        $assinante_3 = $linha['membro_2'];
    }
}

/*
$assinante_1 = $_POST['assinante_1'];
$assinante_2 = $_POST['assinante_2'];
$assinante_3 = $_POST['assinante_3'];

$cidade = $_POST['cidade'];
$sessao = $_POST['sessao'];
*/


$selecao = $resultado = $conexao->get_selecao_id();

$ano_selecao = (int)$selecao[0]['ano'];
$ano_selecao_mais_um = $ano_selecao+1;
$codigo = strtoupper($selecao[0]['codigo']);
if($codigo == "OTT_STT") $codigo = "OTT/STT";

 
$selecao_atual = $conexao->get_selecao_id();

 $html = "

<p class='center' style='font-size: 10px;'>

    <img src='../imagens/brasao.png' width='70px'><br>
         " .$_SESSION['cabecalho_relatorio']. " 
    
</p>
 <table border='0' style='width:100%'>
    <tr>
          <th align='center'><strong>Relatório dos inspecionados</strong></th>
    </tr>
</table> 
 <table border='0' style='width:100%'>
  <tr>
    <th align='center'><strong> <p style='font-size: 12px; font-family: Times New Roman;'>JISE:  Sessão Nº $sessao - CSE-$codigo-EAS de ".$_POST['data_ata_saude']." na cidade de  $cidade. </p></strong></th>
  </tr>
</table> 

" ;

//$mpdf->AddPage('L');
//$mpdf->WriteHTML($html);
//$mpdf->AddPage();

//$html = "";


$lista_candidatos_ = $conexao->get_ata_dia_exame_medico($data_ata); 

    $html = $html . " <br>
        <table border='0' style='font-size: 10px; font-family: Times New Roman; width:100%' >
            <tr>
                <td style='background-color: #D8D8D8'>
                    <b>Nº</b>
                </td>
                <td style='background-color: #D8D8D8'>
                    <b>CPF</b>
                </td>
                <td style='background-color: #D8D8D8'>
                    <b>NOME COMPLETO</b>
                </td>
                <td style='background-color: #D8D8D8'>
                    <b>NASCIMENTO</b>
                </td>
                <td style='background-color: #D8D8D8'>
                    <b>NOME DA MÃE</b>
                </td>
                <td style='background-color: #D8D8D8'>
                    <b>PARECER</b>
                </td>
                <td style='background-color: #D8D8D8'>
                    <b>GRUPO</b>
                </td>
                <td style='background-color: #D8D8D8'>
                    <b>CID</b>
                </td>
            </tr>";
    
$contador = 1;

foreach ($lista_candidatos_ as &$candidato) 
{
    $apto = "";
    if($candidato['apto_saude'] === '1') $apto = "APTO";
    if($candidato['apto_saude'] === '0') $apto = "INAPTO";
    
    $nascimento = "";
    if($candidato['data_nascimento'] != null) $nascimento = trata_data ($candidato['data_nascimento']);
    
    $html = $html . "
        <tr>
            <td>
                ".$contador."
            </td>
            <td>
                ".$candidato['cpf']."
            </td>
            <td>
                ".mb_strtoupper($candidato['nome_completo'],"UTF-8")."
            </td>
            <td>
                ".$nascimento."
            </td>
            <td>
                ".$candidato['mae']."
            </td>
            <td>
                ".$apto."
            </td>
             <td>
                ".strtoupper($candidato['grupo_saude'])."
            </td>
            <td>
                ".$candidato['cid_saude']."
            </td>
        </tr>";
    
    $contador++;
}
$html = $html . "</table>";


$html = $html . " <br><br><br>
 <table border='0' style='font-size: 10px; font-family: Times New Roman; width:100%'>
  <tr>
    <th>_______________________________________________</th>
    <th>_______________________________________________</th>
    <th>_______________________________________________</th>
  </tr>
  <tr>
    <th>$assinante_1</th>
    <th>$assinante_2</th>
    <th>$assinante_3</th>
  </tr>
</table> ";

$mpdf->AddPage('L');
$mpdf->WriteHTML($html);


 //$mpdf->SetDisplayMode('fullwidth');
 
//$mpdf->WriteHTML($html);
$mpdf->Output("relatorio_exames_medicos_dia.pdf",'D');

exit();