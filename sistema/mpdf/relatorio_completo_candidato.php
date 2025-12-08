<?php
include_once '../../banco_dados/conexao.php';
include_once '../../sistema/funcoes.php';

$id_candidato = $_GET['id_candidato'];
session_start();

if (!isset($_SESSION['id_usuario'])) {
    erro_relatorio("Erro 823494! A sua sessão expirou! Faça o login no sistema para gerar o relatório");
    exit();
}

if ($_SESSION['perfil'] == 'candidato' || $_SESSION['candidato'] == 1) {
    $id_candidato = $_SESSION['id_usuario'];
    if ($_GET['codigo'] != hash('sha256', $_SESSION['chave'])) {
        erro_gerar_relatorio_cadastro_candidato("Erro 8145345346 ao gerar relatório!");
        exit();
    }
}

$conexao = new Conexao();
$candidato_relatorio = $conexao->get_usuario_id($id_candidato);

$get_selecao = $conexao->get_selecao_id();
$liberacao_avaliacao_curricular = 0;
if (count($get_selecao) > 0) $liberacao_avaliacao_curricular = $get_selecao[0]['liberacao_avaliacao_curricular'];

if (count($candidato_relatorio) != 1) {
    erro_gerar_relatorio_cadastro_candidato("Erro 81456 ao gerar relatório! Candidato não encontrado!");
    exit();
}

// Dados do candidato
$id_criptografado = hash('sha256', $candidato_relatorio[0]['id']);
$id_selecao = $candidato_relatorio[0]['id_selecao'];
$cpf = $candidato_relatorio[0]['cpf'];
$perfil = $candidato_relatorio[0]['perfil'];
$candidato = $candidato_relatorio[0]['candidato'];
$nome_completo = mb_strtoupper($candidato_relatorio[0]['nome_completo']);
$trocar_senha = $candidato_relatorio[0]['trocar_senha'];
$concorrendo = $candidato_relatorio[0]['concorrendo'];
$senha = $candidato_relatorio[0]['senha'];
$desistencia = $candidato_relatorio[0]['desistencia'];
$estado_civil = mb_strtoupper($candidato_relatorio[0]['estado_civil']);
$companheiro = mb_strtoupper($candidato_relatorio[0]['companheiro']);
$sexo = mb_strtoupper($candidato_relatorio[0]['sexo']);
$nome_social = mb_strtoupper($candidato_relatorio[0]['nome_social']);
$pai = mb_strtoupper($candidato_relatorio[0]['pai']);
$mae = mb_strtoupper($candidato_relatorio[0]['mae']);
$identidade = mb_strtoupper($candidato_relatorio[0]['identidade']);
$nacionalidade = mb_strtoupper($candidato_relatorio[0]['nacionalidade']);
$naturalidade = mb_strtoupper($candidato_relatorio[0]['naturalidade']);
$dependente = mb_strtoupper($candidato_relatorio[0]['dependente']);
$data_nascimento = $candidato_relatorio[0]['data_nascimento'];
$uf = mb_strtoupper($candidato_relatorio[0]['uf']);
$cep = $candidato_relatorio[0]['cep'];
$cidade = mb_strtoupper($candidato_relatorio[0]['nome_cidade']);
$rua_num_complemento = mb_strtoupper($candidato_relatorio[0]['rua_num_complemento']);
$bairro = mb_strtoupper($candidato_relatorio[0]['bairro']);
$tel_residencial = $candidato_relatorio[0]['tel_residencial'];
$tel_celular = $candidato_relatorio[0]['tel_celular'];
$mail = mb_strtoupper($candidato_relatorio[0]['mail']);
$tempo_sv_pub = $candidato_relatorio[0]['tempo_sv_pub'];
$tempo_sv_pub_anos = $candidato_relatorio[0]['tempo_sv_pub_anos'];
$tempo_sv_pub_meses = $candidato_relatorio[0]['tempo_sv_pub_meses'];
$tempo_sv_pub_dias = $candidato_relatorio[0]['tempo_sv_pub_dias'];
$tempo_sv_mil = $candidato_relatorio[0]['tempo_sv_mil'];
$tempo_sv_mil_anos = $candidato_relatorio[0]['tempo_sv_mil_anos'];
$tempo_sv_mil_meses = $candidato_relatorio[0]['tempo_sv_mil_meses'];
$tempo_sv_mil_dias = $candidato_relatorio[0]['tempo_sv_mil_dias'];
$certificado = mb_strtoupper($candidato_relatorio[0]['certificado']);
$num_ducumento = $candidato_relatorio[0]['num_ducumento'];
$data_expedicao = $candidato_relatorio[0]['data_expedicao'];
$civil_militar = mb_strtoupper($candidato_relatorio[0]['civil_militar']);
$ativa_reserva = mb_strtoupper($candidato_relatorio[0]['ativa_reserva']);
$forca = mb_strtoupper($candidato_relatorio[0]['forca']);
$ano_incorporacao = $candidato_relatorio[0]['ano_incorporacao'];
$posto_grad = mb_strtoupper($candidato_relatorio[0]['posto_grad']);
$arma_quadro_servico = mb_strtoupper($candidato_relatorio[0]['arma_quadro_servico']);
$licenciamento = $candidato_relatorio[0]['licenciamento'];
$autodeclaracao = mb_strtoupper($candidato_relatorio[0]['autodeclaracao']);
$vaga_reservada = mb_strtoupper($candidato_relatorio[0]['vaga_reservada']);

