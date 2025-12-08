<?php
include_once '../../banco_dados/conexao.php';
include_once '../../sistema/funcoes.php';

session_start();

if (!isset($_SESSION['id_usuario'])) {
    erro_relatorio("Erro 823494! A sua sessão expirou! Faça o login no sistema para gerar o relatório");
    exit();
}

if ($_SESSION['perfil'] == 'candidato' || $_SESSION['candidato'] == 1) {
    $id_candidato = $_SESSION['id_usuario'];
} else {
    $id_candidato = (int)$_GET['id_candidato'];
}

$conexao = new Conexao();
$candidato_relatorio = $conexao->get_usuario_id($id_candidato);

if (count($candidato_relatorio) != 1) {
    erro_gerar_relatorio_cadastro_candidato("Erro 81456 ao gerar o comprovante! Candidato não encontrado!");
    exit();
}

$chave = hash('sha256', $id_candidato . $_SESSION['chave']);

if (!isset($_GET['crip'])) {
    erro_gerar_relatorio_cadastro_candidato("Erro 81423423456 ao gerar o comprovante!");
    exit();
}

if ($_GET['crip'] != $chave) {
    erro_gerar_relatorio_cadastro_candidato("Erro 8456 ao gerar o comprovante!");
    exit();
}

$id_selecao = $candidato_relatorio[0]['id_selecao'];
$cpf = $candidato_relatorio[0]['cpf'];
$perfil = $candidato_relatorio[0]['perfil'];
$candidato = $candidato_relatorio[0]['candidato'];
$nome_completo = mb_strtoupper($candidato_relatorio[0]['nome_completo'], "UTF-8");
$trocar_senha = $candidato_relatorio[0]['trocar_senha'];
$concorrendo = $candidato_relatorio[0]['concorrendo'];
$senha = $candidato_relatorio[0]['senha'];
$desistencia = $candidato_relatorio[0]['desistencia'];
$estado_civil = mb_strtoupper($candidato_relatorio[0]['estado_civil'], "UTF-8");
$sexo = mb_strtoupper($candidato_relatorio[0]['sexo'], "UTF-8");
$nome_social = mb_strtoupper($candidato_relatorio[0]['nome_social'], "UTF-8");
$pai = mb_strtoupper($candidato_relatorio[0]['pai'], "UTF-8");
$mae = mb_strtoupper($candidato_relatorio[0]['mae'], "UTF-8");
$identidade = $candidato_relatorio[0]['identidade'];
$nacionalidade = mb_strtoupper($candidato_relatorio[0]['nacionalidade'], "UTF-8");
$naturalidade = mb_strtoupper($candidato_relatorio[0]['naturalidade'], "UTF-8");
$dependente = $candidato_relatorio[0]['dependente'];
$data_nascimento = $candidato_relatorio[0]['data_nascimento'];
$uf = mb_strtoupper($candidato_relatorio[0]['uf'], "UTF-8");
$cep = $candidato_relatorio[0]['cep'];
$cidade = mb_strtoupper($candidato_relatorio[0]['nome_cidade'], "UTF-8");
$rua_num_complemento = mb_strtoupper($candidato_relatorio[0]['rua_num_complemento'], "UTF-8");
$bairro = mb_strtoupper($candidato_relatorio[0]['bairro'], "UTF-8");
$tel_residencial = $candidato_relatorio[0]['tel_residencial'];
$tel_celular = $candidato_relatorio[0]['tel_celular'];
$mail = mb_strtoupper($candidato_relatorio[0]['mail'], "UTF-8");
$tempo_sv_pub = $candidato_relatorio[0]['tempo_sv_pub'];
$tempo_sv_pub_anos = $candidato_relatorio[0]['tempo_sv_pub_anos'];
$tempo_sv_pub_meses = $candidato_relatorio[0]['tempo_sv_pub_meses'];
$tempo_sv_pub_dias = $candidato_relatorio[0]['tempo_sv_pub_dias'];
$tempo_sv_mil = $candidato_relatorio[0]['tempo_sv_mil'];
$tempo_sv_mil_anos = $candidato_relatorio[0]['tempo_sv_mil_anos'];
$tempo_sv_mil_meses = $candidato_relatorio[0]['tempo_sv_mil_meses'];
$tempo_sv_mil_dias = $candidato_relatorio[0]['tempo_sv_mil_dias'];
$certificado = mb_strtoupper($candidato_relatorio[0]['certificado'], "UTF-8");
$num_ducumento = $candidato_relatorio[0]['num_ducumento'];
$data_expedicao = $candidato_relatorio[0]['data_expedicao'];
$civil_militar = mb_strtoupper($candidato_relatorio[0]['civil_militar'], "UTF-8");
$ativa_reserva = mb_strtoupper($candidato_relatorio[0]['ativa_reserva'], "UTF-8");
$forca = mb_strtoupper($candidato_relatorio[0]['forca'], "UTF-8");
$ano_incorporacao = $candidato_relatorio[0]['ano_incorporacao'];
$posto_grad = mb_strtoupper($candidato_relatorio[0]['posto_grad'], "UTF-8");
$arma_quadro_servico = mb_strtoupper($candidato_relatorio[0]['arma_quadro_servico'], "UTF-8");
$licenciamento = mb_strtoupper($candidato_relatorio[0]['licenciamento'], "UTF-8");
$assinatura_sistema = $candidato_relatorio[0]['assinatura_sistema'];
$apagado = $candidato_relatorio[0]['apagado'];
$etapa = $candidato_relatorio[0]['etapa'];
$nome_selecao =  mb_strtoupper($candidato_relatorio[0]['nome_selecao'] . " / " . $candidato_relatorio[0]['ano_selecao'], "UTF-8");

