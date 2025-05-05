<?php

require_once("funcoes_codbarras.inc");
$campo1="8";//Identificação da arrecadação 
$campo2="5";//segmento 5 - Órgãos governamentais
$campo3="6";//identifcador do valor em reais -- COM 8 NÃO FUNCIONA, DIZ CÓDIGO VALIDADOR INVÁLIDO!
$campo4=""; //digito verificador do código de barras (calcular)
$campo5=$valortotalpedido_gru; //valor com 11 digitos incluindo centavos
$campo6="0254"; //código do STN junto a FEBRABAN
$campo7="22690"; //código do recolhimento 22690-4
//$campo8="02435"; //apelido da UG/Gestão responsável pela arrecadação CÓDIGO OBTIDO PLEO SUB TEN ROBERTO 02435
$campo8=$apelido; //apelido da UG/Gestão responsável pela arrecadação CÓDIGO OBTIDO PLEO SUB TEN ROBERTO 02435
$campo9=""; //tipo de contribuinte 1=CPF 2=CNPJ
$campo10=$cpfcnpjcliente_gru; //cpf ou cnpj do contribuinte
$campo5=convertervalorstring($campo5);//retira o ponto e a virgula do campo em moeda.

if (strlen($campo10)==11) 
{
    $campo9="1";
    $campo10="000".$campo10;
}
else
{
    $campo9="2";  
}


$string=$campo1.$campo2.$campo3.$campo5.$campo6.$campo7.$campo8.$campo9.$campo10;



$campo4=calculadigito($string);//calcula o digito verificador do c�digo de barras Campo 4

$codigo=$campo1.$campo2.$campo3.$campo4.$campo5.$campo6.$campo7.$campo8.$campo9.$campo10;

$cod1=substr($codigo, 0,11); 
$cod2=substr($codigo, 11,11); 
$cod3=substr($codigo, 22,11); 
$cod4=substr($codigo, 33,11); 

$dv1=calculadigito($cod1);
$dv2=calculadigito($cod2);
$dv3=calculadigito($cod3);
$dv4=calculadigito($cod4);

//echo $cod1."-".$dv1." ".$cod2."-".$dv2." ".$cod3."-".$dv3." ".$cod4."-".$dv4;
$valor=$cod1.$cod2.$cod3.$cod4;

$barra=gerarcodigodebarra($valor);
//echo "<center>";
//echo $cod1."-".$dv1." ".$cod2."-".$dv2." ".$cod3."-".$dv3." ".$cod4."-".$dv4;
//echo $barra;

echo "<br>";

?>