if ($vaga_reservada == 0 || ($vaga_reservada == null))  $vaga_reservada = "NÃO";
if ($vaga_reservada == 1) $vaga_reservada = "SIM";

//EIPOT
$curso_graduacao = mb_strtoupper($candidato_relatorio[0]['curso_graduacao']);
$ano_formacao_ofor = $candidato_relatorio[0]['ano_formacao_ofor'];
$nota_ofor = $candidato_relatorio[0]['nota_ofor'];
$arma_eipot = mb_strtoupper($candidato_relatorio[0]['arma_eipot']);
$cidade_etapas_presenciais = mb_strtoupper($candidato_relatorio[0]['cidade_etapas_presenciais']);

if ($companheiro != null) $companheiro = " COM " . $companheiro;

$inscricao = "Em análise";
if ($candidato_relatorio[0]['etapa'] > 1)
    $inscricao = "INSCRIÇÃO REALIZADA COM SUCESSO";
if ($candidato_relatorio[0]['medico_obrigatorio'] == 1)
    $inscricao = "MÉDICO OBRIGATÓRIO";

$voluntario_12rm = $candidato_relatorio[0]['voluntario_12rm'];
$prioridade_forca = mb_strtoupper($candidato_relatorio[0]['prioridade_forca']);
$dependente = $candidato_relatorio[0]['dependente'];
$medico_obrigatorio = $candidato_relatorio[0]['medico_obrigatorio'];
$instituto_ensino = mb_strtoupper($candidato_relatorio[0]['instituto_ensino']);
$uf_instituto_ensino = mb_strtoupper($candidato_relatorio[0]['uf_instituto_ensino']);
$cidade_instituto_ensino = mb_strtoupper($candidato_relatorio[0]['cidade_instituto_ensino']);
$ano_formacao = $candidato_relatorio[0]['ano_formacao'];
$assinatura_sistema = $candidato_relatorio[0]['assinatura_sistema'];
$apagado = $candidato_relatorio[0]['apagado'];
$nome_selecao =  mb_strtoupper($candidato_relatorio[0]['nome_selecao'] . " / " . $candidato_relatorio[0]['ano_selecao']);
$codigo_selecao =  $candidato_relatorio[0]['codigo_selecao'];

if ($tempo_sv_pub_anos == 0)
    $tempo_sv_pub_anos = "-0-";
if ($tempo_sv_pub_meses == 0)
    $tempo_sv_pub_meses = "-0-";
if ($tempo_sv_pub_dias == 0)
    $tempo_sv_pub_dias = "-0-";

if ($tempo_sv_mil_anos == 0)
    $tempo_sv_mil_anos = "-0-";
