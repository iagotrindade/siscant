<?php
//session_start();
//extract($_POST);
//extract($_GET);
//extract($_SESSION);
# MOD10.PHP
# CALCULAR D�GITO MOD10 - PESOS 2 E 1
# MAVIAEL RAMALHO CALADO
# www.maviael.com
# contato@maviael.com

function calculadigito($string){
		              // Recebe o valor do form em index.htm
		$posicao1 = strlen($string)-1;     // Obtem a posicao do �ltimo digito da string
		$multi    = 2;                     // Declara o multiplicador com o valor de 2
		$acumula  = 0;                     // Zera a variavel que acumular� a soma dos digitos
		
		while ($posicao1 >= 0){            // Loop para multiplicar cada digito da string por 2 ou 1, da direita pra esquerda
		      $resultado = substr($string,$posicao1,1) * $multi;
		      $posicao2  = strlen($resultado)-1;
		      while ($posicao2 >= 0){      // Loop para acumular a soma dos digitos do resultado da multiplica��o
		            $acumula = $acumula + substr($resultado,$posicao2,1);
		            $posicao2--;
		      }
		      if ($multi == 2){            // Alterna o multiplicador entre 2 e 1
		         $multi = 1;
		      }else{
		         $multi = 2;
		      }
		      $posicao1--;                 // Controla a posi��o da string a ser processada
		}
		
		//$digito = bcmod($acumula, 10);
		$digito = fmod($acumula, 10);
		
		$digito = 10 - $digito;
		
		if ($digito == 10){
		   $digito = 0;
		}
		
		return $digito;
}

function gerarcodigodebarra($valor){
$barra = null;
		  
		  if (strlen($valor)%2 != 0){
		    $valor="0".$valor;
		  } 
		
		  $BarCodes[0]="00110";
		  $BarCodes[1]="10001";
		  $BarCodes[2]="01001";
		  $BarCodes[3]="11000";
		  $BarCodes[4]="00101";
		  $BarCodes[5]="10100";
		  $BarCodes[6]="01100";
		  $BarCodes[7]="00011";
		  $BarCodes[8]="10010";
		  $BarCodes[9]="01010";
		  $pe = "<img src=\"codigo_barras/preto.gif\"  width=\"1\" height=\"50\">";
		  $pl = "<img src=\"codigo_barras/preto.gif\"  width=\"3\" height=\"50\">";
		  $be = "<img src=\"codigo_barras/branco.gif\" width=\"1\" height=\"50\">";
		  $bl = "<img src=\"codigo_barras/branco.gif\" width=\"3\" height=\"50\">";
		  $barras = array("B" => $pl.$bl, "b" => $pl.$be, "N" => $pe.$bl, "n" => $pe.$be);
		
		  #Desenha o inicio do c�digo
		  $retorno  = $barras["n"]; 
		  $retorno .= $barras["n"];
		
		  #Desenha o c�digo de barras de acordo com o valor especificado
		  for ($i=0;$i<strlen($valor);$i=$i+2){
		    $num    = substr($valor,$i,2);
		    $cadeia = "";
		    for ($conta=0;$conta<5;$conta++){
		       $texto  = "";
		       $texto1 = "";
		       for ($conta1=0;$conta1<2;$conta1++){
		          $texto=$texto.substr($BarCodes[substr($num,$conta1,1)],$conta,1);
		       }
		       if ($texto == "00"){      //Preto Fino + Branco Fino
		          $texto1 = $texto1."n";
		       }
		       if ($texto == "01"){      //Preto Fino + Branco Largo
		          $texto1 = $texto1."N";
		       }
		       if ($texto == "10"){      //Preto Largo + Branco Fino
		          $texto1 = $texto1."b";
		       }
		       if ($texto == "11"){      //Preto Largo + Branco Largo
		          $texto1 = $texto1."B";
		       }
		       $cadeia = $cadeia.$texto1;
		    }
		    $numeros = array($num => $cadeia);
		    $cod     = $numeros[$num];
		    for ($j=0;$j<=4;$j++){
		      $retorno .= $barras[substr($cod,$j,1)];
		    }
		  }
		
		  #Desenha o final do c�digo
		  $retorno .= $barras["b"];
		  $retorno .= $barras["n"];
		echo '<center>';
		  //$barra =($v1."-".$d1."  ".$v2."-".$d2."  ".$v3."-".$d3."  ".$v4."-".$d4);
		  $barra .= ("<br>");
		  $barra .= ($retorno);
		  return $barra;
}

function convertervalorstring($valor_real){//pega o valor formatado e transforma em string 1.120,00=>112000

		$valor_real = str_replace(".","", $valor_real);
		$valor_real = str_replace(",","", $valor_real);
		$valor_real = str_replace(" ","", $valor_real);
		
		$nrcar=strlen($valor_real);
		$zeros=11-$nrcar;

		if ($zeros=="1") {
		    $valor_real="0".$valor_real;
		}
		if ($zeros=="2") {
		    $valor_real="00".$valor_real;
		}
		if ($zeros=="3") {
		    $valor_real="000".$valor_real;
		}
		if ($zeros=="4") {
		    $valor_real="0000".$valor_real;
		}
		if ($zeros=="5") {
		    $valor_real="00000".$valor_real;
		}
		if ($zeros=="6") {
		    $valor_real="000000".$valor_real;
		}
		if ($zeros=="7") {
		    $valor_real="0000000".$valor_real;
		}
		if ($zeros=="8") {
		    $valor_real="00000000".$valor_real;
		}
		if ($zeros=="9") {
		    $valor_real="000000000".$valor_real;
		}
		if ($zeros=="10") {
		    $valor_real="0000000000".$valor_real;
		}
		
		return $valor_real;

}

?>
