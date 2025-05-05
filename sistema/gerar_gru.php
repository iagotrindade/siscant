<?php

include_once 'funcoes.php';
session_start();
include_once '../banco_dados/conexao.php';
$conexao = new Conexao();

if(!$_GET)
{
    erro_mensagem("Erro 385856856765!");
    exit();
}

if(!isset($_SESSION['chave']) || !isset($_SESSION['selecao']))
{
    $conexao = null;
    erro("Erro 742375345457! Sua sesão foi encerrada! Faça o Login Novamente!");
    exit();
}

if($_SESSION['candidato'] != 1)
{
    $conexao = null;
    erro("Erro 347347345745! Não foi possível gerar a GRU!");
    exit();
}

if( $_GET['crip'] != hash('sha256', $_SESSION['chave']."freitas"))
{
    $conexao = null;
    erro("Erro 8546835745! Não foi possível gerar a GRU!");
    exit();
}

$get_usuario = $conexao->get_usuario_id($_SESSION['id_usuario']);

$nome_usuario = $get_usuario[0]['nome_completo'];
$cpf_usuario_limpo = $get_usuario[0]['cpf'];

$cpf_usuario_mascara = mascara($cpf_usuario_limpo,'###.###.###-##');
 
$get_selecao = $conexao->get_selecao_id();

if($get_selecao[0]['pagamento'] == '0' || $get_selecao[0]['pagamento'] == null)
{
    $conexao = null;
    erro("Erro 7634743756! Não foi possível gerar a GRU!");
    exit();
}
if($get_selecao[0]['valor_gru'] == '0' || $get_selecao[0]['valor_gru'] == null)
{
    $conexao = null;
    erro("Erro 3473757! Não foi possível gerar a GRU!");
    exit();
}
if($get_selecao[0]['apelido_ug'] == '0' || $get_selecao[0]['apelido_ug'] == null)
{
    $conexao = null;
    erro("Erro 547857567! Não foi possível gerar a GRU!");
    exit();
}


//$valor_gru="0,19";
$apelido = $get_selecao[0]['apelido_ug'];
$valor_gru=$get_selecao[0]['valor_gru'];
$nomecliente_gru=$nome_usuario;
$cpfcnpjcliente_gru_formatado = $cpf_usuario_mascara; // $cpf_usuario_mascara;
$cpfcnpjcliente_gru = $cpf_usuario_limpo;
$valortotalpedido_gru=(string)number_format($valor_gru, 2, ',', '');

$data_validade_gru = $get_selecao[0]['data_fim_inscricao'];
if($data_validade_gru != null || $data_validade_gru != '')
    $data_validade_gru = trata_data ($data_validade_gru);

include("codigo_barras/funcoes_codbarras.inc");
include("codigo_barras/gerar_cod_barras.php");

$valor_aparece_no_boleto = number_format($valor_gru, 2, ',', '');

$insere_log = $conexao->insere_log($_SESSION['id_usuario'], $_SESSION['cpf'], $_SESSION['id_usuario'], "22100", null, null, "Candidato gerou uma GRU automática no valor de R$ $valor_gru", null);
?>
<html>
			
    <head>
        <meta http-equiv="Content-Language" content="pt-br">
        <meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
        <title>GRU - SiSCanT</title>
    </head>
			
    <body>
			
    <div align="center">
        <table border="1" cellpadding="0" cellspacing="0" width="715" id="table1" bordercolor="#333333" style="border-collapse: collapse" height="114">
            <tr>
                <td width="85" rowspan="4">
                    <p align="center">
                    <img border="0" src="imagens/brasao.png" width="82" height="82">
                </td>
                <td align="center" valign="top" width="351" rowspan="4">
                    <p align="center">
                        <font size="3" face="Arial">
                            <br>MINISTÉRIO DA FAZENDA
                            <br> SECRETARIA DO TESOURO NACIONAL
                            <br> Guia de Recolhimento da União - GRU
                        </font>
                    </p>
                    
                </td>
                <td align="left" valign="top" width="136" height="20">
                <font size="1" face="Arial">Gestão responsável pela arrecadação</font></td>
                <td align="right" height="20" width="137"><font face="Arial"><b><?php echo $apelido ?></b></font></td>
            </tr>
            <tr>
                <td align="left" valign="top" width="136" height="20">
                <font size="1" face="Arial">Número de Referência</font></td>
                <td align="right" height="20" width="137"><font face="Arial"><b></b></font></td>
            </tr>
            <tr>
                <td align="left" valign="top" width="136" height="20">
                <font size="1" face="Arial">Competência</font></td>
                <td align="right" height="20" width="137"><font face="Arial"><b><?php echo ""; ?></b></font></td>
            </tr>
            <tr>
                <td align="left" valign="top" width="136" height="20">
                <font size="1" face="Arial">Vencimento</font></td>
                <td align="right" height="20" width="137"><font face="Arial"><b><?php echo $data_validade_gru ?></b></font></td>
            </tr>
        </table>
    </div>
			
    <div align="center">
        <table border="1" cellpadding="0" cellspacing="0" width="715" id="table2" style="border-collapse: collapse" bordercolor="#333333" height="259">

            <tr>
                <td align="left" valign="top" height="20"><font face="Arial">
                <font size="1">&nbsp;Nome do Contribuinte<br>
