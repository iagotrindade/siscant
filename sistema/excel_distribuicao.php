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
    erro("Erro 235645734! ! Sem permissão para download");
    exit();
}

$ano_distribuicao_medico_obrigatorio = null;
if($_SESSION['selecao_codigo'] == 'mfdv' && isset($_POST['ano_distribuicao_medico_obrigatorio']))
{
    $ano_medico = (int)$_POST['ano_distribuicao_medico_obrigatorio'];
    if($ano_medico == null || $ano_medico == 0)
    {
        erro("Erro 649679789! Ano da distribuição do médico obrigatório invalido!");
        exit();
    }
    else  $ano_distribuicao_medico_obrigatorio = $ano_medico;
}


include_once '../banco_dados/conexao.php';
$conexao = new Conexao();

$selecao = $conexao->get_selecao_id();
$nome_selecao = strtoupper($selecao[0]['codigo']) . " - " . $selecao[0]['nome'] . " de " . $selecao[0]['ano'];

$lista_candidatos = null; 
if($ano_distribuicao_medico_obrigatorio == null) $lista_candidatos = $conexao->get_candidatos_desc_class_distribuicao();
if($ano_distribuicao_medico_obrigatorio != null) $lista_candidatos = $conexao->get_candidatos_desc_class_distribuicao_med_obr($ano_distribuicao_medico_obrigatorio);

$datetime = date('d/m/Y H:i:s');
 
$html[0] = "";

$html[0] .= "<table>";
    $html[0] .= "<tr>";
        $html[0] .= "<td colspan='8'><b> Relatório de distribuição da Seleção  $nome_selecao</b></td>";    
    $html[0] .= "</tr>";
$html[0] .= "</table>";

$html[0] .= "<table>";
    $html[0] .= "<tr>";
        $html[0] .= "<td colspan='8'><i>Relatório gerado em $datetime</i></td>";
    $html[0] .= "</tr>";
$html[0] .= "</table>";

$html[0] .= "<table>";
    $html[0] .= "<tr>";
        $html[0] .= "<td ></td>";    
    $html[0] .= "</tr>";
$html[0] .= "</table>";


$html[0] .= "<table>";
    $html[0] .= "<tr>";
        $html[0] .= "<td><b>Nº</b></td>";    
        $html[0] .= "<td><b>Posto/Grad</b></td>";    
        $html[0] .= "<td><b>Especialidade</b></td>";    
        $html[0] .= "<td><b>Nome</b></td>";
        $html[0] .= "<td><b>Identidade e CPF</b></td>";
        $html[0] .= "<td><b>Data de Incorporação</b></td>";
        $html[0] .= "<td><b>Localidade Convocação e OM 1ª Fase</b></td>";
        $html[0] .= "<td><b>Localidade Convocação e OM 2ª Fase</b></td>";
        
    $html[0] .= "</tr>";
$html[0] .= "</table>";
 
$contador = 1;