$get_selecao = $conexao->get_selecao_id();

///////////////////////////////////////
//////////////
///////////// VALIDAÇÕES
////////////
///////////////////////////////////////

if (inscricao()) {
    erro_gerar_relatorio_cadastro_candidato("Erro 272344! Não foi possível gerar o comprovante de inscrição! Inscrição em andamento!");
    exit();
}

if ((int)$etapa < 2) {
    erro_gerar_relatorio_cadastro_candidato("Erro 236437457! Não foi possível gerar o comprovante de inscrição!");
    exit();
}

if ($get_selecao[0]['eliminar_docs_obrigatorios'] == '1') {
    $lista_docs_obrigatorios_sobrando_candidato = $conexao->get_documentos_obrigatorios_sobrando_candidato($id_candidato);
    $get_candidato = $conexao->get_usuario_id($_SESSION['id_usuario']);
    $lista_docs_obrigatorios_sobrando = retorna_docs_obrigatorios_sobrando_candidato($get_candidato, $lista_docs_obrigatorios_sobrando_candidato);

    $quantidade_docs_faltantes = count($lista_docs_obrigatorios_sobrando);

    if ($quantidade_docs_faltantes > 0) {
        erro_gerar_relatorio_cadastro_candidato("Erro 235345! $quantidade_docs_faltantes Documentos de Inscrição faltando! Não foi possível gerar o comprovante de inscrição!");
        exit();
    }
}

$inscricoes = $conexao->get_especialidade_candidato($id_candidato);
if (count($inscricoes) == 0) {
    erro_gerar_relatorio_cadastro_candidato("Erro 14234235! Nenhuma inscrição realizada! Não foi possível gerar o comprovante de inscrição!");
    exit();
}

$foto = "red_user.jpeg";
$get_foto = $conexao->get_foto_usuario($id_candidato);
if (count($get_foto) > 0) {
    $foto = $get_foto[0]['nome'];
} else {
    $foto = "../imagens/user.jpg";
}

///////////////////////////////////////
//////////////
///////////// PASSOU NAS VALIDAÇÕES
////////////
///////////////////////////////////////

$insere_log = $conexao->insere_log($id_candidato, $cpf, null, "18101", "Inscrição", "Relatório", "Candidato $cpf gerou o relatório de inscrição", null);

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
    $dependente = "NÃO POSSUI";

if ($data_expedicao != null)
    $data_expedicao = trata_data($data_expedicao);
if ($data_nascimento != null)
    $data_nascimento = trata_data($data_nascimento);

$datetime = date('d/m/Y H:i:s');

if ($nome_social == null)
    $nome_social = "NÃO POSSUI";

// ========== HTML COM DESIGN APIMORADO ==========

$html = "
<div style='border: 2px solid #006400; border-radius: 8px; padding: 15px; margin-bottom: 20px; background: #f8f9fa;'>
    <table border='0' style='width:100%'>
        <tr>
            <th align='center' style='font-size: 16px; font-weight: bold; color: #006400;'>
                COMPROVANTE DE INSCRIÇÃO
            </th>
        </tr>
        <tr>
            <th align='center' style='font-size: 14px; font-weight: bold; color: #006400;'>
                $nome_selecao
            </th>
        </tr>
    </table> 
</div>

<div style='border: 1px solid #006400; border-radius: 8px; padding: 15px; margin-bottom: 20px; background: white; box-shadow: 0 2px 4px rgba(0,0,0,0.1);'>
    <table border='0' style='width:100%'>
        <tr>
            <td align='left' style='vertical-align: top;'>
                <div style='font-size: 14px; font-weight: bold; margin-bottom: 10px; color: #006400;'>
                    Inscrição do Candidato <span style='text-decoration: underline;'>Nº: " . $id_candidato . "</span>
                </div>
                <div style='font-size: 12px; margin-bottom: 5px;'>
                    <strong>Status:</strong> 
                    <span style='padding: 4px 12px; border-radius: 15px; color: #006400;'>INSCRIÇÃO CONCLUÍDA</span>
                </div>
            </td>
            <td align='right' style='vertical-align: top;'>
                <img src='../fotos/$foto' height='70px' style='border-radius: 4px; border: 2px solid #006400;'>
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
                <td style='padding: 6px; border-bottom: 1px solid #f0f0f0;'><b>Estado Civil: </b> $estado_civil</td>
                <td style='padding: 6px; border-bottom: 1px solid #f0f0f0;'><b>Nome Social: </b> $nome_social</td>
            </tr>
            <tr>
                <td style='padding: 6px; border-bottom: 1px solid #f0f0f0;'><b>Identidade: </b> $identidade</td>
                <td style='padding: 6px; border-bottom: 1px solid #f0f0f0;'><b>Data de Nascimento: </b> <span style='color: #006400;'>$data_nascimento</span></td>
            </tr>
            <tr>
                <td style='padding: 6px; border-bottom: 1px solid #f0f0f0;'><b>Nome do Pai: </b> $pai</td>
                <td style='padding: 6px; border-bottom: 1px solid #f0f0f0;'><b>Sexo: </b> $sexo</td>    
            </tr>
            <tr>
                <td style='padding: 6px; border-bottom: 1px solid #f0f0f0;'><b>Nome da Mãe: </b> $mae</td>
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