if ($tempo_sv_mil_meses == 0)
    $tempo_sv_mil_meses = "-0-";
if ($tempo_sv_mil_dias == 0)
    $tempo_sv_mil_dias = "-0-";

if ($dependente == 0)
    $dependente = "-0-";

if ($voluntario_12rm != null) {
    if ($voluntario_12rm == 1)
        $voluntario_12rm = "SIM";
    else if ($voluntario_12rm == 0)
        $voluntario_12rm = "NÃO";
}

if ($data_expedicao != null)
    $data_expedicao = trata_data($data_expedicao);
if ($data_nascimento != null)
    $data_nascimento = trata_data($data_nascimento);

$datetime = date('d/m/Y H:i:s');

$foto = "red_user.jpeg";
$get_foto = $conexao->get_foto_usuario($id_candidato);
if (count($get_foto) > 0) {
    $foto = $get_foto[0]['nome'];
} else {
    $foto = "../imagens/user.jpg";
}

if ($nome_social == null)
    $nome_social = "";

$arquivo_pagamento = $conexao->get_arquivo_pagamento($id_candidato);
if (count($arquivo_pagamento) > 0)
    $pagamento = " adicionado <span style='color: #006400; font-weight: bold;'>OK</span>";
else
    $pagamento = " <span style='color: red; font-weight: bold;'>NÃO ADICIONADO</span>";

// ========== HTML COM DESIGN APIMORADO ==========

$html = "
<div style='border: 2px solid #006400; border-radius: 8px; padding: 15px; margin-bottom: 20px; background: #f8f9fa;'>
    <table border='0' style='width:100%'>
        <tr>
            <th align='center' style='font-size: 16px; font-weight: bold; color: #006400;'>
                " . mb_strtoupper($nome_selecao, "UTF-8") . "
            </th>
        </tr>
    </table> 
</div>

<div style='border: 1px solid #006400; border-radius: 8px; padding: 15px; margin-bottom: 20px; background: white; box-shadow: 0 2px 4px rgba(0,0,0,0.1);'>
    <table border='0' style='width:100%'>
        <tr>
            <td align='left' style='vertical-align: top;'>
                <div style='font-size: 14px; font-weight: bold; margin-bottom: 10px; color: #006400;'>
                    Relatório do Candidato <span style='text-decoration: underline;'>Nº: " . $id_candidato . "</span>
                </div>
                <div style='font-size: 12px; margin-bottom: 5px;'>
                    <strong>Status da inscrição:</strong> 
                    <span style='padding: 4px 12px; border-radius: 15px; color: #006400;'>$inscricao</span>
                </div>";

if ($_SESSION['selecao_pagamento'] == true) {
    $html .= "
                <div style='font-size: 11px; margin-top: 8px;'>
                    <strong>Pagamento/Isenção:</strong> $pagamento
                </div>";
}

$html .= "
            </td>
            <td align='right' style='vertical-align: top;'>
                <img src='../fotos/$foto' height='70px' style='border: 2px solid #006400; border-radius: 8px;'>
            </td>
        </tr>
    </table> 
</div>