&nbsp;&nbsp;&nbsp;&nbsp; </font><b><font size="2"><?php echo $nomecliente_gru; ?></font></b></font></td>
                <td width="136" align="left" valign="top" height="20">
                <font size="1" face="Arial">CPF do Contribuinte</font></td>
                <td width="137" align="right" height="20"><font face="Arial"><b>
                <?php echo $cpfcnpjcliente_gru_formatado; ?></b></font></td>
            </tr>
            <tr>		
                <td height="20"><font face="Arial"><font size="1">&nbsp;Nome do 
                Recolhedor<br>
&nbsp;&nbsp;&nbsp;&nbsp; </font><b><font size="2">COMANDO DA <?php echo $get_selecao[0]['rm']?>ª REGIÃO MILITAR</font></b></font></td>
                <td width="136" align="left" valign="top" height="20">
                <font size="1" face="Arial">UG/Gestão</font></td>
                <td width="137" align="right" height="20"><font face="Arial"><b>
                167090/00001</b></font></td>
            </tr>
            <tr>
                <td height="120" rowspan="4" align="justify" valign="top">
                <font face="Arial"><font size="1">&nbsp;Instruções<br>
                </font>&nbsp;<font size="2">&nbsp;&nbsp; </font><b><font size="2">
                    A cópia do comprovante de pagamento desta taxa de inscrição deve ser anexada no SISCANT em arquivo PDF dentro do período de inscrição previsto, que é quando o Sr(a) possui permissão para atualizar informações e efetuar inserções de arquivos.
                    </font></b></font></td>
                <td width="136" align="left" valign="top" height="20">
                <font size="1" face="Arial">(=) Valor do Principal</font></td>
                <td width="137" align="right" height="20"><font face="Arial"><b>
                <?php echo $valor_aparece_no_boleto; ?></b></font></td>
            </tr>
            <tr>
                <td width="136" align="left" valign="top" height="20">
                <font size="1" face="Arial">(-) Desconto/Abatimento</font></td>
                <td width="137" align="right" height="20">&nbsp;</td>
            </tr>
            <tr>
                <td width="136" align="left" valign="top" height="20">
                <font size="1" face="Arial">(-) Outras deduções</font></td>
                <td width="137" align="right" height="20">&nbsp;</td>
            </tr>
            <tr>
                <td width="136" align="left" valign="top" height="20">
                <font size="1" face="Arial">(+) Mora/Multa</font></td>
                <td width="137" align="right" height="20">&nbsp;</td>
            </tr>
            <tr>
                <td height="90" rowspan="3">
                <p align="center"><b>GRU Simples</b></p>
                <p align="center"><b>Pagamento exclusivo no Banco do Brasil</b></td>
                <td width="136" align="left" valign="top" height="20">
                <font size="1" face="Arial">(+) Juros/Encargos</font></td>
                <td width="137" align="right" height="20">&nbsp;</td>
            </tr>
            <tr>
                <td width="136" align="left" valign="top" height="20">
                <font size="1" face="Arial">(+) Outros acréscimos</font></td>
                <td width="137" align="right" height="20">&nbsp;</td>
            </tr>
            <tr>
                <td width="136" align="left" valign="top" height="20">
                <font size="1" face="Arial">(=) Valor Total</font></td>
                <td width="137" align="right" height="20"><font face="Arial"><b>
                <?php echo $valor_aparece_no_boleto; ?></b></font></td>
            </tr>
        </table>
    </div>
			
    <div align="center"><br>
        <table border="1" cellpadding="0" cellspacing="0" width="715" id="table3" style="border-width:0px; border-collapse: collapse" bordercolor="#333333" height="41">
            <tr>
                <td width="462" style="border-style: none; border-width: medium">
                    <p align="center"> 
                        <font size="2">
                            <?php 
                                echo $cod1."-".$dv1." ".$cod2."-".$dv2." ".$cod3."-".$dv3." ".$cod4."-".$dv4; 
                            ?> 
                        </font>
                </td>
                <td width="250" style="border-style: none; border-width: medium">
                <font face="Arial" size="1">Autenticação Mecânica</font></td>
            </tr>
            <tr>
                <td colspan="2" style="border-style: none; border-width: medium">
                <p align="center"><?php echo $barra;?></td>
                <td style="border-style: none; border-width: medium">&nbsp;</td>
            </tr>
        </table>
    </div>
        
        <br>
        <br>
        <br>
        
        <input type="button" name="imprimir" value="Imprimir GRU" onclick="window.print();">
        
    </body>
</html>
			
