<?php

$data_hoje = date('d/m/Y H:i:s');

include_once '../../banco_dados/conexao.php';
include_once '../../sistema/funcoes.php';
include("mpdf60/mpdf.php");

$old = ini_set('memory_limit', '512M');

$mpdf = new mPDF();
$mpdf->SetDisplayMode('fullpage');
$css = file_get_contents("css/estilo.css");
$mpdf->WriteHTML($css, 1);

set_time_limit(300);

session_start();

if (!isset($_SESSION['perfil'])) {
    erro_relatorio("Erro 823494! A sua sessão expirou! Faça o login no sistema para gerar o relatório");
    exit();
}
if ($_SESSION['perfil'] != 'admin') {
    erro_relatorio("Erro 824! Somente o administrador pode gerar este relatório");
    exit();
}
if ($_SESSION['candidato'] == '1') {
    erro_gerar_relatorio_cadastro_candidato("Erro 8145345346 ao gerar relatório!");
    exit();
}

if ($_POST['data_inicial_inspecao'] == '' || $_POST['data_inicial_inspecao'] == null || $_POST['data_final_inspecao'] == '' || $_POST['data_final_inspecao'] == null) {
    erro_gerar_relatorio_cadastro_candidato("Você deve inserir o intervalo de datas da inspeção de saúde!");
    exit();
}

$orientacao = htmlspecialchars(trim($_POST['orientacao']));
$cabecalho = htmlspecialchars(trim($_POST['cabecalho']));

$data_inicial = $_POST['data_inicial_inspecao'];
$data_final = $_POST['data_final_inspecao'];
$data_ata = reverte_data($_POST['data_inspecao']);

$assinante_1 = null;
$assinante_2 = null;
$assinante_3 = null;

$cidade = null;
$sessao = null;

$conexao = new Conexao();

$get_exames_saude = $conexao->get_exames_medico();

foreach ($get_exames_saude as $linha) {
    if ($linha['dia_exame'] == $data_ata) {
        $sessao = $linha['sessao'];
        $cidade = $linha['cidade'];
        $assinante_1 = $linha['presidente'];
        $assinante_2 = $linha['membro_1'];
        $assinante_3 = $linha['membro_2'];
    }
}

$selecao = $resultado = $conexao->get_selecao_id();

$ano_selecao = (int)$selecao[0]['ano'];
$ano_selecao_mais_um = $ano_selecao + 1;
$codigo = strtoupper($selecao[0]['codigo']);
if ($codigo == "OTT_STT") $codigo = "OTT/STT";

$eas_ebst = "";

if ($codigo == "OTT/STT") $eas_ebst = "EST/EBST";
if ($codigo == "MFDV") $eas_ebst = "EAS";


$selecao_atual = $conexao->get_selecao_id();

if ($cabecalho == 'sim') {
    $html = "
    <p class='center' style='font-size: 10px;'>

        <img src='../imagens/brasao.png' width='70px'><br>
            " . $_SESSION['cabecalho_relatorio'] . " 

    </p>";
}
$html = $html . "
 <table border='0' style='width:100%'>
    <tr>
          <th align='center'><strong>Relatório dos inspecionados</strong></th>
    </tr>
</table> 
 <table border='0' style='width:100%'>
  <tr>
    <th align='center'><strong> <p style='font-size: 12px; font-family: Times New Roman;'>JISE: CSE-$codigo - $eas_ebst de " . $data_inicial . " a " . $data_final . "</p></strong></th>
  </tr>
</table> 

";

$lista_candidatos_ = $conexao->get_ata_dia_exame_medico(reverte_data($data_inicial), reverte_data($data_final));

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
                    <b>SEXO</b>
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

foreach ($lista_candidatos_ as &$candidato) {
    // Filtro por intervalo de datas
    if (!empty($data_inicial) && !empty($data_final)) {
        $data_inspecao = $candidato['data_exame_saude'];

        // Valida e converte as datas para timestamps
        $data_inicial_ts = DateTime::createFromFormat('d/m/Y', $data_inicial) ? DateTime::createFromFormat('d/m/Y', $data_inicial)->getTimestamp() : false;
        $data_final_ts = DateTime::createFromFormat('d/m/Y', $data_final) ? DateTime::createFromFormat('d/m/Y', $data_final)->getTimestamp() : false;
        $data_inspecao_ts = DateTime::createFromFormat('Y-m-d', $data_inspecao) ? DateTime::createFromFormat('Y-m-d', $data_inspecao)->getTimestamp() : false;

        // Verifica se as datas são válidas
        if ($data_inicial_ts === false || $data_final_ts === false || $data_inspecao_ts === false) {
            continue; // Ignora se qualquer data for inválida
        }

        // Verifica se a data da inspeção está fora do intervalo
        if ($data_inspecao_ts < $data_inicial_ts || $data_inspecao_ts > $data_final_ts) {
            continue;
        }
    }


    $apto = "";
    $grupo_saude = "";
    $cid_saude = "";

    if ($candidato['apto_saude'] === '1') {
        $apto = "APTO";
    } elseif ($candidato['apto_saude'] === '0') {
        $apto = "INAPTO";
    } else {
        $apto = "NÃO COMPARECEU";
    }

    $grupo_saude = $candidato['grupo_saude'];
    $cid_saude = $candidato['cid_saude'];

    if ($candidato['apto_saude_recurso'] === '1') $apto = "APTO";
    if ($candidato['apto_saude_recurso'] === '0') $apto = "INAPTO";

    $grupo_saude = $candidato['grupo_saude_recurso'];
    $cid_saude = $candidato['cid_saude_recurso'];

    $nascimento = "";
    if ($candidato['data_nascimento'] != null) $nascimento = trata_data($candidato['data_nascimento']);

    $html = $html . "
        <tr>
            <td>
                " . $contador . "
            </td>
            <td>
                " . $candidato['cpf'] . "
            </td>
            <td>
                " . mb_strtoupper($candidato['nome_completo'], "UTF-8") . "
            </td>
            <td>
                " . $candidato['sexo'] . "
            </td>
            <td>
                " . $nascimento . "
            </td>
            <td>
                " . $candidato['mae'] . "
            </td>
            <td>
                " . $apto . "
            </td>
             <td>
                " . strtoupper($grupo_saude) . "
            </td>
            <td>
                " . $cid_saude . "
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
    <th width='33%'>$assinante_1</th>
    <th width='33%'>$assinante_2</th>
    <th width='33%'>$assinante_3</th>
  </tr>
</table> ";

if ($orientacao == 'paisagem') $mpdf->AddPage('L');

$mpdf->WriteHTML($html);

$alteracoes_detalhadas = " Data da geração do relatório $data_hoje |
                           Cabeçalho = $cabecalho 
                           Orientação = $orientacao";

$insere_log = $conexao->insere_log($_SESSION['id_usuario'], $_SESSION['cpf'], null, "20103", "relatorio", "create", "Gerou um relatório de quem realizou exame de saúde na data de $data_inspecao_saude", $alteracoes_detalhadas);

if ($insere_log)
    $mpdf->Output("Relatório Inspeção de Saúde.pdf", 'D');

exit();
