<?php

  
if(count($usuario_visualiza) != 1)
{
    erro ("Erro 23532!");
    exit();
}

 // <editor-fold defaultstate="collapsed" desc="Variaveis usuário">

$id_usuario = $usuario_visualiza[0]['id'];
$id_selecao = $usuario_visualiza[0]['id_selecao'];
$cpf = $usuario_visualiza[0]['cpf'];
$perfil = $usuario_visualiza[0]['perfil'];
$candidato = $usuario_visualiza[0]['candidato'];
$nome_completo = $usuario_visualiza[0]['nome_completo'];
$trocar_senha = $usuario_visualiza[0]['trocar_senha'];
$concorrendo = $usuario_visualiza[0]['concorrendo'];
$justificativa_concorrendo = $usuario_visualiza[0]['justificativa_concorrendo'];
$id_usuario_alterou_concorrendo = $usuario_visualiza[0]['id_usuario_alterou_concorrendo'];
$isento_pagamento = $usuario_visualiza[0]['isento_pagamento'];
$senha = $usuario_visualiza[0]['senha'];
$desistencia = $usuario_visualiza[0]['desistencia'];
$estado_civil = $usuario_visualiza[0]['estado_civil'];
$companheiro = $usuario_visualiza[0]['companheiro'];
$sexo = $usuario_visualiza[0]['sexo'];
$nome_social = $usuario_visualiza[0]['nome_social'];
$pai = $usuario_visualiza[0]['pai'];
$mae = $usuario_visualiza[0]['mae'];
$identidade = $usuario_visualiza[0]['identidade'];
$nacionalidade = $usuario_visualiza[0]['nacionalidade'];
$naturalidade = $usuario_visualiza[0]['naturalidade'];
$dependente = $usuario_visualiza[0]['dependente'];
$data_nascimento = $usuario_visualiza[0]['data_nascimento'];
$uf = $usuario_visualiza[0]['uf'];
$cep = $usuario_visualiza[0]['cep'];
$cidade = $usuario_visualiza[0]['nome_cidade'];
$id_cidade= $usuario_visualiza[0]['id_cidade'];
$rua_num_complemento = $usuario_visualiza[0]['rua_num_complemento'];
$bairro = $usuario_visualiza[0]['bairro'];
$tel_residencial = $usuario_visualiza[0]['tel_residencial'];
$tel_celular = $usuario_visualiza[0]['tel_celular'];
$mail = $usuario_visualiza[0]['mail'];
$tempo_sv_pub = $usuario_visualiza[0]['tempo_sv_pub'];
$tempo_sv_pub_anos = $usuario_visualiza[0]['tempo_sv_pub_anos'];
$tempo_sv_pub_meses = $usuario_visualiza[0]['tempo_sv_pub_meses'];
$tempo_sv_pub_dias = $usuario_visualiza[0]['tempo_sv_pub_dias'];
$tempo_sv_mil = $usuario_visualiza[0]['tempo_sv_mil'];
$tempo_sv_mil_anos = $usuario_visualiza[0]['tempo_sv_mil_anos'];
$tempo_sv_mil_meses = $usuario_visualiza[0]['tempo_sv_mil_meses'];
$tempo_sv_mil_dias = $usuario_visualiza[0]['tempo_sv_mil_dias'];
$certificado = $usuario_visualiza[0]['certificado'];
$num_ducumento = $usuario_visualiza[0]['num_ducumento'];
$data_expedicao = $usuario_visualiza[0]['data_expedicao'];
$civil_militar = $usuario_visualiza[0]['civil_militar'];
$ativa_reserva = $usuario_visualiza[0]['ativa_reserva'];
$forca = $usuario_visualiza[0]['forca'];
$ano_incorporacao = $usuario_visualiza[0]['ano_incorporacao'];
$posto_grad = $usuario_visualiza[0]['posto_grad'];
$nome_guerra = $usuario_visualiza[0]['nome_guerra'];
$arma_quadro_servico = $usuario_visualiza[0]['arma_quadro_servico'];
$licenciamento = $usuario_visualiza[0]['licenciamento'];
$om_nome = $usuario_visualiza[0]['om_nome'];
$etapa = $usuario_visualiza[0]['etapa'];

