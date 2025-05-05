<?php

include_once '../funcoes.php';

if(!isset($_GET['data_inicio']) || !isset($_GET['data_fim']))
{
    echo '<p id="diferenca_datas"><b><legend>Diferença em Dias:</legend> </b></p>';
    exit();
}
    

if($_GET['data_inicio'] ==  "__/__/____"  || $_GET['data_fim'] ==  "__/__/____" || $_GET['data_fim'] == '' || $_GET['data_inicio'] == '')
{
    echo '<p id="diferenca_datas"><b><legend>Diferença em Dias: </legend></b></p>';
    exit();
}


$data_inicio = $_GET['data_inicio'];
$data_fim = $_GET['data_fim'];

if(!valida_data($data_inicio))
{
    echo '<p id="diferenca_datas"><b><legend>';    echo $_GET['data_inicio']; echo ' </legend></b></p>';
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

$total_dias = (int)$intervalo->format('%a');

if($total_dias > 0) 
    $total_dias = $total_dias + 1;


?>

<p id='diferenca_datas_dias'><b><legend>Diferença em Dias: <?php echo $total_dias ?> </legend> </b></p>