<!-- Card de Identificação -->
<div style='border: 1px solid #ddd; border-radius: 8px; margin-bottom: 20px; background: white; box-shadow: 0 2px 4px rgba(0,0,0,0.1);'>
    <div style='background: #006400; color: white; padding: 12px; border-radius: 8px 8px 0 0;'>
        <center><b>IDENTIFICAÇÃO DO CANDIDATO</b></center>
    </div>
    <div style='padding: 15px;'>
        <table border='0' style='font-size: 12px; width:100%'>
            <tr>
                <td width='50%' style='padding: 6px; border-bottom: 1px solid #f0f0f0;'><b>Nome Completo: </b> $nome_completo</td>
                <td width='50%' style='padding: 6px; border-bottom: 1px solid #f0f0f0;'><b>CPF: </b> <span style='color: #006400;'>$cpf</span></td>
            </tr>
            <tr>
                <td style='padding: 6px; border-bottom: 1px solid #f0f0f0;'><b>Estado Civil: </b> $estado_civil $companheiro</td>
                <td style='padding: 6px; border-bottom: 1px solid #f0f0f0;'><b>Nome Social: </b> $nome_social</td>
            </tr>
            <tr>
                <td style='padding: 6px; border-bottom: 1px solid #f0f0f0;'><b>Identidade: </b> $identidade</td>
                <td style='padding: 6px; border-bottom: 1px solid #f0f0f0;'><b>Data de Nascimento: </b> <span style='color: #006400;'>$data_nascimento</span></td>
            </tr>
            <tr>
                <td style='padding: 6px; border-bottom: 1px solid #f0f0f0;'><b>Nome do pai: </b> $pai</td>
                <td style='padding: 6px; border-bottom: 1px solid #f0f0f0;'><b>Sexo: </b> $sexo</td>    
            </tr>
            <tr>
                <td style='padding: 6px; border-bottom: 1px solid #f0f0f0;'><b>Nome da mãe: </b> $mae</td>
                <td style='padding: 6px; border-bottom: 1px solid #f0f0f0;'><b>E-Mail: </b> <span style='color: #006400;'>$mail</span></td>
            </tr>
            <tr>
                <td style='padding: 6px; border-bottom: 1px solid #f0f0f0;'><b>Nacionalidade: </b> $nacionalidade</td>
                <td style='padding: 6px; border-bottom: 1px solid #f0f0f0;'><b>Naturalidade: </b> $naturalidade</td>
            </tr>
            <tr>
                <td style='padding: 6px; border-bottom: 1px solid #f0f0f0;'><b>UF: </b> $uf</td>
                <td style='padding: 6px; border-bottom: 1px solid #f0f0f0;'><b>CEP: </b> $cep</td>
            </tr>
            <tr>
                <td style='padding: 6px; border-bottom: 1px solid #f0f0f0;'><b>Bairro: </b> $bairro</td>
                <td style='padding: 6px; border-bottom: 1px solid #f0f0f0;'><b>Cidade: </b> $cidade</td>
            </tr>
            <tr>
                <td colspan='2' style='padding: 6px; border-bottom: 1px solid #f0f0f0;'><b>Endereço Completo: </b> $rua_num_complemento</td>
            </tr>
            <tr>
                <td style='padding: 6px;'><b>Telefone de Recados: </b> $tel_residencial</td>
                <td style='padding: 6px;'><b>Telefone de Contato: </b> <span style='color: #006400; font-weight: bold;'>$tel_celular</span></td>
            </tr>
        </table>
    </div>
</div>

<!-- Card de Informações Acadêmicas/Militares -->
<div style='border: 1px solid #ddd; border-radius: 8px; margin-bottom: 20px; background: white; box-shadow: 0 2px 4px rgba(0,0,0,0.1);'>
    <div style='background: #006400; color: white; padding: 12px; border-radius: 8px 8px 0 0;'>
        <center><b>INFORMAÇÕES ACADÊMICAS E MILITARES</b></center>
    </div>
    <div style='padding: 15px;'>
        <table border='0' style='font-size: 12px; width:100%'>";

if ($_SESSION['selecao_codigo'] == 'mfdv') {
    $html .= "
            <tr>
                <td colspan='3' style='padding: 6px; border-bottom: 1px solid #f0f0f0;'><b>Nome do Instituto de Ensino: </b>$instituto_ensino</td>
            </tr>
            <tr>
                <td style='padding: 6px; border-bottom: 1px solid #f0f0f0;'><b>UF Instituto: </b>$uf_instituto_ensino</td>
                <td style='padding: 6px; border-bottom: 1px solid #f0f0f0;'><b>Cidade Instituto: </b>$cidade_instituto_ensino</td>
                <td style='padding: 6px; border-bottom: 1px solid #f0f0f0;'><b>Ano de Formação: </b>$ano_formacao</td>
            </tr>
            <tr>
                <td style='padding: 6px;'><b>Dependentes: </b> $dependente</td>
            </tr>";
}

