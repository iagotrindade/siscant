<?php

session_start();
include_once 'funcoes.php';

if(!isset($_SESSION['perfil']))
{
    erro("Erro 24574257457!");
    exit();
}

if($_SESSION['perfil'] != "admin")
{
    erro("Erro 42373478578!");
    exit();
}

$datetime = date('d/m/Y H:i:s');

include_once '../banco_dados/conexao.php';
$conexao = new Conexao();

$lista_candidatos = $conexao->get_todos_candidatos();


$html[0] = "";

$html[0] .= "<table>";
    $html[0] .= "<tr>";
        $html[0] .= "<td colspan='3'><b>RELATÓRIO DESTINADO A 6ª RM</b></td>";    
        $html[0] .= "<td><i>Gerado em $datetime</i></td>";
    $html[0] .= "</tr>";
$html[0] .= "</table>";

$html[0] .= "<table>";
    $html[0] .= "<tr>";
        $html[0] .= "<td ></td>";    
    $html[0] .= "</tr>";
$html[0] .= "</table>";


$html[0] .= "<table>";
    $html[0] .= "<tr>";
        $html[0] .= "<td><b>CPF</b></td>";    
        $html[0] .= "<td><b>Nome Completo</b></td>";    
        $html[0] .= "<td><b>Data de Nascimento</b></td>";
        $html[0] .= "<td><b>Anos Sv Militar</b></td>";
        $html[0] .= "<td><b>Meses Sv Militar</b></td>";
        $html[0] .= "<td><b>Dias Sv Militar</b></td>";
        $html[0] .= "<td><b>Cidade das Etapas Presenciais</b></td>";
        $html[0] .= "<td><b>Prioridades das Cidades</b></td>";
        $html[0] .= "<td><b>Especialidade</b></td>";
        $html[0] .= "<td><b>Currículo</b></td>";
        $html[0] .= "<td><b>Pontos</b></td>";
        $html[0] .= "<td><b>Data de Início</b></td>";
        $html[0] .= "<td><b>Data de Fim</b></td>";
        $html[0] .= "<td><b>É multiplicável?</b></td>";
        $html[0] .= "<td><b>ID Currículo</b></td>";
        $html[0] .= "<td><b>Nome Currículo</b></td>";
        $html[0] .= "<td><b>Válido</b></td>";
        
        
    $html[0] .= "</tr>";
$html[0] .= "</table>";


