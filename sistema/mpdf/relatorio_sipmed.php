<?php

include_once '../../banco_dados/conexao.php';
include_once '../../sistema/funcoes.php';
include("mpdf60/mpdf.php");
//05MAI25 - SILVA E IAGO -  AJUSTE CONEXAO E ATUALIZAÇÃO TABELA 
$mpdf = new mPDF(
    '',    // mode - default ''
    '',    // format - A4, for example, default ''
    0,     // font size - default 0
    '',    // default font family
    5,    // margin_left
    5,    // margin right
    16,    // margin top
    16,    // margin bottom
    5,     // margin header
    5,     // margin footer
    'L'
);

$mpdf->SetDisplayMode('fullpage');
$css = file_get_contents("css/estilo.css");
$mpdf->WriteHTML($css, 1);

$titulo_sipmed = $_POST['titulo_sipmed'];
$texto_sipmed = $_POST['texto_sipmed'];

set_time_limit(300);

session_start();

if (!isset($_SESSION['perfil'])) {

    var_dump('okl'); exit;
    erro_relatorio("Erro 823494! A sua sessão expirou! Faça o login no sistema para gerar o relatório");
    exit();
}
if ($_SESSION['perfil'] != 'admin' && $_SESSION['perfil'] != 'jise') {
    erro_relatorio("Erro 824! Somente o administrador pode gerar este relatório");
    exit();
}
if ($_SESSION['candidato'] == '1') {
    erro_gerar_relatorio_cadastro_candidato("Erro 8145345346 ao gerar relatório!");
    exit();
}

$conexao = new Conexao();

$id_usuario = $_SESSION['id_usuario'];

$rm_usuario = $conexao->rm_usuario($id_usuario);


if ($rm_usuario == "3")
    $_SESSION['cabecalho_relatorio'] = 'MINISTÉRIO DA DEFESA<br>
    EXÉRCITO BRASILEIRO<br>
    COMANDO MILITAR DO SUL<br>
    COMANDO DA 3ª REGIÃO MILITAR<br>
    (Gov das Armas Prov do RS/1821)<br>
    REGIÃO DOM DIOGO DE SOUZA<br>';

if ($rm_usuario == "8")
    $_SESSION['cabecalho_relatorio'] = 'MINISTÉRIO DA DEFESA<br>
    EXÉRCITO BRASILEIRO<br>
    COMANDO MILITAR DO SUL<br>
    COMANDO DA 8ª REGIÃO MILITAR<br>
    (Gov das Armas Prov do PA/1821)<br>
    REGIÃO FORTE DO PRESÉPIO<br>';

if ($rm_usuario == "6")
    $_SESSION['cabecalho_relatorio'] = 'MINISTÉRIO DA DEFESA<br>
    EXÉRCITO BRASILEIRO<br>
    COMANDO DA 6ª REGIÃO MILITAR<br>
    (Governo das Armas Província da Bahia/1821)<br>
    REGIÃO MARECHAL CANTUÁRIA<br>';

if ($rm_usuario == "12")
    $_SESSION['cabecalho_relatorio'] = 'MINISTÉRIO DA DEFESA<br>
    EXÉRCITO BRASILEIRO<br>
    COMANDO MILITAR DA 12ª REGIÃO MILITAR<br>
    (Comando de Elementos de Fronteira/1948)<br>
    (FORTE MENDONÇA FURTADO)<br>';

if ($rm_usuario == "7")
    $_SESSION['cabecalho_relatorio'] = 'MINISTÉRIO DA DEFESA<br>
    EXÉRCITO BRASILEIRO<br>
   COMANDO DA 7ª REGIÃO MILITAR
(Gov das Armas Prov PE/1821)
REGIÃO MATIAS DE ALBUQUERQUE<br>';

if ($rm_usuario == "5")
    $_SESSION['cabecalho_relatorio'] = 'MINISTÉRIO DA DEFESA<br>
