<?php
ob_start();
include_once '../banco_dados/conexao.php';
include_once '../sistema/funcoes.php';

session_start();

$titulo = $_POST['titulo'] ?? '';
$paragrafo_um = $_POST['paragrafo_um'] ?? '';

set_time_limit(300);

// Segurança sessão
if (!isset($_SESSION['perfil'])) {
    erro_relatorio("Erro 823494! A sua sessão expirou! Faça o login no sistema para gerar o relatório");
    exit;
}
if ($_SESSION['perfil'] != 'admin' && $_SESSION['perfil'] != 'jise') {
    erro_relatorio("Erro 824! Somente o administrador pode gerar este relatório");
    exit;
}
if (!empty($_SESSION['candidato']) && $_SESSION['candidato'] == '1') {
    erro_gerar_relatorio_cadastro_candidato("Erro 8145345346 ao gerar relatório!");
    exit;
}

$conexao = new Conexao();
$id_usuario = $_SESSION['id_usuario'];
$datetime = date('d/m/Y H:i:s');

// Início do HTML com formatação melhorada
$html = '<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <style>
        body { font-family: Arial, sans-serif; font-size: 11px; }
        table { border-collapse: collapse; width: 100%; margin-bottom: 20px; }
        th { background-color: #f2f2f2; font-weight: bold; text-align: center; 
              padding: 8px; border: 1px solid #ddd; }
        td { padding: 6px; border: 1px solid #ddd; text-align: left; }
        h3 { background-color: #e8e8e8; padding: 10px; margin: 15px 0 10px 0; 
             border-left: 4px solid #3366cc; }
        .header { text-align: center; margin-bottom: 20px; }
        .footer { margin-top: 30px; text-align: right; font-size: 10px; color: #666; }
        .numero { text-align: center; width: 40px; }
        .data { text-align: center; width: 90px; }
        .cpf, .identidade { text-align: center; width: 100px; }
    </style>
</head>
<body>';

// Cabeçalho do relatório
$html .= '<div class="header">
            <h1>RELATÓRIO DE CANDIDATOS PARA CADASTRO - SIPMED</h1>
            <p>Data de geração: ' . $datetime . '</p>
         </div>';

$lista_especialidades = $conexao->get_especialidade();
$contador_geral = 1;

foreach ($lista_especialidades as $especialidade) {
    $lista_candidatos = $conexao->get_candidatos_especialidade($especialidade['id']);
    if (count($lista_candidatos) === 0) {
        continue;
    }

    $html .= "<h3>ESPECIALIDADE: " . mb_strtoupper($especialidade['nome'], 'UTF-8') . "</h3>";

    $html .= "<table>
                <tr>
                    <th class='numero'>Nº</th>
                    <th>NOME COMPLETO</th>
                    <th>E-MAIL</th>
                    <th class='cpf'>CPF</th>
                    <th class='identidade'>IDENTIDADE</th>
                    <th class='data'>DATA NASC.</th>
                    <th>NOME DA MÃE</th>
                    <th>NOME DO PAI</th>
                    <th>ENDEREÇO COMPLETO</th>
                </tr>";

    $contador_especialidade = 1;

    foreach ($lista_candidatos as $candidato) {
        if ($candidato['etapa'] < 3) {
            continue;
        }

        $cidade = $conexao->get_cidade_id($candidato['id_cidade']);

        $endereco = mb_strtoupper(
            $candidato['rua_num_complemento'] . " - " .
                $candidato['bairro'] . " - " .
                $cidade[0]['nome'] . "/" .
                $candidato['uf'] . " - CEP: " .
                $candidato['cep'],
            'UTF-8'
        );
        $data_nascimento = trata_data($candidato['data_nascimento']);

        $html .= "<tr>
                    <td class='numero'>{$contador_geral}</td>
                    <td>" . mb_strtoupper($candidato['nome_completo'], 'UTF-8') . "</td>
                    <td>{$candidato['mail']}</td>
                    <td class='cpf'>{$candidato['cpf']}</td>
                    <td class='identidade'>{$candidato['identidade']}</td>
                    <td class='data'>{$data_nascimento}</td>
                    <td>" . mb_strtoupper($candidato['mae'], 'UTF-8') . "</td>
                    <td>" . mb_strtoupper($candidato['pai'], 'UTF-8') . "</td>
                    <td>{$endereco}</td>
                  </tr>";

        $contador_geral++;
        $contador_especialidade++;
    }

    $html .= "</table>
              <p><strong>Total de candidatos nesta especialidade: </strong>" . ($contador_especialidade - 1) . "</p>
              <br>";
}

// Rodapé do relatório
$html .= '<div class="footer">
            <p>Relatório gerado automaticamente pelo SiSCanT</p>
            <p>Total geral de candidatos: ' . ($contador_geral - 1) . '</p>
         </div>';

$html .= '</body></html>';

$data_ = date('d_m_Y_H_i_s');
$arquivo = "relatorio_candidatos_sipmed_{$data_}.xls";

// Headers para download
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Content-Type: application/vnd.ms-excel; charset=utf-8");
header("Content-Disposition: attachment; filename=\"{$arquivo}\"");
header("Content-Description: PHP Generated Data");

echo $html;
ob_end_flush();
exit();
