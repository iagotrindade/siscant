<?php

session_start();
include_once 'funcoes.php';

if(!isset($_SESSION['perfil']))
{
    erro("Erro 2474374357!");
    exit();
}

if($_SESSION['perfil'] != "admin")
{
    erro("Erro 3457457437! ! Sem permissão para download");
    exit();
}

include_once '../banco_dados/conexao.php';
$conexao = new Conexao();

$lista_candidatos = $conexao->get_medicos_obrigatorios();




// Verifica qual o maior número de prioridade de cidades para montar as colunas da planilha
$maior_numero_de_cidades = 0;
foreach($lista_candidatos as $linha)
{
    $especialidades_candidato = $conexao->get_especialidade_candidato($linha['id']);
    $num_cidades = 0;
    foreach($especialidades_candidato as $especialidade)
    {
        if($especialidade['concorrendo'] == '1') $concorrendo_especialidade = "Concorrendo";
        {
            $id_especialidade_candidato = $conexao->get_id_candidato_x_especialidade($linha['id'], $especialidade['id_especialidade']);
            if(count($id_especialidade_candidato) > 0)
            {
                $lista_prioridade_cidades = $conexao->get_prioridade_especialidade_candidato($id_especialidade_candidato[0]['id']);
                
                foreach($lista_prioridade_cidades as $prior_cid)
                {
                    $num_cidades ++;
                    if($num_cidades > $maior_numero_de_cidades)
                        $maior_numero_de_cidades = $num_cidades ;
                }
            }
        }
    }
}





$datetime = date('d/m/Y H:i:s');
 
$html[0] = "";

