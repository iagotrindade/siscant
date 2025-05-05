<?php

session_start();
include_once 'funcoes.php';

if(!isset($_SESSION['perfil']))
{
    erro("Erro 238979327!");
    exit();
}

if($_SESSION['perfil'] != "admin" &&  $_SESSION['perfil'] != "avaliador" && $_SESSION['perfil'] != "consulta")
{
    erro("Erro 6457567!");
    exit();
}

if(!isset($_GET['id_especialidade']))
{
    erro("Erro 567!");
    exit();
}

$id_especialidade = (int)$_GET['id_especialidade'];

if(!is_int($id_especialidade))
{
    erro("Erro 5234534567!");
    exit();
}

if($id_especialidade == 0)
{
    erro("Erro 4358585468! Especialidade não selecionada!");
    exit();
}

include_once '../banco_dados/conexao.php';
$conexao = new Conexao();

$lista_candidatos = $conexao->get_candidatos_especialidade($id_especialidade); 

$especialidade = $conexao->get_especialidade_id($id_especialidade); 

$nome_espe = $especialidade[0]['nome'];
$ott_stt = $especialidade[0]['ott_stt'];

$ott_stt = strtoupper($ott_stt);

$datetime = $datetime = date('Y-m-d H:i:s');
$datetime = trata_data_hora($datetime);
 
$html[0] = "";

$html[0] .= "<table>";
    $html[0] .= "<tr>";
        $html[0] .= "<td colspan='5'><b> $ott_stt - $nome_espe </b></td>";    
        $html[0] .= "<td><i>Relatório gerado em $datetime</i></td>";
    $html[0] .= "</tr>";
$html[0] .= "</table>";

$html[0] .= "<table>";
    $html[0] .= "<tr>";
        $html[0] .= "<td ></td>";    
    $html[0] .= "</tr>";
$html[0] .= "</table>";


$html[0] .= "<table>";
    $html[0] .= "<tr>";
        $html[0] .= "<td><b>Código</b></td>";    
        $html[0] .= "<td><b>Nome Completo</b></td>";    
        $html[0] .= "<td><b>CPF</b></td>";
        $html[0] .= "<td><b>E-MAIL</b></td>";
        $html[0] .= "<td><b>Pst Validados</b></td>";
        $html[0] .= "<td><b>100% Avaliado</b></td>";
    $html[0] .= "</tr>";
$html[0] .= "</table>";


 
$contador = 1;

foreach($lista_candidatos as $linha)
{
    if (!isset($html[$contador])) 
    {
        $html[$contador] = null;
    }
    
        $verifica_avaliado_completamente = $conexao->get_avaliado_especialidade($linha['id'],$id_especialidade);  
        
        $avaliado_completamente = "Sim";
        foreach($verifica_avaliado_completamente as $curriculo_)
        {
            if($curriculo_['valido'] == null) $avaliado_completamente = "Não";
        }

        $pontuacao_avaliada = null;
        $get_pontuacao_avaliada = $conexao->get_pontuacao_avaliada($linha['id'],$id_especialidade);  

        if(count($get_pontuacao_avaliada)>0)
            $pontuacao_avaliada = round($get_pontuacao_avaliada[0]['pontuacao_avaliada'],3);

        $foto = "user.jpg";

        $get_foto = $conexao->get_foto_usuario($linha['id']);  
        if(count($get_foto) > 0)
            $foto = $get_foto[0]['nome'];

            $html[$contador] .= "<table>";
                $html[$contador] .= "<tr>";
                $html[$contador] .= "<td>".$linha['id']."</td>";
                $html[$contador] .= "<td>".$linha['nome_completo']."</td>";
                $html[$contador] .= "<td>".$linha['cpf']."</td>";
                $html[$contador] .= "<td>".$linha['mail']."</td>";
                $html[$contador] .= "<td>".$pontuacao_avaliada."</td>";
                $html[$contador] .= "<td>".$avaliado_completamente."</td>";
                $html[$contador] .= "</tr>";
            $html[$contador] .= "</table>";
    
    $contador++;
}
 
$nome_espe = str_replace(" ", "_", $nome_espe);
$arquivo = $ott_stt."_".$nome_espe.'.xls';

header ("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
header ("Cache-Control: no-cache, must-revalidate");
header ("Pragma: no-cache");
header ("Content-type: application/x-msexcel");
header ("Content-Disposition: attachment; filename={$arquivo}" );
header ("Content-Description: PHP Generated Data" );
 

for($i=0;$i<$contador;$i++)
{  
    echo utf8_decode($html[$i]);
}

$insere_log = $conexao->insere_log($_SESSION['id_usuario'], $_SESSION['cpf'], $_SESSION['id_usuario'], "20103", "excel_candidatos_especialidade", "Create", "Usuário ".$_SESSION['cpf']." gerou uma planilha excel dos candidatos da especialidade  $ott_stt - $nome_espe ", "null");

$conexao = null;

?>