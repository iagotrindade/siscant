<?php
ob_start();
include_once '../../banco_dados/conexao.php';
include_once '../../sistema/funcoes.php';
include("mpdf60/mpdf.php");

$mpdf = new mPDF();
$mpdf->SetDisplayMode('fullpage');
$css = file_get_contents("css/estilo.css");
$mpdf->WriteHTML($css, 1);

$id_candidato = $_GET['id_candidato'];
$id_especialidade = $_GET['id_especialidade'];

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

$conexao = new Conexao();

$id_usuario = $_SESSION['id_usuario'];

$candidato = $conexao->get_usuario_id($id_candidato);
$especialidade = $conexao->get_especialidade_id($id_especialidade);
$nome_selecao =  $candidato[0]['nome_selecao'] . " / " . $candidato[0]['ano_selecao'];

$foto = "red_user.jpeg";
$get_foto = $conexao->get_foto_usuario($id_candidato);
if (count($get_foto) > 0) {
    $foto = $get_foto[0]['nome'];
} else {
    $foto = "../imagens/user.jpg";
}

/*echo '   <pre>';
print_r($especialidade);
exit;*/
$html = "
<div style='border: 2px solid #006400; border-radius: 10px; padding: 15px; margin-bottom: 20px; background: linear-gradient(to bottom, #f8fff8, #f0f8f0); box-shadow: 0 4px 6px rgba(0,100,0,0.1);'>
    <table border='0' style='width:100%; border-collapse:collapse;'>
        <tr>
            <td colspan='2' style='text-align:center; padding: 0 0 4px 0;'>
                <div style='font-size: 18px; font-weight: bold; color: #006400; letter-spacing: 1px;'>
                    CHECKLIST DOCUMENTAL
                </div>
                <div style='height: 2px; background: linear-gradient(to right, transparent, #006400, transparent); margin: 8px 0;'></div>
            </td>
        </tr>

        <tr>
            <td colspan='2' style='text-align:center; padding: 4px 0;'>
                <div style='font-size: 14px; font-weight: 600; color: #2d5016; background: #e8f5e8; padding: 8px; border-radius: 6px; margin: 5px 0;'>
                    $nome_selecao
                </div>
            </td>
        </tr>

        <tr>
            <td style='padding: 15px 0 0 0; vertical-align: top;'>
                <div style='background: white; padding: 12px; border-radius: 6px;'>
                    <div style='font-size: 13px; font-weight: 600; margin-bottom: 8px; color: #2d5016;'>
                        Candidato: <span style='font-weight: 700; color: #006400;'>" . mb_strtoupper($candidato[0]['nome_completo']) . "</span>
                    </div>

                    <div style='font-size: 13px; font-weight: 600; margin-bottom: 8px; color: #2d5016;'>
                        CPF: <span style='font-weight: 700; color: #006400;'>" . mascara($candidato[0]['cpf'], '###.###.###-##') . "</span>
                    </div>

                    <div style='font-size: 13px; font-weight: 600; margin-bottom: 8px; color: #2d5016;'>
                        Especialidade: <span style='font-weight: 700; color: #006400;'>" . mb_strtoupper($especialidade[0]['ott_stt'] . ' - ' . $especialidade[0]['nome']) . "</span>
                    </div>

                    <div style='font-size: 13px; font-weight: 600; color: #2d5016;'>
                        Data: <span style='font-weight: 700; color: #006400;'>" . date('d/m/Y') . "</span>
                    </div>
                </div>
            </td>

            <td style='text-align: right; padding: 15px 0 0 0; width: 100px; vertical-align: top;'>
                <div style='display: inline-block; padding: 4px; background: white; border-radius: 8px;'>
                    <img src='../fotos/$foto' height='65px' style='border-radius: 4px; display: block; border: 2px solid #006400; width: 60px;'>
                </div>
            </td>
        </tr>
    </table> 
</div>
";

$todos_documentos_obrigatorios = $conexao->get_docs_checklist($id_candidato, 'obrigatorio');
$documentos_obrigatorios = array_column($todos_documentos_obrigatorios, null, 'doc_id');
$documentos_curriculares = $conexao->get_docs_checklist($id_candidato, 'curricular');

if (count($documentos_obrigatorios) > 0) {
    $html .= "
    <div style='border: 1px solid #e0e0e0; border-radius: 10px; margin-bottom: 20px; background: white; box-shadow: 0 3px 6px rgba(0,0,0,0.08); overflow: hidden;'>
        <div style='background: #006400; color: white; padding: 14px; border-radius: 10px 10px 0 0;'>
            <center>
                <b style='font-size: 15px; letter-spacing: 0.5px;'>DOCUMENTAÇÃO COMUM</b>
                <div style='font-size: 11px; opacity: 0.9; margin-top: 4px;'>
                    " . count($documentos_obrigatorios) . " documento(s)
                </div>
            </center>
        </div>
        <table border='0' style='width:100%; border-collapse:collapse;'>
    ";

    $contador = 0;
    foreach ($documentos_obrigatorios as $doc) {
        $contador++;
        $status_text = $doc['status'] == 1 ? "APRESENTADO" : "NÃO APRESENTADO";
        $status_color = $doc['status'] == 1 ? "#28a745" : "#dc3545";
        $bg_color = $contador % 2 === 0 ? '#fafafa' : '#ffffff';

        $html .= "
            <tr style='background: $bg_color;'>
                <td style='padding: 12px 15px; vertical-align: middle; border-bottom: 1px solid #f0f0f0;'>
                    <div style='font-size: 13px; font-weight: 600; color: #333;'>
                        " . mb_strtoupper($doc['doc_nome']) . "
                    </div>
                </td>
                <td style='padding: 12px 15px; vertical-align: middle; border-bottom: 1px solid #f0f0f0; width: 160px; text-align: center;'>
                    <div style='display: flex; align-items: center; justify-content: center; gap: 8px;'>
                        <span style='font-size: 11px; font-weight: 600; color: $status_color;'>$status_text</span>
                    </div>
                </td>
            </tr>
        ";
    }
    $html .= "
        </table> 
    </div>
    ";
}