$html[0] .= "<table>";
    $html[0] .= "<tr>";
        $html[0] .= "<td><b> Relatório de todos os Médicos Obrigatórios </b></td>";    
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
        $html[0] .= "<td><b>NOME COMPLETO</b></td>";    
        $html[0] .= "<td><b>CPF</b></td>";
        $html[0] .= "<td><b>RA</b></td>";
        $html[0] .= "<td><b>IDENTIDADE</b></td>";
        $html[0] .= "<td><b>E-MAIL</b></td>";
        $html[0] .= "<td><b>MÉDICO OBRIGATÓRIO</b></td>";
        $html[0] .= "<td><b>CONSELHO</b></td>";
        $html[0] .= "<td><b>ANO DA SELEÇÃO</b></td>";
        $html[0] .= "<td><b>CLASSIFICADO</b></td>";
        
        $html[0] .= "<td><b>ESPECIALIDADE INCORPORAÇÃO</b></td>";
        $html[0] .= "<td><b>DATA INCORPORAÇÃO</b></td>";
        
        $html[0] .= "<td><b>ESPECIALIDADES</b></td>";
        $html[0] .= "<td><b>PRIORIDADE DE CIDADES</b></td>";
        $html[0] .= "<td><b>ESTADO CIVL</b></td>";
        $html[0] .= "<td><b>SEXO</b></td>";
        $html[0] .= "<td><b>RUA NUM COMP</b></td>";
        $html[0] .= "<td><b>BAIRRO</b></td>";
        $html[0] .= "<td><b>CIDADE ENDEREÇO</b></td>";
        $html[0] .= "<td><b>NATURALIDADE</b></td>";
        $html[0] .= "<td><b>DEPENDENTES</b></td>";
        $html[0] .= "<td><b>DATA DE NASCIMENTO</b></td>";
        $html[0] .= "<td><b>TELEFONE 1</b></td>";
        $html[0] .= "<td><b>TELEFONE 2</b></td>";
        
        $html[0] .= "<td><b>SV PUB ANOS</b></td>";
        $html[0] .= "<td><b>SV PUB MESES</b></td>";
        $html[0] .= "<td><b>SV PUB DIAS</b></td>";
        
        $html[0] .= "<td><b>SV MIL ANOS</b></td>";
        $html[0] .= "<td><b>SV MIL MESES</b></td>";
        $html[0] .= "<td><b>SV MIL DIAS</b></td>";
        
        $html[0] .= "<td><b>CIVIL/MILITAR</b></td>";
        $html[0] .= "<td><b>ATIVA/RESERVISTA</b></td>";
        $html[0] .= "<td><b>FORÇA</b></td>";
        $html[0] .= "<td><b>ANO INCORPORAÇÃO</b></td>";
        $html[0] .= "<td><b>LICENCIAMENTO</b></td>";
        $html[0] .= "<td><b>POSTO/GRAD</b></td>";
        $html[0] .= "<td><b>ARMA/QUAD/SV</b></td>";
        
        $html[0] .= "<td><b>VOLUNTÁRIO 12ª RM</b></td>";
        $html[0] .= "<td><b>PRIORIDADE DE FORÇA</b></td>";
        $html[0] .= "<td><b>ANO DE FORMAÇÃO</b></td>";
        $html[0] .= "<td><b>INSTITUIÇÃO DE ENSINO</b></td>";
        $html[0] .= "<td><b>UF INSTITUIÇÃO DE ENSINO</b></td>";
        $html[0] .= "<td><b>VOLUNTÁRIO SV MILITAR</b></td>";
        $html[0] .= "<td><b>APTO EX SAÚDE</b></td>";
        $html[0] .= "<td><b>GRUPO EX SAÚDE</b></td>";
        $html[0] .= "<td><b>DATA EX SAÚDE</b></td>";
        $html[0] .= "<td><b>CID EX SAÚDE</b></td>";
        $html[0] .= "<td><b>OBS  EX SAÚDE</b></td>";
        $html[0] .= "<td><b>APTO EX SAÚDE RECURSO</b></td>";
        $html[0] .= "<td><b>GRUPO EX SAÚDE RECURSO</b></td>";
        $html[0] .= "<td><b>DATA EX SAÚDE RECURSO</b></td>";
        $html[0] .= "<td><b>CID EX SAÚDE RECURSO</b></td>";
        $html[0] .= "<td><b>OBS  EX SAÚDE RECURSO</b></td>";
        $html[0] .= "<td><b>REFRATÁRIO/IMPEDIDO</b></td>";
        $html[0] .= "<td><b>HISTÓRICO JUDICIAL</b></td>";
        $html[0] .= "<td><b>TRANSITOU EM JULGADO</b></td>";
        $html[0] .= "<td><b>NÚMERO DA AÇÃO</b></td>";
        $html[0] .= "<td><b>DATA DA LIMINAR</b></td>";
        $html[0] .= "<td><b>CONVOCADO</b></td>";
        $html[0] .= "<td><b>FAVORÁVEL/DESFAVORÁVEL</b></td>";
        $html[0] .= "<td><b>PUBLICAÇÃO EM BAR REG, NUM e DATA</b></td>";
        $html[0] .= "<td><b>SOLICITOU ADIAMENTO</b></td>";
        $html[0] .= "<td><b>INÍCIO ADIAMENTO</b></td>";
        $html[0] .= "<td><b>FIM ADIAMENTO</b></td>";
        $html[0] .= "<td><b>ESPECIALIDADE ADIAMENTO</b></td>";
        $html[0] .= "<td><b>ORDEM DE DISTRIBUIÇÃO</b></td>";
        $html[0] .= "<td><b>FORÇA DA DISTRIBUIÇÃO</b></td>";
        
        $html[0] .= "<td><b>TITULAR OU RESERVA DE DISTRIBUIÇÃO</b></td>";
        $html[0] .= "<td><b>OM DE DISTRIBUIÇÃO</b></td>";
        $html[0] .= "<td><b>ABREV OM DE DISTRIBUIÇÃO</b></td>";
        $html[0] .= "<td><b>UF DA DISTRIBUIÇÃO</b></td>";
        $html[0] .= "<td><b>CIDADE DA DISTRIBUIÇÃO</b></td>";
        $html[0] .= "<td><b>OM 1º FASE</b></td>";
        $html[0] .= "<td><b>ABREV OM 1º FASE</b></td>";
        $html[0] .= "<td><b>CIDADE 1º FASE</b></td>";
        $html[0] .= "<td><b>OBSERVAÇÃO DISTRIBUIÇÃO</b></td>";
        
        $html[0] .= "<td><b>Transferência de FISEMI</b></td>";
        $html[0] .= "<td><b>RM de Origem</b></td>";
        $html[0] .= "<td><b>RM de Destino</b></td>";
        
        for($i = 1; $i <= $maior_numero_de_cidades; $i++)
        {
            $html[0] .= "<td><b>Prioridade de Cidade - Coluna $i</b></td>";
        }
        
        
    $html[0] .= "</tr>";
$html[0] .= "</table>";
 
$contador = 1;

