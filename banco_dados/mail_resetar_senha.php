
<?php
/**
 * @author Asp Volpato
 * 2.0v - 08/06/2022 - recuperação de senha da vpn
 */
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

$resultado_selecao = $conexao->get_selecao_id();

include_once '../envia_carta/src/Exception.php';
include_once '../envia_carta/src/PHPMailer.php';
include_once '../envia_carta/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

$randomString = 'r@nd0m$tring';

//Create a new PHPMailer instance
$mail = new PHPMailer();
//Tell PHPMailer to use SMTP
$mail->SMTPDebug = 0;

$mail->SMTPOptions = array(
    'ssl' => array(
        'verify_peer' => false,
        'verify_peer_name' => true,
        'allow_self_signed' => true
    )
);

try {
    $mail->isSMTP();
    $mail->Host = 'smtp.webmail.eb.mil.br'; // Servidor SMTP
    $mail->SMTPAuth = true;
    $mail->Username = 'siscant@3rm.eb.mil.br'; // Seu usuário SMTP
    $mail->Password = 'EMHFXQMNPYQMAWYD'; // Sua senha SMTP
    $mail->SMTPSecure = 'tls';  //'tls' Define o tipo de criptografia para TLS
    $mail->Port = 587; // Porta TCP para TLS

    $mail->From = 'siscant@3rm.eb.mil.br'; //Set who the message is to be sent from
    $mail->FromName = utf8_decode('Não responda - Comando 3ª RM'); //Nome do Remetente
    $mail->Subject = 'SiSCanT - Solicitacao de Reset de Senha';                       // Assunto do e-mail

    // Configurações do remetente e destinatário    

    $mail->setFrom('siscant@3rm.eb.mil.br', 'Servico Militar');        // Remetente
    //$mail->addAddress('siscant@3rm.eb.mil.br'); // Adiciona um destinatário

    $mail->AddAddress($mail_usuario, $nome_completo);

    // Definir como HTML
    $mail->isHTML(true);

    // Conteúdo do e-mail
    $mail->Body = utf8_decode('        
        <!doctype html>
        <html lang="en">
            <head>
                <meta charset="utf-8">
            </head>
            <body style="background-color: #bdc3c7; font-family:Arial, Helvetica, sans-serif;">
                <table class="container-table" width="100%" cellpadding="0" cellspacing="0" border="0" style="max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                        <!-- Header -->
                        <tr>
                            <td class="header-cell" style="background-color: green; padding: 25px 20px; text-align: center; color: #ffffff;">
                                <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                    <tr>
                                        <td style="text-align: center;">
                                                 <h1 style="font-size: 22px; margin: 0 0 5px 0; font-weight: bold;">ALTERAÇÃO DE SENHA</h1>
                                            <p style="font-size: 14px; margin: 0; opacity: 0.9;">Sistema de Seleção de Candidatos Temporários</p>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>

                        <tr>
                        <td style="padding: 30px 25px;">
                            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                <!-- Greeting -->
                                <tr>
                                    <td style="padding-bottom: 20px;">
                                        <p style="font-size: 16px; color: #333333; margin: 0;">
                                            Prezado(a) <strong>' . $nome_completo_requerente . '</strong>,
                                        </p>
                                    </td>
                                </tr>
                                
                                <!-- Intro -->
                                <tr>
                                    <td style="padding-bottom: 25px;">
                                        <p style="font-size: 14px; color: #555555; margin: 0;">
                                            Sua solicitação de alteração de senha foi recebida. Sua nova senha temporária é:
                                        </p>
                                    </td>
                                </tr>

                                <tr>
                                    <td style="padding-bottom: 25px;">
                                        <center>
                                            <p style="width: 30%; background-color: green; font-size: 20px; color: white; padding: 10px; border-radius: 10px; text-align: center;">
                                                <b>' . $nova_senha . '</b>
                                            </p>
                                        </center>
                                    </td>
                                </tr>

                                <tr>
                                    <td style="padding-bottom: 25px;">
                                        <p style="font-size: 14px; color: #555555; margin: 0;">
                                            Caso você não tenha feito esta solicitação recomendamos que avise a Comissão de Seleção e ignore este email.
                                        </p>
                                    </td>
                                </tr>

                                <!-- Warning -->
                                <tr>
                                    <td>
                                        <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color: #fff3cd; border: 1px solid #ffeaa7; border-radius: 4px;">
                                            <tr>
                                                <td style="padding: 15px; text-align: center;">
                                                    <p style="font-size: 13px; color: #856404; margin: 0;">
                                                        <strong>IMPORTANTE:</strong> Não responda a este email, pois sua mensagem não será visualizada. 
                                                        A comunicação deve ser feita exclusivamente através do SiSCanT.
                                                    </p>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </body>
        </html>
    ');

    $mail->SMTPDebug = 0;
    $enviado = $mail->send();

    if (!$enviado) {
        echo 'Erro ao enviar o email: ' . $mail->ErrorInfo;
    } else {
        echo 'Email enviado com sucesso!';
    }

    if ($enviado) {
        $foi_enviado_email = true;
    } else {
        echo 'Falha ao enviar mensagem: ' . $mail->ErrorInfo;
    }
} catch (Exception $e) {
    echo "Erro ao enaviar mensagem: {$mail->ErrorInfo}";
}


    //Set the hostname of the mail server
    // $mail->Host = 'lugia.1cta.eb.mil.br';
    //Set the SMTP port number - likely to be 25, 465 or 587
    // $mail->Port = 25;
    //Set who the message is to be sent to

    /* $mensagem = 
   
                "
                <b>".  mb_strtoupper($resultado_selecao[0]['nome'], 'UTF-8'). " - ".$resultado_selecao[0]['ano']."</b><br><br>
                    
                ".$nome.", a sua senha do SiSCanT foi resetada!<br><br>

                <b>Usuário: </b>".$cpf_candidato."<br>
                <b>Nova senha: </b>".$nova_senha."<br><br>";

                if($candidato == 1)
                $mensagem = $mensagem . "
                <i> Não responda este E-Mail!<br>
                A comunicação deve ser realizada pelo sistema SiSCanT </i>";

                $mail->Body = $mensagem;
              
                #Envio da Mensagem
                $enviado = $mail->Send();

                #Limpa os destinatários e os anexos
                $mail->ClearAllRecipients();
                $mail->ClearAttachments();
                #Exibe uma mensagem de resultado
            
                if($enviado)
                {
                    $foi_enviado_email = true;
                    $mensagem_formatada = str_replace("'","`",$mensagem);
                    $alteracao = "Senha resetada e enviada para $nome, E-Mail: $mail_usuario, CPF: $cpf_candidato";
                    $insere_log = $conexao->insere_log($_SESSION['id_usuario'], $cpf_candidato, null, "14117", "mail", "Insert", $alteracao, $mensagem_formatada);
                }

               /* else
                {
                    $alteracao = "ERRO! E-Mail de reset de senha não foi enviado para $nome, E-Mail: $mail_usuario, CPF: $cpf_candidato";
                    $insere_log = $conexao->insere_log($_SESSION['id_usuario'], $cpf_candidato, null, "21101", "mail", "Insert", $alteracao, null);
                }

  // Replace the plain text body with one created manually
    $mail->Body = utf8_decode('        
        <!doctype html>
        <html lang="en">
            <head>
                <meta charset="utf-8">
            </head>
            <body style="background-color: #bdc3c7; font-family:Arial, Helvetica, sans-serif;">
                <div style="padding: 20px;">
                    <div style="width: 600px; background-color: white; padding: 20px; border-radius: 5px;">
                        Prezado.<br>
                        O código para a recuperação de senha é: <br>
                        <center><p style="width: 15%; background-color: green; font-size: 20px; color: white; padding: 10px; border-radius: 10px; text-align: center;">
                            <b>'.$randomString.'</b>
                        </p></center>
                        Caso você não tenha feito esta solicitação recomendamos que avise nossa Central de Serviços e ignore este email.
                        <center>
                        <br><br><br>
                            <p>1º Centro de Telemática de Área</p>
                            <img style="width: 50px;" src="https://www.1cta.eb.mil.br/images/phocagallery/1cta.png" alt="1º CTA">
                        </center>
                    </div>
                </div>
            </body>
        </html>
    ');

    //Corpo da mensagem em texto
    $mail->AltBody = utf8_decode('Comando da 3ª Região Militar');

    //send the message, check for errors
    $ret = $mail->Send();

    
               // var_dump($ret);
              // exit;
    // $ret = true;
    if ($ret) {
        return ['status' => 'success', 'msg' => 'E-mail enviado com sucesso.'];
    } else {
       echo "ERRO!";
        exit;
       saveLog('sendmail error: ' . $mail->ErrorInfo);
        return ['status' => 'danger', 'msg' => 'Erro ao aa email. Favor, informe nossa central de serviços.<br>' . $mail->ErrorInfo];
    }

 

