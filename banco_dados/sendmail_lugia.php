<?php

$resultado_selecao = $conexao->get_selecao_id();


//Funcionava até 24/02/2023
//include_once '../PHPMailer/PHPMailerAutoload.php';

// Antigo Funciona na 1º versão que foi utilizado
//include_once '../PHPMailer_old/class.phpmailer.php';


include_once '../envia_carta/src/Exception.php';
include_once '../envia_carta/src/PHPMailer.php';
include_once '../envia_carta/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


#########################################
#Inicia a classe PHPMailer
$mail_envia = new PHPMailer();

////////////////////////////////////////////////////////////////////////////
// Desabilita SSL para que a nova versão do PHPMailer possa enviar o E-Mail
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
$mail_envia->Password = '12345678'; // Senha da caixa postal utilizada
#Define o remetente
$mail_envia->From = "siscant@3rm.eb.mil.br"; // Endereço de quem enviou o e-mail
$mail_envia->FromName = "Serviço Militar";// E-MAIL Recebido de quem
#Define os destinatário(s)
$mail_envia->AddAddress($mail_usuario, $cpf_usuario);// Vai enviar o e-mail, E-Mail e Nome

//$mail_envia->SMTPDebug  = 1; 
//$mail_envia->addReplyTo('noreply@servico_militar', 'First Last'); // Aqui define um endereço(email) alternativo para resposta.

//$mail_envia->AddBCC('gfreitas@3rm.eb.mil.br', 'Ten Freitas'); // Cópia Oculta
#Define os dados técnicos da Mensagem
$mail_envia->IsHTML(true); // Define que o e-mail será enviado como HTML
$mail_envia->CharSet = 'utf-8'; // Charset da mensagem (opcional)


$mail_envia->Subject = "Serviço Técnico Temporário"; // Assunto da mensagem
$mensagem = 
"
<b>A sua senha foi resetada. Sua nova senha é </b>' . $nova_senha . '
<br>
Qualquer dúvida, utilize o suporte que estará disponível ao se logar no sistema. <br><br>

<i> Este é um e-mail automático, por favor não responda!</i>";

$mail_envia->Body = $mensagem;
#Envio da Mensagem
$enviado = $mail_envia->Send();
#Limpa os destinatários e os anexos
$mail_envia->ClearAllRecipients();
$mail_envia->ClearAttachments();
#Exibe uma mensagem de resultado

$_SESSION['cadastro_candidato_email_enviado'] = false;    
if($enviado)
{
    $_SESSION['cadastro_candidato_email_enviado'] = true;    
    $mensagem_formatada = str_replace("'","`",$mensagem);
    $alteracao = "E-Mail de reset enviado para:".$mail_usuario;
    
    $insere_log = $conexao->insere_log(null, $cpf_usuario, $last_id, "14102", "mail", "Insert", $alteracao, $mensagem_formatada);
    
}
if(!$enviado)
{
    $detalhe_erro = print_r(error_get_last());
    $alteracao = "ERRO! E-Mail de reset de candidato NÃO enviado para:".$mail_usuario;
    $insere_log = $conexao->insere_log(null, $cpf_usuario, $last_id, "21100", "mail", "Insert", $alteracao, $detalhe_erro);
}

?>