$voluntario_12rm = $usuario_visualiza[0]['voluntario_12rm'];
$prioridade_forca = $usuario_visualiza[0]['prioridade_forca'];
$dependente = $usuario_visualiza[0]['dependente'];

$medico_obrigatorio = $usuario_visualiza[0]['medico_obrigatorio'];
$conselho = $usuario_visualiza[0]['conselho'];
$instituto_ensino = $usuario_visualiza[0]['instituto_ensino'];
$uf_instituto_ensino = $usuario_visualiza[0]['uf_instituto_ensino'];
$id_cidade_instituto_ensino = $usuario_visualiza[0]['id_cidade_instituto_ensino'];
$cidade_instituto_ensino = $usuario_visualiza[0]['cidade_instituto_ensino'];
$ano_formacao = $usuario_visualiza[0]['ano_formacao'];

$voluntario_sv_militar = $usuario_visualiza[0]['voluntario_sv_militar'];
$arrimo = $usuario_visualiza[0]['arrimo'];
$obrigatorio = $usuario_visualiza[0]['obrigatorio'];
$situacao_militar = $usuario_visualiza[0]['situacao_militar'];
$antecedentes = $usuario_visualiza[0]['antecedentes'];
$forum_civil = $usuario_visualiza[0]['forum_civil'];
$forum_criminal = $usuario_visualiza[0]['forum_criminal'];

$solicitou_adiamento = $usuario_visualiza[0]['solicitou_adiamento'];
$data_inicio_adiamento = $usuario_visualiza[0]['data_inicio_adiamento'];
$data_fim_adiamento = $usuario_visualiza[0]['data_fim_adiamento'];
$especialidade_adiamento = $usuario_visualiza[0]['especialidade_adiamento'];

$apto_saude = $usuario_visualiza[0]['apto_saude'];
$grupo_saude = $usuario_visualiza[0]['grupo_saude'];
$data_exame_saude = $usuario_visualiza[0]['data_exame_saude'];
$cid_saude = $usuario_visualiza[0]['cid_saude'];
$observacao_exame_saude = $usuario_visualiza[0]['observacao_exame_saude'];
$ata_is = $usuario_visualiza[0]['ata_is'];

$apto_saude_recurso = $usuario_visualiza[0]['apto_saude_recurso'];
$grupo_saude_recurso = $usuario_visualiza[0]['grupo_saude_recurso'];
$data_exame_saude_recurso = $usuario_visualiza[0]['data_exame_saude_recurso'];
$cid_saude_recurso = $usuario_visualiza[0]['cid_saude_recurso'];
$observacao_exame_saude_recurso = $usuario_visualiza[0]['observacao_exame_saude_recurso'];
$ata_is_recurso = $usuario_visualiza[0]['ata_is_recurso'];

$resultado_eaf = $usuario_visualiza[0]['resultado_eaf'];

$transferencia_fisemi = $usuario_visualiza[0]['transferencia_fisemi'];
$fisemi_rm_origem = $usuario_visualiza[0]['fisemi_rm_origem'];
$fisemi_rm_destino = $usuario_visualiza[0]['fisemi_rm_destino'];

$refratario_impedido = $usuario_visualiza[0]['refratario_impedido'];
$historico_judicial = $usuario_visualiza[0]['historico_judicial'];
$numero_acao = $usuario_visualiza[0]['numero_acao'];
$data_liminar = $usuario_visualiza[0]['data_liminar'];
$transitou_julgado = $usuario_visualiza[0]['transitou_julgado'];
$favoravel_desfavoravel = $usuario_visualiza[0]['favoravel_desfavoravel'];
$convocado = $usuario_visualiza[0]['convocado'];
$publicacao_bar_reg = $usuario_visualiza[0]['publicacao_bar_reg'];

