<?php

session_start();
include_once 'funcoes.php';

if(!isset($_SESSION['perfil']))
{
    erro("Erro 238979327!");
    exit();
}

if($_SESSION['perfil'] != "admin")
{
    erro("Erro 9774935267!");
    exit();
}


include_once '../banco_dados/conexao.php';
$conexao = new Conexao();

$lista_especialidades = $conexao->get_especialidade(); 

$datetime = $datetime = date('Y-m-d H:i:s');
$datetime = trata_data_hora($datetime);


$html[0] = "";

$html[0] .= "<table>";
    $html[0] .= "<tr>";
        $html[0] .= "<td colspan='5'><b> Relatório de pontuação das especialidades</b></td>";    
        $html[0] .= "<td><i>Relatório gerado em $datetime</i></td>";
    $html[0] .= "</tr>";
$html[0] .= "</table>";

$contador = 1;

foreach($lista_especialidades as $especialidade_id)
{
    $id_especialidade = $especialidade_id['id'];

    $lista_candidatos = $conexao->get_candidatos_especialidade($id_especialidade); 

    $especialidade = $conexao->get_especialidade_id($id_especialidade); 

    $nome_espe = $especialidade[0]['nome'];
    $ott_stt = $especialidade[0]['ott_stt'];

    $ott_stt = mb_strtoupper($ott_stt);

    $html[$contador] = "";
    
    $html[$contador] .= "<table>";
        $html[$contador] .= "<tr>";
            $html[$contador] .= "<td ></td>";    
        $html[$contador] .= "</tr>";
    $html[$contador] .= "</table>";
    

    $html[$contador] .= "<table>";
        $html[$contador] .= "<tr>";
            $html[$contador] .= "<td colspan='5'><b> $ott_stt - $nome_espe </b></td>";    
        $html[$contador] .= "</tr>";
    $html[$contador] .= "</table>";

    


    $html[$contador] .= "<table>";
        $html[$contador] .= "<tr>";
            $html[$contador] .= "<td><b>Código</b></td>";    
            $html[$contador] .= "<td><b>Nome Completo</b></td>";    
            $html[$contador] .= "<td><b>CPF</b></td>";
            $html[$contador] .= "<td><b>E-MAIL</b></td>";
            $html[$contador] .= "<td><b>Pst Validados</b></td>";
            $html[$contador] .= "<td><b>100% Avaliado</b></td>";
        $html[$contador] .= "</tr>";
    $html[$contador] .= "</table>";


     $contador++;
    
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
}
 
$arquivo = 'pontuacao_especialidades.xls';

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

$insere_log = $conexao->insere_log($_SESSION['id_usuario'], $_SESSION['cpf'], $_SESSION['id_usuario'], "20111", "excel_pontuacao_especialidade", "Create", "Usuário ".$_SESSION['cpf']." gerou uma planilha excel da pontuação das especialidades", "null");

$conexao = null;

?>