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
$mail_envia->Password = 'EMHFXQMNPYQMAWYD'; // Sua senha SMTP
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


$mail_envia->Subject = "SiSCanT - Suporte ao Candidato"; // Assunto da mensagem

// Conteúdo do e-mail
$mail_envia->Body = utf8_decode('        
        <!doctype html>
        <html lang="en">
            <head>
                <meta charset="utf-8">
            </head>
            <body style="background-color: #bdc3c7; font-family:Arial, Helvetica, sans-serif;">
                <div>
                    <div style="width: 600px; background-color: white; padding: 20px; border-radius: 5px;">
                        Prezado ' . $nome_completo_requerente . '.<br>
                        Sua dúvida encaminhada através do SiSCanT foi respondida.
                            <i>    
                                <p style="width: 97%; background-color: green; font-size: 20px; color: white; padding: 10px; border-radius: 10px; text-align:justify;">
                                
                                    <b>Data de envio:</b> ' . trata_data($data_enviado_requerente) . ' <br>
                                    <b>Mensagem enviada:</b> ' . $mensagem_requerente . '<br><br>

                                    <b>Data Resposta: </b> ' .  trata_data($datetime) . '<br>
                                    <b>Resposta: </b> ' . $resposta . '<br></b></b>
                                </p>
                            </i>
                        Não responda a este email pois sua mensagem não será visualizada. A comunicação deve ser feita através do SiSCanT.
                        
                        <center>
                            <br><br><br>
                            <p>Sistema de Seleção de Candidatos Temporários (SiSCanT)</p>
                        </center>
                    </div>
                </div>
            </body>
        </html>
    ');


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