foreach($lista_candidatos as $linha)
{
    if (!isset($html[$contador])) 
    {
        $html[$contador] = null;
    }
    
    if($linha['incorporado'] != '1') continue;
    if($linha['forca_distribuicao'] != 'exercito' || $linha['forca_distribuicao'] == null) continue;
    
    $convocado = "";
    if($linha['convocado'] == '1')
        $convocado = "Sim";
    if($linha['convocado'] == '0')
        $convocado = "Não";
    
    $nascimento = null;
    if($linha['data_nascimento'] != null)
        $nascimento = trata_data ($linha['data_nascimento']);
    
    
    
    $especialidade = null;
    $prioridade_cidade = null;
    $nome_cidade_inst_ensino = null;
    
    $judicial = null;
    if($linha['refratario_impedido'] == 'impedido') $judicial = " CUMPRIMENTO DECISÃO JUDICIAL";
    
    
    /*
     * TODAS AS ESPECIALIDADES
    $especialidades_candidato = $conexao->get_especialidade_candidato($linha['id']);
    $contador_especialidade = 1;
    foreach($especialidades_candidato as $especialidade)
    {
        if($especialidade['concorrendo'] == '1') $concorrendo_especialidade = "Concorrendo";
        {
            $especialidades = $especialidades . $contador_especialidade . "ª_ESP_" .  strtoupper($especialidade['ott_stt']) . " - " . $especialidade['especialidade'] . " | ";
            $contador_especialidade++;
        }
    }
    */
    
    if($linha['nome_especialidade_distribuicao'] != null) $especialidade = strtoupper ($linha['ott_stt']) . " - " . $linha['nome_especialidade_distribuicao'];
    
    if($linha['nome_cidade_inst_ensino'] != null) $nome_cidade_inst_ensino = $linha['nome_cidade_inst_ensino'] ;
    
    
    $situacao = null;
    if($ano_distribuicao_medico_obrigatorio != null)$situacao = 'Serviço Militar Obrigatório.';
    if($ano_distribuicao_medico_obrigatorio == null)$situacao = 'Serviço Militar Voluntário.';
    
    
    if($ano_distribuicao_medico_obrigatorio == null && $linha['medico_obrigatorio'] == '1') continue;
    if($ano_distribuicao_medico_obrigatorio != null && $linha['medico_obrigatorio'] == null) continue;
    
    if($ano_distribuicao_medico_obrigatorio != null && $linha['ano_selecao_medico_obrigatorio'] != $ano_distribuicao_medico_obrigatorio) continue;
        
    $data_incorporacao = null;
    if($linha['data_incorporacao'] != null) $data_incorporacao = trata_data($linha['data_incorporacao']);
    
    //if($linha['om_distribuicao'] == null) continue;
    //if($linha['om_1_fase'] == null) continue;
    //if($linha['concorrendo'] == '0') continue;
    
    $html[$contador] .= "<table>";
        $html[$contador] .= "<tr>";
        $html[$contador] .= "<td>".$contador."</td>";
        $html[$contador] .= "<td>".$linha['posto_grad']."</td>";
        $html[$contador] .= "<td>".$especialidade."</td>";
        $html[$contador] .= "<td>".mb_strtoupper($linha['nome_completo'], 'UTF-8')."</td>";
        $html[$contador] .= "<td>_".$linha['identidade']." e ".$linha['cpf']."</td>";
        $html[$contador] .= "<td>_".$data_incorporacao."</td>";
        $html[$contador] .= "<td>".$linha['nome_cidade_1_fase']." - ".$linha['abreviatura_om_1_fase']."</td>";
        $html[$contador] .= "<td>".$linha['cidade_distribuicao']." - ".$linha['abreviatura_om']."</td>";
        $html[$contador] .= "</tr>";
    $html[$contador] .= "</table>";
    
    $html[$contador] .= "<table>";
    $html[$contador] .= "<tr>";
        $html[$contador] .= "<td colspan='8'> Data de Nascimento: $nascimento </td>";
    $html[$contador] .= "</tr>";
    
    
    if($_SESSION['selecao_codigo'] == 'mfdv')
    {
        $html[$contador] .= "<tr>";
        $html[$contador] .= "<td colspan='8'>Local de conclusão de curso superior: ".$linha['instituto_ensino']." - ".$nome_cidade_inst_ensino."/".$linha['uf_instituto_ensino']." </td>";
    }
    
    $anos_sv = $linha['tempo_sv_mil_anos'];
    $meses_sv = $linha['tempo_sv_mil_meses'];
    $dias_sv = $linha['tempo_sv_mil_dias'];
    
    if($anos_sv == null) $anos_sv = "0";
    if($meses_sv == null) $meses_sv = "0";
    if($dias_sv == null) $dias_sv = "0";
    
    $endereco = $linha['rua_num_complemento']." - ".$linha['bairro']." - ".$linha['cidade_endereco']."/".$linha['uf']." CEP: ".$linha['cep'];
    $endereco = mb_strtoupper($endereco, 'UTF-8');
    
    $ja_foi_militar = null;
    if($linha['ativa_reserva'] == 'ja_foi_militar')
    {
        $ja_foi_militar = "CONFORME CERTIFICADO DE RESERVISTA, FOI ". " ". $linha['posto_grad'] . " " . $linha['forca'] .  " " . $linha['arma_quadro_servico'] . " Incorporado em ".$linha['ano_incorporacao']." Licenciamento em ".$linha['licenciamento'];
        $ja_foi_militar = mb_strtoupper($ja_foi_militar, 'UTF-8');
    }
    
    $militar_ativa = null;
    if($linha['ativa_reserva'] == 'militar_ativa')
    {
        $militar_ativa = "MILITAR DA ATIVA "." ". $linha['forca'] . ". " . $linha['posto_grad'] .  " " . $linha['arma_quadro_servico'] . " Incorporado em ".$linha['ano_incorporacao'] . " (ATUALIZAR O TEMPO DE SERVIÇO)";
        $militar_ativa = mb_strtoupper($militar_ativa, 'UTF-8');
    }
    
    
    $html[$contador] .= "</tr>";
    $html[$contador] .= "<tr>";
    $html[$contador] .= "<td colspan='8'> Tempo de Serviço Militar: ".$anos_sv."A ".$meses_sv."M ".$dias_sv."D - ".$ja_foi_militar.$militar_ativa ."</td>";
    $html[$contador] .= "</tr>";
    $html[$contador] .= "<tr>";
    $html[$contador] .= "<td colspan='8'> Endereço: $endereco</td>";
    $html[$contador] .= "</tr>";
    $html[$contador] .= "<tr>";
    $html[$contador] .= "<td colspan='8'>Observação: ".$linha['numero_distribuicao']."ª Designação - " .$situacao." $judicial </td>";
    $html[$contador] .= "</tr>";
$html[$contador] .= "</table>";

$html[$contador] .= "<table>";
    $html[$contador] .= "<tr>";
        $html[$contador] .= "<td ></td>";    
    $html[$contador] .= "</tr>";
$html[$contador] .= "</table>";
    
    $contador++;
    
}

$data_ = date('d_m_Y');

$arquivo = "distribuicao_candidatos_$data_.xls";

header ("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
header ("Cache-Control: no-cache, must-revalidate");
header ("Pragma: no-cache");
header ("Content-type: application/x-msexcel");
header ("Content-Disposition: attachment; filename={$arquivo}" );
header ("Content-Description: PHP Generated Data" );
 

for($i=0; $i < $contador; $i++)
{  
    echo utf8_decode($html[$i]);
}

$insere_log = $conexao->insere_log($_SESSION['id_usuario'], $_SESSION['cpf'], $_SESSION['id_usuario'], "20114", "excel_todos_candidatos", "Create", "Usuário ".$_SESSION['cpf']." gerou uma planilha excel de TODOS os candidatos ", "null");

$conexao = null;

?>