EXÉRCITO BRASILEIRO<br>
COMANDO DA 5ª REGIÃO MILITAR<br>
(Comando das Armas do Estado do Paraná/1990)<br>
“REGIÃO HERÓIS DA LAPA<br>';

if ($rm_usuario == "11")
    $_SESSION['cabecalho_relatorio'] = 'MINISTÉRIO DA DEFESA<br>
EXÉRCITO BRASILEIRO<br>
COMANDO DA 11ª REGIÃO MILITAR<br>
(Cmdo Mil Bsb/1960)<br>
REGIÃO TENENTE-CORONEL LUIZ CRULS<br>';

if ($rm_usuario == "2")
    $_SESSION['cabecalho_relatorio'] = 'MINISTÉRIO DA DEFESA<br>
EXÉRCITO BRASILEIRO<br>
COMANDO DA 2ª REGIÃO MILITAR<br>
(Cmdo das Armas Prov Pr/1890)<br>
REGIÃO DAS BANDEIRAS<br>';

if ($rm_usuario == "4")
    $_SESSION['cabecalho_relatorio'] = 'MINISTÉRIO DA DEFESA<br>
EXÉRCITO BRASILEIRO<br>
COMANDO DA 4ª REGIÃO MILITAR<br>
(4⁰ Distrito Militar/1891)<br>
REGIÃO DAS MINAS DO OURO<br>';

$html = "
<p class='center' style='font-size: 10px; text-align: center; margin-bottom: 5px;'>
    <img src='../imagens/brasao.png' width='70px'><br>
    " . $_SESSION['cabecalho_relatorio'] . "
</p>
<table border='0' style='width:100%; margin-top: 5px; margin-bottom: 5px;'>
    <tr>
        <th align='center'><strong>" . $titulo_sipmed . "</strong></th>
    </tr>
</table>

<p style='font-size: 12px; font-family: Times New Roman; text-align: justify; margin: 5px 0;'>
     " . $texto_sipmed . "
</p>
";

$mpdf->WriteHTML($html);

$lista_candidatos = $conexao->get_inscritos_eipot_tabelas($rm_usuario);

$ordem_arma = [
    'INFANTARIA',
    'CAVALARIA',
    'ARTILHARIA DE CAMPANHA',
    'ARTILHARIA ANTIAÉREA',
    'ENGENHARIA',
    'COMUNICAÇÕES',
    'MATERIAL BÉLICO',
    'INTENDÊNCIA'
];

$inscritos_por_arma = [];

foreach ($lista_candidatos as $inscrito) {
    if ($inscrito['rm_inscricao'] == $rm_usuario) { // Filtra por rm_inscricao
        $arma = $inscrito['arma_especialidade'];
        $inscritos_por_arma[$arma][] = $inscrito;
    }
}

uksort($inscritos_por_arma, function ($a, $b) use ($ordem_arma) {
    // Verificando se a especialidade $a e $b estão na ordem específica
    $pos_a = array_search($a, $ordem_arma);
    $pos_b = array_search($b, $ordem_arma);

    // Se não encontrar a especialidade, coloca no final e ordena alfabeticamente
    if ($pos_a === false) $pos_a = PHP_INT_MAX;
    if ($pos_b === false) $pos_b = PHP_INT_MAX;

    // Se ambos os valores estão fora da ordem (não definidos em $ordem_arma), ordena alfabeticamente
    if ($pos_a === PHP_INT_MAX && $pos_b === PHP_INT_MAX) {
        return strcasecmp($a, $b);
    }

    return $pos_a - $pos_b;
});