if (isset($_SESSION['eipot'])) {
    $html .= "
            <tr>
                <td colspan='2' style='padding: 6px; border-bottom: 1px solid #f0f0f0;'><b>Curso de Graduação: </b>$curso_graduacao</td>
                <td style='padding: 6px; border-bottom: 1px solid #f0f0f0;'><b>Arma EIPOT: </b>$arma_eipot</td>
            </tr>
            <tr>
                <td colspan='2' style='padding: 6px; border-bottom: 1px solid #f0f0f0;'><b>Ano de Formação OFOR: </b>$ano_formacao_ofor</td>
                <td style='padding: 6px; border-bottom: 1px solid #f0f0f0;'><b>Nota OFOR: </b>$nota_ofor</td>
            </tr>";
}

$html .= "
            <tr>
                <td style='padding: 6px;'><b>Serviço Militar: </b> 
                    <span style='color: #006400;'>$tempo_sv_mil_anos anos</span>, 
                    <span style='color: #006400;'>$tempo_sv_mil_meses meses</span>, 
                    <span style='color: #006400;'>$tempo_sv_mil_dias dias</span>
                </td>
            </tr>
        </table>
    </div>
</div>

<!-- Card de Situação Militar -->
<div style='border: 1px solid #ddd; border-radius: 8px; margin-bottom: 20px; background: white; box-shadow: 0 2px 4px rgba(0,0,0,0.1);'>
    <div style='background: #006400; color: white; padding: 12px; border-radius: 8px 8px 0 0;'>
        <center><b>SITUAÇÃO MILITAR</b></center>
    </div>
    <div style='padding: 15px;'>
        <table border='0' style='font-size: 12px; width:100%'>
            <tr>
                <td colspan='3' style='padding: 6px; border-bottom: 1px solid #f0f0f0;'>
                    <b>Civil/Militar: </b>
                    <span style='color: #006400; font-weight: bold;'>$civil_militar</span>
                </td>
            </tr>";

if (isset($_SESSION['6_regiao']) || isset($_SESSION['12_regiao'])) {
    $html .= "
            <tr>
                <td colspan='3' style='padding: 6px; border-bottom: 1px solid #f0f0f0;'>
                    <b>Cidade de Apresentação: </b>
                    <span style='color: #006400;'>$cidade_etapas_presenciais</span>
                </td>
            </tr>";
}

$html .= "
            <tr>";
if ($certificado) $html .= "<td style='padding: 6px; border-bottom: 1px solid #f0f0f0;'><b>Certificado: </b>$certificado</td>";
if ($num_ducumento) $html .= "<td style='padding: 6px; border-bottom: 1px solid #f0f0f0;'><b>Nº Documento: </b>$num_ducumento</td>";
if ($data_expedicao) $html .= "<td style='padding: 6px; border-bottom: 1px solid #f0f0f0;'><b>Expedição: </b>$data_expedicao</td>";
$html .= "</tr>";

if ($civil_militar == 'militar' || $ativa_reserva == 'ja_foi_militar') {
    $html .= "
            <tr>
                <td style='padding: 6px; border-bottom: 1px solid #f0f0f0;'><b>Ativa/Reserva: </b>$ativa_reserva</td>
                <td style='padding: 6px; border-bottom: 1px solid #f0f0f0;'><b>Força: </b>$forca</td>
                <td style='padding: 6px; border-bottom: 1px solid #f0f0f0;'><b>Ano incorporação: </b>$ano_incorporacao</td>
            </tr>
            <tr>
                <td style='padding: 6px; border-bottom: 1px solid #f0f0f0;'><b>Posto/Graduação: </b>$posto_grad</td>
                <td style='padding: 6px; border-bottom: 1px solid #f0f0f0;'><b>Arma/Quadro/Serviço: </b>$arma_quadro_servico</td>";
    if ($licenciamento)
        $html .= "<td style='padding: 6px; border-bottom: 1px solid #f0f0f0;'><b>Licenciamento: </b>$licenciamento</td>";
    $html .= "</tr>";
}