if (count($documentos_curriculares) > 0) {
    $html .= "
    <div style='border: 1px solid #e0e0e0; border-radius: 10px; margin-bottom: 20px; background: white; box-shadow: 0 3px 6px rgba(0,0,0,0.08); overflow: hidden;'>
        <div style='background: #006400; color: white; padding: 14px; border-radius: 10px 10px 0 0;'>
            <center>
                <b style='font-size: 15px; letter-spacing: 0.5px;'>DOCUMENTAÇÃO CURRICULAR</b>
                <div style='font-size: 11px; opacity: 0.9; margin-top: 4px;'>
                    " . count($documentos_curriculares) . " documento(s)
                </div>
            </center>
        </div>
        <table border='0' style='width:100%; border-collapse:collapse;'>
    ";

    $contador = 0;
    foreach ($documentos_curriculares as $doc) {
        $contador++;
        $status_text = $doc['status'] == 1 ? "APRESENTADO" : "NÃO APRESENTADO";
        $status_color = $doc['status'] == 1 ? "#28a745" : "#dc3545";
        $bg_color = $contador % 2 === 0 ? '#fafafa' : '#ffffff';

        $html .= "
            <tr style='background: $bg_color;'>
                <td style='padding: 12px 15px; vertical-align: middle; border-bottom: 1px solid #f0f0f0;'>
                    <div style='font-size: 13px; font-weight: 600; color: #333;'>
                        " . mb_strtoupper($doc['doc_nome']) . "
                    </div>
                </td>
                <td style='padding: 12px 15px; vertical-align: middle; border-bottom: 1px solid #f0f0f0; width: 160px; text-align: center;'>
                    <div style='display: flex; align-items: center; justify-content: center; gap: 8px;'>
                        <span style='font-size: 11px; font-weight: 600; color: $status_color;'>$status_text</span>
                    </div>
                </td>
            </tr>
        ";
    }
    $html .= "
        </table> 
    </div>
    ";
}

// Adiciona quem avalioi
if ($todos_documentos_obrigatorios[0] != '') {
    $html .= "
    <div style='border: 1px solid #e0e0e0; border-radius: 10px; margin-bottom: 20px; background: white; box-shadow: 0 3px 6px rgba(0,0,0,0.08); overflow: hidden;'>
        <div style='background: #006400; color: white; padding: 15px; border-radius: 10px 10px 0 0;'>
            <center>
                <b style='font-size: 15px; letter-spacing: 0.5px;'>AVALIADOR</b>
            </center>
        </div>

        <div style='background: #FFFFFF; padding: 15px;'>
            Análise realizada em " .  trata_data_hora($todos_documentos_obrigatorios[0]['data_entrega']) . " por " . $todos_documentos_obrigatorios[0]['posto_grad'] . ' - ' . $todos_documentos_obrigatorios[0]['nome_avaliador'] . "
        </div>
    </div>
    ";
}

// Adiciona rodapé/resumo
$total_apresentados = 0;
$total_nao_apresentados = 0;

foreach ($documentos_obrigatorios as $doc) {
    $doc['status'] == 1 ? $total_apresentados++ : $total_nao_apresentados++;
}
foreach ($documentos_curriculares as $doc) {
    $doc['status'] == 1 ? $total_apresentados++ : $total_nao_apresentados++;
}

$html .= "
<div style='border: 1px solid #d4edda; border-radius: 8px; padding: 15px; margin-bottom: 20px; background: #d4edda;'>
    <table border='0' style='width:100%; border-collapse:collapse;'>
        <tr>
            <td style='text-align: center; padding: 5px;'>
                <div style='font-size: 13px; font-weight: 600; color: #155724;'>
                    RESUMO GERAL: 
                    <span style='color: #28a745;'>$total_apresentados APRESENTADO(S)</span> • 
                    <span style='color: #dc3545;'>$total_nao_apresentados NÃO APRESENTADO(S)</span> • 
                    <span style='color: #006400;'>" . ($total_apresentados + $total_nao_apresentados) . " TOTAL</span>
                </div>
            </td>
        </tr>
    </table>
</div>
";

$mpdf->WriteHTML($html);

$mpdf->Output("Checklist documental " . mb_strtoupper($candidato[0]['nome_completo'] . "_" . $especialidade[0]['ott_stt'] . ' - ' . $especialidade[0]['nome']) . ".pdf", 'D');
ob_end_flush();
exit();
