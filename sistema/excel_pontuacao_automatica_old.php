<?php

ini_set('memory_limit', '2048M');
session_start();
include_once 'funcoes.php';

if(!isset($_SESSION['perfil']))
{
    erro("Erro 32634634643!");
    exit();
}

if($_SESSION['perfil'] != "admin")
{
    erro("Erro 235645734! ! Sem permissão para download");
    exit();
}

include_once '../banco_dados/conexao.php';
$conexao = new Conexao();

$selecao = $conexao->get_selecao_id();
$nome_selecao = mb_strtoupper($selecao[0]['codigo']) . " - " . $selecao[0]['nome'] . " de " . $selecao[0]['ano'];

$lista_candidatos = $conexao->get_candidatos_desc_class();

$datetime = date('d/m/Y H:i:s');
 
$html[0] = "";

$html[0] .= "<table>";
    $html[0] .= "<tr>";
        $html[0] .= "<td><b> Relatório de pontuação automática da Seleção  $nome_selecao</b></td>";    
    $html[0] .= "</tr>";
$html[0] .= "</table>";

$html[0] .= "<table>";
    $html[0] .= "<tr>";
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
        $html[0] .= "<td><b>ESPECIALIDADE</b></td>";    
        $html[0] .= "<td><b>CPF</b></td>";
        $html[0] .= "<td><b>NOME</b></td>";
        $html[0] .= "<td><b>PONTUAÇÃO</b></td>";
    $html[0] .= "</tr>";
$html[0] .= "</table>";
 
$lista_candidatos = $conexao->get_todos_candidatos();

$contador = 1;
foreach ($lista_candidatos as $linha) 
{
    
    $id_usuario = $linha['id'];

    $inscricoes = $conexao->get_especialidade_candidato($id_usuario);
    
    foreach ($inscricoes as $valor)
    {
        if (!isset($html[$contador])) 
        {
           $html[$contador] = null;
        }
        
        $pontuacao_final = null;        
        $id_especialidade = $valor['id_especialidade'];
        $lista_curriculo_adicionado = $conexao->get_curriculos_inseridos_candidato($id_usuario,$id_especialidade);  
        $pontuacao_total = 0;

        foreach ($lista_curriculo_adicionado as $cirriculo) 
        {
            $data_inicio_original = null;
            $data_fim_original = null;
            $total_meses = null;
            $total_de_dias = null;
            $total_dias = null;
            $pontuacao = null;


            if($cirriculo['carga_horaria_obrigatoria'] == 1)
            {
                if($cirriculo['data_inicio'] != null)
                    $data_inicio_original = reverte_data($cirriculo['data_inicio']);
                if($cirriculo['data_termino'] != null)
                    $data_fim_original = reverte_data($cirriculo['data_termino']);

                $data_inicio = new DateTime(date($data_inicio_original));
                $data_fim = new DateTime(date($data_fim_original));
                $intervalo = $data_fim->diff($data_inicio);

                $anos = (int)$intervalo->format('%Y');
                $meses = (int)$intervalo->format('%m');
                $dias = (int)$intervalo->format('%d');

                $total_de_dias = (int)$intervalo->format('%a');

                $total_dias = null;
                if($data_inicio != null && $data_fim != null)
                {
                    $dias_anos = $anos*365;
                    $dias_meses = $meses*30;
                    $total_dias = $dias_anos + $dias_meses + $dias +1;
                }
                $total_meses = $total_dias/30;
                $total_meses = round($total_meses,2);

                $pontuacao = ($cirriculo['pontuacao'] * $total_de_dias)/1000;
            }
            else
            {
                $pontuacao = $cirriculo['pontuacao']/1000;
            }

            $pontuacao_final = $pontuacao_final + $pontuacao;

            

        }
        if($pontuacao_final != null)
            $pontuacao_final = str_replace('.', ',', $pontuacao_final);
        
                                     
        
        $html[$contador] .= "<table>";
            $html[$contador] .= "<tr>";
                $html[$contador] .= '<td>'.mb_strtoupper($valor['ott_stt'], "UTF-8") . " - " .$valor['especialidade'].'</td>';
                $html[$contador] .= '<td><a href="usuario_visualiza.php?id_usuario='.$linha['id'].'">'.$linha['cpf'].'</a></td>';
                $html[$contador] .= '<td>'.$linha['nome_completo'].'</td>';
                $html[$contador] .= '<td>'.$pontuacao_final.'</td>';
                $html[$contador] .= "</tr>";
            $html[$contador] .= "</table>";
            
        $contador++;
    }
                            
                            
}

$data_ = date('d_m_Y');

$arquivo = "pontuacao_automatica_$data_.xls";

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

$insere_log = $conexao->insere_log($_SESSION['id_usuario'], $_SESSION['cpf'], $_SESSION['id_usuario'], "20113", "excel_pontuacao_automatica", "Create", "Usuário ".$_SESSION['cpf']." gerou uma planilha excel de pontuação automática", "null");

$conexao = null;

?>