$html .= "
            <tr>
                <td style='padding: 6px;'><b>Auto Declaração: </b> <span style='color: #006400;'>$autodeclaracao</span></td>
                <td style='padding: 6px;'><b>Vaga reservada: </b> 
                    <span style='color: " . ($vaga_reservada == 'SIM' ? '#006400' : '#666') . "; font-weight: bold;'>$vaga_reservada</span>
                </td>
            </tr>
        </table>
    </div>
</div>";

// DOCUMENTOS OBRIGATÓRIOS
$lista_docs_obrigatorios_adicionados = $conexao->get_docs_obrigatorios_inseridos_candidato($id_candidato);
$lista_docs_obrigatorios_sobrando_candidato = $conexao->get_documentos_obrigatorios_sobrando_candidato($id_candidato);
$get_candidato = $conexao->get_usuario_id($id_candidato);
$lista_docs_obrigatorios_sobrando = retorna_docs_obrigatorios_sobrando_candidato($get_candidato, $lista_docs_obrigatorios_sobrando_candidato);

$quantidade_docs_faltantes = count($lista_docs_obrigatorios_sobrando);

$html .= "
<div style='border: 1px solid " . ($quantidade_docs_faltantes > 0 ? '#dc3545' : '#006400') . "; border-radius: 8px; margin-bottom: 20px; background: white; box-shadow: 0 2px 4px rgba(0,0,0,0.1);'>
    <div style='background: " . ($quantidade_docs_faltantes > 0 ? '#dc3545' : '#006400') . "; color: white; padding: 12px; border-radius: 8px 8px 0 0;'>
        <center><b>DOCUMENTOS DE INSCRIÇÃO</b></center>
    </div>
    <div style='padding: 15px;'>
        <div style='font-size: 12px;'>
            <b>Adicionado(s): </b><br>";

foreach ($lista_docs_obrigatorios_adicionados as &$doc_acicionado) {
    if ((int)$_SESSION['selecao_regiao'] == 7) {
        $avaliacao = "";
        if ($doc_acicionado['valido'] == 1) $avaliacao = "<span style='color: #006400;'>Válido</span>";
        if ($doc_acicionado['valido'] == 0) $avaliacao = "<span style='color: #dc3545;'>Inválido</span>";
        if ($doc_acicionado['justificativa'] != null) $avaliacao = $avaliacao . " - " . $doc_acicionado['justificativa'];
        $html .= "• " . $doc_acicionado['label'] . " | " . $avaliacao . "<br>";
    } else {
        $html .= "• " . $doc_acicionado['label'] . "<br>";
    }
}

if ($quantidade_docs_faltantes > 0) {
    $html .= "
            <div style='color: #dc3545; padding-top: 15px; border-top: 1px solid #f0f0f0; margin-top: 10px;'>
                <b>Inscrição INCOMPLETA!</b> Conclua sua inscrição dentro do prazo previsto no Anexo A!<br>
                <b>" . $quantidade_docs_faltantes . " Documento(s) Faltando: </b><br>";
    foreach ($lista_docs_obrigatorios_sobrando as &$doc) {
        $html .= "• " . $doc['nome'] . "<br>";
    }
    $html .= "</div>";
} else {
    $html .= "
            <div style='color: #006400; padding-top: 10px; margin-top: 10px; font-weight: bold;'>
                Todos os documentos obrigatórios foram adicionados
            </div>";
}

$html .= "
        </div>
    </div>
</div>";