foreach ($lista_candidatos as $linha) 
{
    $id_usuario = $linha['id'];

    $anos_sv_militar = (int)$linha['tempo_sv_mil_anos'];
    $meses_sv_militar = (int)$linha['tempo_sv_mil_meses'];
    $dias_sv_militar = (int)$linha['tempo_sv_mil_dias'];

    if($dias_sv_militar >=30)
    {
        $meses_sv_militar ++;
        $dias_sv_militar = $dias_sv_militar -30;
    }

    if($meses_sv_militar >= 12)
    {
        $total_anos_sv_publico ++;
        $meses_sv_militar = $meses_sv_militar -12;
    }

    $data_nascimento = trata_data($linha['data_nascimento']);

    $inscricoes = $conexao->get_especialidade_candidato($id_usuario);

    
    
    foreach ($inscricoes as $valor)
    {
        $id_especialidade = $valor['id_especialidade'];
        $lista_curriculo_adicionado = $conexao->get_curriculos_inseridos_candidato($id_usuario,$id_especialidade);  
        $pontuacao_total = 0;
        
        
        // Prioridade de Cidades
        //$id_candidato_x_especialidade = $conexao->get_id_candidato_x_especialidade($id_usuario,$id_especialidade_selecionada);
        $get_cidades_candidato_esp = $conexao->get_prioridade_especialidade_candidato($valor['id_candidato_x_especialidade'])  ;
        $cidades_do_candidato = null;
        foreach ($get_cidades_candidato_esp as $linha35) 
        {
            if($linha35 == end($get_cidades_candidato_esp))
                $cidades_do_candidato = $cidades_do_candidato . $linha35['nome'];
            else
            $cidades_do_candidato = $cidades_do_candidato . $linha35['nome'] . ',';
            //$cidades_do_candidato = $cidades_do_candidato . " |_" . $linha35['prioridade'] . "ª_" . $linha35['nome'];
        }
        
        if($valor['concorrendo'] != null)
        {
            if($valor['concorrendo']  == '0') continue;
        }
        

        foreach ($lista_curriculo_adicionado as $cirriculo) 
        {

            $contador = 1;
            
            if (!isset($html[$contador])) 
            {
                $html[$contador] = null;
            }
            
            $pontuacao = (int)$cirriculo['pontuacao']/1000;

            $dt_inicio = null;
            if($cirriculo['data_inicio'] != null)
                $dt_inicio = trata_data($cirriculo['data_inicio']);
            $dt_fim = null;
            if($cirriculo['data_termino'] != null)
                $dt_fim = trata_data ($cirriculo['data_termino']);

            $multiplicador = "Não";
            if($cirriculo['multiplicacao'] == '1')
                $multiplicador = "Sim";
            
            /*
            $encode_curriculo = null;
            $caminho_curriculo = $_SESSION['pasta_arquivos'].$cirriculo['nome'];
            if(file_exists($caminho_curriculo))
                $encode_curriculo = base64_encode(file_get_contents($caminho_curriculo)); 
            */
            
            $curriculo_valido = null;
            if($cirriculo['valido'] == '1') $curriculo_valido = 'Sim';
            if($cirriculo['valido'] == '0') $curriculo_valido = 'Não';
            
            $cpf = '_'.$linha['cpf'];
            
        $html[$contador] .= "<table>";
            $html[$contador] .= "<tr>";
                $html[$contador] .= '<td>'.$cpf.'</td>';
                $html[$contador] .= '<td>'.$linha['nome_completo'].'</td>';
                $html[$contador] .= '<td>'.$data_nascimento.'</td>';
                $html[$contador] .= '<td>'.$anos_sv_militar.'</td>';
                $html[$contador] .= '<td>'.$meses_sv_militar.'</td>';
                $html[$contador] .= '<td>'.$dias_sv_militar.'</td>';
                $html[$contador] .= '<td>'.$linha['cidade_etapas_presenciais'].'</td>';
                $html[$contador] .= '<td>'.$cidades_do_candidato.'</td>';
                $html[$contador] .= '<td>'.mb_strtoupper($valor['ott_stt'], "UTF-8") . " - " .$valor['especialidade'].'</td>';
                $html[$contador] .= '<td>'.$cirriculo['nome_curriculo'].'</td>';
                $html[$contador] .= '<td>'.$pontuacao.'</td>';
                $html[$contador] .= '<td>'.$dt_inicio.'</td>';
                $html[$contador] .= '<td>'.$dt_fim.'</td>';
                $html[$contador] .= '<td>'.$multiplicador.'</td>';
                $html[$contador] .= '<td>'.$cirriculo['id_especialidade_curriculo'].'</td>';
                $html[$contador] .= '<td>'.$cirriculo['nome'].'</td>';
                $html[$contador] .= '<td>'.$curriculo_valido.'</td>';
            $html[$contador] .= "</tr>";
        $html[$contador] .= "</table>";
        
        $contador++;
        
        }
    }
}
 
$arquivo = "pnts_cand_nao_avaliado".'.xls';

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

$insere_log = $conexao->insere_log($_SESSION['id_usuario'], $_SESSION['cpf'], $_SESSION['id_usuario'], "20105", "excel_pontos_nao_avaliados", "Create", "Usuário ".$_SESSION['cpf']." gerou uma planilha excel dos pontos dos candidatos não avaliado ", "null");

$conexao = null;

?>