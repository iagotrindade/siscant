<?php

include_once '../funcoes.php';

if(!isset($_GET['data_inicio']) || !isset($_GET['data_fim']))
{
    echo '<p id="diferenca_datas"><b><legend>Diferença em Meses:</legend> </b></p>';
    exit();
}
    

if($_GET['data_inicio'] ==  "__/__/____"  || $_GET['data_fim'] ==  "__/__/____" || $_GET['data_fim'] == '' || $_GET['data_inicio'] == '')
{
    echo '<p id="diferenca_datas"><b><legend>Diferença em Meses: </legend></b></p>';
    exit();
}


$data_inicio = $_GET['data_inicio'];
$data_fim = $_GET['data_fim'];

if(!valida_data($data_inicio))
{
    echo '<p id="diferenca_datas"><b><legend>Data de Início inválida! </legend></b></p>';
    exit();
}

if(!valida_data($data_fim))
{
    echo '<p id="diferenca_datas"><b><legend>Data de Fim inválida!</legend></b></p>';
    exit();
}

$data_inicio = reverte_data($data_inicio);
$data_fim = reverte_data($data_fim);


$data_inicio = new DateTime(date($data_inicio));
$data_fim = new DateTime(date($data_fim));
$intervalo = $data_fim->diff($data_inicio);

$anos = (int)$intervalo->format('%Y');
$meses = (int)$intervalo->format('%m');
$dias = (int)$intervalo->format('%d');

$total_dias = null;
if($data_inicio != null && $data_fim != null)
{
    $dias_anos = $anos*365;
    $dias_meses = $meses*30;
    $total_dias = $dias_anos + $dias_meses + $dias +1;
}
$total_meses = $total_dias/30;
$total_meses = round($total_meses,2);

?>

<p id='diferenca_datas'><b><legend>Diferença em Meses: <?php echo $total_meses ?> </legend> </b></p>