foreach($lista_candidatos as $linha)
{
    if (!isset($html[$contador])) 
    {
        $html[$contador] = null;
    }
    
    $concorrendo = "";
    if($linha['concorrendo'] == '1')
        $concorrendo = "Sim";
    if($linha['concorrendo'] == '0')
        $concorrendo = "Não";
    
    $historico_judicial = "";
    if($linha['historico_judicial'] == '1')
        $historico_judicial = "Sim";
    if($linha['historico_judicial'] == '0')
        $historico_judicial = "Não";
    
    $transitou_julgado = "";
    if($linha['transitou_julgado'] == '1')
        $historico_judicial = "Sim";
    if($linha['transitou_julgado'] == '0')
        $historico_judicial = "Não";
    
    $convocado = "";
    if($linha['convocado'] == '1')
        $convocado = "Sim";
    if($linha['convocado'] == '0')
        $convocado = "Não";
    
    $solicitou_adiamento = "";
    if($linha['solicitou_adiamento'] == '1')
        $solicitou_adiamento = "Sim";
    if($linha['solicitou_adiamento'] == '0')
        $solicitou_adiamento = "Não";
    
        
    $nascimento = null;
    if($linha['data_nascimento'] != null)
        $nascimento = trata_data ($linha['data_nascimento']);
    
    $data_liminar = null;
    if($linha['data_liminar'] != null)
        $data_liminar = trata_data ($linha['data_liminar']);
    
    $data_inicio_adiamento = null;
    if($linha['data_inicio_adiamento'] != null)
        $data_inicio_adiamento = trata_data ($linha['data_inicio_adiamento']);
    
    $data_fim_adiamento = null;
    if($linha['data_fim_adiamento'] != null)
        $data_fim_adiamento = trata_data ($linha['data_fim_adiamento']);
    
    $data_exame_saude = null;
    if($linha['data_exame_saude'] != null)
        $data_exame_saude = trata_data ($linha['data_exame_saude']);
    
    $data_exame_saude_recurso = null;
    if($linha['data_exame_saude_recurso'] != null)
        $data_exame_saude_recurso = trata_data ($linha['data_exame_saude_recurso']);
    
    $especialidades = null;
    $prioridade_cidade = null;
    
    $especialidades_candidato = $conexao->get_especialidade_candidato($linha['id']);
    
    $contador_especialidade = 1;
    
    foreach($especialidades_candidato as $especialidade)
    {
        
        if($especialidade['concorrendo'] == '1') $concorrendo_especialidade = "Concorrendo";
        {
            $especialidades = $especialidades . $contador_especialidade . "ª_ESP_" .  mb_strtoupper($especialidade['ott_stt']) . " - " . $especialidade['especialidade'] . " | ";
        
            
            $id_especialidade_candidato = $conexao->get_id_candidato_x_especialidade($linha['id'], $especialidade['id_especialidade']);
             
            if(count($id_especialidade_candidato) > 0)
            {
                
                $lista_prioridade_cidades = $conexao->get_prioridade_especialidade_candidato($id_especialidade_candidato[0]['id']);
                
                foreach($lista_prioridade_cidades as $prior_cid)
                {
                    $prioridade_cidade = $prioridade_cidade . "_".  $contador_especialidade . "ª_ESP_" . "Prioridade " . $prior_cid['prioridade'] . " Cidade: " . $prior_cid['nome'] . " | ";

                }
            }
            
            $contador_especialidade++;
        }
    }
    
    
    $medico_obri = null;
    if($linha['medico_obrigatorio'] == '1') $medico_obri = 'Sim';
    else $medico_obri = 'Não';
    
    $voluntario_12rm = null;
    if($linha['voluntario_12rm'] != null && $linha['voluntario_12rm'] == 0)
        $voluntario_12rm = "Não";
    if($linha['voluntario_12rm'] != null && $linha['voluntario_12rm'] == 1)
        $voluntario_12rm = "Sim";
    
    $voluntario_sv_miltar = null;
    if($linha['voluntario_sv_militar'] != null && $linha['voluntario_sv_militar'] == 0)
        $voluntario_sv_miltar = "Não";
    if($linha['voluntario_sv_militar'] != null && $linha['voluntario_sv_militar'] == 1)
        $voluntario_sv_miltar = "Sim";
    
    $apto_saude = null;
    if($linha['apto_saude'] != null && $linha['apto_saude'] == '0')
        $apto_saude = "Não";
    if($linha['apto_saude'] != null && $linha['apto_saude'] == '1')
        $apto_saude = "Sim";
   
    $apto_saude_recurso = null;
    if($linha['apto_saude_recurso'] != null && $linha['apto_saude_recurso'] == '0')
        $apto_saude_recurso = "Não";
    if($linha['apto_saude_recurso'] != null && $linha['apto_saude_recurso'] == '1')
        $apto_saude_recurso = "Sim";
    
    $tranf_fisemi = null;
    if($linha['transferencia_fisemi'] == '1')
        $tranf_fisemi = "Sim";
    if($linha['transferencia_fisemi'] == '0')
        $tranf_fisemi = "Não";
    
    $data_incorporacao = null;
        if($linha['data_incorporacao'] != null) $data_incorporacao = trata_data ($linha['data_incorporacao']);
        
    
    $html[$contador] .= "<table>";
        $html[$contador] .= "<tr>";
        $html[$contador] .= "<td>".$linha['nome_completo']."</td>";
        $html[$contador] .= "<td>_".$linha['cpf']."</td>";
        $html[$contador] .= "<td>_".$linha['num_ducumento']."</td>";
        $html[$contador] .= "<td>_".$linha['identidade']."</td>";
        $html[$contador] .= "<td>".$linha['mail']."</td>";
        $html[$contador] .= "<td>".$medico_obri."</td>";
        $html[$contador] .= "<td>".$linha['conselho']."</td>";
        $html[$contador] .= "<td>".$linha['ano_selecao_medico_obrigatorio']."</td>";
        $html[$contador] .= "<td>".$concorrendo."</td>";
        
        $html[$contador] .= "<td>".$linha['ott_stt']." ".$linha['nome_especialidade']."</td>";
        $html[$contador] .= "<td>".$data_incorporacao."</td>";
        
        $html[$contador] .= "<td>".$especialidades."</td>";
        $html[$contador] .= "<td>".$prioridade_cidade."</td>";
        $html[$contador] .= "<td>".$linha['estado_civil']."</td>";
        $html[$contador] .= "<td>".$linha['sexo']."</td>";
        $html[$contador] .= "<td>".$linha['rua_num_complemento']."</td>";
        $html[$contador] .= "<td>".$linha['bairro']."</td>";
        $html[$contador] .= "<td>".$linha['cidade_endereco']."</td>";
        $html[$contador] .= "<td>".$linha['naturalidade']."</td>";
        $html[$contador] .= "<td>".$linha['dependente']."</td>";
        $html[$contador] .= "<td>".$nascimento."</td>";
        $html[$contador] .= "<td>_".$linha['tel_residencial']."</td>";
        $html[$contador] .= "<td>_".$linha['tel_celular']."</td>";
        
        $html[$contador] .= "<td>".$linha['tempo_sv_pub_anos']."</td>";
        $html[$contador] .= "<td>".$linha['tempo_sv_pub_meses']."</td>";
        $html[$contador] .= "<td>".$linha['tempo_sv_pub_dias']."</td>";
        
        $html[$contador] .= "<td>".$linha['tempo_sv_mil_anos']."</td>";
        $html[$contador] .= "<td>".$linha['tempo_sv_mil_meses']."</td>";
        $html[$contador] .= "<td>".$linha['tempo_sv_mil_dias']."</td>";
        
        $html[$contador] .= "<td>".$linha['civil_militar']."</td>";
        $html[$contador] .= "<td>".$linha['ativa_reserva']."</td>";
        $html[$contador] .= "<td>".$linha['forca']."</td>";
        $html[$contador] .= "<td>".$linha['ano_incorporacao']."</td>";
        $html[$contador] .= "<td>".$linha['licenciamento']."</td>";
        $html[$contador] .= "<td>".$linha['posto_grad']."</td>";
        $html[$contador] .= "<td>".$linha['arma_quadro_servico']."</td>";
        
        
        $html[$contador] .= "<td>".$voluntario_12rm."</td>";
        $html[$contador] .= "<td>".$linha['prioridade_forca']."</td>";
        $html[$contador] .= "<td>".$linha['ano_formacao']."</td>";
        $html[$contador] .= "<td>".$linha['instituto_ensino']."</td>";
        $html[$contador] .= "<td>".$linha['uf_instituto_ensino']."</td>";
        $html[$contador] .= "<td>".$voluntario_sv_miltar."</td>";
        $html[$contador] .= "<td>".$apto_saude."</td>";
        $html[$contador] .= "<td>".$linha['grupo_saude']."</td>";
        $html[$contador] .= "<td>".$data_exame_saude."</td>";
        $html[$contador] .= "<td>".$linha['cid_saude']."</td>";
        $html[$contador] .= "<td>".$linha['observacao_exame_saude']."</td>";
        $html[$contador] .= "<td>".$apto_saude_recurso."</td>";
        $html[$contador] .= "<td>".$linha['grupo_saude_recurso']."</td>";
        $html[$contador] .= "<td>".$data_exame_saude_recurso."</td>";
        $html[$contador] .= "<td>".$linha['cid_saude_recurso']."</td>";
        $html[$contador] .= "<td>".$linha['observacao_exame_saude_recurso']."</td>";
        $html[$contador] .= "<td>".$linha['refratario_impedido']."</td>";
        $html[$contador] .= "<td>".$historico_judicial."</td>";
        $html[$contador] .= "<td>".$transitou_julgado."</td>";
        $html[$contador] .= "<td>".$linha['numero_acao']."</td>";
        $html[$contador] .= "<td>".$data_liminar."</td>";
        $html[$contador] .= "<td>".$convocado."</td>";
        $html[$contador] .= "<td>".$linha['favoravel_desfavoravel']."</td>";
        $html[$contador] .= "<td>".$linha['publicacao_bar_reg']."</td>";
        $html[$contador] .= "<td>".$solicitou_adiamento."</td>";
        $html[$contador] .= "<td>".$data_inicio_adiamento."</td>";
        $html[$contador] .= "<td>".$data_fim_adiamento."</td>";
        $html[$contador] .= "<td>".$linha['especialidade_adiamento']."</td>";
        
        $html[$contador] .= "<td>".$linha['numero_distribuicao']."</td>";
        
        $html[$contador] .= "<td>".$linha['forca_distribuicao']."</td>";
        
        $html[$contador] .= "<td>".$linha['titular_reserva_distribuicao']."</td>";
        $html[$contador] .= "<td>".$linha['nome_om_distribuicao']."</td>";
        $html[$contador] .= "<td>".$linha['abreviatura']."</td>";
        $html[$contador] .= "<td>".$linha['uf_distribuicao']."</td>";
        $html[$contador] .= "<td>".$linha['cidade_distribuicao']."</td>";
        $html[$contador] .= "<td>".$linha['nome_om_1_fase']."</td>";
        $html[$contador] .= "<td>".$linha['abreviatura_om_1_fase']."</td>";
        $html[$contador] .= "<td>".$linha['nome_cidade_1_fase']."</td>";
        $html[$contador] .= "<td>".$linha['observacao_distribuicao']."</td>";
        
        
        $html[$contador] .= "<td>".$tranf_fisemi."</td>";
        $html[$contador] .= "<td>".$linha['fisemi_rm_origem']."</td>";
        $html[$contador] .= "<td>".$linha['fisemi_rm_destino']."</td>";
        
        
        
        
        foreach($especialidades_candidato as $especialidade)
        {
            
            if($especialidade['concorrendo'] == '1') $concorrendo_especialidade = "Concorrendo";
            {
                $especialidades = $especialidades .  mb_strtoupper($especialidade['ott_stt']) . " - " . $especialidade['especialidade'] . " | ";

                $id_especialidade_candidato = $conexao->get_id_candidato_x_especialidade($linha['id'], $especialidade['id_especialidade']);

                if(count($id_especialidade_candidato) > 0)
                {
                    $lista_prioridade_cidades = $conexao->get_prioridade_especialidade_candidato($id_especialidade_candidato[0]['id']);
                    foreach($lista_prioridade_cidades as $prior_cid)
                    {
                        $prioridade_cidade =  $prior_cid['nome'] . " | " . $especialidade['especialidade'] . " | Prioridade " . $prior_cid['prioridade'];
                        
                        $html[$contador] .= "<td>".$prioridade_cidade."</td>";
                    }
                }
                $contador_especialidade++;
            }
        }
        
        
        
        $html[$contador] .= "</tr>";
    $html[$contador] .= "</table>";
    
    $contador++;
}

$data_ = date('d_m_Y');

$arquivo = "todos_medicos_obrigatorios_$data_.xls";

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

$insere_log = $conexao->insere_log($_SESSION['id_usuario'], $_SESSION['cpf'], $_SESSION['id_usuario'], "20112", "excel_medicos_obrigatorios", "Create", "Usuário ".$_SESSION['cpf']." gerou uma planilha excel de TODOS os médicos obrigatórios ", null);

$conexao = null;

?>