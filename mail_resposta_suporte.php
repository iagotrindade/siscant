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


#########################################
#Inicia a classe PHPMailer
$mail_envia = new PHPMailer();

////////////////////////////////////////////////////////////////////////////
// Desabilita SSL para que a nova versoã do PHPMailer possa enviar o E-Mail
$mail_envia->SMTPOptions = array(
    'ssl' => array(
        'verify_peer' => true,
        'verify_peer_name' => true,
        'allow_self_signed' => false
    )
);
////////////////////////////////////////////////////////////////////////////

#Define os dados do servidor e tipo de conexão
$mail_envia->IsSMTP(); // Define que a mensagem será SMTP

//$mail_envia->Host = "smtp.1cta.eb.mil.br"; // Endereço do servidor SMTP --- Funcionando até 30/11/2021
$mail_envia->Host = "smtp.webmail.eb.mil.br"; // Endereço do servidor SMTP
$mail_envia->Port = 587; // Porta

$mail_envia->SMTPAuth = true; // Autenticação
// $mail_envia->Username = 'usuario@3rm.eb.mil.br'; // Usuário do servidor SMTP
$mail_envia->Username = 'siscant@3rm.eb.mil.br'; // Seu usuário SMTP
$mail_envia->Password = '12345678'; // Sua senha SMTP
$mail_envia->SMTPSecure = 'tls';  //'tls' Define o tipo de criptografia para TLS
#Define o remetente
$mail_envia->From = "siscant@3rm.eb.mil.br"; // Endereço de quem enviou o e-mail
$mail_envia->FromName = "Serviço Militar"; // E-MAIL Recebido de quem
#Define os destinatário(s)
$mail_envia->AddAddress($mail, $cpf_requerente); // Vai enviar o e-mail, E-Mail e Nome

//$mail_envia->SMTPDebug  = 1; 
//$mail_envia->addReplyTo('noreply@servico_militar', 'First Last'); // Aqui define um endereço(email) alternativo para resposta.

//$mail_envia->AddBCC('gfreitas@3rm.eb.mil.br', 'Sgt Garrido'); // Cópia Oculta
#Define os dados técnicos da Mensagem
$mail_envia->IsHTML(true); // Define que o e-mail será enviado como HTML
$mail_envia->CharSet = 'utf-8'; // Charset da mensagem (opcional)


$mail_envia->Subject = "Serviço Técnico Temporário - Resposta Suporte"; // Assunto da mensagem
$mensagem =
    "
<b>" .  mb_strtoupper($resultado_selecao[0]['nome'], 'UTF-8') . " - " . $resultado_selecao[0]['ano'] . "</b><br><br>
    
" . $nome_completo_requerente . ", esta é a resposta da sua solicitação de suporte!<br><br>

<i>Data de envio: " . trata_data($data_enviado_requerente) . " <br>
Suporte Solicitado: " . $mensagem_requerente . " </i><br><br>

<b>Data Resposta: </b> " .  trata_data($datetime) . "<br>
<b>Resposta: </b> " . $resposta . "<br></b>

<br><i> Não responda este E-Mail!<br>
A comunicação deve ser realizada pelo sistema SiSCanT </i>";

$mail_envia->Body = $mensagem;
#Envio da Mensagem
$enviado = $mail_envia->Send();
#Limpa os destinatários e os anexos
$mail_envia->ClearAllRecipients();
$mail_envia->ClearAttachments();
#Exibe uma mensagem de resultado

if ($enviado) {
    $mensagem_formatada = str_replace("'", "`", $mensagem);
    $alteracao = "E-Mail resposta de suporte enviado para CPF: $cpf_requerente e E-Mail: " . $mail;

    $insere_log = $conexao->insere_log($_SESSION['id_usuario'], $cpf_requerente, $id_suporte, "14116", "mail", "Insert", $alteracao, $mensagem_formatada);
}

if (!$enviado) {
    $detalhe_erro = print_r(error_get_last());
    $alteracao = "ERRO! E-Mail de suporte NÃO enviado para CPF: $cpf_requerente e E-Mail: " . $mail;
    $insere_log = $conexao->insere_log($_SESSION['id_usuario'], $cpf_requerente, $id_suporte, "21102", "mail", "Insert", $alteracao, $detalhe_erro);
}