<!-- Card de Informações Militares -->
<div style='border: 1px solid #ddd; border-radius: 8px; margin-bottom: 20px; background: white; box-shadow: 0 2px 4px rgba(0,0,0,0.1);'>
    <div style='background: #006400; color: white; padding: 12px; border-radius: 8px 8px 0 0;'>
        <center><b>INFORMAÇÕES MILITARES</b></center>
    </div>
    <div style='padding: 15px;'>
        <table border='0' style='font-size: 12px; width:100%'>
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
            </tr>
            <tr>";
if ($certificado) $html .= "<td style='padding: 6px; border-bottom: 1px solid #f0f0f0;'><b>Certificado: </b>$certificado</td>";
if ($num_ducumento) $html .= "<td style='padding: 6px; border-bottom: 1px solid #f0f0f0;'><b>Nº Documento: </b>$num_ducumento</td>";
if ($data_expedicao) $html .= "<td style='padding: 6px; border-bottom: 1px solid #f0f0f0;'><b>Expedição: </b>$data_expedicao</td>";
$html .= "</tr>";

if ($civil_militar == 'MILITAR' || $ativa_reserva == 'JA_FOI_MILITAR') {
    $html .= "
            <tr>
                <td style='padding: 6px; border-bottom: 1px solid #f0f0f0;'><b>Ativa/Reserva: </b>$ativa_reserva</td>
                <td style='padding: 6px; border-bottom: 1px solid #f0f0f0;'><b>Força: </b>$forca</td>
                <td style='padding: 6px; border-bottom: 1px solid #f0f0f0;'><b>Ano Incorporação: </b>$ano_incorporacao</td>
            </tr>
            <tr>
                <td style='padding: 6px; border-bottom: 1px solid #f0f0f0;'><b>Posto/Graduação: </b>$posto_grad</td>
                <td style='padding: 6px; border-bottom: 1px solid #f0f0f0;'><b>Arma/Quadro/Serviço: </b>$arma_quadro_servico</td>";
    if ($licenciamento)
        $html .= "<td style='padding: 6px; border-bottom: 1px solid #f0f0f0;'><b>Licenciamento: </b>$licenciamento</td>";
    $html .= "</tr>";
}

$html .= "
        </table>
    </div>
</div>

<!-- Card de Especialidades -->
<div style='border: 1px solid #ddd; border-radius: 8px; margin-bottom: 20px; background: white; box-shadow: 0 2px 4px rgba(0,0,0,0.1);'>
    <div style='background: #006400; color: white; padding: 12px; border-radius: 8px 8px 0 0;'>
        <center><b>ESPECIALIDADE(S) INSCRITAS</b></center>
    </div>
    <div style='padding: 15px;'>";

foreach ($inscricoes as $valor) {
    $lista_docs_obrigatorios = $conexao->get_curriculos_inseridos_candidato($id_candidato, $valor['id_especialidade']);
    $get_prioridade_cidades = $conexao->get_prioridade_especialidade_candidato($valor['id_candidato_x_especialidade']);

    $html .= "
        <div style='margin-bottom: 10px; padding: 10px; border: 1px solid #eee; border-radius: 4px; background: #f8f9fa;'>
            <div style='font-weight: bold; color: #006400;'>
                " . mb_strtoupper($valor['ott_stt'], "UTF-8") . " - " . mb_strtoupper($valor['especialidade'], "UTF-8") . "
            </div>
        </div>";
}

$html .= "
    </div>
</div>

<div style='text-align: center; padding: 20px; border-top: 2px solid #006400; margin-top: 20px;'>
    <p style='font-size: 12px; color: #006400; font-weight: bold;'>Comprovante gerado em $datetime</p>
    <p style='font-size: 16px; color: #006400; font-weight: bold; margin-top: 20px;'>$assinatura_sistema</p>
</div>";

include("mpdf60/mpdf.php");

$mpdf = new mPDF();
$mpdf->SetDisplayMode('fullpage');
$css = file_get_contents("css/estilo.css");
$mpdf->WriteHTML($css, 1);
$mpdf->WriteHTML($html);

$insere_log = $conexao->insere_log($_SESSION['id_usuario'], $_SESSION['cpf'], null, "20109", "relatorio", "create", "Gerou o comprovante de inscrição", null);
if ($insere_log)
    $mpdf->Output('Comprovante_Inscricao_' . $id_candidato . '.pdf', 'D');

exit;
