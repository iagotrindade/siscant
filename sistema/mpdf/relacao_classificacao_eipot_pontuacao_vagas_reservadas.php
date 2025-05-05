<?php 
include_once '../../banco_dados/conexao.php';
include_once '../../sistema/funcoes.php';
include("mpdf60/mpdf.php");

$mpdf=new mPDF(); 
$mpdf->SetDisplayMode('fullpage');
$css = file_get_contents("css/estilo.css");
$mpdf->WriteHTML($css,1);

$titulo_pontos = $_POST['titulo_pontos'];
$subtitulo_pontos = $_POST['subtitulo_pontos'];
$texto_pontos = $_POST['texto_pontos'];
$texto_dia = $_POST['texto_dia'];

set_time_limit(300);

session_start();

if(!isset($_SESSION['perfil']))
{
    erro_relatorio("Erro 823494! A sua sessão expirou! Faça o login no sistema para gerar o relatório");
    exit();
}
if($_SESSION['perfil'] != 'admin')
{
    erro_relatorio("Erro 824! Somente o administrador pode gerar este relatório");
    exit();
}
if($_SESSION['candidato'] == '1')
{
    erro_gerar_relatorio_cadastro_candidato("Erro 8145345346 ao gerar relatório!");
    exit();
}

$conexao = new Conexao();

$id_usuario = $_SESSION['id_usuario'];
$rm_usuario = $conexao->rm_usuario($id_usuario);

if($rm_usuario == "3")
$_SESSION['cabecalho_relatorio'] = 'MINISTÉRIO DA DEFESA<br>
    EXÉRCITO BRASILEIRO<br>
    COMANDO MILITAR DO SUL<br>
    COMANDO DA 3ª REGIÃO MILITAR<br>
    (Gov das Armas Prov do RS/1821)<br>
    REGIÃO DOM DIOGO DE SOUZA<br>';

if($rm_usuario == "8")
$_SESSION['cabecalho_relatorio'] = 'MINISTÉRIO DA DEFESA<br>
    EXÉRCITO BRASILEIRO<br>
    COMANDO DA 8ª REGIÃO MILITAR<br>
    (Gov das Armas Prov do PA/1821)<br>
    REGIÃO FORTE DO PRESÉPIO<br>';

if($rm_usuario == "6")
$_SESSION['cabecalho_relatorio'] = 'MINISTÉRIO DA DEFESA<br>
    EXÉRCITO BRASILEIRO<br>
    COMANDO DA 6ª REGIÃO MILITAR<br>
    (Governo das Armas Província da Bahia/1821)<br>
    REGIÃO MARECHAL CANTUÁRIA<br>';

if($rm_usuario == "12")
$_SESSION['cabecalho_relatorio'] = 'MINISTÉRIO DA DEFESA<br>
    EXÉRCITO BRASILEIRO<br>
    COMANDO MILITAR DA 12ª REGIÃO MILITAR<br>
     (Comando de Elementos de Fronteira/1948)<br>
    (FORTE MENDONÇA FURTADO)<br>';

if($rm_usuario == "7")
$_SESSION['cabecalho_relatorio'] = 'MINISTÉRIO DA DEFESA<br>
    EXÉRCITO BRASILEIRO<br>
   COMANDO DA 7ª REGIÃO MILITAR
(Gov das Armas Prov PE/1821)
REGIÃO MATIAS DE ALBUQUERQUE<br>';

if($rm_usuario == "5")
$_SESSION['cabecalho_relatorio'] = 'MINISTÉRIO DA DEFESA<br>
EXÉRCITO BRASILEIRO<br>
COMANDO DA 5ª REGIÃO MILITAR<br>
(Comando das Armas do Estado do Paraná/1990)<br>
“REGIÃO HERÓIS DA LAPA<br>';

if($rm_usuario == "11")
$_SESSION['cabecalho_relatorio'] = 'MINISTÉRIO DA DEFESA<br>
EXÉRCITO BRASILEIRO<br>
COMANDO DA 11ª REGIÃO MILITAR<br>
(Cmdo Mil Bsb/1960)<br>
REGIÃO TENENTE-CORONEL LUIZ CRULS<br>';

if($rm_usuario == "2")
$_SESSION['cabecalho_relatorio'] = 'MINISTÉRIO DA DEFESA<br>
EXÉRCITO BRASILEIRO<br>
COMANDO DA 2ª REGIÃO MILITAR<br>
(Cmdo das Armas Prov Pr/1890)<br>
REGIÃO DAS BANDEIRAS<br>';