foreach ($inscritos_por_arma as $arma => $candidatos) {
    if (count($candidatos) === 0) {
        continue; // Não cria tabela se não houver candidatos
    }
    //TROCA DAS COLUNAS IDT E NSC
    $html = "
   <table border='1' style='width:100%; border-collapse: collapse; margin-bottom: 20px; font-size: 10px;'>
    <tr>
        <th colspan='12' style='text-align: center; background-color: #D8D8D8; font-size: 10px;'>" . mb_strtoupper($arma, "UTF-8") . "</th>
    </tr>
    <tr style='font-size: 10px;'>
        <th style='text-align: center; '>CPF</th>
        <th style='text-align: center; '>NOME</th>
        <th style='text-align: center;'>IDT</th>
        <th style='text-align: center;'>NASCIMENTO</th>
        <th style='text-align: center; '>NATURALIDADE</th>
        <th style='text-align: center; '>SEXO</th>
        <th style='text-align: center; '>MÃE</th>
        <th style='text-align: center; '>PAI</th>
        <th style='text-align: center; '>ENDEREÇO</th>
        <th style='text-align: center;'>TELEFONE</th>
        <th style='text-align: center;'>EMAIL</th>
        <th style='text-align: center;'>CIVIL/MILITAR</th>
    </tr>
";
    /*$html = "
   <table border='13' style='width:100%; border-collapse: collapse; margin-bottom: 20px;'>
    <tr>
        <th colspan='12' style='text-align: center; background-color: #D8D8D8; font-size: 12px;'>" . mb_strtoupper($arma, "UTF-8") . "</th>
    </tr>
    <tr style='font-size: 10px;'>
        <th style='text-align: center; width: 5%;'>CPF</th>
        <th style='text-align: center; width: 10%;'>NOME</th>
        <th style='text-align: center; width: 5%;'>NASCIMENTO</th>
        <th style='text-align: center; width: 5%;'>IDT</th>
        <th style='text-align: center; width: 5%;'>NATURALIDADE</th>
        <th style='text-align: center; width: 5%;'>SEXO</th>
        <th style='text-align: center; width: 10%;'>MÃE</th>
        <th style='text-align: center; width: 10%;'>PAI</th>
        <th style='text-align: center; width: 10%;'>ENDEREÇO</th>
        <th style='text-align: center; width: 5%;'>TELEFONE</th>
        <th style='text-align: center; width: 5%;'>EMAIL</th>
        <th style='text-align: center; width: 5%;'>CIVIL/MILITAR</th>
    </tr>
"; */

    $contador = 1;

    foreach ($candidatos as $candidato) {

        $html .= "
        <tr style='font-size: 8px;'>
            <td style='text-align: center;'>" . strtoupper($candidato['cpf']) . "</td>
            <td style='text-align: center;'>" . strtoupper($candidato['nome_completo']) . "</td>
            <td style='text-align: center;'>" . strtoupper($candidato['identidade']) . "</td>
            <td style='text-align: center;'>" . strtoupper($candidato['data_nascimento']) . "</td>
            <td style='text-align: center;'>" . strtoupper($candidato['naturalidade']) . "</td>
            <td style='text-align: center;'>" . strtoupper($candidato['sexo']) . "</td>
            <td style='text-align: center;'>" . strtoupper($candidato['mae']) . "</td>
            <td style='text-align: center;'>" . strtoupper($candidato['pai']) . "</td>
            <td style='text-align: center;'>" . strtoupper($candidato['rua_num_complemento']." - ".$candidato['bairro']." - ".$candidato['cidade_endereco']."/".$candidato['uf']." CEP: ".$candidato['cep']) . "</td>
            <td style='text-align: center;'>" . strtoupper($candidato['tel_celular']) . "</td>
            <td style='text-align: center;'>" . strtoupper($candidato['mail']) . "</td>
            <td style='text-align: center;'>" . strtoupper('Civil') . "</td>
        </tr>";
        $contador++;
    }

    $html .= "</table>";
    $mpdf->WriteHTML($html);
}


// Se necessário, adicione um AddPage() no final para uma nova página após todas as tabelas

// $mpdf->WriteHTML($html);
$mpdf->Output("sipmed.pdf", 'D');

exit();