$numero_distribuicao = $usuario_visualiza[0]['numero_distribuicao'];
$forca_distribuicao = $usuario_visualiza[0]['forca_distribuicao'];
$om_distribuicao = $usuario_visualiza[0]['om_distribuicao'];
$uf_distribuicao = $usuario_visualiza[0]['uf_distribuicao'];
$id_cidade_distribuicao = $usuario_visualiza[0]['id_cidade_distribuicao'];
$titular_reserva_distribuicao = $usuario_visualiza[0]['titular_reserva_distribuicao'];
$observacao_distribuicao = $usuario_visualiza[0]['observacao_distribuicao'];
$incorporado = $usuario_visualiza[0]['incorporado'];
$data_incorporacao = $usuario_visualiza[0]['data_incorporacao'];
$especialidade_incorporacao = $usuario_visualiza[0]['especialidade_incorporacao'];

$om_1_fase = $usuario_visualiza[0]['om_1_fase'];
$cep_om_1_fase = $usuario_visualiza[0]['cep_om_1_fase'];
$endereco_om_1_fase = $usuario_visualiza[0]['endereco_om_1_fase'];
$guarnicao_om_1_fase = $usuario_visualiza[0]['guarnicao_om_1_fase'];
$telefone_om_1_fase = $usuario_visualiza[0]['telefone_om_1_fase'];
$uf_om_1_fase = $usuario_visualiza[0]['uf_om_1_fase'];

$apresentacao_om = $usuario_visualiza[0]['apresentacao_candidato_om'];
$observacao_om = $usuario_visualiza[0]['observacao_om'];
$autodeclaracao = $usuario_visualiza[0]['autodeclaracao'];
$vaga_reservada = $usuario_visualiza[0]['vaga_reservada'];

//EIPOT
$curso_graduacao = $usuario_visualiza[0]['curso_graduacao'];
$ano_formacao_ofor = $usuario_visualiza[0]['ano_formacao_ofor'];
$nota_ofor = $usuario_visualiza[0]['nota_ofor'];
$arma_eipot = $usuario_visualiza[0]['arma_eipot'];
$rm_inscricao = $usuario_visualiza[0]['rm_inscricao'];
$rm_destino= $usuario_visualiza[0]['rm_destino'];

$qtd_flexao_braco = $usuario_visualiza[0]['qtd_flexao_braco'];
$qtd_abdominal = $usuario_visualiza[0]['qtd_abdominal'];
$qtd_barra = $usuario_visualiza[0]['qtd_barra'];
$dist_corrida = $usuario_visualiza[0]['dist_corrida'];
$ano_formacao_ofor_avaliador = $usuario_visualiza[0]['ano_formacao_ofor_avaliador'];
$nota_ofor_avaliador = $usuario_visualiza[0]['nota_ofor_avaliador'];
$eipot_usuario_avaliou = $usuario_visualiza[0]['eipot_usuario_avaliou'];
$eipot_data_avaliacao = $usuario_visualiza[0]['eipot_data_avaliacao'];
$cidade_exame_musica_12rm = $usuario_visualiza[0]['cidade_exame_musica_12rm'];

$aditamento_convocacao = $usuario_visualiza[0]['aditamento_convocacao'];

$uf_1_fase = $usuario_visualiza[0]['uf_1_fase'];
$id_cidade_1_fase = $usuario_visualiza[0]['id_cidade_1_fase'];
$ano_selecao_medico_obrigatorio = $usuario_visualiza[0]['ano_selecao_medico_obrigatorio'];

$cidade_etapas_presenciais = $usuario_visualiza[0]['cidade_etapas_presenciais'];

$assinatura_sistema = $usuario_visualiza[0]['assinatura_sistema'];
$apagado = $usuario_visualiza[0]['apagado'];
$nome_selecao =  $usuario_visualiza[0]['nome_selecao'] . " / " . $usuario_visualiza[0]['ano_selecao'];



if($data_expedicao != null)
    $data_expedicao = trata_data($data_expedicao);
if($data_nascimento != null)
    $data_nascimento = trata_data($data_nascimento);
?>