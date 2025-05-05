<?php

$resultado_selecao = $conexao->get_selecao_id();

//include_once '../PHPMailer/class.phpmailer.php';

//Funcionava até 24/02/2023
//include_once '../PHPMailer/PHPMailerAutoload.php';

include_once '../envia_carta/src/Exception.php';
include_once '../envia_carta/src/PHPMailer.php';
include_once '../envia_carta/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

##########################################
#Inicia a classe PHPMailer
$mail_envia = new PHPMailer();

////////////////////////////////////////////////////////////////////////////
// Desabilita SSL para que a nova versoã do PHPMailer possa enviar o E-Mail
$mail_envia->SMTPOptions = array(
'ssl' => array(
    'verify_peer' => false,
    'verify_peer_name' => false,
    'allow_self_signed' => true
));
////////////////////////////////////////////////////////////////////////////

#Define os dados do servidor e tipo de conexão
$mail_envia->IsSMTP(); // Define que a mensagem será SMTP

//$mail_envia->Host = "smtp.1cta.eb.mil.br"; // Endereço do servidor SMTP --- Funcionando até 30/11/2021
$mail_envia->Host = "lugia.1cta.eb.mil.br"; // Endereço do servidor SMTP


$mail_envia->SMTPAuth = false; // Autenticação
// $mail_envia->Username = 'usuario@3rm.eb.mil.br'; // Usuário do servidor SMTP
$mail_envia->Password = ''; // Senha da caixa postal utilizada
#Define o remetente
$mail_envia->From = "siscant@3rm.eb.mil.br"; // Endereço de quem enviou o e-mail
$mail_envia->FromName = "Serviço Militar";// E-MAIL Recebido de quem
#Define os destinatário(s)

//$primeiro_mail = "paolao@3rm.eb.mil.br";
//$segundo_mail  = "leandrosilva@3rm.eb.mil.br";
//$terceiro_mail  = "selecao_svtt@3rm.eb.mil.br";

//$primeiro_mail = "gfreitas@3rm.eb.mil.br";
//$segundo_mail  = "gfreitas@3rm.eb.mil.br";
//$terceiro_mail  = "gfreitas@3rm.eb.mil.br";

$mail_envia->AddAddress($primeiro_mail, "3º SGT Paola");// Vai enviar o e-mail, E-Mail e Nome
$mail_envia->AddAddress($segundo_mail,  "SGT Leandro Silva");// Vai enviar o e-mail, E-Mail e Nome
$mail_envia->AddAddress($terceiro_mail, "Seleção SVTT");// Vai enviar o e-mail, E-Mail e Nome

//$mail_envia->SMTPDebug  = 1; 
//$mail_envia->addReplyTo('noreply@servico_militar', 'First Last'); // Aqui define um endereço(email) alternativo para resposta.

//$mail_envia->AddBCC('gfreitas@3rm.eb.mil.br', 'Ten Freitas'); // Cópia Oculta
#Define os dados técnicos da Mensagem
$mail_envia->IsHTML(true); // Define que o e-mail será enviado como HTML
$mail_envia->CharSet = 'utf-8'; // Charset da mensagem (opcional)

$datetime = date('H:i:s d/m/Y');

$mail_envia->Subject = "Candidato escolheu cidade para servir"; // Assunto da mensagem

if($cidade_escolheu_servir != 754809)
{
    $mensagem = 
    "
    <u>".  mb_strtoupper($_SESSION['selecao_nome'], 'UTF-8'). " - ".$_SESSION['selecao_ano']."</u><br><br>

    Um candidato <b><font color='green'>ESCOLHEU</font></b> a cidade onde quer servir. <br><br>

    <b>Nome:</b> ".mb_strtoupper($_SESSION['nome_completo'], 'UTF-8')." <br>
    <b>CPF: </b>".$_SESSION['cpf']." <br>
    <b>Especialidade: </b> ".$nome_especialidade." <br>
    <b>Posição: </b> ".$lugar."º lugar <br>
    <b>Cidade: </b> ".$nome_cidade_escolheu." <br>
    <b>Horário: </b> ".$datetime." <br><br>

    <i> Este é um e-mail automático, por favor não responda!";
}
if($cidade_escolheu_servir == 754809)
{
    $mensagem = 
    "
    <u>".  mb_strtoupper($_SESSION['selecao_nome'], 'UTF-8'). " - ".$_SESSION['selecao_ano']."</u><br><br>

    Um candidato <b><font color='red'>DESISTIU</font></b> da sua vaga! <br><br>

    <b>Nome:</b> ".mb_strtoupper($_SESSION['nome_completo'], 'UTF-8')." <br>
    <b>CPF: </b>".$_SESSION['cpf']." <br>
    <b>Especialidade: </b> ".$nome_especialidade." <br>
    <b>Posição: </b> ".$lugar."º lugar <br>
    <b>Horário: </b> ".$datetime." <br><br>
    
    <i> Este é um e-mail automático, por favor não responda!";
}

$mail_envia->Body = $mensagem;
#Envio da Mensagem
$enviado = $mail_envia->Send();
#Limpa os destinatários e os anexos
$mail_envia->ClearAllRecipients();
$mail_envia->ClearAttachments();
#Exibe uma mensagem de resultado

if($enviado)
{
    $mensagem_formatada = str_replace("'","`",$mensagem);
    $alteracao = "E-Mail de escolha de cidade do candidato ".$_SESSION['cpf']." enviado para: ".$primeiro_mail . " , " .$segundo_mail . " e ". $terceiro_mail;
    $insere_log = $conexao->insere_log($_SESSION['id_usuario'], $_SESSION['cpf'], null, "14132", "mail", "Insert", $alteracao, $mensagem_formatada);
    
}
if(!$enviado)
{
    $detalhe_erro = print_r(error_get_last());
    $alteracao = "ERRO! E-Mail de escolha de cidade do candidato ".$_SESSION['cpf']." NÃO foi enviado para: ".$primeiro_mail . " , " .$segundo_mail . " e ". $terceiro_mail;
    $insere_log = $conexao->insere_log($_SESSION['id_usuario'], $_SESSION['cpf'], null, "21103", "mail", "Insert", $alteracao, $detalhe_erro);
}

?>