// ESPECIALIZAÇÕES CADASTRADAS
if (!isset($_SESSION['eipot'])) {
    $html .= "
    <div style='border: 1px solid #ddd; border-radius: 8px; margin-bottom: 20px; background: white; box-shadow: 0 2px 4px rgba(0,0,0,0.1);'>
        <div style='background: #006400; color: white; padding: 12px; border-radius: 8px 8px 0 0;'>
            <center><b>ESPECIALIDADE(S) CADASTRADA(S)</b></center>
        </div>
        <div style='padding: 15px;'>";

    $inscricoes = $conexao->get_especialidade_candidato($id_candidato);

    if (count($inscricoes) > 0) {
        foreach ($inscricoes as $valor) {
            $lista_docs_obrigatorios = $conexao->get_curriculos_inseridos_candidato($id_candidato, $valor['id_especialidade']);
            $get_prioridade_cidades = $conexao->get_prioridade_especialidade_candidato($valor['id_candidato_x_especialidade']);

            $concorrendo_na_especialidade = "";
            if ($valor['concorrendo'] == 0)
                $concorrendo_na_especialidade = " <span style='color: red;'> - DESCLASSIFICADO</span>";

            $html .= "
            <div style='margin-bottom: 15px; padding: 10px; border: 1px solid #eee; border-radius: 4px;'>
                <div style='font-weight: bold; margin-bottom: 8px; color: #006400;'>
                    " . mb_strtoupper($valor['ott_stt'], "UTF-8") . " - " . $valor['especialidade'] . " $concorrendo_na_especialidade
                </div>
                <div style='font-size: 11px;'>
                    <b>Documentos adicionados:</b> ";

            $pontuacao_total = null;
            if ($liberacao_avaliacao_curricular == 1) $pontuacao_total = 0;

            foreach ($lista_docs_obrigatorios as &$docs_obrigatorios) {
                if ((int)$_SESSION['selecao_regiao'] == 7 && $liberacao_avaliacao_curricular == 1) {
                    $avaliacao = "";
                    if ($docs_obrigatorios['valido'] == '1') {
                        $pontuacao = $docs_obrigatorios['pontuacao'] / 1000;
                        $multi = (int)$docs_obrigatorios['multiplicador'];
                        if ($multi > 1)
                            $pontuacao = $pontuacao * $multi;
                        $pontuacao_total = $pontuacao_total + $pontuacao;
                    }
                    if ($docs_obrigatorios['valido'] == 1) $avaliacao = "<span style='color: #006400;'>Válido</span>";
                    if ($docs_obrigatorios['valido'] == 0) $avaliacao = "<span style='color: #dc3545;'>Inválido</span>";
                    $avaliacao =  $avaliacao . " " . $docs_obrigatorios['justificativa'];
                    $html .= $docs_obrigatorios['label'] . " " . $avaliacao . " | ";
                } else {
                    $html .= $docs_obrigatorios['label'] . " | ";
                }
            }

            if ((int)$_SESSION['selecao_regiao'] == 7 && $liberacao_avaliacao_curricular == 1) {
                $html .= " <b>TOTAL DE PONTOS: $pontuacao_total</b>";
            }

            $html .= "
                </div>
            </div>";
        }
    } else {
        $html .= "
            <div style='color: red; text-align: center; padding: 20px;'>
                <b>Inscrição INCOMPLETA!</b><br>
                Nenhuma Especialidade Cadastrada!<br>
                Nenhum Currículo Adicionado!<br>
                Conclua sua inscrição dentro do prazo previsto no Anexo A!
            </div>";
    }

    $html .= "
        </div>
    </div>";
}

if ($candidato_relatorio[0]['etapa'] == 1) {
    $html .= "        
    <div style='border: 1px solid #006400; border-radius: 8px; padding: 15px; margin-bottom: 20px; background: #e8f5e8;'>
        <p style='font-size: 12px; margin: 0; color: #006400;'>
            <b>Observação:</b> Este relatório é de caráter informativo para conferência de dados cadastrados. A inscrição será validada após o término do período de inscrição.
        </p>
    </div>";
}

$html .= "  
<div style='text-align: center; padding: 20px; border-top: 2px solid #006400; margin-top: 20px;'>
    <p style='font-size: 12px; color: #006400; font-weight: bold;'>Relatório gerado em $datetime</p>
</div>";

include("mpdf60/mpdf.php");

$mpdf = new mPDF();
$mpdf->SetDisplayMode('fullpage');
$css = file_get_contents("css/estilo.css");
$mpdf->WriteHTML($css, 1);
$mpdf->WriteHTML($html);

$insere_log = $conexao->insere_log($_SESSION['id_usuario'], $_SESSION['cpf'], null, "20108", "relatorio", "create", "Gerou um relatório de informações do candidato CPF $cpf de ID $id_candidato", null);

if ($insere_log)
    $mpdf->Output("relatorio_candidato_$cpf.pdf", 'D');

exit;