if($rm_usuario == "4")
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
       <th align='center'><strong>" . $titulo_pontos . "</strong></th>
    </tr>
</table>

<table border='0' style='width:100%; margin-top: 5px; margin-bottom: 5px;'>
    <tr>
       <th align='center'><strong>" . $subtitulo_pontos ."</strong></th>
    </tr>
</table>

<table border='0' style='width:100%; margin-top: 5px; margin-bottom: 5px;'>
    <tr>
        <th align='right'><strong>" . $texto_dia ."</strong></th>
    </tr>
</table>

<p style='font-size: 12px; font-family: Times New Roman; text-align: justify; margin: 5px 0;'>
    " . $texto_pontos . "
</p>

<p style='font-size: 12px; font-family: Times New Roman; text-align: justify; margin: 5px 0;'>
  
    <br>
    <br>
</p>
";

$mpdf->WriteHTML($html);
$id_usuario = $_SESSION['id_usuario'];
$rm_usuario = $conexao->rm_usuario($id_usuario); 
$lista_inscritos = $conexao->get_inscritos_eipot_vagas_reservadas_tabelas($rm_usuario); 

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

foreach ($lista_inscritos as $inscrito) {
    
    if ($inscrito['arma_especialidade'] == null || $inscrito['arma_eipot']  == null) continue;
        $arma = $inscrito['arma_especialidade'];
        $inscritos_por_arma[$arma][] = $inscrito;
    
}

uksort($inscritos_por_arma, function($a, $b) use ($ordem_arma) {
    $pos_a = array_search($a, $ordem_arma);
    $pos_b = array_search($b, $ordem_arma);
    
    // Se não encontrar a especialidade, colocamos no final
    if ($pos_a === false) $pos_a = PHP_INT_MAX;
    if ($pos_b === false) $pos_b = PHP_INT_MAX;

    return $pos_a - $pos_b;
});
// Agrupa inscritos por arma


foreach ($inscritos_por_arma as $arma => $candidatos) {
    if (count($candidatos) === 0) {
        continue; // Não cria tabela se não houver candidatos
    }
    
    $html = "
   <table border='1' style='width:100%; border-collapse: collapse; margin-bottom: 20px;'>
    <tr>
        <th colspan='4' style='text-align: center; background-color: #D8D8D8; font-size: 14px;'>" . mb_strtoupper($arma, "UTF-8") . "</th>
    </tr>
    <tr>
        <th style='text-align: center; width: 10%;'>Nº</th>
        <th style='text-align: center; width: 30%;'>CPF</th>
        <th style='text-align: center; width: 60%;'>NOME</th>
    </tr>
    ";

    $contador = 1;
    $nota_final_eipot = [];
    
    foreach ($candidatos as $index => $candidato) {
        // Obtendo a nota final de cada candidato
        $nota_final_eipot[$index] = get_nota_final_eipot($candidato['id']);
        $nota_final_eipot[$index] = (float) $nota_final_eipot[$index];
        
        // Mascarando o CPF
        $cpf = substr($candidato['cpf'], 0, -5) . "*****";
    }
    
    // Ordenando os arrays de candidatos e suas notas simultaneamente (decrescente)
    array_multisort($nota_final_eipot, SORT_DESC, $candidatos);

    usort($candidatos, function($a, $b) {
        return strcmp(strtoupper($a['nome_completo']), strtoupper($b['nome_completo']));
    });
    
    // Gerando o HTML para a tabela após a ordenação
    foreach ($candidatos as $index => $candidato) {
        // O CPF já foi mascarado no primeiro loop
        $cpf = substr($candidato['cpf'], 0, -5) . "*****";
        $html .= "
        <tr>
            <td style='text-align: center;'>$contador</td>
            <td style='text-align: center;'>$cpf</td>
            <td style='text-align: left;'>" . strtoupper($candidato['nome_completo']) . "</td>
        </tr>";
        $contador++;
    }
    
    $html .= "</table>";
    $mpdf->WriteHTML($html);
}



// Se necessário, adicione um AddPage() no final para uma nova página após todas as tabelas



 //$mpdf->SetDisplayMode('fullwidth');
 
//$mpdf->WriteHTML($html);
$mpdf->Output("inscritos.pdf",'D');

exit();